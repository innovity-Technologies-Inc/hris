# Task: Implement Employee Personal Dashboard and Journey Timeline

Create a visually appealing dashboard for employees to track their journey, financial stats, and career milestones.

## Status
- **Date**: 2026-05-24
- **Priority**: High
- **Status**: In Progress

## Tasks

### 1. Research & Analysis
- [x] Identify data sources for Timeline (Onboarding, Approval, Transfers, Promotions, Increments).
- [x] Identify data sources for Dashboard stats (Tenure, Total Earnings, Total Bonuses).
- [x] Analyze existing "Glassmorphism" UI patterns in the project.

### 2. Backend Implementation
- [x] **Service Layer (`App\Services\Employee\EmployeeDashboardServices.php`)**:
    - [x] Method `getDashboardStats($employee_id)`: Calculate serving years, sum total earnings (salary + overtime + offday work), and sum total bonuses.
    - [x] Method `getTimelineEvents($employee_id)`: Collect all milestones into a sorted collection (Onboarding, Transfers, Promotions, Increments).
- [x] **Controller Layer (`App\Http\Controllers\Employee\EmployeeDashboardController.php`)**:
    - [x] Method `index()`: Show dashboard for the logged-in employee.
    - [x] Method `show($employee_id)`: Show dashboard for a specific employee (Admin/HR only).
- [x] **Routes**:
    - [x] Define `employee-dashboard` route.
    - [x] Define `employee-dashboard/{id}` route.

### 3. Frontend Implementation
- [x] **View (`resources/views/employee_dashboard/index.blade.php`)**:
    - [x] Layout: Extended from `structure.master`.
    - [x] Stats Section: 3-4 beautiful Glassmorphism cards for Tenure, Earnings, and Bonus.
    - [x] Timeline Section: Vertical scrollable timeline with icons and tooltips.
- [x] **Sidebar Integration**:
    - [x] Add "Employee Dashboard" link after the main Dashboard.
- [x] **Employee Index Integration**:
    - [x] Add "Dashboard" action button in `resources/views/employee/index.blade.php`.

### 4. Verification
- [x] Create Pest tests to verify stat calculations and timeline event aggregation.
- [x] Manually verify UI responsiveness and theme compatibility (Dark/Light).
- [x] Update `TEST_LOG.md`.

### 5. Finalization
- [x] Update `project_doc.md`.
- [x] Run `php artisan optimize`.
- [x] Commit changes.
