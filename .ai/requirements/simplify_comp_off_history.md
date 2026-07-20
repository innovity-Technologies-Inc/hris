# Requirement: Simplify Comp-Off Management (Remove Dedicated History Table)

## Overview
Simplify comp-off architecture by eliminating the dedicated `employee_comp_off_histories` table. Standard leave history is stored in the `leaves` table, while balance metrics (`comp_off_days`, `used_days`, `balance_days`) are tracked directly on `employee_comp_offs`.

## Detailed Specifications
1. **Schema Clean-up**:
   - Drop `employee_comp_off_histories` table.
   - Remove migration `2026_07_20_000007_create_employee_comp_off_histories_table.php`.

2. **Model Clean-up**:
   - Delete `App\Models\Employee\EmployeeCompOffHistory.php`.

3. **Service & Controller Clean-up**:
   - Remove history log insertions from `AttendanceServices.php` (`incrementCompOffBalance`).
   - Remove history log insertions from `LeavesController.php` (`store`).
