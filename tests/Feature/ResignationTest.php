<?php

use App\Models\Company\Company;
use App\Models\Company\CompanyLocation;
use App\Models\Employee\Employee;
use App\Models\Offboarding\Offboarding;
use App\Models\User;
use Spatie\Permission\Models\Permission;

beforeEach(function () {
    // Ensure permissions exist
    Permission::firstOrCreate(['name' => 'resignations.view', 'guard_name' => 'web']);
    Permission::firstOrCreate(['name' => 'resignations.create', 'guard_name' => 'web']);
    Permission::firstOrCreate(['name' => 'resignations.edit', 'guard_name' => 'web']);
    Permission::firstOrCreate(['name' => 'resignations.delete', 'guard_name' => 'web']);
});

test('resignation CRUD operations and hierarchy cascade work correctly', function () {
    $user = User::factory()->create();
    $user->givePermissionTo(['resignations.view', 'resignations.create', 'resignations.edit', 'resignations.delete']);

    $company = Company::factory()->create(['name' => 'Test Resign Company']);
    $branch = CompanyLocation::create(['company_id' => $company->id, 'name' => 'HQ Branch', 'location_address' => '123 Main Street']);
    $employee = Employee::factory()->create(['full_name' => 'Jane Resigning Employee', 'status' => 'active']);

    // 1. Test hierarchy cascade endpoint
    $this->actingAs($user)
        ->getJson(route('offboarding.get_employees_by_hierarchy', ['company_id' => $company->id]))
        ->assertStatus(200)
        ->assertJsonStructure(['success', 'message', 'data']);

    // 2. Test Store Resignation
    $storeResponse = $this->actingAs($user)->postJson(route('offboarding.store'), [
        'company_id' => $company->id,
        'branch_id' => $branch->id,
        'employee_id' => $employee->id,
        'resignation_date' => '2026-08-01',
        'notice_period_days' => 30,
        'last_working_day' => '2026-08-31',
        'reason' => 'Career advancement opportunity elsewhere.',
        'remarks' => 'Smooth transition planned.',
        'offboarding_type' => 'resignation', // changed from 'type' to 'offboarding_type'
    ]);

    $storeResponse->assertStatus(201)
        ->assertJsonPath('success', true);

    $this->assertDatabaseHas('offboardings', [
        'employee_id' => $employee->id,
        'reason' => 'Career advancement opportunity elsewhere.',
        'notice_period_days' => 30,
        'status' => 'pending',
    ]);

    $resignation = Offboarding::withoutGlobalScopes()->where('employee_id', $employee->id)->first();
    expect($resignation)->not->toBeNull();

    // 3. Test View Index & Details
    $this->actingAs($user)
        ->get(route('offboarding.resignation.index'))
        ->assertStatus(200);

    $this->actingAs($user)
        ->get(route('offboarding.show', $resignation->id))
        ->assertStatus(200);

    // 4. Test Update Resignation
    $updateResponse = $this->actingAs($user)->putJson(route('offboarding.update', $resignation->id), [
        'employee_id' => $employee->id,
        'resignation_date' => '2026-08-01',
        'notice_period_days' => 45,
        'last_working_day' => '2026-09-15',
        'reason' => 'Updated career advancement reason.',
        'status' => 'approved',
        'offboarding_type' => 'resignation', // changed from 'type' to 'offboarding_type'
    ]);

    $updateResponse->assertStatus(200)
        ->assertJsonPath('success', true);

    $this->assertDatabaseHas('offboardings', [
        'id' => $resignation->id,
        'notice_period_days' => 45,
        'status' => 'approved',
    ]);

    // 5. Test Delete Resignation
    $deleteResponse = $this->actingAs($user)->deleteJson(route('offboarding.destroy', $resignation->id));
    $deleteResponse->assertStatus(200)
        ->assertJsonPath('success', true);

    $this->assertSoftDeleted('offboardings', [
        'id' => $resignation->id,
    ]);
});
