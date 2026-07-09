<?php

use App\Models\User;
use App\Enums\UserType;
use App\Models\Employee\Employee;
use App\Models\Employee\EmployeeOfficeInfo;
use App\Models\Company\Company;
use App\Models\Company\Group;
use App\Models\Company\CompanyType;
use App\Models\Transfer\Transfer;
use Innovity\ApprovalEngine\Models\Workflow;
use Innovity\ApprovalEngine\Models\ApprovalStepRequest;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

beforeEach(function () {
    app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    Permission::firstOrCreate(['name' => 'transfers.create', 'guard_name' => 'web']);
    Permission::firstOrCreate(['name' => 'transfers.view', 'guard_name' => 'web']);
    Permission::firstOrCreate(['name' => 'transfers.edit', 'guard_name' => 'web']);
    
    $this->group = Group::create(['name' => 'Test Group', 'status' => 'active']);
    $this->type = CompanyType::create(['name' => 'IT', 'short_name' => 'IT', 'status' => 'active']);
    
    \App\Models\Setting\GeneralSetting::firstOrCreate([], [
        'name' => 'Test HRMS',
        'branch_status' => 1
    ]);
    \App\Models\Setting\TransferSetting::firstOrCreate([], [
        'employee_transfer_level' => 'company',
        'supervisor_transfer_level' => 'company'
    ]);
});

test('it processes transfer using central approval engine sequential workflow', function () {
    $this->withoutExceptionHandling();

    // Create spatie roles
    $hrRole = Role::firstOrCreate(['name' => 'HR Manager', 'guard_name' => 'web']);
    
    // Setup sequential workflow for 'career-movement'
    $workflow = Workflow::create([
        'name' => 'Career Movement Workflow',
        'module' => 'career-movement',
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
    $hrUser->givePermissionTo('transfers.edit');

    $employee = Employee::create([
        'full_name' => 'John Doe',
        'applicant_id' => 'APP123',
        'system_id' => 'SYS123',
        'punch_card_no' => 'P123',
        'status' => 'active'
    ]);
    $oldCompany = Company::create(['group_id' => $this->group->id, 'type_id' => $this->type->id, 'name' => 'Old Company', 'short_name' => 'OLD', 'status' => 'active', 'address' => '123 St']);
    $newCompany = Company::create(['group_id' => $this->group->id, 'type_id' => $this->type->id, 'name' => 'New Company', 'short_name' => 'NEW', 'status' => 'active', 'address' => '456 St']);
    
    EmployeeOfficeInfo::create([
        'employee_id' => $employee->id,
        'current_company_id' => $oldCompany->id,
    ]);

    // Create and store transfer application
    $admin = User::factory()->create(['user_type' => UserType::Group]);
    $admin->givePermissionTo('transfers.create');
    $response = $this->actingAs($admin)->postJson(route('transfer.api.store'), [
        'employee_id' => $employee->id,
        'requested_company_id' => $newCompany->id,
        'remarks' => 'Transfer remarks'
    ]);

    $response->assertStatus(200)->assertJson(['success' => true]);
    $transfer = Transfer::latest()->first();

    expect($transfer->status)->toBe('pending');

    // Verify workflow step request is created
    $stepRequest = ApprovalStepRequest::whereHas('approvalRequest', function ($q) use ($transfer) {
        $q->where('approvable_id', $transfer->id)->where('approvable_type', 'transfer');
    })->first();

    expect($stepRequest)->not->toBeNull();
    expect($stepRequest->status->value)->toBe('pending');

    // Approve the step request using HR Manager
    $response = $this->actingAs($hrUser)->postJson(route('approval.action', $stepRequest->id), [
        'action' => 'approve',
        'comments' => 'Approve transfer'
    ]);

    $response->assertStatus(200);
    
    // Refresh transfer and verify it is approved
    $transfer->refresh();
    expect($transfer->status)->toBe('approved');

    // Complete/finalize the transfer using HR Manager
    $response = $this->actingAs($hrUser)->postJson(route('transfer.api.complete', $transfer->id));
    $response->assertStatus(200);

    $transfer->refresh();
    expect($transfer->status)->toBe('completed');

    // Verify employee office info was updated
    $officeInfo = EmployeeOfficeInfo::where('employee_id', $employee->id)->first();
    expect((int)$officeInfo->current_company_id)->toBe($newCompany->id);
});
