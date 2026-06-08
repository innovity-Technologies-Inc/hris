<?php

use App\Models\User;
use App\Models\Employee\Employee;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->employee = Employee::factory()->create(['id' => 1]);

    $this->employeeUser = User::factory()->create([
        'user_type' => 'employee',
        'employee_id' => 1
    ]);
});

test('employee user cannot access office information creation', function () {
    $this->actingAs($this->employeeUser);
    
    $response = $this->get(route('employee.office_informations.create', 1));
    $response->assertStatus(403);
    });

    test('employee user cannot edit eligible plans', function () {
    $this->actingAs($this->employeeUser);

    $response = $this->get(route('employee.eligible_plans.edit', 1));
    $response->assertStatus(403);
    });

    test('employee user cannot access salary breakdown creation', function () {
    $this->actingAs($this->employeeUser);

    $response = $this->get(route('employee.salary_breakdown.create', 1));
    $response->assertStatus(403);
    });

    test('employee user cannot access bank account creation', function () {
    $this->actingAs($this->employeeUser);

    $response = $this->get(route('employee.bank_accounts.create', 1));
    $response->assertStatus(403);
    });

    test('employee user cannot assign plans', function () {
    $this->actingAs($this->employeeUser)->withoutMiddleware();

    $response = $this->post(route('employee.profile.plans.store', 'meal-plans'), [
        'employee_id' => 1,
        'plan_ids' => [1]
    ]);
    $response->assertStatus(403);
});

test('employee user cannot access leave application creation', function () {
    $this->actingAs($this->employeeUser);
    
    $response = $this->get(route('leave.create'));
    $response->assertStatus(403);
});

