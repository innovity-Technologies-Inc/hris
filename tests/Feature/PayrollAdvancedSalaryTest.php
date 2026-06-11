<?php

use App\Models\User;
use App\Models\Employee\Employee;
use App\Models\Company\Company;
use App\Models\Company\CompanyLocation;
use App\Models\Company\PayGroup;
use App\Models\Payroll\EmployeePenalty;
use App\Enums\UserType;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('salary process correctly calculates hourly frequency and deducts penalties', function () {
    // 1. Prepare data
    app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

    $companyType = \App\Models\Company\CompanyType::create(['name' => 'Type 1', 'short_name' => 'T1', 'status' => 'active']);
    $group = \App\Models\Company\Group::create(['name' => 'Group 1', 'status' => 'active']);
    $company = Company::create(['group_id' => $group->id, 'type_id' => $companyType->id, 'name' => 'Test Company', 'short_name' => 'TC', 'address' => 'N/A', 'status' => 'active']);
    
    // Create Deduction Plan
    \App\Models\Plan\DeductionPlan::firstOrCreate([], [
        'late_deduction_days' => 3,
        'late_salary_deduction_rate' => 0.5,
        'early_out_deduction_days' => 3,
        'early_out_salary_deduction_rate' => 0.5,
        'calculation_type' => 'basic_salary'
    ]);
    
    // Create Hourly Pay Group
    $payGroup = PayGroup::create([
        'title' => 'Hourly Workers',
        'payroll_frequency' => 'Hourly',
        'salary_processing_day' => '1',
        'status' => 'active'
    ]);

    // Create Employee
    $employee = Employee::factory()->create(['full_name' => 'John Doe']);
    
    \App\Models\Employee\EmployeeOfficeInfo::create([
        'employee_id' => $employee->id,
        'current_company_id' => $company->id,
        'weekends' => ['Friday', 'Saturday']
    ]);

    \App\Models\Employee\EmployeeEligiblePlan::create([
        'employee_id' => $employee->id,
        'bonus_plan_status' => 'active',
        'bonus_plan_from' => '2026-01-01'
    ]);

    // Hourly rate embedded in gross_salary
    $hourlyRate = 100;
    \App\Models\Employee\EmployeeSalaryBreakdown::create([
        'employee_id' => $employee->id,
        'gross_salary' => $hourlyRate, 
        'basic_salary' => 60,
        'basic_salary_percentage' => '60',
    ]);

    $grade = \App\Models\Company\SalaryGrade::create(['grade_name' => 'Grade 1', 'status' => 'active']);
    // Mock PayScale Link
    $payScale = \App\Models\Company\PayScale::create([
        'pay_group_id' => $payGroup->id,
        'grade_id' => $grade->id,
        'name' => 'Scale 1',
        'min_salary' => 10000,
        'max_salary' => 50000,
        'status' => 'active'
    ]);
    \App\Models\Employee\EmployeeSalaryBreakdown::where('employee_id', $employee->id)
        ->update(['pay_scale_id' => $payScale->id]);

    $shiftPlan = \App\Models\Plan\ShiftPlan::create([
        'name' => 'Hourly Shift',
        'clock_in_time' => '09:00:00',
        'clock_out_time' => '19:00:00', // 10 hours
        'treat_as_full_day_minutes' => 600,
        'treat_as_half_day_minutes' => 300,
        'active_ind' => 'active'
    ]);

    // Create Attendance (10 hours total)
    \App\Models\Attendance\Attendance::create([
        'employee_id' => $employee->id,
        'in_time' => '2026-06-01 09:00:00',
        'out_time' => '2026-06-01 19:00:00',
        'working_time' => 10,
        'shift_type' => 'Regular',
        'attendance_status' => 'Present',
        'shift_id' => $shiftPlan->id,
    ]);

    // Create Penalty Plan
    $penaltyPlan = \App\Models\Plan\PenaltyPlan::create(['title' => 'Late Policy', 'penalty_amount' => 50, 'status' => 'active']);

    // Create Penalty (Approved)
    $penalty = EmployeePenalty::create([
        'employee_id' => $employee->id,
        'penalty_plan_id' => $penaltyPlan->id,
        'penalty_amount' => 50,
        'occurrence_date' => '2026-06-02',
        'status' => 'approved',
    ]);

    // Process Salary
    $service = new \App\Services\Payroll\PayrollServices();
    $data = [
        'company_id' => $company->id,
        'pay_group_id' => $payGroup->id,
        'start_date' => '2026-06-01',
        'end_date' => '2026-06-30',
        'salary_month' => '2026-06',
        'branch_id' => null,
        'division_id' => null,
        'department_id' => null,
        'section_id' => null,
        'employee_id' => null,
    ];

    $service->salaryProcess($data);

    // Verify
    $payroll = \App\Models\Payroll\Payroll::where('employee_id', $employee->id)->first();
    expect($payroll)->not->toBeNull();
    
    // Hourly Gross Calculation = (24000 / 30 / 8) * 10 = 100 * 10 = 1000
    // Total Salary = 1000 - 50 (Penalty) = 950
    expect($payroll->salary)->toEqual(1000);
    expect($payroll->penalty_amount)->toEqual(50);
    expect($payroll->total_salary)->toEqual(950);

    // Verify penalty status updated
    $penalty->refresh();
    expect($penalty->status)->toEqual('deducted');
});

