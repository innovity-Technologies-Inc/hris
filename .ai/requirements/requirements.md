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
- **Profile Review System**:
    - Dedicated "Profile Review" page listing employees with `pending` status.
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

### 🚐 Transport Services
- Vehicle and Driver management.
- Requisition and allocation workflows.

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

## Technical Constraints
- **Framework**: Laravel 12.
- **PHP Version**: 8.2+ (Strict Typing).
- **Architecture**: Request-Service-Controller.
- **UI**: Bootstrap 5 + Glassmorphism + Dark Mode.
- **Search**: Laravel FlexSearch.
