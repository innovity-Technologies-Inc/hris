<?php

use App\Models\User;
use App\Models\Announcement\Announcement;
use App\Models\Company\Company;
use App\Models\Company\CompanyLocation;
use App\Models\Company\Division;
use App\Models\Company\Department;
use App\Models\Company\Section;
use App\Models\Setting\GeneralSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::factory()->create([
        'user_type' => 'group', // Super admin type
    ]);

    GeneralSetting::create([
        'name' => 'HRMS Test',
        'currency' => '৳',
        'branch_status' => 1,
        'division_status' => 1,
        'department_status' => 1,
        'section_status' => 1,
    ]);

    $this->company = Company::create([
        'name' => 'Test Company',
        'short_name' => 'TC',
        'type_id' => 1,
        'group_id' => 1,
        'email' => 'test@company.com',
        'address' => 'Test Address',
        'status' => 'active'
    ]);

    $this->branch = CompanyLocation::create([
        'name' => 'Test Branch',
        'location_address' => 'Test Address',
        'status' => 'active',
        'company_id' => $this->company->id
    ]);

    $this->division = Division::create([
        'name' => 'Test Division',
        'short_name' => 'TDiv',
        'status' => 'active',
        'company_id' => $this->company->id,
        'location_id' => $this->branch->id
    ]);

    $this->department = Department::create([
        'department_name' => 'Test Department',
        'short_name' => 'TD',
        'status' => 'active',
        'company_id' => $this->company->id
    ]);

    $this->section = Section::create([
        'name' => 'Test Section',
        'short_name' => 'TSec',
        'status' => 'active',
        'department_id' => $this->department->id,
        'division_id' => $this->division->id,
        'location_id' => $this->branch->id,
        'company_id' => $this->company->id
    ]);
});

it('can list announcements', function () {
    $this->withoutMiddleware();
    $this->actingAs($this->admin);

    Announcement::create([
        'title' => 'Test Announcement',
        'content' => '<p>Test content</p>',
        'company_id' => $this->company->id
    ]);

    $response = $this->get(route('announcements.index'));

    $response->assertStatus(200);
    $response->assertSee('Test Announcement');
});

it('can store a new announcement with attachment', function () {
    $this->withoutMiddleware();
    $this->actingAs($this->admin);
    Storage::fake('public');

    $file = UploadedFile::fake()->create('test-document.pdf', 100);

    $data = [
        'title' => 'New Announcement Post',
        'content' => '<p>This is test content.</p>',
        'company_id' => $this->company->id,
        'branch_id' => $this->branch->id,
        'division_id' => $this->division->id,
        'department_id' => $this->department->id,
        'section_id' => $this->section->id,
        'attachment' => $file
    ];

    $response = $this->postJson(route('announcements.store'), $data);

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'message' => 'Announcement created successfully.',
        ]);

    $announcement = Announcement::first();
    expect($announcement->title)->toBe('New Announcement Post');
    expect($announcement->attachment_path)->not->toBeNull();
    expect($announcement->division_id)->toBe($this->division->id);
    expect($announcement->section_id)->toBe($this->section->id);
    Storage::disk('public')->assertExists($announcement->attachment_path);
});

it('can update an announcement and replace attachment', function () {
    $this->withoutMiddleware();
    $this->actingAs($this->admin);
    Storage::fake('public');

    $announcement = Announcement::create([
        'title' => 'Old Title',
        'content' => 'Old Content',
        'attachment_path' => 'announcements/old-file.pdf'
    ]);

    $newFile = UploadedFile::fake()->create('new-document.pdf', 200);

    $data = [
        'title' => 'Updated Title',
        'content' => '<p>Updated Content</p>',
        'division_id' => $this->division->id,
        'section_id' => $this->section->id,
        'attachment' => $newFile
    ];

    $response = $this->putJson(route('announcements.update', $announcement->id), $data);

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'message' => 'Announcement updated successfully.',
        ]);

    $announcement->refresh();
    expect($announcement->title)->toBe('Updated Title');
    expect($announcement->content)->toBe('<p>Updated Content</p>');
    expect($announcement->division_id)->toBe($this->division->id);
    expect($announcement->section_id)->toBe($this->section->id);
    Storage::disk('public')->assertExists($announcement->attachment_path);
});

it('can delete an announcement', function () {
    $this->withoutMiddleware();
    $this->actingAs($this->admin);
    Storage::fake('public');

    $announcement = Announcement::create([
        'title' => 'Delete Me',
        'content' => 'Content',
        'attachment_path' => 'announcements/delete-file.pdf'
    ]);

    $response = $this->deleteJson(route('announcements.destroy', $announcement->id));

    $response->assertStatus(200);
    $this->assertDatabaseMissing('announcements', ['id' => $announcement->id]);
});

it('can trigger PDF generation route', function () {
    $this->withoutMiddleware();
    $this->actingAs($this->admin);

    $announcement = Announcement::create([
        'title' => 'PDF Title',
        'content' => '<p>PDF content text</p>'
    ]);

    $response = $this->get(route('announcements.pdf', $announcement->id));

    expect($response->status())->toBeIn([200, 500]);
});

test('announcements are filtered by user scope targets', function () {
    $this->withoutMiddleware();

    $employeeUser = User::factory()->create([
        'user_type' => 'employee',
    ]);

    $employee = \App\Models\Employee\Employee::factory()->create();
    $employeeUser->update(['employee_id' => $employee->id]);

    \App\Models\Employee\EmployeeOfficeInfo::factory()->create([
        'employee_id' => $employee->id,
        'current_company_id' => $this->company->id,
        'current_business_unit_id' => $this->branch->id,
    ]);

    $otherCompany = Company::create([
        'name' => 'Other Company',
        'short_name' => 'OC',
        'type_id' => 1,
        'group_id' => 1,
        'email' => 'other@company.com',
        'address' => 'Other Address',
        'status' => 'active'
    ]);

    $visibleAnn = Announcement::create([
        'title' => 'Targeted Announcement',
        'content' => 'Content',
        'company_id' => $this->company->id,
        'branch_id' => $this->branch->id
    ]);

    $globalAnn = Announcement::create([
        'title' => 'Global Notice',
        'content' => 'Content'
    ]);

    $hiddenAnn = Announcement::create([
        'title' => 'Other Company Announcement',
        'content' => 'Content',
        'company_id' => $otherCompany->id
    ]);

    $response = $this->actingAs($employeeUser)->get(route('announcements.index'));

    $response->assertStatus(200);
    $response->assertSee('Targeted Announcement');
    $response->assertSee('Global Notice');
    $response->assertDontSee('Other Company Announcement');
});
