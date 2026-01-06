<?php

namespace App\Providers;

use App\Models\ApiKey;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

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
        Paginator::useBootstrap();

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
    }
}
