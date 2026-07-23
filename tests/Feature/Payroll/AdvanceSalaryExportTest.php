<?php

use App\Models\User;
use App\Enums\UserType;
use App\Models\Employee\Employee;
use App\Models\Employee\EmployeeOfficeInfo;
use App\Models\Employee\EmployeeSalaryBreakdown;
use App\Models\Employee\EmployeeEligiblePlan;
use App\Models\Company\Company;
use App\Models\Company\Group;
use App\Models\Company\CompanyType;
use App\Models\Company\PayGroup;
use App\Models\Company\Designation;
use App\Models\Company\SalaryGrade;
use App\Models\Company\PayScale;
use App\Models\Payroll\PayrollProcess;
use App\Models\Payroll\AdvanceSalary;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

beforeEach(function () {
    app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    Permission::firstOrCreate(['name' => 'advance-salary.view', 'guard_name' => 'web']);
    Permission::firstOrCreate(['name' => 'advance-salary.create', 'guard_name' => 'web']);
    Permission::firstOrCreate(['name' => 'advance-salary.edit', 'guard_name' => 'web']);
    Permission::firstOrCreate(['name' => 'advance-salary.delete', 'guard_name' => 'web']);
    
    $this->group = Group::create(['name' => 'Test Group', 'status' => 'active']);
    $this->type = CompanyType::create(['name' => 'IT', 'short_name' => 'IT', 'status' => 'active']);
    $this->company = Company::create(['group_id' => $this->group->id, 'type_id' => $this->type->id, 'name' => 'Test Company', 'short_name' => 'TST', 'status' => 'active', 'address' => '123 St']);

    \App\Models\Setting\GeneralSetting::firstOrCreate([], [
        'name' => 'Test HRMS',
        'branch_status' => 0
    ]);
});

test('it exports and prints advance salary process lists and batch details', function () {
    $this->withoutExceptionHandling();

    $hrUser = User::factory()->create(['user_type' => UserType::Company, 'name' => 'HR Manager']);
    $hrUser->givePermissionTo('advance-salary.view');
    $hrUser->givePermissionTo('advance-salary.create');

    $employee = Employee::create([
        'full_name' => 'John Doe',
        'applicant_id' => 'APP123',
        'system_id' => 'SYS123',
        'punch_card_no' => 'P123',
        'status' => 'active'
    ]);

    $designation = Designation::create([
        'designation_level' => 1,
        'company_designation' => 'Software Engineer 1',
        'status' => 'active'
    ]);

    EmployeeOfficeInfo::create([
        'employee_id' => $employee->id,
        'current_company_id' => $this->company->id,
        'current_designation_id' => $designation->id,
        'date_of_join' => '2020-01-01',
    ]);

    $grade = SalaryGrade::create([
        'grade_code' => 'G1',
        'grade_name' => 'Grade 1',
        'status' => 'active'
    ]);

    $payGroup = PayGroup::create([
        'title' => 'Monthly Staff',
        'payroll_frequency' => 'Monthly',
        'salary_processing_day' => '25',
        'status' => 'active'
    ]);

    $payScale = PayScale::create([
        'title' => 'Scale A',
        'grade_id' => $grade->id,
        'pay_group_id' => $payGroup->id,
        'min_salary' => 20000,
        'max_salary' => 50000,
        'status' => 'active'
    ]);

    EmployeeSalaryBreakdown::create([
        'employee_id' => $employee->id,
        'pay_scale_id' => $payScale->id,
        'gross_salary' => 30000,
        'basic_salary' => 21000,
        'house_allowance' => 3000,
        'transport_allowance' => 2000,
        'food_allowance' => 2000,
        'medical_allowance' => 1000,
        'other_earnings' => 1000,
        'basic_salary_percentage' => 70,
        'house_allowance_percentage' => 10,
        'transport_allowance_percentage' => 6.67,
        'food_allowance_percentage' => 6.67,
        'medical_allowance_percentage' => 3.33,
        'other_earnings_percentage' => 3.33,
        'status' => 'active'
    ]);

    EmployeeEligiblePlan::create([
        'employee_id' => $employee->id,
        'bonus_plan_from' => '2020-01-01',
        'bonus_plan_status' => 'active',
    ]);

    // Create an advance salary batch
    $response = $this->actingAs($hrUser)->post(route('advance-salary.store'), [
        'company_id' => $this->company->id,
        'branch_id' => null,
        'division_id' => null,
        'department_id' => null,
        'section_id' => null,
        'pay_group_id' => $payGroup->id,
        'employee_id' => $employee->id,
        'deduction_month' => '2026-07',
        'amount_type' => 'fixed',
        'amount_value' => 5000.00,
        'salary_month' => '2026-07',
        'reason' => 'Festival preparation'
    ]);

    $response->assertJson([
        'success' => true,
        'redirect_url' => route('advance-salary.index')
    ]);

    $process = PayrollProcess::latest()->first();
    expect($process)->not->toBeNull();
    expect($process->type)->toBe('advance');

    // Test export and print routes
    $this->withoutMiddleware();

    // Index-level export and print
    $response = $this->actingAs($hrUser)->get(route('advance-salary.export.excel'));
    $response->assertStatus(200);
    $this->assertTrue(str_contains($response->headers->get('content-disposition'), 'attachment; filename=advance_salary_processes_'));

    $response = $this->actingAs($hrUser)->get(route('advance-salary.print'));
    $response->assertStatus(200);
    $response->assertViewIs('payroll.advance_salary.print_index');

    // Process-level export and print
    $response = $this->actingAs($hrUser)->get(route('advance-salary.process.export.excel', $process->id));
    $response->assertStatus(200);
    $this->assertTrue(str_contains($response->headers->get('content-disposition'), 'attachment; filename=advance_salary_allocations_'));

    $response = $this->actingAs($hrUser)->get(route('advance-salary.process.print', $process->id));
    $response->assertStatus(200);
    $response->assertViewIs('payroll.advance_salary.print_process');
});
