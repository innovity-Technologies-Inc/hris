<?php

use App\Models\User;
use App\Models\Employee\Employee;
use App\Models\Movement\EmployeeMovement;
use App\Models\Movement\EmployeeMovementDetail;
use App\Enums\UserType;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    Permission::firstOrCreate(['name' => 'movement.view', 'guard_name' => 'web']);
    Permission::firstOrCreate(['name' => 'movement.create', 'guard_name' => 'web']);
    Permission::firstOrCreate(['name' => 'movement.edit', 'guard_name' => 'web']);
    Permission::firstOrCreate(['name' => 'movement.hr-approve', 'guard_name' => 'web']);

    $this->taPlan = \App\Models\Plan\TAPlan::create([
        'name' => 'Standard TA Plan',
        'short_name' => 'STD_TA',
        'remuneration' => 15.00,
        'status' => 'active'
    ]);
    
    $this->daPlan = \App\Models\Plan\DAPlan::create([
        'name' => 'Standard DA Plan',
        'short_name' => 'STD_DA',
        'remuneration' => 200.00,
        'status' => 'active'
    ]);
});

test('it can create employee movement with multiple route legs and file attachments', function () {
    Storage::fake('public');

    $employee = Employee::create([
        'full_name' => 'John Doe',
        'applicant_id' => 'APP001',
        'system_id' => 'SYS001',
        'punch_card_no' => 'P001',
        'status' => 'active'
    ]);

    $user = User::factory()->create([
        'user_type' => UserType::Employee,
        'employee_id' => $employee->id,
    ]);
    
    $role = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Employee', 'guard_name' => 'web']);
    $role->syncPermissions(['movement.view', 'movement.create']);
    $user->assignRole($role);

    $attachmentFile1 = UploadedFile::fake()->create('receipt1.jpg', 500);
    $attachmentFile2 = UploadedFile::fake()->create('receipt2.pdf', 1000);

    $payload = [
        'employee_id' => $employee->id,
        'from_date' => '2026-08-05 08:00:00',
        'to_date' => '2026-08-07 18:00:00',
        'distance' => 50.00,
        'total_days' => 3,
        'status' => 'pending',
        'items' => [
            [
                'source_address' => 'Dhaka Office',
                'source_lat' => 23.8103,
                'source_lng' => 90.4125,
                'destination_address' => 'Chittagong Office',
                'dest_lat' => 22.3569,
                'dest_lng' => 91.7832,
                'distance' => 30.00,
                'reason' => 'Client meeting',
                'attachment' => $attachmentFile1,
            ],
            [
                'source_address' => 'Chittagong Office',
                'source_lat' => 22.3569,
                'source_lng' => 91.7832,
                'destination_address' => 'Sylhet Office',
                'dest_lat' => 24.8949,
                'dest_lng' => 91.8687,
                'distance' => 20.00,
                'reason' => 'Site inspection',
                'attachment' => $attachmentFile2,
            ]
        ]
    ];

    $response = $this->actingAs($user, 'web')
        ->post(route('movement.store'), $payload);

    $response->assertStatus(201);
    $response->assertJson([
        'success' => true,
        'message' => 'Resource created successfully.'
    ]);

    $this->assertDatabaseHas('employee_movements', [
        'employee_id' => $employee->id,
        'distance' => 50.00,
        'total_days' => 3,
        'status' => 'pending',
    ]);

    $movement = EmployeeMovement::first();
    $this->assertCount(2, $movement->details);

    $this->assertDatabaseHas('employee_movement_details', [
        'employee_movement_id' => $movement->id,
        'source_address' => 'Dhaka Office',
        'destination_address' => 'Chittagong Office',
        'distance' => 30.00,
        'reason' => 'Client meeting',
    ]);

    $this->assertDatabaseHas('employee_movement_details', [
        'employee_movement_id' => $movement->id,
        'source_address' => 'Chittagong Office',
        'destination_address' => 'Sylhet Office',
        'distance' => 20.00,
        'reason' => 'Site inspection',
    ]);
});

