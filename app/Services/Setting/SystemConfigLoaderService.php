<?php

namespace App\Services\Setting;

use App\Models\Setting\GoogleMapApi;
use App\Models\Setting\MailSetting;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;

class SystemConfigLoaderService
{
    /**
     * Load Google Maps and SMTP settings from database configuration tables.
     *
     * @return void
     */
    public function loadConfigs(): void
    {
        $this->loadGoogleMapsKey();
        $this->loadMailSettings();
    }

    /**
     * Load Google Maps API key from DB and override service configuration.
     *
     * @return void
     */
    protected function loadGoogleMapsKey(): void
    {
        if (Schema::hasTable('api_keys')) {
            $mapsKey = cache()->rememberForever('google_maps_api_key', function () {
                return GoogleMapApi::first()?->google_maps_api_key;
            });

            if (!empty($mapsKey)) {
                Config::set('services.google.maps_key', $mapsKey);
            }

            $mapsRadius = cache()->rememberForever('google_maps_radius', function () {
                return GoogleMapApi::first()?->google_maps_radius;
            });

            if (!empty($mapsRadius)) {
                Config::set('services.google.maps_radius', $mapsRadius);
            }
        }
    }

    /**
     * Load Mail/SMTP configuration settings from DB.
     *
     * @return void
     */
    protected function loadMailSettings(): void
    {
        if (Schema::hasTable('mail_settings')) {
            $mail = MailSetting::first();
            if ($mail) {
                Config::set([
                    'mail.mailers.smtp.host'       => $mail->mail_host,
                    'mail.mailers.smtp.port'       => $mail->port,
                    'mail.mailers.smtp.encryption' => $mail->encryption_type,
                    'mail.mailers.smtp.username'   => $mail->sender_email,
                    'mail.mailers.smtp.password'   => $mail->password,
                    'mail.from.address'            => $mail->sender_email,
                    'mail.from.name'               => $mail->app_name,
                ]);
            }
        }
    }
}
