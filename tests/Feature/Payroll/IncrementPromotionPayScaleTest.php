<?php

use App\Models\User;
use App\Models\Employee\Employee;
use App\Models\Employee\EmployeeOfficeInfo;
use App\Models\Employee\EmployeeSalaryBreakdown;
use App\Models\Company\PayGroup;
use App\Models\Company\PayScale;
use App\Models\Company\SalaryGrade;
use App\Models\Company\Designation;
use App\Models\Payroll\Increment;
use App\Models\Payroll\Promotion;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::factory()->create(['user_type' => \App\Enums\UserType::Group]);

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

    $this->payScale = PayScale::create([
        'title' => 'Scale A',
        'grade_id' => $this->grade->id,
        'pay_group_id' => $this->payGroup->id,
        'min_salary' => 20000,
        'max_salary' => 50000,
        'status' => 'active'
    ]);

    $this->employee = Employee::factory()->create(['full_name' => 'Test Employee']);

    $this->designation1 = Designation::create([
        'designation_level' => 1,
        'company_designation' => 'Software Engineer 1',
        'status' => 'active'
    ]);

    $this->designation2 = Designation::create([
        'designation_level' => 2,
        'company_designation' => 'Software Engineer 2',
        'status' => 'active'
    ]);

    EmployeeOfficeInfo::create([
        'employee_id' => $this->employee->id,
        'current_company_id' => 1,
        'current_designation_id' => $this->designation1->id,
        'date_of_join' => '2020-01-01',
    ]);

    EmployeeSalaryBreakdown::create([
        'employee_id' => $this->employee->id,
        'pay_scale_id' => $this->payScale->id,
        'gross_salary' => 30000,
        'basic_salary' => 21000,
        'house_allowance' => 3000,
        'transport_allowance' => 2000,
        'food_allowance' => 2000,
        'medical_allowance' => 1000,
        'other_earnings' => 1000,
        'basic_salary_percentage' => 70,
        'house_allowance_percentage' => 10,
        'transport_allowance_percentage' => 6.67,
        'food_allowance_percentage' => 6.67,
        'medical_allowance_percentage' => 3.33,
        'other_earnings_percentage' => 3.33,
    ]);
});

it('can store a new increment with pay scale', function () {
    $this->withoutMiddleware();

    $data = [
        'employee_id' => $this->employee->id,
        'pay_scale_id' => $this->payScale->id,
        'increment_base' => 'gross_salary',
        'increment_method' => 'fixed',
        'salary_increase_amount' => 5000,
        'effective_from' => '2026-07-01',
        'status' => 'pending',
    ];

    $response = $this->actingAs($this->admin)
        ->postJson(route('increment.store'), $data);

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'redirect_url' => route('increment.index')
        ]);

    $this->assertDatabaseHas('increments', [
        'employee_id' => $this->employee->id,
        'pay_scale_id' => $this->payScale->id,
        'new_gross_salary' => 35000,
    ]);
});

it('can store a new promotion with pay scale', function () {
    $this->withoutMiddleware();

    $data = [
        'employee_id' => $this->employee->id,
        'pay_scale_id' => $this->payScale->id,
        'previous_designation' => $this->designation1->id,
        'new_designation' => $this->designation2->id,
        'increment_base' => 'gross_salary',
        'increment_method' => 'fixed',
        'salary_increase_amount' => 10000,
        'effective_from' => '2026-07-01',
        'status' => 'pending',
    ];

    $response = $this->actingAs($this->admin)
        ->postJson(route('promotion.store'), $data);

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'redirect_url' => route('promotion.index')
        ]);

    $this->assertDatabaseHas('promotions', [
        'employee_id' => $this->employee->id,
        'pay_scale_id' => $this->payScale->id,
        'new_gross_salary' => 40000,
    ]);
});

it('updates employee salary breakdown pay_scale_id upon adjustment', function () {
    $newPayScale = PayScale::create([
        'title' => 'Scale B',
        'grade_id' => $this->grade->id,
        'pay_group_id' => $this->payGroup->id,
        'min_salary' => 30000,
        'max_salary' => 60000,
        'status' => 'active'
    ]);

    $increment = Increment::create([
        'employee_id' => $this->employee->id,
        'pay_scale_id' => $newPayScale->id,
        'increment_base' => 'gross_salary',
        'increment_method' => 'fixed',
        'salary_increase_amount' => 5000,
        'increment_amount_value' => 5000,
        'previous_basic_salary' => 21000,
        'previous_gross_salary' => 30000,
        'new_gross_salary' => 35000,
        'effective_from' => '2026-07-01',
        'status' => 'approved',
        'is_adjustment' => 1
    ]);

    $service = app(\App\Services\Payroll\PayrollServices::class);
    $service->updateSalaryData($increment);

    $this->assertDatabaseHas('employee_salary_breakdowns', [
        'employee_id' => $this->employee->id,
        'pay_scale_id' => $newPayScale->id,
        'gross_salary' => 35000,
    ]);
});

