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
use App\Models\Plan\BonusPlan;
use Innovity\ApprovalEngine\Models\Workflow;
use Innovity\ApprovalEngine\Models\ApprovalStepRequest;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

beforeEach(function () {
    app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    Permission::firstOrCreate(['name' => 'bonuses.create', 'guard_name' => 'web']);
    Permission::firstOrCreate(['name' => 'bonuses.view', 'guard_name' => 'web']);
    Permission::firstOrCreate(['name' => 'bonuses.edit', 'guard_name' => 'web']);
    Permission::firstOrCreate(['name' => 'bonuses.delete', 'guard_name' => 'web']);
    
    $this->group = Group::create(['name' => 'Test Group', 'status' => 'active']);
    $this->type = CompanyType::create(['name' => 'IT', 'short_name' => 'IT', 'status' => 'active']);
    $this->company = Company::create(['group_id' => $this->group->id, 'type_id' => $this->type->id, 'name' => 'Test Company', 'short_name' => 'TST', 'status' => 'active', 'address' => '123 St']);

    \App\Models\Setting\GeneralSetting::firstOrCreate([], [
        'name' => 'Test HRMS',
        'branch_status' => 0
    ]);
});

test('it processes bonus using central approval engine sequential workflow', function () {
    $this->withoutExceptionHandling();

    // Create spatie roles
    $hrRole = Role::firstOrCreate(['name' => 'HR Manager', 'guard_name' => 'web']);
    
    // Setup sequential workflow for 'bonus'
    $workflow = Workflow::create([
        'name' => 'Bonus Approval Workflow',
        'module' => 'bonus',
        'type' => 'sequential',
        'total_steps' => 1,
        'is_active' => true
    ]);
    
    $step = $workflow->steps()->create([
        'name' => 'HR Approval',
        'step_order' => 1,
        'type' => 'role-user',
        'required_user_type' => 'company',
        'role_id' => $hrRole->id
    ]);

    // Create users
    $hrUser = User::factory()->create(['user_type' => UserType::Company, 'name' => 'HR Manager']);
    $hrUser->assignRole($hrRole);
    $hrUser->givePermissionTo('bonuses.edit');

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

    // Create a bonus plan
    $bonusPlan = BonusPlan::create([
        'pay_group_id' => $payGroup->id,
        'name' => 'Test Festival Bonus',
        'bonus_type' => 'festival',
        'bonus_config_type' => 'Custom',
        'custom_rate' => 15000.00,
        'status' => 'active'
    ]);

    // Link employee to bonus plan
    DB::table('employee_bonus_plans')->insert([
        'employee_id' => $employee->id,
        'plan_id' => $bonusPlan->id,
        'status' => 'active',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    // Create and store bonus batch
    $admin = User::factory()->create(['user_type' => UserType::Company]);
    $admin->givePermissionTo('bonuses.create');
    $response = $this->actingAs($admin)->postJson(route('bonus.store'), [
        'company_id' => $this->company->id,
        'branch_id' => null,
        'division_id' => null,
        'department_id' => null,
        'section_id' => null,
        'pay_group_id' => $payGroup->id,
        'salary_month' => '2026-07',
        'plan_ids' => [$bonusPlan->id]
    ]);

    $response->assertJson([
        'success' => true,
        'redirect_url' => route('bonus.index')
    ]);
    
    $process = PayrollProcess::latest()->first();

    expect($process->approval_status)->toBe('pending');
    expect($process->total_amount)->toEqual(15000.00);

    // Verify workflow step request is created
    $stepRequest = ApprovalStepRequest::whereHas('approvalRequest', function ($q) use ($process) {
        $q->where('approvable_id', $process->id)->where('approvable_type', 'App\Models\Payroll\PayrollProcess');
    })->first();

    expect($stepRequest)->not->toBeNull();
    expect($stepRequest->status->value)->toBe('pending');

    // Approve the step request using HR Manager
    $response = $this->actingAs($hrUser)->postJson(route('approval.action', $stepRequest->id), [
        'action' => 'approve',
        'comments' => 'Approve bonus batch'
    ]);

    $response->assertStatus(200);
    
    // Refresh and verify it is approved
    $process->refresh();
    expect($process->approval_status)->toBe('approved');

    // Test export and print routes
    $this->withoutMiddleware();
    
    // Index-level export and print
    $response = $this->actingAs($hrUser)->get(route('bonus.export.excel'));
    $response->assertStatus(200);
    $this->assertTrue(str_contains($response->headers->get('content-disposition'), 'attachment; filename=bonus_and_reward_processes_'));

    $response = $this->actingAs($hrUser)->get(route('bonus.print'));
    $response->assertStatus(200);
    $response->assertViewIs('payroll.bonus.print_index');

    // Process-level export and print
    $response = $this->actingAs($hrUser)->get(route('bonus.process.export.excel', $process->id));
    $response->assertStatus(200);
    $this->assertTrue(str_contains($response->headers->get('content-disposition'), 'attachment; filename=bonus_eligible_employees_'));

    $response = $this->actingAs($hrUser)->get(route('bonus.process.print', $process->id));
    $response->assertStatus(200);
    $response->assertViewIs('payroll.bonus.print_process');
});
