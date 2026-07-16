# Task: Fix Employee input selection display in clock-in page

## Problem
In some users/environments, the selected employee is not showing in the employee input box on the clock-in page.

## Proposed Solution
1. Update `AttendancesController@clock_in_out` and the view PHP block to resolve the logged-in employee ID via both `auth()->user()->employee_id` and `Employee::where('user_id', auth()->id())` columns.
2. Explicitly pass and double check this value.

## Execution Steps
1. **Controller Update**:
   - Update `app/Http/Controllers/Attendance/AttendancesController.php` to resolve `$loggedInEmployeeId` using both columns and pass it to the view.
2. **View Update**:
   - Update `resources/views/attendance/clock_in_out.blade.php` to use the passed or resolved `$loggedInEmployeeId` from the controller.
3. **Verification**:
   - Verify tests and commit.
