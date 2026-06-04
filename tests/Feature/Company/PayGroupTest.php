<?php

use App\Models\Company\Company;
use App\Models\Company\PayGroup;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Create and assign permissions
    Permission::firstOrCreate(['name' => 'general-settings.view', 'guard_name' => 'web']);
    Permission::firstOrCreate(['name' => 'general-settings.create', 'guard_name' => 'web']);
    Permission::firstOrCreate(['name' => 'general-settings.edit', 'guard_name' => 'web']);
    Permission::firstOrCreate(['name' => 'general-settings.delete', 'guard_name' => 'web']);
    
    $role = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
    $role->givePermissionTo(['general-settings.view', 'general-settings.create', 'general-settings.edit', 'general-settings.delete']);
    
    $this->admin = User::factory()->create(['user_type' => 'Group']);
    $this->admin->assignRole($role);
});

it('can list pay groups via ajax', function () {
    $this->withoutMiddleware();
    PayGroup::create([
        'title' => 'Monthly Staff',
        'payroll_frequency' => 'Monthly',
        'salary_processing_day' => '25',
        'status' => 'active'
    ]);

    $response = $this->actingAs($this->admin)
        ->get(route('pay_groups.index'), ['HTTP_X-Requested-With' => 'XMLHttpRequest']);

    $response->assertStatus(200)
        ->assertSee('Monthly Staff')
        ->assertSee('Monthly');
});

it('can store a new pay group', function () {
    $this->withoutMiddleware();
    $data = [
        'title' => 'Weekly Casual',
        'payroll_frequency' => 'Weekly',
        'salary_processing_day' => 'Friday',
        'status' => 'active'
    ];

    $response = $this->actingAs($this->admin)
        ->postJson(route('pay_groups.store'), $data);

    $response->assertStatus(200)
        ->assertJson(['success' => true]);

    $this->assertDatabaseHas('pay_groups', ['title' => 'Weekly Casual']);
});

it('can update a pay group', function () {
    $this->withoutMiddleware();
    $payGroup = PayGroup::create([
        'title' => 'Old Title',
        'payroll_frequency' => 'Monthly',
        'salary_processing_day' => '1',
        'status' => 'active'
    ]);

    $data = [
        'title' => 'New Title',
        'payroll_frequency' => 'Monthly',
        'salary_processing_day' => '1',
        'status' => 'inactive'
    ];

    $response = $this->actingAs($this->admin)
        ->putJson(route('pay_groups.update', $payGroup->id), $data);

    $response->assertStatus(200)
        ->assertJson(['success' => true]);

    $this->assertDatabaseHas('pay_groups', ['title' => 'New Title', 'status' => 'inactive']);
});

it('can delete a pay group', function () {
    $this->withoutMiddleware();
    $payGroup = PayGroup::create([
        'title' => 'To Be Deleted',
        'payroll_frequency' => 'Hourly',
        'salary_processing_day' => 'Daily',
        'status' => 'active'
    ]);

    $response = $this->actingAs($this->admin)
        ->deleteJson(route('pay_groups.delete', $payGroup->id));

    $response->assertStatus(200)
        ->assertJson(['success' => true]);

    $this->assertDatabaseMissing('pay_groups', ['id' => $payGroup->id]);
});
