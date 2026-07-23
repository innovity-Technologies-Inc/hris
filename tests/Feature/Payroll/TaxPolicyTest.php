<?php

use App\Models\Company\Company;
use App\Models\Payroll\TaxPolicy;
use App\Models\User;
use Spatie\Permission\Models\Permission;

beforeEach(function () {
    // Ensure permissions exist
    Permission::firstOrCreate(['name' => 'tax-policy.view', 'guard_name' => 'web']);
    Permission::firstOrCreate(['name' => 'tax-policy.create', 'guard_name' => 'web']);
    Permission::firstOrCreate(['name' => 'tax-policy.edit', 'guard_name' => 'web']);
    Permission::firstOrCreate(['name' => 'tax-policy.delete', 'guard_name' => 'web']);
});

test('tax policy CRUD operations work correctly via API and Axios', function () {
    $user = User::factory()->create();
    $user->givePermissionTo(['tax-policy.view', 'tax-policy.create', 'tax-policy.edit', 'tax-policy.delete']);

    $company = Company::factory()->create(['name' => 'Tax Policy Test Company']);

    // 1. Get index page
    $response = $this->actingAs($user)->get(route('tax-policy.index'));
    $response->assertStatus(200);

    // 2. Get create view
    $response = $this->actingAs($user)->get(route('tax-policy.create'));
    $response->assertStatus(200);

    // 3. Store a new Tax Policy with slabs (Exemption type: fixed)
    $response = $this->actingAs($user)->postJson(route('tax-policy.store'), [
        'company_id' => $company->id,
        'branch_id' => null,
        'zero_tax_male' => 350000.00,
        'zero_tax_female' => 400000.00,
        'min_tax_amount' => 5000.00,
        'exemption_type' => 'fixed',
        'salary_ratio' => '1/3',
        'fixed_amount' => 120000.00,
        'slabs' => [
            [
                'taxable_amount' => 100000.00,
                'tax_percentage' => 5.00,
                'tax_amount' => 5000.00,
            ],
            [
                'taxable_amount' => 300000.00,
                'tax_percentage' => 10.00,
                'tax_amount' => 300000 * 10 / 100,
            ]
        ]
    ]);

    $response->assertStatus(201)
        ->assertJson([
            'success' => true,
            'message' => 'Tax Policy created successfully.'
        ]);

    $policy = TaxPolicy::latest()->first();
    expect($policy)->not->toBeNull();
    expect($policy->zero_tax_male)->toEqual(350000.00);
    expect($policy->slabs->count())->toBe(2);

    // 4. Get Edit form
    $response = $this->actingAs($user)->get(route('tax-policy.edit', $policy->id));
    $response->assertStatus(200);

    // 5. Update Tax Policy (Exemption type: exempt_allowance)
    $response = $this->actingAs($user)->putJson(route('tax-policy.update', $policy->id), [
        'company_id' => $company->id,
        'branch_id' => null,
        'zero_tax_male' => 360000.00,
        'zero_tax_female' => 410000.00,
        'min_tax_amount' => 6000.00,
        'exemption_type' => 'exempt_allowance',
        'exempt_allowances' => ['house_allowance', 'medical_allowance'],
        'slabs' => [
            [
                'taxable_amount' => 150000.00,
                'tax_percentage' => 6.00,
                'tax_amount' => 9000.00,
            ]
        ]
    ]);

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'message' => 'Tax Policy updated successfully.'
        ]);

    $policy->refresh();
    expect($policy->zero_tax_male)->toEqual(360000.00);
    expect($policy->exemption_type)->toBe('exempt_allowance');
    expect($policy->exempt_allowances)->toBe(['house_allowance', 'medical_allowance']);
    expect($policy->slabs->count())->toBe(1);

    // 6. Delete Tax Policy
    $response = $this->actingAs($user)->deleteJson(route('tax-policy.destroy', $policy->id));
    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'message' => 'Tax Policy deleted successfully.'
        ]);

    expect(TaxPolicy::find($policy->id))->toBeNull();
});
