<?php

use App\Models\Employee\Employee;
use App\Models\Employee\EmployeeLifecycle;
use App\Models\User;
use App\Services\Employee\EmployeeServices;
use App\Services\Setting\NotificationServices;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use App\Models\Employee\EmployeeOfficeInfo;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create(['user_type' => 'group']);
    $this->actingAs($this->user);
});

test('employee creation creates profile_created lifecycle event', function () {
    $service = new EmployeeServices(new NotificationServices());

    $request = new \Illuminate\Http\Request();
    $request->merge([
        'first_name' => 'John',
        'last_name' => 'Doe',
        'father_name' => 'Father',
        'mother_name' => 'Mother',
        'gender' => 'Male',
        'date_of_birth' => '1990-01-01',
        'personal_mobile' => '01234567890',
        'work_email' => 'john.doe@example.com',
        'user_type' => 'employee',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $validated = [
        'applicant_id' => 'APP-001',
        'system_id' => 'SYS-001',
        'punch_card_no' => 'PC-001',
        'first_name' => 'John',
        'last_name' => 'Doe',
        'father_name' => 'Father',
        'mother_name' => 'Mother',
        'gender' => 'Male',
        'religion' => 'Islam',
        'nationality' => 'Bangladeshi',
        'date_of_birth' => '1990-01-01',
        'personal_mobile' => '01234567890',
        'present_address' => '{"line_1":"Test"}',
    ];

    $employee = $service->employeeInfoSave($request, $validated);

    $this->assertDatabaseHas('employee_lifecycles', [
        'employee_id' => $employee->id,
        'type' => 'profile_created',
    ]);
});

test('employee office info saves probation and confirmed lifecycle events', function () {
    $service = new EmployeeServices(new NotificationServices());
    
    $employee = Employee::factory()->create();

    $validatedProbation = [
        'employee_id' => $employee->id,
        'probation_duration' => 6,
        'date_of_join' => now()->toDateString(),
    ];

    $service->employeeOfficeInfoSave(new \Illuminate\Http\Request(), $validatedProbation);

    $this->assertDatabaseHas('employee_lifecycles', [
        'employee_id' => $employee->id,
        'type' => 'probation',
    ]);

    $officeInfo = EmployeeOfficeInfo::where('employee_id', $employee->id)->first();
    
    $validatedConfirmed = [
        'employee_id' => $employee->id,
        'confirmation_date' => now()->addMonths(6)->toDateString(),
    ];

    $service->employeeOfficeInfoSave(new \Illuminate\Http\Request(), $validatedConfirmed, $officeInfo);

    $this->assertDatabaseHas('employee_lifecycles', [
        'employee_id' => $employee->id,
        'type' => 'confirmed',
    ]);
});

test('toggling employee status creates lifecycle event', function () {
    $service = new EmployeeServices(new NotificationServices());
    $employee = Employee::factory()->create(['status' => 'active']);

    $service->toggleEmployeeStatus($employee->id, 'inactive');

    $this->assertDatabaseHas('employee_lifecycles', [
        'employee_id' => $employee->id,
        'type' => 'inactive',
    ]);
});
