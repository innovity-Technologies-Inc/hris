<?php

use App\Models\User;
use App\Enums\UserType;
use App\Models\Employee\Employee;
use App\Models\Employee\EmployeeOfficeInfo;
use App\Models\Company\Company;
use App\Models\Company\Division;
use App\Models\Company\Department;
use App\Models\Company\Section;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

uses(RefreshDatabase::class);

function createEmployee($overrides = []) {
    return Employee::create(array_merge([
        'applicant_id' => Str::random(10),
        'system_id' => Str::random(10),
        'punch_card_no' => Str::random(10),
        'first_name' => 'First',
        'full_name' => 'Full Name',
        'father_name' => 'Father',
        'mother_name' => 'Mother',
        'gender' => 'Male',
        'religion' => 'None',
        'nationality' => 'Bangladeshi',
        'present_address' => ['address' => 'Test'],
        'date_of_birth' => '1990-01-01',
        'personal_mobile' => '0123456789',
        'status' => 'active',
    ], $overrides));
}

beforeEach(function () {
    // Setup basic organization structure
    $group = \App\Models\Company\Group::create(['name' => 'Test Group', 'short_name' => 'TG', 'status' => 'active']);
    $type = \App\Models\Company\CompanyType::create(['name' => 'Test Type', 'short_name' => 'TT', 'status' => 'active']);

    $this->companyA = Company::create([
        'name' => 'Company A', 
        'short_name' => 'COA', 
        'type_id' => $type->id, 
        'group_id' => $group->id, 
        'address' => 'Addr A', 
        'status' => 'active'
    ]);
    $this->companyB = Company::create([
        'name' => 'Company B', 
        'short_name' => 'COB', 
        'type_id' => $type->id, 
        'group_id' => $group->id, 
        'address' => 'Addr B', 
        'status' => 'active'
    ]);

    // Business Units (CompanyLocation)
    $this->buA = \App\Models\Company\CompanyLocation::create(['name' => 'BU A', 'company_id' => $this->companyA->id, 'location_address' => 'Addr BU A', 'status' => 'active']);
    $this->buB = \App\Models\Company\CompanyLocation::create(['name' => 'BU B', 'company_id' => $this->companyB->id, 'location_address' => 'Addr BU B', 'status' => 'active']);

    $this->divisionA = Division::create([
        'name' => 'Division A', 
        'short_name' => 'DIVA', 
        'company_id' => $this->companyA->id,
        'location_id' => $this->buA->id,
        'status' => 'active'
    ]);
    $this->divisionB = Division::create([
        'name' => 'Division B', 
        'short_name' => 'DIVB', 
        'company_id' => $this->companyB->id,
        'location_id' => $this->buB->id,
        'status' => 'active'
    ]);

    $this->deptA = Department::create([
        'department_name' => 'Dept A',
        'short_name' => 'DEPTA',
        'division_id' => $this->divisionA->id,
        'company_id' => $this->companyA->id,
        'location_id' => $this->buA->id,
        'status' => 'active'
    ]);
    $this->deptB = Department::create([
        'department_name' => 'Dept B',
        'short_name' => 'DEPTB',
        'division_id' => $this->divisionB->id,
        'company_id' => $this->companyB->id,
        'location_id' => $this->buB->id,
        'status' => 'active'
    ]);

    $this->sectionA = Section::create([
        'name' => 'Section A',
        'short_name' => 'SECTA',
        'department_id' => $this->deptA->id,
        'division_id' => $this->divisionA->id,
        'company_id' => $this->companyA->id,
        'location_id' => $this->buA->id,
        'status' => 'active'
    ]);
    $this->sectionB = Section::create([
        'name' => 'Section B',
        'short_name' => 'SECTB',
        'department_id' => $this->deptB->id,
        'division_id' => $this->divisionB->id,
        'company_id' => $this->companyB->id,
        'location_id' => $this->buB->id,
        'status' => 'active'
    ]);

    // Create Employee A in Section A
    $this->employeeA = createEmployee(['full_name' => 'Employee A']);
    $this->userA = User::factory()->create([
        'user_type' => UserType::Company,
        'employee_id' => $this->employeeA->id
    ]);
    $this->employeeA->update(['user_id' => $this->userA->id]);
    
    EmployeeOfficeInfo::create([
        'employee_id' => $this->employeeA->id,
        'current_company_id' => $this->companyA->id,
        'current_business_unit_id' => $this->buA->id,
        'current_division_id' => $this->divisionA->id,
        'current_department_id' => $this->deptA->id,
        'current_section_id' => $this->sectionA->id,
        'joining_company_id' => $this->companyA->id,
        'joining_division_id' => $this->divisionA->id,
    ]);

    // Create Employee B in Section B
    $this->employeeB = createEmployee(['full_name' => 'Employee B']);
    $this->userB = User::factory()->create([
        'user_type' => UserType::Company,
        'employee_id' => $this->employeeB->id
    ]);
    $this->employeeB->update(['user_id' => $this->userB->id]);

    EmployeeOfficeInfo::create([
        'employee_id' => $this->employeeB->id,
        'current_company_id' => $this->companyB->id,
        'current_business_unit_id' => $this->buB->id,
        'current_division_id' => $this->divisionB->id,
        'current_department_id' => $this->deptB->id,
        'current_section_id' => $this->sectionB->id,
        'joining_company_id' => $this->companyB->id,
        'joining_division_id' => $this->divisionB->id,
    ]);

    // Create a Group user
    $this->groupUser = User::factory()->create([
        'user_type' => UserType::Group
    ]);
});

