# Coding Style Guidelines

## 🔄 Workflow Requirements
- **Post-Task Optimization**: Every task execution MUST be followed by `php artisan optimize` to ensure the application state is fresh.
- **Mandatory Commits**: Every completed directive MUST be committed to source control immediately.

## 🏗️ Architectural Framework: Request-Service-Controller Pattern
This project strictly follows a Service-Oriented Architecture (SOA) with an API-first approach. Every feature MUST adhere to the following data flow:
1. **Form Requests**: All incoming data validation MUST be handled by dedicated Laravel Form Request classes (`App\Http\Requests\{Module}`).
2. **API Controllers**: Controllers MUST act as API endpoints and remain thin. Their responsibilities are:
    - Injecting the necessary Service classes.
    - Calling service methods.
    - Returning **JSON responses** with appropriate status codes.
3. **Service Classes**: All business logic, database calculations, and complex workflows MUST reside in `App\Services\{Module}`.
    - Naming: `{Module}Services.php` (e.g., `EmployeeServices.php`).
    - Injection: Services should be injected into controllers via the constructor or method injection.
4. **Eloquent Models**: Use `App\Models\{Module}` for database interactions and relationships.

## 🌐 Frontend & API Interaction
- **Blade Templates**: Use Blade for the base layout and initial page load.
- **Asynchronous Operations**: All data fetching, form submissions, and dynamic updates MUST be handled using:
    - **Axios**: For all HTTP requests to the API controllers.
    - **Vanilla JavaScript**: For DOM manipulation and event handling (avoid complex frontend frameworks unless specified).
- **JSON Standard**: Controllers must return a standardized JSON structure:
  ```json
  {
    "success": true,
    "message": "Action completed successfully",
    "data": { ... },
    "errors": [ ... ]
  }
  ```

## 📁 Modular Directory Enforcement
For every new module or major feature, maintain strict directory separation across all layers:
- `app/Http/Controllers/{Module}/`
- `app/Http/Requests/{Module}/`
- `app/Services/{Module}/`
- `app/Models/{Module}/`
- `app/Imports/{Module}/`
- `app/Notifications/{Module}/`
- `app/Mail/{Module}/`
- `resources/views/{module}/`

## 💻 Coding Best Practices
- **Type Hinting**: Use PHP 8.2+ strict typing for all method arguments and return types.
- **Naming Conventions**:
    - Services: `{Module}Services.php`.
    - Controllers: `{Module}Controller.php`.
    - Imports: `{Module}Import.php`.
    - Blade Files: `snake_case`.
- **Filtering & Searching**: Use `daiyanmozumder/laravel-flexsearch`.
- **Error Handling**: Use try-catch blocks within Service classes and log errors using Laravel's `Log` facade.

## 📦 Key Packages
- `daiyanmozumder/laravel-flexsearch`: Core filtering engine.
- `daiyan_mozumder/flex-db-dump`: Database utility.
- `endroid/qr-code`: QR code generation.
- `spatie/browsershot`: PDF/Screenshot generation.
- `maatwebsite/excel`: Excel imports/exports.
