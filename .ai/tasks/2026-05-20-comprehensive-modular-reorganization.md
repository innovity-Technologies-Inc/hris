# Task: Comprehensive Modular Reorganization

## Objective
Reorganize Views, Imports, Mail, and Notifications into logical module subdirectories to align with the project's Service-Oriented Architecture (SOA) and modular goals.

## Sub-tasks

### 1. View Consolidation (resources/views/)
- [ ] Merge `resources/views/employees/` -> `resources/views/employee/`
- [ ] Merge `resources/views/leaves/` -> `resources/views/leave/`
- [ ] Merge `resources/views/plans/` -> `resources/views/plan/`
- [ ] Merge `resources/views/settings/` -> `resources/views/setting/`
- [ ] Merge `resources/views/company_setup/` -> `resources/views/company/`
- [ ] Merge `resources/views/organization_structure/` -> `resources/views/structure/`
- [ ] Move `resources/views/search/` -> `resources/views/employee/`
- [ ] Move `resources/views/dashboard.blade.php` -> `resources/views/dashboard/index.blade.php`
- [ ] Move `resources/views/leave.blade.php` -> `resources/views/leave/individual.blade.php`
- [ ] Update all `view()` calls in Controllers and PHP files.

### 2. Import Modularization (app/Imports/)
- [ ] Move `AttendanceImport.php` to `app/Imports/Attendance/`
- [ ] Move employee-related imports to `app/Imports/Employee/`
- [ ] Move plan-related imports to `app/Imports/Plan/`
- [ ] Move `LeavesImport.php` to `app/Imports/Leave/`
- [ ] Update namespaces and references.

### 3. Mail & Notifications Modularization
- [ ] Move employee mails to `app/Mail/Employee/`
- [ ] Move `TestMail.php` to `app/Mail/Dashboard/`
- [ ] Move `PasswordResetNotification.php` to `app/Notifications/Setting/`
- [ ] Update namespaces and references.

### 4. Cleanup & Optimization
- [ ] Remove empty directories.
- [ ] Run `php artisan optimize`.

## Verification Strategy
- [ ] Check sidebar links and main navigation.
- [ ] Verify form submissions (Imports).
- [ ] Verify mail sending (if possible in test environment).
- [ ] Run `php artisan test` (Pest).
