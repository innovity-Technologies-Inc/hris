<?php

use App\Models\User;
use App\Enums\UserType;
use Spatie\Permission\Models\Role;
use Innovity\ApprovalEngine\Models\Workflow;
use Innovity\ApprovalEngine\Models\WorkflowStep;
use App\Services\ApproverResolver;
use App\Models\Employee\Employee;
use App\Models\Employee\EmployeeOfficeInfo;
use App\Models\Employee\ProfileUpdateRequest;

beforeEach(function () {
    $this->admin = User::factory()->create([
        'user_type' => UserType::Group,
        'name' => 'Test Admin'
    ]);
    
    // Give permissions to create approval workflows
    $permission = \Spatie\Permission\Models\Permission::firstOrCreate(['name' => 'approval-workflows.create', 'guard_name' => 'web']);
    $this->admin->givePermissionTo($permission);
});

test('it can store a sequential approval workflow with dynamic steps', function () {
    $this->actingAs($this->admin);

    $role = Role::firstOrCreate(['name' => 'HR Manager', 'guard_name' => 'web']);
    $specificUser = User::factory()->create(['name' => 'Specific Reviewer']);

    $response = $this->postJson(route('setting.approval_workflows.store'), [
        'module_name' => 'profile-update',
        'type' => 'sequential',
        'is_active' => '1',
        'steps' => [
            [
                'type' => 'user-type',
                'required_user_type' => 'department'
            ],
            [
                'type' => 'role-user',
                'required_user_type' => 'section',
                'role_id' => $role->id
            ],
            [
                'type' => 'specific-user',
                'user_id' => $specificUser->id
            ]
        ]
    ]);

    $response->assertStatus(200);

    $workflow = Workflow::where('module', 'profile-update')->first();
    expect($workflow)->not->toBeNull();
    expect($workflow->steps)->toHaveCount(3);

    $step1 = $workflow->steps[0];
    expect($step1->type)->toBe('user-type');
    expect($step1->required_user_type)->toBe('department');

    $step2 = $workflow->steps[1];
    expect($step2->type)->toBe('role-user');
    expect($step2->required_user_type)->toBe('section');
    expect((int)$step2->role_id)->toBe($role->id);

    $step3 = $workflow->steps[2];
    expect($step3->type)->toBe('specific-user');
    expect((int)$step3->user_id)->toBe($specificUser->id);
});

test('it resolves steps using the approver resolver', function () {
    $resolver = app(ApproverResolver::class);

    // Create a workflow stub
    $workflow = Workflow::create([
        'name' => 'Test Workflow',
        'module' => 'test-module',
        'type' => 'sequential',
        'total_steps' => 2,
    ]);

    // Create a specific user step
    $specificUser = User::factory()->create(['name' => 'Target Reviewer']);
    $stepSpecific = WorkflowStep::create([
        'workflow_id' => $workflow->id,
        'name' => 'Step 1',
        'step_order' => 1,
        'type' => 'specific-user',
        'user_id' => $specificUser->id
    ]);

    // Create an employee and a profile update request to serve as the approvable
    $empRequester = Employee::factory()->create();
    $requesterUser = User::factory()->create([
        'name' => 'Requester',
        'employee_id' => $empRequester->id
    ]);
    $empRequester->update(['user_id' => $requesterUser->id]);

    $approvable = ProfileUpdateRequest::create([
        'employee_id' => $empRequester->id,
        'section' => 'personal_info',
        'previous_data' => [],
        'requested_data' => [],
        'status' => 'pending'
    ]);

    // Resolve specific-user step
    $resolvedUsers = $resolver->resolve((string)$stepSpecific->id, $approvable);
    expect($resolvedUsers)->toBe([$specificUser->id]);

    // Create a role-user step
    $role = Role::firstOrCreate(['name' => 'Section Reviewer', 'guard_name' => 'web']);
    
    // Create employees and users of user_type 'section'
    $empWithRole = Employee::factory()->create();
    $userWithRole = User::factory()->create([
        'user_type' => 'section',
        'name' => 'Section Head with Role',
        'employee_id' => $empWithRole->id
    ]);
    $empWithRole->update(['user_id' => $userWithRole->id]);
    $userWithRole->assignRole($role);

    $empWithoutRole = Employee::factory()->create();
    $userWithoutRole = User::factory()->create([
        'user_type' => 'section',
        'name' => 'Section Head without Role',
        'employee_id' => $empWithoutRole->id
    ]);
    $empWithoutRole->update(['user_id' => $userWithoutRole->id]);

    // Assign office info
    EmployeeOfficeInfo::create([
        'employee_id' => $empWithRole->id,
        'current_section_id' => 10,
        'current_company_id' => 1,
        'current_division_id' => 2,
        'current_department_id' => 3,
        'current_business_unit_id' => 4,
    ]);

    EmployeeOfficeInfo::create([
        'employee_id' => $empWithoutRole->id,
        'current_section_id' => 10,
        'current_company_id' => 1,
        'current_division_id' => 2,
        'current_department_id' => 3,
        'current_business_unit_id' => 4,
    ]);

    EmployeeOfficeInfo::create([
        'employee_id' => $empRequester->id,
        'current_section_id' => 10,
        'current_company_id' => 1,
        'current_division_id' => 2,
        'current_department_id' => 3,
        'current_business_unit_id' => 4,
    ]);

    $stepRoleUser = WorkflowStep::create([
        'workflow_id' => $workflow->id,
        'name' => 'Step 2',
        'step_order' => 2,
        'type' => 'role-user',
        'required_user_type' => 'section',
        'role_id' => $role->id
    ]);

    $resolvedRoleUsers = $resolver->resolve((string)$stepRoleUser->id, $approvable);

    // Should only resolve to the user holding the role
    expect($resolvedRoleUsers)->toContain($userWithRole->id);
    expect($resolvedRoleUsers)->not->toContain($userWithoutRole->id);
});
