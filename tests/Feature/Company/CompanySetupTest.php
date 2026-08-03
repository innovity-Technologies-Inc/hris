<?php

use App\Models\User;
use App\Enums\UserType;
use App\Models\Company\Company;
use App\Models\Company\Group;
use App\Models\Company\CompanyType;
use App\Models\Setting\IDCardDesign;
use App\Models\Employee\Employee;
use App\Models\Employee\EmployeeOfficeInfo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::factory()->create([
        'user_type' => UserType::Group,
        'name' => 'Test Admin'
    ]);

    // Give permissions to the test user
    $permissions = [
        'companies.view',
        'companies.create',
        'companies.edit',
        'companies.delete'
    ];
    foreach ($permissions as $p) {
        \Spatie\Permission\Models\Permission::firstOrCreate(['name' => $p, 'guard_name' => 'web']);
    }
    $this->admin->givePermissionTo($permissions);

    $this->group = Group::create([
        'name' => 'Test Group',
        'short_name' => 'TG',
        'status' => 'active',
    ]);

    $this->companyType = CompanyType::create([
        'name' => 'Test Type',
        'short_name' => 'TT',
        'status' => 'active',
    ]);
});

it('can create and update a company with a website', function () {
    $this->actingAs($this->admin);

    // 1. Create company via POST
    $storeResponse = $this->post(route('companies.store'), [
        'name' => 'Innovative Tech Ltd',
        'short_name' => 'ITL',
        'group_id' => $this->group->id,
        'type_id' => $this->companyType->id,
        'address' => '123 Tech Avenue',
        'telephone' => '1234567890',
        'fax' => '0987654321',
        'email' => 'contact@innovative.com',
        'website' => 'www.innovativetech.com',
        'status' => 'active',
    ]);

    $storeResponse->assertRedirect(route('companies.index'));

    $company = Company::where('short_name', 'ITL')->first();
    expect($company)->not->toBeNull();
    expect($company->website)->toBe('www.innovativetech.com');

    // 2. Update company via PUT
    $updateResponse = $this->put(route('companies.update', $company->id), [
        'name' => 'Innovative Tech Ltd',
        'short_name' => 'ITL',
        'group_id' => $this->group->id,
        'type_id' => $this->companyType->id,
        'address' => '123 Tech Avenue',
        'telephone' => '1234567890',
        'fax' => '0987654321',
        'email' => 'contact@innovative.com',
        'website' => 'www.newinnovative.com',
        'status' => 'active',
    ]);

    $updateResponse->assertRedirect(route('companies.index'));
    $company->refresh();
    expect($company->website)->toBe('www.newinnovative.com');
});

it('renders the company website on employee ID card rendering', function () {
    Storage::fake('public');

    // Create company with specific website
    $company = Company::create([
        'name' => 'Specific Company Ltd',
        'short_name' => 'SCL',
        'group_id' => $this->group->id,
        'type_id' => $this->companyType->id,
        'address' => 'Corporate Tower',
        'website' => 'www.scl-global.com',
        'status' => 'active',
    ]);

    // Create active design
    $design = IDCardDesign::create([
        'theme_name' => 'Theme 1 (Modern Corporate)',
        'file_path' => 'upload/id_card_designs/designs/active_theme.php',
        'status' => 'active'
    ]);

    // Copy design_1 layout content into fake storage path
    $realDesign1Path = resource_path('views/setting/id_design/designs/design_1.blade.php');
    $designContent = file_get_contents($realDesign1Path);
    Storage::disk('public')->put($design->file_path, $designContent);

    // Create employee
    $employee = Employee::factory()->create();

    // Set employee company
    EmployeeOfficeInfo::create([
        'employee_id' => $employee->id,
        'current_company_id' => $company->id,
        'date_of_join' => '2023-01-15',
    ]);

    // Render HTML in preview/compilation pipeline
    $service = app(\App\Services\Setting\IDCardService::class);
    $html = $service->renderIdCardHtml($design, $employee);

    // Verify company website is rendered in HTML
    expect($html)->toContain('www.scl-global.com');
});

it('can view the company bulk upload page', function () {
    $this->actingAs($this->admin);

    // Ensure permission is assigned to user
    \Spatie\Permission\Models\Permission::firstOrCreate(['name' => 'employee-management.import', 'guard_name' => 'web']);
    $this->admin->givePermissionTo('employee-management.import');

    $response = $this->get(route('company.bulk_upload'));
    $response->assertStatus(200);
});
