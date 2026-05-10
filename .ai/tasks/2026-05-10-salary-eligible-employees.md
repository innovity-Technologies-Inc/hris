# Task: Fix Salary Process Eligible Employees View

Fix the feature to view eligible employees for each salary process batch using existing data.

## 📝 Sub-tasks

### 1. Controller Update
- [ ] Update `App\Http\Controllers\Payroll\SalaryController::show` to ensure it correctly fetches and passes data.
- [ ] Use `process_id` for querying `Payroll` for better reliability if needed.

### 2. View Updates
- [ ] Fix `resources/views/payroll/salary/view.blade.php`.
    - Change "Eligible Employees for Bonus" to "Eligible Employees for Salary".
    - Update variable usage from `$bonuses` to `$salaryes` (as passed by the controller).
    - Fix the back button route (should point to `salary.index`).
    - Ensure it correctly displays employee data (Profile, ID, Name, Total Salary).

### 3. Finalization
- [ ] Update `project_doc.md`.
- [ ] Run `php artisan optimize`.
- [ ] Commit changes.

## 🧪 Verification
- [ ] Click "View Details" on the Salary Process index page, then "View Eligible Employees".
- [ ] Verify the table displays the correct list of employees with their processed salary data.
