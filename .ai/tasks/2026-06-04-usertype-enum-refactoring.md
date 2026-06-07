# Task: UserType Enum Refactoring

Refactor all `user_type` string comparisons to use the `App\Enums\UserType` enum across the codebase to ensure compatibility with the `User` model's enum casting.

## Tasks

### PHP Files Refactoring
- [ ] Refactor `app/Http/Controllers/Employee/EmployeeReportController.php`
- [ ] Refactor `app/Http/Controllers/Employee/EmployeeSalaryBreakdownController.php`
- [ ] Refactor `app/Http/Controllers/Employee/EmployeeSearchController.php`
- [ ] Refactor `app/Http/Controllers/Employee/NIDVerificationController.php`
- [ ] Refactor `app/Http/Controllers/Movement/EmployeeMovementsController.php`
- [ ] Refactor `app/Http/Controllers/Payroll/SalaryController.php`
- [ ] Refactor `app/Http/Requests/Transfer/StoreTransferRequest.php`
- [ ] Refactor `app/Services/Transfer/TransferServices.php`
- [ ] Refactor `app/Traits/OrganizationScoped.php`

### Blade Templates Refactoring
- [ ] Refactor `resources/views/employee/partials/creation_button.blade.php`
- [ ] Refactor `resources/views/employee/partials/profile_view/education_info.blade.php`
- [ ] Refactor `resources/views/employee/partials/profile_view/employment_history.blade.php`
- [ ] Refactor `resources/views/employee/partials/profile_view/general_info.blade.php`
- [ ] Refactor `resources/views/employee/partials/profile_view/nominee_information.blade.php`
- [ ] Refactor `resources/views/leave/create.blade.php`
- [ ] Refactor `resources/views/movement/form.blade.php`
- [ ] Refactor `resources/views/structure/partials/sidebar.blade.php`

## Verification
- [ ] Run `php artisan optimize`
- [ ] Verify that the application still functions correctly (logins, organizational scoping, etc.)
