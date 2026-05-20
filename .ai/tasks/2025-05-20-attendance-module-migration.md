# Task: Attendance Module Migration

## Description
Migrate Attendance module files to a logical modular structure and update all references to maintain system integrity.

## Sub-tasks
1.  **Move Files**:
    - [ ] `app/Http/Controllers/AttendancesController.php` -> `app/Http/Controllers/Attendance/AttendancesController.php`
    - [ ] `app/Models/Attendance.php` -> `app/Models/Attendance/Attendance.php`
    - [ ] `app/Services/AttendanceServices.php` -> `app/Services/Attendance/AttendanceServices.php`
2.  **Update Namespaces**:
    - [ ] Update `AttendancesController.php` namespace and add `use App\Http\Controllers\Controller;`.
    - [ ] Update `Attendance.php` namespace.
    - [ ] Update `AttendanceServices.php` namespace.
3.  **Update Global References**:
    - [ ] Replace `App\Models\Attendance` (exact match) with `App\Models\Attendance\Attendance`.
    - [ ] Replace `App\Services\AttendanceServices` with `App\Services\Attendance\AttendanceServices`.
    - [ ] Replace `App\Http\Controllers\AttendancesController` with `App\Http\Controllers\Attendance\AttendancesController`.
4.  **Finalization**:
    - [ ] Run `php artisan optimize`.
    - [ ] Verify functionality with tests (if applicable).

## Verification
- Run tests to ensure no regressions.
- Manually verify Attendance module functionality.
- Log results in `TEST_LOG.md`.
