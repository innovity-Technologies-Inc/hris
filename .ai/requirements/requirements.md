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

### 🚐 Transport Services
- Vehicle and Driver management.
- Requisition and allocation workflows.

## Technical Constraints
- **Framework**: Laravel 12.
- **PHP Version**: 8.2+ (Strict Typing).
- **Architecture**: Request-Service-Controller.
- **UI**: Bootstrap 5 + Glassmorphism + Dark Mode.
- **Search**: Laravel FlexSearch.
