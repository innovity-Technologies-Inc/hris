# Requirements for Showing Latest Attendance Records First

## 1. Background
Users want to see the most recent clock-in/out attendance records at the top of the list for quick inspection.

## 2. Functional Requirements
- **Order by Latest**:
    - Sort the attendance records query in `AttendancesController@index` in descending order of `in_time`.
    - Apply the same sort order to the print view query in `AttendancesController@printIndex`.
