# Task: Update Namespaces for Employee Module

Update all references to classes that were moved into the `Employee` sub-namespace.

## Requirements
- Update Models, Services, and Controllers references throughout the codebase.
- Ensure all imports and fully qualified names are updated.
- Maintain application stability (run `php artisan optimize`).

## Sub-tasks
- [ ] Replace Model references in `app/`, `routes/`, `database/`, `tests/`, `resources/views/`.
- [ ] Replace Service references.
- [ ] Replace Controller references.
- [ ] Update `routes/web.php`.
- [ ] Verify with `php artisan optimize`.
- [ ] Run basic tests to ensure no regressions.

## Replacements

### Models
- `App\Models\Employee` -> `App\Models\Employee\Employee`
- `App\Models\EmployeeBankAccount` -> `App\Models\Employee\EmployeeBankAccount`
- `App\Models\EmployeeEducationExperienceTraining` -> `App\Models\Employee\EmployeeEducationExperienceTraining`
- `App\Models\EmployeeEligiblePlan` -> `App\Models\Employee\EmployeeEligiblePlan`
- `App\Models\EmployeeEmploymentHistory` -> `App\Models\Employee\EmployeeEmploymentHistory`
- `App\Models\EmployeeId` -> `App\Models\Employee\EmployeeId`
- `App\Models\EmployeeNominee` -> `App\Models\Employee\EmployeeNominee`
- `App\Models\EmployeeOfficeInfo` -> `App\Models\Employee\EmployeeOfficeInfo`
- `App\Models\EmployeeSalaryBreakdown` -> `App\Models\Employee\EmployeeSalaryBreakdown`
- `App\Models\EmployeeBonusPlan` -> `App\Models\Employee\EmployeeBonusPlan`
- `App\Models\EmployeeLeavePlan` -> `App\Models\Employee\EmployeeLeavePlan`
- `App\Models\EmployeeMealPlan` -> `App\Models\Employee\EmployeeMealPlan`
- `App\Models\EmployeeOffdayPlan` -> `App\Models\Employee\EmployeeOffdayPlan`
- `App\Models\EmployeeOtPlan` -> `App\Models\Employee\EmployeeOtPlan`
- `App\Models\EmployeeRosterPlan` -> `App\Models\Employee\EmployeeRosterPlan`
- `App\Models\EmployeeShiftPlan` -> `App\Models\Employee\EmployeeShiftPlan`

### Services
- `App\Services\EmployeeServices` -> `App\Services\Employee\EmployeeServices`
- `App\Services\EmployeePlansServices` -> `App\Services\Employee\EmployeePlansServices`

### Controllers
- `App\Http\Controllers\EmployeeBankAccountController` -> `App\Http\Controllers\Employee\EmployeeBankAccountController`
- `App\Http\Controllers\EmployeeEducationExperienceTrainingController` -> `App\Http\Controllers\Employee\EmployeeEducationExperienceTrainingController`
- `App\Http\Controllers\EmployeeEligibleController` -> `App\Http\Controllers\Employee\EmployeeEligibleController`
- `App\Http\Controllers\EmployeeEmploymentHistoryController` -> `App\Http\Controllers\Employee\EmployeeEmploymentHistoryController`
- `App\Http\Controllers\EmployeeIdCardController` -> `App\Http\Controllers\Employee\EmployeeIdCardController`
- `App\Http\Controllers\EmployeeNomineeController` -> `App\Http\Controllers\Employee\EmployeeNomineeController`
- `App\Http\Controllers\EmployeePlansController` -> `App\Http\Controllers\Employee\EmployeePlansController`
- `App\Http\Controllers\EmployeeProfileController` -> `App\Http\Controllers\Employee\EmployeeProfileController`
- `App\Http\Controllers\EmployeeReviewController` -> `App\Http\Controllers\Employee\EmployeeReviewController`
- `App\Http\Controllers\EmployeeSalaryBreakdownController` -> `App\Http\Controllers\Employee\EmployeeSalaryBreakdownController`
- `App\Http\Controllers\EmployeeSearchController` -> `App\Http\Controllers\Employee\EmployeeSearchController`
- `App\Http\Controllers\ProfileController` -> `App\Http\Controllers\Employee\ProfileController`
