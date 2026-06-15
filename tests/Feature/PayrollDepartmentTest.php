<?php

use App\Models\Company\Company;
use App\Models\Company\Department;
use App\Models\Company\Division;
use App\Models\Company\Group;
use App\Models\Company\CompanyType;
use App\Models\Payroll\PayrollProcess;
use App\Services\Payroll\PayrollServices;
use Illuminate\Http\Request;
use DaiyanMozumder\LaravelFlexSearch\FlexSearch;

test('payroll process search result eager loads department and uses correct attribute', function () {
    // Setup
    $companyType = CompanyType::create(['name' => 'Type 1', 'short_name' => 'T1', 'status' => 'active']);
    $group = Group::create(['name' => 'Group 1', 'status' => 'active']);
    $company = Company::create([
        'group_id' => $group->id, 
        'type_id' => $companyType->id, 
        'name' => 'Test Company', 
        'short_name' => 'TC', 
        'address' => 'N/A', 
        'status' => 'active'
    ]);
    
    $division = Division::create([
        'name' => 'Test Division',
        'short_name' => 'TD',
        'company_id' => $company->id,
        'status' => 'active'
    ]);
    
    $department = Department::create([
        'department_name' => 'Engineering',
        'short_name' => 'ENG',
        'division_id' => $division->id,
        'company_id' => $company->id,
        'status' => 'active'
    ]);

    $process = PayrollProcess::create([
        'batch_id' => 'B001',
        'company_id' => $company->id,
        'division_id' => $division->id,
        'department_id' => $department->id,
        'salary_month' => '2026-06',
        'type' => 'salary',
        'total_amount' => 1000,
        'total_employee' => 1,
    ]);

    $service = new PayrollServices();
    $request = new Request();
    $flexSearch = new FlexSearch();

    $results = $service->payrollProcessSearchResult($request, PayrollProcess::class, $flexSearch);
    $firstResult = $results->first();

    // Verify eager loading
    expect($firstResult->relationLoaded('getDepartment'))->toBeTrue();
    expect($firstResult->relationLoaded('getCompany'))->toBeTrue();
    expect($firstResult->relationLoaded('getDivision'))->toBeTrue();

    // Verify correct attribute access
    expect($firstResult->getDepartment->department_name)->toBe('Engineering');
    
    // Test that the previous incorrect access 'name' would fail/be null if we had it
    // (In PHP, undefined property on object returns null with a warning, but here we want to ensure we use department_name)
    expect($firstResult->getDepartment->name)->toBeNull();
});
