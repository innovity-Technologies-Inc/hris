<?php

use App\Models\User;
use App\Models\Employee\Employee;
use App\Models\Employee\EmployeeOfficeInfo;
use App\Models\Payroll\Payroll;
use App\Models\Payroll\Promotion;
use App\Models\Payroll\Increment;
use App\Models\Transfer\Transfer;
use Illuminate\Foundation\Testing\RefreshDatabase;

beforeEach(function () {
    $this->admin = User::factory()->create(['user_type' => 'Group']);
    $this->employee = Employee::factory()->create(['full_name' => 'Test Employee']);
    $this->empUser = User::factory()->create(['user_type' => 'Employee', 'employee_id' => $this->employee->id]);
    
    EmployeeOfficeInfo::create([
        'employee_id' => $this->employee->id,
        'current_company_id' => 1,
        'date_of_join' => '2020-01-01',
    ]);
});

it('calculates employee dashboard statistics correctly', function () {
    // Mock Payrolls
    Payroll::create([
        'employee_id' => $this->employee->id,
        'batch_id' => 'BATCH-' . uniqid(),
        'salary' => 50000,
        'deduction_amount' => 0,
        'leaves_count' => 0,
        'offday_work_count' => 0,
        'absent_count' => 0,
        'late_count' => 0,
        'excessive_late_count' => 0,
        'early_exit_count' => 0,
        'overtime_count' => 0,
        'overtime_amount' => 5000,
        'offday_work_salary' => 2000,
        'bonus_amount' => 10000,
        'process_id' => 1,
    ]);

    $service = app(\App\Services\Employee\EmployeeDashboardServices::class);
    $stats = $service->getDashboardStats($this->employee->id);

    expect($stats['total_earnings'])->toBe(57000.0) 
        ->and($stats['total_bonus'])->toBe(10000.0)
        ->and($stats['tenure'])->toMatch('/\d+y \d+m \d+d/'); 
});

it('aggregates timeline events in correct order', function () {
    // 1. Create Promotion (3 years ago)
    Promotion::create([
        'employee_id' => $this->employee->id,
        'previous_designation' => 1,
        'new_designation' => 2,
        'increment_base' => 'gross_salary',
        'increment_method' => 'fixed',
        'salary_increase_amount' => 5000,
        'increment_amount_value' => 5000,
        'previous_basic_salary' => 20000,
        'previous_gross_salary' => 40000,
        'new_gross_salary' => 45000,
        'effective_from' => now()->subYears(3)->format('Y-m-d'),
        'status' => 'approved'
    ]);

    // 2. Create Increment (1 year ago)
    Increment::create([
        'employee_id' => $this->employee->id,
        'increment_base' => 'gross_salary',
        'increment_method' => 'fixed',
        'salary_increase_amount' => 2000,
        'increment_amount_value' => 2000,
        'previous_basic_salary' => 22000,
        'previous_gross_salary' => 45000,
        'new_gross_salary' => 47000,
        'effective_from' => now()->subYears(1)->format('Y-m-d'),
        'status' => 'approved'
    ]);

    $service = app(\App\Services\Employee\EmployeeDashboardServices::class);
    $events = $service->getTimelineEvents($this->employee->id);

    // Should have Onboarding, Joining, Promotion, Increment
    expect($events->count())->toBeGreaterThanOrEqual(4);
    
    // Sort by date desc: Increment (1y ago), Promotion (3y ago), Joining (2020), Onboarding (today)
    expect($events[0]['type'])->toBe('onboarding'); 
    
    $joiningEvent = $events->firstWhere('type', 'joining');
    expect($joiningEvent)->not->toBeNull()
        ->and($joiningEvent['title'])->toBe('Joined Organization');
});

it('restricts employee dashboard access', function () {
    $anotherEmployee = Employee::create([
        'full_name' => 'Other Emp',
        'applicant_id' => 'OTHER-' . uniqid(),
        'system_id' => 'OTHER-SYS-' . uniqid(),
        'punch_card_no' => 'OTHER-PUNCH-' . uniqid(),
        'father_name' => 'Father',
        'mother_name' => 'Mother',
        'religion' => 'Islam',
        'nationality' => 'Bangladeshi',
        'gender' => 'Male',
        'present_address' => json_encode(['address' => 'Addr']),
        'date_of_birth' => '1990-01-01',
        'personal_mobile' => '0123456789',
        'status' => 'active'
    ]);
    
    EmployeeOfficeInfo::create([
        'employee_id' => $anotherEmployee->id,
        'current_company_id' => 1,
    ]);

    // Employee trying to see another's dashboard should fail
    $this->actingAs($this->empUser)
        ->get(route('employee.dashboard.show', $anotherEmployee->id))
        ->assertStatus(403);

    // Admin can see any dashboard
    $this->actingAs($this->admin)
        ->get(route('employee.dashboard.show', $anotherEmployee->id))
        ->assertStatus(200);
});
