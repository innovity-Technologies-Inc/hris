# Requirements for Google Map Api Setting Module Conversion

## 1. Background
The current setting module uses the terminology "API Keys" (model `ApiKey`, controller `ApiKeyController`, route `api-keys`, view `api_keys.blade.php`). To be more specific and clear, this should be renamed to "Google Map API" (model `GoogleMapApi`, controller `GoogleMapApiController`, route `google-map-api`, view `google_map_api.blade.php`). In addition, the CRUD operations for saving the key should be converted to be fully API/Axios-based, and configuration loaders must be updated.

## 2. Functional Requirements
- **Renaming and Refactoring**:
    - Rename `ApiKey` model to `GoogleMapApi`.
    - Rename `ApiKeyController` to `GoogleMapApiController`.
    - Update `SystemConfigLoaderService` to use `GoogleMapApi` model.
    - Update route names and paths to refer to `google-map-api` instead of `api-keys`.
- **Axios-Based settings save**:
    - Convert settings form in `google_map_api.blade.php` to execute save actions asynchronously via Axios.
    - Return JSON responses upon successful validation and key update.
    - Refresh the cache value after saving.

## 3. Technical Requirements
- **Database Mapping**: `GoogleMapApi` model must map to the existing `api_keys` table.
- **Cache Eviction**: Clear cache key `google_maps_api_key` when updating/saving the key to ensure the new key is loaded.
