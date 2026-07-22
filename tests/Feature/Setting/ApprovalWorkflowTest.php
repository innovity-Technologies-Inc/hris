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
    $specificUser = User::factory()->create(['name' => 'Specific Reviewer', 'user_type' => UserType::Company]);

    $response = $this->postJson(route('setting.approval_workflows.store'), [
        'module_name' => 'profile-update',
        'type' => 'sequential',
        'is_active' => '1',
        'steps' => [
            [
                'type' => 'user-type',
                'required_user_type' => 'section'
            ],
            [
                'type' => 'role-user',
                'required_user_type' => 'department',
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
    expect($step1->required_user_type)->toBe('section');

    $step2 = $workflow->steps[1];
    expect($step2->type)->toBe('role-user');
    expect($step2->required_user_type)->toBe('department');
    expect((int)$step2->role_id)->toBe($role->id);

    $step3 = $workflow->steps[2];
    expect($step3->type)->toBe('specific-user');
    expect((int)$step3->user_id)->toBe($specificUser->id);
});

test('it rejects sequential workflow if step order violates authority level hierarchy', function () {
    $this->actingAs($this->admin);

    $role = Role::firstOrCreate(['name' => 'HR Manager', 'guard_name' => 'web']);
    
    $response = $this->postJson(route('setting.approval_workflows.store'), [
        'module_name' => 'profile-update',
        'type' => 'sequential',
        'is_active' => '1',
        'steps' => [
            [
                'type' => 'user-type',
                'required_user_type' => 'department' // Weight 4
            ],
            [
                'type' => 'user-type',
                'required_user_type' => 'section' // Weight 5 (Lower authority - invalid!)
            ]
        ]
    ]);

    $response->assertStatus(422);
    $response->assertJsonFragment([
        'message' => 'Step 2 (level: section) cannot have a lower authority level than Step 1 (level: department) in a sequential workflow.'
    ]);
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

test('it prevents creating multiple workflows for the same module', function () {
    $this->actingAs($this->admin);

    Workflow::create([
        'name' => 'Profile Update Workflow',
        'module' => 'profile-update',
        'type' => 'sequential',
        'total_steps' => 1
    ]);

    $response = $this->postJson(route('setting.approval_workflows.store'), [
        'module_name' => 'profile-update',
        'type' => 'sequential',
        'steps' => [
            [
                'type' => 'user-type',
                'required_user_type' => 'section'
            ]
        ]
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['module_name']);
});

test('it auto-approves steps if requester has higher authority or is the resolved approver', function () {
    $this->actingAs($this->admin);

    $workflow = Workflow::create([
        'name' => 'Profile Update Workflow',
        'module' => 'profile-update',
        'type' => 'sequential',
        'total_steps' => 3,
        'is_active' => true
    ]);

    $step1 = WorkflowStep::create([
        'workflow_id' => $workflow->id,
        'name' => 'Step 1',
        'step_order' => 1,
        'type' => 'user-type',
        'required_user_type' => 'section'
    ]);

    $step2 = WorkflowStep::create([
        'workflow_id' => $workflow->id,
        'name' => 'Step 2',
        'step_order' => 2,
        'type' => 'user-type',
        'required_user_type' => 'division'
    ]);

    $step3 = WorkflowStep::create([
        'workflow_id' => $workflow->id,
        'name' => 'Step 3',
        'step_order' => 3,
        'type' => 'user-type',
        'required_user_type' => 'company'
    ]);

    $requesterEmp = Employee::factory()->create();
    $requesterUser = User::factory()->create([
        'user_type' => 'division',
        'employee_id' => $requesterEmp->id
    ]);
    $requesterEmp->update(['user_id' => $requesterUser->id]);

    EmployeeOfficeInfo::create([
        'employee_id' => $requesterEmp->id,
        'current_company_id' => 1,
        'current_division_id' => 2,
        'current_department_id' => 3,
        'current_section_id' => 4,
        'current_business_unit_id' => 5,
    ]);

    $approvable = ProfileUpdateRequest::create([
        'employee_id' => $requesterEmp->id,
        'section' => 'personal_info',
        'previous_data' => [],
        'requested_data' => [],
        'status' => 'pending',
        'created_by' => $requesterUser->id,
    ]);

    $masterRequest = app(\Innovity\ApprovalEngine\Services\WorkflowGenerator::class)->generate($approvable, 'profile-update');

    $stepRequests = $masterRequest->stepRequests()->orderBy('id')->get();

    expect($stepRequests)->toHaveCount(3);
    
    $sr1 = $stepRequests->where('workflow_step_id', $step1->id)->first();
    expect($sr1->status->value)->toBe('approved');
    expect($sr1->comments)->toContain('Target user level (division) has higher authority than required level (section)');

    $sr2 = $stepRequests->where('workflow_step_id', $step2->id)->first();
    expect($sr2->status->value)->toBe('approved');
    expect($sr2->comments)->toContain('Requester is the resolved approver');

    $sr3 = $stepRequests->where('workflow_step_id', $step3->id)->first();
    expect($sr3->status->value)->toBe('pending');
});

test('it can store and update a workflow with inclusion and exclusion scopes', function () {
    $editPermission = \Spatie\Permission\Models\Permission::firstOrCreate(['name' => 'approval-workflows.edit', 'guard_name' => 'web']);
    $this->admin->givePermissionTo($editPermission);

    $this->actingAs($this->admin);

    $response = $this->postJson(route('setting.approval_workflows.store'), [
        'module_name' => 'profile-update',
        'type' => 'sequential',
        'scope_type' => 'user_type',
        'includer_user_types' => ['division', 'department'],
        'exclude_scope_type' => 'role',
        'exclude_role_ids' => [1, 2],
        'steps' => [
            [
                'type' => 'user-type',
                'required_user_type' => 'section'
            ]
        ]
    ]);

    $response->assertStatus(200);

    $workflow = Workflow::where('module', 'profile-update')->first();
    expect($workflow->includer_user_types)->toBe(['division', 'department']);
    expect($workflow->exclude_role_ids)->toBe([1, 2]);

    // Test Update
    $updateResponse = $this->putJson(route('setting.approval_workflows.update', $workflow->id), [
        'module_name' => 'profile-update',
        'type' => 'sequential',
        'scope_type' => 'role',
        'includer_role_ids' => [3],
        'exclude_scope_type' => 'specific_user',
        'exclude_user_ids' => [5, 6],
        'steps' => [
            [
                'type' => 'user-type',
                'required_user_type' => 'section'
            ]
        ]
    ]);

    $updateResponse->assertStatus(200);

    $workflow = $workflow->fresh();
    expect($workflow->includer_role_ids)->toBe([3]);
    expect($workflow->includer_user_types)->toBeNull();
    expect($workflow->exclude_user_ids)->toBe([5, 6]);
    expect($workflow->exclude_role_ids)->toBeNull();
});

test('it filters by requester includers and bypasses others', function () {
    $this->actingAs($this->admin);

    // 1. Create a workflow that only applies to division creators
    $workflow = Workflow::create([
        'name' => 'Profile Update Workflow',
        'module' => 'profile-update',
        'type' => 'sequential',
        'total_steps' => 1,
        'is_active' => true,
        'includer_user_types' => ['division']
    ]);

    $step = WorkflowStep::create([
        'workflow_id' => $workflow->id,
        'name' => 'Step 1',
        'step_order' => 1,
        'type' => 'user-type',
        'required_user_type' => 'company'
    ]);

    // Case A: Creator is division -> needs approval (pending status)
    $divEmp = Employee::factory()->create();
    $divUser = User::factory()->create([
        'user_type' => 'division',
        'employee_id' => $divEmp->id
    ]);
    $divEmp->update(['user_id' => $divUser->id]);

    EmployeeOfficeInfo::create([
        'employee_id' => $divEmp->id,
        'current_company_id' => 1,
        'current_division_id' => 2,
        'current_department_id' => 3,
        'current_section_id' => 4,
        'current_business_unit_id' => 5,
    ]);

    $approvableDiv = ProfileUpdateRequest::create([
        'employee_id' => $divEmp->id,
        'section' => 'personal_info',
        'previous_data' => [],
        'requested_data' => [],
        'status' => 'pending',
        'created_by' => $divUser->id,
    ]);

    $requestDiv = app(\Innovity\ApprovalEngine\Services\WorkflowGenerator::class)->generate($approvableDiv, 'profile-update');
    expect($requestDiv->status->value)->toBe('pending');
    expect($approvableDiv->fresh()->status)->toBe('pending');

    // Case B: Creator is department (not in includers) -> auto-approves
    $deptEmp = Employee::factory()->create();
    $deptUser = User::factory()->create([
        'user_type' => 'department',
        'employee_id' => $deptEmp->id
    ]);
    $deptEmp->update(['user_id' => $deptUser->id]);

    EmployeeOfficeInfo::create([
        'employee_id' => $deptEmp->id,
        'current_company_id' => 1,
        'current_division_id' => 2,
        'current_department_id' => 3,
        'current_section_id' => 4,
        'current_business_unit_id' => 5,
    ]);

    $approvableDept = ProfileUpdateRequest::create([
        'employee_id' => $deptEmp->id,
        'section' => 'personal_info',
        'previous_data' => [],
        'requested_data' => [],
        'status' => 'pending',
        'created_by' => $deptUser->id,
    ]);

    $requestDept = app(\Innovity\ApprovalEngine\Services\WorkflowGenerator::class)->generate($approvableDept, 'profile-update');
    expect($requestDept->status->value)->toBe('approved');
    expect($approvableDept->fresh()->status)->toBe('approved');
});

test('it bypasses approval if creator matches excluders', function () {
    $this->actingAs($this->admin);

    // Create a workflow with all default includers, but excluders for 'company' user type
    $workflow = Workflow::create([
        'name' => 'Profile Update Workflow',
        'module' => 'profile-update',
        'type' => 'sequential',
        'total_steps' => 1,
        'is_active' => true,
        'exclude_user_types' => ['company']
    ]);

    $step = WorkflowStep::create([
        'workflow_id' => $workflow->id,
        'name' => 'Step 1',
        'step_order' => 1,
        'type' => 'user-type',
        'required_user_type' => 'company'
    ]);

    // Case A: Creator is company (excluded) -> auto-approves
    $compEmp = Employee::factory()->create();
    $compUser = User::factory()->create([
        'user_type' => 'company',
        'employee_id' => $compEmp->id
    ]);
    $compEmp->update(['user_id' => $compUser->id]);

    EmployeeOfficeInfo::create([
        'employee_id' => $compEmp->id,
        'current_company_id' => 1,
        'current_division_id' => 2,
        'current_department_id' => 3,
        'current_section_id' => 4,
        'current_business_unit_id' => 5,
    ]);

    $approvableComp = ProfileUpdateRequest::create([
        'employee_id' => $compEmp->id,
        'section' => 'personal_info',
        'previous_data' => [],
        'requested_data' => [],
        'status' => 'pending',
        'created_by' => $compUser->id,
    ]);

    $requestComp = app(\Innovity\ApprovalEngine\Services\WorkflowGenerator::class)->generate($approvableComp, 'profile-update');
    expect($requestComp->status->value)->toBe('approved');
    expect($approvableComp->fresh()->status)->toBe('approved');

    // Case B: Creator is division (not excluded) -> needs approval (pending status)
    $divEmp = Employee::factory()->create();
    $divUser = User::factory()->create([
        'user_type' => 'division',
        'employee_id' => $divEmp->id
    ]);
    $divEmp->update(['user_id' => $divUser->id]);

    EmployeeOfficeInfo::create([
        'employee_id' => $divEmp->id,
        'current_company_id' => 1,
        'current_division_id' => 2,
        'current_department_id' => 3,
        'current_section_id' => 4,
        'current_business_unit_id' => 5,
    ]);

    $approvableDiv = ProfileUpdateRequest::create([
        'employee_id' => $divEmp->id,
        'section' => 'personal_info',
        'previous_data' => [],
        'requested_data' => [],
        'status' => 'pending',
        'created_by' => $divUser->id,
    ]);

    $requestDiv = app(\Innovity\ApprovalEngine\Services\WorkflowGenerator::class)->generate($approvableDiv, 'profile-update');
    expect($requestDiv->status->value)->toBe('pending');
    expect($approvableDiv->fresh()->status)->toBe('pending');
});

test('it can store a sequential approval workflow with a role step', function () {
    $this->actingAs($this->admin);

    $role = Role::firstOrCreate(['name' => 'General Manager', 'guard_name' => 'web']);

    $response = $this->postJson(route('setting.approval_workflows.store'), [
        'module_name' => 'leave',
        'type' => 'sequential',
        'is_active' => '1',
        'steps' => [
            [
                'type' => 'role',
                'role_id' => $role->id
            ]
        ]
    ]);

    $response->assertStatus(200);

    $workflow = Workflow::where('module', 'leave')->first();
    expect($workflow)->not->toBeNull();
    expect($workflow->steps)->toHaveCount(1);

    $step = $workflow->steps[0];
    expect($step->type)->toBe('role');
    expect((int)$step->role_id)->toBe($role->id);
    expect($step->required_user_type)->toBeNull();
});

test('it resolves role step type to all users with that role', function () {
    $resolver = app(ApproverResolver::class);

    $role = Role::firstOrCreate(['name' => 'Auditor', 'guard_name' => 'web']);

    $workflow = Workflow::create([
        'name' => 'Role Test Workflow',
        'module' => 'salary',
        'type' => 'sequential',
        'total_steps' => 1,
    ]);

    $step = WorkflowStep::create([
        'workflow_id' => $workflow->id,
        'name' => 'Step 1',
        'step_order' => 1,
        'type' => 'role',
        'role_id' => $role->id
    ]);

    $userWithRole1 = User::factory()->create(['name' => 'Auditor 1']);
    $userWithRole1->assignRole($role);

    $userWithRole2 = User::factory()->create(['name' => 'Auditor 2']);
    $userWithRole2->assignRole($role);

    $userWithoutRole = User::factory()->create(['name' => 'Normal User']);

    $empRequester = Employee::factory()->create();
    $approvable = ProfileUpdateRequest::create([
        'employee_id' => $empRequester->id,
        'section' => 'personal_info',
        'previous_data' => [],
        'requested_data' => [],
        'status' => 'pending'
    ]);

    $resolvedUsers = $resolver->resolve((string)$step->id, $approvable);
    
    expect($resolvedUsers)->toContain($userWithRole1->id);
    expect($resolvedUsers)->toContain($userWithRole2->id);
    expect($resolvedUsers)->not->toContain($userWithoutRole->id);
});
