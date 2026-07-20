<?php

use App\Models\Company\Company;
use App\Models\Company\CompanyLocation;
use App\Models\Employee\Employee;
use App\Models\Offboarding\Offboarding;
use App\Models\User;
use Spatie\Permission\Models\Permission;

beforeEach(function () {
    Permission::firstOrCreate(['name' => 'resignations.view', 'guard_name' => 'web']);
    Permission::firstOrCreate(['name' => 'resignations.create', 'guard_name' => 'web']);
    Permission::firstOrCreate(['name' => 'resignations.edit', 'guard_name' => 'web']);
    Permission::firstOrCreate(['name' => 'resignations.delete', 'guard_name' => 'web']);

    Permission::firstOrCreate(['name' => 'terminations.view', 'guard_name' => 'web']);
    Permission::firstOrCreate(['name' => 'terminations.create', 'guard_name' => 'web']);
    Permission::firstOrCreate(['name' => 'terminations.edit', 'guard_name' => 'web']);
    Permission::firstOrCreate(['name' => 'terminations.delete', 'guard_name' => 'web']);
});

test('offboarding resignation and termination workflows work correctly', function () {
    $user = User::factory()->create();
    $user->givePermissionTo([
        'resignations.view', 'resignations.create', 'resignations.edit', 'resignations.delete',
        'terminations.view', 'terminations.create', 'terminations.edit', 'terminations.delete'
    ]);

    $company = Company::factory()->create(['name' => 'Test Offboarding Company']);
    $branch = CompanyLocation::create(['company_id' => $company->id, 'name' => 'HQ Branch', 'location_address' => '123 Main St']);
    $employeeResign = Employee::factory()->create(['full_name' => 'Resigning Employee', 'status' => 'active']);
    $employeeTerminate = Employee::factory()->create(['full_name' => 'Terminated Employee', 'status' => 'active']);

    // 1. Store Resignation
    $resignationResponse = $this->actingAs($user)->postJson(route('offboarding.store'), [
        'company_id' => $company->id,
        'branch_id' => $branch->id,
        'employee_id' => $employeeResign->id,
        'offboarding_type' => 'resignation',
        'resignation_date' => '2026-08-01',
        'notice_period_days' => 30,
        'last_working_day' => '2026-08-31',
        'reason' => 'Personal reasons.',
    ]);

    $resignationResponse->assertStatus(201)
        ->assertJsonPath('success', true);

    $employeeResign->refresh();
    expect($employeeResign->status)->toBe('resigned');

    // 2. Store Termination
    $terminationResponse = $this->actingAs($user)->postJson(route('offboarding.store'), [
        'company_id' => $company->id,
        'branch_id' => $branch->id,
        'employee_id' => $employeeTerminate->id,
        'offboarding_type' => 'termination',
        'resignation_date' => '2026-08-01',
        'notice_period_days' => 14,
        'last_working_day' => '2026-08-15',
        'reason' => 'Contract termination due to restructure.',
    ]);

    $terminationResponse->assertStatus(201)
        ->assertJsonPath('success', true);

    $employeeTerminate->refresh();
    expect($employeeTerminate->status)->toBe('terminated');

    // 3. Test Index Routes
    $this->actingAs($user)
        ->get(route('offboarding.resignation.index'))
        ->assertStatus(200);

    $this->actingAs($user)
        ->get(route('offboarding.termination.index'))
        ->assertStatus(200);

    // 4. Test Show & Update
    $offboarding = Offboarding::withoutGlobalScopes()->where('employee_id', $employeeResign->id)->first();
    expect($offboarding)->not->toBeNull();

    $this->actingAs($user)
        ->get(route('offboarding.show', $offboarding->id))
        ->assertStatus(200);

    $updateResponse = $this->actingAs($user)->putJson(route('offboarding.update', $offboarding->id), [
        'employee_id' => $employeeResign->id,
        'offboarding_type' => 'resignation',
        'resignation_date' => '2026-08-01',
        'notice_period_days' => 45,
        'last_working_day' => '2026-09-15',
        'reason' => 'Updated personal reason.',
        'status' => 'approved',
    ]);

    $updateResponse->assertStatus(200)
        ->assertJsonPath('success', true);

    // 5. Test Delete
    $deleteResponse = $this->actingAs($user)->deleteJson(route('offboarding.destroy', $offboarding->id));
    $deleteResponse->assertStatus(200)
        ->assertJsonPath('success', true);
});

test('offboarded employee is restricted to my-offboarding portal page', function () {
    $employee = Employee::factory()->create(['full_name' => 'Offboarded User', 'status' => 'resigned']);
    $offboardedUser = User::factory()->create(['employee_id' => $employee->id]);

    // Offboarded employee accessing my-offboarding -> Allowed (200)
    $this->actingAs($offboardedUser)
        ->get(route('offboarding.my_offboarding'))
        ->assertStatus(200);

    // Offboarded employee trying to access another route -> Redirected to my-offboarding
    $this->actingAs($offboardedUser)
        ->get(route('dashboard.index'))
        ->assertRedirect(route('offboarding.my_offboarding'));
});
