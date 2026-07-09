<?php

use App\Models\User;
use App\Models\Employee\Employee;
use App\Models\Employee\EmployeeOfficeInfo;
use App\Models\Company\Company;
use App\Models\Company\Group;
use App\Models\Company\CompanyType;
use App\Models\Transfer\Transfer;
use App\Models\Payroll\Increment;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Enums\UserType;
use Spatie\Permission\Models\Permission;

beforeEach(function () {
    app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    Permission::firstOrCreate(['name' => 'transfers.create', 'guard_name' => 'web']);
    Permission::firstOrCreate(['name' => 'transfers.view', 'guard_name' => 'web']);
    Permission::firstOrCreate(['name' => 'transfers.approve', 'guard_name' => 'web']);
    Permission::firstOrCreate(['name' => 'transfers.edit', 'guard_name' => 'web']);
    
    $this->group = Group::create(['name' => 'Test Group', 'status' => 'active']);
    $this->type = CompanyType::create(['name' => 'IT', 'short_name' => 'IT', 'status' => 'active']);

    \App\Models\Setting\GeneralSetting::firstOrCreate([], [
        'name' => 'Test HRMS',
        'branch_status' => 1
    ]);
    \App\Models\Setting\TransferSetting::firstOrCreate([], [
        'employee_transfer_level' => 'company',
        'supervisor_transfer_level' => 'company'
    ]);
});

test('it can store a transfer with multiple attachments', function () {
    $this->withoutMiddleware();
    Storage::fake('public');

    $admin = User::factory()->create(['user_type' => UserType::Group]);
    $role = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
    $role->syncPermissions(['transfers.create', 'transfers.view', 'transfers.approve', 'transfers.edit']);
    $admin->assignRole($role);

    $employee = Employee::create([
        'full_name' => 'John Doe',
        'applicant_id' => 'APP001',
        'system_id' => 'SYS001',
        'punch_card_no' => 'P001',
        'status' => 'active'
    ]);
    $oldCompany = Company::create(['group_id' => $this->group->id, 'type_id' => $this->type->id, 'name' => 'Old Company', 'short_name' => 'OLD', 'status' => 'active', 'address' => '123 St']);
    $newCompany = Company::create(['group_id' => $this->group->id, 'type_id' => $this->type->id, 'name' => 'New Company', 'short_name' => 'NEW', 'status' => 'active', 'address' => '456 St']);
    
    EmployeeOfficeInfo::create([
        'employee_id' => $employee->id,
        'current_company_id' => $oldCompany->id,
    ]);

    $file1 = UploadedFile::fake()->create('doc1.pdf', 100);
    $file2 = UploadedFile::fake()->create('doc2.jpg', 200);

    $response = $this->actingAs($admin, 'web')->postJson(route('transfer.api.store'), [
        'employee_id' => $employee->id,
        'requested_company_id' => $newCompany->id,
        'remarks' => 'Transfer remarks',
        'attachments' => [$file1, $file2]
    ]);

    $response->assertStatus(200)->assertJson(['success' => true]);
    $transfer = Transfer::latest()->first();

    expect($transfer->attachments)->toHaveCount(2);
    expect($transfer->attachments->first()->file_name)->toBe('doc1.pdf');
    Storage::disk('public')->assertExists($transfer->attachments->first()->file_path);
});

test('it can store an increment with multiple attachments', function () {
    $this->withoutMiddleware();
    Storage::fake('public');

    $admin = User::factory()->create(['user_type' => UserType::Group]);
    $employee = Employee::create([
        'full_name' => 'John Doe',
        'applicant_id' => 'APP001',
        'system_id' => 'SYS001',
        'punch_card_no' => 'P001',
        'status' => 'active'
    ]);
    
    $company = Company::create(['group_id' => $this->group->id, 'type_id' => $this->type->id, 'name' => 'C1', 'short_name' => 'C1', 'status' => 'active', 'address' => 'A1']);
    EmployeeOfficeInfo::create([
        'employee_id' => $employee->id,
        'current_company_id' => $company->id,
    ]);
    $employee->salary()->create([
        'gross_salary' => 50000,
        'basic_salary' => 30000,
    ]);

    $file1 = UploadedFile::fake()->create('attachment1.pdf', 100);

    $response = $this->actingAs($admin)->postJson(route('increment.store'), [
        'employee_id' => $employee->id,
        'increment_base' => 'basic_salary',
        'increment_method' => 'fixed',
        'salary_increase_amount' => 5000,
        'effective_from' => '2026-08-01',
        'attachments' => [$file1]
    ]);

    $response->assertStatus(200)->assertJson(['success' => true]);
    $increment = Increment::latest()->first();

    expect($increment->attachments)->toHaveCount(1);
    expect($increment->attachments->first()->file_name)->toBe('attachment1.pdf');
    Storage::disk('public')->assertExists($increment->attachments->first()->file_path);
});
