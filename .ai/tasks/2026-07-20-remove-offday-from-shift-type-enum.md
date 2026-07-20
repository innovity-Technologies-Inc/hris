# Task: Remove 'Off-Day' from Attendance `shift_type` ENUM

## Solution Outline
1. Create a migration `2026_07_20_000005_remove_offday_from_shift_type_enum.php`:
   - Update any remaining `attendance` rows where `shift_type = 'Off-Day'` to `'paid-off'`.
   - Change `shift_type` ENUM definition to `ENUM('Regular', 'Roster', 'paid-off', 'comp-off')`.
2. Update `AttendanceServices.php`: Remove `'Off-Day'` check from `getWorkType()`.
3. Update `PayrollServices.php`: Filter `shift_type` using `['paid-off']`.
4. Run `php artisan migrate`.
5. Run `php artisan optimize`.
6. Commit changes.
