# Task: Rename ApiKey Module to GoogleMapApi and make it Axios-based

## Problem
The settings module uses generic "API Keys" terminology. We want to align this specifically to "Google Map API" in models, controllers, routes, views, and service configurations. We also need to refactor the save method to be Axios-based.

## Proposed Solution
1. Rename model `ApiKey.php` to `GoogleMapApi.php`.
2. Rename controller `ApiKeyController.php` to `GoogleMapApiController.php`.
3. Update route definitions to use `google-map-api` endpoints.
4. Rename `api_keys.blade.php` view to `google_map_api.blade.php` and make the form submission Axios-based.
5. Update `SystemConfigLoaderService` to use the `GoogleMapApi` model.

## Execution Steps
1. **Model Update**:
   - Create `app/Models/Setting/GoogleMapApi.php` (remapping to `api_keys` table and retaining encryption).
   - Delete `app/Models/Setting/ApiKey.php`.
2. **Controller Refactoring**:
   - Create `app/Http/Controllers/Setting/GoogleMapApiController.php` exposing index (Axios page view) and save (Axios POST/PUT JSON response).
   - Delete `app/Http/Controllers/Setting/ApiKeyController.php`.
3. **Route Update**:
   - Update `routes/web.php` replacing `api-keys` endpoints with `google-map-api` endpoints.
4. **Service & Provider Update**:
   - Update `SystemConfigLoaderService.php` to import and reference `GoogleMapApi` instead of `ApiKey`.
5. **View Refactoring**:
   - Rename `resources/views/setting/api_keys.blade.php` to `resources/views/setting/google_map_api.blade.php`.
   - Update the form to use Axios for save action, displaying toastr response feedback.
