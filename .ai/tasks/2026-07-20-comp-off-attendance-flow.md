# Task: Implement Comp-Off Attendance Tagging and Balance Tracking Flow

## Problem Statement
Previously, off-day attendance records were uniformly assigned `shift_type = 'Off-Day'` regardless of whether the plan was `Paid` or `comp-off`. Comp-off days were not tracked in a dedicated table, and salary calculations treated all off-days as paid work.

## Solution Outline
1. **Migration 1**: Modify `shift_type` column on `attendance` table to `string` to accommodate `'paid-offday'` and `'comp-off-offday'`.
2. **Migration 2**: Create `employee_comp_offs` table for tracking accumulated comp-off days, used days, and balance.
3. **Model**: Create `App\Models\Employee\EmployeeCompOff.php`.
4. **Attendance Service**:
   - Update `AttendanceServices` off-day check to return `shift_type = 'comp-off-offday'` when `type == 'comp-off'`, and `'paid-offday'` when `type == 'Paid'`.
   - Implement `incrementCompOffBalance($employee_id, $attendance_date)` to create or increment `employee_comp_offs` record.
5. **Payroll Service**:
   - Update `PayrollServices::offDayWorkSalary()` to filter attendances with `shift_type` in `['paid-offday', 'Paid-Off-Day', 'Off-Day']` where plan is paid.
   - Exclude `comp-off-offday` from salary payout calculations.

## Execution Steps

### Step 1: Database Migrations
- `2026_07_20_000002_modify_shift_type_in_attendance_table.php`
- `2026_07_20_000003_create_employee_comp_offs_table.php`
- Run `php artisan migrate`.

### Step 2: Model & Service Layer Implementation
- Create `App\Models\Employee\EmployeeCompOff.php`.
- Update `AttendanceServices.php`.
- Update `PayrollServices.php`.

### Step 3: Optimization & Source Control
- Run `php artisan optimize`.
- Commit changes.
