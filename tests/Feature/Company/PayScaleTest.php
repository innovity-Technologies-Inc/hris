<?php

use App\Models\Company\PayGroup;
use App\Models\Company\PayScale;
use App\Models\Company\SalaryGrade;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->grade = SalaryGrade::create([
        'grade_code' => 'G1',
        'grade_name' => 'Grade 1',
        'status' => 'active'
    ]);

    $this->payGroup = PayGroup::create([
        'title' => 'Monthly Staff',
        'payroll_frequency' => 'Monthly',
        'salary_processing_day' => '25',
        'status' => 'active'
    ]);
    
    // Create and assign permissions
    Permission::firstOrCreate(['name' => 'general-settings.view', 'guard_name' => 'web']);
    Permission::firstOrCreate(['name' => 'general-settings.create', 'guard_name' => 'web']);
    Permission::firstOrCreate(['name' => 'general-settings.edit', 'guard_name' => 'web']);
    Permission::firstOrCreate(['name' => 'general-settings.delete', 'guard_name' => 'web']);
    
    $role = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
    $role->givePermissionTo(['general-settings.view', 'general-settings.create', 'general-settings.edit', 'general-settings.delete']);
    
    $this->admin = User::factory()->create(['user_type' => \App\Enums\UserType::Group]);
    $this->admin->assignRole($role);
});

it('can list pay scales via ajax', function () {
    $this->withoutMiddleware();
    PayScale::create([
        'grade_id' => $this->grade->id,
        'pay_group_id' => $this->payGroup->id,
        'min_salary' => 50000,
        'max_salary' => 100000,
        'status' => 'active'
    ]);

    $response = $this->actingAs($this->admin)
        ->get(route('pay_scales.index'), ['HTTP_X-Requested-With' => 'XMLHttpRequest']);

    $response->assertStatus(200)
        ->assertSee('G1')
        ->assertSee('Monthly Staff')
        ->assertSee('50,000.00');
});

it('can store a new pay scale', function () {
    $this->withoutMiddleware();
    $data = [
        'grade_id' => $this->grade->id,
        'pay_group_id' => $this->payGroup->id,
        'min_salary' => 60000,
        'max_salary' => 120000,
        'status' => 'active'
    ];

    $response = $this->actingAs($this->admin)
        ->postJson(route('pay_scales.store'), $data);

    $response->assertStatus(200)
        ->assertJson(['success' => true]);

    $this->assertDatabaseHas('pay_scales', ['min_salary' => 60000, 'max_salary' => 120000]);
});

it('can update a pay scale', function () {
    $this->withoutMiddleware();
    $payScale = PayScale::create([
        'grade_id' => $this->grade->id,
        'pay_group_id' => $this->payGroup->id,
        'min_salary' => 40000,
        'max_salary' => 80000,
        'status' => 'active'
    ]);

    $data = [
        'grade_id' => $this->grade->id,
        'pay_group_id' => $this->payGroup->id,
        'min_salary' => 45000,
        'max_salary' => 90000,
        'status' => 'inactive'
    ];

    $response = $this->actingAs($this->admin)
        ->putJson(route('pay_scales.update', $payScale->id), $data);

    $response->assertStatus(200)
        ->assertJson(['success' => true]);

    $this->assertDatabaseHas('pay_scales', ['min_salary' => 45000, 'status' => 'inactive']);
});

it('can delete a pay scale', function () {
    $this->withoutMiddleware();
    $payScale = PayScale::create([
        'grade_id' => $this->grade->id,
        'pay_group_id' => $this->payGroup->id,
        'min_salary' => 30000,
        'max_salary' => 60000,
        'status' => 'active'
    ]);

    $response = $this->actingAs($this->admin)
        ->deleteJson(route('pay_scales.delete', $payScale->id));

    $response->assertStatus(200)
        ->assertJson(['success' => true]);

    $this->assertDatabaseMissing('pay_scales', ['id' => $payScale->id]);
});
