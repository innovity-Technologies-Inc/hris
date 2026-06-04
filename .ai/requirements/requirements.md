# Project Requirements

## Overview
A comprehensive Human Resource Management System (HRMS) built with Laravel 12, following Service-Oriented Architecture (SOA).

## Core Modules

### 🏢 Organizational Management
- Hierarchy: Group -> Company -> Division -> Department -> Section.
- Management of Banks, Branches, Salary Grades, and Gazette Locations.

### 👥 Employee Lifecycle
- Detailed profiles (General & Office info).
- Nominees, Education/Experience, Bank Accounts.
- Salary Breakdown management.
- Movement (Transfers) and status tracking.
- Dynamic ID Card generation with QR codes.
- **Employee Office Information Inconsistencies**:
    - Standardize naming across Models, Views, and Imports for Business Unit (Branch), Division, and Section (always use `name`).
    - Fix bugs in the Office Info edit form (incorrect current company selection logic).
    - Ensure all fields from the `employee_office_infos` table are correctly displayed in the profile view and editable in the form.
- **Profile Review System**:    - Dedicated "Profile Review" page listing employees with `pending` status.
    - Profile View enhancement: "Review" button for pending employees.
    - Review Workflow:
        - Modal with options: `Incomplete` or `Accept`.
        - If `Incomplete`: Provide a mandatory "Cause" text; status reverts to `incomplete`.
        - If `Accept`: Status changes to `active`.
    - Automated Email Notifications:
        - Notify employee when status is set to `incomplete` (including the cause).
        - Notify employee when status is set to `active`.
    - **Profile Review UI/UX Enhancement**:
        - Align the styling of the "Profile Review" page with the "Employee Information" page (Integrated cards, same header style, filter layout).
        - Position the "Profile Review" menu item immediately after "Employee Information" in the sidebar for better logical flow.

### 🔔 Hierarchical Notification System
- **Database Notifications**:
    - Table: `notifications` (fields: `user_type`, `user_id` (nullable), `message`, `data`, `read_at`).
    - **Notification Workflow**:
        - Employee submits profile -> Notification for `user_type = 'hr'`, `user_id = null`.
        - HR/Admin reviews profile -> Notification for `user_type = 'Employee'`, `user_id = {employee_user_id}`.
    - **Visibility Logic**:
        - `Employee`: View notifications where `user_id` matches.
        - `HR`: View notifications where `user_type = 'hr'` (accessible to 'Group' type users).
        - **Hierarchical Escalation**:
            - User types (small to big): `Employee` -> `Section` -> `Department` -> `Division` -> `Business Unit` -> `Company` -> `Group`.
            - Logic: If a notification is assigned to a `user_id`, determine that user's type. The notification should be visible to the user at the next level up in the same organizational hierarchy (e.g., if a Section-type user is targeted, the notification goes to the Division-type user associated with that section's division).

### 📅 Plans & Policies
- **Attendance**: Shift Plans, Roster Plans.
- **Benefits**: Allowance, Bonus, and DA Plans.
- **Time-off**: Leave and Off-Day Plans.
- **Others**: Meal and TA Plans.

### ⏱️ Attendance & Leaves
- Clock-in/out records.
- Bulk attendance imports.
- Leave application and approval workflows.

### 💰 Payroll & Benefits
- Automated salary processing.
- Promotions, Increments, and Bonus management.
- **Salary Process Eligibility View**: 
    - Provide a "View Eligible Employees" feature accessible via the Salary Process index page (modal -> view button).
    - Display eligibility data mapped from the existing `payrolls` table associated with the specific salary process batch.
    - **Individual Payroll Detail View**: For each employee in the eligible list, provide a "View" button to see the full breakdown of their payroll data (earnings, deductions, attendance stats, etc.).
    - **Industry Standard PDF Payslip**: 
        - In the individual payroll detail page, add a "Generate Payslip" button.
        - Generate a professional PDF payslip containing company branding (logo, name, address), employee details (Name, ID, Designation, Department), month/year, and a clear breakdown of earnings and deductions.
        - Company details must be pulled from the `companies` and `employee_office_infos` tables.
    - **Industry Standard Salary Certificate**:
        - In the individual payroll detail page, add a "Generate Salary Certificate" button.
        - Generate a professional Salary Certificate PDF (To Whom It May Concern format).
        - Include company branding, employee's tenure, designation, and current monthly salary breakdown.
        - Both Payslip and Salary Certificate should be viewable in the browser as PDFs.

