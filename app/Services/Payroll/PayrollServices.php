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
use App\Models\Payroll\Payroll;
use App\Models\Payroll\PayrollProcess;
use App\Models\Payroll\Promotion;
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

            DB::transaction(function () use ($data, $employeeData, $total_advance, $total_employees, $processId, $salary_month, $startDate, $endDate) {
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

    public function promotionDataStore($data)
    {
        Promotion::create($data);
    }

    public function promotionDataUpdate($id, $data)
    {
        $promotion = Promotion::find($id);
        $promotion->update($data);
    }

    public function incrementDataStore($data)
    {
//        dd($data);
        Increment::create($data);
    }

    public function incrementDataUpdate($id, $data)
    {
        $increment = Increment::find($id);
        $increment->update($data);
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
        $salaryData->update([
            'basic_salary' => $this->salaryCalculation($newGrossSalary, $salaryData->basic_salary_percentage),
            'house_allowance' => $this->salaryCalculation($newGrossSalary, $salaryData->house_allowance_percentage),
            'transport_allowance' => $this->salaryCalculation($newGrossSalary, $salaryData->transport_allowance_percentage),
            'food_allowance' => $this->salaryCalculation($newGrossSalary, $salaryData->food_allowance_percentage),
            'medical_allowance' => $this->salaryCalculation($newGrossSalary, $salaryData->medical_allowance_percentage),
            'other_earnings' => $this->salaryCalculation($newGrossSalary, $salaryData->other_earnings_percentage),
            'gross_salary' => $newGrossSalary
        ]);
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
            ->select('id', 'full_name')
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
                    $q->where('id', $data['employee_id']);
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

        Log::info('Total Bonus Amount ' . $total_bonus);
        $data['amount'] = $total_bonus;
//        dd($data);
        DB::transaction(function () use ($data, $employeeData, $total_bonus, $total_employees, $processId) {
            if ($processId == null) {
                Log::info('ProcessId ' . $processId);
                Log::info('Payroll Process Creating');
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
                Log::info('Payroll Process Created: ' . $process->id);

            } else {
                Log::info('ProcessId ' . $processId);
                $process = PayrollProcess::find($processId);
                Log::info('Payroll Process Updating: ' . $process->id);
                $process->update([
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
            }

            foreach ($employeeData as $employee) {
                Log::info('Bonus Process Created: ' . $employee['employee_id']);
                $bonus = Bonus::create([
                    'process_id' => $process->id,
                    'batch_id' => $process->batch_id,
                    'employee_id' => $employee['employee_id'],
                    'amount' => $employee['bonus_amount'],
                ]);
            }
            Log::info('Bonus Created: ' . $bonus->id);

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
        $holidayDates = collect($holidayDates)->unique();
        return $holidayDates;
    }

    public function findHolidaysInSalaryMonth($startDate, $endDate)
    {
        $holidays = $this->getHolidays();
        return $holidays->filter(function ($holiday) use ($startDate, $endDate) {
            return $holiday->between($startDate, $endDate);
        });
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
        $leaveDates = collect($leaveDates)->unique();
//        dd($leaveDates);
        return $leaveDates;
    }

    public function findLeavesInSalaryMonth($employee, $startDate, $endDate)
    {
        $leaves = $this->leaveDays($employee);
        return $leaves->filter(function ($q) use ($startDate, $endDate) {
            return $q->between($startDate, $endDate);
        });
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
        $employeeAttendDate = [];
        foreach ($employeeAttendance as $item) {
            $employeeAttendDate[] = Carbon::parse($item->in_time);
        }
        $employeeAttendDate = collect($employeeAttendDate);

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

        Log::info('Total unique "safe" days (present/leave/holiday/weekend).', ['count' => count($safeData)]);

        $absentDates = collect(CarbonPeriod::create($startDate, $endDate))
            ->map(fn($date) => $date->format('Y-m-d'))
            ->diff(
                $safeData->map(fn($date) => $date->format('Y-m-d'))
            )
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
        Log::info('Calculating overtime salary using Gross-based Base Rate.', ['employee_id' => $employeeId, 'frequency' => $frequency]);
        $overTimeSalary = 0;
        $grossSalary = $employeeSalary->gross_salary;
        
        // Determine hourly rate based on Gross-based Base Rate (Gross / Working Days)
        if ($frequency === 'hourly') {
            $hourlyRate = $grossSalary; // gross_salary is already hourly rate
        } elseif ($frequency === 'daily') {
            $hourlyRate = $grossSalary / ($workingHoursPerDay ?: 8); // gross_salary is daily rate
        } else {
            // Base Rate = Gross / Working Days. Hourly Rate = Base Rate / Working Hours Per Day
            $dayRate = $grossSalary / ($workingDays ?: 30);
            $hourlyRate = $dayRate / ($workingHoursPerDay ?: 8);
        }
        
        Log::info('Calculated hourly rate for OT.', ['hourly_rate' => $hourlyRate, 'gross_salary' => $grossSalary]);

        $groupedAttendances = $otAttendances->groupBy('ot_id');

        foreach ($groupedAttendances as $otId => $records) {
            if (!$otId) continue;
            
            $plan = \App\Models\Plan\OTPlan::find($otId);
            if (!$plan) {
                Log::warning('OT Plan not found.', ['ot_id' => $otId]);
                continue;
            }

            $overtimeCount = $records->sum('overtime');

            if ($plan->ot_config_type == 'Salary Based') {
                if ($plan->salary_rate_type == 'Basic Rate') {
                    $amount = $hourlyRate * ($overtimeCount / 60);
                } else {
                    $amount = ($hourlyRate * $plan->overtime_multiplier) * ($overtimeCount / 60);
                }
            } else {
                $amount = $plan->custom_overtime_rate * ($overtimeCount / 60);
            }
            
            Log::info('OT Plan calculation.', [
                'plan_name' => $plan->name,
                'minutes' => $overtimeCount,
                'calculated_amount' => $amount,
                'hourly_rate_used' => $hourlyRate
            ]);
            
            $overTimeSalary += $amount;
        }
        
        $finalAmount = ceil(round($overTimeSalary, 2));
        Log::info('Total overtime salary.', ['amount' => $finalAmount]);
        return $finalAmount;
    }

    public function offDayWorkSalary($employeeId, $employeeSalary, $offDayAttendances, $totalMonthlyHours = 240, $workingHoursPerDay = 8, $frequency = 'monthly', $workingDays = 30)
    {
        Log::info('Calculating off-day work salary using Gross-based Base Rate.', ['employee_id' => $employeeId, 'frequency' => $frequency]);
        $offDayWorkSalary = 0;
        $grossSalary = $employeeSalary->gross_salary;

        // Determine hourly rate based on Gross-based Base Rate (Gross / Working Days)
        if ($frequency === 'hourly') {
            $hourlyRate = $grossSalary; // gross_salary is already hourly rate
        } elseif ($frequency === 'daily') {
            $hourlyRate = $grossSalary / $workingHoursPerDay; // gross_salary is daily rate
        } else {
            $dayRate = $grossSalary / ($workingDays ?: 30);
            $hourlyRate = $dayRate / ($workingHoursPerDay ?: 8);
        }

        Log::info('Calculated hourly rate for Off-Day Work.', ['hourly_rate' => $hourlyRate, 'gross_salary' => $grossSalary]);

        $groupedAttendances = $offDayAttendances->groupBy('offday_id');

        foreach ($groupedAttendances as $offdayId => $records) {
            if (!$offdayId) continue;

            $plan = \App\Models\Plan\OffDayPlan::find($offdayId);
            if (!$plan) {
                Log::warning('Off-Day Plan not found.', ['offday_id' => $offdayId]);
                continue;
            }

            $offDayWorkDayCount = $records->count();

            if ($plan->getShift) {
                $shiftTimeInMints = $plan->getShift->treat_as_full_day_minutes + $plan->getShift->grace_time + $plan->getShift->early_out_grace_minutes;
                $offDayWorkCount = $offDayWorkDayCount * ($shiftTimeInMints / 60); 
            } else {
                $offDayWorkCount = $offDayWorkDayCount * ($workingHoursPerDay ?: 8); 
            }

            if ($plan->offday_config_type == 'Salary Based') {
                if ($plan->salary_rate_type == 'Basic Rate') {
                    $amount = $hourlyRate * $offDayWorkCount;
                } else {
                    $amount = ($hourlyRate * $plan->offday_multiplier) * $offDayWorkCount;
                }
            } else {
                // custom_offday_rate is now treated as an hourly rate
                $amount = $plan->custom_offday_rate * $offDayWorkCount;
            }
            
            Log::info('Off-Day Plan calculation.', [
                'plan_name' => $plan->name,
                'days_count' => $offDayWorkDayCount,
                'total_hours' => $offDayWorkCount,
                'calculated_amount' => $amount,
                'hourly_rate_used' => $hourlyRate
            ]);
            
            $offDayWorkSalary += $amount;
        }

        $finalAmount = ceil(round($offDayWorkSalary, 2));
        Log::info('Total off-day work salary.', ['amount' => $finalAmount]);
        return $finalAmount;
    }

    public function deductionAmount($lateCount, $excessiveLateCount, $earlyExitCount, $absentCount, $employeeSalary, $workingDays = 30, $frequency = 'monthly', $workingHoursPerDay = 8){
        Log::info('Calculating deduction amounts.', [
            'frequency' => $frequency,
            'working_days' => $workingDays,
            'working_hours_per_day' => $workingHoursPerDay
        ]);
        
        $deductionRule = DeductionPlan::first();
        if (!$deductionRule) {
            Log::warning('No deduction plan found. Skipping deductions.');
            return [
                'total' => 0,
                'late_deduction_amount' => 0,
                'excessive_late_deduction_amount' => 0,
                'absent_deduction_amount' => 0,
                'early_exit_deduction_amount' => 0,
            ];
        }

        if ($deductionRule->calculation_type == 'basic_salary'){
            $baseForDeduction = $employeeSalary->basic_salary;
        }else{
            $baseForDeduction = $employeeSalary->gross_salary;
        }

        // Determine what "1 day's pay" is based on frequency
        if ($frequency === 'hourly') {
            $dayRate = $baseForDeduction * $workingHoursPerDay;
        } elseif ($frequency === 'daily') {
            $dayRate = $baseForDeduction;
        } else {
            $dayRate = $baseForDeduction / ($workingDays ?: 30);
        }

        Log::info('Determined base day rate for deductions.', ['day_rate' => $dayRate, 'base_salary_field' => $baseForDeduction]);

        //late deduction amount calculation
        if ($deductionRule->late_deduction_days != null && $lateCount >= $deductionRule->late_deduction_days){
            $lateDeductionUnits = intdiv($lateCount, $deductionRule->late_deduction_days) * $deductionRule->late_salary_deduction_rate;
            $lateDeductionAmount = $lateDeductionUnits * $dayRate;
        }else{
            $lateDeductionAmount = 0;
        }
        Log::info('Late Deduction Amount [' . $lateDeductionAmount . ']');

        //excessive late deduction amount calculation
        if ($deductionRule->excessive_late_deduction_days != null && $excessiveLateCount >= $deductionRule->excessive_late_deduction_days){
            $excessiveLateDeductionUnits = intdiv($excessiveLateCount, $deductionRule->excessive_late_deduction_days) * $deductionRule->excessive_late_salary_deduction_rate;
            $excessiveLateDeductionAmount = $excessiveLateDeductionUnits * $dayRate;
        }else{
            $excessiveLateDeductionAmount = 0;
        }
        Log::info('Excessive Late Deduction Amount [' . $excessiveLateDeductionAmount . ']');

        //absent deduction amount calculation
        if ($deductionRule->absent_deduction_days != null && $absentCount >= $deductionRule->absent_deduction_days){
            $absentDeductionUnits = intdiv($absentCount, $deductionRule->absent_deduction_days) * $deductionRule->absent_salary_deduction_rate;
            $absentDeductionAmount = $absentDeductionUnits * $dayRate;
        }else{
            $absentDeductionAmount = 0;
        }
        Log::info('Absent Deduction Amount [' . $absentDeductionAmount . ']');

        //early exit deduction amount calculation
        if ($deductionRule->early_out_deduction_days != null && $earlyExitCount >= $deductionRule->early_out_deduction_days){
            $earlyExitDeductionUnits = intdiv($earlyExitCount, $deductionRule->early_out_deduction_days) * $deductionRule->early_out_salary_deduction_rate;
            $earlyExitDeductionAmount = $earlyExitDeductionUnits * $dayRate;
        }else{
            $earlyExitDeductionAmount = 0;
        }
        Log::info('Early Exit Deduction Amount [' . $earlyExitDeductionAmount . ']');

        return [
            'total' => $lateDeductionAmount + $excessiveLateDeductionAmount + $absentDeductionAmount + $earlyExitDeductionAmount,
            'late_deduction_amount' => $lateDeductionAmount,
            'excessive_late_deduction_amount' => $excessiveLateDeductionAmount,
            'absent_deduction_amount' => $absentDeductionAmount,
            'early_exit_deduction_amount' => $earlyExitDeductionAmount,
        ];
    }

    public function bonusWithSalaryCalculation($employee, $employeeSalary, $salary_month){
        $bonusData = Bonus::with('getBatch')->where('employee_id', $employee)
            ->whereHas('getBatch', function ($query) use ($salary_month) {
                $query->where('salary_month', $salary_month)
                    ->where('approval_status', 'approved');
            })->get();
        $totalBonus = 0;
        if ($bonusData->count() > 0){
            $totalBonus = $bonusData->sum('amount');
        }
        return $totalBonus;

    }

    public function salaryProcess($data, $processId = null)
    {
        Log::info('Starting salary process.', ['data' => $data, 'process_id' => $processId]);
        $payGroup = \App\Models\Company\PayGroup::find($data['pay_group_id']);
        $frequency = strtolower($payGroup->payroll_frequency);
        
        $workingDaysPerCycle = $payGroup->working_days_per_cycle ?? 30;
        $workingHoursPerDay = $payGroup->working_hours_per_day ?? 8;
        $totalMonthlyHours = $workingDaysPerCycle * $workingHoursPerDay;

        Log::info('Pay group configuration.', [
            'frequency' => $frequency,
            'working_days_per_cycle' => $workingDaysPerCycle,
            'working_hours_per_day' => $workingHoursPerDay,
            'total_monthly_hours' => $totalMonthlyHours
        ]);

        if ($frequency === 'monthly') {
            $salary_month = $data['salary_month'];
            $startDate = Carbon::createFromFormat('Y-m', $salary_month)->startOfMonth();
            $endDate = Carbon::createFromFormat('Y-m', $salary_month)->endOfMonth();
        } else {
            $startDate = Carbon::parse($data['start_date']);
            $endDate = Carbon::parse($data['end_date']);
            $salary_month = $startDate->format('Y-m'); // Fallback for views/logs
            // Make sure data array has salary_month for existing references
            $data['salary_month'] = $salary_month;
        }

        Log::info('Salary period defined.', [
            'start_date' => $startDate->toDateString(),
            'end_date' => $endDate->toDateString(),
            'salary_month' => $salary_month
        ]);

        $employees = $this->findEmployees($data, $startDate);

        $total_employees = count($employees);
        if ($total_employees == 0) {
            Log::warning('No eligible employees found for salary generation.', ['data' => $data]);
            throw new \Exception('Eligible Employees not found.');
        }

        Log::info('Found eligible employees.', ['count' => $total_employees]);

        $total_salary = 0;
        $employeeData = [];
        $penaltiesToUpdate = [];

        foreach ($employees as $employee) {
            Log::info('Processing salary for employee.', ['employee_id' => $employee->id, 'full_name' => $employee->full_name]);
            
            $employeeAttendance = Attendance::where('employee_id', $employee->id)
                ->whereBetween('in_time', [$startDate, $endDate])
                ->get();
            
            Log::info('Employee attendance records.', ['count' => $employeeAttendance->count()]);
            
            $absentData = $this->absentCalculate($employee, $startDate, $endDate, $employeeAttendance);
            $employeeSalary = EmployeeSalaryBreakdown::where('employee_id', $employee->id)->first();
            
            if (!$employeeSalary) {
                Log::error('Salary breakdown not found for employee.', ['employee_id' => $employee->id]);
                continue;
            }

            // Base Salary Calculation depending on frequency
            if ($frequency === 'hourly') {
                $totalShiftMinutes = 0;
                foreach ($employeeAttendance as $attendance) {
                    if ($attendance->getShift) {
                        $in = Carbon::parse($attendance->getShift->clock_in_time);
                        $out = Carbon::parse($attendance->getShift->clock_out_time);
                        if ($out < $in) {
                            $out->addDay();
                        }
                        $totalShiftMinutes += $in->diffInMinutes($out);
                    }
                }
                // gross_salary is hourly rate, so multiply by (total minutes / 60)
                $calculatedGrossSalary = $employeeSalary->gross_salary * ($totalShiftMinutes / 60);
                Log::info('Hourly salary calculation.', ['total_minutes' => $totalShiftMinutes, 'hourly_rate' => $employeeSalary->gross_salary, 'calculated_gross' => $calculatedGrossSalary]);
            } elseif ($frequency === 'daily') {
                $totalDaysInRange = abs($startDate->diffInDays($endDate)) + 1;
                $calculatedGrossSalary = $employeeSalary->gross_salary * $totalDaysInRange;
                Log::info('Daily salary calculation.', ['total_days' => $totalDaysInRange, 'daily_rate' => $employeeSalary->gross_salary, 'calculated_gross' => $calculatedGrossSalary]);
            } else {
                $calculatedGrossSalary = $employeeSalary->gross_salary;
                Log::info('Monthly salary calculation.', ['gross_salary' => $calculatedGrossSalary]);
            }

            $lateCount = $employeeAttendance->where('in_status', 'Late')->count();
            $excessiveLateCount = $employeeAttendance->where('in_status', 'Excessive-Late')->count();
            $earlyExitCount = $employeeAttendance->where('out_status', 'Early-Exit')->count();

            $overtimeMinutes = $employeeAttendance->sum('overtime');
            if ($overtimeMinutes > 0) {
                $otAttendances = $employeeAttendance->where('overtime', '>', 0);
                $overTimeSalary = $this->overTimeSalary($employee->id, $employeeSalary, $otAttendances, $totalMonthlyHours, $workingHoursPerDay, $frequency);
            } else {
                $overTimeSalary = 0;
            }
            Log::info('Overtime calculation.', ['minutes' => $overtimeMinutes, 'amount' => $overTimeSalary]);

            $offDayWorkCount = $employeeAttendance->where('shift_type', 'Off-Day')->count();
            if ($offDayWorkCount > 0) {
                $offDayAttendances = $employeeAttendance->where('shift_type', 'Off-Day');
                $offDayWorkSalary = $this->offDayWorkSalary($employee->id, $employeeSalary, $offDayAttendances, $totalMonthlyHours, $workingHoursPerDay, $frequency);
            } else {
                $offDayWorkSalary = 0;
            }
            Log::info('Off-day work calculation.', ['count' => $offDayWorkCount, 'amount' => $offDayWorkSalary]);

            $deductionData = $this->deductionAmount($lateCount, $excessiveLateCount, $earlyExitCount, $absentData['absent_count'], $employeeSalary, $workingDaysPerCycle, $frequency, $workingHoursPerDay);
            $deductionAmount = $deductionData['total'];
            Log::info('Deduction calculation.', ['deduction_data' => $deductionData]);
            
            // Bonus is handled in a separate module, removing from standard salary process
            $bonusAmount = 0; 
            
            // Penalty Calculation
            $penalties = \App\Models\Payroll\EmployeePenalty::where('employee_id', $employee->id)
                ->where('status', 'approved')
                ->whereBetween('occurrence_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                ->get();
            $penaltyAmount = $penalties->sum('penalty_amount');
            if($penaltyAmount > 0) {
                 foreach($penalties as $pen) {
                     $penaltiesToUpdate[] = $pen->id;
                 }
            }
            Log::info('Penalty calculation.', ['amount' => $penaltyAmount, 'count' => $penalties->count()]);

            // Advance Salary Calculation
            Log::info('Searching for approved advances for recovery (including overdue).', [
                'employee_id' => $employee->id,
                'current_salary_month' => $salary_month
            ]);
            
            // We search for advances where deduction_month is <= current salary_month
            // This ensures missed advances from previous months are also recovered
            $advances = AdvanceSalary::where('employee_id', $employee->id)
                ->where('status', 'approved')
                ->where('deduction_month', '<=', $salary_month)
                ->get();
                
            if ($advances->isEmpty()) {
                // Debug: Check if there are any advances at all for this employee
                $anyAdvances = AdvanceSalary::where('employee_id', $employee->id)->get();
                if ($anyAdvances->isNotEmpty()) {
                    Log::info('Employee has advances, but none are "approved" or "due".', [
                        'employee_id' => $employee->id,
                        'advances' => $anyAdvances->map(fn($a) => [
                            'id' => $a->id,
                            'status' => $a->status,
                            'due_month' => $a->deduction_month
                        ])
                    ]);
                }
            }

            $advanceDeductionAmount = $advances->sum('amount');
            Log::info('Advance Salary recovery result.', [
                'employee_id' => $employee->id,
                'total_recovered' => $advanceDeductionAmount, 
                'count' => $advances->count(),
                'advance_ids' => $advances->pluck('id')->toArray()
            ]);

            // Arrear Calculation
            Log::info('Searching for approved arrears for addition (including overdue).', [
                'employee_id' => $employee->id,
                'current_salary_month' => $salary_month
            ]);
            
            $arrears = Arrear::where('employee_id', $employee->id)
                ->where('status', 'approved')
                ->where('payment_month', '<=', $salary_month)
                ->get();

            $arrearAmount = $arrears->sum('amount');
            Log::info('Arrear addition result.', [
                'employee_id' => $employee->id,
                'total_added' => $arrearAmount, 
                'count' => $arrears->count(),
                'arrear_ids' => $arrears->pluck('id')->toArray()
            ]);

            $salary_amount = $calculatedGrossSalary + $offDayWorkSalary + $overTimeSalary + $arrearAmount - $deductionAmount - $penaltyAmount - $advanceDeductionAmount;
            
            Log::info('Final salary breakdown for employee.', [
                'employee_id' => $employee->id,
                'math' => [
                    'base_gross' => $calculatedGrossSalary,
                    'overtime' => $overTimeSalary,
                    'offday_work' => $offDayWorkSalary,
                    'arrears' => $arrearAmount,
                    'deductions' => $deductionAmount,
                    'penalties' => $penaltyAmount,
                    'advances' => $advanceDeductionAmount,
                    'EQUALS' => $salary_amount
                ]
            ]);
            
            $total_salary += $salary_amount;

            $employeeData[] = [
                'employee_id' => $employee->id,
                'absent_count' => $absentData['absent_count'],
                'absent_dates' => $absentData['absent_dates'],
                'salary' => $calculatedGrossSalary, // Store the calculated gross
                'leaves_count' => $absentData['leave_count'],
                'late_count' => $lateCount,
                'excessive_late_count' => $excessiveLateCount,
                'early_exit_count' => $earlyExitCount,
                'overtime_count' => $overtimeMinutes,
                'overtime_amount' => $overTimeSalary,
                'offday_work_count' => $offDayWorkCount,
                'offday_work_salary' => $offDayWorkSalary,
                'deduction_amount' => $deductionAmount,
                'late_deduction_amount' => $deductionData['late_deduction_amount'],
                'excessive_late_deduction_amount' => $deductionData['excessive_late_deduction_amount'],
                'absent_deduction_amount' => $deductionData['absent_deduction_amount'],
                'early_exit_deduction_amount' => $deductionData['early_exit_deduction_amount'],
                'penalty_amount' => $penaltyAmount,
                'advance_deduction_amount' => $advanceDeductionAmount,
                'arrear_amount' => $arrearAmount,
                'bonus_amount' => $bonusAmount,
                'total_salary' => $salary_amount,
                'advance_ids' => $advances->pluck('id')->toArray(),
                'arrear_ids' => $arrears->pluck('id')->toArray(),
            ];
        }
        Log::info('Total Payroll Amount ' . $total_salary);
        $data['amount'] = $total_salary;

        DB::transaction(function () use ($data, $employeeData, $total_salary, $total_employees, $processId, $penaltiesToUpdate, $startDate, $endDate, $salary_month) {
            if ($processId == null) {
                Log::info('Creating new PayrollProcess.', [
                    'company_id' => $data['company_id'],
                    'pay_group_id' => $data['pay_group_id'],
                    'salary_month' => $data['salary_month'] ?? null
                ]);
                $process = PayrollProcess::create([
                    'batch_id' => uniqid('Salary_', true),
                    'company_id' => $data['company_id'],
                    'branch_id' => $data['branch_id'],
                    'division_id' => $data['division_id'],
                    'department_id' => $data['department_id'],
                    'section_id' => $data['section_id'],
                    'pay_group_id' => $data['pay_group_id'],
                    'salary_month' => $data['salary_month'] ?? null,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'type' => 'salary',
                    'total_amount' => $total_salary,
                    'generated_by' => Auth::id() ?? null,
                    'total_employee' => $total_employees,
                ]);
                Log::info('Payroll Process Created.', ['id' => $process->id, 'batch_id' => $process->batch_id]);

            } else {
                Log::info('Updating existing PayrollProcess.', ['id' => $processId]);
                $process = PayrollProcess::find($processId);
                $process->update([
                    'company_id' => $data['company_id'],
                    'branch_id' => $data['branch_id'],
                    'division_id' => $data['division_id'],
                    'department_id' => $data['department_id'],
                    'section_id' => $data['section_id'],
                    'pay_group_id' => $data['pay_group_id'],
                    'salary_month' => $data['salary_month'] ?? null,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'type' => 'salary',
                    'total_amount' => $total_salary,
                    'generated_by' => Auth::id() ?? null,
                    'total_employee' => $total_employees,
                ]);
                Log::info('Payroll Process Updated.', ['id' => $process->id]);
            }

            foreach ($employeeData as $employee) {
                Log::info('Creating individual payroll record.', ['employee_id' => $employee['employee_id']]);
                $salary = Payroll::create([
                    'process_id' => $process->id,
                    'batch_id' => $process->batch_id,
                    'employee_id' => $employee['employee_id'],
                    'salary' => $employee['salary'],
                    'late_count' => $employee['late_count'],
                    'leaves_count' => $employee['leaves_count'],
                    'absent_count' => $employee['absent_count'],
                    'absent_dates' => $employee['absent_dates'],
                    'excessive_late_count' => $employee['excessive_late_count'],
                    'early_exit_count' => $employee['early_exit_count'],
                    'overtime_count' => $employee['overtime_count'],
                    'overtime_amount' => $employee['overtime_amount'],
                    'offday_work_count' => $employee['offday_work_count'],
                    'offday_work_salary' => $employee['offday_work_salary'],
                    'deduction_amount' => $employee['deduction_amount'],
                    'late_deduction_amount' => $employee['late_deduction_amount'],
                    'excessive_late_deduction_amount' => $employee['excessive_late_deduction_amount'],
                    'absent_deduction_amount' => $employee['absent_deduction_amount'],
                    'early_exit_deduction_amount' => $employee['early_exit_deduction_amount'],
                    'penalty_amount' => $employee['penalty_amount'],
                    'advance_deduction_amount' => $employee['advance_deduction_amount'],
                    'bonus_amount' => $employee['bonus_amount'],
                    'total_salary' => $employee['total_salary'],
                ]);

                if (count($employee['advance_ids']) > 0) {
                    AdvanceSalary::whereIn('id', $employee['advance_ids'])->update(['status' => 'deducted']);
                }

                if (count($employee['arrear_ids']) > 0) {
                    Arrear::whereIn('id', $employee['arrear_ids'])->update(['status' => 'paid']);
                }
            }
            
            if (count($penaltiesToUpdate) > 0) {
                Log::info('Updating penalties status to deducted.', ['penalty_ids' => $penaltiesToUpdate]);
                \App\Models\Payroll\EmployeePenalty::whereIn('id', $penaltiesToUpdate)->update(['status' => 'deducted']);
            }
            
            Log::info('Payroll process database transaction completed.');
        });
    }


    public function bonusDelete($id)
    {
        Log::info('Deleting individual bonus records for process.', ['process_id' => $id]);
        try {
            Bonus::where('process_id', $id)->delete();
            Log::info('Individual bonus records deleted successfully.', ['process_id' => $id]);
        } catch (\Exception $e) {
            Log::error('Failed to delete individual bonus records.', [
                'process_id' => $id,
                'message' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    public function salaryDelete($id)
    {
        Log::info('Initiating deletion of salary process data.', ['process_id' => $id]);
        try {
            $process = PayrollProcess::find($id);
            if ($process) {
                $startDate = $process->start_date;
                $endDate = $process->end_date;
                $salaryMonth = $process->salary_month;
                
                // Find all payrolls for this process to get employee IDs
                $employeeIds = Payroll::where('process_id', $id)->pluck('employee_id');
                
                if ($employeeIds->isNotEmpty()) {
                    Log::info('Rolling back penalty and advance statuses for affected employees.', ['count' => $employeeIds->count()]);
                    
                    // Reset penalties for these employees in this date range
                    \App\Models\Payroll\EmployeePenalty::whereIn('employee_id', $employeeIds)
                        ->whereBetween('occurrence_date', [$startDate, $endDate])
                        ->where('status', 'deducted')
                        ->update(['status' => 'approved']);

                    // Reset advance salaries for these employees for this deduction month
                    AdvanceSalary::whereIn('employee_id', $employeeIds)
                        ->where('deduction_month', $salaryMonth)
                        ->where('status', 'deducted')
                        ->update(['status' => 'approved']);
                }
            }

            // Bulk delete individual payroll records
            $deletedCount = Payroll::where('process_id', $id)->delete();
            Log::info('Individual payroll records deleted.', ['process_id' => $id, 'count' => $deletedCount]);

        } catch (\Exception $e) {
            Log::error('Salary deletion sequence failed.', [
                'process_id' => $id,
                'message' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    public function processDelete($id)
    {
        Log::info('Deleting Payroll Process record.', ['process_id' => $id]);
        try {
            $process = PayrollProcess::findOrFail($id);
            $process->delete();
            Log::info('Payroll Process record deleted successfully.', ['process_id' => $id]);
        } catch (\Exception $e) {
            Log::error('Failed to delete Payroll Process record.', [
                'process_id' => $id,
                'message' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    public function advanceStatusUpdate($id, $status)
    {
        Log::info('Updating individual advance salary item status.', [
            'advance_id' => $id,
            'status' => $status
        ]);
        try {
            $advance = AdvanceSalary::findOrFail($id);
            $advance->update(['status' => $status]);
            Log::info('Individual advance salary status updated.', ['advance_id' => $id]);
        } catch (\Exception $e) {
            Log::error('Failed to update individual advance salary status.', [
                'advance_id' => $id,
                'message' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    public function searchResult(Request $request, $modelName, $flexsearch)
    {
        Log::info('Executing generic search result query.', ['model' => $modelName, 'filters' => $request->all()]);
        try {
            $query = $modelName::with('getEmployee');

            $filters = [];

            if ($request->filled('effective_from_start')) {
                $filters['effective_from>='] = ($request->input('effective_from_start'));
            }

            if ($request->filled('effective_from_end')) {
                $filters['effective_from<='] = ($request->input('effective_from_end'));
            }

            if ($request->filled('status')) {
                $filters['status'] = ($request->input('status'));
            }

            $searchTerm = $request->get('keyword');

            $searchableFields = ['getEmployee.applicant_id', 'getEmployee.full_name', 'getEmployee.system_id'];

            $data = $flexsearch->apply($query,
                $filters,
                $searchTerm,
                $searchableFields)->orderBy('id', 'desc')->paginate(20);

            return $data;
        } catch (\Exception $e) {
            Log::error('Search result query failed.', ['message' => $e->getMessage()]);
            throw $e;
        }
    }

    public function payrollProcessSearchResult(Request $request, $modelName, $flexsearch)
    {
        Log::info('Executing payroll process search result query.', ['model' => $modelName, 'filters' => $request->all()]);
        try {
            $query = $modelName::with(['generatedBy', 'getCompany', 'getBranch', 'getDivision', 'getDepartment', 'getSection']);

            $filters = [];

            if ($request->filled('from_start')) {
                $filters['created_at>='] = ($request->input('from_start'));
            }

            if ($request->filled('from_end')) {
                $filters['created_at<='] = ($request->input('from_end'));
            }

            if ($request->filled('status')) {
                $filters['approval_status'] = ($request->input('status'));
            }

            if ($request->filled('salary_month')) {
                $filters['salary_month'] = ($request->input('salary_month'));
            }

            $searchTerm = $request->get('keyword');

            $searchableFields = ['generatedBy.name', 'batch_id'];

            $data = $flexsearch->apply($query,
                $filters,
                $searchTerm,
                $searchableFields);
            return $data;
        } catch (\Exception $e) {
            Log::error('Payroll process search query failed.', ['message' => $e->getMessage()]);
            throw $e;
        }
    }

}
