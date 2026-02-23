<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\BonusPlan;
use App\Models\DeductionPlan;
use App\Models\Employee;
use App\Models\EmployeeOffdayPlan;
use App\Models\EmployeeOfficeInfo;
use App\Models\EmployeeOtPlan;
use App\Models\EmployeeSalaryBreakdown;
use App\Models\Holiday;
use App\Models\Leave;
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

            'salary_month' => 'required',
        ];

        if ($flag == 'bonus') {
            $rules['plan_ids'] = 'required|array|min:1';
            // Validate each ID inside the arrays
            $rules['plan_ids.*'] = 'required|integer|exists:bonus_plans,id';
        }

        $validated = $request->validate($rules,
            [
                'plan_ids.required' => 'Plan is required.',
                'plan_ids.*.required' => 'Plan is required.',
                'salary_month.required' => 'Salary Month is required.',
            ]);
        return $validated;
    }

    public function bonusCalculation($employee, $plan_ids)
    {
        $bonus_amount = 0;
        foreach ($plan_ids as $id) {
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
            ->whereHas('salary')
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
        $total_employees = count($employees);
        if ($total_employees == 0) {
            throw new \Exception('Eligible Employees not found.');
        }
        $total_bonus = 0;
        $employeeData = [];
        foreach ($employees as $employee) {
            $bonus_amount = $this->bonusCalculation($employee, $data['plan_ids']);
            $total_bonus += $bonus_amount;
            $employeeData[] = [
                'employee_id' => $employee->id,
                'bonus_amount' => $bonus_amount,
            ];
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
        $weekends = EmployeeOfficeInfo::find($employee->id)->weekends;
        return $weekends;
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

    public function overTimeSalary($employee, $employeeSalary, $overtimeCount)
    {
        $employeeOvertime = EmployeeOtPlan::where('employee_id', $employee)->first();
        $basicSalary = $employeeSalary->basic_salary;
        if ($employeeOvertime->getPlan->ot_config_type == 'Salary Based') {
            if ($employeeOvertime->getPlan->salary_rate_type == 'Basic Rate') {
                $overTimeSalary = ($basicSalary / 60) * $overtimeCount;
            } else {
                $overTimeSalary = (($basicSalary * $employeeOvertime->getPlan->overtime_multiplier) / 60) * $overtimeCount;
            }
        } else {
            $overTimeSalary = ($employeeOvertime->getPlan->custom_overtime_rate / 60) * $overtimeCount;
        }
        return ceil(round($overTimeSalary,2));
    }

    public function offDayWorkSalary($employee, $employeeSalary, $offDayWorkDayCount)
    {
        $employeeOffDayWork = EmployeeOffdayPlan::where('employee_id', $employee)->first();
        $shiftTimeInMints = $employeeOffDayWork->getPlan->getShift->treat_as_full_day_minutes + $employeeOffDayWork->getPlan->getShift->grace_time + $employeeOffDayWork->getPlan->getShift->early_out_grace_minutes;
        $offDayWorkCount = $offDayWorkDayCount * $shiftTimeInMints;
        $basicSalary = $employeeSalary->basic_salary;
        if ($employeeOffDayWork->getPlan->offday_config_type == 'Salary Based') {
            if ($employeeOffDayWork->getPlan->salary_rate_type == 'Basic Rate') {
                $offDayWorkSalary = ($basicSalary / 60) * $offDayWorkCount;
            } else {
                $offDayWorkSalary = (($basicSalary * $employeeOffDayWork->getPlan->offday_multiplier) / 60) * $offDayWorkCount;
            }
        } else {
            $offDayWorkSalary = ($employeeOffDayWork->getPlan->custom_offday_rate / 60) * $offDayWorkCount;
        }
        return ceil(round($offDayWorkSalary,2));
    }

    public function deductionAmount($lateCount, $excessiveLateCount, $earlyExitCount, $absentCount, $employeeSalary){
        $deductionRule = DeductionPlan::first();
//        dd($deductionRule);
        //late deduction amount calculation
        if ($deductionRule->late_deduction_days != null && $lateCount >= $deductionRule->late_deduction_days){
            $lateDeduction = intdiv($lateCount, $deductionRule->late_deduction_days) * $deductionRule->late_salary_deduction_rate;
            if ($deductionRule->calculation_type == 'basic_salary'){
                $lateDeductionAmount = $lateDeduction * ($employeeSalary->basic_salary/30);
            }else{
                $lateDeductionAmount = $lateDeduction * ($employeeSalary->gross_salary/30);
            }
        }else{
            $lateDeductionAmount = 0;
        }
        Log::info('Late Deduction Amount [' . $lateDeductionAmount . ']');

        //excessive late deduction amount calculation

        if ($deductionRule->excessive_late_deduction_days != null && $lateCount >= $deductionRule->excessive_late_deduction_days){
            $excessiveLateDeduction = intdiv($excessiveLateCount, $deductionRule->excessive_late_deduction_days) * $deductionRule->excessive_late_salary_deduction_rate;
            if ($deductionRule->calculation_type == 'basic_salary'){
                $excessiveLateDeductionAmount = $excessiveLateDeduction * ($employeeSalary->basic_salary/30);
            }else{
                $excessiveLateDeductionAmount = $excessiveLateDeduction * ($employeeSalary->gross_salary/30);
            }
        }else{
            $excessiveLateDeductionAmount = 0;
        }
        Log::info('Excessive Late Deduction Amount [' . $excessiveLateDeductionAmount . ']');

        //absent deduction amount calculation
        if ($deductionRule->absent_deduction_days != null && $absentCount >= $deductionRule->absent_deduction_days){
            $absentDeduction = intdiv($absentCount, $deductionRule->absent_deduction_days) * $deductionRule->absent_salary_deduction_rate;
            if ($deductionRule->calculation_type == 'basic_salary'){
                $absentDeductionAmount = $absentDeduction * ($employeeSalary->basic_salary/30);
            }else{
                $absentDeductionAmount = $absentDeduction * ($employeeSalary->gross_salary/30);
            }
        }else{
            $absentDeductionAmount = 0;
        }
        Log::info('Absent Deduction Amount [' . $absentDeductionAmount . ']');

        //early exit deduction amount calculation
        if ($deductionRule->early_out_deduction_days != null && $absentCount >= $deductionRule->early_out_deduction_days){
            $earlyExitDeduction = intdiv($earlyExitCount, $deductionRule->early_out_deduction_days) * $deductionRule->early_out_salary_deduction_rate;
            if ($deductionRule->calculation_type == 'basic_salary'){
                $earlyExitDeductionAmount = $earlyExitDeduction * ($employeeSalary->basic_salary/30);
            }else{
                $earlyExitDeductionAmount = $earlyExitDeduction * ($employeeSalary->gross_salary/30);
            }
        }else{
            $earlyExitDeductionAmount = 0;
        }
        Log::info('Early Exit Deduction Amount [' . $earlyExitDeductionAmount . ']');

        return $lateDeductionAmount + $excessiveLateDeductionAmount + $absentDeductionAmount + $earlyExitDeductionAmount;
    }

    public function bonusWithSalaryCalculation($employee, $employeeSalary, $salary_month){
        $bonusData = Bonus::with('getBatch')->where('employee_id', $employee)
            ->whereHas('getBatch', function ($query) use ($salary_month) {
                $query->where('salary_month', $salary_month)
                    ->where('approval_status', 'approved');
            })->get();
        if ($bonusData->count() > 0){
            $totalBonus = $bonusData->sum('amount');
        }
        return $totalBonus;

    }

    public function salaryProcess($data, $processId = null)
    {
        $salary_month = $data['salary_month'];
        $firstDayOfSalaryMonth = Carbon::parse($salary_month)->copy()->startOfMonth();
        $employees = $this->findEmployees($data, $firstDayOfSalaryMonth);

        $total_employees = count($employees);
        if ($total_employees == 0) {
            throw new \Exception('Eligible Employees not found.');
        }

        $total_salary = 0;
        $employeeData = [];
        foreach ($employees as $employee) {
            $startDate = Carbon::createFromFormat('Y-m', $salary_month)->startOfMonth();
            $endDate = Carbon::createFromFormat('Y-m', $salary_month)->endOfMonth();

            $employeeAttendance = Attendance::where('employee_id', $employee->id)
                ->whereBetween('in_time', [$startDate, $endDate])
                ->get();//
            $absentData = $this->absentCalculate($employee, $startDate, $endDate, $employeeAttendance);
            $employeeSalary = EmployeeSalaryBreakdown::where('employee_id', $employee->id)->first();
            $lateCount = $employeeAttendance->where('in_status', 'Late')->count();
            $excessiveLateCount = $employeeAttendance->where('in_status', 'Excessive-Late')->count();
            $earlyExitCount = $employeeAttendance->where('out_status', 'Early-Exit')->count();

            $overtimeCount = $employeeAttendance->sum('overtime');
            if ($overtimeCount > 0) {
                $overTimeSalary = $this->overTimeSalary($employee->id, $employeeSalary, $overtimeCount);
            } else {
                $overTimeSalary = 0;
            }

            $offDayWorkCount = $employeeAttendance->where('shift_type', 'Off-Day')->count();
            if ($offDayWorkCount > 0) {
                $offDayWorkSalary = $this->offDayWorkSalary($employee->id, $employeeSalary, $offDayWorkCount);
            } else {
                $offDayWorkSalary = 0;
            }
            $deductionAmount = $this->deductionAmount($lateCount, $excessiveLateCount, $earlyExitCount, $absentData['absent_count'], $employeeSalary);
            $bonusAmount = $this->bonusWithSalaryCalculation($employee->id, $employeeSalary, $salary_month);
            $salary_amount = $employeeSalary->gross_salary + $offDayWorkSalary + $overTimeSalary + $bonusAmount - $deductionAmount;
            $total_salary += $salary_amount;
            $employeeData[] = [
                'employee_id' => $employee->id,
                'absent_count' => $absentData['absent_count'],
                'absent_dates' => $absentData['absent_dates'],
                'salary' => $employeeSalary->gross_salary,
                'leaves_count' => $absentData['leave_count'],
                'late_count' => $lateCount,
                'excessive_late_count' => $excessiveLateCount,
                'early_exit_count' => $earlyExitCount,
                'overtime_count' => $overtimeCount,
                'overtime_amount' => $overTimeSalary,
                'offday_work_count' => $offDayWorkCount,
                'offday_work_salary' => $offDayWorkSalary,
                'deduction_amount' => $deductionAmount,
                'bonus_amount' => $bonusAmount,
                'total_salary' => $salary_amount,
            ];
        }
        Log::info('Total Payroll Amount ' . $total_salary);
        $data['amount'] = $total_salary;
//        dd($data);
        DB::transaction(function () use ($data, $employeeData, $total_salary, $total_employees, $processId) {
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
                    'salary_month' => $data['salary_month'],
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
                    'salary_month' => $data['salary_month'],
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
                    'excessive_late_count' => $employee['late_count'],
                    'early_exit_count' => $employee['early_exit_count'],
                    'overtime_count' => $employee['overtime_count'],
                    'overtime_amount' => $employee['overtime_amount'],
                    'offday_work_count' => $employee['offday_work_count'],
                    'offday_work_salary' => $employee['offday_work_salary'],
                    'deduction_amount' => $employee['deduction_amount'],
                    'bonus_amount' => $employee['bonus_amount'],
                    'total_salary' => $employee['total_salary'],
                ]);
            }
            Log::info('Payroll Created: ' . $salary->id);
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
        Log::info('Deleting Old Bonus Data');
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
