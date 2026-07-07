<?php

use App\Models\Employee\Employee;
use App\Models\User;
use App\Enums\UserType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Setup basic organization structure
    DB::table('groups')->insert(['id' => 1, 'name' => 'Test Group', 'status' => 'active']);
    
    DB::table('company_types')->insert(['id' => 1, 'name' => 'Test Type', 'short_name' => 'TT', 'status' => 'active']);

    DB::table('companies')->insert([
        ['id' => 1, 'name' => 'Company A', 'group_id' => 1, 'type_id' => 1, 'short_name' => 'COA', 'address' => 'Addr A', 'status' => 'active'],
        ['id' => 2, 'name' => 'Company B', 'group_id' => 1, 'type_id' => 1, 'short_name' => 'COB', 'address' => 'Addr B', 'status' => 'active'],
    ]);

    DB::table('company_locations')->insert([
        ['id' => 1, 'company_id' => 1, 'name' => 'Location A1', 'location_address' => 'Addr A1', 'status' => 'active'],
        ['id' => 2, 'company_id' => 1, 'name' => 'Location A2', 'location_address' => 'Addr A2', 'status' => 'active'],
    ]);

    DB::table('divisions')->insert([
        ['id' => 1, 'company_id' => 1, 'location_id' => 1, 'name' => 'Div A1', 'short_name' => 'DIVA1', 'status' => 'active'],
    ]);

    DB::table('departments')->insert([
        ['id' => 1, 'company_id' => 1, 'location_id' => 1, 'division_id' => 1, 'department_name' => 'Dept A1-1', 'short_name' => 'DA1', 'status' => 'active'],
        ['id' => 2, 'company_id' => 1, 'location_id' => 1, 'division_id' => 1, 'department_name' => 'Dept A1-2', 'short_name' => 'DA2', 'status' => 'active'],
    ]);

    DB::table('sections')->insert([
        ['id' => 1, 'company_id' => 1, 'location_id' => 1, 'division_id' => 1, 'department_id' => 1, 'name' => 'Section A1-1-1', 'short_name' => 'SA1', 'status' => 'active'],
    ]);

    // Create 5 Employees
    for ($i = 1; $i <= 5; $i++) {
        DB::table('employees')->insert([
            'id' => $i,
            'full_name' => "Employee $i",
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    // Assign Employees to Office Info
    // Emp 1 in Section 1 (Dept 1, Loc 1, Comp 1)
    // Emp 2 in Dept 1 (Loc 1, Comp 1) but no Section
    DB::table('employee_office_infos')->insert([
        ['employee_id' => 1, 'current_company_id' => 1, 'current_business_unit_id' => 1, 'current_division_id' => 1, 'current_department_id' => 1, 'current_section_id' => 1],
        ['employee_id' => 2, 'current_company_id' => 1, 'current_business_unit_id' => 1, 'current_division_id' => 1, 'current_department_id' => 1, 'current_section_id' => null],
    ]);
    // Emp 3 in Dept 2 (Loc 1, Comp 1)
    DB::table('employee_office_infos')->insert([
        ['employee_id' => 3, 'current_company_id' => 1, 'current_business_unit_id' => 1, 'current_division_id' => 1, 'current_department_id' => 2, 'current_section_id' => null],
    ]);
    // Emp 4 in Loc 2 (Comp 1)
    DB::table('employee_office_infos')->insert([
        ['employee_id' => 4, 'current_company_id' => 1, 'current_business_unit_id' => 2, 'current_division_id' => null, 'current_department_id' => null, 'current_section_id' => null],
    ]);
    // Emp 5 in Comp 2
    DB::table('employee_office_infos')->insert([
        ['employee_id' => 5, 'current_company_id' => 2, 'current_business_unit_id' => null, 'current_division_id' => null, 'current_department_id' => null, 'current_section_id' => null],
    ]);
});

test('Group user can see all employees', function () {
    $user = User::factory()->create(['user_type' => UserType::Group]);
    Auth::login($user);

    expect(Employee::count())->toBe(5);
});

test('Company user can see only employees in their company', function () {
    // User linked to Emp 1 (Company 1)
    $user = User::factory()->create([
        'user_type' => UserType::Company,
        'employee_id' => 1
    ]);
    Auth::login($user);

    // Should see Emp 1, 2, 3, 4 (all in Company 1)
    expect(Employee::count())->toBe(4);
});

test('Business Unit user can see only employees in their location', function () {
    // User linked to Emp 1 (Location 1)
    $user = User::factory()->create([
        'user_type' => UserType::BusinessUnit,
        'employee_id' => 1
    ]);
    Auth::login($user);

    // Should see Emp 1, 2, 3 (all in Location 1)
    expect(Employee::count())->toBe(3);
});

test('Department user can see only employees in their department', function () {
    // User linked to Emp 1 (Department 1)
    $user = User::factory()->create([
        'user_type' => UserType::Department,
        'employee_id' => 1
    ]);
    Auth::login($user);

    // Should see Emp 1, 2 (all in Department 1)
    expect(Employee::count())->toBe(2);
});

test('Section user can see only employees in their section', function () {
    // User linked to Emp 1 (Section 1)
    $user = User::factory()->create([
        'user_type' => UserType::Section,
        'employee_id' => 1
    ]);
    Auth::login($user);

    // Should see only Emp 1
    expect(Employee::count())->toBe(1);
    expect(Employee::first()->id)->toBe(1);
});

test('Scoped user can only see organizational units in their scope', function () {
    // Department user from Dept 1 (Location 1, Company 1)
    $user = User::factory()->create([
        'user_type' => UserType::Department,
        'employee_id' => 1
    ]);
    Auth::login($user);

    // Since Department model also uses OrganizationScoped
    // They should only see their own department
    expect(\App\Models\Company\Department::count())->toBe(1);
    expect(\App\Models\Company\Department::first()->id)->toBe(1);
});
