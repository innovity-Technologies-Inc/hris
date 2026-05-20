<?php

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Create a user and assign necessary permissions
    $this->user = User::factory()->create();
    $role = Role::create(['name' => 'Admin']);
    $permissions = [
        'vehicles.view',
        'vehicles.create',
        'assign-driver.view',
        'assign-driver.create',
    ];
    foreach ($permissions as $permission) {
        Permission::create(['name' => $permission]);
    }
    $role->syncPermissions($permissions);
    $this->user->assignRole($role);

    // Setup for Vehicle Driver create
    \App\Models\Company\Designation::create([
        'company_designation' => 'Driver',
        'designation_level' => 1,
        'status' => 'Active',
    ]);
});

test('vehicle create route is accessible', function () {
    $response = $this->actingAs($this->user)
        ->get(route('transport.vehicles.create'));

    $response->assertStatus(200);
});

test('vehicle show route is accessible', function () {
    // We need a vehicle to show
    $vehicle = \App\Models\Transport\Vehicle::create([
        'vehicle_category' => 'Car',
        'model_number' => 'TEST-123',
        'manufacture_year' => 2023,
        'fuel_type' => 'Petrol',
        'purchase_type' => 'Purchase',
        'ownership_type' => 'Company-owned',
        'status' => 'Active',
    ]);

    $response = $this->actingAs($this->user)
        ->get(route('transport.vehicles.show', $vehicle->id));

    $response->assertStatus(200);
});

test('vehicle driver create route is accessible', function () {
    $response = $this->actingAs($this->user)
        ->get(route('transport.vehicle_drivers.create'));

    $response->assertStatus(200);
});

