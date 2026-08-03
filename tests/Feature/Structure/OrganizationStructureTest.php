<?php

use App\Models\User;
use App\Models\Structure\OrganizationStructure;
use App\Models\Company\Group;
use App\Models\Company\Company;
use App\Models\Employee\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\Company\CompanyType;
use App\Models\Company\Division;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::factory()->create(['user_type' => \App\Enums\UserType::Group]);
    
    // Seed general setting to avoid views issue
    \App\Models\Setting\GeneralSetting::updateOrCreate(['id' => 1], [
        'name' => 'Test Company',
        'company_name' => 'Test Company',
        'branch_status' => 1,
        'currency' => 'USD',
    ]);

    // Give required permissions
    \Spatie\Permission\Models\Permission::firstOrCreate(['name' => 'members.create', 'guard_name' => 'web']);
    \Spatie\Permission\Models\Permission::firstOrCreate(['name' => 'members.view', 'guard_name' => 'web']);
    \Spatie\Permission\Models\Permission::firstOrCreate(['name' => 'members.edit', 'guard_name' => 'web']);
    $this->admin->givePermissionTo(['members.create', 'members.view', 'members.edit']);

    $this->group = Group::create(['name' => 'Test Group', 'status' => 'active']);
    
    $companyType = CompanyType::create([
        'name' => 'Private Limited',
        'short_name' => 'PVT',
        'status' => 'active',
    ]);

    $this->company = Company::create([
        'name' => 'Test Company',
        'short_name' => 'TC',
        'group_id' => $this->group->id,
        'type_id' => $companyType->id,
        'address' => 'Test Address',
        'status' => 'active',
    ]);

    $this->division = Division::create([
        'name' => 'Engineering',
        'short_name' => 'ENG',
        'status' => 'active',
        'company_id' => $this->company->id,
    ]);

    $this->employee = Employee::factory()->create();
});

it('can store a board member with company_id being null', function () {
    $response = $this->actingAs($this->admin)->post(route('organization-structure.store'), [
        'member_type' => 'Board Member',
        'type' => 'company',
        'name' => 'Board Member Name',
        'position' => 'Director',
        'email' => 'board@example.com',
        'contact_no' => '01700000000',
        'group_id' => $this->group->id,
        'company_id' => null,
        'status' => 'active',
    ]);

    $response->assertRedirect(route('organization-structure.index'));
    $this->assertDatabaseHas('organization_structure', [
        'name' => 'Board Member Name',
        'member_type' => 'Board Member',
        'company_id' => null,
    ]);
});

it('can store a key member with company_id being null', function () {
    $response = $this->actingAs($this->admin)->post(route('organization-structure.store'), [
        'member_type' => 'Key Member',
        'type' => 'division',
        'position' => 'Division Key Person',
        'group_id' => $this->group->id,
        'division_id' => $this->division->id,
        'company_id' => null,
        'employee_id' => $this->employee->id,
        'status' => 'active',
    ]);

    $response->assertRedirect(route('organization-structure.index'));
    $this->assertDatabaseHas('organization_structure', [
        'member_type' => 'Key Member',
        'company_id' => null,
        'employee_id' => $this->employee->id,
    ]);
});

it('can update a member and set company_id to null', function () {
    $member = OrganizationStructure::create([
        'member_type' => 'Board Member',
        'type' => 'Company',
        'name' => 'Original Name',
        'position' => 'Director',
        'email' => 'original@example.com',
        'contact_no' => '01711111111',
        'group_id' => $this->group->id,
        'company_id' => $this->company->id,
        'status' => 'Active',
    ]);

    $response = $this->actingAs($this->admin)->put(route('organization-structure.update', $member->id), [
        'member_type' => 'Board Member',
        'type' => 'company',
        'name' => 'Updated Board Member',
        'position' => 'Director',
        'email' => 'original@example.com',
        'contact_no' => '01711111111',
        'group_id' => $this->group->id,
        'company_id' => null,
        'status' => 'active',
    ]);

    $response->assertRedirect(route('organization-structure.index'));
    $this->assertDatabaseHas('organization_structure', [
        'id' => $member->id,
        'name' => 'Updated Board Member',
        'company_id' => null,
    ]);
});
