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
    Permission::firstOrCreate(['name' => 'resignations.export', 'guard_name' => 'web']);

    Permission::firstOrCreate(['name' => 'terminations.view', 'guard_name' => 'web']);
    Permission::firstOrCreate(['name' => 'terminations.create', 'guard_name' => 'web']);
    Permission::firstOrCreate(['name' => 'terminations.edit', 'guard_name' => 'web']);
    Permission::firstOrCreate(['name' => 'terminations.delete', 'guard_name' => 'web']);
    Permission::firstOrCreate(['name' => 'terminations.export', 'guard_name' => 'web']);
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

test('offboarding search filters filter records correctly', function () {
    $user = User::factory()->create(['user_type' => \App\Enums\UserType::Group]);
    $user->givePermissionTo(['resignations.view', 'terminations.view']);

    $company = Company::factory()->create(['name' => 'Filter Test Company']);
    $branch = CompanyLocation::create(['company_id' => $company->id, 'name' => 'Filter Test Branch', 'location_address' => '123 Main St']);

    $employee1 = Employee::factory()->create(['full_name' => 'Alpha Tester', 'applicant_id' => 'EMP-001', 'system_id' => 'SYS-001']);
    $employee2 = Employee::factory()->create(['full_name' => 'Beta Tester', 'applicant_id' => 'EMP-002', 'system_id' => 'SYS-002']);

    // Associate employees to company and branch
    $employee1->officeInfo()->create([
        'current_company_id' => $company->id,
        'current_business_unit_id' => $branch->id,
    ]);
    $employee2->officeInfo()->create([
        'current_company_id' => $company->id,
        'current_business_unit_id' => $branch->id,
    ]);

    // Create offboarding records
    $off1 = Offboarding::create([
        'employee_id' => $employee1->id,
        'offboarding_type' => 'resignation',
        'resignation_date' => '2026-07-10',
        'notice_period_days' => 30,
        'last_working_day' => '2026-08-10',
        'reason' => 'Test reason 1',
        'status' => 'pending'
    ]);

    $off2 = Offboarding::create([
        'employee_id' => $employee2->id,
        'offboarding_type' => 'resignation',
        'resignation_date' => '2026-07-20',
        'notice_period_days' => 30,
        'last_working_day' => '2026-08-20',
        'reason' => 'Test reason 2',
        'status' => 'approved'
    ]);

    // 1. Filter by employee_name
    $response = $this->actingAs($user)
        ->get(route('offboarding.resignation.index', ['employee_name' => 'Alpha']))
        ->assertStatus(200);
    $response->assertSee('Alpha Tester');
    $response->assertDontSee('Beta Tester');

    // 2. Filter by employee_id (applicant_id)
    $response = $this->actingAs($user)
        ->get(route('offboarding.resignation.index', ['employee_id' => 'EMP-002']))
        ->assertStatus(200);
    $response->assertSee('Beta Tester');
    $response->assertDontSee('Alpha Tester');

    // 3. Filter by system_id
    $response = $this->actingAs($user)
        ->get(route('offboarding.resignation.index', ['system_id' => 'SYS-001']))
        ->assertStatus(200);
    $response->assertSee('Alpha Tester');
    $response->assertDontSee('Beta Tester');

    // 4. Filter by status
    $response = $this->actingAs($user)
        ->get(route('offboarding.resignation.index', ['status' => 'approved']))
        ->assertStatus(200);
    $response->assertSee('Beta Tester');
    $response->assertDontSee('Alpha Tester');

    // 5. Filter by date range (from / to)
    $response = $this->actingAs($user)
        ->get(route('offboarding.resignation.index', ['from' => '2026-07-15', 'to' => '2026-07-25']))
        ->assertStatus(200);
    $response->assertSee('Beta Tester');
    $response->assertDontSee('Alpha Tester');

    // 6. Filter by company_id and branch_id
    $response = $this->actingAs($user)
        ->get(route('offboarding.resignation.index', ['company_id' => $company->id, 'branch_id' => $branch->id]))
        ->assertStatus(200);
    $response->assertSee('Alpha Tester');
    $response->assertSee('Beta Tester');
});

test('offboarding create view preloads hierarchy from section id query parameter', function () {
    $user = User::factory()->create(['user_type' => \App\Enums\UserType::Group]);
    $user->givePermissionTo(['resignations.create']);

    \App\Models\Setting\GeneralSetting::updateOrCreate(['id' => 1], [
        'name' => 'HRMS Test',
        'currency' => '৳',
        'branch_status' => 1,
        'division_status' => 1,
        'department_status' => 1,
        'section_status' => 1
    ]);

    $company = Company::factory()->create(['name' => 'Hierarchy Preload Company']);
    $branch = CompanyLocation::create(['company_id' => $company->id, 'name' => 'Hierarchy Preload Branch', 'location_address' => '456 St']);
    
    $division = \App\Models\Company\Division::create(['company_id' => $company->id, 'name' => 'Preload Division', 'short_name' => 'PLD']);
    $department = \App\Models\Company\Department::create(['company_id' => $company->id, 'department_name' => 'Preload Department', 'short_name' => 'PLD']);
    $section = \App\Models\Company\Section::create([
        'company_id' => $company->id,
        'location_id' => $branch->id,
        'division_id' => $division->id,
        'department_id' => $department->id,
        'name' => 'Preload Section',
        'short_name' => 'PLS',
        'status' => 'active'
    ]);

    $response = $this->actingAs($user)
        ->get(route('offboarding.resignation.create', ['id' => $section->id]))
        ->assertStatus(200);

    // Assert that the preselected options are visible in the form
    $response->assertSee('value="' . $company->id . '" selected', false);
    $response->assertSee('value="' . $branch->id . '" selected', false);
    $response->assertSee('value="' . $division->id . '" selected', false);
    $response->assertSee('value="' . $department->id . '" selected', false);
    $response->assertSee('value="' . $section->id . '" selected', false);
});

test('offboarding exports resignation and termination records correctly', function () {
    $user = User::factory()->create();
    $user->givePermissionTo([
        'resignations.view', 'resignations.export',
        'terminations.view', 'terminations.export'
    ]);

    // Test Excel Export
    $this->actingAs($user)
        ->get(route('offboarding.resignation.export.excel'))
        ->assertStatus(200)
        ->assertHeader('content-disposition', 'attachment; filename=resignations.xlsx');

    $this->actingAs($user)
        ->get(route('offboarding.termination.export.excel'))
        ->assertStatus(200)
        ->assertHeader('content-disposition', 'attachment; filename=terminations.xlsx');

    // Test PDF Export
    $this->actingAs($user)
        ->get(route('offboarding.resignation.export.pdf'))
        ->assertStatus(200)
        ->assertHeader('content-type', 'application/pdf');

    $this->actingAs($user)
        ->get(route('offboarding.termination.export.pdf'))
        ->assertStatus(200)
        ->assertHeader('content-type', 'application/pdf');
});

test('offboarding exports are restricted by permissions', function () {
    $user = User::factory()->create();
    // User does NOT have export permissions

    $this->actingAs($user)
        ->get(route('offboarding.resignation.export.excel'))
        ->assertStatus(403);

    $this->actingAs($user)
        ->get(route('offboarding.resignation.export.pdf'))
        ->assertStatus(403);
});