test('salary process correctly calculates daily frequency', function () {
    app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

    $companyType = \App\Models\Company\CompanyType::create(['name' => 'Type 1', 'short_name' => 'T1', 'status' => 'active']);
    $group = \App\Models\Company\Group::create(['name' => 'Group 1', 'status' => 'active']);
    $company = Company::create(['group_id' => $group->id, 'type_id' => $companyType->id, 'name' => 'Test Company', 'short_name' => 'TC', 'address' => 'N/A', 'status' => 'active']);

    // Create Daily Pay Group
    $payGroup = PayGroup::create([
        'title' => 'Daily Workers',
        'payroll_frequency' => 'Daily',
        'salary_processing_day' => '1',
        'status' => 'active'
    ]);

    $grade = \App\Models\Company\SalaryGrade::create(['grade_name' => 'Grade 1', 'status' => 'active']);
    $payScale = \App\Models\Company\PayScale::create([
        'pay_group_id' => $payGroup->id,
        'grade_id' => $grade->id,
        'name' => 'Scale 1',
        'min_salary' => 10000,
        'max_salary' => 50000,
        'status' => 'active'
    ]);

    $employee = Employee::factory()->create(['full_name' => 'Jane Doe']);
    
    \App\Models\Employee\EmployeeOfficeInfo::create([
        'employee_id' => $employee->id,
        'current_company_id' => $company->id,
        'weekends' => ['Friday', 'Saturday']
    ]);

    \App\Models\Employee\EmployeeEligiblePlan::create([
        'employee_id' => $employee->id,
        'bonus_plan_status' => 'active',
        'bonus_plan_from' => '2026-01-01'
    ]);

    // Daily rate embedded in gross_salary
    $dailyRate = 500;
    \App\Models\Employee\EmployeeSalaryBreakdown::create([
        'employee_id' => $employee->id,
        'pay_scale_id' => $payScale->id,
        'gross_salary' => $dailyRate, 
        'basic_salary' => $dailyRate * 0.6,
        'basic_salary_percentage' => '60',
    ]);

    \App\Models\Plan\DeductionPlan::firstOrCreate([], [
        'late_deduction_days' => 3,
        'late_salary_deduction_rate' => 0.5,
        'early_out_deduction_days' => 3,
        'early_out_salary_deduction_rate' => 0.5,
        'calculation_type' => 'basic_salary'
    ]);

    // Process Salary for 5 days
    $service = new \App\Services\Payroll\PayrollServices();
    $data = [
        'company_id' => $company->id,
        'pay_group_id' => $payGroup->id,
        'start_date' => '2026-06-01',
        'end_date' => '2026-06-05', // 5 days range
        'salary_month' => '2026-06',
        'branch_id' => null,
        'division_id' => null,
        'department_id' => null,
        'section_id' => null,
        'employee_id' => null,
    ];

    $service->salaryProcess($data);

    $payroll = \App\Models\Payroll\Payroll::where('employee_id', $employee->id)->first();
    expect($payroll)->not->toBeNull();
    
    // Daily Gross Calculation = 500 * 5 = 2500
    expect($payroll->salary)->toEqual(2500);
    expect($payroll->total_salary)->toEqual(2500);
});