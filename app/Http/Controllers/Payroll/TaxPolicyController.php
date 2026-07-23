<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payroll\StoreTaxPolicyRequest;
use App\Models\Payroll\TaxPolicy;
use App\Services\Payroll\TaxPolicyService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TaxPolicyController extends Controller
{
    use ApiResponse;

    protected $taxPolicyService;

    public function __construct(TaxPolicyService $taxPolicyService)
    {
        $this->taxPolicyService = $taxPolicyService;
    }

    /**
     * Get the allowance fields mapping for the dropdown.
     */
    private function getAllowanceMapping(): array
    {
        return [
            'house_allowance' => 'House Allowance',
            'transport_allowance' => 'Transport Allowance',
            'food_allowance' => 'Food Allowance',
            'medical_allowance' => 'Medical Allowance',
            'other_earnings' => 'Other Earnings',
        ];
    }

    /**
     * Display the single tax policy configuration form.
     */
    public function index(Request $request)
    {
        $title = 'Tax Policy Configuration';
        $section = 'Finance';
        $sub_section = 'Tax Policy';

        // Load the first available policy or initialize/save a default one
        $policy = TaxPolicy::with('slabs')->first();
        if (!$policy) {
            $policy = TaxPolicy::create([
                'zero_tax_male' => 350000.00,
                'zero_tax_female' => 400000.00,
                'min_tax_amount' => 5000.00,
                'exemption_type' => 'fixed',
                'salary_ratio' => '1/3',
                'fixed_amount' => 120000.00,
                'min_negotiable_tax_limit' => 50000.00,
                'tax_payable_percentage' => 80.00,
                'total_tax_month' => 12,
            ]);
            
            // Create default slabs
            $policy->slabs()->createMany([
                ['taxable_amount' => 300000.00, 'tax_percentage' => 0.00, 'tax_amount' => 0.00],
                ['taxable_amount' => 100000.00, 'tax_percentage' => 5.00, 'tax_amount' => 5000.00],
                ['taxable_amount' => null, 'tax_percentage' => 10.00, 'tax_amount' => 0.00],
            ]);
            
            $policy->load('slabs');
        }

        $allowanceMapping = $this->getAllowanceMapping();

        return view('payroll.tax_policy.create', compact('title', 'section', 'sub_section', 'policy', 'allowanceMapping'));
    }

    /**
     * Update the specified tax policy configuration.
     */
    public function update(StoreTaxPolicyRequest $request, $id)
    {
        try {
            $policy = $this->taxPolicyService->saveTaxPolicy($request->validated(), $id);
            return $this->successResponse('Tax Policy updated successfully.', [
                'redirect_url' => route('tax-policy.index')
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to update tax policy.', ['error' => $e->getMessage()]);
            return $this->errorResponse('Failed to update Tax Policy: ' . $e->getMessage());
        }
    }
}
