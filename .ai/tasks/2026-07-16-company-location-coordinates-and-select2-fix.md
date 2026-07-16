# Task: Add Coordinates (Lat/Lng) to Company Locations and Fix Select2 Dropdown

## Problem
1. Company locations do not currently store latitude and longitude.
2. Select2 dropdowns within Bootstrap modals do not update correctly via raw JS value assignment and need to be bound with `dropdownParent` to allow correct search focus.

## Proposed Solution
1. Create a migration to add `latitude` and `longitude` fields to the `company_locations` table.
2. Update `CompanyLocation` model and controller validation to allow `latitude` and `longitude`.
3. In `company_locations.index` form, add hidden fields for `latitude` and `longitude`.
4. Capture coords from Google Autocomplete place selection.
5. Fix Select2 initialization and update events using jQuery in `index.blade.php`.

## Execution Steps
1. **Migration & Model**:
   - Run `php artisan make:migration add_latitude_longitude_to_company_locations_table --table=company_locations`.
   - Implement the migration adding `latitude` and `longitude` nullable decimal/string fields.
   - Run the migration.
   - Add columns to `CompanyLocation` fillable array.
2. **Controller Validation**:
   - Update `CompanyLocationController` store and update validation rules to include `latitude` and `longitude`.
3. **Blade UI & Select2 Fix**:
   - Add hidden inputs for `latitude` and `longitude` in the modal.
   - Bind Google Maps Autocomplete to populate these fields.
   - Use jQuery to initialize Select2 on `#modal_company_id` inside the modal with `dropdownParent: $('#companyLocationModal')`.
   - Update edit and create click handlers to update Select2 value using `trigger('change')`.
4. **Optimization & Verification**:
   - Run optimize, tests, and commit changes.
