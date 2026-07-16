# Task: Add Covering Radius (Area) to Google Maps settings

## Problem
Currently, the Google Maps setting only stores the API key. We need to support a "Covering Area (Radius in Meters)" setting as well.

## Proposed Solution
1. Create a migration to add `google_maps_radius` to the `api_keys` table.
2. Update the `GoogleMapApi` model, controller, and settings view to validate and save the radius.
3. Update `SystemConfigLoaderService` to cache and load `services.google.maps_radius`.

## Execution Steps
1. **Migration & Model**:
   - Run `php artisan make:migration add_google_maps_radius_to_api_keys_table --table=api_keys`.
   - Update migration file with `integer('google_maps_radius')->nullable()`.
   - Run `php artisan migrate`.
   - Add `google_maps_radius` to `$fillable` in `GoogleMapApi` model.
2. **Settings view & Controller**:
   - Update `google_map_api.blade.php` to include the covering radius number input.
   - Update `GoogleMapApiController` to validate and update the radius.
3. **Config Loader**:
   - Update `SystemConfigLoaderService` to cache and override `services.google.maps_radius` config.
4. **Verification**:
   - Update `GoogleMapApiTest.php` to test validation and persistence of radius.
   - Verify tests and optimize.
