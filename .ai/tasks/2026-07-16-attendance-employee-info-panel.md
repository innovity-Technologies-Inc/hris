# Task: Replace employee select box with information panel

## Problem
Employees clocking in should not have to interact with an employee selection dropdown. Instead, their name, ID, and branch location should be displayed directly in a read-only panel.

## Proposed Solution
1. Update `AttendancesController@clock_in_out` to load `$loggedInEmployee` with its assigned branch relationship.
2. In `clock_in_out.blade.php`, render a styled card displaying Name, ID, and Branch Location if `$loggedInEmployee` is set, with hidden inputs to maintain javascript compatibility. Otherwise, fallback to the dropdown list.

## Execution Steps
1. **Controller Update**:
   - Update `app/Http/Controllers/Attendance/AttendancesController.php` to fetch `$loggedInEmployee` with its related `officeInfo.getCurrentBusinessUnit`.
2. **View Update**:
   - Update `resources/views/attendance/clock_in_out.blade.php` to conditionally render the info card or fallback select element.
3. **Verification**:
   - Run full test suite and commit.
