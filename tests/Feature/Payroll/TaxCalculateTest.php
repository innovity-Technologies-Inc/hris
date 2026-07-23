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
});

test('tax calculate index page returns correct view', function () {
    $user = User::factory()->create();
    $user->givePermissionTo(['tax-policy.view']);

    $response = $this->actingAs($user)->get(route('tax-calculate.index'));
    $response->assertStatus(200);
});

test('tax calculation endpoint dispatches ProcessTaxCalculationJob successfully', function () {
    Queue::fake();

    $user = User::factory()->create();
    $user->givePermissionTo(['tax-policy.edit']);

    $response = $this->actingAs($user)->postJson(route('tax-calculate.calculate'));

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'message' => 'Tax calculation initiated successfully. Slabs are being evaluated in the background.'
        ]);

    Queue::assertPushed(ProcessTaxCalculationJob::class);
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
    // Tax per month: 54,400.00 / 13 = 4,184.62

    expect($result)->not->toBeNull();
    expect($result['gross_salary'])->toEqual(1040000.00);
    expect($result['exemption_amount'])->toEqual(120000.00);
    expect($result['taxable_amount'])->toEqual(920000.00);
    expect($result['total_tax_amount'])->toEqual(68000.00);
    expect($result['tax_payable'])->toEqual(54400.00);
    expect(round($result['tax_per_month'], 2))->toEqual(4184.62);
    expect($result['slabs_reached'])->toBe(4);
});