test('group user can see everything', function () {
    Auth::login($this->groupUser);

    expect(Company::count())->toBe(2)
        ->and(Employee::count())->toBe(2)
        ->and(Division::count())->toBe(2)
        ->and(Department::count())->toBe(2)
        ->and(Section::count())->toBe(2)
        ->and(\App\Models\Company\CompanyLocation::count())->toBe(2);
});

test('company user can only see their own company data', function () {
    Auth::login($this->userA);

    expect(Company::count())->toBe(1)
        ->and(Company::first()->id)->toBe($this->companyA->id);

    expect(Employee::count())->toBe(1)
        ->and(Employee::first()->id)->toBe($this->employeeA->id);

    expect(Division::count())->toBe(1)
        ->and(Division::first()->id)->toBe($this->divisionA->id);

    expect(Department::count())->toBe(1)
        ->and(Department::first()->id)->toBe($this->deptA->id);
});

test('business unit user can only see their own business unit data', function () {
    $this->userA->update(['user_type' => UserType::BusinessUnit]);
    Auth::login($this->userA);

    expect(\App\Models\Company\CompanyLocation::count())->toBe(1)
        ->and(\App\Models\Company\CompanyLocation::first()->id)->toBe($this->buA->id);

    expect(Division::count())->toBe(1)
        ->and(Division::first()->id)->toBe($this->divisionA->id);
});

test('division user can only see their own division data', function () {
    $this->userA->update(['user_type' => UserType::Division]);
    Auth::login($this->userA);
    
    expect(Division::count())->toBe(1)
        ->and(Division::first()->id)->toBe($this->divisionA->id);

    expect(Department::count())->toBe(1)
        ->and(Department::first()->id)->toBe($this->deptA->id);

    expect(Company::count())->toBe(1)
        ->and(Company::first()->id)->toBe($this->companyA->id);
});

test('department user can only see their own department data', function () {
    $this->userA->update(['user_type' => UserType::Department]);
    Auth::login($this->userA);

    expect(Department::count())->toBe(1)
        ->and(Department::first()->id)->toBe($this->deptA->id);

    expect(Section::count())->toBe(1)
        ->and(Section::first()->id)->toBe($this->sectionA->id);

    expect(Division::count())->toBe(1)
        ->and(Division::first()->id)->toBe($this->divisionA->id);
});

test('section user can only see their own section data', function () {
    $this->userA->update(['user_type' => UserType::Section]);
    Auth::login($this->userA);

    expect(Section::count())->toBe(1)
        ->and(Section::first()->id)->toBe($this->sectionA->id);

    expect(Department::count())->toBe(1)
        ->and(Department::first()->id)->toBe($this->deptA->id);
});

test('employee user can only see their own data', function () {
    $this->userA->update(['user_type' => UserType::Employee]);
    Auth::login($this->userA);

    expect(Employee::count())->toBe(1)
        ->and(Employee::first()->id)->toBe($this->employeeA->id);
    
    // For other tables, it should filter by employee_id if it exists
    // Let's create an attendance record for both
    \App\Models\Attendance\Attendance::create(['employee_id' => $this->employeeA->id, 'attendance_date' => now()]);
    \App\Models\Attendance\Attendance::create(['employee_id' => $this->employeeB->id, 'attendance_date' => now()]);

    expect(\App\Models\Attendance\Attendance::count())->toBe(1)
        ->and(\App\Models\Attendance\Attendance::first()->employee_id)->toBe($this->employeeA->id);
});

