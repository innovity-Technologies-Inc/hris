<?php

use App\Models\User;
use App\Models\Employee\Employee;
use App\Models\Employee\EmployeeOfficeInfo;
use App\Models\Company\Company;
use App\Models\Company\Group;
use App\Models\Company\CompanyType;
use App\Models\Transfer\Transfer;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Notification;
use App\Notifications\Transfer\TransferRequestedNotification;
use App\Notifications\Transfer\TransferCompletedNotification;

use App\Enums\UserType;

beforeEach(function () {
    app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    Permission::firstOrCreate(['name' => 'transfers.create', 'guard_name' => 'web']);
    Permission::firstOrCreate(['name' => 'transfers.view', 'guard_name' => 'web']);
    Permission::firstOrCreate(['name' => 'transfers.approve', 'guard_name' => 'web']);
    Permission::firstOrCreate(['name' => 'transfers.edit', 'guard_name' => 'web']);
    
    $this->group = Group::create(['name' => 'Test Group', 'status' => 'active']);
    $this->type = CompanyType::create(['name' => 'IT', 'short_name' => 'IT', 'status' => 'active']);

    // Ensure settings exist
    \App\Models\Setting\GeneralSetting::firstOrCreate([], [
        'name' => 'Test HRMS',
        'branch_status' => 1
    ]);
    \App\Models\Setting\TransferSetting::firstOrCreate([], [
        'employee_transfer_level' => 'company',
        'supervisor_transfer_level' => 'company'
    ]);
});

test('transfer application can be submitted and completed', function () {
    $this->withoutMiddleware();
    Notification::fake();

    // 1. Setup Data
    $admin = User::factory()->create(['user_type' => UserType::Group]);
    $role = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
    $role->syncPermissions(['transfers.create', 'transfers.view', 'transfers.approve', 'transfers.edit']);
    $admin->assignRole($role);

    $approver = User::factory()->create(['user_type' => UserType::Group]);
    $approverRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Approver', 'guard_name' => 'web']);
    $approverRole->syncPermissions(['transfers.approve']);
    $approver->assignRole($approverRole);

    $employee = Employee::create([
        'full_name' => 'John Doe',
        'applicant_id' => 'APP001',
        'system_id' => 'SYS001',
        'punch_card_no' => 'P001',
        'status' => 'active'
    ]);
    
    $oldCompany = Company::create(['group_id' => $this->group->id, 'type_id' => $this->type->id, 'name' => 'Old Company', 'short_name' => 'OLD', 'status' => 'active', 'address' => '123 St']);
    $newCompany = Company::create(['group_id' => $this->group->id, 'type_id' => $this->type->id, 'name' => 'New Company', 'short_name' => 'NEW', 'status' => 'active', 'address' => '456 St']);
    
    EmployeeOfficeInfo::create([
        'employee_id' => $employee->id,
        'current_company_id' => $oldCompany->id,
    ]);

    // 2. Submit Application (API)
    $response = $this->actingAs($admin, 'web')->postJson(route('transfer.api.store'), [
        'employee_id' => $employee->id,
        'requested_company_id' => $newCompany->id,
        'remarks' => 'Promotion transfer',
    ]);

    $response->assertStatus(200)->assertJson(['success' => true]);
    $transferId = $response->json('data.id');

    // 3. Set Approvers
    $this->actingAs($admin)->postJson(route('transfer.api.set_approvers', $transferId), [
        'approver_ids' => [$approver->id]
    ])->assertStatus(200);

    // Verify Notification Sent
    Notification::assertSentTo($approver, TransferRequestedNotification::class);

    // 4. Approve
    $this->actingAs($approver)->postJson(route('transfer.api.approve', $transferId), [
        'remarks' => 'Looks good'
    ])->assertStatus(200);

    // 5. Complete
    $this->actingAs($admin)->postJson(route('transfer.api.complete', $transferId))
        ->assertStatus(200);

    // Verify Office Info Updated
    $updatedOfficeInfo = EmployeeOfficeInfo::where('employee_id', $employee->id)->first();
    expect($updatedOfficeInfo->current_company_id)->toBe($newCompany->id);
});

it('restricts transfer logs based on organizational scope', function () {
    $this->withoutMiddleware();
    
    $admin = User::factory()->create(['user_type' => UserType::Group]);
    
    $company1 = Company::create(['group_id' => $this->group->id, 'type_id' => $this->type->id, 'name' => 'C1', 'short_name' => 'C1', 'status' => 'active', 'address' => 'A1']);
    $company2 = Company::create(['group_id' => $this->group->id, 'type_id' => $this->type->id, 'name' => 'C2', 'short_name' => 'C2', 'status' => 'active', 'address' => 'A2']);

    $emp1 = Employee::create(['full_name' => 'E1', 'applicant_id' => 'A01', 'system_id' => 'S01', 'punch_card_no' => 'P01', 'status' => 'active']);
    EmployeeOfficeInfo::create(['employee_id' => $emp1->id, 'current_company_id' => $company1->id]);
    
    $emp2 = Employee::create(['full_name' => 'E2', 'applicant_id' => 'A02', 'system_id' => 'S02', 'punch_card_no' => 'P02', 'status' => 'active']);
    EmployeeOfficeInfo::create(['employee_id' => $emp2->id, 'current_company_id' => $company2->id]);

    // Create a transfer for each
    Transfer::create([
        'employee_id' => $emp1->id,
        'current_company_id' => $company1->id,
        'requested_company_id' => $company1->id,
        'status' => 'pending',
        'created_by' => $admin->id
    ]);

    Transfer::create([
        'employee_id' => $emp2->id,
        'current_company_id' => $company2->id,
        'requested_company_id' => $company2->id,
        'status' => 'pending',
        'created_by' => $admin->id
    ]);

    // User from Company One
    $user1 = User::factory()->create(['user_type' => UserType::Company, 'employee_id' => $emp1->id]);

    $response = $this->actingAs($user1)->getJson(route('transfer.api.list'));
    $response->assertStatus(200);
    
    // Paginator structure: { success: true, data: { data: [...] } }
    $response->assertJsonCount(1, 'data.data');

    // Group user sees both
    $groupUser = User::factory()->create(['user_type' => UserType::Group]);
    $this->actingAs($groupUser)->getJson(route('transfer.api.list'))
        ->assertStatus(200)
        ->assertJsonCount(2, 'data.data');
});

test('transfer application view loads correctly', function () {
    $this->withoutMiddleware();
    
    $admin = User::factory()->create(['user_type' => UserType::Group]);
    
    $response = $this->actingAs($admin)->get(route('transfer.create'));

    $response->assertStatus(200);
    $response->assertViewIs('transfer.application');
    $response->assertViewHas('levelWeight');
});
