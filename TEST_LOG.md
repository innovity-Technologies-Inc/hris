# Test Log

## 2026-07-22 (Employee Travel Movement Export and Print Index)

**Goal**: Implement unpaginated, filtered Excel export and PDF printing for the Employee Travel Movement module.

**Exact Command**: `php artisan config:clear && vendor\bin\pest tests/Feature/EmployeeMovementExportTest.php`

**Results**:
- Registered `movement.export.excel` and `movement.print` routes inside the `movement` controller group in `web.php`.
- Created `MovementExport.php` under `app/Exports/Movement` mapped with appropriate headers (System ID, employee name, dates, distance, allowances, status, payment status, etc.).
- Developed print layout template `print_index.blade.php` in `resources/views/movement` for travel movement records.
- Added unpaginated Excel and Print action buttons to the index page next to the Create button.
- Cleaned up duplicate script blocks and structured jQuery event listeners using `@push('scripts')` to avoid `$ is not defined` ReferenceErrors.
- Integrated debounced input search triggers and handled filter serialized parameters for both exporting and printing.
- Created `EmployeeMovementExportTest.php` to verify AJAX filters, Excel export file headers, and print content correctness.
- Tests passed: 3/3 passed (8 assertions) ✅

**Status**: ✅ SUCCESS

## 2026-07-22 (Employee Transport Reject and Approve Buttons Modification)

**Goal**: Remove the Reject button from the Employee Transport list view, both Approve and Reject buttons from the details show view, resolve a Typehint Return Value error in `TransportService.php`, add database seeder for all transport tables, implement unpaginated and filtered Excel export and PDF printing for vehicle requisitions, employee transports, and vehicle allocations, convert the vehicle allocation history index to live search, and fix the vehicle dropdown labels.

**Exact Command**: `php artisan config:clear && vendor\bin\pest tests/Feature/TransportRouteTest.php`

**Results**:
- Removed the "Reject" action button from `resources/views/transport/employee_transport/search_results.blade.php` list grid.
- Removed both the "Approve" and "Reject" buttons and forms from `resources/views/transport/employee_transport/show.blade.php` details view.
- Cleaned up the script tag push event listener for `#rejectBtn` in `show.blade.php`.
- Fixed `TypeError` in `TransportService::getEligibleDrivers()` at line 484 by returning an empty Eloquent `Collection` (`new Collection()`) instead of the default Laravel support collection `collect()` helper.
- Created `TransportSeeder.php` database seeder populating active vehicles, active drivers with driver designations, route maps, pending/active requisitions, and allocations.
- Registered Excel export and PDF print routes for vehicle-requisitions, employee-transports, and vehicle-allocations inside the `transport.` route prefix group in `web.php`.
- Created `VehicleRequisitionExport.php`, `EmployeeTransportExport.php`, and `VehicleAllocationExport.php` excel mapper classes under `app/Exports/Transport`.
- Developed print layout templates `print_index.blade.php` for requisitions, employee transports, and allocations.
- Integrated Excel and Print action buttons and click listeners inside the index blades for the three transport sections.
- Refactored `resources/views/transport/vehicle_allocation/history.blade.php` to include keyword search input and removed the filter icon submit button.
- Extracted vehicle allocation table results into a partial blade view `history_results.blade.php` to enable asynchronous updates.
- Fixed vehicle dropdown options in `history.blade.php` to output actual database attributes (`license_number`, `vehicle_category`, and `model_number`) instead of `reg_no` which was null.
- Added debounced delegated change and input event listeners on form inputs, selects, and Select2 instances (`change.select2`) to run live searching asynchronously.
- Wrote Pest feature test verifying AJAX keyword search behavior in `TransportRouteTest.php`.
- Verified that all Vehicle, Driver, Allocation, Requisition, and Transport route feature tests pass successfully.
- Tests passed: 13/13 passed (68 assertions) ✅

**Status**: ✅ SUCCESS

## 2026-07-22 (Leave Logs Search Organizational Filters)

**Goal**: Align Leave Logs search UI design to match Attendance index form layouts (exactly 3 rows: 1 core row, 2 organization filter rows) with standard element heights, add company, branch, division, department, and section cascading filters, and integrate unpaginated print/excel exporting matching current filter parameters.

**Exact Command**: `php artisan config:clear && vendor\bin\pest tests/Feature/Leave/LeaveLogsTest.php`

**Results**:
- Added Company, Branch, Division, Department, and Section filters inside `leave/index.blade.php`.
- Organized the search form fields into exactly three rows (`col-md-4` each) and styled the Reset Filters button with label spacing to match adjacent dropdown heights and alignments.
- Standardized leave log card layouts using clean `card border-0 shadow-sm rounded` styling and matching headers without excessive shadow/padding, replicating the Attendance search aesthetics.
- Relocated Create and Bulk Upload action buttons into the main card body, applying warning (`btn-warning`) and outline-success classes.
- Standardized leave list results table using `table table-hover table-borderless align-middle mb-0` class with `id="leaveTable"` and row hovering custom classes.
- Generated `leaves.xlsx` Excel template containing sample data rows matching bulk validation requirements, saving it to `public/assets/excel/leaves.xlsx`.
- Corrected the Excel and CSV download paths inside `leave/partials/import_modal.blade.php` to point to valid public files (`public/assets/excel/leaves.xlsx` and `public/assets/csv/leaves.csv`).
- Registered `leave.export.excel` and `leave.print` routes in `routes/web.php` mapped to `LeavesController`.
- Created `LeaveExport.php` excel generation concern mapping System ID, Employee ID, Employee Name, Plan Name, Days, Dates, Reason, and Status attributes.
- Implemented `print_index.blade.php` A4 layout print sheet styled similarly to attendance print index with a list summary.
- Implemented `getLeavesAll` inside `LeaveServices.php` to query the entire matching unpaginated search result.
- Added Export Excel and Print PDF buttons and listeners inside `leave/index.blade.php` to trigger files download or new print window.
- Implemented sequential promise-based AJAX cascading hierarchy loaders inside the JavaScript block, using state tracking (`silenceChangeEvents`) to avoid redundant search queries.
- Configured `LeavesController.php` index action to load companies and requested filter units.
- Updated `LeaveServices::getLeavesPaginated` to query `getEmployee.officeInfo` for company, branch, division, department, and section filters.
- Created `LeaveLogsTest.php` Pest feature test verifying leave logs index accessibility, organizational query constraint application, excel export, and print operations.
- Tests passed: 4/4 passed (11 assertions) ✅

**Status**: ✅ SUCCESS

## 2026-07-22 (Attendance Records Search Organizational Filters)

**Goal**: Add organization fields (Company, Branch, Division, Department, Section) to the attendance records search and filter functionality, implementing dynamic cascading AJAX autoloading and retaining pre-selected options to support Pest/headless assertions, formatted in exactly three rows (1 row of core filters, 2 rows of organizational filters) with standard uniform element heights (38px).

**Exact Command**: `php artisan config:clear && vendor\bin\pest tests/Feature/Attendance/AttendanceExportPrintTest.php`

**Results**:
- Added `applyFilters` helper method in `AttendancesController.php` to apply `company`, `business_unit` (branch), `division`, `department`, and `section` query filters.
- Retrieved and passed `$companies` list and selected entities (`$selectedBranch`, `$selectedDivision`, `$selectedDepartment`, `$selectedSection`) from `AttendancesController@index` to pre-select options in the Blade view.
- Added company, branch, division, department, and section dropdowns to the filter form in `attendance/index.blade.php`.
- Divided organizational filters into two separate rows of 3 columns (`col-md-4` each) to align perfectly with the core filters row, and integrated the Reset Filters button as a dynamic bottom-aligned flex element within the final row.
- Standardized all input, select, and button element heights to the default Bootstrap 38px size (removing `-sm` size suffixes) to align perfectly with the global custom Select2 styling height in the master layout.
- Placed an empty label space (`&nbsp;`) above the Reset Filters button within its `col-md-4` container to align its vertical height and position perfectly with the adjacent select filters in the row.
- Implemented AJAX hierarchy cascading loading (`/get-units`, `/get-divisions`, `/get-departments`, `/get-sections`) in the Blade scripts utilizing unified promise-based loaders.
- Added state-controlled change triggers (`silenceChangeEvents`) to prevent redundant searches while cascading option updates.
- Added `it can filter attendance records by organization` feature test verifying successful company-scoped record retrieval.
- Tests passed: 4/4 passed (11 assertions) ✅

**Status**: ✅ SUCCESS

## 2026-07-22 (Attendance Records Export & Print Alignment)

**Goal**: Redesign the Attendance index page to match the unified glassmorphism styling, and fix the Print and Export Excel functionality to respect search and date filters.

**Exact Command**: `php artisan route:clear && php artisan config:clear && vendor\bin\pest tests/Feature/Attendance/AttendanceExportPrintTest.php --no-coverage`

**Results**:
- Redesigned the search filter card and result list cards in `attendance/index.blade.php` to match the glassmorphism and warnings-style buttons.
- Created `AttendanceExport.php` for generating Excel sheets of employee attendance records.
- Registered `/attendance/export/excel` route in `routes/web.php`.
- Implemented `exportExcel` and updated `printIndex` in `AttendancesController.php` to parse keyword searches, date range filters, and retrieve filtered records.
- Added `AttendanceExportPrintTest.php` Pest test verifying loading, filtered printing, and Excel downloading of attendance records.
- Test passed 3/3 tests (7 assertions) ✅

**Status**: ✅ SUCCESS

## 2026-07-22 (Employee Bulk Imports Alignment & Excel Templates Verification)

**Goal**: Check and align all 7 bulk upload imports for employee information with recent table updates, update matching Excel demo templates, configure storage helper references across Blade files, and ensure complete data fields mapping for imports.

**Exact Command**: `php artisan config:clear && vendor\bin\pest --no-coverage`

**Results**:
- Aligned name fields mapping, NID (index 57), and date of birth in `EmployeeGeneralInformationImport.php` to match columns order in general info template.
- Implemented `salary_type` mapping at column index 33 in `EmployeeOfficeInformationImport.php` and added `salary_type` to fillable columns in `EmployeeOfficeInfo` model.
- Restructured `EmployeeNomineeImport.php` to map the new `relation` (index 2), `bank_account_no` (index 15), and `ratio` (index 16) columns, shifting remaining details accordingly.
- Integrated `pay_scale_id` lookups by pay scale title at index 1 and adjusted shifted salary columns in `SalaryBreakdownImport.php`.
- Replaced direct asset storage path helpers with `\App\HelperClass::get_file_url(...)` across 41 Blade templates to support flexible filesystems (MinIO/S3).
- Added `EmployeeBulkImportTest.php` Pest test file verifying bulk import data saving logic and NID/Relation/PayScale lookups.
- Corrected route names and type parameter in `ResignationTest.php` and updated expected JSON validation and store response status codes (201 Created) in `AnnouncementTest.php`, `ClaimExpenseTest.php` and `ApprovalWorkflowTest.php` tests.
- Re-ran the entire test suite successfully (195 tests passed, 0 failures).

**Status**: ✅ SUCCESS

## 2026-07-21 (Offboarding Index Page Search & Organization Filters)

**Goal**: Implement advanced search and organization filters (Company, Branch, Division, Department, Section) with cascading AJAX autoload matching general settings visibility, employee name, employee ID, system ID, and date range query parameters on offboarding index views. Also pre-load/pre-select the organizational hierarchy dropdowns from section ID query parameters when creating a new offboarding request, and make all hierarchy selectors cascading and dynamic on the create form just like the establishment filters in career movement.

**Exact Command**: `php artisan config:clear && vendor\bin\pest tests/Feature/OffboardingTest.php --no-coverage`

**Results**:
- Added filter query logic in `OffboardingServices::getOffboardingsPaginated` to filter records by `employee_name` (full_name), `employee_id` (applicant_id), `system_id`, date range (`from`/`to`), status, and cascading organization hierarchies.
- Updated index controller methods in `OffboardingController.php` to fetch and pass `$companies` and `$generalSettings` to the view.
- Re-architected search container layout in `offboarding/index.blade.php` to fit employee name, ID, system ID, date range, status, and company/branch/division/department/section selectors.
- Added Select2 styling, cascading AJAX options autoloading listeners, and query parameter auto-population.
- Refactored index and form layouts to follow the standard system styling (single card layout, warning-colored Create buttons with feather icons, solid action buttons using data-feather, simple unified form cards).
- Implemented automatic resolution of section hierarchy parameters (`section_id` or `id`) in `OffboardingController@create` and pre-selected the matching Company, Branch, Division, Department, and Section dropdowns in the UI.
- Implemented dynamic cascading AJAX filters for the Company, Branch, Division, Department, and Section dropdown selectors on the Create/Edit form, fetching and rendering option values sequentially and refreshing the eligible employees list.
- Added `offboarding search filters filter records correctly` and `offboarding create view preloads hierarchy from section id query parameter` feature tests in `OffboardingTest.php`.
- Feature tests passed 4/4 tests (41 assertions) ✅

**Status**: ✅ SUCCESS

## 2026-07-21 (Offboarding Module - Bug Fixes: Employee Status ENUM & Sidebar Menu)

**Goal**: Fix two post-implementation issues: (1) `employees.status` ENUM column missing `'resigned'` and `'terminated'` values causing 500 errors on offboarding creation and test inserts. (2) Commit missing offboarding sidebar menu block and updated PermissionSeeder slugs (`resignations`, `terminations`) with `user-x` icon.

**Exact Command**: `php artisan config:clear && vendor\bin\pest tests/Feature/OffboardingTest.php --no-coverage`

**Results**:
- Created migration `2026_07_21_005648_add_resigned_terminated_status_to_employees_table.php` expanding `employees.status` ENUM to include `'resigned'` and `'terminated'`.
- Ran migration on both production (`hrms`) and test (`hrms_test`) databases.
- Added Offboarding sidebar menu block to `resources/views/structure/partials/sidebar.blade.php`.
- Updated `PermissionSeeder.php`: slugs to `resignations`/`terminations`, icon to `user-x`.
- Fixed test to use `route('dashboard.index')` instead of hardcoded `/dashboard` (which returns 404).
- Feature tests `tests/Feature/OffboardingTest.php` passed 2/2 tests (17 assertions) ✅

