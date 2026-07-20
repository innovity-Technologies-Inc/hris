# Task: Remove EmployeeCompOffHistory Table & Clean Up References

## Execution Steps
1. Drop `employee_comp_off_histories` table from database and delete migration file `2026_07_20_000007_create_employee_comp_off_histories_table.php`.
2. Delete model `App\Models\Employee\EmployeeCompOffHistory.php`.
3. Clean up `AttendanceServices.php` to remove `EmployeeCompOffHistory` calls.
4. Clean up `LeavesController.php` to remove `EmployeeCompOffHistory` calls.
5. Run `php artisan optimize`.
6. Commit changes.
