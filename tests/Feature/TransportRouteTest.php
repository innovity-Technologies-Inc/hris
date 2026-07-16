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

test('route map routes are accessible', function () {
    // Add permissions
    $permissions = [
        'employee-transport.create',
        'employee-transport.view',
        'employee-transport.edit',
        'employee-transport.delete',
    ];
    foreach ($permissions as $permission) {
        if (!Permission::where('name', $permission)->exists()) {
            Permission::create(['name' => $permission]);
        }
    }
    $this->user->givePermissionTo($permissions);

    $group = \App\Models\Company\Group::create([
        'name' => 'Test Group',
        'short_name' => 'TG',
        'status' => 'active',
    ]);

    $companyType = \App\Models\Company\CompanyType::create([
        'name' => 'Test Type',
        'short_name' => 'TT',
        'status' => 'active',
    ]);

    $company = \App\Models\Company\Company::create([
        'group_id' => $group->id,
        'type_id' => $companyType->id,
        'name' => 'Test Company',
        'short_name' => 'TC',
        'address' => 'Test Address',
        'status' => 'active',
    ]);

    // Create a route map
    $routeMap = \App\Models\Transport\RouteMap::create([
        'route_name' => 'Test Route',
        'start_point' => 'Start',
        'end_point' => 'End',
        'status' => 'Active',
    ]);

    // Test Index
    $response = $this->actingAs($this->user)->get(route('transport.route_maps.index'));
    $response->assertStatus(200);

    // Test Create Page
    $response = $this->actingAs($this->user)->get(route('transport.route_maps.create'));
    $response->assertStatus(200);

    // Test Store
    $response = $this->actingAs($this->user)->post(route('transport.route_maps.store'), [
        'route_name' => 'Stored Route',
        'start_point' => 'Stored Start',
        'end_point' => 'Stored End',
        'via_points' => ['Stop 1', 'Stop 2'],
        'status' => 'Active',
    ]);
    $response->assertRedirect(route('transport.route_maps.index'));
    $this->assertDatabaseHas('route_maps', ['route_name' => 'Stored Route']);

    // Test Edit Page
    $response = $this->actingAs($this->user)->get(route('transport.route_maps.edit', $routeMap->id));
    $response->assertStatus(200);

    // Test Update
    $response = $this->actingAs($this->user)->put(route('transport.route_maps.update', $routeMap->id), [
        'route_name' => 'Updated Route',
        'start_point' => 'Start',
        'end_point' => 'End',
        'via_points' => ['Stop A', 'Stop B'],
        'status' => 'Active',
    ]);
    $response->assertRedirect(route('transport.route_maps.index'));
    $this->assertDatabaseHas('route_maps', ['id' => $routeMap->id, 'route_name' => 'Updated Route']);

    // Test Delete
    $response = $this->actingAs($this->user)->delete(route('transport.route_maps.destroy', $routeMap->id));
    $response->assertJson(['success' => true]);
    $this->assertDatabaseMissing('route_maps', ['id' => $routeMap->id]);
});

