# Requirements for Google Map Radius Setting

## 1. Background
To allow geo-fencing or validation of branch coverage, the Google Map settings must support a "Covering Area" or "Radius" configuration field. This value should be saved to the database alongside the API key and loaded into the application configuration during initialization.

## 2. Functional Requirements
- **Radius Configuration Field**:
    - Add a "Covering Area (Radius in Meters)" number input to the Google Map API setting page.
    - Save this value (`google_maps_radius`) in the database.
- **Config Initialization**:
    - Load the radius from the database and set it as `services.google.maps_radius` configuration in `SystemConfigLoaderService` (called during application boot).
- **Cache Eviction**:
    - Evict cache key `google_maps_radius` when updating settings.

## 3. Technical Requirements
- **Database Migration**: Add nullable integer column `google_maps_radius` to the `api_keys` table.
- **Model Update**: Add `google_maps_radius` to `$fillable` in `GoogleMapApi`.
- **Validation**: Validate that the input is a positive integer.
