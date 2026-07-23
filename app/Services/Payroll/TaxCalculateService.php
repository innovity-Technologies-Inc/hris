<?php

namespace App\Services\Payroll;

use App\Models\Employee\Employee;
use App\Models\Payroll\TaxPolicy;
use App\Models\Payroll\TaxCalculation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use DaiyanMozumder\LaravelFlexSearch\FlexSearch;

class TaxCalculateService
{
    /**
     * Search results using FlexSearch.
     */
    public function searchResult($request, $modelClass, FlexSearch $flexSearch, $paginate = true)
    {
        $query = $modelClass::with(['employee', 'policy']);

        // Filter by keyword (employee name, applicant_id, system_id)
        if ($request->filled('keyword')) {
            $keyword = $request->input('keyword');
            $query->whereHas('employee', function ($q) use ($keyword) {
                $q->where('full_name', 'like', "%{$keyword}%")
                  ->orWhere('applicant_id', 'like', "%{$keyword}%")
                  ->orWhere('system_id', 'like', "%{$keyword}%");
            });
        }

        // Organizational Search Filters
        if ($request->filled('company')) {
            $query->whereHas('employee.officeInfo', function($q) use ($request) {
                $q->where('current_company_id', $request->input('company'));
            });
        }

        if ($request->filled('business_unit')) {
            $query->whereHas('employee.officeInfo', function($q) use ($request) {
                $q->where('current_business_unit_id', $request->input('business_unit'));
            });
        }

        if ($request->filled('division')) {
            $query->whereHas('employee.officeInfo', function($q) use ($request) {
                $q->where('current_division_id', $request->input('division'));
            });
        }

        if ($request->filled('department')) {
            $query->whereHas('employee.officeInfo', function($q) use ($request) {
                $q->where('current_department_id', $request->input('department'));
            });
        }

        if ($request->filled('section')) {
            $query->whereHas('employee.officeInfo', function($q) use ($request) {
                $q->where('current_section_id', $request->input('section'));
            });
        }

        if ($paginate) {
            return $query->paginate(15);
        }

        return $query->get();
    }

