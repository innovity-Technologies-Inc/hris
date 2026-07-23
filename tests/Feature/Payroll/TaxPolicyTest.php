<?php

use App\Models\Payroll\TaxPolicy;
use App\Models\User;
use Spatie\Permission\Models\Permission;

beforeEach(function () {
    // Ensure permissions exist
    Permission::firstOrCreate(['name' => 'tax-policy.view', 'guard_name' => 'web']);
    Permission::firstOrCreate(['name' => 'tax-policy.edit', 'guard_name' => 'web']);
});

test('tax policy single-page configuration update works correctly via Axios', function () {
    $user = User::factory()->create();
    $user->givePermissionTo(['tax-policy.view', 'tax-policy.edit']);

    // 1. Get configuration page (Index triggers auto-creation of default policy)
    $response = $this->actingAs($user)->get(route('tax-policy.index'));
    $response->assertStatus(200);

    $policy = TaxPolicy::first();
    expect($policy)->not->toBeNull();
    expect($policy->zero_tax_male)->toEqual(350000.00);

    // 2. Update Tax Policy (Exemption type: exempt_allowance) via PUT request
    // Tests having taxable_amount and taxable_amount = null for last slab.
    $response = $this->actingAs($user)->putJson(route('tax-policy.update', $policy->id), [
        'zero_tax_male' => 360000.00,
        'zero_tax_female' => 410000.00,
        'min_tax_amount' => 6000.00,
        'min_negotiable_tax_limit' => 50000.00,
        'tax_payable_percentage' => 80.00,
        'total_tax_month' => 12,
        'exemption_type' => 'exempt_allowance',
        'exempt_allowances' => ['house_allowance', 'medical_allowance'],
        'slabs' => [
            [
                'taxable_amount' => 150000.00,
                'tax_percentage' => 6.00,
                'tax_amount' => 9000.00,
            ],
            [
                'taxable_amount' => null, // Last slab taxable amount can be null
                'tax_percentage' => 10.00,
                'tax_amount' => 0.00,
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
    expect($policy->min_negotiable_tax_limit)->toEqual(50000.00);
    expect($policy->tax_payable_percentage)->toEqual(80.00);
    expect($policy->total_tax_month)->toEqual(12);
    expect($policy->exemption_type)->toBe('exempt_allowance');
    expect($policy->exempt_allowances)->toBe(['house_allowance', 'medical_allowance']);
    expect($policy->slabs->count())->toBe(2);
    
    $lastSlab = $policy->slabs->last();
    expect($lastSlab->taxable_amount)->toBeNull();
});
