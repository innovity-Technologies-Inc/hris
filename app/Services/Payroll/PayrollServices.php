<?php

namespace App\Services\Payroll;

use App\Models\Attendance\Attendance;
use App\Models\Plan\BonusPlan;
use App\Models\Plan\DeductionPlan;
use App\Models\Employee\Employee;
use App\Models\Employee\EmployeeOffdayPlan;
use App\Models\Employee\EmployeeOfficeInfo;
use App\Models\Employee\EmployeeOtPlan;
use App\Models\Employee\EmployeeSalaryBreakdown;
use App\Models\Company\Holiday;
use App\Models\Leave\Leave;
use App\Models\Payroll\AdvanceSalary;
use App\Models\Payroll\Arrear;
use App\Models\Payroll\Bonus;
use App\Models\Payroll\Increment;
use App\Models\Payroll\Decrement;
use App\Models\Payroll\Promotion;
use App\Models\Payroll\Demotion;
use App\Models\Payroll\Payroll;
use App\Models\Payroll\PayrollProcess;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class PayrollServices
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function advanceProcess($data, $processId = null)
    {
        try {
            Log::info('Initiating advance salary process.', ['data' => $data, 'process_id' => $processId]);
            
            $payGroup = \App\Models\Company\PayGroup::findOrFail($data['pay_group_id']);
            $frequency = strtolower($payGroup->payroll_frequency);
            
            $deduction_month = $data['deduction_month'];
            $amount_type = $data['amount_type'];
            $amount_value = $data['amount_value'];
            $percentage_base = $data['percentage_base'] ?? 'gross_salary';
            $reason = $data['reason'] ?? '';

            if ($frequency === 'monthly') {
                $salary_month = $data['salary_month'];
                $effectiveDate = Carbon::createFromFormat('Y-m', $salary_month)->startOfMonth();
                $startDate = $effectiveDate->copy();
                $endDate = $effectiveDate->copy()->endOfMonth();
            } else {
                $startDate = Carbon::parse($data['start_date']);
                $endDate = Carbon::parse($data['end_date']);
                $salary_month = $startDate->format('Y-m');
                $effectiveDate = $startDate;
                $data['salary_month'] = $salary_month;
            }

            Log::info('Target period and frequency for advance.', [
                'frequency' => $frequency,
                'salary_month' => $salary_month,
                'start_date' => $startDate->toDateString(),
                'end_date' => $endDate->toDateString()
            ]);

            $employees = $this->findEmployees($data, $effectiveDate);
            Log::info('Eligible employees found for advance.', ['count' => $employees->count()]);
            
            $total_advance = 0;
            $employeeData = [];
            
            foreach ($employees as $employee) {
                try {
                    $employeeSalary = EmployeeSalaryBreakdown::where('employee_id', $employee->id)->first();
                    if (!$employeeSalary) {
                        Log::warning('Skipping employee: Salary breakdown missing.', ['employee_id' => $employee->id]);
                        continue;
                    }

                    $calculated_amount = 0;
                    if ($amount_type === 'fixed') {
                        $calculated_amount = $amount_value;
                    } else {
                        $base_salary = ($percentage_base === 'basic_salary') ? $employeeSalary->basic_salary : $employeeSalary->gross_salary;
                        $calculated_amount = ($base_salary * $amount_value) / 100;
                    }

                    if ($calculated_amount > 0) {
                        Log::info('Calculated advance for employee.', [
                            'employee_id' => $employee->id,
                            'amount' => $calculated_amount
                        ]);
                        $total_advance += $calculated_amount;
                        $employeeData[] = [
                            'employee_id' => $employee->id,
                            'amount' => $calculated_amount,
                            'deduction_month' => $deduction_month,
                            'reason' => $reason,
                        ];
                    }
                } catch (\Exception $innerEx) {
                    Log::error('Error processing individual employee advance.', [
                        'employee_id' => $employee->id,
                        'error' => $innerEx->getMessage()
                    ]);
                }
            }
            
            $total_employees = count($employeeData);
            if ($total_employees == 0) {
                Log::warning('No advance records could be generated.', ['data' => $data]);
                throw new \Exception('No eligible employees found for advance salary generation with provided criteria.');
            }

            Log::info('Consolidated advance summary.', [
                'total_employees' => $total_employees,
                'total_amount' => $total_advance
            ]);

            $process = null;
            DB::transaction(function () use ($data, $employeeData, $total_advance, $total_employees, $processId, $salary_month, $startDate, $endDate, &$process) {
                if ($processId == null) {
                    $process = PayrollProcess::create([
                        'batch_id' => uniqid('Advance_', true),
                        'company_id' => $data['company_id'],
                        'branch_id' => $data['branch_id'],
                        'division_id' => $data['division_id'],
                        'department_id' => $data['department_id'],
                        'section_id' => $data['section_id'],
                        'pay_group_id' => $data['pay_group_id'],
                        'salary_month' => $salary_month,
                        'start_date' => $startDate,
                        'end_date' => $endDate,
                        'type' => 'advance',
                        'total_amount' => $total_advance,
                        'generated_by' => Auth::id() ?? null,
                        'total_employee' => $total_employees,
                    ]);
                    Log::info('New Advance Payroll Process record created.', ['process_id' => $process->id]);
                } else {
                    $process = PayrollProcess::findOrFail($processId);
                    $process->update([
                        'company_id' => $data['company_id'],
                        'branch_id' => $data['branch_id'],
                        'division_id' => $data['division_id'],
                        'department_id' => $data['department_id'],
                        'section_id' => $data['section_id'],
                        'pay_group_id' => $data['pay_group_id'],
                        'salary_month' => $salary_month,
                        'start_date' => $startDate,
                        'end_date' => $endDate,
                        'total_amount' => $total_advance,
                        'generated_by' => Auth::id() ?? null,
                        'total_employee' => $total_employees,
                    ]);
                    // Clear old items
                    AdvanceSalary::where('process_id', $processId)->delete();
                    Log::info('Existing Advance Payroll Process updated.', ['process_id' => $processId]);
                }

                foreach ($employeeData as $employee) {
                    AdvanceSalary::create([
                        'process_id' => $process->id,
                        'batch_id' => $process->batch_id,
                        'employee_id' => $employee['employee_id'],
                        'amount' => $employee['amount'],
                        'deduction_month' => $employee['deduction_month'],
                        'reason' => $employee['reason'],
                        'status' => 'pending',
                    ]);
                }
            });

            Log::info('Advance salary process database transaction committed.');
            return $process;

        } catch (\Exception $e) {
            Log::error('Advance salary processing failure in Service layer.', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    public function advanceDelete($id)
    {
        Log::info('Deleting Advance Salary Process and related items.', ['process_id' => $id]);
        try {
            $process = PayrollProcess::findOrFail($id);
            DB::transaction(function () use ($process, $id) {
                AdvanceSalary::where('process_id', $id)->delete();
                $process->delete();
            });
            Log::info('Advance Salary Process and items deleted successfully.', ['process_id' => $id]);
        } catch (\Exception $e) {
            Log::error('Failed to delete Advance Salary Process.', [
                'process_id' => $id,
                'message' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    public function promotionRequestData($request)
    {
        $data = [
            'employee_id' => $request->employee_id,
            'pay_scale_id' => $request->pay_scale_id,
            'movement_type_id' => $request->movement_type_id,
            'previous_designation' => $request->previous_designation,
            'new_designation' => $request->new_designation,
            'increment_base' => $request->increment_base,
            'increment_method' => $request->increment_method,
            'salary_increase_amount' => $request->salary_increase_amount,
            'effective_from' => $request->effective_from,
            'effective_to' => $request->effective_to,
        ];

        $result = $this->salaryData($data);

        return [
            'data' => $result['data'],
        ];
    }

    public function salaryData($data)
    {
        $employeeSalary = EmployeeSalaryBreakdown::where('employee_id', $data['employee_id'])->first();
        $increment_result = $this->incrementCalculation($data, $employeeSalary);
        $data['new_gross_salary'] = $increment_result['new_gross_salary'];
        $data['increment_amount_value'] = $increment_result['increment_value'];
        $data['previous_basic_salary'] = $employeeSalary->basic_salary;
        $data['previous_gross_salary'] = $employeeSalary->gross_salary;
        return [
            'data' => $data,
        ];
    }

    public function incrementCalculation($data, $employeeSalary)
    {
        $incrementBase = $data['increment_base'];
        $incrementMethod = $data['increment_method'];
        $incrementAmount = $data['salary_increase_amount'];
        $basicSalary = $employeeSalary->basic_salary;
        $grossSalary = $employeeSalary->gross_salary;
        if ($incrementBase == 'basic_salary') {
            if ($incrementMethod == 'percentage') {
                $incrementValue = $basicSalary * ($incrementAmount / 100);
            } else {
                $incrementValue = $incrementAmount;
            }
        } else {
            if ($incrementMethod == 'percentage') {
                $incrementValue = $grossSalary * ($incrementAmount / 100);
            } else {
                $incrementValue = $incrementAmount;
            }
        }

        $newGrossSalary = $grossSalary + $incrementValue;

        return [
            'new_gross_salary' => $newGrossSalary,
            'increment_value' => $incrementValue,
        ];
    }

    public function incrementRequestData($request)
    {
        $data = [
            'employee_id' => $request->employee_id,
            'pay_scale_id' => $request->pay_scale_id,
            'movement_type_id' => $request->movement_type_id,
            'increment_base' => $request->increment_base,
            'increment_method' => $request->increment_method,
            'salary_increase_amount' => $request->salary_increase_amount,
            'effective_from' => $request->effective_from,
            'effective_to' => $request->effective_to,
        ];

        $result = $this->salaryData($data);

        return [
            'data' => $result['data'],
        ];
    }

    protected function handleAttachments($model, $folder)
    {
        if (request()->hasFile('attachments')) {
            foreach (request()->file('attachments') as $file) {
                $path = $file->store($folder, 'public');
                $model->attachments()->create([
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                ]);
            }
        }
    }

    public function promotionDataStore($data)
    {
        $model = Promotion::create($data);
        $this->handleAttachments($model, 'promotions');
        return $model;
    }

    public function promotionDataUpdate($id, $data)
    {
        $promotion = Promotion::find($id);
        $promotion->update($data);
        $this->handleAttachments($promotion, 'promotions');
        return $promotion;
    }

    public function incrementDataStore($data)
    {
        $model = Increment::create($data);
        $this->handleAttachments($model, 'increments');
        return $model;
    }

    public function incrementDataUpdate($id, $data)
    {
        $increment = Increment::find($id);
        $increment->update($data);
        $this->handleAttachments($increment, 'increments');
        return $increment;
    }

    public function decrementRequestData($request)
    {
        $data = [
            'employee_id' => $request->employee_id,
            'pay_scale_id' => $request->pay_scale_id,
            'movement_type_id' => $request->movement_type_id,
            'decrement_base' => $request->decrement_base,
            'decrement_method' => $request->decrement_method,
            'salary_decrease_amount' => $request->salary_decrease_amount,
            'effective_from' => $request->effective_from,
            'effective_to' => $request->effective_to,
        ];

        $result = $this->decrementSalaryData($data);

        return [
            'data' => $result['data'],
        ];
    }

    public function demotionRequestData($request)
    {
        $data = [
            'employee_id' => $request->employee_id,
            'pay_scale_id' => $request->pay_scale_id,
            'movement_type_id' => $request->movement_type_id,
            'previous_designation' => $request->previous_designation,
            'new_designation' => $request->new_designation,
            'decrement_base' => $request->decrement_base,
            'decrement_method' => $request->decrement_method,
            'salary_decrease_amount' => $request->salary_decrease_amount,
            'effective_from' => $request->effective_from,
            'effective_to' => $request->effective_to,
        ];

        $result = $this->decrementSalaryData($data);

        return [
            'data' => $result['data'],
        ];
    }

    public function decrementSalaryData($data)
    {
        $employeeSalary = EmployeeSalaryBreakdown::where('employee_id', $data['employee_id'])->first();
        $decrement_result = $this->decrementCalculation($data, $employeeSalary);
        $data['new_gross_salary'] = $decrement_result['new_gross_salary'];
        $data['decrement_amount_value'] = $decrement_result['decrement_value'];
        $data['previous_basic_salary'] = $employeeSalary->basic_salary;
        $data['previous_gross_salary'] = $employeeSalary->gross_salary;
        return [
            'data' => $data,
        ];
    }

    public function decrementCalculation($data, $employeeSalary)
    {
        $decrementBase = $data['decrement_base'];
        $decrementMethod = $data['decrement_method'];
        $decrementAmount = $data['salary_decrease_amount'];
        $basicSalary = $employeeSalary->basic_salary;
        $grossSalary = $employeeSalary->gross_salary;
        if ($decrementBase == 'basic_salary') {
            if ($decrementMethod == 'percentage') {
                $decrementValue = $basicSalary * ($decrementAmount / 100);
            } else {
                $decrementValue = $decrementAmount;
            }
        } else {
            if ($decrementMethod == 'percentage') {
                $decrementValue = $grossSalary * ($decrementAmount / 100);
            } else {
                $decrementValue = $decrementAmount;
            }
        }

        $newGrossSalary = max(0, $grossSalary - $decrementValue);

        return [
            'new_gross_salary' => $newGrossSalary,
            'decrement_value' => $decrementValue,
        ];
    }

    public function decrementDataStore($data)
    {
        $model = Decrement::create($data);
        $this->handleAttachments($model, 'decrements');
        return $model;
    }

    public function decrementDataUpdate($id, $data)
    {
        $decrement = Decrement::find($id);
        $decrement->update($data);
        $this->handleAttachments($decrement, 'decrements');
        return $decrement;
    }

    public function demotionDataStore($data)
    {
        $model = Demotion::create($data);
        $this->handleAttachments($model, 'demotions');
        return $model;
    }

    public function demotionDataUpdate($id, $data)
    {
        $demotion = Demotion::find($id);
        $demotion->update($data);
        $this->handleAttachments($demotion, 'demotions');
        return $demotion;
    }

    public function salaryCalculation($data1, $data2)
    {
        return $data1 * ($data2 / 100);
    }

    public function updateSalaryData($data)
    {
        $employee_id = $data->employee_id;
        $salaryData = EmployeeSalaryBreakdown::where('employee_id', $employee_id)->first();
        $newGrossSalary = $data->new_gross_salary;
        
        $updatePayload = [
            'basic_salary' => $this->salaryCalculation($newGrossSalary, $salaryData->basic_salary_percentage),
            'house_allowance' => $this->salaryCalculation($newGrossSalary, $salaryData->house_allowance_percentage),
            'transport_allowance' => $this->salaryCalculation($newGrossSalary, $salaryData->transport_allowance_percentage),
            'food_allowance' => $this->salaryCalculation($newGrossSalary, $salaryData->food_allowance_percentage),
            'medical_allowance' => $this->salaryCalculation($newGrossSalary, $salaryData->medical_allowance_percentage),
            'other_earnings' => $this->salaryCalculation($newGrossSalary, $salaryData->other_earnings_percentage),
            'gross_salary' => $newGrossSalary
        ];

        if (!empty($data->pay_scale_id)) {
            $updatePayload['pay_scale_id'] = $data->pay_scale_id;
        }

        $salaryData->update($updatePayload);
    }

    public function designationUpdate($data)
    {
        $employee_id = $data->employee_id;
        $newDesignation = $data->new_designation;
        Log::info('Employee Designation: ' . $newDesignation);
        Log::info('Employee ID: ' . $employee_id);
        $designation = EmployeeOfficeInfo::where('employee_id', $employee_id)->first();
        $designation->update([
            'current_designation_id' => $newDesignation,
        ]);
    }

    public function payrollProcessDataValidation($request, $flag = null)
    {
        Log::info('Validating payroll process data.', [
            'request_all' => $request->all(),
            'flag' => $flag
        ]);
        $rules = [
            // Validate the arrays themselves
            'employee_id' => 'nullable',
            'company_id' => 'nullable|exists:companies,id',
            'branch_id' => 'nullable|exists:company_locations,id',
            'division_id' => 'nullable|exists:divisions,id',
            'department_id' => 'nullable|exists:departments,id',
            'section_id' => 'nullable|exists:sections,id',
        ];

        if ($flag !== 'bonus') {
            $rules['pay_group_id'] = 'required|exists:pay_groups,id';
            
            $payGroup = \App\Models\Company\PayGroup::find($request->pay_group_id);
            if ($payGroup) {
                if (strtolower($payGroup->payroll_frequency) === 'monthly') {
                    $rules['salary_month'] = 'required';
                } else {
                    $rules['start_date'] = 'required|date';
                    $rules['end_date'] = 'required|date|after_or_equal:start_date';
                }
            }
        } else {
            $rules['pay_group_id'] = 'required|exists:pay_groups,id';
            $rules['salary_month'] = 'required';
            $rules['plan_ids'] = 'required|array|min:1';
            // Validate each ID inside the arrays
            $rules['plan_ids.*'] = 'required|integer|exists:bonus_plans,id';
        }

        $messages = [
            'plan_ids.required' => 'Plan is required.',
            'plan_ids.*.required' => 'Plan is required.',
            'salary_month.required' => 'Salary Month is required.',
            'start_date.required' => 'Start Date is required.',
            'end_date.required' => 'End Date is required.',
            'pay_group_id.required' => 'Pay Group is required.',
        ];

        $validated = $request->validate($rules, $messages);
        Log::info('Data validation successful.', ['validated' => $validated]);

        // Check for duplicates
        if ($flag !== 'bonus') {
            Log::info('Checking for duplicate payroll processes.');
            $processQuery = PayrollProcess::where('company_id', $request->company_id)
                ->where('pay_group_id', $request->pay_group_id)
                ->where('type', 'salary');

            if ($request->branch_id) {
                $processQuery->where('branch_id', $request->branch_id);
            }
            if ($request->division_id) {
                $processQuery->where('division_id', $request->division_id);
            }
            if ($request->department_id) {
                $processQuery->where('department_id', $request->department_id);
            }
            if ($request->section_id) {
                $processQuery->where('section_id', $request->section_id);
            }

            if (isset($payGroup) && strtolower($payGroup->payroll_frequency) === 'monthly') {
                $processQuery->where('salary_month', $request->salary_month);
            } else {
                $processQuery->where('start_date', $request->start_date)
                             ->where('end_date', $request->end_date);
            }

            $existingProcess = $processQuery->first();

            if ($existingProcess) {
                Log::warning('Duplicate payroll process found.', ['process_id' => $existingProcess->id]);
                throw new \Exception('Salary process already exists for the selected criteria and period.');
            }
        }

        return $validated;
    }

    public function bonusCalculation($employee, $plan_ids)
    {
        Log::info('Calculating bonus for employee.', ['employee_id' => $employee->id, 'plan_ids' => $plan_ids]);
        $bonus_amount = 0;
        foreach ($plan_ids as $id) {
            // Check if employee is explicitly attached to this bonus plan
            $isAttached = \Illuminate\Support\Facades\DB::table('employee_bonus_plans')
                ->where('employee_id', $employee->id)
                ->where('plan_id', $id)
                ->exists();

            if (!$isAttached) {
                Log::info('Employee not attached to bonus plan.', ['employee_id' => $employee->id, 'plan_id' => $id]);
                continue;
            }

            $plan_data = BonusPlan::find($id);
            if ($plan_data) {
                Log::info('Calculating bonus for plan.', ['plan_name' => $plan_data->name]);
                if ($plan_data->bonus_config_type == 'Salary Based') {
                    $basic_salary = EmployeeSalaryBreakdown::where('employee_id', $employee->id)->first()->basic_salary;
                    Log::info('Basic Salary for bonus calculation.', ['employee_id' => $employee->id, 'basic_salary' => $basic_salary]);
                    if ($plan_data->salary_rate_type == 'Basic Rate') {
                        $amount = $basic_salary;
                    } else {
                        Log::info('Multiplier applied.', ['multiplier' => $plan_data->multiplier]);
                        $amount = $basic_salary * $plan_data->multiplier;
                    }
                } else {
                    $amount = $plan_data->custom_rate;
                }
                Log::info('Bonus plan amount calculated.', ['plan_name' => $plan_data->name, 'amount' => $amount]);
                $bonus_amount += $amount;
            }
        }
        Log::info('Total bonus for employee calculated.', ['employee_id' => $employee->id, 'total_bonus' => $bonus_amount]);
        return $bonus_amount;
    }

    public function findEmployees($data, $firstDayOfSalaryMonth)
    {
        Log::info('Finding eligible employees for salary.', [
            'criteria' => $data,
            'effective_date' => $firstDayOfSalaryMonth->toDateString()
        ]);
        $query = Employee::query()
            ->select('id', 'full_name', 'photo_path', 'system_id')
            ->whereHas('salary', function ($q) use ($data) {
                if (!empty($data['pay_group_id'])) {
                    $q->whereHas('payScale', function ($q2) use ($data) {
                        $q2->where('pay_group_id', $data['pay_group_id']);
                    });
                }
            })
            ->whereHas('officeInfo', function ($q) use ($data) {
                $q->where('current_company_id', $data['company_id']);

                if (!empty($data['branch_id'])) {
                    $q->where('current_business_unit_id', $data['branch_id']);
                }
                if (!empty($data['division_id'])) {
                    $q->where('current_division_id', $data['division_id']);
                }
                if (!empty($data['department_id'])) {
                    $q->where('current_department_id', $data['department_id']);
                }
                if (!empty($data['section_id'])) {
                    $q->where('current_section_id', $data['section_id']);
                }
                if (!empty($data['employee_id'])) {
                    $q->where('employee_id', $data['employee_id']);
                }
            })
            ->whereHas('employeeEligibility', function ($q) use ($firstDayOfSalaryMonth) {
                $q->where('bonus_plan_status', 'active')
                    ->whereDate('bonus_plan_from', '<=', $firstDayOfSalaryMonth);
            });

        $employees = $query->get();

        Log::info('Eligible employees count.', ['count' => $employees->count()]);

        return $employees;
    }

    public function bonusProcess($data, $processId = null)
    {
        Log::info('Initiating bonus process.', ['data' => $data, 'process_id' => $processId]);
        
        return DB::transaction(function () use ($data, $processId) {
            $salary_month = $data['salary_month'];
            $firstDayOfSalaryMonth = Carbon::parse($salary_month)->copy()->startOfMonth();
            $employees = $this->findEmployees($data, $firstDayOfSalaryMonth);
            
            $total_bonus = 0;
            $employeeData = [];
            foreach ($employees as $employee) {
                $bonus_amount = $this->bonusCalculation($employee, $data['plan_ids']);
                if ($bonus_amount > 0) {
                    $total_bonus += $bonus_amount;
                    $employeeData[] = [
                        'employee_id' => $employee->id,
                        'bonus_amount' => $bonus_amount,
                    ];
                }
            }
            
            $total_employees = count($employeeData);
            if ($total_employees == 0) {
                throw new \Exception('Eligible Employees not found for the selected Bonus Plans.');
            }

            if ($processId == null) {
                $process = PayrollProcess::create([
                    'batch_id' => uniqid('Bonus_', true),
                    'company_id' => $data['company_id'],
                    'branch_id' => $data['branch_id'],
                    'division_id' => $data['division_id'],
                    'department_id' => $data['department_id'],
                    'section_id' => $data['section_id'],
                    'pay_group_id' => $data['pay_group_id'],
                    'salary_month' => $data['salary_month'],
                    'type' => 'bonus',
                    'total_amount' => $total_bonus,
                    'generated_by' => Auth::id() ?? null,
                    'total_employee' => $total_employees,
                    'bonus_plan_ids' => $data['plan_ids'],
                ]);
            } else {
                $process = PayrollProcess::findOrFail($processId);
                $process->update([
                    'company_id' => $data['company_id'],
                    'branch_id' => $data['branch_id'],
                    'division_id' => $data['division_id'],
                    'department_id' => $data['department_id'],
                    'section_id' => $data['section_id'],
                    'pay_group_id' => $data['pay_group_id'],
                    'salary_month' => $data['salary_month'],
                    'total_amount' => $total_bonus,
                    'generated_by' => Auth::id() ?? null,
                    'total_employee' => $total_employees,
                    'bonus_plan_ids' => $data['plan_ids'],
                ]);
                Bonus::where('process_id', $processId)->delete();
            }

            foreach ($employeeData as $employee) {
                Bonus::create([
                    'process_id' => $process->id,
                    'batch_id' => $process->batch_id,
                    'employee_id' => $employee['employee_id'],
                    'amount' => $employee['bonus_amount'],
                ]);
            }
            Log::info('Bonus process completed.', ['id' => $process->id]);
            return $process;
        });
    }

    public function bonusDelete($id)
    {
        Log::info('Deleting Bonus Process.', ['process_id' => $id]);
        return DB::transaction(function () use ($id) {
            $process = PayrollProcess::findOrFail($id);
            Bonus::where('process_id', $id)->delete();
            $process->delete();
        });
    }

    public function getHolidays()
    {
        $holidays = Holiday::all();
        $holidayDates = [];

        foreach ($holidays as $holiday) {
            $period = CarbonPeriod::create(
                Carbon::parse($holiday->start_date),
                Carbon::parse($holiday->end_date)
            );
            foreach ($period as $date) {
                $holidayDates[] = $date;
            }
        }
        return collect($holidayDates)->unique();
    }

    public function findHolidaysInSalaryMonth($startDate, $endDate)
    {
        $holidays = $this->getHolidays();
        return $holidays->filter(fn($holiday) => $holiday->between($startDate, $endDate));
    }

    public function weekends($employee)
    {
        $officeInfo = EmployeeOfficeInfo::where('employee_id', $employee->id)->first();
        return $officeInfo ? ($officeInfo->weekends ?? []) : [];
    }

    public function findWeekendsInSalaryMonth($employee, $startDate, $endDate)
    {
        $weekends = $this->weekends($employee);
        $weekendDates = collect();
        $current = $startDate->copy();

        while ($current <= $endDate) {
            if (in_array($current->format('l'), $weekends)) {
                $weekendDates->push($current->copy());
            }
            $current->addDay();
        }
        return $weekendDates;
    }

    public function leaveDays($employee)
    {
        $leaves = Leave::where('employee_id', $employee->id)
            ->where('status', 'approved')
            ->get();

        $leaveDates = [];
        foreach ($leaves as $leaveDate) {
            $period = CarbonPeriod::create(
                Carbon::parse($leaveDate->from),
                Carbon::parse($leaveDate->to)
            );
            foreach ($period as $date) {
                $leaveDates[] = $date;
            }
        }
        return collect($leaveDates)->unique();
    }

    public function findLeavesInSalaryMonth($employee, $startDate, $endDate)
    {
        $leaves = $this->leaveDays($employee);
        return $leaves->filter(fn($q) => $q->between($startDate, $endDate));
    }

    public function absentCalculate($employee, $startDate, $endDate, $employeeAttendance)
    {
        Log::info('Calculating absences.', [
            'employee_id' => $employee->id,
            'start_date' => $startDate->toDateString(),
            'end_date' => $endDate->toDateString()
        ]);

        $holidaysInSalaryMonth = $this->findHolidaysInSalaryMonth($startDate, $endDate);
        $weekendsInSalaryMonth = $this->findWeekendsInSalaryMonth($employee, $startDate, $endDate);
        $leavesInSalaryMonth = $this->findLeavesInSalaryMonth($employee, $startDate, $endDate);
        $employeeAttendDate = collect($employeeAttendance)->map(fn($item) => Carbon::parse($item->in_time));

        Log::info('Attendance analysis counts.', [
            'holidays' => count($holidaysInSalaryMonth),
            'weekends' => count($weekendsInSalaryMonth),
            'leaves' => count($leavesInSalaryMonth),
            'attendance_records' => count($employeeAttendDate)
        ]);

        $safeData = $holidaysInSalaryMonth->merge($leavesInSalaryMonth)
            ->merge($weekendsInSalaryMonth)
            ->merge($employeeAttendDate)
            ->unique();
        
        $leave_count = $leavesInSalaryMonth->count();

        $absentDates = collect(CarbonPeriod::create($startDate, $endDate))
            ->map(fn($date) => $date->format('Y-m-d'))
            ->diff($safeData->map(fn($date) => $date->format('Y-m-d')))
            ->values()
            ->toArray();

        Log::info('Absence calculation result.', [
            'absent_count' => count($absentDates),
            'absent_dates' => $absentDates,
            'leave_count' => $leave_count
        ]);

        return [
            'absent_count' => count($absentDates),
            'absent_dates' => $absentDates,
            'leave_count' => $leave_count,
        ];
    }

    public function overTimeSalary($employeeId, $employeeSalary, $otAttendances, $totalMonthlyHours = 240, $workingHoursPerDay = 8, $frequency = 'monthly', $workingDays = 30)
    {
        Log::info('Calculating overtime salary.', ['employee_id' => $employeeId, 'frequency' => $frequency]);
        $overTimeSalary = 0;
        $grossSalary = $employeeSalary->gross_salary;
        
        if ($frequency === 'hourly') {
            $hourlyRate = $grossSalary;
        } elseif ($frequency === 'daily') {
            $hourlyRate = $grossSalary / ($workingHoursPerDay ?: 8);
        } else {
            $dayRate = $grossSalary / ($workingDays ?: 30);
            $hourlyRate = $dayRate / ($workingHoursPerDay ?: 8);
        }
        
        $groupedAttendances = $otAttendances->groupBy('ot_id');

        foreach ($groupedAttendances as $otId => $records) {
            if (!$otId) continue;
            $plan = \App\Models\Plan\OTPlan::find($otId);
            if (!$plan) continue;

            $overtimeCount = $records->sum('overtime');
            if ($plan->ot_config_type == 'Salary Based') {
                $rate = ($plan->salary_rate_type == 'Basic Rate') ? $hourlyRate : ($hourlyRate * $plan->overtime_multiplier);
                $amount = $rate * ($overtimeCount / 60);
            } else {
                $amount = $plan->custom_overtime_rate * ($overtimeCount / 60);
            }
            $overTimeSalary += $amount;
        }
        
        return ceil(round($overTimeSalary, 2));
    }

    public function offDayWorkSalary($employeeId, $employeeSalary, $offDayAttendances, $totalMonthlyHours = 240, $workingHoursPerDay = 8, $frequency = 'monthly', $workingDays = 30)
    {
        Log::info('Calculating off-day work salary.', ['employee_id' => $employeeId, 'frequency' => $frequency]);
        $offDayWorkSalary = 0;
        $grossSalary = $employeeSalary->gross_salary;

        if ($frequency === 'hourly') {
            $hourlyRate = $grossSalary;
        } elseif ($frequency === 'daily') {
            $hourlyRate = $grossSalary / ($workingHoursPerDay ?: 8);
        } else {
            $dayRate = $grossSalary / ($workingDays ?: 30);
            $hourlyRate = $dayRate / ($workingHoursPerDay ?: 8);
        }

        $groupedAttendances = $offDayAttendances->groupBy('offday_id');

        foreach ($groupedAttendances as $offdayId => $records) {
            if (!$offdayId) continue;
            $plan = \App\Models\Plan\OffDayPlan::find($offdayId);
            if (!$plan || $plan->type === 'comp-off') continue;

            $offDayWorkDayCount = $records->count();
            if ($plan->getShift) {
                $shiftTimeInMints = $plan->getShift->treat_as_full_day_minutes + $plan->getShift->grace_time + $plan->getShift->early_out_grace_minutes;
                $offDayWorkCount = $offDayWorkDayCount * ($shiftTimeInMints / 60); 
            } else {
                $offDayWorkCount = $offDayWorkDayCount * ($workingHoursPerDay ?: 8); 
            }

            if ($plan->offday_config_type == 'Salary Based') {
                $rate = ($plan->salary_rate_type == 'Basic Rate') ? $hourlyRate : ($hourlyRate * $plan->offday_multiplier);
                $amount = $rate * $offDayWorkCount;
            } else {
                $amount = $plan->custom_offday_rate * $offDayWorkCount;
            }
            $offDayWorkSalary += $amount;
        }

        return ceil(round($offDayWorkSalary, 2));
    }

    public function deductionAmount($lateCount, $excessiveLateCount, $earlyExitCount, $absentCount, $employeeSalary, $workingDays = 30, $frequency = 'monthly', $workingHoursPerDay = 8)
    {
        Log::info('Calculating deduction amounts.', ['frequency' => $frequency]);
        $deductionRule = DeductionPlan::first();
        if (!$deductionRule) return ['total' => 0, 'late_deduction_amount' => 0, 'excessive_late_deduction_amount' => 0, 'absent_deduction_amount' => 0, 'early_exit_deduction_amount' => 0];

        $baseForDeduction = ($deductionRule->calculation_type == 'basic_salary') ? $employeeSalary->basic_salary : $employeeSalary->gross_salary;

        if ($frequency === 'hourly') {
            $dayRate = $baseForDeduction * $workingHoursPerDay;
        } elseif ($frequency === 'daily') {
            $dayRate = $baseForDeduction;
        } else {
            $dayRate = $baseForDeduction / ($workingDays ?: 30);
        }

        $lateDeductionAmount = ($deductionRule->late_deduction_days && $lateCount >= $deductionRule->late_deduction_days) ? (intdiv($lateCount, $deductionRule->late_deduction_days) * $deductionRule->late_salary_deduction_rate * $dayRate) : 0;
        $excessiveLateDeductionAmount = ($deductionRule->excessive_late_deduction_days && $excessiveLateCount >= $deductionRule->excessive_late_deduction_days) ? (intdiv($excessiveLateCount, $deductionRule->excessive_late_deduction_days) * $deductionRule->excessive_late_salary_deduction_rate * $dayRate) : 0;
        $absentDeductionAmount = ($deductionRule->absent_deduction_days && $absentCount >= $deductionRule->absent_deduction_days) ? (intdiv($absentCount, $deductionRule->absent_deduction_days) * $deductionRule->absent_salary_deduction_rate * $dayRate) : 0;
        $earlyExitDeductionAmount = ($deductionRule->early_out_deduction_days && $earlyExitCount >= $deductionRule->early_out_deduction_days) ? (intdiv($earlyExitCount, $deductionRule->early_out_deduction_days) * $deductionRule->early_out_salary_deduction_rate * $dayRate) : 0;

        return [
            'total' => $lateDeductionAmount + $excessiveLateDeductionAmount + $absentDeductionAmount + $earlyExitDeductionAmount,
            'late_deduction_amount' => $lateDeductionAmount,
            'excessive_late_deduction_amount' => $excessiveLateDeductionAmount,
            'absent_deduction_amount' => $absentDeductionAmount,
            'early_exit_deduction_amount' => $earlyExitDeductionAmount,
        ];
    }

    public function bonusWithSalaryCalculation($employee, $employeeSalary, $salary_month)
    {
        $bonusData = Bonus::with('getBatch')->where('employee_id', $employee)
            ->whereHas('getBatch', function ($query) use ($salary_month) {
                $query->where('salary_month', $salary_month)->where('approval_status', 'approved');
            })->get();
        return $bonusData->sum('amount');
    }

    public function salaryProcess($data, $processId = null)
    {
        Log::info('Initiating salary process calculation.', ['data' => $data, 'process_id' => $processId]);

        return DB::transaction(function () use ($data, $processId) {
            if ($processId != null) {
                $this->rollbackSalaryProcess($processId);
            }

            $payGroup = \App\Models\Company\PayGroup::find($data['pay_group_id']);
            $frequency = strtolower($payGroup->payroll_frequency);
            $workingDaysPerCycle = $payGroup->working_days_per_cycle ?? 30;
            $workingHoursPerDay = $payGroup->working_hours_per_day ?? 8;
            $totalMonthlyHours = $workingDaysPerCycle * $workingHoursPerDay;

            if ($frequency === 'monthly') {
                $salary_month = $data['salary_month'];
                $effectiveDate = Carbon::createFromFormat('Y-m', $salary_month)->startOfMonth();
                $startDate = $effectiveDate->copy();
                $endDate = $effectiveDate->copy()->endOfMonth();
            } else {
                $startDate = Carbon::parse($data['start_date']);
                $endDate = Carbon::parse($data['end_date']);
                $salary_month = $startDate->format('Y-m');
                $data['salary_month'] = $salary_month;
                $effectiveDate = $startDate;
            }

            $employees = $this->findEmployees($data, $effectiveDate);
            if ($employees->isEmpty()) throw new \Exception('Eligible Employees not found.');

            $total_salary = 0;
            $employeeData = [];
            $penaltiesToUpdate = [];

            foreach ($employees as $employee) {
                $employeeAttendance = Attendance::where('employee_id', $employee->id)->whereBetween('in_time', [$startDate, $endDate])->get();
                $absentData = $this->absentCalculate($employee, $startDate, $endDate, $employeeAttendance);
                $employeeSalary = EmployeeSalaryBreakdown::where('employee_id', $employee->id)->first();
                if (!$employeeSalary) continue;

                if ($frequency === 'hourly') {
                    $totalShiftMinutes = 0;
                    foreach ($employeeAttendance as $attendance) {
                        if ($attendance->getShift) {
                            $in = Carbon::parse($attendance->getShift->clock_in_time);
                            $out = Carbon::parse($attendance->getShift->clock_out_time);
                            if ($out < $in) $out->addDay();
                            $totalShiftMinutes += $in->diffInMinutes($out);
                        }
                    }
                    $calculatedGrossSalary = $employeeSalary->gross_salary * ($totalShiftMinutes / 60);
                } elseif ($frequency === 'daily') {
                    $totalDaysInRange = abs($startDate->diffInDays($endDate)) + 1;
                    $calculatedGrossSalary = $employeeSalary->gross_salary * $totalDaysInRange;
                } else {
                    $calculatedGrossSalary = $employeeSalary->gross_salary;
                }

                $lateCount = $employeeAttendance->where('in_status', 'Late')->count();
                $excessiveLateCount = $employeeAttendance->where('in_status', 'Excessive-Late')->count();
                $earlyExitCount = $employeeAttendance->where('out_status', 'Early-Exit')->count();

                $overTimeSalary = $this->overTimeSalary($employee->id, $employeeSalary, $employeeAttendance->where('overtime', '>', 0), $totalMonthlyHours, $workingHoursPerDay, $frequency, $workingDaysPerCycle);
                $offDayWorkSalary = $this->offDayWorkSalary($employee->id, $employeeSalary, $employeeAttendance->where('shift_type', 'paid-off'), $totalMonthlyHours, $workingHoursPerDay, $frequency, $workingDaysPerCycle);
                $deductionData = $this->deductionAmount($lateCount, $excessiveLateCount, $earlyExitCount, $absentData['absent_count'], $employeeSalary, $workingDaysPerCycle, $frequency, $workingHoursPerDay);
                
                $penalties = \App\Models\Payroll\EmployeePenalty::where('employee_id', $employee->id)->where('status', 'approved')->whereBetween('occurrence_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])->get();
                foreach($penalties as $pen) $penaltiesToUpdate[] = $pen->id;

                $advances = AdvanceSalary::where('employee_id', $employee->id)->where('status', 'approved')->where('deduction_month', '<=', $salary_month)->get();
                $arrears = Arrear::where('employee_id', $employee->id)->where('status', 'approved')->where('payment_month', '<=', $salary_month)->get();

                $previousDues = \App\Models\Payroll\PreviousDue::where('employee_id', $employee->id)->where('status', 'pending')->get();
                $previousDueAmount = $previousDues->sum('amount');

                $salary_amount = $calculatedGrossSalary + $offDayWorkSalary + $overTimeSalary + $arrears->sum('amount') - $deductionData['total'] - $penalties->sum('penalty_amount') - $advances->sum('amount') - $previousDueAmount;
                
                $newDueAmount = 0;
                if ($salary_amount < 0) {
                    $newDueAmount = abs($salary_amount);
                    $salary_amount = 0;
                }
                
                $total_salary += $salary_amount;

                $employeeData[] = [
                    'employee_id' => $employee->id,
                    'absent_count' => $absentData['absent_count'],
                    'absent_dates' => $absentData['absent_dates'],
                    'salary' => $calculatedGrossSalary,
                    'leaves_count' => $absentData['leave_count'],
                    'late_count' => $lateCount,
                    'excessive_late_count' => $excessiveLateCount,
                    'early_exit_count' => $earlyExitCount,
                    'overtime_count' => $employeeAttendance->sum('overtime'),
                    'overtime_amount' => $overTimeSalary,
                    'offday_work_count' => $employeeAttendance->where('shift_type', 'paid-off')->count(),
                    'offday_work_salary' => $offDayWorkSalary,
                    'deduction_amount' => $deductionData['total'],
                    'late_deduction_amount' => $deductionData['late_deduction_amount'],
                    'excessive_late_deduction_amount' => $deductionData['excessive_late_deduction_amount'],
                    'absent_deduction_amount' => $deductionData['absent_deduction_amount'],
                    'early_exit_deduction_amount' => $deductionData['early_exit_deduction_amount'],
                    'penalty_amount' => $penalties->sum('penalty_amount'),
                    'advance_deduction_amount' => $advances->sum('amount'),
                    'arrear_amount' => $arrears->sum('amount'),
                    'bonus_amount' => 0,
                    'total_salary' => $salary_amount,
                    'new_due_amount' => $newDueAmount,
                    'advance_ids' => $advances->pluck('id')->toArray(),
                    'arrear_ids' => $arrears->pluck('id')->toArray(),
                    'previous_due_ids' => $previousDues->pluck('id')->toArray(),
                ];
            }

            if ($processId == null) {
                $process = PayrollProcess::create(['batch_id' => uniqid('Salary_', true), 'company_id' => $data['company_id'], 'branch_id' => $data['branch_id'], 'division_id' => $data['division_id'], 'department_id' => $data['department_id'], 'section_id' => $data['section_id'], 'pay_group_id' => $data['pay_group_id'], 'salary_month' => $salary_month, 'start_date' => $startDate, 'end_date' => $endDate, 'type' => 'salary', 'total_amount' => $total_salary, 'generated_by' => Auth::id(), 'total_employee' => count($employeeData)]);
            } else {
                $process = PayrollProcess::findOrFail($processId);
                $process->update(['company_id' => $data['company_id'], 'branch_id' => $data['branch_id'], 'division_id' => $data['division_id'], 'department_id' => $data['department_id'], 'section_id' => $data['section_id'], 'pay_group_id' => $data['pay_group_id'], 'salary_month' => $salary_month, 'start_date' => $startDate, 'end_date' => $endDate, 'total_amount' => $total_salary, 'generated_by' => Auth::id(), 'total_employee' => count($employeeData)]);
            }

            foreach ($employeeData as $emp) {
                Payroll::create(['process_id' => $process->id, 'batch_id' => $process->batch_id, 'employee_id' => $emp['employee_id'], 'salary' => $emp['salary'], 'late_count' => $emp['late_count'], 'leaves_count' => $emp['leaves_count'], 'absent_count' => $emp['absent_count'], 'absent_dates' => $emp['absent_dates'], 'excessive_late_count' => $emp['excessive_late_count'], 'early_exit_count' => $emp['early_exit_count'], 'overtime_count' => $emp['overtime_count'], 'overtime_amount' => $emp['overtime_amount'], 'offday_work_count' => $emp['offday_work_count'], 'offday_work_salary' => $emp['offday_work_salary'], 'deduction_amount' => $emp['deduction_amount'], 'late_deduction_amount' => $emp['late_deduction_amount'], 'excessive_late_deduction_amount' => $emp['excessive_late_deduction_amount'], 'absent_deduction_amount' => $emp['absent_deduction_amount'], 'early_exit_deduction_amount' => $emp['early_exit_deduction_amount'], 'penalty_amount' => $emp['penalty_amount'], 'advance_deduction_amount' => $emp['advance_deduction_amount'], 'arrear_amount' => $emp['arrear_amount'], 'bonus_amount' => $emp['bonus_amount'], 'total_salary' => $emp['total_salary']]);
                if ($emp['advance_ids']) AdvanceSalary::whereIn('id', $emp['advance_ids'])->update(['status' => 'deducted']);
                if ($emp['arrear_ids']) Arrear::whereIn('id', $emp['arrear_ids'])->update(['status' => 'paid']);
                if ($emp['previous_due_ids']) \App\Models\Payroll\PreviousDue::whereIn('id', $emp['previous_due_ids'])->update(['status' => 'deducted']);
                if ($emp['new_due_amount'] > 0) {
                    \App\Models\Payroll\PreviousDue::create([
                        'employee_id' => $emp['employee_id'],
                        'amount' => $emp['new_due_amount'],
                        'salary_month' => $salary_month,
                        'status' => 'pending',
                        'reason' => 'Negative salary balance carried over from ' . $salary_month
                    ]);
                }
            }
            if ($penaltiesToUpdate) \App\Models\Payroll\EmployeePenalty::whereIn('id', $penaltiesToUpdate)->update(['status' => 'deducted']);
            return $process;
        });
    }

    public function rollbackSalaryProcess($id)
    {
        $process = PayrollProcess::find($id);
        if ($process) {
            $employeeIds = Payroll::where('process_id', $id)->pluck('employee_id');
            if ($employeeIds->isNotEmpty()) {
                \App\Models\Payroll\EmployeePenalty::whereIn('employee_id', $employeeIds)->whereBetween('occurrence_date', [$process->start_date, $process->end_date])->where('status', 'deducted')->update(['status' => 'approved']);
                AdvanceSalary::whereIn('employee_id', $employeeIds)->where('deduction_month', $process->salary_month)->where('status', 'deducted')->update(['status' => 'approved']);
                Arrear::whereIn('employee_id', $employeeIds)->where('payment_month', $process->salary_month)->where('status', 'paid')->update(['status' => 'approved']);
                \App\Models\Payroll\PreviousDue::whereIn('employee_id', $employeeIds)->where('status', 'deducted')->update(['status' => 'pending']);
                \App\Models\Payroll\PreviousDue::whereIn('employee_id', $employeeIds)->where('salary_month', $process->salary_month)->where('status', 'pending')->delete();
            }
            Payroll::where('process_id', $id)->delete();
        }
    }

    public function salaryDelete($id)
    {
        return DB::transaction(function () use ($id) {
            $this->rollbackSalaryProcess($id);
            PayrollProcess::findOrFail($id)->delete();
        });
    }

    public function processDelete($id)
    {
        PayrollProcess::findOrFail($id)->delete();
    }

    public function arrearStatusUpdate($id, $status)
    {
        Arrear::findOrFail($id)->update(['status' => $status]);
    }

    public function advanceStatusUpdate($id, $status)
    {
        AdvanceSalary::findOrFail($id)->update(['status' => $status]);
    }

    public function arrearDelete($id)
    {
        Log::info('Deleting Arrear Process.', ['process_id' => $id]);
        return DB::transaction(function () use ($id) {
            $process = PayrollProcess::findOrFail($id);
            Arrear::where('process_id', $id)->delete();
            $process->delete();
        });
    }

    public function arrearProcess($data, $processId = null)
    {
        Log::info('Initiating arrear process.', ['data' => $data, 'process_id' => $processId]);

        return DB::transaction(function () use ($data, $processId) {
            $payGroup = \App\Models\Company\PayGroup::findOrFail($data['pay_group_id']);
            $frequency = strtolower($payGroup->payroll_frequency);
            
            $payment_month = $data['payment_month'];
            $type = $data['type'];
            $amount_type = $data['amount_type'];
            $amount_value = $data['amount_value'];
            $percentage_base = $data['percentage_base'] ?? 'gross_salary';
            $reason = $data['reason'] ?? '';

            if ($frequency === 'monthly') {
                $salary_month = $data['salary_month'];
                $effectiveDate = Carbon::createFromFormat('Y-m', $salary_month)->startOfMonth();
                $startDate = $effectiveDate->copy();
                $endDate = $effectiveDate->copy()->endOfMonth();
            } else {
                $startDate = Carbon::parse($data['start_date']);
                $endDate = Carbon::parse($data['end_date']);
                $salary_month = $startDate->format('Y-m');
                $effectiveDate = $startDate;
                $data['salary_month'] = $salary_month;
            }

            $employees = $this->findEmployees($data, $effectiveDate);
            
            $total_arrear = 0;
            $employeeData = [];
            
            foreach ($employees as $employee) {
                $employeeSalary = EmployeeSalaryBreakdown::where('employee_id', $employee->id)->first();
                if (!$employeeSalary) continue;

                $calculated_amount = 0;
                if ($amount_type === 'fixed') {
                    $calculated_amount = $amount_value;
                } else {
                    $base_salary = ($percentage_base === 'basic_salary') ? $employeeSalary->basic_salary : $employeeSalary->gross_salary;
                    $calculated_amount = ($base_salary * $amount_value) / 100;
                }

                if ($calculated_amount > 0) {
                    $total_arrear += $calculated_amount;
                    $employeeData[] = [
                        'employee_id' => $employee->id,
                        'amount' => $calculated_amount,
                        'type' => $type,
                        'payment_month' => $payment_month,
                        'reason' => $reason,
                    ];
                }
            }
            
            $total_employees = count($employeeData);
            if ($total_employees == 0) {
                throw new \Exception('No eligible employees found for arrear generation.');
            }

            if ($processId == null) {
                $process = PayrollProcess::create([
                    'batch_id' => uniqid('Arrear_', true),
                    'company_id' => $data['company_id'],
                    'branch_id' => $data['branch_id'],
                    'division_id' => $data['division_id'],
                    'department_id' => $data['department_id'],
                    'section_id' => $data['section_id'],
                    'pay_group_id' => $data['pay_group_id'],
                    'salary_month' => $salary_month,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'type' => 'arrear',
                    'total_amount' => $total_arrear,
                    'generated_by' => Auth::id() ?? null,
                    'total_employee' => $total_employees,
                ]);
            } else {
                $process = PayrollProcess::findOrFail($processId);
                $process->update([
                    'company_id' => $data['company_id'],
                    'branch_id' => $data['branch_id'],
                    'division_id' => $data['division_id'],
                    'department_id' => $data['department_id'],
                    'section_id' => $data['section_id'],
                    'pay_group_id' => $data['pay_group_id'],
                    'salary_month' => $salary_month,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'total_amount' => $total_arrear,
                    'generated_by' => Auth::id() ?? null,
                    'total_employee' => $total_employees,
                ]);
                Arrear::where('process_id', $processId)->delete();
            }

            foreach ($employeeData as $employee) {
                Arrear::create([
                    'process_id' => $process->id,
                    'batch_id' => $process->batch_id,
                    'employee_id' => $employee['employee_id'],
                    'amount' => $employee['amount'],
                    'type' => $employee['type'],
                    'payment_month' => $employee['payment_month'],
                    'reason' => $employee['reason'],
                    'status' => 'pending',
                ]);
            }
            Log::info('Arrear process completed.', ['id' => $process->id]);
        });
    }

    public function searchResult(Request $request, $modelName, $flexsearch, $paginate = true)
    {
        $query = $modelName::with('getEmployee');
        $filters = [];
        if ($request->filled('effective_from_start')) $filters['effective_from>='] = $request->input('effective_from_start');
        if ($request->filled('effective_from_end')) $filters['effective_from<='] = $request->input('effective_from_end');
        if ($request->filled('status')) $filters['status'] = $request->input('status');
        
        $applied = $flexsearch->apply($query, $filters, $request->get('keyword'), ['getEmployee.applicant_id', 'getEmployee.full_name', 'getEmployee.system_id'])->orderBy('id', 'desc');

        if ($paginate) {
            return $applied->paginate(20);
        }
        return $applied->get();
    }

    public function payrollProcessSearchResult(Request $request, $modelName, $flexsearch)
    {
        $query = $modelName::with(['generatedBy', 'getCompany', 'getBranch', 'getDivision', 'getDepartment', 'getSection']);
        $filters = [];
        if ($request->filled('from_start')) $filters['created_at>='] = $request->input('from_start');
        if ($request->filled('from_end')) $filters['created_at<='] = $request->input('from_end');
        if ($request->filled('status')) $filters['approval_status'] = $request->input('status');
        if ($request->filled('salary_month')) $filters['salary_month'] = $request->input('salary_month');
        return $flexsearch->apply($query, $filters, $request->get('keyword'), ['generatedBy.name', 'batch_id']);
    }
}
