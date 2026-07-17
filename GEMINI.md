# Gemini Instructions - HRMS Project

This project is a comprehensive Human Resource Management System (HRMS) built with Laravel 12. Adhere to the following architectural and coding standards.

## 🚀 Post-Task Mandates
- **Always Commit**: After completing a task, you MUST commit the changes with a descriptive message.
- **Optimization**: After any code or configuration change, you MUST run `php artisan optimize` to clear and rebuild caches.
- **CRITICAL**: NEVER run tests while the configuration is cached to the primary `hrms` database. Always run `php artisan config:clear` before testing.

## 🏗️ Architectural Overview
- **Framework**: Laravel 12.
- **Pattern**: Strict Request -> Service -> API Controller structure.
- **API-First Development**: All new features must implement API endpoints in controllers that return **JSON responses** conforming to a standardized structure.
  - **Standard Success JSON Structure**:
    - Creation (201 Created): `{"success": true, "message": "Resource created successfully.", "data": {...}}`
    - Update/Action (200 OK): `{"success": true, "message": "Resource updated successfully.", "data": {...}}`
    - Deletion/Release/Deactivation (200 OK): `{"success": true, "message": "Resource deleted/released/deactivated successfully."}`
  - **Standard Error JSON Structure**:
    - Validation Fails (422 Unprocessable Entity): Conforms to default Laravel validation error structure: `{"message": "The given data was invalid.", "errors": {"field": ["Error message"]}}`
    - Action Error (400 Bad Request, 403 Forbidden, 500 Internal Server Error, etc.): `{"success": false, "message": "Error message details here."}`
- **Frontend Interaction**: 
    - Use **Blade templates** for the UI structure.
    - Use **Axios** and **Vanilla JavaScript** for all data fetching and asynchronous interactions with the API controllers.
- **Business Logic**: Always place complex logic in `App\Services`. Controllers should remain thin, primarily handling service delegation and JSON response formatting.
- **Filtering & Searching**: Use the `daiyanmozumder/laravel-flexsearch` package for all table filtering and searching.
- **Models**: Use Eloquent models located in `App\Models`. Use relationships extensively for organizational data.

## 📁 Modular Organization
- **Directory Structure**: For every module, maintain dedicated subdirectories across all layers to ensure clean separation of concerns:
    - `App\Http\Controllers\{Module}`
    - `App\Http\Requests\{Module}`
    - `App\Services\{Module}`
    - `App\Models\{Module}`
    - `App\Imports\{Module}`
    - `App\Notifications\{Module}`
    - `App\Mail\{Module}`
    - `resources\views\{module}`

## 🎨 Design & UI Standards
- **Layout**: The master layout is located at `resources/views/structure/master.blade.php`.
- **Styling**: 
    - Use **Bootstrap 5** for the core UI.
    - Follow the existing "Glassmorphism" and "Modern-Clean" aesthetic seen in `dashboard.blade.php`.
    - Support for Dark Mode is mandatory (using `[data-bs-theme=dark]`).
    - Use CSS variables for colors (e.g., `--primary-color`, `--bs-dashboard-accent`).
- **Icons**: Use FontAwesome or Bootstrap Icons as per existing patterns.
- **Responsiveness**: Ensure all new views are mobile-friendly using Bootstrap's grid system.

## 💻 Coding Guidelines
- **Type Hinting**: Use PHP 8.2+ type hinting for all method arguments and return types.
- **Naming Conventions**:
    - Services: `NameServices.php` (e.g., `EmployeeServices.php`).
    - Controllers: `NameController.php`.
    - Imports: `NameImport.php`.
