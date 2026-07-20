# Task: Refactor Shift Type ENUM in Attendance & Standardize Off-Day Plan Types

## Solution Summary
1. Update migrations to set `shift_type` on `attendance` table to `ENUM('Regular', 'Roster', 'Off-Day', 'paid-off', 'comp-off')` and update `type` on `off_day_plans` to `ENUM('paid-off', 'comp-off')`.
2. Update `PlanService.php` validation rules to require `in:paid-off,comp-off`.
3. Update `form.blade.php`, `search_results.blade.php`, `view.blade.php`, `offday_plan.blade.php`, and `view_offday_modal.blade.php` to handle `paid-off` and `comp-off`.
4. Update `AttendanceServices.php` to assign `shift_type = 'comp-off'` or `shift_type = 'paid-off'` and increment `EmployeeCompOff` balance when `shift_type === 'comp-off'`.
5. Update `PayrollServices.php` to filter paid off-day work (`paid-off`) and skip `comp-off`.

## Execution Steps
- Step 1: Update/Create migrations & run `php artisan migrate`.
- Step 2: Update `PlanService.php` and `OffDayPlansImport.php`.
- Step 3: Update `form.blade.php` & blade views.
- Step 4: Update `AttendanceServices.php` & `PayrollServices.php`.
- Step 5: Run `php artisan optimize` and commit changes.
