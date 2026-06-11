<?php

use App\Models\User;
use App\Models\Employee\Employee;
use App\Models\Employee\EmployeeEmploymentHistory;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    // Setup permissions
    app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    Permission::create(['name' => 'employee-management.view']);
});

test('employment history view handles missing joining_date gracefully', function () {
    // 1. Create a user and employee
    $user = User::factory()->create(['user_type' => \App\Enums\UserType::Group]);
    $user->givePermissionTo('employee-management.view');
    
    $employee = Employee::factory()->create();

    // 2. Create employment history with MISSING joining_date (simulating the bug)
    EmployeeEmploymentHistory::create([
        'employee_id' => $employee->id,
        'histories' => [
            [
                'company' => 'Legacy Corp', // Wrong key
                'designation' => 'Developer',
                // joining_date is missing
            ]
        ],
        'status' => 'active'
    ]);

    // 3. Act as the user and visit the profile page
    $response = $this->actingAs($user)
        ->get(route('employee.profile.employment_history', $employee->id));

    // 4. Assert
    $response->assertStatus(200);
    $response->assertSee('Legacy Corp');
    $response->assertSee('Developer');
    $response->assertSee('N/A'); // Should show N/A for missing date
});

test('employment history view handles correct data correctly', function () {
    $user = User::factory()->create(['user_type' => \App\Enums\UserType::Group]);
    $user->givePermissionTo('employee-management.view');
    
    $employee = Employee::factory()->create();

    EmployeeEmploymentHistory::create([
        'employee_id' => $employee->id,
        'histories' => [
            [
                'company_name' => 'Modern Solutions',
                'designation' => 'Senior Developer',
                'joining_date' => '2020-01-01',
                'end_date' => '2022-12-31'
            ]
        ],
        'status' => 'active'
    ]);

    $response = $this->actingAs($user)
        ->get(route('employee.profile.employment_history', $employee->id));

    $response->assertStatus(200);
    $response->assertSee('Modern Solutions');
    $response->assertSee('Senior Developer');
    $response->assertSee('Jan 2020');
    $response->assertSee('Dec 2022');
});