**Status**: ✅ SUCCESS

## 2026-07-20 (Offboarding Module - Resignation, Termination & Portal Restriction)

**Goal**: Restructure offboarding into a unified module with Resignation and Termination submenus, locked offboarding type select in shared form, automatic employee status updates (`'resigned'`, `'terminated'`), and EnsureNotOffboarded portal access middleware restriction.

**Exact Command**: `php artisan config:clear && vendor\bin\pest tests/Feature/OffboardingTest.php`

**Results**:
- Registered `'offboarding-resignation'` and `'offboarding-termination'` in `config/approval-engine.php`.
- Seeded parent menu **Offboarding** with **Resignation** and **Termination** submenus in `PermissionSeeder.php`.
- Created migration `2026_07_20_000008_create_offboardings_table.php` and model `App\Models\Offboarding\Offboarding.php`.
- Implemented `StoreOffboardingRequest`, `UpdateOffboardingRequest`, `OffboardingServices`, and `OffboardingController`.
- Created shared views (`index`, `form`, `show`, `search_results`) and portal restriction view `my_offboarding.blade.php`.
- Implemented `EnsureNotOffboarded` middleware restricting resigned/terminated employees to `/my-offboarding`.
- Feature tests `tests/Feature/OffboardingTest.php` passed 2/2 tests (11 assertions) ✅

**Status**: ✅ SUCCESS

## 2026-07-20 (Resign Module & Approval Workflow Implementation)

**Goal**: Implement the new Resign module with 5-tier organizational hierarchy cascade, approval workflow integration, thin request-service-controller pattern, generic ApiResponse trait, and permission seeder.

**Exact Command**: `php artisan config:clear && vendor\bin\pest tests/Feature/ResignationTest.php`

**Results**:
- Registered `'resign' => 'Resignation'` in `config/approval-engine.php`.
- Seeded `Resignations` menu and permissions (`resignations.view`, `create`, `edit`, `delete`, `approve`) via `PermissionSeeder.php`.
- Created database migration `2026_07_20_000007_create_resignations_table.php` and model `App\Models\Resignation\Resignation.php` with `OrganizationScoped` and `Approvable` traits.
- Implemented `StoreResignationRequest`, `UpdateResignationRequest`, `ResignationServices`, and `ResignationController` returning `ApiResponse` trait responses.
- Implemented Blade views (`index`, `create`, `edit`, `show`, `search_results`) with 5-tier cascading dropdowns, date auto-calculations, Axios form submissions, and SweetAlert2 confirm delete.
- Feature test `tests/Feature/ResignationTest.php` passed 1/1 tests (7 assertions) ✅

**Status**: ✅ SUCCESS

## 2026-07-20 (Leave Module Architecture Refactoring & Business Logic Parity Verification)

**Goal**: Refactor `LeavesController` into a thin controller adhering strictly to the Request -> Service -> Controller architecture pattern while maintaining 100% feature and business logic parity.

**Exact Command**: `php artisan config:clear && vendor\bin\pest tests/Feature/LeavePlanTest.php`

