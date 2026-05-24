# Gemini Instructions - HRMS Project

This project is a comprehensive Human Resource Management System (HRMS) built with Laravel 12. Adhere to the following architectural and coding standards.

## 🚀 Post-Task Mandates
- **Always Commit**: After completing a task, you MUST commit the changes with a descriptive message.
- **Optimization**: After any code or configuration change, you MUST run `php artisan optimize` to clear and rebuild caches.
- **CRITICAL**: NEVER run tests while the configuration is cached to the primary `hrms` database. Always run `php artisan config:clear` before testing.

## 🏗️ Architectural Overview
- **Framework**: Laravel 12.
- **Pattern**: Strict Request -> Service -> API Controller structure.
- **API-First Development**: All new features must implement API endpoints in controllers that return **JSON responses**. 
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

## 📦 Key Packages
- `daiyanmozumder/laravel-flexsearch`: Core filtering engine.
- `daiyan_mozumder/flex-db-dump`: Database utility.
- `endroid/qr-code`: QR code generation (see `QrCodeService`).
- `spatie/browsershot`: PDF/Screenshot generation (see `IDCardService`).
- `maatwebsite/excel`: Excel imports/exports.

## 🧪 Testing & Verification Standards
Refer to [.ai/guidelines/testing-guidelines.md](.ai/guidelines/testing-guidelines.md) for full details.
- **Framework**: Use **Pest** for all new tests.
- **Environment**: Use **SQLite in-memory** (`:memory:`) for testing to ensure speed and isolation.
- **Mandatory Logging**: Every time a test is requested or executed, you **MUST** update `TEST_LOG.md` with the following details:
    - Date of execution.
    - The original instruction/goal.
    - The exact command used to run the tests.
    - Detailed results (passed/failed counts).
    - Status (✅ SUCCESS or ❌ FAILED).
- **Validation**: A task is only considered complete once its behavior is verified by a passing test and documented in `TEST_LOG.md`.
