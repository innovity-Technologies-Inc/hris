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
- **Profile Review System**:
    - **Workflow**: New employee profiles start as `incomplete`, move to `pending` upon submission, and finally to `active` after HR approval.
    - **Review Dashboard**: A dedicated interface for HR to audit pending profiles.
    - **Approval/Rejection**: HR can mark profiles as `incomplete` with a specific cause (triggering a feedback loop) or `active`.
    - **Email Feedback**: Automated emails are dispatched to employees notifying them of their profile's review outcome (Approval or Incomplete with cause).

### 🔔 Hierarchical Notification System
A custom real-time notification engine with intelligent visibility rules based on the organizational chart.
- **Infrastructure**: Custom `Notification` model and table.
- **Triggers**: Automated creation during key events (e.g., profile submission, review completion).
- **Visibility Levels**:
    - **Direct**: Employees see notifications assigned to their specific User ID.
    - **HR/Group**: Global access to administrative notifications (e.g., "New Profile for Review").
    - **Supervisor Hierarchy**: Intelligent upstream visibility. A notification targeted at a specific user type (e.g., Section) is automatically visible to the user at the next level up (e.g., Department) within the same organizational branch.
- **User Type Hierarchy**: `Employee` -> `Section` -> `Department` -> `Division` -> `Business Unit` -> `Company` -> `Group`.

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

---

## 🔐 Security & Access Control

### 1. RBAC (Role-Based Access Control)
- **Engine**: `spatie/laravel-permission`.
- **Granularity**: Permissions are mapped to menus and specific actions (Create, Edit, View, Delete, Import).

### 2. Employee Security Overrides
To ensure data integrity and prevent self-tampering, strict "hard overrides" are applied to the `Employee` user type, regardless of their assigned RBAC roles:
- **Restricted Modules**: Employees are strictly blocked from **Creating** or **Editing** their own (or others') Office Information, Policy Tags (Eligible Plans), Salary Breakdowns, Bank Accounts, Work Plans, and Leave History.
- **Technical Enforcement**: These restrictions are enforced at both the UI level (hidden buttons) and the Backend level (Controller-level 403 aborts).

### 3. Data Isolation & Organizational Scoping
The system implements a rigorous **Row-Level Security (RLS)** strategy to ensure data privacy and multi-tenant integrity. This is handled automatically at the database level using Laravel Global Scopes.

#### The `OrganizationScoped` Trait
The core of the isolation engine is the `App\Traits\OrganizationScoped` trait. When added to any Eloquent model, it automatically intercepts all database queries (`SELECT`, `UPDATE`, `DELETE`) to inject security filters based on the authenticated user's context.

#### User Access Levels (`user_type`)
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

### 4. Secure Password Reset Flow
To prevent data manipulation during the password reset process, the system implements an encrypted email handling strategy:
- **View Protection**: The email address is not displayed as an editable field. Instead, it is **encrypted** using Laravel's `encrypt()` helper and passed as a hidden input.
- **Controller Control**: The `NewPasswordController` intercepts the request and **decrypts** the email address before validation. This ensures that only the intended, signed email address from the password reset link can be used to reset the password, effectively blocking client-side tampering.

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
