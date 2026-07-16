<?php

use App\Models\User;
use App\Enums\UserType;
use App\Models\Setting\GoogleMapApi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Crypt;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::factory()->create([
        'user_type' => UserType::Group,
        'name' => 'Test Admin'
    ]);

    // Give permissions to the test user
    $permissions = [
        'api-keys.view',
        'api-keys.create'
    ];
    foreach ($permissions as $p) {
        \Spatie\Permission\Models\Permission::firstOrCreate(['name' => $p, 'guard_name' => 'web']);
    }
    $this->admin->givePermissionTo($permissions);
});

it('can manage google maps api key settings via axios', function () {
    $this->actingAs($this->admin);

    // 1. View Index
    $response = $this->get(route('setting.google_map_api'));
    $response->assertStatus(200);

    // 2. Axios Save
    $saveResponse = $this->postJson(route('setting.google_map_api.save'), [
        'google_maps_api_key' => 'AIzaSyExampleKey12345',
        'google_maps_radius' => 500,
    ]);

    $saveResponse->assertStatus(200);
    $saveResponse->assertJson([
        'success' => true,
        'message' => 'Google Maps Settings Saved Successfully'
    ]);

    // Assert it was saved to DB and is encrypted
    $apiKey = GoogleMapApi::first();
    expect($apiKey)->not->toBeNull();
    
    // Accesor decrypts automatically
    expect($apiKey->google_maps_api_key)->toBe('AIzaSyExampleKey12345');
    expect($apiKey->google_maps_radius)->toBe(500);
    
    // Direct attribute is encrypted
    $rawVal = DB::table('api_keys')->first()->google_maps_api_key;
    expect(Crypt::decrypt($rawVal))->toBe('AIzaSyExampleKey12345');

    // 3. Update existing
    $updateResponse = $this->postJson(route('setting.google_map_api.save'), [
        'google_maps_api_key' => 'AIzaSyUpdatedKey98765',
        'google_maps_radius' => 1000,
    ]);

    $updateResponse->assertStatus(200);
    $apiKey->refresh();
    expect($apiKey->google_maps_api_key)->toBe('AIzaSyUpdatedKey98765');
    expect($apiKey->google_maps_radius)->toBe(1000);
});
