# Compensatory Leave Application & Comp-Off History Tracking Requirements

## Overview
Enhance the Leave Application module to support `Compensatory Leave` alongside `Standard Leave Plan`. Enable/disable the compensatory leave option dynamically based on employee balance, validate requested days against available balance (excluding off-days and holidays), deduct used balance upon approval, and record audit entries in a comp-off history table.

## Functional Specifications

1. **Leave Category Selection (`leave_category_type`)**:
   - Add radio/selection in Leave Application UI (`resources/views/leave/create.blade.php`):
     - `Standard Leave Plan` (`standard`)
     - `Compensatory Leave` (`compensatory`)
   - Standard Leave Plan requires a selected Leave Plan (`plan_id`).
   - Compensatory Leave hides the Leave Plan dropdown (`plan_id` is optional/null).

2. **Dynamic Comp-Off Availability & Balance API**:
   - Provide an API endpoint `/get-comp-off-details/{employee_id}` returning:
     - `has_comp_off`: boolean
     - `balance_days`: float
     - `comp_off_days`: float
     - `used_days`: float
   - When an employee is selected in the UI:
     - If `balance_days <= 0` or no comp-off record exists:
       - Disable the `Compensatory Leave` radio button with a visual notice: *"No Compensatory Leave balance available"*.
       - If `Compensatory Leave` was previously selected, switch selection back to `Standard Leave Plan`.
     - If `balance_days > 0`:
       - Enable the `Compensatory Leave` radio button and display the available balance.

3. **Off-Day Exclusion for Compensatory Leave Date Range**:
   - Date range calculations for Compensatory Leave must **always exclude** the employee's configured weekends and public holidays.
   - Applied leave count = net working days between `from` and `to`.

4. **Compensatory Leave Validation**:
   - In `LeavesController::store()`:
     - Validate that requested days do not exceed `balance_days` on `EmployeeCompOff`.
     - If requested days > `balance_days`, fail validation with message: `"You do not have enough Compensatory Leave balance. Available: {$balance_days} days, Requested: {$requested_days} days."`.

5. **Comp-Off History Tracking (`employee_comp_off_histories`)**:
   - Create table `employee_comp_off_histories` storing:
     - `employee_id`, `leave_id`, `type` (`earned` / `used`), `days`, `previous_balance`, `new_balance`, `remarks`.
   - When an attendance is marked with `shift_type = 'comp-off'`, record an `earned` history entry.
   - When a compensatory leave is approved (or created with approved status):
     - Deduct `used_days` and update `balance_days` on `EmployeeCompOff`.
     - Record a `used` history entry.

6. **API & Axios Integration**:
   - Modern, smooth Axios-based form interaction, balance fetching, dynamic date range calculations, and error feedback.
