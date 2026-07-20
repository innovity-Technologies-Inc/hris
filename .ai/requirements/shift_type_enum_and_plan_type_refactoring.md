# Refactoring Shift Type ENUM & Off-Day Plan Types (`paid-off` / `comp-off`)

## Requirements

1. **Shift Type ENUM Column in `attendance` Table**:
   - Revert `shift_type` column in `attendance` table to an ENUM:
     `ENUM('Regular', 'Roster', 'Off-Day', 'paid-off', 'comp-off')`.

2. **Standardized Off-Day Plan Type Values**:
   - Standardize `type` column values in `off_day_plans` to: `paid-off` and `comp-off` (with default `'paid-off'`).
   - Update form UI radio values in `form.blade.php` to `paid-off` and `comp-off`.
   - Update backend validation in `PlanService.php`: `'type' => 'required|in:paid-off,comp-off'`.

3. **Attendance Shift Type Assignment & Comp-Off Increment**:
   - In `AttendanceServices.php`:
     - If the off-day plan type is `comp-off`, set `shift_type = 'comp-off'`.
     - If the off-day plan type is `paid-off`, set `shift_type = 'paid-off'`.
   - When an attendance with `shift_type === 'comp-off'` is stored, increment `employee_comp_offs` record for that employee.

4. **Payroll Calculation Alignment**:
   - In `PayrollServices.php`:
     - Exclude `shift_type = 'comp-off'` (or `plan->type === 'comp-off'`) from monetary salary remuneration.
     - Include `paid-off` (and legacy `'Off-Day'`) for paid off-day work salary calculation.
