<?php

use App\Models\User;
use App\Enums\UserType;
use App\Models\Company\Company;
use App\Models\Company\Group;
use App\Models\Company\CompanyType;
use App\Models\Company\CompanyLocation;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::factory()->create([
        'user_type' => UserType::Group,
        'name' => 'Test Admin'
    ]);

    // Give permissions to the test user
    $permissions = [
        'company-branches.view',
        'company-branches.create',
        'company-branches.edit',
        'company-branches.delete'
    ];
    foreach ($permissions as $p) {
        \Spatie\Permission\Models\Permission::firstOrCreate(['name' => $p, 'guard_name' => 'web']);
    }
    $this->admin->givePermissionTo($permissions);

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

    $this->company = Company::create([
        'name' => 'Test Company',
        'short_name' => 'TC',
        'group_id' => $group->id,
        'type_id' => $companyType->id,
        'address' => 'Test Address',
        'status' => 'active',
    ]);
});

it('can manage company locations via axios', function () {
    $this->actingAs($this->admin);

    // 1. View Index
    $response = $this->get(route('company_locations.index'));
    $response->assertStatus(200);

    // 2. Axios Store
    $storeResponse = $this->postJson(route('company_locations.store'), [
        'company_id' => $this->company->id,
        'name' => 'Dhaka Branch',
        'location_address' => 'Dhaka, Bangladesh',
        'city' => 'Dhaka',
        'state' => 'Dhaka Division',
        'division' => 'Dhaka Division',
        'country' => 'Bangladesh',
        'status' => 'active'
    ]);

    $storeResponse->assertStatus(200);
    $storeResponse->assertJson([
        'success' => true,
        'message' => 'Company Location Saved Successfully'
    ]);

    $location = CompanyLocation::where('name', 'Dhaka Branch')->first();
    expect($location)->not->toBeNull();
    expect($location->location_address)->toBe('Dhaka, Bangladesh');

    // 3. Axios Edit (JSON response)
    $editResponse = $this->getJson(route('company_locations.edit', $location->id));
    $editResponse->assertStatus(200);
    expect($editResponse->json('name'))->toBe('Dhaka Branch');

    // 4. Axios Update
    $updateResponse = $this->putJson(route('company_locations.update', $location->id), [
        'company_id' => $this->company->id,
        'name' => 'Dhaka Main Branch',
        'location_address' => 'Mirpur, Dhaka, Bangladesh',
        'city' => 'Dhaka',
        'state' => 'Dhaka Division',
        'division' => 'Dhaka Division',
        'country' => 'Bangladesh',
        'status' => 'active'
    ]);

    $updateResponse->assertStatus(200);
    $updateResponse->assertJson([
        'success' => true,
        'message' => 'Company Location updated Successfully'
    ]);

    $location->refresh();
    expect($location->name)->toBe('Dhaka Main Branch');
    expect($location->location_address)->toBe('Mirpur, Dhaka, Bangladesh');

    // 5. Axios Destroy
    $deleteResponse = $this->deleteJson(route('company_locations.destroy', $location->id));
    $deleteResponse->assertStatus(200);
    $deleteResponse->assertJson([
        'success' => true,
        'message' => 'Company location deleted successfully.'
    ]);

    expect(CompanyLocation::find($location->id))->toBeNull();
});