    /**
     * Calculate tax for all active employees.
     */
    public function calculateTaxForAllEmployees(): void
    {
        Log::info('TaxCalculateService: Beginning tax calculations for all employees.');

        // Get the active tax policy
        $policy = TaxPolicy::with('slabs')->first();
        if (!$policy) {
            Log::warning('TaxCalculateService: No Tax Policy configured.');
            return;
        }

        // Get all active employees with salary breakdowns
        $employees = Employee::has('salary')->where('status', 'active')->get();

        foreach ($employees as $employee) {
            try {
                $result = $this->calculateTaxForEmployee($employee, $policy);
                if ($result) {
                    TaxCalculation::updateOrCreate(
                        ['employee_id' => $employee->id],
                        [
                            'policy_id' => $policy->id,
                            'gross_salary' => $result['gross_salary'],
                            'exemption_amount' => $result['exemption_amount'],
                            'taxable_amount' => $result['taxable_amount'],
                            'slab_taxes' => $result['slab_taxes'],
                            'slabs_reached' => $result['slabs_reached'],
                            'total_tax_amount' => $result['total_tax_amount'],
                            'tax_payable' => $result['tax_payable'],
                            'tax_per_month' => $result['tax_per_month'],
                        ]
                    );
                }
            } catch (\Exception $e) {
                Log::error('TaxCalculateService: Failed to calculate tax for employee.', [
                    'employee_id' => $employee->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        Log::info('TaxCalculateService: Completed tax calculations.');
    }

    /**
     * Calculate tax details for a single employee based on active policy.
     */
    public function calculateTaxForEmployee(Employee $employee, TaxPolicy $policy): ?array
    {
        $salaryBreakdown = $employee->salary;
        if (!$salaryBreakdown) {
            return null;
        }

        $totalTaxMonth = (int) ($policy->total_tax_month ?? 12);
        $monthlyGross = (double) $salaryBreakdown->gross_salary;
        $annualGross = $monthlyGross * $totalTaxMonth;

        // Based on the gender, check if they have more income than the zero tax limit
        $gender = strtolower($employee->gender ?? 'male');
        $zeroTaxLimit = ($gender === 'female') ? (double) $policy->zero_tax_female : (double) $policy->zero_tax_male;

        if ($annualGross <= $zeroTaxLimit) {
            return [
                'gross_salary' => $annualGross,
                'exemption_amount' => 0.00,
                'taxable_amount' => 0.00,
                'slab_taxes' => [],
                'slabs_reached' => 0,
                'total_tax_amount' => 0.00,
                'tax_payable' => 0.00,
                'tax_per_month' => 0.00,
            ];
        }

        // Exemption policy logic
        $exemptionAmount = 0.00;
        if ($policy->exemption_type === 'fixed') {
            // Find salary ratio limit (e.g. 1/3, 2/3)
            $ratioStr = $policy->salary_ratio;
            $ratio = 0.0;
            if ($ratioStr && strpos($ratioStr, '/') !== false) {
                list($num, $den) = explode('/', $ratioStr);
                if ((double)$den > 0) {
                    $ratio = (double)$num / (double)$den;
                }
            }
            // Check fixed exempt amount
            $fixedAmount = (double) $policy->fixed_amount;
            
            // Exemption is smaller of fixed exempt amount and gross salary * salary ratio limit
            $ratioAmount = $annualGross * $ratio;
            $exemptionAmount = min($fixedAmount, $ratioAmount);
        } else {
            // For exempt allowances type: addition of these allowances for the employee
            $allowancesSum = 0.00;
            $exemptAllowances = $policy->exempt_allowances ?? [];
            foreach ($exemptAllowances as $allowanceField) {
                if (isset($salaryBreakdown->$allowanceField)) {
                    $allowancesSum += (double) $salaryBreakdown->$allowanceField;
                }
            }
            // allowances are monthly in salary breakdown, so multiply by 12 for annual exemption
            $exemptionAmount = $allowancesSum * 12;
        }

        // Taxable amount = gross salary - tax exemption amount
        $taxableAmount = max(0.00, $annualGross - $exemptionAmount);

        // Calculate tax based on progressive slabs
        $slabs = $policy->slabs; // Ordered by ID asc (which is entry order)
        
        $totalTax = 0.00;
        $remainingTaxable = $taxableAmount;
        $slabTaxes = [];
        $slabsReached = 0;

        foreach ($slabs as $index => $slab) {
            if ($remainingTaxable <= 0) {
                break;
            }

            $slabsReached++;
            $percentage = (double) $slab->tax_percentage;
            
            // check if last slab (taxable_amount is null)
            if (is_null($slab->taxable_amount)) {
                $slabTax = $remainingTaxable * ($percentage / 100);
                $totalTax += $slabTax;
                $slabTaxes[] = [
                    'slab_index' => $index + 1,
                    'percentage' => $percentage,
                    'taxable_limit' => 'Unlimited',
                    'taxed_amount' => round($slabTax, 2)
                ];
                $remainingTaxable = 0;
                break;
            } else {
                $slabLimit = (double) $slab->taxable_amount;
                if ($remainingTaxable > $slabLimit) {
                    $slabTax = $slabLimit * ($percentage / 100);
                    $totalTax += $slabTax;
                    $remainingTaxable -= $slabLimit;
                    $slabTaxes[] = [
                        'slab_index' => $index + 1,
                        'percentage' => $percentage,
                        'taxable_limit' => $slabLimit,
                        'taxed_amount' => round($slabTax, 2)
                    ];
                } else {
                    // remainingTaxable <= slabLimit
                    $slabTax = $remainingTaxable * ($percentage / 100);
                    $totalTax += $slabTax;
                    $slabTaxes[] = [
                        'slab_index' => $index + 1,
                        'percentage' => $percentage,
                        'taxable_limit' => $slabLimit,
                        'taxed_amount' => round($slabTax, 2)
                    ];
                    $remainingTaxable = 0;
                    break;
                }
            }
        }

        // Calculate Tax Payable based on Minimum Negotiable Tax Limit and Tax Payable Percentage
        $minNegotiableTax = (double) $policy->min_negotiable_tax_limit;
        $taxPayablePct = (double) $policy->tax_payable_percentage;

        if ($totalTax > $minNegotiableTax) {
            $taxPayable = $totalTax * ($taxPayablePct / 100);
        } else {
            $taxPayable = $totalTax;
        }

        $taxPerMonth = $taxPayable / $totalTaxMonth;

        return [
            'gross_salary' => $annualGross,
            'exemption_amount' => $exemptionAmount,
            'taxable_amount' => $taxableAmount,
            'slab_taxes' => $slabTaxes,
            'slabs_reached' => $slabsReached,
            'total_tax_amount' => $totalTax,
            'tax_payable' => $taxPayable,
            'tax_per_month' => $taxPerMonth,
        ];
    }
}
