# Requirements for Company Location Google Maps Autocomplete Integration and Axios CRUD

## 1. Background
The company location (branch) module currently has manual entry fields for branch/location name, location address, city, state, division, and country. Users need to select the location using Google Location Autocomplete via the Google Maps API (similar to the Travel Movement module) and have the city, state, division, and country auto-populated. Additionally, the CRUD operations for this module should be refactored to be API/Axios-based.

## 2. Functional Requirements
- **Google Location Autocomplete**:
    - Integrate the Google Maps API Autocomplete on the Location Address input field.
    - When a place is selected, dynamically parse the address components and auto-populate:
        - `location_address` (with the formatted address)
        - `city`
        - `state`
        - `division`
        - `country`
- **Axios-Based CRUD**:
    - Convert Company Location Management (Add, Edit, List, Delete) to be fully Axios-based (single-page behavior with modals instead of page redirects).
    - Store, Update, and Delete actions should return JSON responses instead of redirects.
    - Edit action should return the location details as JSON.

## 3. Technical Requirements
- **Google Maps Script**: Load Google Maps Places library (reusing `services.google.maps_key` config).
- **Controller Refactoring**: Update `CompanyLocationController` to return JSON for API requests (store, edit, update, destroy).
- **Blade Templates Update**:
    - Replace the separate create/edit views with modal structures in `index.blade.php`.
    - Handle form submissions, validation messages, and success messages using Axios and JavaScript.
