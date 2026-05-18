# Task: Employee Profile RBAC Edit Buttons

Wrap "Edit" buttons in employee profile partials with `@can('employee-management.edit')` permission checks.

## Status: COMPLETED
## Assigned to: Gemini CLI

## Tasks:
- [x] Wrap Edit button in `resources/views/employees/partials/profile_view/bank_accounts.blade.php` <!-- id: 0 -->
- [x] Wrap Edit button in `resources/views/employees/partials/profile_view/education_info.blade.php` <!-- id: 1 -->
- [x] Wrap Edit button in `resources/views/employees/partials/profile_view/eligible_plans_info.blade.php` <!-- id: 2 -->
- [x] Wrap Edit button in `resources/views/employees/partials/profile_view/nominee_information.blade.php` <!-- id: 3 -->
- [x] Wrap Edit button in `resources/views/employees/partials/profile_view/office_info.blade.php` <!-- id: 4 -->
- [x] Wrap Edit button in `resources/views/employees/partials/profile_view/salary_breakdown.blade.php` <!-- id: 5 -->
- [x] Run `php artisan optimize` <!-- id: 6 -->
- [x] Verify with tests (if possible) or manual verification of code. <!-- id: 7 -->

## Verification Criteria:
- "Edit" buttons are only visible to users with 'employee-management.edit' permission.
- No syntax errors in Blade files.
