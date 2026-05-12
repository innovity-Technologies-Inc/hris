# Coding Style Guidelines

## 🔄 Workflow Requirements
- **Post-Task Optimization**: Every task execution MUST be followed by `php artisan optimize` to ensure the application state is fresh.
- **Mandatory Commits**: Every completed directive MUST be committed to source control immediately.

## 🏗️ Architectural Framework: Request-Service-Controller Pattern
This project strictly follows a Service-Oriented Architecture (SOA). Every feature MUST adhere to the following data flow:
1. **Form Requests**: All incoming data validation MUST be handled by dedicated Laravel Form Request classes (`App\Http\Requests`).
2. **Thin Controllers**: Controllers MUST remain thin. Their only responsibilities are:
    - Injecting the necessary Service classes.
    - Calling service methods.
    - Returning responses (views or JSON).
3. **Service Classes**: All business logic, database calculations, and complex workflows MUST reside in `App\Services`.
    - Naming: `{Module}Services.php` (e.g., `EmployeeServices.php`).
    - Injection: Services should be injected into controllers via the constructor.
4. **Eloquent Models**: Use `App\Models` for database interactions and relationships.

## 💻 Coding Best Practices
- **Type Hinting**: Use PHP 8.2+ strict typing for all method arguments and return types.
- **Naming Conventions**:
    - Services: `{Module}Services.php`.
    - Controllers: `{Module}Controller.php`.
    - Imports: `{Module}Import.php`.
    - Blade Files: `snake_case` (e.g., `attendance_form.blade.php`).
- **Filtering & Searching**: Use the `daiyanmozumder/laravel-flexsearch` package for all table filtering and searching.
- **Error Handling**: Use try-catch blocks within Service classes and log errors using Laravel's `Log` facade.
- **Data Integration**: Use `maatwebsite/excel` for bulk imports/exports.
- **AJAX Interactions**: Use a central `DataController` pattern for dynamic dropdowns (e.g., fetching departments based on company).

## 📦 Key Packages
- `daiyanmozumder/laravel-flexsearch`: Core filtering engine.
- `daiyan_mozumder/flex-db-dump`: Database utility.
- `endroid/qr-code`: QR code generation.
- `spatie/browsershot`: PDF/Screenshot generation.
- `maatwebsite/excel`: Excel imports/exports.
