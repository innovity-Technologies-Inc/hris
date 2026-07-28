<?php

use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Config;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Seed general setting to avoid 500 error in views if needed
    \App\Models\Setting\GeneralSetting::updateOrCreate(['id' => 1], [
        'name' => 'Test Company',
        'company_name' => 'Test Company',
        'branch_status' => 1,
        'currency' => 'USD',
    ]);
});

test('serveViaAccel returns X-Accel-Redirect header with signed MinIO URL', function () {
    $user = User::factory()->create();

    // Mock/fake the minio disk
    Storage::fake('minio');
    Config::set('filesystems.default', 'minio');

    $path = 'upload/logo/test.png';
    Storage::disk('minio')->put($path, 'dummy logo content');

    $encodedPath = base64_encode($path);

    $response = $this->actingAs($user)
        ->get(route('file.accel', ['encodedPath' => $encodedPath]));

    $response->assertStatus(200);
    $response->assertHeader('X-Accel-Redirect');
    
    $redirectHeader = $response->headers->get('X-Accel-Redirect');
    expect($redirectHeader)->toStartWith('/minio-internal');
});
