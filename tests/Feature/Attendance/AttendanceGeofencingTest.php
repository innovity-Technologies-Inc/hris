<?php

use App\Models\User;
use App\Enums\UserType;
use App\Models\Employee\Employee;
use App\Models\Employee\EmployeeOfficeInfo;
use App\Models\Company\Company;
use App\Models\Company\Group;
use App\Models\Company\CompanyType;
use App\Models\Company\CompanyLocation;
use App\Models\Setting\GoogleMapApi;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::factory()->create([
        'user_type' => UserType::Group,
        'name' => 'Test Admin'
    ]);

    $group = Group::create([
        'name' => 'Test Group',
        'short_name' => 'TG',
        'status' => 'active',
    ]);

    $companyType = CompanyType::create([
        'name' => 'Test Type',
        'short_name' => 'TT',
        'status' => 'active',
    ]);

    $company = Company::create([
        'name' => 'Test Company',
        'short_name' => 'TC',
        'group_id' => $group->id,
        'type_id' => $companyType->id,
        'address' => 'Test Address',
        'status' => 'active',
    ]);

    $this->branch = CompanyLocation::create([
        'company_id' => $company->id,
        'name' => 'Dhaka Branch',
        'location_address' => 'Dhaka, Bangladesh',
        'latitude' => 23.8103,
        'longitude' => 90.4125,
        'status' => 'active'
    ]);

    $this->employee = Employee::factory()->create([
        'status' => 'active',
    ]);

    EmployeeOfficeInfo::create([
        'employee_id' => $this->employee->id,
        'current_company_id' => $company->id,
        'current_business_unit_id' => $this->branch->id,
        'card_number' => '123456',
        'joining_date' => now()->toDateString(),
    ]);

    // Save radius setting
    GoogleMapApi::create([
        'google_maps_api_key' => 'AIzaSyExampleKey12345',
        'google_maps_radius' => 500
    ]);
    
    // Clear and reload config cache for the test
    app(\App\Services\Setting\SystemConfigLoaderService::class)->loadConfigs();
});

it('includes branch coordinates and covering radius in attendance details response', function () {
    $this->actingAs($this->admin);

    $response = $this->getJson('/get-attendance-details/' . $this->employee->id);

    $response->assertStatus(200);
    $response->assertJsonStructure([
        'status',
        'branch' => [
            'id',
            'latitude',
            'longitude',
            'name'
        ],
        'covering_radius'
    ]);

    expect(floatval($response->json('branch.latitude')))->toBe(23.8103);
    expect(floatval($response->json('branch.longitude')))->toBe(90.4125);
    expect(intval($response->json('covering_radius')))->toBe(500);
});
