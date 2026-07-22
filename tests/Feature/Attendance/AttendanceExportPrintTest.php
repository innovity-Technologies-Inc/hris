<?php

use App\Models\User;
use App\Enums\UserType;
use App\Models\Employee\Employee;
use App\Models\Attendance\Attendance;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Setup General Setting
    \App\Models\Setting\GeneralSetting::updateOrCreate(['id' => 1], [
        'name' => 'HRMS Test',
        'currency' => '৳',
    ]);

    $this->admin = User::factory()->create([
        'user_type' => UserType::Group,
        'name' => 'Test Admin'
    ]);

    // Give permission
    $role = Role::create(['name' => 'Admin']);
    Permission::findOrCreate('attendance.view', 'web');
    $role->givePermissionTo('attendance.view');
    $this->admin->assignRole($role);
});

test('it can load the attendance index page', function () {
    $response = $this->actingAs($this->admin)
        ->get(route('attendance.index'));

    $response->assertStatus(200);
});

test('it can print the attendance records with filters', function () {
    $employee = Employee::factory()->create(['full_name' => 'Jane Doe']);
    
    // Create attendance
    Attendance::create([
        'employee_id' => $employee->id,
        'in_time' => '2026-07-22 09:00:00',
        'in_status' => 'On-Time',
        'attendance_status' => 'Present',
    ]);

    $response = $this->actingAs($this->admin)
        ->get(route('attendance.print', ['keyword' => 'Jane Doe']));

    $response->assertStatus(200);
    $response->assertViewHas('attendanceRecords');
    $records = $response->viewData('attendanceRecords');
    expect($records)->toHaveCount(1);
    expect($records->first()->getEmployee->full_name)->toBe('Jane Doe');
});

test('it can export attendance records to excel with filters', function () {
    $employee1 = Employee::factory()->create(['full_name' => 'Jane Doe']);
    $employee2 = Employee::factory()->create(['full_name' => 'John Smith']);

    Attendance::create([
        'employee_id' => $employee1->id,
        'in_time' => '2026-07-22 09:00:00',
        'in_status' => 'On-Time',
        'attendance_status' => 'Present',
    ]);

    Attendance::create([
        'employee_id' => $employee2->id,
        'in_time' => '2026-07-22 09:05:00',
        'in_status' => 'On-Time',
        'attendance_status' => 'Present',
    ]);

    $response = $this->actingAs($this->admin)
        ->get(route('attendance.export.excel', ['keyword' => 'Jane Doe']));

    $response->assertStatus(200);
    // It should trigger a file download response
    $this->assertTrue(
        str_contains(
            $response->headers->get('content-disposition'),
            'attachment; filename=attendance_records.xlsx'
        )
    );
});