- **Validation**: ALWAYS use dedicated Request classes for validation.
- **Search Implementation**: Refer to `EmployeeSearchController.php` for standard FlexSearch implementation patterns.
- **Error Handling**: Use try-catch blocks in services and log errors when necessary.
- **Model Scoping & Workflows**: 
    - Every model that maps to employee records or has branch/company/section context limitations MUST use the `App\Traits\OrganizationScoped` trait to ensure query safety.
    - **Nullable Target Scopes**: If a model requires nullable targeting (where null values represent global items visible to all companies/branches), define `public $allowNullableOrgScope = true;` on the model using the `OrganizationScoped` trait.
    - Every model requiring approval workflows MUST implement the `\Innovity\ApprovalEngine\Traits\Approvable` trait.
    - **Includers & Excluders**: The central approval workflow engine supports multi-value criteria matching. Includers define which roles, user types, or specific users require approval (creating pending requests), and Excluders define which roles, user types, or users bypass approval.
    - **Announcements**: Notice boards and broadcasts are managed in the Announcement module, with target audiences loaded dynamically via 5-tier cascading dropdowns (Company, Branch, Division, Department, Section) and downloadable as styled PDFs.

## 📦 Key Packages
- `daiyanmozumder/laravel-flexsearch`: Core filtering engine.
- `daiyan_mozumder/flex-db-dump`: Database utility.
- `endroid/qr-code`: QR code generation (see `QrCodeService`).
- `spatie/browsershot`: PDF/Screenshot generation (see `IDCardService`).
- `maatwebsite/excel`: Excel imports/exports.

## 🧪 Testing & Verification Standards
Refer to [.ai/guidelines/testing-guidelines.md](.ai/guidelines/testing-guidelines.md) for full details.
- **Framework**: Use **Pest** for all new tests.
- **Environment**: Use **MySQL test database** (`hrms_test`) for testing to ensure speed and isolation.
- **Mandatory Logging**: Every time a test is requested or executed, you **MUST** update `TEST_LOG.md` with the following details:
    - Date of execution.
    - The original instruction/goal.
    - The exact command used to run the tests.
    - Detailed results (passed/failed counts).
    - Status (✅ SUCCESS or ❌ FAILED).
- **Validation**: A task is only considered complete once its behavior is verified by a passing test and documented in `TEST_LOG.md`.

## 📋 Leave Module Refactoring & Recent Tasks

The Leave Module has been updated with API-first architectures, strict type validation, responsive calendar components, and year-scoped balance computations:

### 1. Architectural & Validation Updates
* **API-First Refactoring**: Converted `LeavePlanController` to inherit thin-controller architectures, delegating validations to [StoreLeavePlanRequest.php](file:///P:/Project/Web/hrms/app/Http/Requests/Plan/StoreLeavePlanRequest.php) and [UpdateLeavePlanRequest.php](file:///P:/Project/Web/hrms/app/Http/Requests/Plan/UpdateLeavePlanRequest.php) and returning standardized success/error JSON response payloads.
* **Axios Form Interceptors**: Updated Leave Plan create/edit forms to submit dynamically via Axios. Handled validation constraint exceptions (status `422`) inline on client interfaces, showing error feedback dynamically.
* **Axios & SweetAlert Delete**: Integrated a dynamic JQuery delegated handler to run confirmations via SweetAlert2 and dispatch delete requests via Axios, refreshing grids on completion.

### 2. Schema, Scoping, and Filtering Updates
* **Removal of `day_type`**: Deprecated and removed the `day_type` attribute across migrations, fillable model arrays, validations, imports, and blade templates.
* **`off_day_include` enum**: Converted the column type from integer (`0`/`1`) to `enum('yes', 'no')` via database migrations, updating imports, validators, view panels, and dropdown selections accordingly.
* **Running Calendar Year Scoping**: Configured leave taken calculations to scope dynamically to the running calendar year, validating and subtracting usage against the current year limit, and querying the database in `DataController` and `LeavesController`.
* **Gender-Based Filtering**: Integrated gender scopes in `plansView` of `EmployeePlansController` to ensure employees only view active leave plans assigned to `'Both'` or matching their specific gender.

### 3. Interactive Leave Calendar
* Added a **Leave Calendar** tab inside the employee profile leave info section. 
* Implemented interactive filter dropdowns for Month and Year.
* Built a dynamic calendar days grid using Vanilla Javascript.
* Displays approved, pending, and rejected leave request periods directly on calendar cells with interactive tooltips and status-color themes, compatible with dark mode.

