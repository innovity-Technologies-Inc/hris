<?php

use App\Models\User;
use App\Models\Plan\LeavePlan;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Create a user and assign necessary permissions
    $this->user = User::factory()->create(['user_type' => 'employee']);
    $role = Role::create(['name' => 'Admin']);
    
    $permissions = [
        'leave-plans.view',
        'leave-plans.create',
        'leave-plans.edit',
        'leave-plans.delete',
    ];
    
    foreach ($permissions as $permission) {
        Permission::findOrCreate($permission, 'web');
    }
    
    $role->syncPermissions($permissions);
    $this->user->assignRole($role);
});

test('leave plans index page is accessible', function () {
    $response = $this->actingAs($this->user)
        ->get(route('plan.leave_plans.index'));

    $response->assertStatus(200);
    $response->assertViewIs('plan.leave_plans.index');
});

test('leave plans create page is accessible', function () {
    $response = $this->actingAs($this->user)
        ->get(route('plan.leave_plans.create'));

    $response->assertStatus(200);
    $response->assertViewIs('plan.leave_plans.form');
});

test('leave plan store validation behavior', function () {
    $response = $this->actingAs($this->user)
        ->postJson(route('plan.leave_plans.store'), [
            'name' => '',
            'applicable_gender' => 'invalid_gender',
        ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['name', 'applicable_gender']);
});

test('leave plan CRUD operations and database assertion', function () {
    // 1. Create a Leave Plan
    $response = $this->actingAs($this->user)
        ->postJson(route('plan.leave_plans.store'), [
            'name' => 'Annual Leave',
            'short_name' => 'AL',
            'applicable_gender' => 'Both',
            'leave_type' => 'Casual Leave',
            'leave_limit' => 15,
            'max_no_of_days' => 20,
            'display_serial' => 1,
            'apply_limit' => 3,
            'allow_fractional_leave' => 'active',
            'off_day_include' => 'yes',
            'active_ind' => 'active',
        ]);

    $response->assertStatus(201);
    $response->assertJson(['success' => true]);

    $this->assertDatabaseHas('leave_plans', [
        'name' => 'Annual Leave',
        'short_name' => 'AL',
        'off_day_include' => 'yes',
    ]);

    $plan = LeavePlan::where('name', 'Annual Leave')->first();

    // 2. Edit page accessibility
    $editResponse = $this->actingAs($this->user)
        ->get(route('plan.leave_plans.edit', $plan->id));
    $editResponse->assertStatus(200);

    // 3. Update the Leave Plan
    $updateResponse = $this->actingAs($this->user)
        ->putJson(route('plan.leave_plans.update', $plan->id), [
            'name' => 'Updated Annual Leave',
            'short_name' => 'UAL',
            'applicable_gender' => 'Male',
            'leave_type' => 'Casual Leave',
            'leave_limit' => 10,
            'max_no_of_days' => 15,
            'display_serial' => 2,
            'apply_limit' => 2,
            'allow_fractional_leave' => 'inactive',
            'off_day_include' => 'no',
            'active_ind' => 'inactive',
        ]);

    $updateResponse->assertStatus(200);
    $updateResponse->assertJson(['success' => true]);

    $this->assertDatabaseHas('leave_plans', [
        'id' => $plan->id,
        'name' => 'Updated Annual Leave',
        'off_day_include' => 'no',
        'active_ind' => 'inactive',
    ]);

    // 4. Delete the Leave Plan
    $deleteResponse = $this->actingAs($this->user)
        ->deleteJson(route('plan.leave_plans.delete', $plan->id));

    $deleteResponse->assertStatus(200);
    $deleteResponse->assertJson(['success' => true]);

    $this->assertDatabaseMissing('leave_plans', [
        'id' => $plan->id,
    ]);
});