### 📊 Employee Personal Dashboard & Timeline
- **Goal**: Provide employees and HR with a visual overview of an employee's journey and key financial/tenure statistics.
- **Employee Personal Dashboard**:
    *   **Access**: Linked in the sidebar immediately after the main Dashboard.
    *   **Context**: For regular employees, it shows their own data. For HR/Admin, it can be accessed for any employee via the "Employee Information" index.
    *   **Key Statistics**:
        *   `Serving Years`: Calculated tenure from joining date.
        *   `Total Earnings`: Aggregated salary from processed payrolls.
        *   `Total Bonuses`: Aggregated bonus amounts.
    *   **UI**: Modern "Glassmorphism" layout with descriptive cards.
- **Employee Journey Timeline**:
    *   **Visual Representation**: A vertical or horizontal timeline showing key milestones.
    *   **Milestones**:
        *   `Onboarding`: Date of profile creation.
        *   `Profile Approval`: Date when status changed to active.
        *   `Transfers`: History of requested and completed transfers.
        *   `Promotions`: History of designation/level changes.
        *   `Increments`: History of salary increases.
    *   **Interactivity**: Tooltips or modals for more detail on each milestone.

### 🚐 Transport Services
- Vehicle and Driver management.
- Requisition and allocation workflows.

### 🔄 Transfer Module
- **Goal**: Facilitate and track employee movement between different organizational units (Companies, Business Units, Divisions, Departments, Sections).
- **Sections**:
    - `Application`: A form for requesting an employee's transfer to a new organizational hierarchy.
    - `Logs`: A list of all transfer requests with detailed status tracking and approval history.
- **Workflow**:
    1. **Submission**:
        - Select target employee.
        - Select target organizational units (cascading dropdowns: Company -> Business Unit -> Division -> Department -> Section -> Designation).
    2. **Approval Configuration**:
        - HR/Admin configures the required number of approvals (e.g., 3).
        - HR/Admin selects specific employees as "Approval Authorities".
        - Integrated filter for picking authorities (Name, Company, Business Unit, Division, Department, Section, User Type).
    3. **Approval Lifecycle**:
        - Authorities receive automated email and in-app panel notifications.
        - Authorities approve/reject from their dedicated approval panel.
        - HR monitors real-time approval status.
    4. **Execution & Finalization**:
        - Once all approvals are secured, HR marks the transfer as `complete`.
        - **Automatic Data Update**: Upon completion, the system automatically updates the employee's `EmployeeOfficeInfo` record with the new organizational data.
        - Final notifications (Email + Panel) are sent to the employee and all relevant parties.
- **Technical Mandate**:
    - **Backend**: Strict Request -> Service -> API Controller pattern returning JSON responses.
    - **Frontend**: Blade templates for structure; **Axios** and **Vanilla JavaScript** for all dynamic data fetching (e.g., cascading dropdowns, filtering, status updates) and asynchronous form submissions.
    - **UI**: Modern Bootstrap 5 / Glassmorphism design consistent with the rest of the HRMS.

### 🔐 User Management & RBAC
- **Authentication**: Integrated via Laravel Breeze (Blade implementation).
- **Access Control**: Powered by `spatie/laravel-permission`.
- **Employee-User Link**:
    - Automatic `User` creation during `Employee` general information setup.
    - Login credentials (User Type, Roles, Password) handled in a "Login Information" tab.
    - `User` email maps to `Employee` work email.
    - Bi-directional linking: `users.employee_id` and `employees.user_id`.
- **Organization-Based Visibility (Data Scoping)**:
    - **Group**: View all data.
    - **Company**: View data for a specific company and its hierarchy.
    - **Business Unit/Division/Department/Section**: Scoped access to the specific level and its descendants.
    - **Employee**: View only their own records.
- **Role Management UI**:
    - Centralized management under Settings -> Role Management.
    - Permission granularity: Create, Edit, View, Delete for all menus and submenus.
    - Dynamic permission seeder for system-wide menus.
    - **Permission Reset**: Ability to clear and re-seed all permission-related data (roles, permissions, menus) to restore system defaults.
    - **RBAC Visibility for Action Buttons**: 
        - Apply `@can` checks for Create, Edit, Delete, and Import buttons across all index and partial blade files for Company Setup, Plans, and Transport modules.
        - Wrap "Edit" buttons in employee profile partials (Bank Accounts, Education, Eligible Plans, Nominee, Office Info, Salary Breakdown) with `@can('employee-management.edit')`.
        - Wrap "Create", "Assign", "Store", "Remove", and "Delete" buttons/forms for Meal, Shift, Roster, OT, Off-Day, Bonus, and Leave plans in employee profile with `@can('employee-management.edit')`.
        - Use permission slugs from `PermissionSeeder` (e.g., `slug.create`, `slug.edit`, `slug.delete`, `slug.import`).

