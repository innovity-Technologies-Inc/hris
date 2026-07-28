<?php

use App\Models\Company\Company;
use App\Models\Employee\Employee;
use App\Models\Employee\EmployeeSalaryBreakdown;
use App\Models\Payroll\TaxPolicy;
use App\Models\Payroll\TaxCalculation;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use App\Services\Payroll\TaxCalculateService;
use App\Jobs\Payroll\ProcessTaxCalculationJob;
use Illuminate\Support\Facades\Queue;

beforeEach(function () {
    // Ensure permissions exist
    Permission::firstOrCreate(['name' => 'tax-policy.view', 'guard_name' => 'web']);
    Permission::firstOrCreate(['name' => 'tax-policy.edit', 'guard_name' => 'web']);
    Permission::firstOrCreate(['name' => 'tax-calculate.view', 'guard_name' => 'web']);
    Permission::firstOrCreate(['name' => 'tax-calculate.process', 'guard_name' => 'web']);
    Permission::firstOrCreate(['name' => 'estimated-tax.view', 'guard_name' => 'web']);
    Permission::firstOrCreate(['name' => 'estimated-tax.export', 'guard_name' => 'web']);
});

test('tax calculate index page returns correct view', function () {
    $user = User::factory()->create();
    $user->givePermissionTo(['estimated-tax.view']);

    $response = $this->actingAs($user)->get(route('tax-calculate.index'));
    $response->assertStatus(200);
});

test('tax calculate process page returns correct view', function () {
    $user = User::factory()->create();
    $user->givePermissionTo(['tax-calculate.view']);

    $response = $this->actingAs($user)->get(route('tax-calculate.process'));
    $response->assertStatus(200);
});

test('tax calculation endpoint completes synchronously for small employee counts', function () {
    $user = User::factory()->create([
        'user_type' => \App\Enums\UserType::Group
    ]);
    $user->givePermissionTo(['tax-calculate.process']);

    // Active employee count is 0 (<= 500), should execute synchronously
    $response = $this->actingAs($user)->postJson(route('tax-calculate.calculate'));

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'message' => 'Tax calculation completed successfully.'
        ]);
});

test('tax calculation endpoint dispatches background job for large employee counts', function () {
    Queue::fake();

    $user = User::factory()->create([
        'user_type' => \App\Enums\UserType::Group
    ]);
    $user->givePermissionTo(['tax-calculate.process']);

    // Create 501 active employees to cross the threshold
    $employees = Employee::factory()->count(501)->create(['status' => 'active']);

    $response = $this->actingAs($user)->postJson(route('tax-calculate.calculate'));

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'message' => 'Tax calculation initiated successfully. Slabs are being evaluated in the background.'
        ]);

    Queue::assertPushed(ProcessTaxCalculationJob::class);
});

test('tax calculation export endpoint returns 200 and triggers excel download', function () {
    $user = User::factory()->create();
    $user->givePermissionTo(['estimated-tax.export']);

    $response = $this->actingAs($user)->get(route('tax-calculate.export'));
    $response->assertStatus(200);
    $response->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
});

test('tax calculation progress endpoint returns correct progress structure', function () {
    $user = User::factory()->create();
    $user->givePermissionTo(['tax-calculate.view']);

    $response = $this->actingAs($user)->get(route('tax-calculate.progress'));
    $response->assertStatus(200)
        ->assertJsonStructure([
            'total',
            'processed',
            'status'
        ]);
});