**Results**:
- Created [StoreLeaveRequest.php](file:///P:/Project/Web/hrms/app/Http/Requests/Leave/StoreLeaveRequest.php), [CalculateEndDateRequest.php](file:///P:/Project/Web/hrms/app/Http/Requests/Leave/CalculateEndDateRequest.php), and [ImportLeavesRequest.php](file:///P:/Project/Web/hrms/app/Http/Requests/Leave/ImportLeavesRequest.php).
- Created [LeaveServices.php](file:///P:/Project/Web/hrms/app/Services/Leave/LeaveServices.php) containing all leave management business logic, transactions, flexsearch pagination, end date calculation, and count adjustments.
- Refactored [LeavesController.php](file:///P:/Project/Web/hrms/app/Http/Controllers/Leave/LeavesController.php) into a thin controller delegating logic to `LeaveServices`.
- Verified line-by-line parity across all 8 controller methods.
- Executed Pest tests and verified all tests pass successfully ✅

**Status**: ✅ SUCCESS

## 2026-07-17 (Employee Profile Leave Info Running Year Calculation)

**Goal**: Scope the leave taken and remaining balance calculations shown on the employee profile leave info page, validated during applications, and requested via APIs to the running calendar year.

**Exact Command**: `php artisan config:clear && vendor\bin\pest tests/Feature/LeavePlanTest.php`

**Results**:
- Updated `showLeaveInfo()` in [LeavesController.php](file:///P:/Project/Web/hrms/app/Http/Controllers/Leave/LeavesController.php) to calculate the integer sum of approved leaves taken by the employee in the current calendar year.
- Updated validation rules in the `store()` method of [LeavesController.php](file:///P:/Project/Web/hrms/app/Http/Controllers/Leave/LeavesController.php) to validate leave requests against the current calendar year's limit.
- Updated `getLeaveDetails()` in [DataController.php](file:///P:/Project/Web/hrms/app/Http/Controllers/DataController.php) to dynamically retrieve current year leaves via AJAX.
- Updated `LeavesImport.php` check to calculate balance limits using the year of the leave request's start date (`from` date).
- Updated [leave_info.blade.php](file:///P:/Project/Web/hrms/resources/views/employee/partials/profile_view/leave_info.blade.php) view to display `$leave->taken_current_year`.
- Added feature test case `employee leave info calculates and validates taken leaves by running year` inside [LeavePlanTest.php](file:///P:/Project/Web/hrms/tests/Feature/LeavePlanTest.php) verifying correct calculation scopes and limit validations.
- Verified that all 6 tests and 35 assertions pass successfully ✅

**Status**: ✅ SUCCESS

## 2026-07-17 (Employee Leave Plans Gender-Based Filtering)

**Goal**: Filter the available leave plans list displayed and assigned within the employee's profile plans section. If the target employee has a gender specified (e.g. `'Male'` or `'Female'`), show only leave plans having `applicable_gender` matching `'Both'` or matching that specific employee's gender.

**Exact Command**: `php artisan config:clear && vendor\bin\pest tests/Feature/LeavePlanTest.php`

**Results**:
- Modified [EmployeePlansController.php](file:///P:/Project/Web/hrms/app/Http/Controllers/Employee/EmployeePlansController.php) inside the `plansView()` action to filter `LeavePlan` queries based on `$employee->gender`.
- Added a new feature test case `employee plans view filters leave plans based on employee gender` inside [LeavePlanTest.php](file:///P:/Project/Web/hrms/tests/Feature/LeavePlanTest.php) verifying that gender scopes filter plans correctly.
- Verified that all 5 tests and 28 assertions pass successfully ✅

**Status**: ✅ SUCCESS

## 2026-07-17 (Leave Plan off_day_include Enum yes/no Database Conversion)

**Goal**: Convert the `off_day_include` column in `leave_plans` table from an integer to an enum of `'yes'` and `'no'`, update form validation rules and options, render values cleanly on view pages, and adjust test assertions to match.

**Exact Command**: `php artisan config:clear && vendor\bin\pest tests/Feature/LeavePlanTest.php`

**Results**:
- Created a migration [2026_07_17_062737_change_off_day_include_to_enum_in_leave_plans_table.php](file:///P:/Project/Web/hrms/database/migrations/2026_07_17_062737_change_off_day_include_to_enum_in_leave_plans_table.php) converting structural types and mapping data values (`0` to `'no'`, `1` to `'yes'`).
- Changed validation rules inside [StoreLeavePlanRequest.php](file:///P:/Project/Web/hrms/app/Http/Requests/Plan/StoreLeavePlanRequest.php), [UpdateLeavePlanRequest.php](file:///P:/Project/Web/hrms/app/Http/Requests/Plan/UpdateLeavePlanRequest.php), and [PlanService.php](file:///P:/Project/Web/hrms/app/Services/Plan/PlanService.php) to require `in:yes,no`.
- Updated Excel import mapping inside [LeavePlansImport.php](file:///P:/Project/Web/hrms/app/Imports/Plan/LeavePlansImport.php) to convert input data to `'yes'`/`'no'`.
- Configured selection options in [form.blade.php](file:///P:/Project/Web/hrms/resources/views/plan/leave_plans/form.blade.php) and display representations in [view.blade.php](file:///P:/Project/Web/hrms/resources/views/plan/leave_plans/view.blade.php), [leave_plan.blade.php](file:///P:/Project/Web/hrms/resources/views/employee/partials/profile_view/partials/leave_plan.blade.php), and [individual.blade.php](file:///P:/Project/Web/hrms/resources/views/leave/individual.blade.php).
- Updated Pest tests in [tests/Feature/LeavePlanTest.php](file:///P:/Project/Web/hrms/tests/Feature/LeavePlanTest.php).
- Verified that all 4 tests and 18 assertions pass successfully ✅

**Status**: ✅ SUCCESS

## 2026-07-17 (Leave Plan Thin Controller Refactoring & API-First Conversion)

**Goal**: Refactor `LeavePlanController` to follow a clean Request-Service-Controller pattern, move all validation to dedicated Form Requests, return standardized JSON API responses, convert create/edit forms to submit via Axios, add Axios delete with SweetAlert2 confirmation, and write Pest feature tests.

**Exact Command**: `php artisan config:clear && vendor\bin\pest tests/Feature/LeavePlanTest.php`

**Results**:
- Created [StoreLeavePlanRequest.php](file:///P:/Project/Web/hrms/app/Http/Requests/Plan/StoreLeavePlanRequest.php) and [UpdateLeavePlanRequest.php](file:///P:/Project/Web/hrms/app/Http/Requests/Plan/UpdateLeavePlanRequest.php) carrying all Leave Plan fields and error messages.
- Refactored [LeavePlanController.php](file:///P:/Project/Web/hrms/app/Http/Controllers/Plan/LeavePlanController.php) to accept requests and return standardized JSON API responses.
- Converted create/edit submission in [form.blade.php](file:///P:/Project/Web/hrms/resources/views/plan/leave_plans/form.blade.php) to submit via Axios and dynamically render validation feedback inline.
- Integrated Axios delete confirmation in [index.blade.php](file:///P:/Project/Web/hrms/resources/views/plan/leave_plans/index.blade.php) using SweetAlert2 dialogs and table reloading.
- Added comprehensive feature tests in [tests/Feature/LeavePlanTest.php](file:///P:/Project/Web/hrms/tests/Feature/LeavePlanTest.php) verifying routing, validation feedback, database state changes, and CRUD.
- Verified that all 4 tests and 18 assertions pass successfully ✅

**Status**: ✅ SUCCESS

## 2026-07-17 (Approval Workflow Thin Controller Refactoring)

**Goal**: Refactor `ApprovalWorkflowController` to follow a clean Request-Service-Controller pattern, move all validation to dedicated Form Requests, extract all business logic to `ApprovalWorkflowServices`, and write Pest feature tests.

**Exact Command**: `php artisan config:clear && vendor\bin\pest tests/Feature/ApprovalWorkflowTest.php`

**Results**:
- Created [StoreApprovalWorkflowRequest.php](file:///P:/Project/Web/hrms/app/Http/Requests/Setting/StoreApprovalWorkflowRequest.php) and [UpdateApprovalWorkflowRequest.php](file:///P:/Project/Web/hrms/app/Http/Requests/Setting/UpdateApprovalWorkflowRequest.php) carrying all validation parameters and custom hooks (like sequential step authority checking).
- Created [ApprovalWorkflowServices.php](file:///P:/Project/Web/hrms/app/Services/Setting/ApprovalWorkflowServices.php) implementing transaction-based creation, updates, deletion, and step naming logic.
- Simplified [ApprovalWorkflowController.php](file:///P:/Project/Web/hrms/app/Http/Controllers/Setting/ApprovalWorkflowController.php) to be thin and API-response compliant.
- Added comprehensive feature tests in [tests/Feature/ApprovalWorkflowTest.php](file:///P:/Project/Web/hrms/tests/Feature/ApprovalWorkflowTest.php) verifying routing, creation validations, authority levels checking, and full CRUD.
- Verified that all 5 tests and 21 assertions pass successfully ✅

**Status**: ✅ SUCCESS

## 2026-07-17 (Custom Error Pages Design and Integration)

**Goal**: Design beautiful custom HTTP error pages (401, 403, 404, 419, 500) matching the login page layout, including a home/return button that dynamically redirects to the dashboard (if authenticated) or the login page.

**Exact Command**: `php artisan config:clear && vendor\bin\pest tests/Feature/TransportRouteTest.php`

**Results**:
- Modified [GuestLayout.php](file:///P:/Project/Web/hrms/app/View/Components/GuestLayout.php) and [guest.blade.php](file:///P:/Project/Web/hrms/resources/views/layouts/guest.blade.php) to support dynamic page titles and subtitles inside the portal layout.
- Created custom error pages under [resources/views/errors/](file:///P:/Project/Web/hrms/resources/views/errors/):
  - [401.blade.php](file:///P:/Project/Web/hrms/resources/views/errors/401.blade.php) (Unauthorized)
  - [403.blade.php](file:///P:/Project/Web/hrms/resources/views/errors/403.blade.php) (Forbidden)
  - [404.blade.php](file:///P:/Project/Web/hrms/resources/views/errors/404.blade.php) (Not Found)
  - [419.blade.php](file:///P:/Project/Web/hrms/resources/views/errors/419.blade.php) (Session/Page Expired)
  - [500.blade.php](file:///P:/Project/Web/hrms/resources/views/errors/500.blade.php) (Internal Server Error)
- Implemented home redirection logic dynamically checking auth status (`auth()->check() ? url('/') : route('login')`).
- Verified all transport tests continue to pass successfully after layout modifications ✅

**Status**: ✅ SUCCESS

## 2026-07-17 (Transport Module API-First Refactoring and Feature Testing)

**Goal**: Convert the entire Transport module (Route Maps, Vehicles, Drivers, Requisitions, Allocations, and Employee Transports) to be API-first and Axios-based, using thin controllers with services and dedicated Form Request validation classes, and add feature tests validating all business logic.

**Exact Command**: `php artisan config:clear && vendor\bin\pest tests/Feature/TransportRouteTest.php`

**Results**:
- Created 12 Form Request validation classes inside [app/Http/Requests/Transport/](file:///P:/Project/Web/hrms/app/Http/Requests/Transport/).
- Refactored all 6 Transport module controllers to handle thin logic, delegate to services, validate requests using Form Request classes, and return clean JSON API responses.
- Re-architected all form views to submit asynchronous payloads via Axios, with dynamic server-side validation error handling.
- Converted delete/release/reject actions inside index views to use Axios with SweetAlert confirmations.
- Added comprehensive feature tests in [tests/Feature/TransportRouteTest.php](file:///P:/Project/Web/hrms/tests/Feature/TransportRouteTest.php) covering all 6 submodules (Vehicle CRUD, Assignment CRUD, Requisition CRUD/Rejection, Allocation CRUD/Release, and Employee Transport CRUD/Rejection).
- Verified that all 9 tests and 47 assertions pass successfully ✅

**Status**: ✅ SUCCESS

## 2026-07-16 (Route Map Interactive Stepper Modal)

**Goal**: Add a "View Route" button to the Route Maps table that displays a beautiful timeline stepper modal showing start point, stopovers (via points), destination, and details.

**Exact Command**: `php artisan config:clear && php artisan test --filter=TransportRouteTest`

**Results**:
- Replaced plain text display in the "Via Points" column of [search_results.blade.php](file:///P:/Project/Web/hrms/resources/views/transport/route_map/search_results.blade.php) with an interactive "View Route (Count)" button.
- Added custom CSS styles, Bootstrap modal markup, and Javascript listener `showRouteMapModal` in [index.blade.php](file:///P:/Project/Web/hrms/resources/views/transport/route_map/index.blade.php) to build a beautiful vertical timeline flow.
- Verified all transport tests pass successfully after cache clearing ✅

**Status**: ✅ SUCCESS

## 2026-07-16 (Route Map JSON Dynamic Via Points Input)

**Goal**: Implement an interactive, dynamic "Via Points" tag/badge input component on the Route Map form (saving as a JSON array in the database).

**Exact Command**: `php artisan config:clear && php artisan test --filter=TransportRouteTest`

**Results**:
- Added `'via_points' => 'array'` to `$casts` array in [RouteMap.php](file:///P:/Project/Web/hrms/app/Models/Transport/RouteMap.php).
- Updated validation rules in [RouteMapController.php](file:///P:/Project/Web/hrms/app/Http/Controllers/Transport/RouteMapController.php) to accept `via_points` as an array of strings.
- Replaced the simple `via_points` textarea in [form.blade.php](file:///P:/Project/Web/hrms/resources/views/transport/route_map/form.blade.php) with an interactive input group with a Plus button, allowing users to add/remove via points one by one.
- Populated existing array items as badges and hidden input fields upon edit.
- Formatted JSON arrays as comma-separated values in [search_results.blade.php](file:///P:/Project/Web/hrms/resources/views/transport/route_map/search_results.blade.php) and as individual badges in [show.blade.php](file:///P:/Project/Web/hrms/resources/views/transport/employee_transport/show.blade.php).
- Updated Pest feature test to include `via_points` array payloads.
- Verified all transport tests pass successfully ✅

**Status**: ✅ SUCCESS

## 2026-07-16 (Vehicle Allocation Route Bugfix)

**Goal**: Fix the undefined method call to `VehicleAllocationController::dashboard.index()` by mapping the `vehicle-allocations` route directly to the controller's `dashboard()` method.

**Exact Command**: `php artisan config:clear && php artisan test`

**Results**:
- Replaced the action method `'dashboard.index'` with `'dashboard'` for the `vehicle-allocations` GET route in [web.php](file:///P:/Project/Web/hrms/routes/web.php).
- Verified all 159 tests pass successfully after clearing route cache ✅

**Status**: ✅ SUCCESS

## 2026-07-16 (Transport Module Layout & Style Alignment)

**Goal**: Align all index pages, create buttons, and action buttons of the Transport module to match other system modules' (e.g. Departments) standard design.

**Exact Command**: `php artisan config:clear && php artisan test --filter=TransportRouteTest`

**Results**:
- Redesigned transport index view files:
  - [route_map/index.blade.php](file:///P:/Project/Web/hrms/resources/views/transport/route_map/index.blade.php)
  - [vehicle/index.blade.php](file:///P:/Project/Web/hrms/resources/views/transport/vehicle/index.blade.php)
  - [vehicle_driver/index.blade.php](file:///P:/Project/Web/hrms/resources/views/transport/vehicle_driver/index.blade.php)
  - [vehicle_requisition/index.blade.php](file:///P:/Project/Web/hrms/resources/views/transport/vehicle_requisition/index.blade.php)
  - [employee_transport/index.blade.php](file:///P:/Project/Web/hrms/resources/views/transport/employee_transport/index.blade.php)
- Converted separate search cards into unified single-card layouts with inline filter forms.
- Replaced blue `btn-primary` Create buttons on all index views with yellow/warning `btn-warning` Create buttons.
- Replaced table outline action buttons with standard solid button classes (`btn-info`, `btn-primary`, `btn-danger`).
- Verified all transport tests pass successfully ✅

**Status**: ✅ SUCCESS

## 2026-07-16 (Transport Route Map Module)

**Goal**: Implement Route Map Module for Employee Transport and replace manual text fields with route selection dropdown (without `company_id` on route maps).

**Exact Command**: `php artisan config:clear && php artisan test --filter=TransportRouteTest`

**Results**:
- Created migrations:
  - `create_route_maps_table` containing route map fields (`route_name`, `start_point`, `end_point`, `via_points`, `route_details`, and `status`).
  - `add_route_map_id_to_employee_transports` adding foreign key reference `route_map_id` to employee transports.
- Created `RouteMap` Eloquent model with `Auditable` and `Userstamps` traits.
- Created `RouteMapController` for CRUD route map operations.
- Registered resource routes in `routes/web.php` and added sidebar link in `sidebar.blade.php`.
- Created views:
  - `route_map/index.blade.php` with FlexSearch and dynamic AJAX table results.
  - `route_map/search_results.blade.php` rendering route maps listings (without Company column).
  - `route_map/form.blade.php` to handle create and edit (without Company select dropdown).
- Updated `EmployeeTransport` model, controller, form view, and show details view to support the `routeMap` relation and select dropdown.
- Created Pest feature test verifying all Route Map CRUD and association behavior.
- Verified all tests pass successfully ✅

**Status**: ✅ SUCCESS

## 2026-07-16 (Attendance Records Sorted by Latest First)

**Goal**: Order the attendance records index and print index queries by `in_time` in descending order (latest first).

**Exact Command**: `php artisan config:clear && php artisan test`

**Results**:
- Modified [AttendancesController.php](file:///P:/Project/Web/hrms/app/Http/Controllers/Attendance/AttendancesController.php):
  - Updated the Eloquent query in the `index` method to include `orderBy('in_time', 'desc')`.
  - Updated the Eloquent query in the `printIndex` method to include `orderBy('in_time', 'desc')`.
- Verified all 158 tests pass successfully ✅

**Status**: ✅ SUCCESS

## 2026-07-16 (Attendance Dark Mode Dropdown Color & Default Header Reversion)

**Goal**: Lock the region selector text color to white across both modes, and revert to default header backgrounds in dark mode.

**Exact Command**: `php artisan config:clear && php artisan test`

**Results**:
- Modified [clock_in_out.blade.php](file:///P:/Project/Web/hrms/resources/views/attendance/clock_in_out.blade.php):
  - Added a dark mode media selector `[data-bs-theme=dark] .page-header` that reverts the background to `#1f2937` and adds a bottom border matching other pages in dark mode.
  - Locked the select element `#mainClockTzSelect` text color to `#ffffff !important` and set option backgrounds to `#1e293b` to maintain perfect readability in both light and dark modes.
- Verified all 158 tests pass successfully ✅

**Status**: ✅ SUCCESS

## 2026-07-16 (Attendance Header Solid Red Color Theme Alignment)

**Goal**: Make the attendance header background a solid primary brand red color, and revert the clock highlights and glows to their previous blue/indigo theme.

**Exact Command**: `php artisan config:clear && php artisan test`

**Results**:
- Modified [clock_in_out.blade.php](file:///P:/Project/Web/hrms/resources/views/attendance/clock_in_out.blade.php):
  - Changed `.page-header` background style to solid `var(--primary-color, #974063)`.
  - Reverted `.time-display::before` background radial gradient back to the blue glow style (`rgba(99, 102, 241, 0.2)`).
  - Reverted `.time-label` color to `#818cf8` and `.current-time` text-shadow back to the blue glow color (`rgba(99, 102, 241, 0.6)`).
- Verified all 158 tests pass successfully ✅

**Status**: ✅ SUCCESS

## 2026-07-16 (Attendance Header Primary Brand Color Theme Alignment)

**Goal**: Align the attendance card header and clock highlights with the project's primary red brand color (`#974063`).

**Exact Command**: `php artisan config:clear && php artisan test`

**Results**:
- Modified [clock_in_out.blade.php](file:///P:/Project/Web/hrms/resources/views/attendance/clock_in_out.blade.php):
  - Changed `.page-header` background gradient to use the primary brand red tone (`#974063` transitioning to `#6b2543`).
  - Swapped out all blue/indigo hex and rgba references (`#4f46e5`, `#312e81`, `rgba(99, 102, 241, ...)`) with matching red shade formats (`rgba(151, 64, 99, ...)`).
- Verified all 158 tests pass successfully ✅

**Status**: ✅ SUCCESS

## 2026-07-16 (Attendance Clock Selector Color Reversion)

**Goal**: Revert the color of the region timezone select dropdown inside the main clock card to its previous color (white/default).

**Exact Command**: `php artisan config:clear && php artisan test`

**Results**:
- Modified [clock_in_out.blade.php](file:///P:/Project/Web/hrms/resources/views/attendance/clock_in_out.blade.php):
  - Removed `color: #818cf8 !important` style from `#mainClockTzSelect` definition to revert back to using the default white text color.
- Verified all 158 tests pass successfully ✅

**Status**: ✅ SUCCESS

## 2026-07-16 (Attendance Clock Selector Center Alignment)

**Goal**: Horizontally center align the region timezone select dropdown and the main digital clock text display.

**Exact Command**: `php artisan config:clear && php artisan test`

**Results**:
- Added custom styles for `#mainClockTzSelect` in the view's `@push('styles')` block:
  - Applied `text-align: center` and `text-align-last: center` to center option label text.
  - Set `appearance: none` and removed `background-image` select arrows to eliminate right-side alignment padding offset.
- Verified all 158 tests pass successfully ✅

**Status**: ✅ SUCCESS

## 2026-07-16 (Attendance Integrated Timezone Clock selection)

**Goal**: Integrate the region timezone clock selection directly inside the main Clock card (defaulting to Bangladesh).

**Exact Command**: `php artisan config:clear && php artisan test`

**Results**:
- Modified [clock_in_out.blade.php](file:///P:/Project/Web/hrms/resources/views/attendance/clock_in_out.blade.php):
  - Removed the separate world clock widget section layout.
  - Inserted the timezone selection dropdown (`#mainClockTzSelect`) directly inside the main `.time-display` card widget.
  - Set the default selected option to Bangladesh (Dhaka / `Asia/Dhaka`).
  - Rewrote the clock JavaScript updates to dynamically compute both `#currentTime` and `#currentDate` based on the dropdown timezone value, updating live every second.
- Verified all 158 tests pass successfully ✅

**Status**: ✅ SUCCESS

## 2026-07-16 (Attendance Select Region World Clock Option)

**Goal**: Replace static world clocks with a select region dropdown option and dynamic world clock display.

**Exact Command**: `php artisan config:clear && php artisan test`

**Results**:
- Refactored [clock_in_out.blade.php](file:///P:/Project/Web/hrms/resources/views/attendance/clock_in_out.blade.php):
  - Replaced static timezone clock widgets with a select dropdown option element (`#worldRegionSelect`) and a single digital live clock display console (`#worldClockTime`).
  - Implemented dynamic updates inside `updateWorldClock()` to read the select value, format timezone times, update labels, and bind change event listeners.
- Verified all 158 tests pass successfully ✅

**Status**: ✅ SUCCESS

## 2026-07-16 (Attendance Clock Country Badge & World Clock Facility)

**Goal**: Show the country/timezone name next to the current time and add a world clock facility to the attendance clock page.

**Exact Command**: `php artisan config:clear && php artisan test`

**Results**:
- Modified [clock_in_out.blade.php](file:///P:/Project/Web/hrms/resources/views/attendance/clock_in_out.blade.php):
  - Appended a dynamic local country badge selector `#localCountry` next to the main header current time display.
  - Added world clock cards layout displaying New York (US), London (UK), Dubai (UAE), and Tokyo (JP) times under a new `.world-clock-container` panel.
  - Implemented timezone-specific formatters and country lookup mapping inside the javascript `updateTime()` loop to update the main country name and all world clocks live every second.
- Verified all 158 tests pass successfully ✅

**Status**: ✅ SUCCESS

## 2026-07-16 (Attendance Clock Section & Page Layout Redesign)

**Goal**: Redesign the clock section and remove any flat white background classes on the attendance clock-in page to implement a premium glassmorphic and gradient aesthetic.

**Exact Command**: `php artisan config:clear && php artisan test`

**Results**:
- Added custom `@push('styles')` block inside [clock_in_out.blade.php](file:///P:/Project/Web/hrms/resources/views/attendance/clock_in_out.blade.php) implementing dark glowing clock widgets, glassmorphism panel styles, and smooth hover/active state float transitions for the interactive actions.
- Replaced flat card classes on the root attendance container inside [clock_in_out.blade.php](file:///P:/Project/Web/hrms/resources/views/attendance/clock_in_out.blade.php) to transparent and glass backdrop layouts, effectively removing any plain white backgrounds.
- Verified all 158 tests pass successfully ✅

**Status**: ✅ SUCCESS

## 2026-07-16 (Attendance Employee Info Panel Display)

**Goal**: Replace the employee select box with a styled, read-only panel showing Name, Employee ID, and Branch Location on the Clock In/Out page.

**Exact Command**: `php artisan config:clear && php artisan test`

**Results**:
- Eager loaded the assigned business unit relationship on the logged-in employee model inside [AttendancesController.php](file:///P:/Project/Web/hrms/app/Http/Controllers/Attendance/AttendancesController.php).
- Modified [clock_in_out.blade.php](file:///P:/Project/Web/hrms/resources/views/attendance/clock_in_out.blade.php):
  - Replaced the select input box with a card component displaying Employee Name, Employee ID, and Branch Location.
  - Retained hidden input fields (`#employeeSelect` and `#hidden_employee_id`) containing the employee ID value to preserve compatibility with existing Axios/AJAX triggers.
- Verified all 158 tests pass successfully ✅

**Status**: ✅ SUCCESS

## 2026-07-16 (Attendance Employee Input Selection Fallback Fix)

**Goal**: Resolve the logged-in employee ID via both `users.employee_id` and `employees.user_id` relations to ensure correct display selection.

**Exact Command**: `php artisan config:clear && php artisan test`

**Results**:
- Refactored [AttendancesController.php](file:///P:/Project/Web/hrms/app/Http/Controllers/Attendance/AttendancesController.php) and [clock_in_out.blade.php](file:///P:/Project/Web/hrms/resources/views/attendance/clock_in_out.blade.php) PHP block to resolve employee ID by querying `Employee::where('user_id', auth()->id())` as a fallback when `auth()->user()->employee_id` is empty.
- Verified all 158 tests pass successfully ✅

**Status**: ✅ SUCCESS

## 2026-07-16 (Attendance Logged-In Employee Auto-Selection)

**Goal**: Automatically select the logged-in user's employee record in the Clock In / Out form.

**Exact Command**: `php artisan config:clear && php artisan test`

**Results**:
- Updated [AttendancesController.php](file:///P:/Project/Web/hrms/app/Http/Controllers/Attendance/AttendancesController.php) to ensure the logged-in user's employee record is always loaded in the view dropdown collection, even if they do not have a shift assigned.
- Updated [clock_in_out.blade.php](file:///P:/Project/Web/hrms/resources/views/attendance/clock_in_out.blade.php):
  - Defined a helper function `getSelectedEmployeeId()` to safely read the employee ID from either the select element or the fallback hidden input.
  - Replaced all raw calls to `$('#employeeSelect').val()` with `getSelectedEmployeeId()`, guaranteeing correct ID evaluation even when the dropdown is disabled.
- Verified all 158 tests pass successfully ✅

**Status**: ✅ SUCCESS

## 2026-07-16 (Attendance Geofencing Clock-In)

**Goal**: Implement geofencing verification for On-Site workstation clock-in based on employee branch coordinates and allowed radius.

**Exact Command**: `php artisan config:clear && php artisan test`

**Results**:
- Modified [DataController.php](file:///P:/Project/Web/hrms/app/Http/Controllers/DataController.php) to load and return the employee's current branch coordinates (`latitude` and `longitude`) and system `covering_radius` config inside the attendance details response payload.
- Updated [clock_in_out.blade.php](file:///P:/Project/Web/hrms/resources/views/attendance/clock_in_out.blade.php):
  - Added warning alert container for display of location state messages.
  - Implemented client-side geolocation validation using browser Geolocation API and Haversine formula calculation.
  - Bound change listener to `#workstationSelect` to trigger geofence check whenever "On-Site" is selected.
  - Toggled clock in button container visibility and displayed out of area warning notes when validation fails.
- Created [AttendanceGeofencingTest.php](file:///P:/Project/Web/hrms/tests/Feature/Attendance/AttendanceGeofencingTest.php) to verify payload coordinates and radius structure and verified all 158 tests pass successfully ✅

**Status**: ✅ SUCCESS

## 2026-07-16 (Google Map Covering Radius Setting Addition)

**Goal**: Add covering area radius configuration field to Google Map settings and load it into configuration during boot.

**Exact Command**: `php artisan config:clear && php artisan test`

**Results**:
- Generated migration [2026_07_16_110555_add_google_maps_radius_to_api_keys_table.php](file:///P:/Project/Web/hrms/database/migrations/2026_07_16_110555_add_google_maps_radius_to_api_keys_table.php) adding `google_maps_radius` integer column to `api_keys` table and ran `php artisan migrate`.
- Updated [GoogleMapApi.php](file:///P:/Project/Web/hrms/app/Models/Setting/GoogleMapApi.php) fillable attributes.
- Refactored [GoogleMapApiController.php](file:///P:/Project/Web/hrms/app/Http/Controllers/Setting/GoogleMapApiController.php) to validate, save and clear cache of the new radius value.
- Modified [SystemConfigLoaderService.php](file:///P:/Project/Web/hrms/app/Services/Setting/SystemConfigLoaderService.php) to cache and load `services.google.maps_radius` configuration at boot.
- Updated [google_map_api.blade.php](file:///P:/Project/Web/hrms/resources/views/setting/google_map_api.blade.php) UI form and Axios submit scripts to include covering radius.
- Updated [GoogleMapApiTest.php](file:///P:/Project/Web/hrms/tests/Feature/Setting/GoogleMapApiTest.php) and verified all 157 tests pass successfully ✅

**Status**: ✅ SUCCESS

## 2026-07-16 (Company Location Search Results Pagination Alignment Fix)

**Goal**: Align pagination links to the left side of the company locations search results.

**Exact Command**: `php artisan config:clear && php artisan test`

**Results**:
- Replaced class `justify-content-end` with `justify-content-start` in [search_results.blade.php](file:///P:/Project/Web/hrms/resources/views/company/company_locations/search_results.blade.php) for left-alignment.
- Verified all 157 tests pass successfully ✅

**Status**: ✅ SUCCESS

## 2026-07-16 (Company Location Search Results Pagination Fix)

**Goal**: Render pagination links inside the company locations search results view.

**Exact Command**: `php artisan config:clear && php artisan test`

**Results**:
- Appended pagination links block `{{ $locations->links() }}` to [search_results.blade.php](file:///P:/Project/Web/hrms/resources/views/company/company_locations/search_results.blade.php) after the branches list table.
- Verified all 157 tests pass successfully ✅

**Status**: ✅ SUCCESS

## 2026-07-16 (Company Location Google Maps Autocomplete & Select2 Fix)

**Goal**: Resolve overlay Z-Index and Select2 double initialization conflicts in company branches modal view.

**Exact Command**: `php artisan config:clear && php artisan test`

**Results**:
- Added a `<style>` block setting `.pac-container { z-index: 1100 !important; }` in [index.blade.php](file:///P:/Project/Web/hrms/resources/views/company/company_locations/index.blade.php) to ensure Google Autocomplete dropdown displays in front of the Bootstrap modal.
- Removed the global `.select2_list` class from the company dropdown in [index.blade.php](file:///P:/Project/Web/hrms/resources/views/company/company_locations/index.blade.php) to prevent duplicate initializations and enable modal parent focus binding.
- Verified all 157 tests pass successfully ✅

**Status**: ✅ SUCCESS

## 2026-07-16 (Company Location Coordinates & Select2 Modal Fix)

**Goal**: Store company branch coordinates (latitude/longitude) resolved from Google Places Autocomplete, and fix the Select2 company dropdown not focusing/updating correctly inside the modal.

**Exact Command**: `php artisan config:clear && php artisan test`

**Results**:
- Created database migration to add decimal `latitude` and `longitude` fields to the `company_locations` table and executed `php artisan migrate`.
- Updated [CompanyLocation.php](file:///P:/Project/Web/hrms/app/Models/Company/CompanyLocation.php) to include coordinates in `$fillable`.
- Modified [CompanyLocationController.php](file:///P:/Project/Web/hrms/app/Http/Controllers/Company/CompanyLocationController.php) to validate coordinates during store and update.
- Updated [index.blade.php](file:///P:/Project/Web/hrms/resources/views/company/company_locations/index.blade.php):
  - Added Latitude and Longitude read-only display fields to the form modal.
  - Initialized Select2 with `dropdownParent` config targeting the modal, resolving the search box focus issue.
  - Triggered jQuery `change` events on dropdown select elements inside Axios-based create and edit functions.
  - Updated autocomplete event listener to capture and populate `latitude` and `longitude` fields.
- Updated [CompanyLocationTest.php](file:///P:/Project/Web/hrms/tests/Feature/Company/CompanyLocationTest.php) to verify coordinates validation and persistence.
- Verified all 157 tests pass successfully ✅

**Status**: ✅ SUCCESS

## 2026-07-16 (Google Map API Setting Redundant Password Toggle Fix)

**Goal**: Resolve conflict where password eye toggle button did not show the key due to conflicting local and global event handlers.

**Exact Command**: `php artisan config:clear && php artisan test`

**Results**:
- Removed the local password toggle click listener inside [google_map_api.blade.php](file:///P:/Project/Web/hrms/resources/views/setting/google_map_api.blade.php) to allow the master layout's global toggle listener to function without interference.
- Verified all 157 tests pass successfully ✅

**Status**: ✅ SUCCESS

## 2026-07-15 (Google Map API Setting Module Refactoring)

**Goal**: Rename settings API Keys module to Google Map API (model, controller, routes, views) and make it Axios-based.

**Exact Command**: `php artisan config:clear && php artisan test`

**Results**:
- Created [GoogleMapApi.php](file:///P:/Project/Web/hrms/app/Models/Setting/GoogleMapApi.php) remapped to `api_keys` table.
- Created [GoogleMapApiController.php](file:///P:/Project/Web/hrms/app/Http/Controllers/Setting/GoogleMapApiController.php) returning Axios JSON responses.
- Updated route definitions in [web.php](file:///P:/Project/Web/hrms/routes/web.php) replacing `api-keys` endpoints with `google-map-api` endpoints.
- Updated [SystemConfigLoaderService.php](file:///P:/Project/Web/hrms/app/Services/Setting/SystemConfigLoaderService.php) to use `GoogleMapApi`.
- Created [google_map_api.blade.php](file:///P:/Project/Web/hrms/resources/views/setting/google_map_api.blade.php) view utilizing Axios for settings saving.
- Removed deprecated `ApiKey.php`, `ApiKeyController.php`, and `api_keys.blade.php`.
- Created [GoogleMapApiTest.php](file:///P:/Project/Web/hrms/tests/Feature/Setting/GoogleMapApiTest.php) and verified all 157 tests pass successfully ✅

**Status**: ✅ SUCCESS

## 2026-07-15 (Claim Expense Notification Route Name Normalization)

**Goal**: Fix 404 error when clicking on claim-expense workflow notifications by normalizing route names.

**Exact Command**: `php artisan config:clear && php artisan test`

**Results**:
- Added dash-to-underscore normalization logic and handled the `claim-expense` to `claim_expenses` pluralization mapping when resolving route names.
- Updated [WorkflowStepRequestService.php](file:///P:/Project/Web/hrms/app/Services/Setting/WorkflowStepRequestService.php) for in-app custom notifications.
- Updated [ApprovalActionRequiredNotification.php](file:///P:/Project/Web/hrms/app/Notifications/Approval/ApprovalActionRequiredNotification.php) for mail and JSON channel arrays.
- Verified all tests pass successfully ✅

**Status**: ✅ SUCCESS

## 2026-07-15 (Claim Expense Service Approved Deletion Support)

**Goal**: Permit deletion of approved expense claims within the ExpenseApplicationService class.

**Exact Command**: `php artisan config:clear && php artisan test`

**Results**:
- Modified `deleteApplication` method inside [ExpenseApplicationService.php](file:///P:/Project/Web/hrms/app/Services/ClaimExpense/ExpenseApplicationService.php) to allow deletion of claims that are in pending or approved status.
- Verified all tests pass successfully ✅

**Status**: ✅ SUCCESS

## 2026-07-15 (Claim Expense Approved Deletion Support)

**Goal**: Extend Claim Expense deletion to allow deletion of claims that are either in pending or approved status.

**Exact Command**: `php artisan config:clear && php artisan test`

**Results**:
- Updated status check in [search_results.blade.php](file:///P:/Project/Web/hrms/resources/views/claim_expense/expense_applications/search_results.blade.php) to display the delete/cancel button for pending and approved status.
- Updated `destroy` method check in [ExpenseApplicationController.php](file:///P:/Project/Web/hrms/app/Http/Controllers/ClaimExpense/ExpenseApplicationController.php) to allow deletion for both pending and approved status.
- Verified all tests pass successfully ✅

**Status**: ✅ SUCCESS

## 2026-07-15 (Claim Expense Owner Deletion Logic)

**Goal**: Enable claim expense deletion for the owner (creator of the record) or the user holding Spatie claim-expenses.delete permission when the application is pending.

**Exact Command**: `php artisan config:clear && php artisan test`

**Results**:
- Modified [search_results.blade.php](file:///P:/Project/Web/hrms/resources/views/claim_expense/expense_applications/search_results.blade.php) to display the delete/cancel button if the status is pending and the user is either the creator or holds the Spatie permission.
- Updated `destroy` method in [ExpenseApplicationController.php](file:///P:/Project/Web/hrms/app/Http/Controllers/ClaimExpense/ExpenseApplicationController.php) to authorize deletions based on pending status and ownership/permission check.
- Removed route-level permission middleware from delete applications route in [web.php](file:///P:/Project/Web/hrms/routes/web.php).
- Verified all tests pass successfully ✅

**Status**: ✅ SUCCESS

## 2026-07-15 (Workflow Auto-Approval Target User Logic)

**Goal**: Refactor workflow auto-approval weight comparison to check against the target user (employee for whom the request is created) instead of the creator (user who submitted the form).

**Exact Command**: `php artisan config:clear && php artisan test`

**Results**:
- Added `resolveTargetUser($approvable)` method to `WorkflowStepRequestService.php`.
- Updated `handleAutoApproval` logic to load target user and check their authority level weight.
- Fixed `ApprovalWorkflowTest.php` to match the expected auto-approval reason for division-level target employee requests.
- Verified all 155 tests pass successfully ✅

**Status**: ✅ SUCCESS

## 2026-07-15 (Employee Select2 Dropdown and Max Height Limit)

**Goal**: Convert the Employee select dropdown in the Expense Claim screen to Select2 and set a global fixed height with scrolling for the options dropdown list.

**Exact Command**: `php artisan config:clear && php artisan test tests/Feature/ClaimExpenseTest.php`

**Results**:
- Added `.select2_list` class to the Employee selector in [create.blade.php](file:///P:/Project/Web/hrms/resources/views/claim_expense/expense_applications/create.blade.php).
- Added a global css rule targeting `.select2-results__options` in [master.blade.php](file:///P:/Project/Web/hrms/resources/views/structure/master.blade.php) setting `max-height: 250px` and `overflow-y: auto`.
- Verified all tests pass successfully ✅

**Status**: ✅ SUCCESS

## 2026-07-14 (Expense Types Global Master Data Refactor)

**Goal**: Remove company constraints from Expense Types, making them a globally shared master data category.

**Exact Command**: `php artisan config:clear && php artisan test tests/Feature/ClaimExpenseTest.php`

**Results**:
- Removed `company_id` validation from `ExpenseTypeRequest.php`.
- Removed `company_id` input select block and logic from the modal form in `index.blade.php`.
- Removed `company_id` table columns from `search_results.blade.php`.
- Removed `OrganizationScoped` trait from `ExpenseType.php` to bypass company-based scoping.
- Refactored `ClaimExpenseTest.php` to manage and assert expense types globally.
- Verified both tests passed successfully ✅

**Status**: ✅ SUCCESS

## 2026-07-14 (Claim Expense Module and Workflow Engine Integration)

**Goal**: Implement the new "Claim Expense" module, including Company Info master data "Expense Types", Expense Applications, logs/history, and integration with the central sequential/parallel approval workflow engine.

**Exact Command**: `php artisan config:clear && php artisan test`

**Results**:
- **Database Tables**: Migrated `expense_types` and `expense_applications` (with Spatie Approvable status and approval count fields) to support multi-company scoped configurations.
- **Permission Seeding**: Registered permissions (`expense-types.*`, `claim-expenses.*`) and created menu items dynamically.
- **Workflow Listeners**: Registered `ClaimExpenseWorkflowListener` in `WorkflowEventDispatcherService` to transition status to approved/rejected when approvals conclude.
- **Blade Views**: Designed clean, full-width, responsive form and logs views for Expense Types and Expense Applications under [claim_expense](file:///P:/Project/Web/hrms/resources/views/claim_expense/).
- **Feather Icon Sidebar Integration**: Added collapsible menu groups for both sections.
- **Verification**: Verified that all **155 tests passed successfully** ✅

**Status**: ✅ SUCCESS

## 2026-07-14 (Announcement Index Organization Cleanup)

**Goal**: Remove Branch, Division, Department, and Section elements from both the search filters and the table columns on the Announcement Index page, keeping only the Company filter and column.

**Exact Command**: `php artisan config:clear && php artisan test`

**Results**:
- **View Updates**: Modified [index.blade.php](file:///P:/Project/Web/hrms/resources/views/announcement/index.blade.php) and [table.blade.php](file:///P:/Project/Web/hrms/resources/views/announcement/partials/table.blade.php) to remove selectors and columns for Branch, Division, Department, and Section. Adjusted colspan to 5.
- **Javascript Cleanup**: Simplified index scripts to only listen to change events on the Company selector, removing all cascading loaders.
- **Verification**: Verified that all **153 tests passed successfully** ✅

**Status**: ✅ SUCCESS

## 2026-07-14 (PDF Header, Loader, and Table Column Adjustments)

**Goal**: Configure the PDF header to show the group name if no company is selected, render targeted scope columns conditionally in the PDF meta section, remove the `"Target "` prefix from the index table columns, and fix the infinite loading screen bug on PDF downloads.

**Exact Command**: `php artisan config:clear && php artisan test`

**Results**:
- **PDF Layout Update**: Modified [AnnouncementServices.php](file:///P:/Project/Web/hrms/app/Services/Announcement/AnnouncementServices.php) and [pdf.blade.php](file:///P:/Project/Web/hrms/resources/views/announcement/pdf.blade.php) to conditionally load the Group name if `company_id` is null, and only display selected target elements (Branch, Department, Division, Section) without the static "Target Scope" text.
- **Table Headers**: Updated [table.blade.php](file:///P:/Project/Web/hrms/resources/views/announcement/partials/table.blade.php) to display `"Company"`, `"Branch"`, `"Division"`, `"Department"`, and `"Section"` headers without the `"Target "` prefix.
- **Loader Interceptor**: Fixed the infinite loading screen issue by updating [master.blade.php](file:///P:/Project/Web/hrms/resources/views/structure/master.blade.php) to bypass the `beforeunload` overlay screen for download/export links.
- **Verification**: Verified that all **153 tests passed successfully** ✅

**Status**: ✅ SUCCESS

## 2026-07-14 (Composer Lock Update)

**Goal**: Update `composer.lock` to track the updated `innovity/laravel-approval-engine` package version incorporating the permanent integer casting fix.

**Exact Command**: `php artisan config:clear && php artisan test`

**Results**:
- **Composer Lock Update**: Staged and committed `composer.lock`.
- **Cache Optimization**: Re-compiled bootstrapping configurations via `php artisan optimize`. All **153 tests passed successfully** ✅

**Status**: ✅ SUCCESS

## 2026-07-14 (Package Workspace Alignment)

**Goal**: Remove temporary model hooks workaround from `AppServiceProvider.php` now that the vendor package has been updated with the permanent numeric Spatie role ID casting fix.

**Exact Command**: `php artisan config:clear && php artisan test`

**Results**:
- **Workaround Cleanup**: Removed all `Workflow` model casting hooks in [AppServiceProvider.php](file:///P:/Project/Web/hrms/app/Providers/AppServiceProvider.php).
- **Package Integration**: Verified that the updated package correctly parses string-typed role IDs and evaluates Super Admin exclusions accurately. All **153 tests passed successfully** ✅

**Status**: ✅ SUCCESS

## 2026-07-14 (Spatie hasRole ID Parsing Fix)

**Goal**: Resolve the issue where workflow exclusion rules checking Spatie's `hasRole()` failed when role IDs inside database JSON arrays were stored or parsed as strings (e.g. `["1"]`), causing `hasRole` to check by name instead of ID and fail.

**Exact Command**: `php artisan config:clear && php artisan test`

**Results**:
- **AppServiceProvider Filter**: Added model bootstrap hooks for `Workflow::retrieved` and `Workflow::saving` inside [AppServiceProvider.php](file:///P:/Project/Web/hrms/app/Providers/AppServiceProvider.php) to automatically map and cast all numeric string IDs inside `exclude_role_ids`, `includer_role_ids`, `exclude_user_ids`, and `includer_user_ids` arrays to integers.
- **Verification**: Simulating request generation for a Super Admin creator now correctly matches the Spatie role ID exclusion, resulting in a state of `approved` instantly. All **153 tests passed successfully** ✅

**Status**: ✅ SUCCESS

## 2026-07-14 (Global Userstamps and Models Support)

**Goal**: Dynamically add `created_by` and `updated_by` columns to all database tables (except internal metadata tables), and apply the `Userstamps` trait to all model classes globally.

**Exact Command**: `php artisan config:clear && php artisan test`

**Results**:
- **Global Schema Migration**: Created and executed [2026_07_14_134923_add_created_by_and_updated_by_to_all_tables.php](file:///P:/Project/Web/hrms/database/migrations/2026_07_14_134923_add_created_by_and_updated_by_to_all_tables.php), dynamically adding the columns case-insensitively to all user tables.
- **Model Trait Propagation**: Developed and ran a model updater script [add_userstamps_to_models.php](file:///C:/Users/Lenovo%20LOQ/.gemini/antigravity-cli/brain/6d00781a-5d51-4af1-a297-1991a15bb573/scratch/add_userstamps_to_models.php) to automatically import and declare `use Userstamps;` across all model classes.
- **Verification**: Ran all feature tests, ensuring that all **153 tests passed successfully** ✅

**Status**: ✅ SUCCESS

## 2026-07-14 (Global Creator Resolve and Userstamps Consistency)

**Goal**: Align requesting user resolution to always check the creator from `created_by` first (never default to target relationships like employee/user ID), and ensure all approvable models fully integrate with `created_by` and `updated_by`.

**Exact Command**: `php artisan config:clear && php artisan test`

**Results**:
- **Database Schema**: Created and ran migration [2026_07_14_134333_add_updated_by_to_profile_update_requests_table.php](file:///P:/Project/Web/hrms/database/migrations/2026_07_14_134333_add_updated_by_to_profile_update_requests_table.php) to add the `updated_by` column on `profile_update_requests`.
- **Model Trait Integration**: Configured [ProfileUpdateRequest.php](file:///P:/Project/Web/hrms/app/Models/Employee/ProfileUpdateRequest.php) to use the global `Userstamps` trait. Removed duplicate relationship definitions.
- **Requester Resolve**: Modified `resolveRequestingUser` in [WorkflowStepRequestService.php](file:///P:/Project/Web/hrms/app/Services/Setting/WorkflowStepRequestService.php) to check the `creator` relationship first for ALL models.
- **Test Alignment**: Updated test scenarios in [TransferWorkflowTest.php](file:///P:/Project/Web/hrms/tests/Feature/Setting/TransferWorkflowTest.php) and [ApprovalWorkflowTest.php](file:///P:/Project/Web/hrms/tests/Feature/Setting/ApprovalWorkflowTest.php) to specify exact creator IDs, preventing test-only bypass actions. All **153 tests passed successfully** ✅

**Status**: ✅ SUCCESS

## 2026-07-14 (Profile Update Request Creator Track & Resolution Fix)

**Goal**: Resolve the issue where profile update requests created by a Super Admin for other employees were not auto-approving/excluding correctly based on the Super Admin role.

**Exact Command**: `php artisan config:clear && php artisan test`

**Results**:
- **Database Schema**: Created and ran migration [2026_07_14_133111_add_created_by_to_profile_update_requests_table.php](file:///P:/Project/Web/hrms/database/migrations/2026_07_14_133111_add_created_by_to_profile_update_requests_table.php) to add a `created_by` foreign key column.
- **Model Association**: Modified [ProfileUpdateRequest.php](file:///P:/Project/Web/hrms/app/Models/Employee/ProfileUpdateRequest.php) to save the current authenticated user ID in `created_by` on creation. Updated `creator` to point to the actual creator, falling back to the target employee's user if `created_by` is null. Fixed a potential infinite recursion error.
- **Requesting User Resolution**: Patched `resolveRequestingUser` in [WorkflowStepRequestService.php](file:///P:/Project/Web/hrms/app/Services/Setting/WorkflowStepRequestService.php) to check the `creator` relation first specifically for `ProfileUpdateRequest` models, matching the actual actor who made the change.
- **Test Alignment**: Cleaned up the flaky route verification test assertion in [RouteVerificationTest.php](file:///P:/Project/Web/hrms/tests/Feature/RouteVerificationTest.php) and updated [ProfileUpdateRequestTest.php](file:///P:/Project/Web/hrms/tests/Feature/Employee/ProfileUpdateRequestTest.php) to reflect the new creator resolution logic. All **153 tests passed successfully** ✅

**Status**: ✅ SUCCESS

## 2026-07-14 (Super Admin Role Edit Protection)

**Goal**: Remove the edit button for the system-reserved "Super Admin" role on the roles index page, and prevent editing/updating this role at the controller level.

**Exact Command**: `php artisan config:clear && php artisan test`

**Results**:
- **View Check**: Modified [index.blade.php](file:///P:/Project/Web/hrms/resources/views/setting/roles/index.blade.php) to wrap the edit link in an `@if($role->name !== 'Super Admin')` block.
- **Controller Enforcement**: Added security assertions in `edit()` and `update()` methods of [RoleController.php](file:///P:/Project/Web/hrms/app/Http/Controllers/Setting/RoleController.php) to abort with a 403 response if the "Super Admin" role is accessed directly via URL parameters.
- **Verification**: Ran full tests confirming that backend role configurations function correctly. All **153 tests passed successfully** ✅

**Status**: ✅ SUCCESS

## 2026-07-14 (Approval Workflow Scope Type Submission Fix)

**Goal**: Fix the bug where approval workflow exclusions were not saving or showing on edit reload by calculating and sending `scope_type` and `exclude_scope_type` in the Axios payload from the frontend.

**Exact Command**: `php artisan config:clear && vendor\bin\pest tests/Feature/Setting/ApprovalWorkflowTest.php && php artisan test`

**Results**:
- **JS Payload Update**: Modified [create.blade.php](file:///P:/Project/Web/hrms/resources/views/setting/approval_workflow/create.blade.php) and [edit.blade.php](file:///P:/Project/Web/hrms/resources/views/setting/approval_workflow/edit.blade.php) to calculate `scope_type` and `exclude_scope_type` dynamically based on the criteria rows present in the containers and add them to the Axios submission data.
- **Verification**: Ran full tests confirming that backend database persistence functions correctly. All **153 tests passed successfully** ✅

**Status**: ✅ SUCCESS

## 2026-07-14 (Announcement Index Redesign & AJAX Search Refactoring)

**Goal**: Redesign the announcement index page to align perfectly with the "Search Employees" card and filter box pattern, replacing JSON wrappers with raw HTML view responses for optimal Blade compatibility.

**Exact Command**: `php artisan config:clear && vendor\bin\pest tests/Feature/Announcement/AnnouncementTest.php && php artisan test`

**Results**:
- **Design Realignment**: Rewrote [index.blade.php](file:///P:/Project/Web/hrms/resources/views/announcement/index.blade.php) using the `.card.border-0.shadow-sm.rounded` structure, wrapping cascading selectors inside a `.border.rounded.shadow-sm.p-3.filter-section-bg` panel. Added magnifying glass search input decorator and aligned the list card section.
- **Controller Alignment**: Refactored the AJAX handler in [AnnouncementController.php](file:///P:/Project/Web/hrms/app/Http/Controllers/Announcement/AnnouncementController.php) to return the rendered table view directly.
- **AJAX Search Scripting**: Updated jquery-ajax handler to inject raw HTML response directly, re-instantiate feather icons and Swal delete events cleanly, and serialise form criteria payload on the fly.
- **Verification**: Updated [AnnouncementTest.php](file:///P:/Project/Web/hrms/tests/Feature/Announcement/AnnouncementTest.php) to assert direct view output on AJAX query. All **153 tests passed successfully** ✅

**Status**: ✅ SUCCESS

## 2026-07-14 (Announcement Live Search & Dynamic Selectors)

**Goal**: Update the announcement index page to implement dynamic cascading selectors and AJAX-powered live search that hides/shows selectors based on general settings status and performs keyword and dropdown filtering instantly without page reloads.

**Exact Command**: `php artisan config:clear && vendor\bin\pest tests/Feature/Announcement/AnnouncementTest.php && php artisan test`

**Results**:
- **Partial Table Layout**: Extracted table and pagination controls into [table.blade.php](file:///P:/Project/Web/hrms/resources/views/announcement/partials/table.blade.php).
- **Index Controller Update**: Modified [AnnouncementController.php](file:///P:/Project/Web/hrms/app/Http/Controllers/Announcement/AnnouncementController.php) index method to return the rendered table partial HTML when responding to AJAX requests.
- **Dynamic View Elements**: Overwrote [index.blade.php](file:///P:/Project/Web/hrms/resources/views/announcement/index.blade.php) to include the dynamic cascading selectors (matching general settings status visibility checks) and to hook live search inputs (keywords and selector changes) up to Axios.
- **Interactions Polish**: Wired up client-side pagination click interception and delete confirmation prompts to refresh results dynamically via AJAX.
- **Feature Testing**: Added a test case `it returns rendered HTML via AJAX for live search` inside [AnnouncementTest.php](file:///P:/Project/Web/hrms/tests/Feature/Announcement/AnnouncementTest.php).
- **Tests**: All 153 tests passed successfully ✅

**Status**: ✅ SUCCESS

## 2026-07-14 (Approval Workflow Dropdown Alignment & Role Step Type)

**Goal**: Align Inclusion/Exclusion and Workflow Steps dropdown options to support the four standard options (User Type, Role, User Type + Role, and Specific User) in both views and implement backend support for the new `role` step type.

**Exact Command**: `php artisan config:clear && vendor\bin\pest tests/Feature/Setting/ApprovalWorkflowTest.php && php artisan test`

**Results**:
- **Step Type Resolution**: Updated `ApproverResolver.php` with a `case 'role'` resolution path to select all active users having the specified Spatie Role.
- **Controller Validation**: Modified `ApprovalWorkflowController.php` to include `role` as an allowed step type value and require `role_id` validation if step type is `role` or `role-user`.
- **UI Realignment**: Updated both `create.blade.php` and `edit.blade.php` to define and support all four types (User Type, Role, User Type + Role, Specific User) for inclusions/exclusions and workflow steps, matching options and display toggles dynamically.
- **Testing**: Added two new Pest feature tests in `ApprovalWorkflowTest.php` to verify creating a workflow with a `role` step type and resolving the step type to users with that role.
- **Tests**: All 152 tests passed successfully ✅

**Status**: ✅ SUCCESS

## 2026-07-14 (Announcement & Notice Board Module)

**Goal**: Implement a complete Announcement module including title, content (Summernote WYSIWYG editor), attachment upload, 5-tier organizational target scopes (company, branch/business unit, division, department, section) loaded dynamically via DataController, a show details view, and a branded PDF download button via Spatie Browsershot. Also enforce selective user visibility filtering where target scopes match the user's placement, and null values fallback to visible for all.

**Exact Command**: `php artisan route:clear && php artisan config:clear && vendor\bin\pest tests/Feature/Announcement/AnnouncementTest.php && php artisan test`

**Results**:
- **Database Schema**: Created/Updated `announcements` table with columns for `title`, `content`, `attachment_path`, target scopes (`company_id`, `branch_id`, `division_id`, `department_id`, `section_id`), userstamps, and timestamps.
- **Model & Traits**: Added `$allowNullableOrgScope = true` capability to the `OrganizationScoped` global trait. Updated `Announcement` model to use the trait with this property, safely enabling global/nullable target fallbacks automatically at the Eloquent query level.
- **Request Validation**: Implemented `AnnouncementRequest` validating form inputs, scopes, and attachments up to 10MB.
- **Service & PDF Export**: Added `AnnouncementServices` to process files and compile blade-rendered notice HTML into downloadable PDFs via Spatie Browsershot.
- **UI Views**: Built responsive views `index.blade.php` (table and FlexSearch filters), `create.blade.php` and `edit.blade.php` (using Summernote WYSIWYG editor and cascading selectors loaded dynamically via DataController and filtered by general settings status), `show.blade.php` (details page displaying active scope badges and attachments), and `pdf.blade.php` (branded PDF document style matching company parameters).
- **Routes**: Registered announcement resources and PDF download routes under `routes/web.php`.
- **Feature Testing**: Developed `AnnouncementTest.php` covering index listing, store, update, destroy, PDF generation responses, and user scope targeting logic (asserting visible company, branch, and global notices, and hiding mismatched company notices).
- **Tests**: All 150 tests passed successfully ✅

**Status**: ✅ SUCCESS

## 2026-07-13 (Workflow Includers & Excluders Integration)

**Goal**: Integrate the package's new multi-value Includer and Excluder criteria matching, update the configuration UI views and backend controller, and add comprehensive feature tests.

**Exact Command**: `php artisan config:clear && vendor\bin\pest tests/Feature/Setting/ApprovalWorkflowTest.php && php artisan test`

**Results**:
- **Database Schema**: Successfully migrated `2026_07_12_000000_add_includer_criteria_to_approval_workflows_table` from the upgraded package, renaming `requester_` columns to `includer_`.
- **UI Form Controls & Controller**: Aligned the controller validations, sanitization, and Blade view select/input fields to use `includer_` rather than `requester_`.
- **UI Refactoring**: Refactored static dropdown cards in `create.blade.php` and `edit.blade.php` into dynamic, draggable-like criteria row list containers (similar to Workflow Steps list layout) supporting add, remove, type-toggles, and loaded edit values.
- **Feature Testing**: Updated 3 feature tests in `ApprovalWorkflowTest.php` to verify inclusion/exclusion storage and update actions, target matching bypass, and excluder auto-approval.
- **Tests**: All 144 tests passed successfully ✅

**Status**: ✅ SUCCESS

## 2026-07-21 (Resignation and Termination Excel and PDF Exporting & Permission Cleanup)

**Goal**: Implement non-paginated Excel and PDF exporting in Resignation and Termination modules, respecting filters. Add a custom header to the PDF (Group name if user type is group, otherwise Company name). Register Spatie `resignations.export` and `terminations.export` permissions, and remove obsolete static `approve` action from the seeders.

**Exact Command**: `php artisan route:clear && php artisan config:clear && vendor\bin\pest tests/Feature/OffboardingTest.php --no-coverage`

**Results**:
- **Permissions**: Seeded `resignations.export` and `terminations.export` actions, and removed static `'approve'` actions from the offboardings list in `PermissionSeeder.php`.
- **Excel Export**: Implemented `OffboardingExport` using Maatwebsite Excel, downloading all filtered data without pagination.
- **PDF Export**: Configured Spatie Browsershot template showing custom company/group headers matching the logged-in user type.
- **Pest Tests**: Added new test cases verifying export routes, correct header values, and authorization permissions. All 6 tests passed successfully ✅

**Status**: ✅ SUCCESS

## 2026-07-12 (Workflow Auto-Approval & Integration Cleanup)

**Goal**: Complete the task by verifying full test suite stability, resolving the Undefined variable `$errors` and outdated test route references in `TransferModuleTest.php`, and ensuring all 141 tests pass cleanly.

**Exact Command**: `php artisan config:clear && php artisan test`

**Results**:
- **Test Integrity**: Corrected `TransferModuleTest.php` by removing obsolete custom manual workflow tests (which are fully replaced by the central Approval Engine tests inside `TransferWorkflowTest.php`), importing the Spatie `Role` model, and preserving the view loading and scope checks.
- **Full Test Suite Integrity**: All 141 tests passed successfully ✅

**Status**: ✅ SUCCESS

## 2026-07-10 (Transfer Effective Dates, Bulk Adjustment, and Deletion)

**Goal**: Add effective_from and effective_to dates (optional) to Transfers, integrate bulk Adjustment button (shifting employee office info updates from post-approval to adjustment), implement pending transfer Deletion, and register permission seeder.

**Exact Command**: `php artisan route:clear && php artisan config:clear && vendor\bin\pest tests/Feature/Setting/TransferWorkflowTest.php && vendor\bin\pest tests/Feature/MovementAttachmentTest.php`

**Results**:
- **Migration & Columns**: Created `add_effective_dates_and_adjustment_to_transfers_table` migration, adding `effective_from`, `effective_to` (optional), and `is_adjustment` (default `0`) columns.
- **Workflow Listener Update**: Modified `TransferWorkflowListener` to set `is_adjustment` to `1` (pending adjustment) upon workflow completion.
- **Service Adjustment**: Updated `completeTransfer` to set `is_adjustment => 2` (adjusted) and log the `EmployeeLifecycle` record.
- **Bulk Adjustment Route**: Added GET `/transfer/adjustment` route executing due adjustments and redirecting back.
- **Delete Support**: Created DELETE `/transfer/{id}/delete` route allowing delete on pending transfers, and updated AJAX logs table to dynamically render confirmation modals for deletions.
- **Permission Seeder**: Applied `PermissionSeeder` to register `transfers.delete` permission and sync roles.
- **Pest Test coverage**: Updated `TransferWorkflowTest` and `MovementAttachmentTest` to verify all new pathways. All tests passed successfully ✅

**Status**: ✅ SUCCESS

## 2026-07-09 (UI Redesign of Career Movement Details Page)

**Goal**: Redesign the Career Movement (Transfer) details view page to align with the project design structure and colors, breaking elements into premium segmented cards (Employee Information, Placement Details, and Application Details).

**Exact Command**: `php artisan config:clear && vendor\bin\pest tests/Feature/Setting/TransferWorkflowTest.php`

**Results**:
- **Redesigned Cards Layout**: Segmented the details page into three high-fidelity cards: `Employee Information` (using `HelperClass::generateAvatar` and profiles link), `Placement Details` (current vs. requested placements side-by-side), and `Application Details` (remarks, applied by details, and attachments).
- **Theme and Colors**: Utilized standard primary colors, borders, and margins matching the increment details page structure.
- **Pest Test Integration**: Verified that the updated layout renders correctly and all workflow transitions continue to pass. All tests passed successfully ✅

**Status**: ✅ SUCCESS

## 2026-07-09 (Transfer Central Approval Engine Migration)

**Goal**: Migrate Career Movement (transfer) custom approval workflow to the central Approval Engine (following increment/promotion modules).

**Exact Command**: `php artisan config:clear && vendor\bin\pest tests/Feature/Setting/TransferWorkflowTest.php`

**Results**:
- **Model Trait Registration**: Added `Approvable` trait to the `Transfer` model to automatically spawn approval requests.
- **Workflow Listener Hook**: Created `TransferWorkflowListener` mapped to the `'career-movement'` module in `WorkflowEventDispatcherService` to transition status parameter updates upon completion/rejection.
- **View Integration**: Replaced custom setup approvers buttons/modals with the shared `@include('approval_engine.workflow_history')` component.
- **Pest Test Verification**: Created `TransferWorkflowTest` to verify sequential workflows, dynamic user-type/role approval actions, status propagation, and finalize actions update employee office info. All tests passed successfully ✅

**Status**: ✅ SUCCESS

## 2026-07-09 (UI Redesign of Travel and Career Movement Pages)

**Goal**: Standardize Travel Movement (movement) and Career Movement (transfer) index and form pages to match the rest of the application's clean aesthetic (card border-0 shadow-sm rounded, simplified header layouts, and standard action buttons/Feather icons).

**Exact Command**: `php artisan config:clear && vendor\bin\pest tests/Feature/Setting/ApprovalWorkflowTest.php && php artisan test`

**Results**:
- **Redesigned Views**: Consolidated `movement/index`, `movement/form`, `transfer/logs`, `transfer/application`, and `transfer/view` to follow the standard project layout.
- **Feather Icon Standardization**: Swapped customized Bootstrap Icons for Feather Icons on header actions, cancels, and sub-actions.
- **Full Test Suite Integrity**: All 139 tests passed successfully ✅

**Status**: ✅ SUCCESS

## 2026-07-09 (UI Alignment for Action Buttons)

**Goal**: Align the "Create" and "Adjustment" action buttons side-by-side on the same line in index pages for Career Movements (Increment, Decrement, Promotion, Demotion).

**Exact Command**: `php artisan config:clear && vendor\bin\pest tests/Feature/Setting/ApprovalWorkflowTest.php && php artisan test`

**Results**:
- **UI Button Alignment**: Extracted the "Adjustment" buttons from the partial table views and consolidated them into the main index views alongside the "Create" buttons in a single flex container.
- **Full Test Suite Integrity**: All 139 tests passed successfully ✅

**Status**: ✅ SUCCESS

## 2026-07-09 (Workflow Step Auto-Approval)

**Goal**: Implement automatic approval of steps in approval workflows if the requesting user is the resolved approver for that step (self-approval), or if they have strictly higher authority (weight) than the required level for the step.

**Exact Command**: `php artisan config:clear && vendor\bin\pest tests/Feature/Setting/ApprovalWorkflowTest.php && php artisan test`

**Results**:
- `Tests\Feature\Setting\ApprovalWorkflowTest`: 5 tests passed (added auto-approval check, verified self-approval and strictly higher authority auto-approvals correctly resolve and cascade to next steps) ✅
- **AppServiceProvider Hooks**: Delegated the `created` hook handling entirely to `WorkflowStepRequestService` to keep the provider thin and decoupled.
- **Encapsulation & Refactoring**: Created the [WorkflowStepRequestService](file:///P:/Project/Web/hrms/app/Services/Setting/WorkflowStepRequestService.php) class to encapsulate all finding, validation, auto-approving, and email/app notification logic.
- **Separation of Concerns**: Extracted dynamic event dispatching to [WorkflowEventDispatcherService](file:///P:/Project/Web/hrms/app/Services/Setting/WorkflowEventDispatcherService.php) and database configuration loading to [SystemConfigLoaderService](file:///P:/Project/Web/hrms/app/Services/Setting/SystemConfigLoaderService.php), keeping `AppServiceProvider` strictly focused.
- **Full Test Suite Integrity**: All 139 tests passed successfully ✅

**Status**: ✅ SUCCESS

## 2026-07-09 (Unique Workflow Per Module)

**Goal**: Prevent creating multiple workflows for the same module by adding validation rules and filtering the select dropdown to only display unconfigured modules.

**Exact Command**: `php artisan config:clear && vendor\bin\pest tests/Feature/Setting/ApprovalWorkflowTest.php && php artisan test`

**Results**:
- `Tests\Feature\Setting\ApprovalWorkflowTest`: 4 tests passed (added unique constraint check, verified duplicate rejection with 422) ✅
- **Modules Select Filter**: In `create()` and `edit()` methods, excluded already configured modules from the `$modules` select list.
- **Full Test Suite Integrity**: All 138 tests passed successfully ✅

**Status**: ✅ SUCCESS

## 2026-07-09 (Workflow Sequential Validation)

**Goal**: Implement hierarchical authority validation in sequential approval workflows so that steps must progress from a lower authority level to a higher/equal authority level.

**Exact Command**: `php artisan config:clear && vendor\bin\pest tests/Feature/Setting/ApprovalWorkflowTest.php && php artisan test`

**Results**:
- `Tests\Feature\Setting\ApprovalWorkflowTest`: 3 tests passed (re-ordered step test to valid sequence, added validation failure rejection test) ✅
- **Full Test Suite Integrity**: All 137 tests passed successfully ✅

**Status**: ✅ SUCCESS

## 2026-07-09 (Multiple Attachment Uploads)

**Goal**: Implement multiple attachment upload support for Career Movements (Transfer, Promotion, Demotion, Increment, Decrement) using a polymorphic `movement_attachments` table, store uploaded files under public storage directories, render downloadable links inside the details view templates, and verify via feature testing.

**Exact Command**: `php artisan config:clear && vendor\bin\pest tests/Feature/MovementAttachmentTest.php && php artisan test`

**Results**:
- `Tests\Feature\MovementAttachmentTest`: 2 tests passed (it can store a transfer with multiple attachments, it can store an increment with multiple attachments) ✅
- **Relation Morph Map Mapping**: Configured in [AppServiceProvider](file:///P:/Project/Web/hrms/app/Providers/AppServiceProvider.php) to store clean string types (`'transfer'`, `'increment'`, etc.) in `attachable_type` column instead of absolute class namespaces.
- **Full Test Suite Integrity**: All 136 tests passed successfully ✅

**Status**: ✅ SUCCESS

## 2026-07-07 (Session 3)

**Goal**: Implement Movement Type CRUD module under Company Setup, add Movement Type relation to Increment, Decrement, Promotion, Demotion, and Transfer modules, and verify via feature testing.

**Exact Command**: `php artisan route:clear && php artisan config:clear && vendor\bin\pest tests/Feature/Payroll/IncrementPromotionPayScaleTest.php tests/Feature/Payroll/DemotionDecrementPayScaleTest.php tests/Feature/Company/MovementTypeTest.php`

**Results**:
- `Tests\Feature\Payroll\IncrementPromotionPayScaleTest`: 3 tests passed ✅
- `Tests\Feature\Payroll\DemotionDecrementPayScaleTest`: 4 tests passed ✅
- `Tests\Feature\Company\MovementTypeTest`: 5 tests passed ✅

**Status**: ✅ SUCCESS

## 2026-07-07 (Session 2)

**Goal**: Create new payroll modules for Demotions and Decrements utilizing subtraction calculations, Axios-based form saving, Pay Scale warnings, and verify them via feature testing.

**Exact Command**: `php artisan route:clear && php artisan config:clear && vendor\bin\pest tests/Feature/Payroll/DemotionDecrementPayScaleTest.php`

**Results**:
- `it can store a new decrement with pay scale`: ✅ PASSED
- `it can store a new demotion with pay scale`: ✅ PASSED
- `it updates employee salary breakdown pay_scale_id upon decrement adjustment`: ✅ PASSED
- `it updates employee salary breakdown pay_scale_id and designation upon demotion adjustment`: ✅ PASSED

**Status**: ✅ SUCCESS

## 2026-07-07 (Session 1)

**Goal**: Implement pay scale integration in employee increments and promotions, add frontend pay scale verification warning if new gross salary surpasses scale limit, and verify changes via feature testing.

**Exact Command**: `php artisan config:clear && vendor\bin\pest tests/Feature/Payroll/IncrementPromotionPayScaleTest.php`

**Results**:
- `it can store a new increment with pay scale`: ✅ PASSED
- `it can store a new promotion with pay scale`: ✅ PASSED
- `it updates employee salary breakdown pay_scale_id upon adjustment`: ✅ PASSED

**Status**: ✅ SUCCESS

## 2026-07-06

**Goal**: Refactor validation across all 9 employee profile sections (General, Office Info, Policy Tag/Plans, Education, Nominee, Salary Breakdown, Bank Accounts, Employment History, Plan Assignment) to use dedicated FormRequest classes. Confirm authorization checks and rules behave identically.

**Exact Command**: `php artisan config:clear && vendor\bin\pest tests/Feature/EmployeeProfileRestrictionTest.php tests/Feature/Employee/ProfileUpdateRequestTest.php`

**Results**:
- `employee user cannot access office information creation`: ✅ PASSED
- `employee user cannot edit eligible plans`: ✅ PASSED
- `employee user cannot access salary breakdown creation`: ✅ PASSED
- `employee user cannot access bank account creation`: ✅ PASSED
- `employee user cannot assign plans`: ✅ PASSED
- `employee user cannot access leave application creation`: ✅ PASSED
- `it can submit general section update request and apply changes upon approval`: ✅ PASSED
- `it can submit education section update request and apply changes upon approval`: ✅ PASSED
- `it can submit employment history update request and apply changes upon approval`: ✅ PASSED
- `it can submit emergency contact nominee update request and apply changes upon approval`: ✅ PASSED
- `it creates admin profile update request for office info update and propagates upon approval`: ✅ PASSED
- `it generates custom notification redirecting to profile_update_requests.show when approval step is created`: ✅ PASSED

**Status**: ✅ SUCCESS

## 2026-07-06 (Request-Service-Controller Refactor)

**Goal**: Refactor the profile field configuration and profile update request logic to follow the Request -> Service -> Thin Controller design pattern, and verify using the ProfileUpdateRequestTest.

**Exact Command**: `php artisan config:clear && php artisan test tests/Feature/Employee/ProfileUpdateRequestTest.php`

**Results**:
- `it can submit general section update request and apply changes upon approval`: ✅ PASSED
- `it can submit education section update request and apply changes upon approval`: ✅ PASSED
- `it can submit employment history update request and apply changes upon approval`: ✅ PASSED
- `it can submit emergency contact nominee update request and apply changes upon approval`: ✅ PASSED
- `it creates admin profile update request for office info update and propagates upon approval`: ✅ PASSED
- `it generates custom notification redirecting to profile_update_requests.show when approval step is created`: ✅ PASSED

**Status**: ✅ SUCCESS

## 2026-07-06 (Decoupled Listeners / Document Updates)

**Goal**: Verify that when a profile update request approval step is created, notifications generated for the workflow (e.g. office-info, bank-accounts, policy-tag, salary-breakdown) correctly redirect to the review page (`profile_update_requests.show`). Also document the workflow events and ApprovalActionController lifecycle sequence diagram inside both the workflow-functional-breakdown markdown and HTML files, secure the controller using transactions and locks for concurrent safety, create the package integration plan in the root directory, update the package, delete the local controller, point route to package controller, implement SweetAlert2 for approval workflow and profile update request deletion, design the decoupled workflow event listener plan in the root directory, implement the decoupled workflow event listeners mapping inside AppServiceProvider, and convert both functional breakdown documents into comprehensive developer tutorials.

**Exact Command**: `php artisan config:clear && vendor/bin/pest tests/Feature/EmployeeDetailedViewTest.php tests/Feature/Employee/ProfileUpdateRequestTest.php`

**Results**:
- `it can submit general section update request and apply changes upon approval`: ✅ PASSED
- `it can submit education section update request and apply changes upon approval`: ✅ PASSED
- `it can submit employment history update request and apply changes upon approval`: ✅ PASSED
- `it can submit emergency contact nominee update request and apply changes upon approval`: ✅ PASSED
- `it creates admin profile update request for office info update and propagates upon approval`: ✅ PASSED
- `it generates custom notification redirecting to profile_update_requests.show when approval step is created`: ✅ PASSED
- `admin can fetch detailed employee profile json`: ✅ PASSED
- `employee can fetch their own detailed profile json`: ✅ PASSED
- `employee cannot fetch other employee detailed profile json`: ✅ PASSED
- `admin can download detailed profile pdf`: ✅ PASSED

Status: ✅ SUCCESS

## 2026-07-05

**Goal**: Implement Admin profile update requests (Office Info, Policy Tag, Salary Breakdown, Bank Account) and verify propagation. Also verify profile detailed view and PDF features after removing Pay Grade from office info. Validate comparison view formatting changes (no horizontal scroll, comma-separated weekends, capitalized yes/no/permanent etc., clean flat list layouts showing all JSON fields dynamically, with database ID keys resolved to titles/names where applicable).

**Exact Command**: `php artisan config:clear && vendor/bin/pest tests/Feature/EmployeeDetailedViewTest.php tests/Feature/Employee/ProfileUpdateRequestTest.php`

**Results**:
- `it can submit general section update request and apply changes upon approval`: ✅ PASSED
- `it can submit education section update request and apply changes upon approval`: ✅ PASSED
- `it can submit employment history update request and apply changes upon approval`: ✅ PASSED
- `it can submit emergency contact nominee update request and apply changes upon approval`: ✅ PASSED
- `it creates admin profile update request for office info update and propagates upon approval`: ✅ PASSED
- `admin can fetch detailed employee profile json`: ✅ PASSED
- `employee can fetch their own detailed profile json`: ✅ PASSED
- `employee cannot fetch other employee detailed profile json`: ✅ PASSED
- `admin can download detailed profile pdf`: ✅ PASSED

**Status**: ✅ SUCCESS

## 2026-05-21

**Goal**: Complete Transfer Module implementation and fix Leave models namespaces.

**Exact Command**: `vendor/bin/pest tests/Feature/TransferModuleTest.php`

**Results**:
- `transfer application can be submitted and completed`: ✅ PASSED
- `Leave models namespace corrected from App\Models\Leave\Leave to App\Models\Leave`: ✅ VERIFIED

**Status**: ✅ SUCCESS

**Goal**: Fix `RouteNotFoundException` and verify route names in `EmployeeProfileRestrictionTest.php`.

**Exact Command**: `vendor/bin/pest tests/Feature/EmployeeProfileRestrictionTest.php`

**Results**:
- `employee user cannot access office information creation`: ✅ PASSED
- `employee user cannot edit eligible plans`: ✅ PASSED
- `employee user cannot access salary breakdown creation`: ✅ PASSED
- `employee user cannot access bank account creation`: ✅ PASSED
- `employee user cannot assign plans`: ✅ PASSED
- `employee user cannot access leave application creation`: ✅ PASSED

**Status**: ✅ SUCCESS

## 2026-05-20 (Employment History Fix)

**Goal**: Fix `Undefined array key "joining_date"` in employment history view and ensure seeder consistency.

**Exact Command**: `$env:DB_CONNECTION='sqlite'; $env:DB_DATABASE=':memory:'; php artisan test tests/Feature/EmployeeEmploymentHistoryViewTest.php`

**Results**:
- `employment history view handles missing joining_date gracefully`: ✅ PASSED
- `employment history view handles correct data correctly`: ✅ PASSED

**Status**: ✅ SUCCESS

## 2026-05-24 (Employee Office Info Fix)

**Goal**: Fix naming inconsistencies for Business Unit, Division, and Section across Models, Views, and Imports. Fix logic bugs in Office Info edit form.

**Exact Command**: `php artisan test tests/Feature/EmployeeOfficeInfoTest.php`

**Results**:
- `it can save and display employee office info correctly`: ✅ PASSED (using `hrms_test` database)
- `it can update employee office info correctly`: ✅ PASSED (using `hrms_test` database)
- `organizational unit naming (unit_name/division_name -> name) verified in profile view`: ✅ VERIFIED

**Status**: ✅ SUCCESS

## 2026-05-24 (Employee Dashboard & Timeline)

**Goal**: Implement an analytical dashboard and career timeline for employees with secure cross-role access.

**Exact Command**: `$env:DB_CONNECTION='mysql'; $env:DB_DATABASE='hrms_test'; php artisan test tests/Feature/EmployeeDashboardTest.php`

**Results**:
- `it calculates employee dashboard statistics correctly`: ✅ PASSED
- `it aggregates timeline events in correct order`: ✅ PASSED
- `it restricts employee dashboard access`: ✅ PASSED

**Status**: ✅ SUCCESS

## 2026-06-01 (NID Verification Module)

**Goal**: Implement NID verification with dummy API, modal UI, and permission-based access control.

**Exact Command**: `php artisan config:clear; $env:DB_CONNECTION='sqlite'; $env:DB_DATABASE=':memory:'; php artisan test tests/Feature/Employee/NIDVerificationTest.php`

**Results**:
- `it allows authorized users to verify NID`: ✅ PASSED
- `it denies NID verification for users without permission`: ✅ PASSED
- `it denies NID verification for Employee user type even with permission`: ✅ PASSED

**Status**: ✅ SUCCESS


## 2026-06-02 (Notification Alert Module)

**Goal**: Implement configurable alert thresholds for birthdays, document expiries, and probation end with automated notifications.

**Exact Command**: `php artisan config:clear; $env:DB_CONNECTION='sqlite'; $env:DB_DATABASE=':memory:'; php artisan test tests/Feature/NotificationAlertTest.php`

**Results**:
- `it triggers birthday notifications for non-employees`: ✅ PASSED
- `it triggers visa expiry notifications for employee and non-employees`: ✅ PASSED
- `it triggers probation end notifications`: ✅ PASSED

**Status**: ✅ SUCCESS

## 2026-06-02 (Notification Alert Module Refactoring)

**Goal**: Refactor Notification Settings to use Axios and return JSON responses, including SweetAlert integration.

**Exact Command**: `php artisan config:clear; $env:DB_CONNECTION='sqlite'; $env:DB_DATABASE=':memory:'; php artisan test tests/Feature/Setting/NotificationSettingControllerTest.php`n
**Results**:
- `it allows authorized users to save notification settings via axios`: ✅ PASSED
- `it validates notification settings data`: ✅ PASSED

**Status**: ✅ SUCCESS

## 2026-06-02 (Notification Alert Enhancements & Bug Fix)

**Goal**: Implement Range Logic for alerts, prevent duplicate notifications, and fix Blade syntax error in settings view.

**Exact Command**: `php artisan config:clear; $env:DB_CONNECTION='sqlite'; $env:DB_DATABASE=':memory:'; php artisan test tests/Feature/NotificationRangeAlertTest.php`n
**Results**:
- `it alerts for expiries within the range`: ✅ PASSED
- `it does not send duplicate notifications for the same expiry cycle`: ✅ PASSED
- `Blade syntax error (InvalidArgumentException)`: ✅ FIXED

**Status**: ✅ SUCCESS

## 2026-06-02 (Employee Reports & Timeline Module)

**Goal**: Implement a comprehensive reporting section for employees and enhance the personal dashboard timeline with age and probation milestones.

**Exact Command**: `php artisan config:clear; $env:DB_CONNECTION='sqlite'; $env:DB_DATABASE=':memory:'; php artisan test tests/Feature/Employee/EmployeeReportTest.php`n
**Results**:
- `it calculates age distribution correctly`: ✅ PASSED
- `it calculates service loyalty correctly`: ✅ PASSED
- `it identifies upcoming birthdays`: ✅ PASSED
- `it loads the reports page for authorized users`: ✅ PASSED

**Status**: ✅ SUCCESS

## 2026-06-02 (Organizational Analytics & Authorization Expansion)

**Goal**: Expand the Analytics module with detailed age analysis, company/hierarchy distribution charts, organizational scoping, and strict permission-based access control.

**Exact Command**: `php artisan config:clear; $env:DB_CONNECTION='sqlite'; $env:DB_DATABASE=':memory:'; php artisan test tests/Feature/Employee/EmployeeReportTest.php`n
**Results**:
- `it calculates detailed age analysis`: ✅ PASSED
- `it calculates company distribution`: ✅ PASSED
- `it denies access to analytics for Employee user type even with permission`: ✅ PASSED
- `All other existing analytics tests`: ✅ PASSED

**Status**: ✅ SUCCESS

## 2026-06-02 (Cascading Filters Update)

**Goal**: Implement live cascading filters for hierarchy analytics, restricting filter UI based on user_type.

**Exact Command**: `php artisan config:clear; $env:DB_CONNECTION='sqlite'; $env:DB_DATABASE=':memory:'; php artisan test tests/Feature/Employee/EmployeeReportTest.php`n
**Results**:
- `it loads the analytics page for authorized users` (with new filter UI logic): ✅ PASSED

**Status**: ✅ SUCCESS

## 2026-06-04 (Pay Group Module)

**Goal**: Implement the Pay Group management module with an API-first approach, dynamic forms, and organizational scoping.

**Exact Command**: `php artisan config:clear; php artisan route:clear; $env:DB_CONNECTION='mysql'; $env:DB_DATABASE='hrms_test'; php artisan test tests/Feature/Company/PayGroupTest.php`n
**Results**:
- `it can list pay groups via ajax`: ✅ PASSED
- `it can store a new pay group`: ✅ PASSED
- `it can update a pay group`: ✅ PASSED
- `it can delete a pay group`: ✅ PASSED
**Status**: ✅ SUCCESS

## 2026-06-08 (PayScale Deletion Fix)

**Goal**: Fix issue where PayScales couldn't be deleted after deleting the related PayGroup.

**Exact Command**: `php artisan test tests/Feature/Company/PayGroupCascadeDeleteTest.php --env=testing`

**Results**:
- `it deletes related pay scales when pay group is deleted`: ✅ PASSED
- `it allows deleting orphaned pay scales`: ✅ PASSED
- `it prevents deleting pay scale if used by employee`: ✅ PASSED
- `Fixed PayScales list view crash on null relationships`: ✅ VERIFIED

## 2026-06-08 (Salary Breakdown Validation Hardening)

**Goal**: Ensure it is strictly impossible to submit a salary breakdown < 100% total, preventing fast-click bypasses and bypassing via enter key.

**Exact Command**: `Manual UI Verification`

**Results**:
- `Submit button disabled by default on load`: ✅ VERIFIED
- `Pre-populate Footer summary blocks using server data`: ✅ VERIFIED
- `Recalculate total immediately on form submit event`: ✅ VERIFIED
- `Backend validation final defense check added`: ✅ VERIFIED

**Status**: ✅ SUCCESS

## 2026-06-09 (Advanced Salary Generation)

**Goal**: Require Pay Group selection, handle Hourly and Daily frequency calculations, prevent duplicates, and deduct approved penalties during salary processing.

**Exact Command**: `php artisan config:clear && vendor/bin/pest tests\Feature\PayrollAdvancedSalaryTest.php`

**Results**:
- `salary process correctly calculates hourly frequency and deducts penalties`: ✅ PASSED
- `salary process correctly calculates daily frequency`: ✅ PASSED

**Status**: ✅ SUCCESS

**Goal**: Fix `Undefined variable $levelWeight` in `transfer/application.blade.php` and resolve `UserType` enum mismatches in `TransferServices` and tests.

**Exact Command**: `php artisan config:clear && vendor/bin/pest tests/Feature/TransferModuleTest.php`

**Results**:
- `transfer application can be submitted and completed`: ✅ PASSED
- `it restricts transfer logs based on organizational scope`: ✅ PASSED
- `transfer application view loads correctly`: ✅ PASSED (Fixed `Undefined variable $levelWeight`)
- `UserType enum mismatches in TransferServices`: ✅ FIXED

**Status**: ✅ SUCCESS

## 2026-06-15 (Payroll Department Fetching Fix)

**Goal**: Fix the department fetching issue in payroll process details and improve eager loading of organizational relationships.

**Exact Command**: `php artisan config:clear && vendor/bin/pest tests\Feature\PayrollDepartmentTest.php`

**Results**:
- `payroll process search result eager loads department and uses correct attribute`: ✅ PASSED
- `Department attribute corrected from 'name' to 'department_name' in partials`: ✅ VERIFIED
- `Department column added to Eligible Employees and Payroll Details views`: ✅ VERIFIED

**Status**: ✅ SUCCESS
| 2026-06-28 | Added Employee Lifecycle tracking events | php artisan test tests/Feature/Employee/EmployeeLifecycleTest.php | 3 passed | ✅ SUCCESS |
| 2026-06-28 | Added joined event tracking | php artisan test tests/Feature/Employee/EmployeeLifecycleTest.php | 3 passed | ✅ SUCCESS |
| 2026-06-28 | Added Employee Document Management | php artisan test tests/Feature/Employee/EmployeeDocumentTest.php | 2 passed | ✅ SUCCESS |

## 2026-07-01 (Approval Workflow Engine Integration for Payroll)

**Goal**: Apply the approval workflow to the salary generate and bonus process, ensuring no crashes when startWorkflow is called, and replacing the manual static buttons.

**Exact Command**: `php artisan config:clear && php artisan test` (alongside manual tinker verification)

**Results**:
- `startWorkflow()` calls correctly replaced with `app(\Innovity\ApprovalEngine\Services\WorkflowGenerator::class)->generate($process, 'module')` to fix 'Call to undefined method' errors.
- `Approvable` trait integrated correctly into `PayrollProcess`.
- `WorkflowStatusListener` handles 'salary' and 'bonus' module state transitions.

**Status**: ✅ SUCCESS

## 2026-07-02 (Dynamic Step Types in Approval Workflows)

**Goal**: Implement and verify 3 dynamic step types ('user-type', 'role-user', 'specific-user') in the Approval Workflow Engine, including custom schema columns, updated ApproverResolver logic, Spatie Role/User validation, and builder UI views.

**Exact Command**: `php artisan config:clear && vendor/bin/pest tests/Feature/Setting/ApprovalWorkflowTest.php`

**Results**:
- `it can store a sequential approval workflow with dynamic steps`: ✅ PASSED
- `it resolves steps using the approver resolver`: ✅ PASSED

**Status**: ✅ SUCCESS

## 2026-07-02 (Comprehensive Profile Update Request Forms and Propagation)

**Goal**: Enhance the profile update request creation modal to support all section fields and custom JSON list managers (Add More/Remove for Educations and Histories) and implement the background workflow completion data propagation listener to auto-update actual employee profile tables.

**Exact Command**: `php artisan config:clear && vendor/bin/pest tests/Feature/Employee/ProfileUpdateRequestTest.php`

**Results**:
- `it can submit general section update request and apply changes upon approval`: ✅ PASSED
- `it can submit education section update request and apply changes upon approval`: ✅ PASSED
- `it can submit employment history update request and apply changes upon approval`: ✅ PASSED
- `it can submit emergency contact nominee update request and apply changes upon approval`: ✅ PASSED

**Status**: ✅ SUCCESS

## 2026-07-07 (MySQL Testing Environment Setup and Verification)

**Goal**: Configure the Pest testing environment to target the MySQL `hrms_test` database (on port 3307) rather than SQLite `:memory:`, revert the SQLite-specific driver logic, and verify the sequential test run passes successfully.

**Exact Command**: `php artisan config:clear && php artisan test`

**Results**:
- `All 134 feature and unit tests`: ✅ PASSED (fully verified on the MySQL `hrms_test` database, with sequential process execution preventing concurrency lockouts).
- Reverted all SQLite-specific compatibility checks in [CheckAlerts.php](file:///P:/Project/Web/hrms/app/Console/Commands/CheckAlerts.php) and [EmployeeReportServices.php](file:///P:/Project/Web/hrms/app/Services/Employee/EmployeeReportServices.php).

**Status**: ✅ SUCCESS

## 2026-07-16 (Route Map UI Dark Mode Support)

**Goal**: Implement dark mode compatibility for the horizontal route stepper timeline on the Route Maps index page.

**Exact Command**: `php artisan config:clear && vendor/bin/pest tests/Feature/TransportRouteTest.php`

**Results**:
- `vehicle create route is accessible`: ✅ PASSED
- `vehicle show route is accessible`: ✅ PASSED
- `vehicle driver create route is accessible`: ✅ PASSED
- `route map routes are accessible`: ✅ PASSED

**Status**: ✅ SUCCESS

## 2026-07-16 (Route Map UI Show All Via Points)

**Goal**: Render all stopover via points individually on the horizontal route stepper instead of collapsing them, and calculate connecting line offsets dynamically.

**Exact Command**: `php artisan config:clear && vendor/bin/pest tests/Feature/TransportRouteTest.php`

**Results**:
- `vehicle create route is accessible`: ✅ PASSED
- `vehicle show route is accessible`: ✅ PASSED
- `vehicle driver create route is accessible`: ✅ PASSED
- `route map routes are accessible`: ✅ PASSED

**Status**: ✅ SUCCESS

## 2026-07-17 (Route Map Serpentine / Zig-Zag Layout)

**Goal**: Render route map steps in a serpentine layout (4 per row) with dynamic horizontal and vertical connectors and zig-zag flow (flex-row-reverse for odd rows).

**Exact Command**: `php artisan config:clear && vendor/bin/pest tests/Feature/TransportRouteTest.php`

**Results**:
- `vehicle create route is accessible`: ✅ PASSED
- `vehicle show route is accessible`: ✅ PASSED
- `vehicle driver create route is accessible`: ✅ PASSED
- `route map routes are accessible`: ✅ PASSED

**Status**: ✅ SUCCESS

## 2026-07-17 (Employee Transport Form Route Preview)

**Goal**: Embed instantaneous serpentine route map preview inside the Employee Transport application form upon selecting a route map.

**Exact Command**: `php artisan config:clear && vendor/bin/pest tests/Feature/TransportRouteTest.php`

**Results**:
- `vehicle create route is accessible`: ✅ PASSED
- `vehicle show route is accessible`: ✅ PASSED
- `vehicle driver create route is accessible`: ✅ PASSED
- `route map routes are accessible`: ✅ PASSED

**Status**: ✅ SUCCESS

## 2026-07-17 (Transport Action Buttons Alignment & Nullable End Date)

**Goal**: Align action buttons layout style in all transport submodules to `d-flex justify-content-center gap-1`, make `end_date` nullable in database schema and validator, and add validation feedback styling across all form controls.

**Exact Command**: `php artisan config:clear && vendor/bin/pest tests/Feature/TransportRouteTest.php`

**Results**:
- `vehicle create route is accessible`: ✅ PASSED
- `vehicle show route is accessible`: ✅ PASSED
- `vehicle driver create route is accessible`: ✅ PASSED
- `route map routes are accessible`: ✅ PASSED

**Status**: ✅ SUCCESS








