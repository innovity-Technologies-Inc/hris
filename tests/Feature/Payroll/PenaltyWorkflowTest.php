<?php

use App\Models\User;
use App\Models\Employee\Employee;
use App\Models\Employee\EmployeeOfficeInfo;
use App\Models\Plan\PenaltyPlan;
use App\Models\Payroll\EmployeePenalty;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Enums\UserType;
use Spatie\Permission\Models\Permission;
use Innovity\ApprovalEngine\Models\Workflow;
use Innovity\ApprovalEngine\Models\WorkflowStep;

uses(RefreshDatabase::class);

beforeEach(function () {
    app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    Permission::firstOrCreate(['name' => 'penalty-management.view', 'guard_name' => 'web']);
    Permission::firstOrCreate(['name' => 'penalty-management.create', 'guard_name' => 'web']);
    
    $this->admin = User::factory()->create(['user_type' => UserType::Group]);
    $this->admin->givePermissionTo('penalty-management.view');
    $this->admin->givePermissionTo('penalty-management.create');

    $this->employee = Employee::factory()->create(['full_name' => 'Test Employee', 'status' => 'active']);
    
    // We must mock office info and general settings since OrganizationScoped reads current company
    $group = \App\Models\Company\Group::create(['name' => 'Test Group', 'status' => 'active']);
    $type = \App\Models\Company\CompanyType::create(['name' => 'IT', 'short_name' => 'IT', 'status' => 'active']);
    $company = \App\Models\Company\Company::create([
        'group_id' => $group->id,
        'type_id' => $type->id,
        'name' => 'Test Company',
        'short_name' => 'TST',
        'status' => 'active',
        'address' => '123 St'
    ]);
    EmployeeOfficeInfo::create([
        'employee_id' => $this->employee->id,
        'current_company_id' => $company->id,
        'date_of_join' => '2020-01-01',
    ]);

    $this->plan = PenaltyPlan::create([
        'title' => 'Late In Penalty',
        'penalty_amount' => 100.00,
        'status' => 'active'
    ]);

    \App\Models\Setting\GeneralSetting::firstOrCreate([], [
        'name' => 'Test HRMS',
        'branch_status' => 0
    ]);
});

test('it triggers approval workflow on penalty creation and handles complete/reject', function () {
    $this->withoutExceptionHandling();

    // Create a workflow for penalty module
    $workflow = Workflow::create([
        'module' => 'penalty',
        'name' => 'Penalty Approval Workflow',
        'type' => 'sequential',
        'total_steps' => 1,
    ]);

    $approver = User::factory()->create(['user_type' => UserType::Company, 'name' => 'Line Manager']);

    WorkflowStep::create([
        'workflow_id' => $workflow->id,
        'name' => 'Step 1',
        'step_order' => 1,
        'type' => 'specific-user',
        'user_id' => $approver->id,
    ]);

    // Create penalty via endpoint
    $response = $this->actingAs($this->admin)->post(route('payroll.penalty.store'), [
        'employee_id' => $this->employee->id,
        'penalty_plan_id' => $this->plan->id,
        'occurrence_date' => '2026-07-23',
        'cause' => 'Late reporting',
        'penalty_amount' => 120.00,
    ]);

    $response->assertStatus(200);
    $response->assertJson(['success' => true]);

    $penalty = EmployeePenalty::latest()->first();
    expect($penalty)->not->toBeNull();
    expect($penalty->status)->toBe('pending');
    expect($penalty->approvalRequests)->toHaveCount(1);

    $approvalRequest = $penalty->approvalRequests->first();
    expect($approvalRequest->status->value)->toBe('pending');

    // Perform approval step
    $stepRequest = $approvalRequest->stepRequests->first();
    expect($stepRequest->status->value)->toBe('pending');

    // Act as approver and approve the step
    $this->actingAs($approver)->post(route('approval.action', $stepRequest->id), [
        'action' => 'approve',
        'comments' => 'Approved penalty',
    ]);

    $penalty->refresh();
    expect($penalty->status)->toBe('approved');

    // Test rejection workflow
    $this->actingAs($this->admin);
    
    $penalty2 = EmployeePenalty::create([
        'employee_id' => $this->employee->id,
        'penalty_plan_id' => $this->plan->id,
        'occurrence_date' => '2026-07-24',
        'cause' => 'Absence without leave',
        'penalty_amount' => 200.00,
    ]);
    $penalty2->refresh();
    
    $penalty2->startWorkflow('penalty');
    expect($penalty2->status)->toBe('pending');

    $request2 = $penalty2->approvalRequests->first();
    $stepRequest2 = $request2->stepRequests->first();

    $response2 = $this->actingAs($approver)->post(route('approval.action', $stepRequest2->id), [
        'action' => 'reject',
        'comments' => 'Invalid penalty reason',
    ]);

    $penalty2->refresh();
    expect($penalty2->status)->toBe('rejected');
});
