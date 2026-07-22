<?php

use App\Models\User;
use App\Enums\UserType;
use App\Models\Employee\Employee;
use App\Models\Leave\Leave;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Setup General Setting
    \App\Models\Setting\GeneralSetting::updateOrCreate(['id' => 1], [
        'name' => 'HRMS Test',
        'currency' => '৳',
        'branch_status' => 1,
    ]);

    $this->admin = User::factory()->create([
        'user_type' => UserType::Group,
        'name' => 'Test Admin'
    ]);

    // Give permission
    $role = Role::create(['name' => 'Admin']);
    Permission::findOrCreate('leaves.view', 'web');
    $role->givePermissionTo('leaves.view');
    $this->admin->assignRole($role);
});

test('it can load the leave logs index page', function () {
    $response = $this->actingAs($this->admin)
        ->get(route('leave.index'));

    $response->assertStatus(200);
});

test('it can filter leave records by organization', function () {
    // Setup companies
    $group = \App\Models\Company\Group::create(['name' => 'Test Group', 'short_name' => 'TG', 'status' => 'active']);
    $type = \App\Models\Company\CompanyType::create(['name' => 'Test Type', 'short_name' => 'TT', 'status' => 'active']);
    
    $companyA = \App\Models\Company\Company::create([
        'name' => 'Company A',
        'short_name' => 'COA',
        'type_id' => $type->id,
        'group_id' => $group->id,
        'address' => 'Addr A',
        'status' => 'active'
    ]);
    
    $companyB = \App\Models\Company\Company::create([
        'name' => 'Company B',
        'short_name' => 'COB',
        'type_id' => $type->id,
        'group_id' => $group->id,
        'address' => 'Addr B',
        'status' => 'active'
    ]);

    $employee1 = Employee::factory()->create(['full_name' => 'Jane Doe']);
    $employee2 = Employee::factory()->create(['full_name' => 'John Smith']);

    \App\Models\Employee\EmployeeOfficeInfo::create([
        'employee_id' => $employee1->id,
        'current_company_id' => $companyA->id,
        'joining_company_id' => $companyA->id,
        'joining_division_id' => 1,
    ]);

    \App\Models\Employee\EmployeeOfficeInfo::create([
        'employee_id' => $employee2->id,
        'current_company_id' => $companyB->id,
        'joining_company_id' => $companyB->id,
        'joining_division_id' => 1,
    ]);

    Leave::create([
        'employee_id' => $employee1->id,
        'from' => '2026-07-22',
        'to' => '2026-07-23',
        'leave_count' => 2,
        'reason' => 'Sickness',
        'status' => 'approved',
    ]);

    Leave::create([
        'employee_id' => $employee2->id,
        'from' => '2026-07-22',
        'to' => '2026-07-23',
        'leave_count' => 2,
        'reason' => 'Sickness',
        'status' => 'approved',
    ]);

    // Request with company filter
    $response = $this->actingAs($this->admin)
        ->get(route('leave.index', ['company' => $companyA->id]));

    $response->assertStatus(200);
    $response->assertViewHas('leaves');
    $records = $response->viewData('leaves');
    expect($records)->toHaveCount(1);
    expect($records->first()->getEmployee->full_name)->toBe('Jane Doe');
});

test('it can print leave records with filters', function () {
    $group = \App\Models\Company\Group::create(['name' => 'Test Group', 'short_name' => 'TG', 'status' => 'active']);
    $type = \App\Models\Company\CompanyType::create(['name' => 'Test Type', 'short_name' => 'TT', 'status' => 'active']);
    
    $companyA = \App\Models\Company\Company::create([
        'name' => 'Company A',
        'short_name' => 'COA',
        'type_id' => $type->id,
        'group_id' => $group->id,
        'address' => 'Addr A',
        'status' => 'active'
    ]);

    $employee1 = Employee::factory()->create(['full_name' => 'Jane Doe']);
    \App\Models\Employee\EmployeeOfficeInfo::create([
        'employee_id' => $employee1->id,
        'current_company_id' => $companyA->id,
        'joining_company_id' => $companyA->id,
        'joining_division_id' => 1,
    ]);

    Leave::create([
        'employee_id' => $employee1->id,
        'from' => '2026-07-22',
        'to' => '2026-07-23',
        'leave_count' => 2,
        'reason' => 'Sickness',
        'status' => 'approved',
    ]);

    $response = $this->actingAs($this->admin)
        ->get(route('leave.print', ['company' => $companyA->id]));

    $response->assertStatus(200);
    $response->assertViewIs('leave.print_index');
    $response->assertViewHas('leaveRecords');
    expect($response->viewData('leaveRecords'))->toHaveCount(1);
});

test('it can export leave records to excel with filters', function () {
    $group = \App\Models\Company\Group::create(['name' => 'Test Group', 'short_name' => 'TG', 'status' => 'active']);
    $type = \App\Models\Company\CompanyType::create(['name' => 'Test Type', 'short_name' => 'TT', 'status' => 'active']);
    
    $companyA = \App\Models\Company\Company::create([
        'name' => 'Company A',
        'short_name' => 'COA',
        'type_id' => $type->id,
        'group_id' => $group->id,
        'address' => 'Addr A',
        'status' => 'active'
    ]);

    $employee1 = Employee::factory()->create(['full_name' => 'Jane Doe']);
    \App\Models\Employee\EmployeeOfficeInfo::create([
        'employee_id' => $employee1->id,
        'current_company_id' => $companyA->id,
        'joining_company_id' => $companyA->id,
        'joining_division_id' => 1,
    ]);

    Leave::create([
        'employee_id' => $employee1->id,
        'from' => '2026-07-22',
        'to' => '2026-07-23',
        'leave_count' => 2,
        'reason' => 'Sickness',
        'status' => 'approved',
    ]);

    $response = $this->actingAs($this->admin)
        ->get(route('leave.export.excel', ['company' => $companyA->id]));

    $response->assertStatus(200);
    expect($response->headers->get('content-type'))->toContain('spreadsheet');
});
