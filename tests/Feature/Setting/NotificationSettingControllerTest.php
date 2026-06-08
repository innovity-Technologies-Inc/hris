<?php

use App\Models\User;
use App\Models\Setting\NotificationSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->admin = User::factory()->create([
        'user_type' => 'company',
    ]);
});

it('allows authorized users to save notification settings via axios', function () {
    $this->withoutMiddleware();
    $this->actingAs($this->admin);

    $data = [
        'birthday_days' => 5,
        'visa_days' => 10,
        'work_permit_days' => 15,
        'passport_days' => 20,
        'license_days' => 25,
        'probation_days' => 30,
    ];

    $response = $this->postJson(route('setting.notification_settings.store'), $data);

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'message' => 'Notification settings updated successfully.',
        ]);

    $settings = NotificationSetting::first();
    expect($settings->birthday_days)->toBe(5);
    expect($settings->probation_days)->toBe(30);
});

it('validates notification settings data', function () {
    $this->withoutMiddleware();
    $this->actingAs($this->admin);

    $data = [
        'birthday_days' => 'invalid',
    ];

    $response = $this->postJson(route('setting.notification_settings.store'), $data);

    $response->assertStatus(422); // Validation error
});
