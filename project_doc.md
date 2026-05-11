# HRMS Project Documentation

## 🚀 Overview
This is a robust Human Resource Management System (HRMS) built with Laravel 12. It follows a Service-Oriented Architecture (SOA) to manage complex HR workflows, including organizational management, employee lifecycle, payroll processing, and transport services.

---

## 🏗️ Architectural Framework

### 1. Service-Oriented Architecture (SOA)
- **Logic Placement**: Business logic is centralized in `App\Services`.
- **Thin Controllers**: Controllers in `App\Http\Controllers` handle request routing and call service methods.
- **Data Access**: Eloquent models in `App\Models` handle database interactions.

### 2. Filtering & Searching (FlexSearch)
- **Package**: `daiyanmozumder/laravel-flexsearch`.
- **Implementation**: Used for dynamic, high-performance filtering across all modules (Employees, Attendance, etc.).
- **Consistency**: All searchable tables follow the FlexSearch pattern for a unified user experience.

### 3. Data Integration
- **Imports**: Bulk data handling via `maatwebsite/excel`. Import classes are located in `App\Imports`.
- **AJAX Driven**: High interactivity using a `DataController` to provide dynamic dropdown data (e.g., getting departments based on company selection).

---

## 📦 Completed Modules

### 🏢 Company & Organization Setup
- **Levels**: Group -> Company -> Division -> Department -> Section.
- **Structure**: Manage organizational hierarchy and locations.
- **Support Data**: Banks, Branches, Salary Grades, Tofsils (Acts), and Gazette Locations.

### 👥 Employee Management
- **Profile**: Comprehensive general and office information.
- **Lifecycle**: Manage Nominees, Education/Experience, Bank Accounts, and Salary Breakdowns.
- **Status**: Toggle employment status and manage movements (transfers).
- **ID Cards**: Dynamic ID card generation with QR codes using `IDCardService` and `QrCodeService`.

### 📅 Plans & Policy Management
Dedicated sub-system to define various HR policies:
- **Attendance/Shift**: Shift Plans and Roster Plans.
- **Benefits**: Allowance, Bonus, and DA (Daily Allowance) Plans.
- **Time-off**: Leave Plans and Off-Day Plans.
- **Miscellaneous**: Meal Plans and TA (Travel Allowance) Plans.

### ⏱️ Attendance & Leaves
- **Attendance**: Clock-in/out records, bulk imports, and detailed reporting.
- **Leaves**: Leave application, approval workflows, and balance tracking.

### 💰 Payroll & Benefits
- **Salary Processing**: Automated salary calculation and breakdown.
- **Salary Process Eligibility View**: A dedicated view to track and audit eligible employees for each salary process batch, accessible via the main salary process index.
- **Individual Payroll Detail View**: Detailed breakdown of earnings (Gross, OT, Bonus, Off-day) and deductions for each employee within a batch.
- **Industry Standard PDF Payslip**: Professional PDF generation of payslips with company branding, employee details, and clear financial breakdown using `Spatie Browsershot`.
- **Salary Certificate Generation**: Standard "To Whom It May Concern" salary certificate generation for employees, including tenure and remuneration details.
- **Adjustments**: Handle Promotions, Increments, and Bonus distributions.
- **Structure**: Based on Salary Grades and Employee-specific breakdown.

### 🚐 Transport Module
- **Assets**: Vehicle and Driver management.
- **Operations**: Requisitions (applications), approvals, and allocations.
- **Tracking**: History of vehicle assignments and driver activities.

### 🔐 User Management & RBAC
- **Authentication**: Integrated via Laravel Breeze (Bootstrap/Blade).
- **Permissions**: powered by `spatie/laravel-permission` with menu-wise granularity (Create, Edit, View, Delete).
- **Integration**: Automatic bi-directional linking between `User` and `Employee` models.
- **Scoping**: Organization-based data visibility restriction (Group, Company, Division, Department, Section, Employee).

---

## 🛡️ Data Isolation & Organizational Scoping

The system implements a rigorous **Row-Level Security (RLS)** strategy to ensure data privacy and multi-tenant integrity. This is handled automatically at the database level using Laravel Global Scopes.

### 1. The `OrganizationScoped` Trait
The core of the isolation engine is the `App\Traits\OrganizationScoped` trait. When added to any Eloquent model, it automatically intercepts all database queries (`SELECT`, `UPDATE`, `DELETE`) to inject security filters based on the authenticated user's context.

