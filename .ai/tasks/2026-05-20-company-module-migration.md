# Task: Company Module Migration

Migrate the Company module files to their respective sub-namespaces and update all references.

## Requirements
- Move Controllers to `app/Http/Controllers/Company/`.
- Move Models to `app/Models/Company/`.
- Update internal namespaces and imports in moved files.
- Update global references in `app/`, `routes/`, `database/`, `tests/`, and `resources/views/`.
- Verify with `php artisan optimize`.

## Sub-tasks
- [ ] Move Controller files and update namespaces.
- [ ] Move Model files and update namespaces.
- [ ] Global search and replace Model references.
- [ ] Global search and replace Controller references.
- [ ] Update `routes/web.php`.
- [ ] Run `php artisan optimize`.
- [ ] Verify functionality.

## Replacements

### Models
- `App\Models\Bank` -> `App\Models\Company\Bank`
- `App\Models\BankAccount` -> `App\Models\Company\BankAccount`
- `App\Models\Branch` -> `App\Models\Company\Branch`
- `App\Models\Company` -> `App\Models\Company\Company`
- `App\Models\CompanyLocation` -> `App\Models\Company\CompanyLocation`
- `App\Models\CompanyType` -> `App\Models\Company\CompanyType`
- `App\Models\Department` -> `App\Models\Company\Department`
- `App\Models\Designation` -> `App\Models\Company\Designation`
- `App\Models\Division` -> `App\Models\Company\Division`
- `App\Models\GazetteLocation` -> `App\Models\Company\GazetteLocation`
- `App\Models\Holiday` -> `App\Models\Company\Holiday`
- `App\Models\JobCreation` -> `App\Models\Company\JobCreation`
- `App\Models\SalaryGrade` -> `App\Models\Company\SalaryGrade`
- `App\Models\Section` -> `App\Models\Company\Section`
- `App\Models\Tofsil.php` -> `App\Models\Company\Tofsil`

### Controllers
- `App\Http\Controllers\BanksController` -> `App\Http\Controllers\Company\BanksController`
- `App\Http\Controllers\BankAccountsController` -> `App\Http\Controllers\Company\BankAccountsController`
- `App\Http\Controllers\BranchesController` -> `App\Http\Controllers\Company\BranchesController`
- `App\Http\Controllers\CompanyLocationController` -> `App\Http\Controllers\Company\CompanyLocationController`
- `App\Http\Controllers\CompanySetupController` -> `App\Http\Controllers\Company\CompanySetupController`
- `App\Http\Controllers\DepartmentController` -> `App\Http\Controllers\Company\DepartmentController`
- `App\Http\Controllers\DesignationController` -> `App\Http\Controllers\Company\DesignationController`
- `App\Http\Controllers\DivisionController` -> `App\Http\Controllers\Company\DivisionController`
- `App\Http\Controllers\GazetteLocationsController` -> `App\Http\Controllers\Company\GazetteLocationsController`
- `App\Http\Controllers\HolidayController` -> `App\Http\Controllers\Company\HolidayController`
- `App\Http\Controllers\JobCreationController` -> `App\Http\Controllers\Company\JobCreationController`
- `App\Http\Controllers\SalaryGradesController` -> `App\Http\Controllers\Company\SalaryGradesController`
- `App\Http\Controllers\SectionController` -> `App\Http\Controllers\Company\SectionController`
- `App\Http\Controllers\TofsilsController` -> `App\Http\Controllers\Company\TofsilsController`
