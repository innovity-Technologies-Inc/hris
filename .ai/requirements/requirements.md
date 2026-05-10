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

## Technical Constraints
- **Framework**: Laravel 12.
- **PHP Version**: 8.2+ (Strict Typing).
- **Architecture**: Request-Service-Controller.
- **UI**: Bootstrap 5 + Glassmorphism + Dark Mode.
- **Search**: Laravel FlexSearch.
