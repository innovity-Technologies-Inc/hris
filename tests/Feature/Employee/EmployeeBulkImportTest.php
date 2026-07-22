<?php

use App\Models\User;
use App\Models\Employee\Employee;
use App\Models\Employee\EmployeeOfficeInfo;
use App\Models\Employee\EmployeeEligiblePlan;
use App\Models\Employee\EmployeeNominee;
use App\Models\Employee\EmployeeSalaryBreakdown;
use App\Imports\Employee\EmployeeGeneralInformationImport;
use App\Imports\Employee\EmployeeOfficeInformationImport;
use App\Imports\Employee\EmployeeEligiblePlanImport;
use App\Imports\Employee\EmployeeNomineeImport;
use App\Imports\Employee\SalaryBreakdownImport;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Setup General Setting
    \App\Models\Setting\GeneralSetting::updateOrCreate(['id' => 1], [
        'name' => 'HRMS Test',
        'currency' => '৳',
    ]);
});

test('general information import saves record with NID correctly', function () {
    $row = collect([
        'APP-001', // applicant_id
        'SYS-001', // system_id
        'PUNCH-001', // punch_card_no
        'John', // first_name
        'M', // middle_name
        'Doe', // last_name
        'John M Doe', // full_name
        'Father Name',
        'Mother Name',
        'Spouse Name',
        'Single', // marital_status
        'Male', // gender
        'Islam', // religion
        'Bangladeshi', // nationality
        'A+', // blood_group
        '5', // height_feet
        '9', // height_inches
        '0', // children_count
        // present address
        'Present 1', 'Present Village', 'Present PO', '1234', 'Present District', 'Present Div', 'Present State', 'Bangladesh',
        // permanent address
        'Permanent 1', 'Permanent Village', 'Permanent PO', '1234', 'Permanent District', 'Permanent Div', 'Permanent State', 'Bangladesh',
        // reference address
        'REF-01', 'Ref Name', 'Ref Desg', 'ref@example.com', '123456', '987654', 'Ref Line 1', 'Ref Village', 'Ref PO', '1234', 'Ref Dist', 'Ref Div', 'Ref State', 'Bangladesh',
        'TIN-123', // tin
        'PASSPORT-123', // passport_no
        '2030-12-31', // passport_expiry
        'LICENSE-123', // license_no
        '2030-12-31', // license_expiry
        '2030-12-31', // visa_expiry
        '2030-12-31', // work_expiry
        'RES-123', // residency_id_number
        '1990-01-01', // date_of_birth
        '1234567890', // nid
        'Bangladesh', // birth_country
        'BIRTH-REG-123', // birth_reg_no
        '01711111111', // personal_mobile
        '02123456', // home_phone
        '01722222222', // work_mobile
        '02654321', // work_phone
        'john@work.com', // work_email
        'john@personal.com', // personal_email
    ]);

    $import = new EmployeeGeneralInformationImport();
    $import->collection(collect([collect(), $row])); // Skip header

    $employee = Employee::where('applicant_id', 'APP-001')->first();
    expect($employee)->not->toBeNull();
    expect($employee->nid)->toBe('1234567890');
    expect($employee->middle_name)->toBe('M');
    expect($employee->last_name)->toBe('Doe');
});

test('nominee import saves relation correctly', function () {
    $employee = Employee::factory()->create(['applicant_id' => 'APP-001']);

    $row = collect([
        'APP-001', // employee_id (lookup)
        'Nominee Name', // nominee_name
        'Wife', // relation
        'Father Name',
        'Mother Name',
        'Spouse Name',
        'Female', // gender
        '1992-05-05', // date_of_birth
        'Islam', // religion
        'Married', // marital_status
        'Bangladeshi', // nationality
        'B+', // blood_group
        '/photos/nominee.jpg', // photo_path
        'NID-NOM-123', // nid
        'BIRTH-NOM-123', // birth_reg_no
        'ACC-NOM-123', // bank_account_no
        '100', // ratio
        '123456', // phone
        '987654', // mobile
        'Present 1', 'Village', 'PO', 'Thana', 'District', 'State', '1234', 'Bangladesh'
    ]);

    $import = new EmployeeNomineeImport();
    $import->collection(collect([collect(), $row])); // Skip header

    $nominee = EmployeeNominee::where('employee_id', $employee->id)->first();
    expect($nominee)->not->toBeNull();
    expect($nominee->relation)->toBe('Wife');
});

test('salary breakdown import maps pay scale correctly', function () {
    $employee = Employee::factory()->create(['applicant_id' => 'APP-001']);
    
    // Create salary grade
    $grade = \App\Models\Company\SalaryGrade::create([
        'grade_code' => 'G10',
        'grade_name' => 'Grade 10',
    ]);

    // Create pay group
    $payGroup = \App\Models\Company\PayGroup::create([
        'title' => 'Group A',
        'payroll_frequency' => 'Monthly',
        'salary_processing_day' => '25',
    ]);

    // Create pay scale
    $payScale = \App\Models\Company\PayScale::create([
        'title' => 'Grade 10 Scale',
        'grade_id' => $grade->id,
        'pay_group_id' => $payGroup->id,
        'min_salary' => 10000,
        'max_salary' => 20000,
    ]);

    $row = collect([
        'APP-001', // employee_id
        'Grade 10 Scale', // pay_scale title
        '10000', // basic_salary
        '5000', // house_allowance
        '2000', // transport_allowance
        '1500', // food_allowance
        '1000', // medical_allowance
        '500', // other_earnings
        '20000', // gross_salary
    ]);

    $import = new SalaryBreakdownImport();
    $import->collection(collect([collect(), $row]));

    $breakdown = EmployeeSalaryBreakdown::where('employee_id', $employee->id)->first();
    expect($breakdown)->not->toBeNull();
    expect($breakdown->pay_scale_id)->toBe($payScale->id);
});
