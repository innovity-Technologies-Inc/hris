# Task: Auto-select logged-in user in Clock In / Out page

## Problem
Currently, the logged-in user's employee record is not guaranteed to be in the dropdown, nor is it reliably selected and initialized on page load.

## Proposed Solution
1. Update `AttendancesController@clock_in_out` to include the logged-in user's employee record in the retrieved employees collection.
2. In `clock_in_out.blade.php`, update the JavaScript to use a helper function `getSelectedEmployeeId()` that retrieves the employee ID, checking both the select element and the hidden input, ensuring compatibility when the select is disabled.

## Execution Steps
1. **Controller Update**:
   - Update `app/Http/Controllers/Attendance/AttendancesController.php` to include the logged-in user's employee ID in the query filter.
2. **View JS Update**:
   - Update `resources/views/attendance/clock_in_out.blade.php` to use `getSelectedEmployeeId()`.
3. **Verification**:
   - Verify tests and commit.
