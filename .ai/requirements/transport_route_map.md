# Requirements for Transport Route Map Module

## 1. Background
Rather than manually entering pickup locations, drop locations, and route details for each Employee Transport Service request, the system should allow administrators to define reusable "Route Maps." Users creating transport services can then select from these predefined routes.

## 2. Functional Requirements
### A. Route Map Management
- **Route Map Entity**:
  - `route_name`: String (Required)
  - `start_point`: String (Required)
  - `end_point`: String (Required)
  - `via_points`: Text (Optional, comma-separated or descriptive list of stop points)
  - `route_details`: Text (Optional description)
  - `status`: Enum/String (`Active`, `Inactive`, default `Active`)
  - `company_id`: Unsigned Big Integer (For organizational scoping support)
- **CRUD Operations**:
  - Standard resource views (`index`, `create`, `edit`, `store`, `update`, `destroy`) for Route Maps.
  - FlexSearch filter support on keyword matching (`route_name`, `start_point`, `end_point`).
  - Active Route Maps only can be associated with new transport requests.

### B. Employee Transport Service Form Integration
- **Remove Manual Inputs**:
  - Remove manual text fields (`pickup_location`, `drop_location`, `route_details`) from the Employee Transport create/edit forms.
- **Route Map Dropdown**:
  - Add a `route_map_id` select dropdown to the Employee Transport form containing all active `RouteMap` records.
- **Store & View Integration**:
  - Update `employee_transports` table migration to support `route_map_id`.
  - Update `EmployeeTransportController` to validate and store `route_map_id` instead of manual locations.
  - Eager load and display `routeMap` relationship details (Name, Start Point, End Point, Via Points, Details) on the transport service show/details view.