test('tax calculation logic evaluates progressive math, total tax month multiplier, and payable negotiable tax correctly', function () {
    // 1. Setup a Tax Policy
    $policy = TaxPolicy::create([
        'zero_tax_male' => 350000.00,
        'zero_tax_female' => 400000.00,
        'min_tax_amount' => 5000.00,
        'exemption_type' => 'fixed',
        'salary_ratio' => '1/3',
        'fixed_amount' => 120000.00,
        'min_negotiable_tax_limit' => 50000.00,
        'tax_payable_percentage' => 80.00,
        'total_tax_month' => 13,
    ]);

    // Setup slabs:
    // Slab 1 (0%): up to 300,000
    // Slab 2 (5%): up to 100,000 (tax = 5,000)
    // Slab 3 (10%): up to 300,000 (tax = 30,000)
    // Slab 4 (15%): null (unlimited remaining)
    $policy->slabs()->createMany([
        ['taxable_amount' => 300000.00, 'tax_percentage' => 0.00, 'tax_amount' => 0.00],
        ['taxable_amount' => 100000.00, 'tax_percentage' => 5.00, 'tax_amount' => 5000.00],
        ['taxable_amount' => 300000.00, 'tax_percentage' => 10.00, 'tax_amount' => 30000.00],
        ['taxable_amount' => null, 'tax_percentage' => 15.00, 'tax_amount' => 0.00],
    ]);

    // 2. Setup an active employee
    $employee = Employee::factory()->create([
        'gender' => 'male',
        'status' => 'active',
    ]);

    // Monthly Gross = 80,000 => Annual Gross = 80,000 * 13 (from policy) = 1,040,000.00
    $salary = EmployeeSalaryBreakdown::create([
        'employee_id' => $employee->id,
        'gross_salary' => 80000.00,
        'basic_salary' => 40000.00,
        'house_allowance' => 20000.00,
        'medical_allowance' => 10000.00,
        'transport_allowance' => 10000.00,
    ]);

    // Run the calculation logic
    $service = new TaxCalculateService();
    $result = $service->calculateTaxForEmployee($employee, $policy);

    // Calculated:
    // Total Tax: 68,000.00
    // Since 68,000.00 > 50,000.00 (min negotiable limit),
    // Tax Payable: 68,000.00 * 80% = 54,400.00
    // Tax per month: 54,400.00 / 12 = 4,533.33

    expect($result)->not->toBeNull();
    expect($result['gross_salary'])->toEqual(1040000.00);
    expect($result['exemption_amount'])->toEqual(120000.00);
    expect($result['taxable_amount'])->toEqual(920000.00);
    expect($result['total_tax_amount'])->toEqual(68000.00);
    expect($result['tax_payable'])->toEqual(54400.00);
    expect(round($result['tax_per_month'], 2))->toEqual(4533.33);
    expect($result['slabs_reached'])->toBe(4);
});

test('tax calculation logic floors tax payable to minimum negotiable tax limit when percentage reduces it below limit', function () {
    // 1. Setup a Tax Policy
    $policy = TaxPolicy::create([
        'zero_tax_male' => 350000.00,
        'zero_tax_female' => 400000.00,
        'min_tax_amount' => 5000.00,
        'exemption_type' => 'fixed',
        'salary_ratio' => '1/3',
        'fixed_amount' => 120000.00,
        'min_negotiable_tax_limit' => 50000.00, // Min limit is 50,000
        'tax_payable_percentage' => 80.00,      // 80% payable
        'total_tax_month' => 12,
    ]);

    // Setup slabs:
    $policy->slabs()->createMany([
        ['taxable_amount' => 300000.00, 'tax_percentage' => 0.00, 'tax_amount' => 0.00],
        ['taxable_amount' => 100000.00, 'tax_percentage' => 5.00, 'tax_amount' => 5000.00],
        ['taxable_amount' => 300000.00, 'tax_percentage' => 10.00, 'tax_amount' => 30000.00],
        ['taxable_amount' => null, 'tax_percentage' => 15.00, 'tax_amount' => 0.00],
    ]);

    // 2. Setup an active employee
    $employee = Employee::factory()->create([
        'gender' => 'male',
        'status' => 'active',
    ]);

    // Monthly Gross = 80,000 => Annual Gross = 80,000 * 12 = 960,000.00
    // Exemption = min(120,000, 960,000 * 1/3) = 120,000.00
    // Taxable Amount = 840,000.00
    // Slab 1 (300k @ 0%): 0.00
    // Slab 2 (100k @ 5%): 5,000.00
    // Slab 3 (300k @ 10%): 30,000.00
    // Slab 4 (140k @ 15%): 21,000.00
    // Total Calculated Tax: 56,000.00 (which is > 50,000 limit)
    // Applying 80% payable: 56,000 * 80% = 44,800.00
    // Since 44,800.00 < 50,000.00 (min negotiable tax limit),
    // Tax Payable should be floored to 50,000.00!
    // Tax per month: 50,000.00 / 12 = 4,166.67
    $salary = EmployeeSalaryBreakdown::create([
        'employee_id' => $employee->id,
        'gross_salary' => 80000.00,
        'basic_salary' => 40000.00,
        'house_allowance' => 20000.00,
        'medical_allowance' => 10000.00,
        'transport_allowance' => 10000.00,
    ]);

    $service = new TaxCalculateService();
    $result = $service->calculateTaxForEmployee($employee, $policy);

    expect($result)->not->toBeNull();
    expect($result['total_tax_amount'])->toEqual(56000.00);
    expect($result['tax_payable'])->toEqual(50000.00); // Floored to min_negotiable_tax_limit
    expect(round($result['tax_per_month'], 2))->toEqual(4166.67);
});

