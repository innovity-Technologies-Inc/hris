# Gemini Instructions - HRMS Project

This project is a comprehensive Human Resource Management System (HRMS) built with Laravel 12. Adhere to the following architectural and coding standards.

## 🏗️ Architectural Overview
- **Framework**: Laravel 12.
- **Pattern**: Service-Oriented Architecture (SOA).
- **Business Logic**: Always place complex logic in `App\Services`. Controllers should remain thin, primarily handling request validation and response delegation.
- **Filtering & Searching**: Use the `daiyanmozumder/laravel-flexsearch` package for all table filtering and searching.
- **Models**: Use Eloquent models located in `App\Models`. Use relationships extensively for organizational data (Company -> Division -> Department -> Section).
- **Data Import**: Uses `maatwebsite/excel` with dedicated import classes in `App\Imports`.

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
- **Validation**: Use Request classes or inline validation in controllers.
- **Search Implementation**: Refer to `EmployeeSearchController.php` for standard FlexSearch implementation patterns.
- **Error Handling**: Use try-catch blocks in services and log errors when necessary.

## 📦 Key Packages
- `daiyanmozumder/laravel-flexsearch`: Core filtering engine.
- `daiyan_mozumder/flex-db-dump`: Database utility.
- `endroid/qr-code`: QR code generation (see `QrCodeService`).
- `spatie/browsershot`: PDF/Screenshot generation (see `IDCardService`).
- `maatwebsite/excel`: Excel imports/exports.

## 🛠️ Workflow
1. **Model & Migration**: Create the database structure first.
2. **Service**: Implement the business logic in a dedicated service.
3. **Import (if needed)**: Create an Import class for bulk data.
4. **Controller**: Create the controller and inject the service.
5. **Route**: Define routes in `web.php`.
6. **View**: Create the blade file following the established design style.
