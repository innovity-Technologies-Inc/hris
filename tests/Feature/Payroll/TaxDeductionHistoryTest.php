<?php

use App\Models\User;
use App\Models\Employee\Employee;
use App\Models\Employee\EmployeeOfficeInfo;
use App\Models\Employee\EmployeeSalaryBreakdown;
use App\Models\Company\Company;
use App\Models\Company\Group;
use App\Models\Company\CompanyType;
use App\Models\Company\PayGroup;
use App\Models\Company\PayScale;
use App\Models\Company\SalaryGrade;
use App\Models\Payroll\TaxPolicy;
use App\Models\Payroll\TaxCalculation;
use App\Models\Payroll\TaxDeductionHistory;
use App\Services\Payroll\PayrollServices;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    Permission::firstOrCreate(['name' => 'tax-policy.view', 'guard_name' => 'web']);
    
    $this->group = Group::create(['name' => 'Test Group', 'status' => 'active']);
    $this->type = CompanyType::create(['name' => 'IT', 'short_name' => 'IT', 'status' => 'active']);
    $this->company = Company::create([
        'group_id' => $this->group->id,
        'type_id' => $this->type->id,
        'name' => 'Test Company',
        'short_name' => 'TST',
        'status' => 'active',
        'address' => '123 St'
    ]);

    \App\Models\Setting\GeneralSetting::firstOrCreate([], [
        'name' => 'Test HRMS',
        'branch_status' => 0
    ]);
});

test('it records tax deduction history during payroll generation and deletes it on rollback', function () {
    $this->withoutExceptionHandling();

    $payGroup = PayGroup::create([
        'title' => 'Monthly Staff',
        'payroll_frequency' => 'Monthly',
        'salary_processing_day' => '25',
        'working_hours_per_day' => 8,
        'working_days_per_cycle' => 30,
        'status' => 'active'
    ]);

    // 1. Create a Tax Policy & Slabs
    $policy = TaxPolicy::create([
        'zero_tax_male' => 350000.00,
        'zero_tax_female' => 400000.00,
        'min_tax_amount' => 5000.00,
        'exemption_type' => 'fixed',
        'salary_ratio' => '1/3',
        'fixed_amount' => 120000.00,
        'min_negotiable_tax_limit' => 50000.00,
        'tax_payable_percentage' => 80.00,
        'total_tax_month' => 12,
        'applicable_pay_groups' => [$payGroup->id],
    ]);

    $policy->slabs()->createMany([
        ['taxable_amount' => 300000.00, 'tax_percentage' => 0.00, 'tax_amount' => 0.00],
        ['taxable_amount' => null, 'tax_percentage' => 10.00, 'tax_amount' => 0.00],
    ]);

    // 2. Setup employee and salary info
    $employee = Employee::create([
        'full_name' => 'Jane Doe',
        'applicant_id' => 'APP789',
        'system_id' => 'SYS789',
        'punch_card_no' => 'P789',
        'gender' => 'female',
        'status' => 'active'
    ]);

    EmployeeOfficeInfo::create([
        'employee_id' => $employee->id,
        'current_company_id' => $this->company->id,
        'date_of_join' => '2020-01-01',
    ]);

    $grade = SalaryGrade::create([
        'grade_code' => 'G1',
        'grade_name' => 'Grade 1',
        'status' => 'active'
    ]);

    $payScale = PayScale::create([
        'title' => 'Scale A',
        'grade_id' => $grade->id,
        'pay_group_id' => $payGroup->id,
        'min_salary' => 20000.00,
        'max_salary' => 100000.00,
        'status' => 'active'
    ]);

    // Monthly gross is 90,000. Annual Gross is 1,080,000. Exemption = 120,000. Taxable = 960,000.
    // Slab 1 (300k @ 0%): 0.
    // Slab 2 (660k @ 10%): 66,000.
    // Total Tax: 66,000. Tax Payable = 66,000 * 80% = 52,800.
    // Monthly Tax (tax_per_month) = 52,800 / 12 = 4,400.
    EmployeeSalaryBreakdown::create([
        'employee_id' => $employee->id,
        'pay_scale_id' => $payScale->id,
        'gross_salary' => 90000.00,
        'basic_salary' => 45000.00,
    ]);

    \App\Models\Employee\EmployeeEligiblePlan::create([
        'employee_id' => $employee->id,
        'bonus_plan_from' => '2020-01-01',
        'bonus_plan_status' => 'active',
    ]);

    // Save TaxCalculation record for the employee
    TaxCalculation::create([
        'employee_id' => $employee->id,
        'policy_id' => $policy->id,
        'gross_salary' => 1080000.00,
        'exemption_amount' => 120000.00,
        'taxable_amount' => 960000.00,
        'total_tax_amount' => 66000.00,
        'tax_payable' => 52800.00,
        'tax_per_month' => 4400.00,
    ]);

    // Authenticate a user to populate generated_by and context
    $admin = User::factory()->create([
        'user_type' => \App\Enums\UserType::Company,
    ]);
    $this->actingAs($admin);

    // 3. Generate salary process
    $payrollService = new PayrollServices();
    $processData = [
        'company_id' => $this->company->id,
        'branch_id' => null,
        'division_id' => null,
        'department_id' => null,
        'section_id' => null,
        'pay_group_id' => $payGroup->id,
        'salary_month' => '2026-07',
    ];

    $process = $payrollService->salaryProcess($processData);

    expect($process)->not->toBeNull();

    // 4. Assert tax deduction history was created
    $history = TaxDeductionHistory::where('employee_id', $employee->id)->first();
    expect($history)->not->toBeNull();
    expect($history->amount)->toEqual(4400.00);
    expect($history->annual_tax_payable)->toEqual(52800.00);
    expect($history->monthly_tax_rate)->toEqual(4400.00);
    expect($history->frequency)->toEqual('monthly');

    // 5. Rollback process
    $payrollService->salaryDelete($process->id);

    // 6. Assert tax deduction history was cleaned up
    $historyCount = TaxDeductionHistory::where('payroll_process_id', $process->id)->count();
    expect($historyCount)->toEqual(0);
});

test('tax deduction history index view returns 200 and loads results', function () {
    $user = User::factory()->create();
    $user->givePermissionTo(['tax-policy.view']);

    $response = $this->actingAs($user)->get(route('tax-deduction.index'));
    $response->assertStatus(200);
});
