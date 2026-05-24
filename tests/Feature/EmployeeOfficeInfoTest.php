<?php

use App\Models\Employee\Employee;
use App\Models\Employee\EmployeeOfficeInfo;
use App\Models\Company\Company;
use App\Models\Company\CompanyLocation;
use App\Models\Company\Division;
use App\Models\Company\Department;
use App\Models\Company\Section;
use App\Models\Company\Designation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create(['user_type' => 'Group']);
    $this->actingAs($this->user);
    $this->withoutMiddleware();

    $this->company = Company::create([
        'name' => 'Test Company',
        'short_name' => 'TC',
        'type_id' => 1,
        'group_id' => 1,
        'address' => 'Test Address',
        'status' => 'active'
    ]);
    $this->unit = CompanyLocation::create(['name' => 'Test Unit', 'company_id' => $this->company->id, 'location_address' => 'Test Addr', 'status' => 'active']);
    $this->division = Division::create(['name' => 'Test Division', 'company_id' => $this->company->id, 'location_id' => $this->unit->id, 'status' => 'active', 'short_name' => 'TD']);
    $this->department = Department::create(['department_name' => 'Test Dept', 'company_id' => $this->company->id, 'location_id' => $this->unit->id, 'division_id' => $this->division->id, 'status' => 'active', 'short_name' => 'TDEPT']);
    $this->section = Section::create(['name' => 'Test Section', 'company_id' => $this->company->id, 'location_id' => $this->unit->id, 'division_id' => $this->division->id, 'department_id' => $this->department->id, 'status' => 'active', 'short_name' => 'TS']);
    $this->designation = Designation::create(['company_designation' => 'Test Dev', 'designation_level' => 'Junior', 'status' => 'active']);

    $this->employee = Employee::create([
        'full_name' => 'John Doe',
        'applicant_id' => 'EMP001',
        'system_id' => 'SYS001',
        'punch_card_no' => 'P001',
        'user_id' => $this->user->id,
        'status' => 'active'
    ]);
});

test('it can save and display employee office info correctly', function () {
    $data = [
        'employee_id' => $this->employee->id,
        'emp_type' => 'permanent',
        'joining_company_id' => $this->company->id,
        'joining_business_unit_id' => $this->unit->id,
        'joining_division_id' => $this->division->id,
        'joining_department_id' => $this->department->id,
        'joining_section_id' => $this->section->id,
        'joining_designation_id' => $this->designation->id,
        'date_of_join' => '2023-01-01',
        'current_company_id' => $this->company->id,
        'current_business_unit_id' => $this->unit->id,
        'current_division_id' => $this->division->id,
        'current_department_id' => $this->department->id,
        'current_section_id' => $this->section->id,
        'current_designation_id' => $this->designation->id,
        'orientation_required' => 'yes',
        'salary_type' => 'monthly',
    ];

    $response = $this->post(route('employee.office_informations.store'), $data);
    $response->assertStatus(302);

    $officeInfo = EmployeeOfficeInfo::where('employee_id', $this->employee->id)->first();
    expect($officeInfo)->not->toBeNull();
    expect($officeInfo->joining_business_unit_id)->toBe($this->unit->id);

    // Verify relations work and use the correct attribute names
    expect($officeInfo->getJoiningBusinessUnit->name)->toBe('Test Unit');
    expect($officeInfo->getJoiningDivision->name)->toBe('Test Division');
    expect($officeInfo->getJoiningDepartment->department_name)->toBe('Test Dept');
    expect($officeInfo->getJoiningSection->name)->toBe('Test Section');
    expect($officeInfo->getJoiningDesignation->company_designation)->toBe('Test Dev');

    // Verify profile view renders without error and shows correct names
    $viewResponse = $this->get(route('employee.profile.office_informations', $this->employee->id));
    $viewResponse->assertStatus(200);
    $viewResponse->assertSee('Test Unit');
    $viewResponse->assertSee('Test Division');
    $viewResponse->assertSee('Test Dept');
    $viewResponse->assertSee('Test Section');
});

test('it can update employee office info correctly', function () {
    $officeInfo = EmployeeOfficeInfo::create([
        'employee_id' => $this->employee->id,
        'emp_type' => 'permanent',
        'orientation_required' => 'no',
    ]);

    $updateData = [
        'employee_id' => $this->employee->id,
        'emp_type' => 'contractual',
        'orientation_required' => 'yes',
        'joining_company_id' => $this->company->id,
        'current_company_id' => $this->company->id,
        'salary_type' => 'monthly',
    ];

    $response = $this->put(route('employee.office_informations.update', $officeInfo->id), $updateData);
    $response->assertStatus(302);

    $officeInfo->refresh();
    expect($officeInfo->emp_type)->toBe('contractual');
    expect($officeInfo->orientation_required)->toBe('yes');
});
