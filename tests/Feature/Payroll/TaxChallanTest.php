<?php

use App\Models\User;
use App\Models\Employee\Employee;
use App\Models\Employee\EmployeeOfficeInfo;
use App\Models\Company\Company;
use App\Models\Company\Group;
use App\Models\Company\CompanyType;
use App\Models\Payroll\TaxChallan;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

beforeEach(function () {
    app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    Permission::firstOrCreate(['name' => 'tax-policy.view', 'guard_name' => 'web']);

    $this->group = Group::create(['name' => 'Test Group', 'status' => 'active']);
    $this->type = CompanyType::create(['name' => 'IT', 'short_name' => 'IT', 'status' => 'active']);
    $this->company = Company::create([
        'group_id' => $this->group->id,
        'type_id' => $this->type->id,
        'name' => 'Test Company',
        'short_name' => 'TST',
        'status' => 'active',
        'address' => '123 St'
    ]);

    $this->employee = Employee::create([
        'full_name' => 'Jack Doe',
        'applicant_id' => 'APP456',
        'system_id' => 'SYS456',
        'punch_card_no' => 'P456',
        'gender' => 'male',
        'status' => 'active'
    ]);

    EmployeeOfficeInfo::create([
        'employee_id' => $this->employee->id,
        'current_company_id' => $this->company->id,
        'date_of_join' => '2020-01-01',
    ]);

    $this->admin = User::factory()->create([
        'user_type' => \App\Enums\UserType::Company,
    ]);
    $this->admin->givePermissionTo('tax-policy.view');

    // Fake the default storage disk
    $this->disk = config('filesystems.default');
    Storage::fake($this->disk);
});

test('tax challan index page returns 200 ok', function () {
    $response = $this->actingAs($this->admin)->get(route('tax-challan.index'));
    $response->assertStatus(200);
});

test('it can store a tax challan with multiple attachments', function () {
    $file1 = UploadedFile::fake()->create('challan_first.pdf', 500);
    $file2 = UploadedFile::fake()->image('challan_screenshot.png');

    $data = [
        'company_id' => $this->company->id,
        'employee_id' => $this->employee->id,
        'tax_paid_from' => '2026-01',
        'tax_paid_to' => '2026-06',
        'attachments' => [$file1, $file2]
    ];

    $response = $this->actingAs($this->admin)
        ->postJson(route('tax-challan.store'), $data);

    $response->assertStatus(201)
        ->assertJson([
            'success' => true,
            'message' => 'Tax Challan created successfully.'
        ]);

    $challan = TaxChallan::first();
    expect($challan)->not->toBeNull();
    expect($challan->company_id)->toEqual($this->company->id);
    expect($challan->employee_id)->toEqual($this->employee->id);
    expect($challan->tax_paid_from)->toEqual('2026-01');
    expect($challan->tax_paid_to)->toEqual('2026-06');
    expect(count($challan->attachments))->toEqual(2);

    // Verify storage
    Storage::disk($this->disk)->assertExists($challan->attachments[0]);
    Storage::disk($this->disk)->assertExists($challan->attachments[1]);
});

test('it can store a tax challan with null employee_id', function () {
    $data = [
        'company_id' => $this->company->id,
        'employee_id' => null,
        'tax_paid_from' => '2026-01',
        'tax_paid_to' => '2026-06',
        'attachments' => []
    ];

    $response = $this->actingAs($this->admin)
        ->postJson(route('tax-challan.store'), $data);

    $response->assertStatus(201);

    $challan = TaxChallan::whereNull('employee_id')->first();
    expect($challan)->not->toBeNull();
    expect($challan->company_id)->toEqual($this->company->id);
    expect($challan->tax_paid_from)->toEqual('2026-01');
    expect($challan->tax_paid_to)->toEqual('2026-06');
});

test('it can update a tax challan and manage attachments', function () {
    $fileOld = UploadedFile::fake()->create('old_doc.pdf', 100);
    
    // Store initially
    $challan = TaxChallan::create([
        'company_id' => $this->company->id,
        'employee_id' => $this->employee->id,
        'tax_paid_from' => '2026-01',
        'tax_paid_to' => '2026-03',
        'attachments' => [$fileOld->store('upload/tax_challans', $this->disk)]
    ]);

    $oldPath = $challan->attachments[0];
    Storage::disk($this->disk)->assertExists($oldPath);

    // Now update: remove old file, upload a new file
    $fileNew = UploadedFile::fake()->create('new_doc.pdf', 200);
    $updateData = [
        'company_id' => $this->company->id,
        'employee_id' => $this->employee->id,
        'tax_paid_from' => '2026-01',
        'tax_paid_to' => '2026-05',
        'attachments' => [$fileNew],
        'removed_attachments' => [$oldPath]
    ];

    $response = $this->actingAs($this->admin)
        ->postJson(route('tax-challan.update', $challan->id), $updateData);

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'message' => 'Tax Challan updated successfully.'
        ]);

    $challan->refresh();
    expect($challan->tax_paid_to)->toEqual('2026-05');
    expect(count($challan->attachments))->toEqual(1);
    
    // Check old file deleted and new file exists
    Storage::disk($this->disk)->assertMissing($oldPath);
    Storage::disk($this->disk)->assertExists($challan->attachments[0]);
});

test('it can delete a tax challan and delete its files', function () {
    $file = UploadedFile::fake()->create('temp_doc.pdf', 100);
    $path = $file->store('upload/tax_challans', $this->disk);

    $challan = TaxChallan::create([
        'company_id' => $this->company->id,
        'employee_id' => $this->employee->id,
        'tax_paid_from' => '2026-01',
        'tax_paid_to' => '2026-02',
        'attachments' => [$path]
    ]);

    Storage::disk($this->disk)->assertExists($path);

    $response = $this->actingAs($this->admin)
        ->deleteJson(route('tax-challan.destroy', $challan->id));

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'message' => 'Tax Challan deleted successfully.'
        ]);

    expect(TaxChallan::count())->toEqual(0);
    Storage::disk($this->disk)->assertMissing($path);
});
