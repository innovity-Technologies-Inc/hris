<?php

namespace App\Providers;

use App\Models\ApiKey;
use App\Models\MailSetting;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (env('APP_FORCE_HTTPS', false)) {
            URL::forceScheme('https');
        }
        
        Paginator::useBootstrap();

        //Google Api Key Configuration
        // Avoid error during migrate
        if (!Schema::hasTable('api_keys')) {
            return;
        }

        // Cache for performance
        $mapsKey = cache()->rememberForever('google_maps_api_key', function () {
            return ApiKey::first()?->google_maps_api_key;
        });

        // Override config if DB value exists
        if (!empty($mapsKey)) {
            config()->set('services.google.maps_key', $mapsKey);
        }

        // 1. Prevent errors during migrations or if table doesn't exist yet
        if (Schema::hasTable('mail_settings')) {

            $mail = MailSetting::first();
            if ($mail) {
                // 2. Map Database columns to Laravel Config keys
                $data = [
                    'mail.mailers.smtp.host'       => $mail->mail_host,
                    'mail.mailers.smtp.port'       => $mail->port,
                    'mail.mailers.smtp.encryption' => $mail->encryption_type,
                    'mail.mailers.smtp.username'   => $mail->sender_email,
                    'mail.mailers.smtp.password'   => $mail->password,
                    'mail.from.address'            => $mail->sender_email,
                    'mail.from.name'               => $mail->app_name,
                ];

                // 3. Apply the changes globally for this request
                Config::set($data);
            }
        }
    }
}
