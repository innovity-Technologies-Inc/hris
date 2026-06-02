<?php

use App\Models\Company\Company;
use App\Models\Employee\Employee;
use App\Models\Employee\EmployeeOfficeInfo;
use App\Models\User;
use App\Services\Employee\EmployeeReportServices;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    Permission::firstOrCreate(['name' => 'employee-management.analytics', 'guard_name' => 'web']);
    $role = Role::firstOrCreate(['name' => 'HR Admin', 'guard_name' => 'web']);
    $role->givePermissionTo('employee-management.analytics');

    $this->admin = User::factory()->create(['user_type' => 'Company']);
    $this->admin->assignRole($role);

    $this->employeeUser = User::factory()->create(['user_type' => 'Employee']);

    $this->reportService = new EmployeeReportServices();
});

it('calculates age distribution correctly', function () {
    // Create employees with different ages
    Employee::factory()->create(['date_of_birth' => now()->subYears(20)]); // 18-25
    Employee::factory()->create(['date_of_birth' => now()->subYears(30)]); // 26-35
    Employee::factory()->create(['date_of_birth' => now()->subYears(40)]); // 36-45

    $dist = $this->reportService->getAgeDistribution();

    expect($dist['labels'])->toContain('18-25', '26-35', '36-45');
    expect(array_sum($dist['data']))->toBeGreaterThanOrEqual(3);
});

it('calculates detailed age analysis', function () {
    Employee::factory()->create(['date_of_birth' => now()->subYears(20)]); 
    Employee::factory()->create(['date_of_birth' => now()->subYears(40)]); 

    $stats = $this->reportService->getAgeAnalysis();

    expect($stats['min'])->toBe(20);
    expect($stats['max'])->toBe(40);
    expect($stats['avg'])->toBe(30.0);
});

it('calculates company distribution', function () {
    $company = Company::create([
        'name' => 'Tech Corp',
        'short_name' => 'TC',
        'type_id' => 1,
        'group_id' => 1,
        'email' => 'tech@example.com',
        'address' => '123 Tech St',
        'status' => 'active'
    ]);
    $emp = Employee::factory()->create();
    EmployeeOfficeInfo::factory()->create([
        'employee_id' => $emp->id,
        'current_company_id' => $company->id
    ]);

    $dist = $this->reportService->getCompanyDistribution();

    expect($dist['labels'])->toContain('Tech Corp');
});

it('calculates service loyalty correctly', function () {
    // 6 months join
    $emp1 = Employee::factory()->create();
    EmployeeOfficeInfo::factory()->create(['employee_id' => $emp1->id, 'date_of_join' => now()->subMonths(6)]);
    
    // 2 years join
    $emp2 = Employee::factory()->create();
    EmployeeOfficeInfo::factory()->create(['employee_id' => $emp2->id, 'date_of_join' => now()->subYears(2)]);

    $dist = $this->reportService->getServiceLoyalty();

    expect($dist['labels'])->toContain('< 1 Year', '1-3 Years');
});

it('identifies upcoming birthdays', function () {
    $currentMonth = now()->month;
    $emp = Employee::factory()->create(['date_of_birth' => now()->setMonth($currentMonth)->setDay(15)->subYears(25)]);

    $birthdays = $this->reportService->getUpcomingBirthdays();

    expect($birthdays)->not->toBeEmpty();
    expect($birthdays[0]['full_name'])->toBe($emp->full_name);
});

it('loads the analytics page for authorized users', function () {
    $this->withoutExceptionHandling();
    $this->actingAs($this->admin);

    $response = $this->get(route('employee.reports'));

    $response->assertStatus(200);
    $response->assertViewIs('employee.reports');
    $response->assertViewHasAll(['ageDist', 'ageStats', 'loyaltyDist', 'companyDist', 'dynamicHierarchies', 'filterOptions', 'birthdays', 'serviceSummary']);
});

it('denies access to analytics for users without permission', function () {
    $userWithoutPermission = User::factory()->create(['user_type' => 'Company']);
    $this->actingAs($userWithoutPermission);

    $response = $this->get(route('employee.reports'));

    $response->assertStatus(403);
});

it('denies access to analytics for Employee user type even with permission', function () {
    $role = Role::firstOrCreate(['name' => 'Employee Role', 'guard_name' => 'web']);
    $role->givePermissionTo('employee-management.analytics');
    $this->employeeUser->assignRole($role);
    
    $this->actingAs($this->employeeUser);

    $response = $this->get(route('employee.reports'));

    $response->assertStatus(403);
});
