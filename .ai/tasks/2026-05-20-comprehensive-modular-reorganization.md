# Task: Comprehensive Modular Reorganization

## Objective
Reorganize Views, Imports, Mail, and Notifications into logical module subdirectories to align with the project's Service-Oriented Architecture (SOA) and modular goals.

## Sub-tasks

### 1. View Consolidation (resources/views/)
- [x] Merge `resources/views/employees/` -> `resources/views/employee/`
- [x] Merge `resources/views/leaves/` -> `resources/views/leave/`
- [x] Merge `resources/views/plans/` -> `resources/views/plan/`
- [x] Merge `resources/views/settings/` -> `resources/views/setting/`
- [x] Merge `resources/views/company_setup/` -> `resources/views/company/`
- [x] Merge `resources/views/organization_structure/` -> `resources/views/structure/`
- [x] Move `resources/views/search/` -> `resources/views/employee/`
- [x] Move `resources/views/dashboard.blade.php` -> `resources/views/dashboard/index.blade.php`
- [x] Move `resources/views/leave.blade.php` -> `resources/views/leave/individual.blade.php`
- [x] Update all `view()` calls in Controllers and PHP files.

### 2. Import Modularization (app/Imports/)
- [x] Move `AttendanceImport.php` to `app/Imports/Attendance/`
- [x] Move employee-related imports to `app/Imports/Employee/`
- [x] Move plan-related imports to `app/Imports/Plan/`
- [x] Move `LeavesImport.php` to `app/Imports/Leave/`
- [x] Update namespaces and references.

### 3. Mail & Notifications Modularization
- [x] Move employee mails to `app/Mail/Employee/`
- [x] Move `TestMail.php` to `app/Mail/Dashboard/`
- [x] Move `PasswordResetNotification.php` to `app/Notifications/Setting/`
- [x] Update namespaces and references.

### 4. Cleanup & Optimization
- [x] Remove empty directories.
- [x] Run `php artisan optimize`.

## Verification Strategy
- [x] Check sidebar links and main navigation.
- [x] Verify form submissions (Imports).
- [x] Verify mail sending (if possible in test environment).
- [x] Run `php artisan test` (Pest).
