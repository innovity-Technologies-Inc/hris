<?php

use App\Models\User;
use App\Models\Employee\Employee;
use App\Models\Company\Company;
use App\Enums\UserType;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Maatwebsite\Excel\Facades\Excel;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Setup General Setting
    \App\Models\Setting\GeneralSetting::updateOrCreate(['id' => 1], [
        'name' => 'HRMS Test',
        'currency' => '৳',
        'branch_status' => 1,
        'division_status' => 1,
        'department_status' => 1,
        'section_status' => 1
    ]);

    // Setup general user
    $this->user = User::factory()->create(['user_type' => UserType::Group]);
    
    // Assign role and permissions
    $role = Role::create(['name' => 'Admin']);
    Permission::findOrCreate('employee-management.view', 'web');
    $role->givePermissionTo('employee-management.view');
    $this->user->assignRole($role);
});

test('employee search index is accessible for authorized users', function () {
    $response = $this->actingAs($this->user)
        ->get(route('employee.employee'));

    $response->assertStatus(200);
    $response->assertViewIs('employee.search_employee');
});

test('employee search index is forbidden for standard employee user type', function () {
    $employeeUser = User::factory()->create(['user_type' => UserType::Employee]);
    
    $response = $this->actingAs($employeeUser)
        ->get(route('employee.employee'));

    $response->assertStatus(403);
});

test('employee search export downloads excel file and respects filters', function () {
    // Mock excel download
    Excel::fake();

    // Create a company
    $company = Company::factory()->create(['name' => 'Acme Corporation']);

    // Create employee with company
    $employeeMatch = Employee::factory()->create(['full_name' => 'John Doe']);
    $employeeMatch->officeInfo()->create([
        'current_company_id' => $company->id,
        'emp_type' => 'permanent',
    ]);

    // Create another employee with different company
    $employeeOther = Employee::factory()->create(['full_name' => 'Jane Smith']);
    $employeeOther->officeInfo()->create([
        'current_company_id' => 999, // Some other id
        'emp_type' => 'contractual',
    ]);

    // Export with company filter
    $response = $this->actingAs($this->user)
        ->get(route('employee.employee.export', [
            'company' => $company->id
        ]));

    $response->assertStatus(200);
    
    // Assert Excel file download was queued
    Excel::assertDownloaded('employee_search_export.xlsx', function (\App\Exports\Employee\EmployeeSearchExport $export) use ($employeeMatch, $employeeOther) {
        $collection = $export->collection();
        return $collection->contains('id', $employeeMatch->id) && 
              !$collection->contains('id', $employeeOther->id);
    });
});

test('employee search export is forbidden for standard employee user type', function () {
    $employeeUser = User::factory()->create(['user_type' => UserType::Employee]);
    
    $response = $this->actingAs($employeeUser)
        ->get(route('employee.employee.export'));

    $response->assertStatus(403);
});
