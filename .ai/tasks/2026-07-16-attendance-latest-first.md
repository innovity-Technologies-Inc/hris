# Task: Show latest attendance records first

## Problem
Currently, attendance records are displayed in ascending order (oldest first), which forces users to paginate to the end of the list to see today's data.

## Proposed Solution
Update `AttendancesController.php` to sort the Eloquent query using `orderBy('in_time', 'desc')` in both `index()` and `printIndex()`.

## Execution Steps
1. **Controller Update**:
   - Edit `app/Http/Controllers/Attendance/AttendancesController.php` to add `orderBy('in_time', 'desc')` to queries.
2. **Verification**:
   - Run tests and commit.
