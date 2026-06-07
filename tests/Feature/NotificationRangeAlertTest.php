<?php

use App\Models\Employee\Employee;
use App\Models\Setting\Notification;
use App\Models\Setting\NotificationSetting;
use App\Models\User;
use App\Enums\UserType;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;

uses(RefreshDatabase::class);

beforeEach(function () {
    NotificationSetting::create([
        'visa_days' => 60,
    ]);
    $this->hr = User::factory()->create(['user_type' => UserType::Group]);
});

it('alerts for expiries within the range (e.g. 40 days left when threshold is 60)', function () {
    $today = Carbon::parse('2026-06-01');
    Carbon::setTestNow($today);

    // Visa expires in 40 days (July 11)
    $expiryDate = $today->copy()->addDays(40)->toDateString();
    
    Employee::factory()->create(['visa_expiry' => $expiryDate]);

    Artisan::call('app:check-alerts');

    $count = Notification::where('title', 'Visa Expiry Alert')->count();
    expect($count)->toBe(1);
});

it('does not send duplicate notifications for the same expiry cycle', function () {
    $today = Carbon::parse('2026-06-01');
    Carbon::setTestNow($today);

    $expiryDate = $today->copy()->addDays(10)->toDateString();
    Employee::factory()->create(['visa_expiry' => $expiryDate]);

    // Run first time
    Artisan::call('app:check-alerts');
    expect(Notification::count())->toBe(1);

    // Run second time (simulating next day or same day rerun)
    Artisan::call('app:check-alerts');
    expect(Notification::count())->toBe(1); // Should still be 1, not 2
});
