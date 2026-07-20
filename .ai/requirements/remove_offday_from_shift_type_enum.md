# Requirement: Remove 'Off-Day' from Attendance `shift_type` ENUM

## Requirement Overview
Remove the legacy `'Off-Day'` value from the `attendance` table's `shift_type` ENUM since all off-day attendances are now explicitly categorized as either `'paid-off'` or `'comp-off'`.

## Detailed Specifications
1. **Database Schema & ENUM**:
   - Update any existing attendance rows with `shift_type = 'Off-Day'` to `shift_type = 'paid-off'`.
   - Alter `shift_type` column on `attendance` table to: `ENUM('Regular', 'Roster', 'paid-off', 'comp-off')`.

2. **Backend & Service Layer Updates**:
   - Update `AttendanceServices.php` and `PayrollServices.php` to remove references to legacy `'Off-Day'` shift_type.
   - Off-day attendance records are tagged exclusively as `'paid-off'` or `'comp-off'`.

3. **Views & Seeders**:
   - Check and clean up any remaining `'Off-Day'` references in attendance views and seeders.
