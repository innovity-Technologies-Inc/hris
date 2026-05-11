<?php

use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Setup basic organization structure
    DB::table('groups')->insert(['id' => 1, 'name' => 'Test Group', 'status' => 'active']);
    
    DB::table('companies')->insert([
        ['id' => 1, 'name' => 'Company A', 'group_id' => 1, 'status' => 'active'],
        ['id' => 2, 'name' => 'Company B', 'group_id' => 1, 'status' => 'active'],
    ]);

    DB::table('company_locations')->insert([
        ['id' => 1, 'company_id' => 1, 'name' => 'Location A1', 'status' => 'active'],
        ['id' => 2, 'company_id' => 1, 'name' => 'Location A2', 'status' => 'active'],
    ]);

    DB::table('departments')->insert([
        ['id' => 1, 'company_id' => 1, 'location_id' => 1, 'department_name' => 'Dept A1-1', 'status' => 'active'],
        ['id' => 2, 'company_id' => 1, 'location_id' => 1, 'department_name' => 'Dept A1-2', 'status' => 'active'],
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
    // Emp 1 & 2 in Dept 1 (Loc 1, Comp 1)
    DB::table('employee_office_infos')->insert([
        ['employee_id' => 1, 'current_company_id' => 1, 'current_business_unit_id' => 1, 'current_department_id' => 1],
        ['employee_id' => 2, 'current_company_id' => 1, 'current_business_unit_id' => 1, 'current_department_id' => 1],
    ]);
    // Emp 3 in Dept 2 (Loc 1, Comp 1)
    DB::table('employee_office_infos')->insert([
        ['employee_id' => 3, 'current_company_id' => 1, 'current_business_unit_id' => 1, 'current_department_id' => 2],
    ]);
    // Emp 4 in Loc 2 (Comp 1)
    DB::table('employee_office_infos')->insert([
        ['employee_id' => 4, 'current_company_id' => 1, 'current_business_unit_id' => 2, 'current_department_id' => null],
    ]);
    // Emp 5 in Comp 2
    DB::table('employee_office_infos')->insert([
        ['employee_id' => 5, 'current_company_id' => 2, 'current_business_unit_id' => null, 'current_department_id' => null],
    ]);
});

test('Group user can see all employees', function () {
    $user = User::factory()->create(['user_type' => 'Group']);
    Auth::login($user);

    expect(Employee::count())->toBe(5);
});

test('Company user can see only employees in their company', function () {
    // User linked to Emp 1 (Company 1)
    $user = User::factory()->create([
        'user_type' => 'Company',
        'employee_id' => 1
    ]);
    Auth::login($user);

    // Should see Emp 1, 2, 3, 4 (all in Company 1)
    expect(Employee::count())->toBe(4);
});

test('Business Unit user can see only employees in their location', function () {
    // User linked to Emp 1 (Location 1)
    $user = User::factory()->create([
        'user_type' => 'Business Unit',
        'employee_id' => 1
    ]);
    Auth::login($user);

    // Should see Emp 1, 2, 3 (all in Location 1)
    expect(Employee::count())->toBe(3);
});

test('Department user can see only employees in their department', function () {
    // User linked to Emp 1 (Department 1)
    $user = User::factory()->create([
        'user_type' => 'Department',
        'employee_id' => 1
    ]);
    Auth::login($user);

    // Should see Emp 1, 2 (all in Department 1)
    expect(Employee::count())->toBe(2);
});

test('Employee user can see only their own record', function () {
    $user = User::factory()->create([
        'user_type' => 'Employee',
        'employee_id' => 1
    ]);
    Auth::login($user);

    expect(Employee::count())->toBe(1);
    expect(Employee::first()->id)->toBe(1);
});

test('Scoped user can only see organizational units in their scope', function () {
    // Department user from Dept 1 (Location 1, Company 1)
    $user = User::factory()->create([
        'user_type' => 'Department',
        'employee_id' => 1
    ]);
    Auth::login($user);

    // Since Department model also uses OrganizationScoped
    // They should only see their own department
    expect(\App\Models\Department::count())->toBe(1);
    expect(\App\Models\Department::first()->id)->toBe(1);
});
