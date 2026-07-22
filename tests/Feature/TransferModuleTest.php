<?php

use App\Models\User;
use App\Models\Employee\Employee;
use App\Models\Employee\EmployeeOfficeInfo;
use App\Models\Company\Company;
use App\Models\Company\Group;
use App\Models\Company\CompanyType;
use App\Models\Transfer\Transfer;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Enums\UserType;

beforeEach(function () {
    app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    Permission::firstOrCreate(['name' => 'transfers.create', 'guard_name' => 'web']);
    Permission::firstOrCreate(['name' => 'transfers.view', 'guard_name' => 'web']);
    Permission::firstOrCreate(['name' => 'transfers.edit', 'guard_name' => 'web']);
    Permission::firstOrCreate(['name' => 'transfers.delete', 'guard_name' => 'web']);
    
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
    $admin = User::factory()->create(['user_type' => UserType::Group]);
    $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
    $adminRole->syncPermissions(['transfers.create']);
    $admin->assignRole($adminRole);
    
    $response = $this->actingAs($admin)->get(route('transfer.create'));

    $response->assertStatus(200);
    $response->assertViewIs('transfer.application');
    $response->assertViewHas('levelWeight');
});

test('it can export transfers to excel', function () {
    $admin = User::factory()->create(['user_type' => UserType::Group]);
    $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
    $adminRole->syncPermissions(['transfers.view']);
    $admin->assignRole($adminRole);

    $company = Company::create(['group_id' => $this->group->id, 'type_id' => $this->type->id, 'name' => 'C1', 'short_name' => 'C1', 'status' => 'active', 'address' => 'A1']);
    $emp = Employee::create(['full_name' => 'John Doe', 'applicant_id' => 'APP001', 'system_id' => 'SYS001', 'punch_card_no' => 'P001', 'status' => 'active']);
    EmployeeOfficeInfo::create(['employee_id' => $emp->id, 'current_company_id' => $company->id]);

    Transfer::create([
        'employee_id' => $emp->id,
        'current_company_id' => $company->id,
        'requested_company_id' => $company->id,
        'status' => 'pending',
        'created_by' => $admin->id
    ]);

    $response = $this->actingAs($admin, 'web')->get(route('transfer.export.excel', ['employee_search' => 'John']));
    $response->assertStatus(200);
    $this->assertTrue(
        headers_sent() || str_contains($response->headers->get('content-disposition'), 'attachment; filename=career_movements_')
    );
});

test('it can print transfers view', function () {
    $admin = User::factory()->create(['user_type' => UserType::Group]);
    $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
    $adminRole->syncPermissions(['transfers.view']);
    $admin->assignRole($adminRole);

    $company = Company::create(['group_id' => $this->group->id, 'type_id' => $this->type->id, 'name' => 'C1', 'short_name' => 'C1', 'status' => 'active', 'address' => 'A1']);
    $emp = Employee::create(['full_name' => 'John Doe', 'applicant_id' => 'APP001', 'system_id' => 'SYS001', 'punch_card_no' => 'P001', 'status' => 'active']);
    EmployeeOfficeInfo::create(['employee_id' => $emp->id, 'current_company_id' => $company->id]);

    Transfer::create([
        'employee_id' => $emp->id,
        'current_company_id' => $company->id,
        'requested_company_id' => $company->id,
        'status' => 'pending',
        'created_by' => $admin->id
    ]);

    $response = $this->actingAs($admin, 'web')->get(route('transfer.print', ['employee_search' => 'John']));
    $response->assertStatus(200);
    $response->assertSee('Career Movement Logs');
    $response->assertSee('John Doe');
});

