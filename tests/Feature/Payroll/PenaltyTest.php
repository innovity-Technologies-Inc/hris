<?php

use App\Models\User;
use App\Models\Employee\Employee;
use App\Models\Plan\PenaltyPlan;
use App\Models\Payroll\EmployeePenalty;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Enums\UserType;
use Spatie\Permission\Models\Permission;

uses(RefreshDatabase::class);

beforeEach(function () {
    app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    Permission::firstOrCreate(['name' => 'penalty-management.view', 'guard_name' => 'web']);
    
    $this->admin = User::factory()->create(['user_type' => UserType::Group]);
    $this->admin->givePermissionTo('penalty-management.view');

    $this->employee = Employee::factory()->create(['full_name' => 'Test Employee']);

    $this->plan = PenaltyPlan::create([
        'title' => 'Late In Penalty',
        'penalty_amount' => 100.00,
        'status' => 'active'
    ]);
});

test('it can access penalty index and filter via ajax', function () {
    $this->withoutMiddleware();

    EmployeePenalty::create([
        'employee_id' => $this->employee->id,
        'penalty_plan_id' => $this->plan->id,
        'occurrence_date' => '2026-07-23',
        'cause' => 'Late reporting',
        'penalty_amount' => 100.00,
        'status' => 'approved'
    ]);

    $response = $this->actingAs($this->admin)->get(route('payroll.penalty.index'));
    $response->assertStatus(200);

    // Ajax request
    $ajaxResponse = $this->actingAs($this->admin)->get(route('payroll.penalty.index', ['_ajax' => 1, 'keyword' => 'Late']));
    $ajaxResponse->assertStatus(200);
    $ajaxResponse->assertSee('Late In Penalty');
});

test('it can export penalties to excel', function () {
    $this->withoutMiddleware();

    EmployeePenalty::create([
        'employee_id' => $this->employee->id,
        'penalty_plan_id' => $this->plan->id,
        'occurrence_date' => '2026-07-23',
        'cause' => 'Late reporting',
        'penalty_amount' => 100.00,
        'status' => 'approved'
    ]);

    $response = $this->actingAs($this->admin)->get(route('payroll.penalty.export.excel', ['keyword' => 'Late']));
    $response->assertStatus(200);
    $this->assertTrue(
        headers_sent() || str_contains($response->headers->get('content-disposition'), 'attachment; filename=employee_penalties_')
    );
});

test('it can print penalties view', function () {
    $this->withoutMiddleware();

    EmployeePenalty::create([
        'employee_id' => $this->employee->id,
        'penalty_plan_id' => $this->plan->id,
        'occurrence_date' => '2026-07-23',
        'cause' => 'Late reporting',
        'penalty_amount' => 100.00,
        'status' => 'approved'
    ]);

    $response = $this->actingAs($this->admin)->get(route('payroll.penalty.print', ['keyword' => 'Late']));
    $response->assertStatus(200);
    $response->assertSee('Employee Penalty Management logs');
    $response->assertSee('Test Employee');
});
