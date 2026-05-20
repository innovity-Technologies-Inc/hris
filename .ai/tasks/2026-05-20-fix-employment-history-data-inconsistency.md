# Task: Fix Employment History Data Inconsistency

Fix the `Undefined array key "joining_date"` error in the employment history profile view and ensure data consistency in seeders.

## 🛠️ Requirements
- Fix `EmployeeSeeder.php` to use correct keys (`company_name`, `joining_date`) in `generateEmploymentHistoryData`.
- Update `resources/views/employee/partials/profile_view/employment_history.blade.php` to handle missing keys gracefully.
- Verify changes by re-seeding and viewing the profile.

## 📝 Sub-tasks
1. [x] Fix `EmployeeSeeder.php` dummy data generation.
2. [x] Update `employment_history.blade.php` with robust null/key checks.
3. [x] (Optional) Add a test case to verify the view doesn't crash with missing data.
4. [x] Run `php artisan db:seed --class=EmployeeSeeder` and verify the fix.

## ✅ Verification Criteria
- [x] No `Undefined array key "joining_date"` error when viewing employment history.
- [x] Employment history data displays correctly for seeded employees.
- [x] View handles legacy/malformed data without crashing.
