# Task: Implement Geofencing for On-Site Clock-In

## Problem
Currently, employees can clock in "On-Site" from anywhere without location verification. We need to verify that they are within the covering radius of their assigned branch/company location.

## Proposed Solution
1. Update `DataController@getAttendanceDetails` / `attendanceRecords` to include `branch` (assigned to employee) and `covering_radius` (loaded from database settings) in the JSON response.
2. In `clock_in_out.blade.php`, bind to workstation dropdown changes and:
   - Perform browser Geolocation lookup.
   - Run Haversine formula to compute distance from branch.
   - Show/hide the "Clock In" button and display location/permission messages based on verification.

## Execution Steps
1. **Controller API Update**:
   - Update `app/Http/Controllers/DataController.php` to fetch employee office info, current branch location, and `services.google.maps_radius` config, and append them to the attendance status payload.
2. **View Geolocation implementation**:
   - Update `resources/views/attendance/clock_in_out.blade.php`:
     - Add warning note HTML element for location alerts.
     - Implement JavaScript functions for distance calculation and geolocation verification.
     - Add change event listener on `#workstationSelect` calling verification when On-Site is selected.
3. **Verification**:
   - Create feature test `tests/Feature/Attendance/AttendanceGeofencingTest.php` to assert branch details are returned in attendance AJAX details.
   - Verify tests and commit.
