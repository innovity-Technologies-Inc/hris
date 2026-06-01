<?php

use App\Models\Employee\Employee;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Create necessary permissions and roles
    Permission::firstOrCreate(['name' => 'employee-management.nid-verification', 'guard_name' => 'web']);
    $role = Role::firstOrCreate(['name' => 'HR Manager', 'guard_name' => 'web']);
    $role->givePermissionTo('employee-management.nid-verification');

    $this->admin = User::factory()->create([
        'user_type' => 'Company',
    ]);
    $this->admin->assignRole($role);

    $this->employeeUser = User::factory()->create([
        'user_type' => 'Employee',
    ]);

    $this->employee = Employee::factory()->create([
        'nid' => '1234567890123',
        'is_nid_verified' => false
    ]);
});

it('allows authorized users to verify NID', function () {
    $this->withoutMiddleware();
    $this->actingAs($this->admin);

    $response = $this->postJson(route('employee.profile.verify_nid', $this->employee->id));

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'message' => 'NID verified successfully.'
        ]);

    $this->employee->refresh();
    expect($this->employee->is_nid_verified)->toBeTrue();
});

it('denies NID verification for users without permission', function () {
    $this->withoutMiddleware();
    $userWithoutPermission = User::factory()->create(['user_type' => 'Company']);
    $this->actingAs($userWithoutPermission);

    $response = $this->postJson(route('employee.profile.verify_nid', $this->employee->id));

    $response->assertStatus(403);
    
    $this->employee->refresh();
    expect($this->employee->is_nid_verified)->toBeFalse();
});

it('denies NID verification for Employee user type even with permission', function () {
    $this->withoutMiddleware();
    // Even if we mistakenly give the permission to an employee role
    $employeeRole = Role::firstOrCreate(['name' => 'Employee', 'guard_name' => 'web']);
    $employeeRole->givePermissionTo('employee-management.nid-verification');
    
    $this->employeeUser->assignRole($employeeRole);
    $this->actingAs($this->employeeUser);

    $response = $this->postJson(route('employee.profile.verify_nid', $this->employee->id));

    $response->assertStatus(403)
        ->assertJson([
            'success' => false,
            'message' => 'Employees are not allowed to verify NID.'
        ]);

    $this->employee->refresh();
    expect($this->employee->is_nid_verified)->toBeFalse();
});
