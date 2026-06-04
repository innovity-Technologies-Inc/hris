<?php

use App\Models\Employee\Employee;
use App\Models\Employee\EmployeeOfficeInfo;
use App\Models\Setting\Notification;
use App\Models\Setting\NotificationSetting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Create notification settings
    NotificationSetting::create([
        'birthday_days' => 1,
        'visa_days' => 30,
        'work_permit_days' => 30,
        'passport_days' => 30,
        'license_days' => 30,
        'probation_days' => 7,
    ]);

    // Create a non-employee user (Group type receives everything)
    $this->hr = User::factory()->create(['user_type' => 'Group']);
});

it('triggers birthday notifications for non-employees', function () {
    $today = Carbon::parse('2026-06-01');
    Carbon::setTestNow($today);

    // Employee whose birthday is tomorrow (June 2)
    $employee = Employee::factory()->create([
        'date_of_birth' => '1990-06-02',
    ]);

    Artisan::call('app:check-alerts');

    // Check if notification exists for HR
    $notification = Notification::where('user_id', $this->hr->id)
        ->where('title', 'Birthday Alert')
        ->first();

    expect($notification)->not->toBeNull();
    expect($notification->message)->toContain($employee->full_name);
});

it('triggers visa expiry notifications for employee and non-employees', function () {
    $today = Carbon::parse('2026-06-01');
    Carbon::setTestNow($today);

    // Visa expires in 30 days (July 1)
    $expiryDate = $today->copy()->addDays(30)->toDateString();
    
    $employee = Employee::factory()->create([
        'visa_expiry' => $expiryDate,
    ]);

    $employeeUser = User::factory()->create([
        'employee_id' => $employee->id,
        'user_type' => 'Employee'
    ]);

    Artisan::call('app:check-alerts');

    // Check notification for HR
    $hrNotification = Notification::where('user_id', $this->hr->id)
        ->where('title', 'Visa Expiry Alert')
        ->first();
    expect($hrNotification)->not->toBeNull();

    // Check notification for Employee
    $empNotification = Notification::where('user_id', $employeeUser->id)
        ->where('title', 'Visa Expiry Alert')
        ->first();
    expect($empNotification)->not->toBeNull();
});

it('triggers probation end notifications', function () {
    $today = Carbon::parse('2026-06-01');
    Carbon::setTestNow($today);

    // Probation ends in 7 days (June 8)
    // Formula: date_of_join + probation_duration = target
    // If target is June 8, and duration is 90, join should be June 8 - 90 days.
    $targetDate = $today->copy()->addDays(7);
    $joinDate = $targetDate->copy()->subDays(90)->toDateString();

    $employee = Employee::factory()->create();
    EmployeeOfficeInfo::create([
        'employee_id' => $employee->id,
        'date_of_join' => $joinDate,
        'probation_duration' => 90
    ]);

    Artisan::call('app:check-alerts');

    $notification = Notification::where('user_id', $this->hr->id)
        ->where('title', 'Probation End Alert')
        ->first();

    expect($notification)->not->toBeNull();
});
