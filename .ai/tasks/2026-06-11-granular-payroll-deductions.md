# Task: Granular Payroll Deductions and Penalty Fix

## Status: ✅ Completed
## Priority: High

## 📝 Tasks

### 1. Database & Model Updates
- [x] Create migration to add `late_deduction_amount`, `excessive_late_deduction_amount`, `absent_deduction_amount`, `early_exit_deduction_amount` to `payrolls` table.
- [x] Update `App\Models\Payroll\Payroll` model `$fillable` array.

### 2. Service Logic Updates (`PayrollServices`)
- [x] Modify `deductionAmount` to return an associative array of detailed deductions.
- [x] Update `salaryProcess` to:
    - Extract detailed deductions from the updated `deductionAmount`.
    - Pass these details into the `Payroll::create` call.
- [x] Fix `salaryDelete` to reset `EmployeePenalty` status:
    - Identify penalties deducted for the given `process_id` (or date range/employee).
    - Update `status` from `deducted` to `approved`.

### 3. UI Updates
- [x] Update `resources/views/payroll/salary/payroll_view.blade.php` to show the granular deductions.
- [x] Create `individualBonusView` in `BonusController` and corresponding route.
- [x] Create `resources/views/payroll/bonus/individual_view.blade.php`.
- [x] Add "Details" button to `resources/views/payroll/bonus/view.blade.php`.

### 4. Data Fix & Seeding
- [x] Manually reset employee 200's penalty status to `approved` (for testing/immediate fix).
- [x] Run seeders to ensure environment is clean.

### 5. Finalization
- [x] Run `php artisan optimize`.
- [x] Verify the fix on employee 200.
- [x] Commit changes.

## 🧪 Verification
- [x] Re-run salary generation for June 2026.
- [x] Check if employee 200 now has 2000 TK penalty.
- [x] Check if detailed deductions are stored and displayed in the view.