### 🗄️ Database Seeding & Initial Setup
- **User Types**: Support 'Group', 'Company', 'Department', and 'Employee' user types.
- **Initial Roles**: 
    - Create a 'Super Admin' role with full permissions.
    - Create a 'Group' user type for overall management.
- **Employee Login Provisioning**:
    - Bulk create/update `User` accounts for all existing employees.
    - Assign appropriate roles (e.g., 'HR Manager', 'Department Manager', 'Employee').
    - Standardize initial passwords to `12345678` for testing/initial rollout.
    - Ensure at least one user has `user_type` 'Group' and role 'Super Admin' with all permissions.
- **Data Consistency & Seeder Reliability**:
    - Ensure all seeders generate data that adheres to the validation rules defined in the corresponding Services.
    - **Employment History Seeder**: Align `EmployeeSeeder` dummy data with `EmployeeServices` validation (use `company_name` and `joining_date` keys).
    - **Robust Views**: Implement defensive programming in Blade templates (e.g., using `?? null` or `isset()`) to prevent crashes when accessing array keys from JSON-casted columns that might be missing due to legacy data or seeder inconsistencies.

### 📁 Modular Organization
- **Goal**: Reorganize the project codebase into logical modules based on the sidebar menu structure.
- **Modules**:
    - `Dashboard`: Core dashboard and analytics.
    - `Employee`: Employee information, profile reviews, search, and bulk uploads.
    - `Attendance`: Clocking, attendance records, and bulk imports.
    - `Leave`: Leave applications and logs.
    - `Movement`: Employee movement (transfer) applications and logs.
    - `Payroll`: Promotions, increments, bonuses, and salary processing.
    - `Plan`: Management of various plans (Meal, Shift, Leave, OT, Roster, Off-Day, Bonus, Allowance, TA, DA, Deduction).
    - `Company`: Organizational hierarchy (Groups, Companies, Branches, Divisions, Departments, Sections), Designations, Banks, Holidays, and Job Creations.
    - `Structure`: Organizational structural views and member management.
    - `Transport`: Vehicle and driver management, requisitions, and allocations.
    - `Setting`: General settings, ID card design, API keys, SMTP, DB backup, and Role Management.
- **Migration Scope**:
    - **Views**: Consolidate redundant folders (e.g., `employees` -> `employee`) and update all `view()` calls.
    - **Imports**: Organize `app/Imports` into module subdirectories (e.g., `app/Imports/Attendance`).
    - **Mail & Notifications**: Organize `app/Mail` and `app/Notifications` into module subdirectories (e.g., `app/Mail/Employee`).
- **Directory Structure**:
    - Each module will have dedicated directories in:
        - `app/Http/Controllers/{Module}`
        - `app/Models/{Module}`
        - `app/Services/{Module}`
        - `app/Http/Requests/{Module}`
        - `app/Imports/{Module}`
        - `app/Mail/{Module}`
        - `app/Notifications/{Module}`
        - `resources/views/{module_snake_case}`
- **Implementation**: 
    - Maintain backward compatibility where necessary during the transition.
    - Update namespaces and imports for all moved files.
    - Run `php artisan optimize` after structural changes.

### 📊 Employee Detailed View & PDF Export
- **Goal**: Provide a comprehensive view of all employee data in a single modal and allow PDF export.
- **Detailed View Modal**:
    - **Trigger**: "Detailed View" button in the employee profile header.
    - **Content**: Display all fields from the `employees` table (General Information, Contact, Documents, etc.).
    - **Logic**: Only show fields that have data.
    - **Styling**: Modern-clean layout, similar to a professional profile PDF.
- **PDF Export**:
    - **Trigger**: "Download PDF" button inside the Detailed View modal.
    - **Content**: A professional PDF document of the detailed employee profile.
    - **Implementation**: Use `Spatie Browsershot` (consistent with ID Card and Salary modules).

