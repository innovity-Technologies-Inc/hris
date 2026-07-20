# Comp-Off Attendance & Remuneration Flow Requirement

## Overview
Update the attendance tagging and payroll salary calculation for off-day work based on the plan type (`Paid` vs `comp-off`). Automatically track earned compensatory leave days in an `employee_comp_offs` record when an employee works on a `comp-off` off-day.

## Requirements

1. **Attendance `shift_type` Value Update**:
   - When an attendance record is created on an off-day:
     - If the associated `OffDayPlan` `type` is `comp-off`, set `shift_type = 'comp-off-offday'`.
     - If the `OffDayPlan` `type` is `Paid`, set `shift_type = 'paid-offday'`.

2. **Comp-Off Day Tracking (`employee_comp_offs` Table)**:
   - When an attendance with `shift_type = 'comp-off-offday'` is recorded:
     - Check if an `EmployeeCompOff` record exists for the employee.
     - **If record exists**: Increment `comp_off_days` by 1, update `balance_days = comp_off_days - used_days`, and set `last_earned_date`.
     - **If record does not exist**: Create a new `EmployeeCompOff` record with `comp_off_days = 1`, `used_days = 0`, `balance_days = 1`, and `last_earned_date`.

3. **Payroll Remuneration Calculation Alignment**:
   - In `PayrollServices`:
     - Only process attendance records with `shift_type = 'paid-offday'` (or `'Paid-Off-Day'`) for cash remuneration.
     - `comp-off-offday` attendance records must be excluded from salary remuneration calculation because compensation is provided as earned comp-off leave days.