test('it can update employee movement routes and edit details', function () {
    Storage::fake('public');

    $employee = Employee::create([
        'full_name' => 'John Doe',
        'applicant_id' => 'APP001',
        'system_id' => 'SYS001',
        'punch_card_no' => 'P001',
        'status' => 'active'
    ]);

    $user = User::factory()->create([
        'user_type' => UserType::Employee,
        'employee_id' => $employee->id,
    ]);
    
    $role = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Employee', 'guard_name' => 'web']);
    $role->syncPermissions(['movement.view', 'movement.edit']);
    $user->assignRole($role);

    $movement = EmployeeMovement::create([
        'employee_id' => $employee->id,
        'from_date' => '2026-08-05 08:00:00',
        'to_date' => '2026-08-07 18:00:00',
        'distance' => 15.00,
        'total_days' => 3,
        'status' => 'pending',
    ]);

    $detail1 = $movement->details()->create([
        'source_address' => 'Old Source',
        'source_lat' => 23.00,
        'source_lng' => 90.00,
        'destination_address' => 'Old Dest',
        'dest_lat' => 22.00,
        'dest_lng' => 91.00,
        'distance' => 15.00,
        'reason' => 'Old Detail Reason',
    ]);

    $newFile = UploadedFile::fake()->create('receipt_new.jpg', 600);

    $payload = [
        'employee_id' => $employee->id,
        'from_date' => '2026-08-05 08:00:00',
        'to_date' => '2026-08-07 18:00:00',
        'distance' => 45.00,
        'total_days' => 3,
        'status' => 'pending',
        'items' => [
            [
                // Edit existing detail
                'id' => $detail1->id,
                'source_address' => 'New Source 1',
                'source_lat' => 23.10,
                'source_lng' => 90.10,
                'destination_address' => 'New Dest 1',
                'dest_lat' => 22.10,
                'dest_lng' => 91.10,
                'distance' => 25.00,
                'reason' => 'Updated Reason 1',
                'attachment' => $newFile,
            ],
            [
                // Add new detail
                'source_address' => 'New Source 2',
                'source_lat' => 23.20,
                'source_lng' => 90.20,
                'destination_address' => 'New Dest 2',
                'dest_lat' => 22.20,
                'dest_lng' => 91.20,
                'distance' => 20.00,
                'reason' => 'New Leg Reason',
            ]
        ]
    ];

    $response = $this->actingAs($user, 'web')
        ->put(route('movement.update', $movement->id), $payload);

    $response->assertStatus(200);
    $response->assertJson([
        'success' => true,
        'message' => 'Resource updated successfully.'
    ]);

    $this->assertDatabaseHas('employee_movements', [
        'id' => $movement->id,
        'distance' => 45.00,
    ]);

    $this->assertDatabaseHas('employee_movement_details', [
        'id' => $detail1->id,
        'source_address' => 'New Source 1',
        'destination_address' => 'New Dest 1',
        'distance' => 25.00,
        'reason' => 'Updated Reason 1',
    ]);

    $this->assertDatabaseHas('employee_movement_details', [
        'employee_movement_id' => $movement->id,
        'source_address' => 'New Source 2',
        'destination_address' => 'New Dest 2',
        'distance' => 20.00,
    ]);
});

test('approver can save and edit allowances after accepting the workflow', function () {
    $employee = Employee::create([
        'full_name' => 'John Doe',
        'applicant_id' => 'APP001',
        'system_id' => 'SYS001',
        'punch_card_no' => 'P001',
        'status' => 'active'
    ]);

    $admin = User::factory()->create([
        'user_type' => UserType::Group,
    ]);
    
    $role = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
    $role->syncPermissions(['movement.view', 'movement.hr-approve']);
    $admin->assignRole($role);

    $movement = EmployeeMovement::create([
        'employee_id' => $employee->id,
        'from_date' => '2026-08-05 08:00:00',
        'to_date' => '2026-08-07 18:00:00',
        'distance' => 100.00,
        'total_days' => 3,
        // Workflow is accepted (Approved)
        'status' => 'approved',
    ]);

    // Save Allowances using TA plan and DA plan
    $payload = [
        'ta_plan_id' => $this->taPlan->id,
        'da_plan_id' => $this->daPlan->id,
        'total_ta' => 1200.00,
        'total_da' => 600.00,
        'total_allowance' => 1800.00,
    ];

    $response = $this->actingAs($admin, 'web')
        ->put(route('movement.save_allowances', $movement->id), $payload);

    $response->assertStatus(200);
    $response->assertJson([
        'success' => true,
        'message' => 'Allowances updated successfully.'
    ]);

    $this->assertDatabaseHas('employee_movements', [
        'id' => $movement->id,
        'ta_plan_id' => $this->taPlan->id,
        'da_plan_id' => $this->daPlan->id,
        'total_ta' => 1200.00,
        'total_da' => 600.00,
        'total_allowance' => 1800.00,
    ]);

    // Employee cannot update allowances
    $employeeUser = User::factory()->create([
        'user_type' => UserType::Employee,
        'employee_id' => $employee->id,
    ]);
    
    $empRole = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Employee', 'guard_name' => 'web']);
    $employeeUser->assignRole($empRole);

    $failResponse = $this->actingAs($employeeUser, 'web')
        ->put(route('movement.save_allowances', $movement->id), $payload);

    $failResponse->assertStatus(403);
});