### 2. User Access Levels (`user_type`)
Data visibility is determined by the `user_type` field in the `users` table:

| User Type | Scope Coverage | Technical Logic |
| :--- | :--- | :--- |
| **Group** | Global | No filters applied. Can see all data across all companies. |
| **Company** | Single Company | Filters by `company_id` or `current_company_id`. |
| **Business Unit / Branch** | Physical Location | Filters by `business_unit_id`, `location_id`, or `branch_id`. |
| **Division** | Single Division | Filters by `division_id` or `current_division_id`. |
| **Department** | Single Department | Filters by `department_id` or `current_department_id`. |
| **Section** | Single Section | Filters by `section_id` or `current_section_id`. |
| **Employee** | Personal Data | Restricted to records where `employee_id` matches their own. |

### 3. Smart Filtering Mechanism
The trait is designed to be "schema-aware" and handles three levels of data retrieval:

*   **Direct Filtering**: If the table contains organizational columns (e.g., `company_id`), it applies a direct `WHERE` clause.
*   **Alias Handling**: It automatically detects column variations. For example, a **Business Unit** admin will be filtered using `business_unit_id`, `branch_id`, or `location_id` depending on what exists in the table.
*   **Relationship Hopping**: If a table (like `Leaves` or `Attendance`) only has an `employee_id`, the trait "hops" to the `EmployeeOfficeInfo` table to verify the employee's current organizational unit before returning the data.

### 4. Implementation Guidelines
- **Automatic Protection**: Once `use OrganizationScoped;` is added to a model, security is "always on."
- **Developer Bypass**: In rare cases where a global query is needed (e.g., system-wide analytics), developers must explicitly use `Model::withoutGlobalScopes()`.
- **Recursion Safety**: The trait uses internal static caching and `withoutGlobalScopes()` during context resolution to prevent infinite loops during the boot process.

### 5. Practical Example: The "Invisible Filter" in Action

**Scenario**: 
A user named **John** is a **Department Manager** for the "Finance" department (ID: 10). He navigates to the "Monthly Attendance" page.

**The Code**:
In the Controller, the developer simply writes:
```php
$records = Attendance::where('month', '2026-05')->get();
```

**What Happens Behind the Scenes**:
1.  **Identity Detection**: The `OrganizationScoped` trait detects that John is logged in and has the `user_type` of **'Department'**.
2.  **Context Resolution**: The trait looks up John's employee profile (using static cache for speed) and finds his `current_department_id` is **10**.
3.  **Schema Check**: The trait checks the `attendance` table. It sees there is no `department_id` column, but there is an `employee_id` column.
4.  **Query Injection**: The trait automatically modifies the query before it is sent to MySQL.
5.  **Final SQL**:
    ```sql
    SELECT * FROM attendance 
    WHERE month = '2026-05' 
    AND EXISTS (
        SELECT 1 FROM employee_office_infos 
        WHERE employee_office_infos.employee_id = attendance.employee_id 
        AND employee_office_infos.current_department_id = 10
    )
    ```

**Outcome**: John only sees the attendance records for employees in the Finance department. He didn't have to write the filter, and the developer didn't have to remember to add it. Security is enforced by default.

---

## 🎨 Design & Coding Guidelines

### UI/UX Standards
- **Framework**: Bootstrap 5.
- **Design Style**: Modern, clean aesthetic with "Glassmorphism" elements.
- **Dark Mode**: Fully supported via `[data-bs-theme=dark]`.
- **Layout**: Centered around `resources/views/structure/master.blade.php`.
- **Interactive Feedback**: Use of standard CSS transitions and hover effects (as seen in dashboard cards).

### Coding Best Practices
- **Type Safety**: Use PHP 8.2+ strict typing.
- **Service Injection**: Inject services into controllers via the constructor.
- **Naming**: 
    - Services: `{Module}Services.php`
    - Controllers: `{Module}Controller.php`
    - Blade Files: `snake_case` (e.g., `attendance_form.blade.php`)
- **Modularity**: Keep modules independent but connected via well-defined relationships in Models.

---

## 🔗 How Modules Connect
1. **Organizational Core**: All modules (Employee, Payroll, Attendance) depend on the `Company -> Division -> Section` hierarchy.
2. **Employee Hub**: The `Employee` model is the central entity. Plans are assigned to employees, who then generate attendance and payroll data.
3. **Plan Application**: Plans define the "rules," and the processing engines (Attendance/Payroll Services) apply these rules to the Employee data.
