# Task: Implement Route Map Module for Employee Transport

## Problem
Currently, route details are manually typed as plain text when submitting employee transport requests, leading to inconsistent and non-reusable route definitions.

## Proposed Solution
Create a `RouteMap` module, link it to `EmployeeTransport`, and replace the manual text fields with a route selection dropdown.

## Execution Steps
1. **Migrations**:
   - Create `route_maps` table migration with `route_name`, `start_point`, `end_point`, `via_points`, `route_details`, `status`, and `company_id`.
   - Create migration to add `route_map_id` to `employee_transports` table.
   - Run migrations.
2. **Model**:
   - Create `App\Models\Transport\RouteMap.php` with `OrganizationScoped`, `Auditable`, and `Userstamps` traits.
   - Add `routeMap()` belongsTo relationship to `EmployeeTransport.php`.
3. **Controller**:
   - Create `App\Http\Controllers\Transport\RouteMapController.php` with resource actions.
   - Update `EmployeeTransportController.php` to fetch active route maps, validate `route_map_id` on store/update, and load the `routeMap` relation on show.
4. **Views**:
   - Create `resources/views/transport/route_map/index.blade.php`, `form.blade.php`, and `search_results.blade.php`.
   - Update `resources/views/transport/employee_transport/form.blade.php` to show `route_map_id` select instead of manual text inputs.
   - Update `resources/views/transport/employee_transport/show.blade.php` to show the details of the selected Route Map.
5. **Routes & Sidebar**:
   - Register route map resource routes in `routes/web.php`.
   - Add Route Maps link to `sidebar.blade.php`.
6. **Verification**:
   - Create and run Pest test to verify Route Map CRUD and association with Employee Transport.
