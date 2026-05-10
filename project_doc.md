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
- **Adjustments**: Handle Promotions, Increments, and Bonus distributions.
- **Structure**: Based on Salary Grades and Employee-specific breakdown.

### 🚐 Transport Module
- **Assets**: Vehicle and Driver management.
- **Operations**: Requisitions (applications), approvals, and allocations.
- **Tracking**: History of vehicle assignments and driver activities.

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
