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

        // Check for duplicates
        if ($flag !== 'bonus') {
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
                throw new \Exception('Salary process already exists for the selected criteria and period.');
            }
        }

        return $validated;
    }

    public function bonusCalculation($employee, $plan_ids)
    {
        $bonus_amount = 0;
        foreach ($plan_ids as $id) {
            // Check if employee is explicitly attached to this bonus plan
            $isAttached = \Illuminate\Support\Facades\DB::table('employee_bonus_plans')
                ->where('employee_id', $employee->id)
                ->where('plan_id', $id)
                ->exists();

            if (!$isAttached) {
                continue;
            }

            $plan_data = BonusPlan::find($id);
            if ($plan_data) {
                if ($plan_data->bonus_config_type == 'Salary Based') {
                    $basic_salary = EmployeeSalaryBreakdown::where('employee_id', $employee->id)->first()->basic_salary;
                    Log::info('Basic Salary of employee_id ' . $employee->id . ': ' . $basic_salary);
                    if ($plan_data->salary_rate_type == 'Basic Rate') {
                        $bonus_amount += $basic_salary;
                    } else {
                        Log::info('Multiplier Value for this employee_id ' . $employee->id . ': ' . $plan_data->multiplier);
                        $bonus_amount += $basic_salary * $plan_data->multiplier;
                        Log::info('Bonus Amount of Plan ' . $plan_data->name . ': ' . $bonus_amount);
                    }
                } else {
                    $bonus_amount += $plan_data->custom_rate;
                }
            }
        }
        Log::info('Bonus Amount of the Employee ' . $bonus_amount);
        return $bonus_amount;
    }

    public function findEmployees($data, $firstDayOfSalaryMonth)
    {
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

        Log::info('Employees Found:', ['count' => $employees->count()]);

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

        $holidaysInSalaryMonth = $this->findHolidaysInSalaryMonth($startDate, $endDate);
        $weekendsInSalaryMonth = $this->findWeekendsInSalaryMonth($employee, $startDate, $endDate);
        $leavesInSalaryMonth = $this->findLeavesInSalaryMonth($employee, $startDate, $endDate);
        $employeeAttendDate = [];
        foreach ($employeeAttendance as $item) {
            $employeeAttendDate[] = Carbon::parse($item->in_time);
        }
        $employeeAttendDate = collect($employeeAttendDate);

//        dd($holidaysInSalaryMonth, $weekendsInSalaryMonth, $leavesInSalaryMonth, $employeeAttendDate);

        Log::info('Holiday Count' . count($holidaysInSalaryMonth));
        Log::info('Weekend Count' . count($weekendsInSalaryMonth));
        Log::info('Leave Count' . count($leavesInSalaryMonth));
        Log::info('Employee Attendance Count' . count($employeeAttendDate));

        $safeData = $holidaysInSalaryMonth->merge($leavesInSalaryMonth)
            ->merge($weekendsInSalaryMonth)
            ->merge($employeeAttendDate)
            ->unique();
        $leave_count = $leavesInSalaryMonth->count();

        Log::info('Total Safe Data' . count($safeData));

//        dd($safeData);

        $absentDates = collect(CarbonPeriod::create($startDate, $endDate))
            ->map(fn($date) => $date->format('Y-m-d'))
            ->diff(
                $safeData->map(fn($date) => $date->format('Y-m-d'))
            )
            ->values()
            ->toArray();

        return [
            'absent_count' => count($absentDates),
            'absent_dates' => $absentDates,
            'leave_count' => $leave_count,
        ];

    }

    public function overTimeSalary($employee, $employeeSalary, $otAttendances)
    {
        $overTimeSalary = 0;
        $basicSalary = $employeeSalary->basic_salary;
        $hourlyRate = $basicSalary / 240; 
        
        $groupedAttendances = $otAttendances->groupBy('ot_id');

        foreach ($groupedAttendances as $otId => $records) {
            if (!$otId) continue;
            
            $plan = \App\Models\Plan\OTPlan::find($otId);
            if (!$plan) continue;

            $overtimeCount = $records->sum('overtime');

            if ($plan->ot_config_type == 'Salary Based') {
                if ($plan->salary_rate_type == 'Basic Rate') {
                    $overTimeSalary += $hourlyRate * ($overtimeCount / 60);
                } else {
                    $overTimeSalary += ($hourlyRate * $plan->overtime_multiplier) * ($overtimeCount / 60);
                }
            } else {
                $overTimeSalary += $plan->custom_overtime_rate * ($overtimeCount / 60);
            }
        }
        
        return ceil(round($overTimeSalary, 2));
    }

    public function offDayWorkSalary($employee, $employeeSalary, $offDayAttendances)
    {
        $offDayWorkSalary = 0;
        $basicSalary = $employeeSalary->basic_salary;
        $hourlyRate = $basicSalary / 240;

        $groupedAttendances = $offDayAttendances->groupBy('offday_id');

        foreach ($groupedAttendances as $offdayId => $records) {
            if (!$offdayId) continue;

            $plan = \App\Models\Plan\OffDayPlan::find($offdayId);
            if (!$plan) continue;

            $offDayWorkDayCount = $records->count();

            if ($plan->getShift) {
                $shiftTimeInMints = $plan->getShift->treat_as_full_day_minutes + $plan->getShift->grace_time + $plan->getShift->early_out_grace_minutes;
                $offDayWorkCount = $offDayWorkDayCount * ($shiftTimeInMints / 60); 
            } else {
                $offDayWorkCount = $offDayWorkDayCount * 8; 
            }

            if ($plan->offday_config_type == 'Salary Based') {
                if ($plan->salary_rate_type == 'Basic Rate') {
                    $offDayWorkSalary += $hourlyRate * $offDayWorkCount;
                } else {
                    $offDayWorkSalary += ($hourlyRate * $plan->offday_multiplier) * $offDayWorkCount;
                }
            } else {
                // custom_offday_rate is now treated as an hourly rate
                $offDayWorkSalary += $plan->custom_offday_rate * $offDayWorkCount;
            }
        }

        return ceil(round($offDayWorkSalary, 2));
    }

    public function deductionAmount($lateCount, $excessiveLateCount, $earlyExitCount, $absentCount, $employeeSalary, $workingDays = 30){
        $deductionRule = DeductionPlan::first();
//        dd($deductionRule);
        //late deduction amount calculation
        if ($deductionRule->late_deduction_days != null && $lateCount >= $deductionRule->late_deduction_days){
            $lateDeduction = intdiv($lateCount, $deductionRule->late_deduction_days) * $deductionRule->late_salary_deduction_rate;
            if ($deductionRule->calculation_type == 'basic_salary'){
                $lateDeductionAmount = $lateDeduction * ($employeeSalary->basic_salary/$workingDays);
            }else{
                $lateDeductionAmount = $lateDeduction * ($employeeSalary->gross_salary/$workingDays);
            }
        }else{
            $lateDeductionAmount = 0;
        }
        Log::info('Late Deduction Amount [' . $lateDeductionAmount . ']');

        //excessive late deduction amount calculation

        if ($deductionRule->excessive_late_deduction_days != null && $lateCount >= $deductionRule->excessive_late_deduction_days){
            $excessiveLateDeduction = intdiv($excessiveLateCount, $deductionRule->excessive_late_deduction_days) * $deductionRule->excessive_late_salary_deduction_rate;
            if ($deductionRule->calculation_type == 'basic_salary'){
                $excessiveLateDeductionAmount = $excessiveLateDeduction * ($employeeSalary->basic_salary/$workingDays);
            }else{
                $excessiveLateDeductionAmount = $excessiveLateDeduction * ($employeeSalary->gross_salary/$workingDays);
            }
        }else{
            $excessiveLateDeductionAmount = 0;
        }
        Log::info('Excessive Late Deduction Amount [' . $excessiveLateDeductionAmount . ']');

        //absent deduction amount calculation
        if ($deductionRule->absent_deduction_days != null && $absentCount >= $deductionRule->absent_deduction_days){
            $absentDeduction = intdiv($absentCount, $deductionRule->absent_deduction_days) * $deductionRule->absent_salary_deduction_rate;
            if ($deductionRule->calculation_type == 'basic_salary'){
                $absentDeductionAmount = $absentDeduction * ($employeeSalary->basic_salary/$workingDays);
            }else{
                $absentDeductionAmount = $absentDeduction * ($employeeSalary->gross_salary/$workingDays);
            }
        }else{
            $absentDeductionAmount = 0;
        }
        Log::info('Absent Deduction Amount [' . $absentDeductionAmount . ']');

        //early exit deduction amount calculation
        if ($deductionRule->early_out_deduction_days != null && $absentCount >= $deductionRule->early_out_deduction_days){
            $earlyExitDeduction = intdiv($earlyExitCount, $deductionRule->early_out_deduction_days) * $deductionRule->early_out_salary_deduction_rate;
            if ($deductionRule->calculation_type == 'basic_salary'){
                $earlyExitDeductionAmount = $earlyExitDeduction * ($employeeSalary->basic_salary/$workingDays);
            }else{
                $earlyExitDeductionAmount = $earlyExitDeduction * ($employeeSalary->gross_salary/$workingDays);
            }
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
        $payGroup = \App\Models\Company\PayGroup::find($data['pay_group_id']);
        $frequency = strtolower($payGroup->payroll_frequency);
        
        $workingDaysPerCycle = $payGroup->working_days_per_cycle ?? 30;
        $workingHoursPerDay = $payGroup->working_hours_per_day ?? 8;
        $totalMonthlyHours = $workingDaysPerCycle * $workingHoursPerDay;

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

        $employees = $this->findEmployees($data, $startDate);

        $total_employees = count($employees);
        if ($total_employees == 0) {
            throw new \Exception('Eligible Employees not found.');
        }

        $total_salary = 0;
        $employeeData = [];
        $penaltiesToUpdate = [];

        foreach ($employees as $employee) {
            $employeeAttendance = Attendance::where('employee_id', $employee->id)
                ->whereBetween('in_time', [$startDate, $endDate])
                ->get();
            $absentData = $this->absentCalculate($employee, $startDate, $endDate, $employeeAttendance);
            $employeeSalary = EmployeeSalaryBreakdown::where('employee_id', $employee->id)->first();
            
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
            } elseif ($frequency === 'daily') {
                $totalDaysInRange = abs($startDate->diffInDays($endDate)) + 1;
                $calculatedGrossSalary = $employeeSalary->gross_salary * $totalDaysInRange;
            } else {
                $calculatedGrossSalary = $employeeSalary->gross_salary;
            }

            $lateCount = $employeeAttendance->where('in_status', 'Late')->count();
            $excessiveLateCount = $employeeAttendance->where('in_status', 'Excessive-Late')->count();
            $earlyExitCount = $employeeAttendance->where('out_status', 'Early-Exit')->count();

            $overtimeMinutes = $employeeAttendance->sum('overtime');
            if ($overtimeMinutes > 0) {
                $otAttendances = $employeeAttendance->where('overtime', '>', 0);
                $overTimeSalary = $this->overTimeSalary($employee->id, $employeeSalary, $otAttendances, $totalMonthlyHours);
            } else {
                $overTimeSalary = 0;
            }

            $offDayWorkCount = $employeeAttendance->where('shift_type', 'Off-Day')->count();
            if ($offDayWorkCount > 0) {
                $offDayAttendances = $employeeAttendance->where('shift_type', 'Off-Day');
                $offDayWorkSalary = $this->offDayWorkSalary($employee->id, $employeeSalary, $offDayAttendances, $totalMonthlyHours, $workingHoursPerDay);
            } else {
                $offDayWorkSalary = 0;
            }
            $deductionData = $this->deductionAmount($lateCount, $excessiveLateCount, $earlyExitCount, $absentData['absent_count'], $employeeSalary, $workingDaysPerCycle);
            $deductionAmount = $deductionData['total'];
            
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

            $salary_amount = $calculatedGrossSalary + $offDayWorkSalary + $overTimeSalary - $deductionAmount - $penaltyAmount;
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
                'bonus_amount' => $bonusAmount,
                'total_salary' => $salary_amount,
            ];
        }
        Log::info('Total Payroll Amount ' . $total_salary);
        $data['amount'] = $total_salary;

        DB::transaction(function () use ($data, $employeeData, $total_salary, $total_employees, $processId, $penaltiesToUpdate, $startDate, $endDate) {
            if ($processId == null) {
                Log::info('ProcessId ' . $processId);
                Log::info('Payroll Process Creating');
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
                    'salary_month' => $data['salary_month'] ?? null,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'type' => 'salary',
                    'total_amount' => $total_salary,
                    'generated_by' => Auth::id() ?? null,
                    'total_employee' => $total_employees,
                ]);
            }

            foreach ($employeeData as $employee) {
                Log::info('Payroll Process Created: ' . $employee['employee_id']);
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
                    'bonus_amount' => $employee['bonus_amount'],
                    'total_salary' => $employee['total_salary'],
                ]);
            }
            
            if (count($penaltiesToUpdate) > 0) {
                \App\Models\Payroll\EmployeePenalty::whereIn('id', $penaltiesToUpdate)->update(['status' => 'deducted']);
            }
            
            Log::info('Payroll Created.');
        });
    }


    public function bonusDelete($id)
    {
        Log::info('Deleting Old Bonus Data');
        $bonuses = Bonus::where('process_id', $id)->get();
        foreach ($bonuses as $bonus) {
            $bonus->delete();
        }
    }

    public function salaryDelete($id)
    {
        Log::info('Deleting Old Salary Data');
        $process = PayrollProcess::find($id);
        if ($process) {
            $startDate = $process->start_date;
            $endDate = $process->end_date;
            
            // Find all payrolls for this process to get employee IDs
            $employeeIds = Payroll::where('process_id', $id)->pluck('employee_id');
            
            // Reset penalties for these employees in this date range
            \App\Models\Payroll\EmployeePenalty::whereIn('employee_id', $employeeIds)
                ->whereBetween('occurrence_date', [$startDate, $endDate])
                ->where('status', 'deducted')
                ->update(['status' => 'approved']);
        }

        $payrolls = Payroll::where('process_id', $id)->get();
        foreach ($payrolls as $payroll) {
            $payroll->delete();
        }
    }

    public function processDelete($id)
    {
        Log::info('Deleting Old Process Data');
        $process = PayrollProcess::find($id);
        $process->delete();
    }

    public function searchResult(Request $request, $modelName, $flexsearch)
    {
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
    }

    public function payrollProcessSearchResult(Request $request, $modelName, $flexsearch)
    {
        $query = $modelName::with('generatedBy');

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
    }

}

