<?php

use App\Models\Employee\Employee;
use App\Models\Employee\EmployeeOfficeInfo;
use App\Models\User;
use App\Services\Employee\EmployeeReportServices;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::factory()->create(['user_type' => 'Company']);
    $this->reportService = new EmployeeReportServices();
});

it('calculates age distribution correctly', function () {
    // Create employees with different ages
    Employee::factory()->create(['date_of_birth' => now()->subYears(20)]); // 18-25
    Employee::factory()->create(['date_of_birth' => now()->subYears(30)]); // 26-35
    Employee::factory()->create(['date_of_birth' => now()->subYears(40)]); // 36-45

    $dist = $this->reportService->getAgeDistribution();

    expect($dist['labels'])->toContain('18-25', '26-35', '36-45');
    expect($dist['data'][0])->toBe(1); // 18-25
    expect($dist['data'][1])->toBe(1); // 26-35
    expect($dist['data'][2])->toBe(1); // 36-45
});

it('calculates service loyalty correctly', function () {
    // 6 months join
    $emp1 = Employee::factory()->create();
    EmployeeOfficeInfo::create(['employee_id' => $emp1->id, 'date_of_join' => now()->subMonths(6)]);
    
    // 2 years join
    $emp2 = Employee::factory()->create();
    EmployeeOfficeInfo::create(['employee_id' => $emp2->id, 'date_of_join' => now()->subYears(2)]);

    $dist = $this->reportService->getServiceLoyalty();

    expect($dist['labels'])->toContain('< 1 Year', '1-3 Years');
    expect($dist['data'][0])->toBe(1); // < 1 Year
    expect($dist['data'][1])->toBe(1); // 1-3 Years
});

it('identifies upcoming birthdays', function () {
    $currentMonth = now()->month;
    $emp = Employee::factory()->create(['date_of_birth' => now()->setMonth($currentMonth)->setDay(15)->subYears(25)]);

    $birthdays = $this->reportService->getUpcomingBirthdays();

    expect($birthdays)->not->toBeEmpty();
    expect($birthdays[0]['full_name'])->toBe($emp->full_name);
});

it('loads the reports page for authorized users', function () {
    $this->actingAs($this->admin);

    $response = $this->get(route('employee.reports'));

    $response->assertStatus(200);
    $response->assertViewIs('employee.reports');
    $response->assertViewHasAll(['ageDist', 'loyaltyDist', 'birthdays', 'serviceSummary']);
});