test('tax calculation logic floors tax payable to minimum tax amount of the tax policy when tax is applicable but below limit', function () {
    // 1. Setup a Tax Policy
    $policy = TaxPolicy::create([
        'zero_tax_male' => 350000.00,
        'zero_tax_female' => 400000.00,
        'min_tax_amount' => 5000.00,          // Minimum tax amount is 5,000
        'exemption_type' => 'fixed',
        'salary_ratio' => '1/3',
        'fixed_amount' => 120000.00,
        'min_negotiable_tax_limit' => 50000.00,
        'tax_payable_percentage' => 80.00,
        'total_tax_month' => 12,
    ]);

    // Setup slabs:
    $policy->slabs()->createMany([
        ['taxable_amount' => 300000.00, 'tax_percentage' => 0.00, 'tax_amount' => 0.00],
        ['taxable_amount' => 100000.00, 'tax_percentage' => 5.00, 'tax_amount' => 5000.00],
        ['taxable_amount' => 300000.00, 'tax_percentage' => 10.00, 'tax_amount' => 30000.00],
        ['taxable_amount' => null, 'tax_percentage' => 15.00, 'tax_amount' => 0.00],
    ]);

    // 2. Setup an active employee
    $employee = Employee::factory()->create([
        'gender' => 'male',
        'status' => 'active',
    ]);

    // Monthly Gross = 40,000 => Annual Gross = 40,000 * 12 = 480,000.00
    // Exemption = min(120,000, 480,000 * 1/3) = 120,000.00
    // Taxable Amount = 360,000.00
    // Slab 1 (300k @ 0%): 0.00
    // Slab 2 (60k @ 5%): 3,000.00
    // Total calculated tax = 3,000.00.
    // Taxable amount = 360,000.00 => Gross salary = 360,000 + 120,000 (exemption) = 480,000.00
    // Monthly gross = 480,000 / 12 = 40,000.00.
    $salary = EmployeeSalaryBreakdown::create([
        'employee_id' => $employee->id,
        'gross_salary' => 40000.00,
        'basic_salary' => 20000.00,
        'house_allowance' => 10000.00,
        'medical_allowance' => 5000.00,
        'transport_allowance' => 5000.00,
    ]);

    $service = new TaxCalculateService();
    $result = $service->calculateTaxForEmployee($employee, $policy);

    expect($result)->not->toBeNull();
    expect($result['total_tax_amount'])->toEqual(3000.00);
    expect($result['tax_payable'])->toEqual(5000.00); // Floored to min_tax_amount (5000.00) instead of 3000.00!
    expect(round($result['tax_per_month'], 2))->toEqual(416.67); // 5000.00 / 12
});