test('it can export increments, decrements, promotions, and demotions', function () {
    $this->withoutMiddleware();
    $this->actingAs($this->admin);

    // Create Increment
    Increment::create([
        'employee_id' => $this->employee->id,
        'pay_scale_id' => $this->payScale->id,
        'increment_base' => 'gross_salary',
        'increment_method' => 'fixed',
        'salary_increase_amount' => 5000,
        'increment_amount_value' => 5000,
        'previous_basic_salary' => 21000,
        'previous_gross_salary' => 30000,
        'new_gross_salary' => 35000,
        'effective_from' => '2026-07-01',
        'status' => 'pending',
    ]);

    // Create Decrement
    \App\Models\Payroll\Decrement::create([
        'employee_id' => $this->employee->id,
        'pay_scale_id' => $this->payScale->id,
        'decrement_base' => 'gross_salary',
        'decrement_method' => 'fixed',
        'salary_decrease_amount' => 5000,
        'decrement_amount_value' => 5000,
        'previous_basic_salary' => 21000,
        'previous_gross_salary' => 30000,
        'new_gross_salary' => 25000,
        'effective_from' => '2026-07-01',
        'status' => 'pending',
    ]);

    // Create Promotion
    Promotion::create([
        'employee_id' => $this->employee->id,
        'pay_scale_id' => $this->payScale->id,
        'previous_designation' => $this->designation1->id,
        'new_designation' => $this->designation2->id,
        'increment_base' => 'gross_salary',
        'increment_method' => 'fixed',
        'salary_increase_amount' => 10000,
        'increment_amount_value' => 10000,
        'previous_basic_salary' => 21000,
        'previous_gross_salary' => 30000,
        'new_gross_salary' => 40000,
        'effective_from' => '2026-07-01',
        'status' => 'pending',
    ]);

    // Create Demotion
    \App\Models\Payroll\Demotion::create([
        'employee_id' => $this->employee->id,
        'pay_scale_id' => $this->payScale->id,
        'previous_designation' => $this->designation2->id,
        'new_designation' => $this->designation1->id,
        'decrement_base' => 'gross_salary',
        'decrement_method' => 'fixed',
        'salary_decrease_amount' => 10000,
        'decrement_amount_value' => 10000,
        'previous_basic_salary' => 28000,
        'previous_gross_salary' => 40000,
        'new_gross_salary' => 30000,
        'effective_from' => '2026-07-01',
        'status' => 'pending',
    ]);

    // Test Excel downloads
    $response = $this->get(route('increment.export.excel'));
    $response->assertStatus(200);
    $this->assertTrue(str_contains($response->headers->get('content-disposition'), 'attachment; filename=employee_increments_'));

    $response = $this->get(route('decrement.export.excel'));
    $response->assertStatus(200);
    $this->assertTrue(str_contains($response->headers->get('content-disposition'), 'attachment; filename=employee_decrements_'));

    $response = $this->get(route('promotion.export.excel'));
    $response->assertStatus(200);
    $this->assertTrue(str_contains($response->headers->get('content-disposition'), 'attachment; filename=employee_promotions_'));

    $response = $this->get(route('demotion.export.excel'));
    $response->assertStatus(200);
    $this->assertTrue(str_contains($response->headers->get('content-disposition'), 'attachment; filename=employee_demotions_'));

    // Test print templates
    $response = $this->get(route('increment.print'));
    $response->assertStatus(200);
    $response->assertSee('Employee Salary Increment Sheet');

    $response = $this->get(route('decrement.print'));
    $response->assertStatus(200);
    $response->assertSee('Employee Salary Decrement Sheet');

    $response = $this->get(route('promotion.print'));
    $response->assertStatus(200);
    $response->assertSee('Employee Promotion Sheet');

    $response = $this->get(route('demotion.print'));
    $response->assertStatus(200);
    $response->assertSee('Employee Demotion Sheet');
});
