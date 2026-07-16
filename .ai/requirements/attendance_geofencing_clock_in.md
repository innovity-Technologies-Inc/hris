# Requirements for Attendance Geofencing Clock-In

## 1. Background
When an employee clocks in and selects "On-Site" as their workstation, the system should verify that their current physical location matches the location coordinates (latitude and longitude) of their assigned branch/business unit within the allowed radius set in the Google Map settings.

## 2. Functional Requirements
- **Branch Coordinates Retrieval**:
    - Include the assigned branch's coordinates (`latitude`, `longitude`) and the system's covering radius (`covering_radius`) in the attendance details response JSON payload.
- **Frontend Location Validation**:
    - When workstation "On-Site" is selected:
        - Intercept the action and fetch user physical coordinates using the browser Geolocation API.
        - Calculate the distance between the employee's current location and the branch location.
        - If the user is within the allowed radius:
            - Make the "Clock In" button visible.
        - If the user is outside the allowed radius:
            - Hide the "Clock In" button.
            - Display a clear error note: "You are out of the office area."
- **Fallback**:
    - If geolocation access is denied or unsupported, show a note requesting location permissions.
    - If other workstations are selected ("Remote", "Work-From-Home"), bypass the geofencing check and always show the "Clock In" button.

## 3. Technical Requirements
- **Endpoint Update**: Modify `DataController@getAttendanceDetails` (specifically `attendanceRecords`) to return branch details and the Google Maps covering radius.
- **View Integration**: Add geolocation calculations and visibility toggle logic to `resources/views/attendance/clock_in_out.blade.php`.
