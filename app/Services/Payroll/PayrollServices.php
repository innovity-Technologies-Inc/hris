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
        Log::info('Initiating advance salary process.', ['data' => $data, 'process_id' => $processId]);

        return DB::transaction(function () use ($data, $processId) {
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

            $employees = $this->findEmployees($data, $effectiveDate);
            
            $total_advance = 0;
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
                    $total_advance += $calculated_amount;
                    $employeeData[] = [
                        'employee_id' => $employee->id,
                        'amount' => $calculated_amount,
                        'deduction_month' => $deduction_month,
                        'reason' => $reason,
                    ];
                }
            }
            
            $total_employees = count($employeeData);
            if ($total_employees == 0) {
                throw new \Exception('No eligible employees found for advance salary generation.');
            }

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
                AdvanceSalary::where('process_id', $processId)->delete();
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

            Log::info('Advance salary process completed.', ['id' => $process->id]);
        });
    }

    public function advanceDelete($id)
    {
        Log::info('Deleting Advance Salary Process.', ['process_id' => $id]);
        return DB::transaction(function () use ($id) {
            $process = PayrollProcess::findOrFail($id);
            AdvanceSalary::where('process_id', $id)->delete();
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

    public function arrearDelete($id)
    {
        Log::info('Deleting Arrear Process.', ['process_id' => $id]);
        return DB::transaction(function () use ($id) {
            $process = PayrollProcess::findOrFail($id);
            Arrear::where('process_id', $id)->delete();
            $process->delete();
        });
    }

    public function arrearStatusUpdate($id, $status)
    {
        $arrear = Arrear::findOrFail($id);
        $arrear->update(['status' => $status]);
    }

    public function advanceStatusUpdate($id, $status)
    {
        $advance = AdvanceSalary::findOrFail($id);
        $advance->update(['status' => $status]);
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

    public function salaryProcess($data, $processId = null)
    {
        Log::info('Initiating salary process calculation.', ['data' => $data, 'process_id' => $processId]);

        return DB::transaction(function () use ($data, $processId) {
            // If we are updating, we must first ROLLBACK previous deductions/payments
            // so they can be accurately picked up by the new calculation.
            if ($processId != null) {
                Log::info('Updating existing salary process: Rolling back previous data.', ['process_id' => $processId]);
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
                
                if (!$employeeSalary) continue;

                // Base Salary Calculation
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

                $otAttendances = $employeeAttendance->where('overtime', '>', 0);
                $overTimeSalary = $this->overTimeSalary($employee->id, $employeeSalary, $otAttendances, $totalMonthlyHours, $workingHoursPerDay, $frequency, $workingDaysPerCycle);

                $offDayAttendances = $employeeAttendance->where('shift_type', 'Off-Day');
                $offDayWorkSalary = $this->offDayWorkSalary($employee->id, $employeeSalary, $offDayAttendances, $totalMonthlyHours, $workingHoursPerDay, $frequency, $workingDaysPerCycle);

                $deductionData = $this->deductionAmount($lateCount, $excessiveLateCount, $earlyExitCount, $absentData['absent_count'], $employeeSalary, $workingDaysPerCycle, $frequency, $workingHoursPerDay);
                $deductionAmount = $deductionData['total'];
                
                $penalties = \App\Models\Payroll\EmployeePenalty::where('employee_id', $employee->id)
                    ->where('status', 'approved')
                    ->whereBetween('occurrence_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                    ->get();
                $penaltyAmount = $penalties->sum('penalty_amount');
                foreach($penalties as $pen) $penaltiesToUpdate[] = $pen->id;

                $advances = AdvanceSalary::where('employee_id', $employee->id)
                    ->where('status', 'approved')
                    ->where('deduction_month', '<=', $salary_month)
                    ->get();
                $advanceDeductionAmount = $advances->sum('amount');

                $arrears = Arrear::where('employee_id', $employee->id)
                    ->where('status', 'approved')
                    ->where('payment_month', '<=', $salary_month)
                    ->get();
                $arrearAmount = $arrears->sum('amount');

                $salary_amount = $calculatedGrossSalary + $offDayWorkSalary + $overTimeSalary + $arrearAmount - $deductionAmount - $penaltyAmount - $advanceDeductionAmount;
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
                    'offday_work_count' => $offDayAttendances->count(),
                    'offday_work_salary' => $offDayWorkSalary,
                    'deduction_amount' => $deductionAmount,
                    'late_deduction_amount' => $deductionData['late_deduction_amount'],
                    'excessive_late_deduction_amount' => $deductionData['excessive_late_deduction_amount'],
                    'absent_deduction_amount' => $deductionData['absent_deduction_amount'],
                    'early_exit_deduction_amount' => $deductionData['early_exit_deduction_amount'],
                    'penalty_amount' => $penaltyAmount,
                    'advance_deduction_amount' => $advanceDeductionAmount,
                    'arrear_amount' => $arrearAmount,
                    'bonus_amount' => 0,
                    'total_salary' => $salary_amount,
                    'advance_ids' => $advances->pluck('id')->toArray(),
                    'arrear_ids' => $arrears->pluck('id')->toArray(),
                ];
            }

            if ($processId == null) {
                $process = PayrollProcess::create([
                    'batch_id' => uniqid('Salary_', true),
                    'company_id' => $data['company_id'],
                    'branch_id' => $data['branch_id'],
                    'division_id' => $data['division_id'],
                    'department_id' => $data['department_id'],
                    'section_id' => $data['section_id'],
                    'pay_group_id' => $data['pay_group_id'],
                    'salary_month' => $salary_month,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'type' => 'salary',
                    'total_amount' => $total_salary,
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
                    'total_amount' => $total_salary,
                    'generated_by' => Auth::id() ?? null,
                    'total_employee' => $total_employees,
                ]);
            }

            foreach ($employeeData as $emp) {
                Payroll::create([
                    'process_id' => $process->id,
                    'batch_id' => $process->batch_id,
                    'employee_id' => $emp['employee_id'],
                    'salary' => $emp['salary'],
                    'late_count' => $emp['late_count'],
                    'leaves_count' => $emp['leaves_count'],
                    'absent_count' => $emp['absent_count'],
                    'absent_dates' => $emp['absent_dates'],
                    'excessive_late_count' => $emp['excessive_late_count'],
                    'early_exit_count' => $emp['early_exit_count'],
                    'overtime_count' => $emp['overtime_count'],
                    'overtime_amount' => $emp['overtime_amount'],
                    'offday_work_count' => $emp['offday_work_count'],
                    'offday_work_salary' => $emp['offday_work_salary'],
                    'deduction_amount' => $emp['deduction_amount'],
                    'late_deduction_amount' => $emp['late_deduction_amount'],
                    'excessive_late_deduction_amount' => $emp['excessive_late_deduction_amount'],
                    'absent_deduction_amount' => $emp['absent_deduction_amount'],
                    'early_exit_deduction_amount' => $emp['early_exit_deduction_amount'],
                    'penalty_amount' => $emp['penalty_amount'],
                    'advance_deduction_amount' => $emp['advance_deduction_amount'],
                    'arrear_amount' => $emp['arrear_amount'],
                    'bonus_amount' => $emp['bonus_amount'],
                    'total_salary' => $emp['total_salary'],
                ]);

                if (count($emp['advance_ids']) > 0) {
                    AdvanceSalary::whereIn('id', $emp['advance_ids'])->update(['status' => 'deducted']);
                }

                if (count($emp['arrear_ids']) > 0) {
                    Arrear::whereIn('id', $emp['arrear_ids'])->update(['status' => 'paid']);
                }
            }
            
            if (count($penaltiesToUpdate) > 0) {
                \App\Models\Payroll\EmployeePenalty::whereIn('id', $penaltiesToUpdate)->update(['status' => 'deducted']);
            }
            
            Log::info('Salary process completed and persisted.', ['id' => $process->id]);
        });
    }

    public function rollbackSalaryProcess($id)
    {
        Log::info('Rolling back salary process distributions.', ['process_id' => $id]);
        $process = PayrollProcess::find($id);
        if ($process) {
            $startDate = $process->start_date;
            $endDate = $process->end_date;
            $salaryMonth = $process->salary_month;
            
            $employeeIds = Payroll::where('process_id', $id)->pluck('employee_id');
            
            if ($employeeIds->isNotEmpty()) {
                \App\Models\Payroll\EmployeePenalty::whereIn('employee_id', $employeeIds)
                    ->whereBetween('occurrence_date', [$startDate, $endDate])
                    ->where('status', 'deducted')
                    ->update(['status' => 'approved']);

                AdvanceSalary::whereIn('employee_id', $employeeIds)
                    ->where('deduction_month', $salaryMonth)
                    ->where('status', 'deducted')
                    ->update(['status' => 'approved']);

                Arrear::whereIn('employee_id', $employeeIds)
                    ->where('payment_month', $salaryMonth)
                    ->where('status', 'paid')
                    ->update(['status' => 'approved']);
            }

            Payroll::where('process_id', $id)->delete();
        }
    }

    public function salaryDelete($id)
    {
        Log::info('Initiating deletion of salary process data.', ['process_id' => $id]);
        return DB::transaction(function () use ($id) {
            $this->rollbackSalaryProcess($id);
            $process = PayrollProcess::findOrFail($id);
            $process->delete();
            Log::info('Salary Process record deleted successfully.', ['process_id' => $id]);
        });
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
