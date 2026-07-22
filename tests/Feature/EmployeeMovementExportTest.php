<?php

use App\Models\User;
use App\Models\Employee\Employee;
use App\Models\Movement\EmployeeMovement;
use App\Enums\UserType;
use Spatie\Permission\Models\Permission;

beforeEach(function () {
    app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    Permission::firstOrCreate(['name' => 'movement.view', 'guard_name' => 'web']);
    
    $this->taPlan = \App\Models\Plan\TAPlan::create([
        'name' => 'Standard TA Plan',
        'short_name' => 'STD_TA',
        'remuneration' => 100.00,
        'status' => 'active'
    ]);
    
    $this->daPlan = \App\Models\Plan\DAPlan::create([
        'name' => 'Standard DA Plan',
        'short_name' => 'STD_DA',
        'remuneration' => 150.00,
        'status' => 'active'
    ]);
});

test('it can access movement index and filter via ajax', function () {
    $admin = User::factory()->create(['user_type' => UserType::Group]);
    $role = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
    $role->syncPermissions(['movement.view']);
    $admin->assignRole($role);

    $employee = Employee::create([
        'full_name' => 'Jane Doe',
        'applicant_id' => 'APP002',
        'system_id' => 'SYS002',
        'punch_card_no' => 'P002',
        'status' => 'active'
    ]);

    EmployeeMovement::create([
        'employee_id' => $employee->id,
        'from_date' => '2026-07-22 09:00:00',
        'to_date' => '2026-07-22 17:00:00',
        'source_address' => 'Office A',
        'source_lat' => 23.8103,
        'source_lng' => 90.4125,
        'destination_address' => 'Office B',
        'dest_lat' => 23.8103,
        'dest_lng' => 90.4125,
        'distance' => 10,
        'ta_plan_id' => $this->taPlan->id,
        'da_plan_id' => $this->daPlan->id,
        'total_days' => 1,
        'total_allowance' => 500,
        'reason' => 'Official visit',
        'status' => 'approved',
        'payment_status' => 'unpaid'
    ]);

    $response = $this->actingAs($admin, 'web')->get(route('movement.index'));
    $response->assertStatus(200);

    // Filter via ajax
    $ajaxResponse = $this->actingAs($admin, 'web')->get(route('movement.index', ['_ajax' => 1, 'keyword' => 'Jane']));
    $ajaxResponse->assertStatus(200);
    $ajaxResponse->assertSee('Jane Doe');
});

test('it can export employee movements to excel', function () {
    $admin = User::factory()->create(['user_type' => UserType::Group]);
    $role = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
    $role->syncPermissions(['movement.view']);
    $admin->assignRole($role);

    $employee = Employee::create([
        'full_name' => 'Jane Doe',
        'applicant_id' => 'APP002',
        'system_id' => 'SYS002',
        'punch_card_no' => 'P002',
        'status' => 'active'
    ]);

    EmployeeMovement::create([
        'employee_id' => $employee->id,
        'from_date' => '2026-07-22 09:00:00',
        'to_date' => '2026-07-22 17:00:00',
        'source_address' => 'Office A',
        'source_lat' => 23.8103,
        'source_lng' => 90.4125,
        'destination_address' => 'Office B',
        'dest_lat' => 23.8103,
        'dest_lng' => 90.4125,
        'distance' => 10,
        'ta_plan_id' => $this->taPlan->id,
        'da_plan_id' => $this->daPlan->id,
        'total_days' => 1,
        'total_allowance' => 500,
        'reason' => 'Official visit',
        'status' => 'approved',
        'payment_status' => 'unpaid'
    ]);

    $response = $this->actingAs($admin, 'web')->get(route('movement.export.excel', ['keyword' => 'Jane']));
    $response->assertStatus(200);
    $this->assertTrue(
        headers_sent() || str_contains($response->headers->get('content-disposition'), 'attachment; filename=travel_movements_')
    );
});

test('it can print employee movements view', function () {
    $admin = User::factory()->create(['user_type' => UserType::Group]);
    $role = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
    $role->syncPermissions(['movement.view']);
    $admin->assignRole($role);

    $employee = Employee::create([
        'full_name' => 'Jane Doe',
        'applicant_id' => 'APP002',
        'system_id' => 'SYS002',
        'punch_card_no' => 'P002',
        'status' => 'active'
    ]);

    EmployeeMovement::create([
        'employee_id' => $employee->id,
        'from_date' => '2026-07-22 09:00:00',
        'to_date' => '2026-07-22 17:00:00',
        'source_address' => 'Office A',
        'source_lat' => 23.8103,
        'source_lng' => 90.4125,
        'destination_address' => 'Office B',
        'dest_lat' => 23.8103,
        'dest_lng' => 90.4125,
        'distance' => 10,
        'ta_plan_id' => $this->taPlan->id,
        'da_plan_id' => $this->daPlan->id,
        'total_days' => 1,
        'total_allowance' => 500,
        'reason' => 'Official visit',
        'status' => 'approved',
        'payment_status' => 'unpaid'
    ]);

    $response = $this->actingAs($admin, 'web')->get(route('movement.print', ['keyword' => 'Jane']));
    $response->assertStatus(200);
    $response->assertSee('Employee Travel Movement Records');
    $response->assertSee('Jane Doe');
});
