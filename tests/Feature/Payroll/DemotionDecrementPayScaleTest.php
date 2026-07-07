<?php

use App\Models\User;
use App\Models\Employee\Employee;
use App\Models\Employee\EmployeeOfficeInfo;
use App\Models\Employee\EmployeeSalaryBreakdown;
use App\Models\Company\PayGroup;
use App\Models\Company\PayScale;
use App\Models\Company\SalaryGrade;
use App\Models\Company\Designation;
use App\Models\Payroll\Decrement;
use App\Models\Payroll\Demotion;
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
        'min_salary' => 10000,
        'max_salary' => 50000,
        'status' => 'active'
    ]);

    $this->employee = Employee::factory()->create(['full_name' => 'Test Employee']);

    $this->designation1 = Designation::create([
        'designation_level' => 2,
        'company_designation' => 'Software Engineer 2',
        'status' => 'active'
    ]);

    $this->designation2 = Designation::create([
        'designation_level' => 1,
        'company_designation' => 'Software Engineer 1',
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

it('can store a new decrement with pay scale', function () {
    $this->withoutMiddleware();

    $data = [
        'employee_id' => $this->employee->id,
        'pay_scale_id' => $this->payScale->id,
        'decrement_base' => 'gross_salary',
        'decrement_method' => 'fixed',
        'salary_decrease_amount' => 5000,
        'effective_from' => '2026-07-01',
        'status' => 'pending',
    ];

    $response = $this->actingAs($this->admin)
        ->postJson(route('decrement.store'), $data);

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'redirect_url' => route('decrement.index')
        ]);

    $this->assertDatabaseHas('decrements', [
        'employee_id' => $this->employee->id,
        'pay_scale_id' => $this->payScale->id,
        'new_gross_salary' => 25000,
    ]);
});

it('can store a new demotion with pay scale', function () {
    $this->withoutMiddleware();

    $data = [
        'employee_id' => $this->employee->id,
        'pay_scale_id' => $this->payScale->id,
        'previous_designation' => $this->designation1->id,
        'new_designation' => $this->designation2->id,
        'decrement_base' => 'gross_salary',
        'decrement_method' => 'fixed',
        'salary_decrease_amount' => 10000,
        'effective_from' => '2026-07-01',
        'status' => 'pending',
    ];

    $response = $this->actingAs($this->admin)
        ->postJson(route('demotion.store'), $data);

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'redirect_url' => route('demotion.index')
        ]);

    $this->assertDatabaseHas('demotions', [
        'employee_id' => $this->employee->id,
        'pay_scale_id' => $this->payScale->id,
        'new_gross_salary' => 20000,
    ]);
});

it('updates employee salary breakdown pay_scale_id upon decrement adjustment', function () {
    $newPayScale = PayScale::create([
        'title' => 'Scale B',
        'grade_id' => $this->grade->id,
        'pay_group_id' => $this->payGroup->id,
        'min_salary' => 10000,
        'max_salary' => 40000,
        'status' => 'active'
    ]);

    $decrement = Decrement::create([
        'employee_id' => $this->employee->id,
        'pay_scale_id' => $newPayScale->id,
        'decrement_base' => 'gross_salary',
        'decrement_method' => 'fixed',
        'salary_decrease_amount' => 5000,
        'decrement_amount_value' => 5000,
        'previous_basic_salary' => 21000,
        'previous_gross_salary' => 30000,
        'new_gross_salary' => 25000,
        'effective_from' => '2026-07-01',
        'status' => 'approved',
        'is_adjustment' => 1
    ]);

    $service = app(\App\Services\Payroll\PayrollServices::class);
    $service->updateSalaryData($decrement);

    $this->assertDatabaseHas('employee_salary_breakdowns', [
        'employee_id' => $this->employee->id,
        'pay_scale_id' => $newPayScale->id,
        'gross_salary' => 25000,
    ]);
});

it('updates employee salary breakdown pay_scale_id and designation upon demotion adjustment', function () {
    $newPayScale = PayScale::create([
        'title' => 'Scale B',
        'grade_id' => $this->grade->id,
        'pay_group_id' => $this->payGroup->id,
        'min_salary' => 10000,
        'max_salary' => 40000,
        'status' => 'active'
    ]);

    $demotion = Demotion::create([
        'employee_id' => $this->employee->id,
        'pay_scale_id' => $newPayScale->id,
        'previous_designation' => $this->designation1->id,
        'new_designation' => $this->designation2->id,
        'decrement_base' => 'gross_salary',
        'decrement_method' => 'fixed',
        'salary_decrease_amount' => 10000,
        'decrement_amount_value' => 10000,
        'previous_basic_salary' => 21000,
        'previous_gross_salary' => 30000,
        'new_gross_salary' => 20000,
        'effective_from' => '2026-07-01',
        'status' => 'approved',
        'is_adjustment' => 1
    ]);

    $service = app(\App\Services\Payroll\PayrollServices::class);
    $service->updateSalaryData($demotion);
    $service->designationUpdate($demotion);

    $this->assertDatabaseHas('employee_salary_breakdowns', [
        'employee_id' => $this->employee->id,
        'pay_scale_id' => $newPayScale->id,
        'gross_salary' => 20000,
    ]);

    $this->assertDatabaseHas('employee_office_infos', [
        'employee_id' => $this->employee->id,
        'current_designation_id' => $this->designation2->id,
    ]);
});
