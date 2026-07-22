<?php

use App\Models\User;
use App\Enums\UserType;
use App\Models\ClaimExpense\ExpenseType;
use App\Models\ClaimExpense\ExpenseApplication;
use App\Models\Employee\Employee;
use App\Models\Company\Company;
use App\Models\Company\Group;
use App\Models\Company\CompanyType;
use App\Models\Setting\GeneralSetting;
use Innovity\ApprovalEngine\Models\Workflow;
use Innovity\ApprovalEngine\Models\WorkflowStep;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::factory()->create([
        'user_type' => UserType::Group,
        'name' => 'Test Admin'
    ]);

    // Give permissions to the test user
    $permissions = [
        'expense-types.view',
        'expense-types.create',
        'expense-types.edit',
        'expense-types.delete',
        'claim-expenses.view',
        'claim-expenses.create',
        'claim-expenses.delete'
    ];
    foreach ($permissions as $p) {
        \Spatie\Permission\Models\Permission::firstOrCreate(['name' => $p, 'guard_name' => 'web']);
    }
    $this->admin->givePermissionTo($permissions);

    $group = Group::create([
        'name' => 'Test Group',
        'short_name' => 'TG',
        'status' => 'active',
    ]);

    $companyType = CompanyType::create([
        'name' => 'Test Type',
        'short_name' => 'TT',
        'status' => 'active',
    ]);

    $this->company = Company::create([
        'group_id' => $group->id,
        'type_id' => $companyType->id,
        'name' => 'Test Company',
        'short_name' => 'TC',
        'email' => 'test@company.com',
        'address' => 'Test Address',
        'status' => 'active',
    ]);

    GeneralSetting::create([
        'name' => 'HRMS Test',
        'currency' => '৳',
        'branch_status' => 1,
        'division_status' => 1,
        'department_status' => 1,
        'section_status' => 1,
    ]);

    $this->employee = Employee::factory()->create([
        'full_name' => 'Test Employee',
        'status' => 'active',
    ]);

    // Set employee company
    $this->employee->officeInfo()->create([
        'current_company_id' => $this->company->id,
        'current_business_unit_id' => null,
        'current_division_id' => null,
        'current_department_id' => null,
        'current_section_id' => null,
    ]);
});

test('it can manage expense types', function () {
    $this->actingAs($this->admin);

    // Store
    $response = $this->postJson(route('expense_types.store'), [
        'name' => 'Travel Fuel',
        'description' => 'Gasoline and diesel claims',
        'status' => 'active',
    ]);

    $response->assertStatus(201);
    $this->assertDatabaseHas('expense_types', [
        'name' => 'Travel Fuel',
    ]);

    $expenseType = ExpenseType::first();

    // Edit
    $response = $this->getJson(route('expense_types.edit', $expenseType->id));
    $response->assertStatus(200);

    // Update
    $response = $this->putJson(route('expense_types.update', $expenseType->id), [
        'name' => 'Travel Gas',
        'description' => 'Updated desc',
        'status' => 'active',
    ]);
    $response->assertStatus(200);
    $this->assertDatabaseHas('expense_types', [
        'id' => $expenseType->id,
        'name' => 'Travel Gas',
    ]);

    // Delete
    $response = $this->deleteJson(route('expense_types.delete', $expenseType->id));
    $response->assertStatus(200);
    $this->assertDatabaseMissing('expense_types', [
        'id' => $expenseType->id,
    ]);
});

test('it triggers approval workflow on expense application store', function () {
    $this->actingAs($this->admin);

    $expenseType = ExpenseType::create([
        'name' => 'Office Refreshments',
        'status' => 'active',
    ]);

    // Set up a workflow for claim-expense
    $specificUser = User::factory()->create(['name' => 'Specific Reviewer', 'user_type' => UserType::Company]);
    $workflow = Workflow::create([
        'name' => 'Claim Expense Workflow',
        'module' => 'claim-expense',
        'type' => 'sequential',
        'is_active' => true,
    ]);
    WorkflowStep::create([
        'name' => 'Step 1',
        'workflow_id' => $workflow->id,
        'type' => 'specific-user',
        'user_id' => $specificUser->id,
        'step_order' => 1,
    ]);

    // Submit Application
    $response = $this->postJson(route('claim_expenses.store'), [
        'employee_id' => $this->employee->id,
        'expense_type_id' => $expenseType->id,
        'amount' => 1500.50,
        'payment_method' => 'cash',
        'purpose' => 'Lunch meeting client',
        'remarks' => 'None',
    ]);

    $response->assertStatus(201);
    $this->assertDatabaseHas('expense_applications', [
        'employee_id' => $this->employee->id,
        'amount' => 1500.50,
        'status' => 'pending',
    ]);

    $application = ExpenseApplication::first();
    expect($application->approvalRequests)->toHaveCount(1);
    
    $req = $application->approvalRequests->first();
    expect($req->stepRequests)->toHaveCount(1);
    
    $stepReq = $req->stepRequests->first();
    expect($stepReq->workflow_step_id)->toBe($workflow->steps->first()->id);
    expect($stepReq->status->value)->toBe('pending');
});
