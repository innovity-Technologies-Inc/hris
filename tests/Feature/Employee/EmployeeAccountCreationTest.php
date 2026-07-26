<?php

use App\Models\User;
use App\Enums\UserType;
use App\Models\Employee\Employee;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
    $role = Role::firstOrCreate(['name' => 'Admin']);
    Permission::findOrCreate('employee-management.create', 'web');
    Permission::findOrCreate('employee-management.edit', 'web');
    $role->givePermissionTo(['employee-management.create', 'employee-management.edit']);
    $this->user->assignRole($role);
});

test('it can create employee account with explicit applicant_id and system_id', function () {
    $response = $this->actingAs($this->user)
        ->post(route('employee.store_account'), [
            'full_name' => 'John Explicit Doe',
            'applicant_id' => 'APP999999',
            'system_id' => 'SYS999999',
            'punch_card_no' => 'PUNCH123',
            'work_email' => 'john.explicit@example.com',
            'user_type' => UserType::Employee->value,
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

    $response->assertRedirect(route('employee.index'));
    $response->assertSessionHas('alert-type', 'success');

    $employee = Employee::where('work_email', 'john.explicit@example.com')->first();
    expect($employee)->not->toBeNull();
    expect($employee->applicant_id)->toBe('APP999999');
    expect($employee->system_id)->toBe('SYS999999');
    expect($employee->punch_card_no)->toBe('PUNCH123');
});

test('it can create employee account and auto-generate applicant_id and system_id if not given', function () {
    // Create pre-existing employee to check sequential increments
    Employee::factory()->create([
        'applicant_id' => 'APP000005',
        'system_id' => 'SYS000008',
    ]);

    $response = $this->actingAs($this->user)
        ->post(route('employee.store_account'), [
            'full_name' => 'Jane Auto Doe',
            'punch_card_no' => 'PUNCH456',
            'work_email' => 'jane.auto@example.com',
            'user_type' => UserType::Employee->value,
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

    $response->assertRedirect(route('employee.index'));
    $response->assertSessionHas('alert-type', 'success');

    $employee = Employee::where('work_email', 'jane.auto@example.com')->first();
    expect($employee)->not->toBeNull();
    expect($employee->applicant_id)->toBe('APP000006');
    expect($employee->system_id)->toBe('SYS000009');
});

test('it can save general info and auto-generate applicant_id and system_id if not given', function () {
    // Submit generalInfoStore
    $response = $this->actingAs($this->user)
        ->post(route('employee.general_informations.store'), [
            'first_name' => 'Alex',
            'last_name' => 'Jones',
            'father_name' => 'Father',
            'mother_name' => 'Mother',
            'gender' => 'Male',
            'religion' => 'Christianity',
            'nationality' => 'American',
            'date_of_birth' => '1990-01-01',
            'personal_mobile' => '01712345678',
            'work_email' => 'alex.jones@example.com',
            'punch_card_no' => 'PUNCH789',
            'user_type' => UserType::Employee->value,
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'present_address' => [
                'line_1' => 'Street 1',
                'post_office' => 'PO',
                'district' => 'District',
                'division' => 'Division',
                'zip_code' => '1234',
                'state' => 'State',
                'country' => 'USA',
            ],
            'permanent_address' => [
                'line_1' => 'Street 1',
                'post_office' => 'PO',
                'district' => 'District',
                'division' => 'Division',
                'zip_code' => '1234',
                'state' => 'State',
                'country' => 'USA',
            ],
        ]);

    $employee = Employee::where('work_email', 'alex.jones@example.com')->first();
    expect($employee)->not->toBeNull();
    expect($employee->applicant_id)->not->toBeEmpty();
    expect($employee->system_id)->not->toBeEmpty();
    expect($employee->applicant_id)->toStartWith('APP');
    expect($employee->system_id)->toStartWith('SYS');

    $response->assertRedirect(route('employee.profile.general_informations', $employee->id));
});

test('it renders employee account created mailable with settings name and logo', function () {
    // Setup setting
    $settings = \App\Models\Setting\GeneralSetting::first();
    $settings->update([
        'name' => 'My Custom HRMS',
        'logo_light' => 'settings/logo.png',
        'favicon' => 'settings/favicon.png',
    ]);

    $mailable = new \App\Mail\Employee\EmployeeAccountCreated('John Doe', 'john@example.com', 'password123');

    // Build the mailable view data
    $data = $mailable->content()->with;

    expect($data['appName'])->toBe('My Custom HRMS');
    expect($data['generalSettings']->logo)->toBe('settings/logo.png');

    // Render HTML and assert it contains logo and name
    $html = $mailable->render();
    expect($html)->toContain('My Custom HRMS');
    expect($html)->toContain('settings/logo.png');
});

test('editing employee created via store_account populates first_name and work_email', function () {
    $this->actingAs($this->user);

    $this->post(route('employee.store_account'), [
        'full_name' => 'Michael Smith',
        'punch_card_no' => 'PUNCH999',
        'work_email' => 'michael.smith@example.com',
        'user_type' => UserType::Employee->value,
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $employee = Employee::where('work_email', 'michael.smith@example.com')->first();
    expect($employee)->not->toBeNull();

    $response = $this->get(route('employee.general_informations.edit', $employee->id));
    $response->assertStatus(200);
    $response->assertSee('Michael');
    $response->assertSee('michael.smith@example.com');
});


