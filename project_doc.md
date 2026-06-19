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
- **Pay Groups**: Define payroll processing categorizations (Hourly, Monthly, Weekly) with dynamic salary processing date/day selection.
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
    - **UI Alignment**: The review interface is designed to match the system-wide employee management style, featuring integrated search cards and high-performance filtering.
    - **Sidebar Integration**: The "Profile Review" menu is logically positioned immediately following "Employee Information" for a streamlined HR auditing workflow.

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

### 💰 In-Depth Payroll & Benefits Module

The Payroll & Benefits module is a highly flexible, transaction-safe, and organization-scoped financial processing engine. It manages the complete lifecycle of employee remuneration, deductions, adjustments, and final disbursement.

#### 🏗️ 1. Core Architecture & Service Delegation
The module follows a strict **Request -> Service -> API Controller** pattern:
- **Thin Controllers**: [SalaryController](file:///P:/Project/Web/hrms/app/Http/Controllers/Payroll/SalaryController.php), [AdvanceSalaryController](file:///P:/Project/Web/hrms/app/Http/Controllers/Payroll/AdvanceSalaryController.php), [EmployeePenaltyController](file:///P:/Project/Web/hrms/app/Http/Controllers/Payroll/EmployeePenaltyController.php), and [DisbursementController](file:///P:/Project/Web/hrms/app/Http/Controllers/Payroll/DisbursementController.php) handle routing, input validation, and user responses (returning Blade templates or JSON structures).
- **Business Logic Services**: Enacted in specialized classes including [PayrollServices](file:///P:/Project/Web/hrms/app/Services/Payroll/PayrollServices.php), [DisbursementServices](file:///P:/Project/Web/hrms/app/Services/Payroll/DisbursementServices.php), [EmployeePenaltyServices](file:///P:/Project/Web/hrms/app/Services/Payroll/EmployeePenaltyServices.php), [PayslipService](file:///P:/Project/Web/hrms/app/Services/Payroll/PayslipService.php), and [SalaryCertificateService](file:///P:/Project/Web/hrms/app/Services/Payroll/SalaryCertificateService.php).
- **Transaction Safety**: All multi-step payroll processes, updates, and rollback deletions are wrapped inside Eloquent database transactions (`DB::transaction(...)`) to guarantee data integrity.
- **Isolation Scoping**: Leverages the [OrganizationScoped](file:///P:/Project/Web/hrms/app/Traits/OrganizationScoped.php) trait at the database query level to restrict viewable records based on the user's `user_type` (e.g., Company, Division, Section, Employee).

---

#### 💸 2. Payroll Calculation Logic by Pay Group Frequency
The system dynamically adapts its calculation engine depending on the employee's assigned **Pay Group Frequency** (Monthly, Daily, Hourly). Working days and hourly divisors are retrieved from the pay group config, with a default fallback of 30 working days and 8 daily hours.

##### A. Monthly Pay Group
- **Base Salary**: Taken directly from the employee's `gross_salary`.
- **Base Rate (Day Rate)**: `Gross Salary / Working Days Per Cycle` (default 30 days).
- **Hourly Rate**: `(Gross Salary / Working Days) / Working Hours Per Day` (default 8 hours).
- **Overtime & Off-Day Work**: Calculated using the **Gross-based Hourly Rate**.
- **Deductions**: Calculated using the **Day Rate** (absent deductions subtract full Day Rate values per absent day). Late, excessive late, and early exit deductions are calculated and stored individually in the `payrolls` table.
- **Bonus**: Calculated based on the `basic_salary` component.

##### B. Daily Pay Group
- **Base Salary**: `Daily Rate (Gross Salary) * Total Days in Range`.
- **Base Rate (Day Rate)**: The `gross_salary` value itself (which is entered as a daily rate).
- **Hourly Rate**: `Gross Salary / Working Hours Per Day` (default 8 hours).
- **Overtime & Off-Day Work**: Calculated using the daily gross-based Hourly Rate.
- **Deductions**: Uses the `gross_salary` directly as the Day Rate.
- **Bonus**: Calculated based on the `basic_salary` (treated as the daily basic portion).

##### C. Hourly Pay Group
- **Base Salary**: `Hourly Rate (Gross Salary) * (Total Scheduled Minutes / 60)`.
- **Base Rate (Day Rate)**: `Gross Salary * Working Hours Per Day` (default 8 hours).
- **Hourly Rate**: The `gross_salary` value itself (entered as an hourly rate).
- **Overtime & Off-Day Work**: Calculated using the **Gross-based Hourly Rate** (the `gross_salary` field).
- **Deductions**: Derived by converting the hourly rate into the equivalent day rate based on scheduled hours.
- **Bonus**: Calculated based on the `basic_salary` (treated as the hourly basic portion).

---

#### 🔄 3. Key Sub-Modules & Workflows

##### A. Salary Process & Rollback
- **Processing**: Salary batches are initiated with a defined month/period. The system checks eligibility based on active employee office info and pay scale status. A dedicated **Salary Process Eligibility View** is provided to review and audit eligible employees before saving the batch.
- **Deduction Granularity**: The system records individual late, excessive late, absent, and early exit count deductions in the `payrolls` table.
- **Rollback Process**: When a process is rolled back or deleted via `rollbackSalaryProcess($id)`, the system updates all subtracted advances and penalties back to `approved` state, ensuring zero loss of adjustment records.

##### B. Advance Salary Module
- **Purpose**: Processes cash/salary advances to employees that are automatically recovered in subsequent payroll processes.
- **Calculations**: Supports both Fixed amounts and Percentage-based calculations (referencing basic salary or gross salary).
- **Auto-Recovery**: During salary generation, the engine scans for active, approved advance salary records matching the target `deduction_month`. The recovery amount is subtracted from the final total salary, and the advance status is updated to `deducted`.
- **Rollback Safety**: Rollback or deletion of a salary process restores the advance records back to `approved` status.

##### C. Employee Penalties
- **Assignment**: Penalties are assigned to employees based on specific Penalty Plans and logged with occurrences and causes in the [EmployeePenalty](file:///P:/Project/Web/hrms/app/Models/Payroll/EmployeePenalty.php) model.
- **Notifications**: Saving a penalty automatically invokes `notifyEmployee()`, dispatching a real-time notification to the target employee's user dashboard.
- **Salary Deductions**: Approved penalties matching the payroll period are deducted from the employee's total earnings during salary generation, changing status to `deducted`.
- **Rollback Integration**: Reverting or deleting a salary process resets the penalty status to `approved`.

##### D. Arrears Management
- **Purpose**: Manages outstanding/overdue payments.
- **Workflow**: Logged under [Arrear](file:///P:/Project/Web/hrms/app/Models/Payroll/Arrear.php), approved arrear batches are included in the payroll process and cleared during final disbursement.

##### E. Promotions & Increments
- **Increments**: Increases are calculated as a flat amount or percentage of gross/basic salary.
- **Promotions**: Designation and salary grades are revised.
- **Modifications**: Once approved, the system updates the employee's `gross_salary` and breakdown and invokes `designationUpdate()` to update office parameters.

##### F. Bonus Management
- **Workflow**: Compiles bonuses based on assigned plans.
- **Processing**: Initiates standalone bonus batches or embeds them inside standard monthly salary payouts.

---

#### 💳 4. Payouts & Disbursement System
Once a salary or bonus batch is approved, it enters the **Disbursement** pipeline handled by [DisbursementServices](file:///P:/Project/Web/hrms/app/Services/Payroll/DisbursementServices.php):
- **Pending Batch Audits**: The dashboard computes statistics (eligible headcount, total batch cost, disbursed vs pending amounts, and headcount) for approved salary/bonus processes.
- **Payment Processing**: HR selects records and defines a payment method (e.g., Cash, Bank Transfer, MFS) to initiate the disbursement batch.
- **Status Progression**: Inside a database transaction, individual records in the `payrolls` or `bonuses` tables are updated from `pending` to `paid` to prevent double-payouts.
- **History & Documentation**: Disbursements support uploading physical files (e.g. bank sheets, receipts) and keep a historical log of who disbursed the funds, when, and the transaction note.

---

#### 📄 5. Branded PDF Generation
Using `Spatie Browsershot`, the system compiles Blade templates into standard PDFs:
- **Payslips**: Comprehensive breakdown of Gross, Overtime, Off-day work, Bonuses, and individual deductions (Absent, Late, Advance Salary, Penalty) compiled with company branding.
- **Salary Certificates**: Generates a standard "To Whom It May Concern" certification representing tenure and remuneration. It can be compiled using a past processed payroll record or generated based on the employee's current salary breakdown.

---

### 📊 Employee Personal Dashboard & Journey Timeline
A dedicated analytical view providing a 360-degree overview of an employee's career and financial growth within the organization.
- **Visual Analytics**: Beautiful "Glassmorphism" cards showing:
    - **Serving Tenure**: Real-time calculation of years, months, and days of service.
    - **Total Earnings**: Aggregate of all processed salaries and allowances.
    - **Total Bonuses**: Cumulative sum of all performance and seasonal rewards.
- **Career Journey Timeline**: A chronological, interactive vertical timeline capturing every significant milestone:
    - **Onboarding**: The date of system entry and profile creation.
    - **Profile Approval**: Official activation of the employee profile.
    - **Career Movements**: History of requested and completed transfers across companies and branches.
    - **Growth Milestones**: Trackable log of promotions and increments.
- **Detailed View & PDF Export**:
    - **Interactive Modal**: A "Detailed View" button in the profile header opens a comprehensive modal displaying all employee fields (General, Contact, Document, Office, and Address).
    - **Conditional Display**: The modal logic ensures only fields with data are shown, providing a clean and relevant view.
    - **Professional PDF Export**: A branded, professionally formatted PDF of the detailed profile can be generated and downloaded directly from the modal, powered by `Spatie Browsershot`.
- **Access Control**:
    - **Employee Access**: Directly linked in the sidebar for personal tracking.
    - **HR/Admin Access**: Integrated as a quick-action button in the main Employee Index, allowing for comprehensive audits.

### 🚐 Transport Module
- **Assets**: Vehicle and Driver management.
- **Operations**: Requisitions (applications), approvals, and allocations.
- **Tracking**: History of vehicle assignments and driver activities.

### 🆔 NID Verification Module
- **Purpose**: Verify employee National ID (NID) against a verification service.
- **Workflow**:
    - "NID Verification" button in the profile header (restricted to authorized roles).
    - Modal display of employee's NID number.
    - Asynchronous verification via dummy API (simulating EC verification).
    - Dynamic "Verified" badge display on the profile header upon success.
- **Access Control**: Requires `nid verification` permission and `user_type != 'Employee'`.

### 🔔 Notification Alert Module
- **Purpose**: Configure and automate alerts for employee milestones (Birthdays, Probation End) and document expiries (Visa, Passport, Work Permit, License).
- **Workflow**:
    - Thresholds are set in **Settings > Notification Alerts**.
    - A daily scheduled command (`app:check-alerts`) evaluates employees against these thresholds.
    - Notifications are generated and stored in the `notifications` table.
- **Recipient Logic**:
    - **Birthdays**: Sent to all users *except* the birthday employee.
    - **Expiries/Probation**: Sent to the particular employee AND all other non-employee users.

### 📈 Workforce Analytics (Reports)
- **Purpose**: Provide visual workforce insights and service analysis with strict organizational scoping.
- **Features**:
    - **Age Distribution & Stats**: Interactive doughnut chart showing age groups, plus Average, Min, and Max age metrics.
    - **Service Loyalty**: Bar chart analyzing organizational tenure.
    - **Organizational Hierarchy**: Charts breaking down employee counts by Company, Division, and Department.
    - **Upcoming Birthdays**: Real-time list of employees with birthdays in the current month.
    - **Service Summary**: Key stats including total headcount, active status, and average tenure.
- **Access Control**: Requires the `analytics` permission (under Employee Management) AND excludes the `Employee` user type. All data respects the `OrganizationScoped` trait.
- **Tech**: Integrated with Chart.js for high-performance rendering.

### 🏠 Dashboard Timeline Enhancements
- **Born Milestone**: Automatically includes birth date and current age in the career journey.
- **Probation Milestone**: Calculates and displays the probation end date based on joining info.

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
