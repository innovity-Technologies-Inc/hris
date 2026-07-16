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
        'vehicles.edit',
        'vehicles.delete',
        'assign-driver.view',
        'assign-driver.create',
        'assign-driver.edit',
        'assign-driver.delete',
        'vehicle-requisition.view',
        'vehicle-requisition.create',
        'vehicle-requisition.edit',
        'vehicle-allocation.view',
        'vehicle-allocation.create',
        'vehicle-allocation.edit',
        'employee-transport.view',
        'employee-transport.create',
        'employee-transport.edit',
        'employee-transport.delete',
    ];
    foreach ($permissions as $permission) {
        Permission::findOrCreate($permission, 'web');
    }
    $role->syncPermissions($permissions);
    $this->user->assignRole($role);

    // Setup for Vehicle Driver create
    \App\Models\Company\Designation::create([
        'company_designation' => 'Driver',
        'short_name' => 'DRV',
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
    $response->assertStatus(201);
    $response->assertJson(['success' => true]);
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
    $response->assertStatus(200);
    $response->assertJson(['success' => true]);
    $this->assertDatabaseHas('route_maps', ['id' => $routeMap->id, 'route_name' => 'Updated Route']);

    // Test Delete
    $response = $this->actingAs($this->user)->delete(route('transport.route_maps.destroy', $routeMap->id));
    $response->assertJson(['success' => true]);
    $this->assertDatabaseMissing('route_maps', ['id' => $routeMap->id]);
});

test('vehicle CRUD and validation behavior', function () {
    $role = Role::firstOrCreate(['name' => 'Admin']);
    $this->user->assignRole($role);

    // 1. Create with missing fields should fail validation
    $response = $this->actingAs($this->user)
        ->postJson(route('transport.vehicles.store'), []);
    $response->assertStatus(422);

    // 2. Create vehicle successfully
    $vehicleData = [
        'vehicle_category' => 'Car',
        'model_number' => 'NEW-CAR-999',
        'manufacture_year' => 2024,
        'fuel_type' => 'Petrol',
        'purchase_type' => 'Purchase',
        'ownership_type' => 'Company-owned',
        'status' => 'Active',
    ];
    $response = $this->actingAs($this->user)
        ->postJson(route('transport.vehicles.store'), $vehicleData);
    $response->assertStatus(201);
    $response->assertJson(['success' => true]);
    $this->assertDatabaseHas('vehicles', ['model_number' => 'NEW-CAR-999']);

    $vehicle = \App\Models\Transport\Vehicle::where('model_number', 'NEW-CAR-999')->first();

    // 3. Update vehicle details
    $updateData = array_merge($vehicleData, ['model_number' => 'UPD-CAR-999']);
    $response = $this->actingAs($this->user)
        ->putJson(route('transport.vehicles.update', $vehicle->id), $updateData);
    $response->assertStatus(200);
    $response->assertJson(['success' => true]);
    $this->assertDatabaseHas('vehicles', ['id' => $vehicle->id, 'model_number' => 'UPD-CAR-999']);

    // 4. Delete vehicle
    $response = $this->actingAs($this->user)
        ->deleteJson(route('transport.vehicles.destroy', $vehicle->id));
    $response->assertStatus(200);
    $response->assertJson(['success' => true]);
    $this->assertDatabaseMissing('vehicles', ['id' => $vehicle->id]);
});

test('vehicle driver assignment CRUD and validation behavior', function () {
    // 1. Setup vehicle and driver
    $vehicle = \App\Models\Transport\Vehicle::create([
        'vehicle_category' => 'Car',
        'model_number' => 'DRV-CAR',
        'manufacture_year' => 2023,
        'fuel_type' => 'Petrol',
        'purchase_type' => 'Purchase',
        'ownership_type' => 'Company-owned',
        'status' => 'Active',
    ]);

    $driver = \App\Models\Employee\Employee::factory()->create();

    // 2. Assign driver
    $assignData = [
        'vehicle_id' => $vehicle->id,
        'driver_id' => $driver->id,
        'start_date' => now()->format('Y-m-d'),
        'end_date' => now()->addDays(7)->format('Y-m-d'),
    ];

    $response = $this->actingAs($this->user)
        ->postJson(route('transport.vehicle_drivers.store'), $assignData);
    $response->assertStatus(201);
    $response->assertJson(['success' => true]);
    $this->assertDatabaseHas('vehicle_drivers', [
        'vehicle_id' => $vehicle->id,
        'driver_id' => $driver->id,
        'status' => 'active'
    ]);

    $assignment = \App\Models\Transport\VehicleDriver::where('vehicle_id', $vehicle->id)->first();

    // 3. Deactivate assignment via destroy
    $response = $this->actingAs($this->user)
        ->deleteJson(route('transport.vehicle_drivers.destroy', $assignment->id));
    $response->assertStatus(200);
    $response->assertJson(['success' => true]);
    $this->assertDatabaseHas('vehicle_drivers', [
        'id' => $assignment->id,
        'status' => 'inactive'
    ]);
});

