# Requirements for Company Location Latitude/Longitude & Select2 Fix

## 1. Background
The company location module needs to store the precise coordinates (latitude and longitude) of the branch. Additionally, the company selection dropdown (using Select2) in the modal is not working or not updating properly when adding/editing locations.

## 2. Functional Requirements
- **Latitude & Longitude Storage**:
    - Add `latitude` and `longitude` fields to the `company_locations` table and model.
    - Autocomplete selection must capture `place.geometry.location.lat()` and `place.geometry.location.lng()` and store them.
- **Select2 Dropdown Fix**:
    - Ensure Select2 dropdown within the modal functions correctly (attaching the `dropdownParent` config to the modal element if necessary).
    - When editing a location, trigger the Select2 update via jQuery `trigger('change')` so the visible select box displays the correct company.
    - When creating a new location, reset the Select2 dropdown properly.

## 3. Technical Requirements
- **Database Migration**: Create a migration to add nullable `latitude` and `longitude` columns to `company_locations`.
- **Model Update**: Add `latitude` and `longitude` to `$fillable` array in `CompanyLocation`.
- **Javascript Fixes**:
    - Bind Select2 with `dropdownParent` targeting the modal container.
    - Use jQuery to update select2 values upon modal open/edit details retrieval.
