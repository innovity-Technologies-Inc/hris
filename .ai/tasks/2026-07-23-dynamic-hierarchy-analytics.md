# Dynamic Hierarchy Analytics Implementation Plan

## Background & Motivation
The current analytics dashboard hardcodes the display of Division and Department breakdowns. However, the HRMS system allows administrators to toggle different organizational hierarchy levels (Business Unit, Division, Department, Section) on or off via the General Settings. The analytics dashboard must dynamically reflect these settings, displaying charts only for the active hierarchy levels while strictly adhering to organizational scoping.

## Scope & Impact
- **Backend**: `EmployeeReportServices` and `EmployeeReportController` will be updated to conditionally fetch data based on `GeneralSetting` flags.
- **Frontend**: The `reports.blade.php` view and its embedded JavaScript will be refactored to iterate over the provided dynamic data and render charts accordingly.
- **Impact**: Ensures the analytics page accurately represents the customized organizational structure of the client without showing empty or irrelevant charts.

## Proposed Solution
1.  **Backend Refactoring**:
    -   Modify `EmployeeReportController@index` to retrieve `GeneralSetting` using `HelperClass::getGeneralSetting()`.
    -   Create a dynamic array of requested hierarchies based on the settings flags (`branch_status`, `division_status`, `department_status`, `section_status`).
    -   Update `EmployeeReportServices` to return a structured array containing the data, labels, and metadata (like title and chart type) for each active hierarchy level.

2.  **Frontend Refactoring**:
    -   Remove the hardcoded Division and Department grid sections in `reports.blade.php`.
    -   Introduce a `@foreach` loop that iterates over the dynamic hierarchy data array passed from the controller.
    -   Generate a Bootstrap card and canvas element for each item.
    -   Update the JavaScript to loop through the same data (passed via JSON) and initialize Chart.js instances dynamically.

## Implementation Steps

### Phase 1: Backend Updates
- [ ] Update `App\Services\Employee\EmployeeReportServices` to consolidate hierarchy data fetching into a single method that accepts an array of active levels.
- [ ] Update `App\Http\Controllers\Employee\EmployeeReportController` to read settings and pass a `dynamicHierarchies` array to the view.

### Phase 2: Frontend Updates
- [ ] Refactor `resources/views/employee/reports.blade.php` to use a loop for rendering the hierarchy cards.
- [ ] Update the Chart.js initialization script in the view to dynamically build charts based on the `dynamicHierarchies` data.

### Phase 3: Testing & Validation
- [ ] Update `tests/Feature/Employee/EmployeeReportTest.php` to mock `GeneralSetting` and verify the dynamic chart generation logic.
- [ ] Manually verify UI aesthetics and organizational scoping.

## Verification & Testing
- Ensure that turning a setting (e.g., `section_status`) off in General Settings immediately removes the corresponding chart from the Analytics page.
- Ensure that organizational scoping (`OrganizationScoped` trait) is still applied to the dynamic queries.