test('vehicle requisition store and reject behavior', function () {
    // Create department
    $dept = \App\Models\Company\Department::create([
        'department_name' => 'IT Dept',
        'short_name' => 'IT',
        'status' => 'Active'
    ]);

    // Create requisition
    $requisitionData = [
        'employee_id' => $this->user->employee_id ?? null,
        'department' => $dept->id,
        'trip_type' => 'Official',
        'trip_mode' => 'One-way',
        'purpose_of_travel' => 'Meeting client',
        'start_date_time' => now()->addHours(1)->format('Y-m-d H:i:s'),
        'end_date_time' => now()->addHours(3)->format('Y-m-d H:i:s'),
        'pickup_location' => 'Office',
        'destination' => 'Client Location',
        'no_of_passengers' => 2,
        'vehicle_type_required' => 'Car',
    ];

    $response = $this->actingAs($this->user)
        ->postJson(route('transport.vehicle_requisitions.store'), $requisitionData);
    $response->assertStatus(201);
    $response->assertJson(['success' => true]);

    $requisition = \App\Models\Transport\VehicleRequisition::latest()->first();

    // Reject requisition
    $response = $this->actingAs($this->user)
        ->postJson(route('transport.vehicle_requisitions.reject', $requisition->id), [
            'rejection_reason' => 'No vehicles available'
        ]);
    $response->assertStatus(200);
    $response->assertJson(['success' => true]);
    $this->assertDatabaseHas('vehicle_requisitions', [
        'id' => $requisition->id,
        'approval_status' => 'Rejected',
        'approval_remarks' => 'No vehicles available'
    ]);
});

test('vehicle allocation store and release behavior', function () {
    $vehicle = \App\Models\Transport\Vehicle::create([
        'vehicle_category' => 'Car',
        'model_number' => 'ALLOC-CAR',
        'manufacture_year' => 2023,
        'fuel_type' => 'Petrol',
        'purchase_type' => 'Purchase',
        'ownership_type' => 'Company-owned',
        'status' => 'Active',
    ]);

    $allocationData = [
        'allocation_type' => 'trip_based',
        'vehicle_ids' => [$vehicle->id],
        'name' => 'Trip to Client',
        'start_date' => now()->format('Y-m-d'),
        'end_date' => now()->addDays(1)->format('Y-m-d'),
        'route_start' => 'Office',
        'route_end' => 'Factory',
        'distance_km' => 45.5,
    ];

    $response = $this->actingAs($this->user)
        ->postJson(route('transport.vehicle_allocations.store'), $allocationData);
    $response->assertStatus(201);
    $response->assertJson(['success' => true]);

    $allocation = \App\Models\Transport\VehicleAllocation::where('vehicle_id', $vehicle->id)->first();
    $this->assertDatabaseHas('vehicles', [
        'id' => $vehicle->id,
        'is_allocated' => true
    ]);

    // Release vehicle
    $response = $this->actingAs($this->user)
        ->patchJson(route('transport.vehicle_allocations.release', $allocation->id), [
            'release_remarks' => 'Completed'
        ]);
    $response->assertStatus(200);
    $response->assertJson(['success' => true]);
    $this->assertDatabaseHas('vehicle_allocations', [
        'id' => $allocation->id,
        'status' => 'Inactive'
    ]);
    $this->assertDatabaseHas('vehicles', [
        'id' => $vehicle->id,
        'is_allocated' => false
    ]);
});

test('employee transport CRUD, reject, and cancel behavior', function () {
    // Setup organization scope structures
    $group = \App\Models\Company\Group::create(['name' => 'Group A', 'short_name' => 'GA', 'status' => 'active']);
    $type = \App\Models\Company\CompanyType::create(['name' => 'Type A', 'short_name' => 'TA', 'status' => 'active']);
    $company = \App\Models\Company\Company::create([
        'group_id' => $group->id,
        'type_id' => $type->id,
        'name' => 'Company A',
        'short_name' => 'CA',
        'address' => 'Addr',
        'status' => 'active'
    ]);

    $routeMap = \App\Models\Transport\RouteMap::create([
        'route_name' => 'Office route',
        'start_point' => 'A',
        'end_point' => 'B',
        'status' => 'Active',
    ]);

    $transportData = [
        'type' => 'company',
        'company_id' => $company->id,
        'service_name' => 'Staff Shuttle',
        'transport_type' => 'Daily Commute',
        'purpose' => 'Staff commute to office',
        'route_map_id' => $routeMap->id,
        'start_date' => now()->addDay()->format('Y-m-d'),
        'end_date' => now()->addDays(30)->format('Y-m-d'),
    ];

    $response = $this->actingAs($this->user)
        ->postJson(route('transport.employee_transports.store'), $transportData);
    $response->assertStatus(201);
    $response->assertJson(['success' => true]);

    $service = \App\Models\Transport\EmployeeTransport::where('service_name', 'Staff Shuttle')->first();

    // Reject transport service
    $response = $this->actingAs($this->user)
        ->patchJson(route('transport.employee_transports.reject', $service->id), [
            'approval_remarks' => 'Rejected'
        ]);
    $response->assertStatus(200);
    $response->assertJson(['success' => true]);
    $this->assertDatabaseHas('employee_transports', [
        'id' => $service->id,
        'status' => 'Rejected'
    ]);
});