### 🆔 NID Verification Module
- **Goal**: Verify employee National ID (NID) against a verification service and display verification status.
- **UI Integration**:
    - Add a "NID Verification" button in the employee profile header action center, positioned beside the "Summary View" button.
    - The button is only visible to users who are NOT type 'Employee' and have the `nid verification` permission.
- **Verification Workflow**:
    - Clicking the button opens a modal.
    - **Modal Content**:
        - A read-only input box showing the employee's NID number.
        - A "Verify NID" button.
    - **Process**:
        - Clicking "Verify NID" calls an API endpoint.
        - **Initial Implementation**: Use a dummy verification function that simulates an API call and returns a success response after a short delay.
        - On success: Show a success message and update the employee's verification status in the database.
- **Verification Status & Badge**:
    - Add a "Verified" badge (with a checkmark icon) in the employee profile header next to their name if NID is verified.
- **Access Control**:
    - New permission: `nid verification` under employee management.
    - Restricted for `user_type = 'Employee'`.
- **Database Changes**:
    *   Add `nid` column to the `employees` table (if not exists).
    *   Add `is_nid_verified` boolean column to the `employees` table.

### 🔔 Notification & Alert Settings
- **Goal**: Configure and automate alerts for critical employee milestones and document expiries.
- **Alert Types**:
    *   **Birthday**: Triggered based on `date_of_birth` in `employees`.
    *   **Visa Expiry**: Triggered based on `visa_expiry` in `employees`.
    *   *Work Permit Expiry**: Triggered based on `work_expiry` in `employees`.
    *   **Passport Expiry**: Triggered based on `passport_expiry` in `employees`.
    *   **License Expiry**: Triggered based on `license_expiry` in `employees`.
    *   **Probation Period End**: Triggered based on `date_of_join` + `probation_duration` in `employee_office_infos`.
- **Threshold Settings**:
    *   A new settings page to define the number of days before the event to send the alert.
- **Notification Routing**:
    *   **Recipients**:
        *   For Birthday: Send to all users EXCEPT the particular employee (e.g., HR, Admin).
        *   For all other alerts: Send to the particular employee AND all other users (HR, Admin).
    *   **Storage**: Save notifications in the `notifications` table (using `App\Models\Setting\Notification`).
- **Automation**:
    *   Daily scheduled task (command) to evaluate all thresholds and generate notifications.

### 📈 Employee Reports & Analytics
- **Goal**: Provide visual analytics and detailed reports for workforce management.
- **Report Section**:
    - **Ages**: Distribution of employee ages (e.g., age groups).
    - **Years of Service (Loyalty)**: Analysis of tenure across the organization.
    - **Upcoming Birthdays**: A list of employees with birthdays in the current month/week.
    - **Service Analysis**: Comprehensive overview of service-related data.
- **UI/UX**:
    - Modern dashboard with interactive charts and graphs (Chart.js).
    - Maintain "Glassmorphism" aesthetic.
    - Accessible via "Reports" under the "Employees" menu in the sidebar.

### 🏠 Employee Dashboard Updates
- **Timeline Enhancements**:
    - **Age**: Include birth date/age milestone in the career timeline.
    - **Probation End**: Include the calculated probation end date (`date_of_join` + `probation_duration`) in the timeline.

### 💳 Pay Group Management
- **Goal**: Define and manage pay groups for payroll processing categorization and scheduling.
- **Access**: Located under "Company Info" menu in the sidebar.
- **Fields**:
    - `title`: Name of the pay group.
    - `payroll_frequency`: (Hourly, Monthly, Weekly).
    - `salary_processing_day`: 
        - If Monthly: Specific date of the month (1-31).
        - If Weekly: Day of the week (Monday-Sunday).
        - If Hourly: Automatically set to "Daily".
    - `status`: (Active, Inactive).
- **Technical Mandate**:
    - **API-First**: Use API controllers returning JSON.
    - **Frontend**: Blade structure with **Axios** and **Vanilla JS** for all data fetching (index listing, searching, form submission).
    - **UI**: Bootstrap 5 / Glassmorphism consistency.
    - **Data Scoping**: Respect `OrganizationScoped` trait.

## Technical Constraints
- **Framework**: Laravel 12.
- **PHP Version**: 8.2+ (Strict Typing).
- **Architecture**: Request-Service-Controller.
- **UI**: Bootstrap 5 + Glassmorphism + Dark Mode.
- **Search**: Laravel FlexSearch.
