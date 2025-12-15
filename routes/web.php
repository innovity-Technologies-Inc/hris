<?php

use App\Http\Controllers\SettingsController;
use App\Http\Controllers\TofsilsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompanySetupController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CompanyLocationController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\SalaryGradesController;
use App\Http\Controllers\GazetteLocationsController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\BanksController;
use App\Http\Controllers\BranchesController;
use App\Http\Controllers\DesignationController;
use App\Http\Controllers\JobCreationController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\BankAccountsController;
use App\Http\Controllers\EmployeeProfileController;
use App\Http\Controllers\EmployeeEligibleController;
use App\Http\Controllers\EmployeeEducationExperienceTrainingController;
use App\Http\Controllers\EmployeeSalaryBreakdownController;
use App\Http\Controllers\EmployeeNomineeController;
use App\Http\Controllers\EmployeeBankAccountController;
use App\Http\Controllers\MealPlansController;
use App\Http\Controllers\ShiftPlanController;
use App\Http\Controllers\OrganizationStructureController;
use App\Http\Controllers\OTPlanController;
use App\Http\Controllers\LeavePlanController;
use App\Http\Controllers\RosterPlansController;
use App\Http\Controllers\OffDayPlansController;
use App\Http\Controllers\EmployeePlansController;


Route::get('test', function () {
   return view('attendance.daily_sheet');
});

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');




Route::prefix('company-setup')->group(function () {
    Route::get('bulk-upload', function () {
        return view('company_setup.bulk_uploads.form');
    })->name('company_setup.bulk_upload');

    Route::controller(CompanySetupController::class)->group(function () {
        Route::get('groups', 'groupIndex')->name('groups.index');
        Route::post('groups/save', 'groupSave')->name('groups.save');
        Route::delete('groups/delete/{id}', 'groupDelete')->name('groups.delete');

        Route::get('company-types', 'companyTypeIndex')->name('company_types.index');
        Route::post('company-types/save', 'companyTypeSave')->name('company_types.save');
        Route::delete('company-types/delete/{id}', 'companyTypeDelete')->name('company_types.delete');

        Route::get('companies', 'companyIndex')->name('companies.index');
        Route::get('companies/create', 'companyCreate')->name('companies.create');
        Route::post('companies/store', 'companyStore')->name('companies.store');
        Route::get('companies/edit/{id}', 'companyEdit')->name('companies.edit');
        Route::put('companies/{id}/update', 'companyUpdate')->name('companies.update');
        Route::delete('companies/delete/{id}', 'companyDelete')->name('companies.delete');
    });

    Route::controller(TofsilsController::class)->group(function () {
        Route::get('tofsils', 'index')->name('tofsils.index');
        Route::get('tofsils/create', 'create')->name('tofsils.create');
        Route::post('tofsils/store', 'store')->name('tofsils.store');
        Route::get('tofsils/edit/{id}', 'edit')->name('tofsils.edit');
        Route::put('tofsils/{id}/update', 'update')->name('tofsils.update');
        Route::delete('tofsils/delete/{id}', 'delete')->name('tofsils.delete');
    });

    Route::controller(SalaryGradesController::class)->group(function () {
        Route::get('salary_grades', 'index')->name('salary_grades.index');
        Route::get('salary_grades/create', 'create')->name('salary_grades.create');
        Route::post('salary_grades/store', 'store')->name('salary_grades.store');
        Route::get('salary_grades/edit/{id}', 'edit')->name('salary_grades.edit');
        Route::put('salary_grades/{id}/update', 'update')->name('salary_grades.update');
        Route::delete('salary_grades/delete/{id}', 'delete')->name('salary_grades.delete');
    });

    Route::controller(GazetteLocationsController::class)->group(function () {
        Route::get('gazette_locations', 'index')->name('gazette_locations.index');
        Route::get('gazette_locations/create', 'create')->name('gazette_locations.create');
        Route::post('gazette_locations/store', 'store')->name('gazette_locations.store');
        Route::get('gazette_locations/edit/{id}', 'edit')->name('gazette_locations.edit');
        Route::put('gazette_locations/{id}/update', 'update')->name('gazette_locations.update');
        Route::delete('gazette_locations/delete/{id}', 'delete')->name('gazette_locations.delete');
    });

    Route::controller(CompanyLocationController::class)->group(function () {
        Route::get('company-locations', 'index')->name('company_locations.index');
        Route::get('company-locations/create', 'create')->name('company_locations.create');
        Route::post('company-locations', 'store')->name('company_locations.store');
        Route::get('company-locations/{id}/edit', 'edit')->name('company_locations.edit');
        Route::put('company-locations/{id}', 'update')->name('company_locations.update');
        Route::delete('company-locations/{id}', 'destroy')->name('company_locations.destroy');
    });

    Route::controller(DivisionController::class)->group(function () {
        Route::get('divisions', 'index')->name('divisions.index');
        Route::get('divisions/create', 'create')->name('divisions.create');
        Route::post('divisions', 'store')->name('divisions.store');
        Route::get('divisions/{id}/edit', 'edit')->name('divisions.edit');
        Route::put('divisions/{id}', 'update')->name('divisions.update');
        Route::delete('divisions/{id}', 'destroy')->name('divisions.delete');
    });
    Route::controller(DepartmentController::class)->group(function () {
        Route::get('departments', 'index')->name('departments.index');
        Route::get('departments/create', 'create')->name('departments.create');
        Route::post('departments', 'store')->name('departments.store');
        Route::get('departments/{id}/edit', 'edit')->name('departments.edit');
        Route::put('departments/{id}', 'update')->name('departments.update');
        Route::delete('departments/{id}', 'destroy')->name('departments.delete');
    });
    Route::controller(SectionController::class)->group(function () {
        Route::get('sections', 'index')->name('sections.index');
        Route::get('sections/create', 'create')->name('sections.create');
        Route::post('sections', 'store')->name('sections.store');
        Route::get('sections/{id}/edit', 'edit')->name('sections.edit');
        Route::put('sections/{id}', 'update')->name('sections.update');
        Route::delete('sections/{id}', 'destroy')->name('sections.delete');
    });
    Route::controller(DesignationController::class)->group(function () {
        Route::get('designations', 'index')->name('designations.index');
        Route::get('designations/create', 'create')->name('designations.create');
        Route::post('designations', 'store')->name('designations.store');
        Route::get('designations/{id}/edit', 'edit')->name('designations.edit');
        Route::put('designations/{id}', 'update')->name('designations.update');
        Route::delete('designations/{id}', 'destroy')->name('designations.delete');
    });
    Route::controller(JobCreationController::class)->group(function () {
        Route::get('job_creations', 'index')->name('job_creations.index');
        Route::get('job_creations/create', 'create')->name('job_creations.create');
        Route::post('job_creations', 'store')->name('job_creations.store');
        Route::get('job_creations/{id}/edit', 'edit')->name('job_creations.edit');
        Route::put('job_creations/{id}', 'update')->name('job_creations.update');
        Route::delete('job_creations/{id}', 'destroy')->name('job_creations.delete');
    });

    Route::controller(BanksController::class)->group(function () {
            Route::get('banks', 'index')->name('banks.index');
            Route::get('banks/create', 'create')->name('banks.create');
            Route::post('banks', 'store')->name('banks.store');
            Route::get('banks/{id}/edit', 'edit')->name('banks.edit');
            Route::put('banks/{id}', 'update')->name('banks.update');
            Route::delete('banks/{id}', 'delete')->name('banks.delete');
        });

    Route::controller(BranchesController::class)->group(function () {
        Route::get('branches', 'index')->name('branches.index');
        Route::get('branches/create', 'create')->name('branches.create');
        Route::post('branches', 'store')->name('branches.store');
        Route::get('branches/{id}/edit', 'edit')->name('branches.edit');
        Route::put('branches/{id}', 'update')->name('branches.update');
        Route::delete('branches/{id}', 'delete')->name('branches.delete');
    });

    Route::controller(BankAccountsController::class)->group(function () {
        Route::get('bank-accounts', 'index')->name('bank_accounts.index');
        Route::get('bank-accounts/create', 'create')->name('bank_accounts.create');
        Route::post('bank-accounts', 'store')->name('bank_accounts.store');
        Route::get('bank-accounts/{id}/edit', 'edit')->name('bank_accounts.edit');
        Route::put('bank-accounts/{id}', 'update')->name('bank_accounts.update');
        Route::delete('bank-accounts/{id}', 'delete')->name('bank_accounts.delete');
    });
});

Route::prefix('employees')->group(function () {

    Route::controller(EmployeeProfileController::class)->group(function () {
        Route::get('/', 'index')->name('employees.index');
        Route::get('import', 'bulkEmployeeImportSections')->name('employees.import');
        Route::get('profile/{id}/general-informations', 'profileView')->name('employees.profile.general_informations');
        Route::get('general-informations/create', 'generalInfoCreate')->name('employees.general_informations.create');
        Route::post('general-informations/store', 'generalInfoStore')->name('employees.general_informations.store');
        Route::get('general-informations/edit/{id}', 'generalInfoEdit')->name('employees.general_informations.edit');
        Route::put('general-informations/{id}/update', 'generalInfoUpdate')->name('employees.general_informations.update');
        Route::post('general-informations/import', 'generalInfoImport')->name('employees.general_informations.import');
        Route::get('office-informations/create/{id}', 'officeInfoCreate')->name('employees.office_informations.create');
        Route::post('office-informations/store', 'officeInfoStore')->name('employees.office_informations.store');
        Route::get('office-informations/edit/{id}', 'officeInfoEdit')->name('employees.office_informations.edit');
        Route::put('office-informations/{id}/update', 'officeInfoUpdate')->name('employees.office_informations.update');
        Route::post('office-informations/import', 'officeInfoImport')->name('employees.office_informations.import');
        Route::get('profile/{id}/office-informations', 'showOfficeInfo')->name('employees.profile.office_informations');

    });

        Route::controller(EmployeeEligibleController::class)->group(function(){
        // Put the specific routes before the parameterized routes
        Route::get('eligible-plans/create/{id}', 'create')->name('employees.eligible_plans.create');
        Route::post('eligible-plans/store', 'store')->name('employees.eligible_plans.store');
        Route::get('eligible-plans/edit/{id}', 'edit')->name('employees.eligible_plans.edit');
        Route::put('eligible-plans/{id}/update', 'update')->name('employees.eligible_plans.update');
        Route::get('profile/{id}/eligible-plans', 'show')->name('employees.profile.eligible_plans');
        Route::post('eligible-plans/import', 'import')->name('employees.eligible_plans.import');
        });

    Route::controller(EmployeeEducationExperienceTrainingController::class)->group(function(){
        Route::get('education-information/create/{id}', 'create')->name('employees.education_information.create');
        Route::post('education-information/store', 'store')->name('employees.education_information.store');
        Route::get('profile/{id}/education-information', 'show')->name('employees.profile.education_information');
        Route::get('education-information/edit/{id}', 'edit')->name('employees.education_information.edit');
        Route::put('education-information/{id}/update', 'update')->name('employees.education_information.update');
        Route::post('education-information/import', 'import')->name('employees.education_information.import');
    });

    Route::controller(EmployeeNomineeController::class)->group(function(){
        Route::get('nominee-information/create/{id}', 'create')->name('employees.nominee_information.create');
        Route::post('nominee-information/store', 'store')->name('employees.nominee_information.store');
        Route::get('profile/{id}/nominee-information', 'show')->name('employees.profile.nominee_information');
        Route::get('nominee-information/edit/{id}', 'edit')->name('employees.nominee_information.edit');
        Route::put('nominee-information/{id}/update', 'update')->name('employees.nominee_information.update');
        Route::post('nominee-information/import', 'import')->name('employees.nominee_information.import');

    });

    Route::controller(EmployeeSalaryBreakdownController::class)->group(function(){
        Route::get('salary-breakdown/create/{id}', 'create')->name('employees.salary_breakdown.create');
        Route::post('salary-breakdown/store', 'store')->name('employees.salary_breakdown.store');
        Route::get('profile/{id}/salary-breakdown', 'show')->name('employees.profile.salary_breakdown');
        Route::get('salary-breakdown/edit/{id}', 'edit')->name('employees.salary_breakdown.edit');
        Route::put('salary-breakdown/{id}/update', 'update')->name('employees.salary_breakdown.update');
        Route::post('salary-breakdown/import', 'import')->name('employees.salary_breakdown.import');
    });

    Route::controller(EmployeeBankAccountController::class)->group(function () {
        Route::get('bank-accounts/create/{id}', 'create')->name('employees.bank_accounts.create');
        Route::post('bank-accounts/store', 'store')->name('employees.bank_accounts.store');
        Route::get('profile/{id}/bank-accounts', 'show')->name('employees.profile.bank_accounts');
        Route::get('bank-accounts/edit/{id}', 'edit')->name('employees.bank_accounts.edit');
        Route::put('bank-accounts/{id}/update', 'update')->name('employees.bank_accounts.update');
        Route::post('bank-accounts/import', 'import')->name('employees.bank_accounts.import');
    });

    Route::controller(EmployeePlansController::class)->group(function () {
        Route::get('profile/{id}/plans/{type}', 'plansView')->name('employees.profile.plans');
        Route::post('profile/plans/{type}/store', 'assignPlan')->name('employees.profile.plans.store');
        Route::post('profile/plans/{type}/remove/{id}', 'removePlan')->name('employees.profile.plans.remove');
        Route::delete('profile/plans/{type}/delete/{id}', 'deletePlan')->name('employees.profile.plans.delete');

    });

});

Route::prefix('plans')->group(function () {
    Route::get('bulk-upload', function () {
        return view('plans.bulk_uploads.form');
    })->name('plans.bulk_upload');

    Route::prefix('meal-plans')->group(function () {
        Route::controller(MealPlansController::class)->group(function(){
            Route::get('/', 'index')->name('plans.meal_plans.index');
            Route::post('store', 'store')->name('plans.meal_plans.store');
            Route::put('update/{id}', 'update')->name('plans.meal_plans.update');
            Route::delete('delete/{id}', 'delete')->name('plans.meal_plans.delete');
            Route::post('import', 'import')->name('plans.meal_plans.import');

        });
    });

    Route::prefix('shift-plans')->group(function () {
        Route::controller(ShiftPlanController::class)->group(function(){
            Route::get('/', 'index')->name('plans.shift_plans.index');
            Route::get('create', 'create')->name('plans.shift_plans.create');
            Route::post('store', 'store')->name('plans.shift_plans.store');
            Route::get('{id}', 'show')->name('plans.shift_plans.show');
            Route::get('edit/{id}', 'edit')->name('plans.shift_plans.edit');
            Route::put('update/{id}', 'update')->name('plans.shift_plans.update');
            Route::delete('delete/{id}', 'delete')->name('plans.shift_plans.delete');
            Route::post('import', 'import')->name('plans.shift_plans.import');

        });
    });

    Route::prefix('leave-plans')->group(function () {
        Route::controller(LeavePlanController::class)->group(function(){
            Route::get('/', 'index')->name('plans.leave_plans.index');
            Route::get('create', 'create')->name('plans.leave_plans.create');
            Route::post('store', 'store')->name('plans.leave_plans.store');
            Route::get('{id}', 'show')->name('plans.leave_plans.show');
            Route::get('edit/{id}', 'edit')->name('plans.leave_plans.edit');
            Route::put('update/{id}', 'update')->name('plans.leave_plans.update');
            Route::delete('delete/{id}', 'delete')->name('plans.leave_plans.delete');
            Route::post('import', 'import')->name('plans.leave_plans.import');

        });
    });

    Route::prefix('ot-plans')->group(function () {
        Route::controller(OTPlanController::class)->group(function(){
            Route::get('/', 'index')->name('plans.ot_plans.index');
            Route::get('create', 'create')->name('plans.ot_plans.create');
            Route::post('store', 'store')->name('plans.ot_plans.store');
            Route::get('{id}', 'show')->name('plans.ot_plans.show');
            Route::get('edit/{id}', 'edit')->name('plans.ot_plans.edit');
            Route::put('update/{id}', 'update')->name('plans.ot_plans.update');
            Route::delete('delete/{id}', 'delete')->name('plans.ot_plans.delete');
            Route::post('import', 'import')->name('plans.ot_plans.import');

        });
    });

    Route::prefix('roster-plans')->group(function () {
        Route::controller(RosterPlansController::class)->group(function(){
            Route::get('/', 'index')->name('plans.roster_plans.index');
            Route::get('create', 'create')->name('plans.roster_plans.create');
            Route::post('store', 'store')->name('plans.roster_plans.store');
            Route::get('{id}', 'show')->name('plans.roster_plans.show');
            Route::get('edit/{id}', 'edit')->name('plans.roster_plans.edit');
            Route::put('update/{id}', 'update')->name('plans.roster_plans.update');
            Route::delete('delete/{id}', 'delete')->name('plans.roster_plans.delete');
            Route::post('import', 'import')->name('plans.roster_plans.import');
        });
    });

    Route::prefix('off-day-plans')->group(function () {
        Route::controller(OffDayPlansController::class)->group(function(){
            Route::get('/', 'index')->name('plans.off_day_plans.index');
            Route::get('create', 'create')->name('plans.off_day_plans.create');
            Route::post('store', 'store')->name('plans.off_day_plans.store');
            Route::get('{id}', 'show')->name('plans.off_day_plans.show');
            Route::get('edit/{id}', 'edit')->name('plans.off_day_plans.edit');
            Route::put('update/{id}', 'update')->name('plans.off_day_plans.update');
            Route::delete('delete/{id}', 'delete')->name('plans.off_day_plans.delete');
            Route::post('import', 'import')->name('plans.off_day_plans.import');
        });
    });

});

Route::controller(EmployeeProfileController::class)->group(function () {
    Route::get('get-grades/{tofsil_id}', 'getGradeByAct');
    Route::get('get-units/{company_id}', 'getUnit');
    Route::get('get-divisions/{company_id}/{unit_id}', 'getDivision');
    Route::get('get-departments/{company_id}/{unit_id}/{division_id}', 'getDepartment');
    Route::get('get-sections/{company_id}/{unit_id}/{division_id}/{department_id}', 'getSection');
    Route::get('get-branches/{bank_id}', 'getBranchesByBank');
});

Route::controller(EmployeePlansController::class)->group(function (){
    Route::get('get-meal-plans/{type}', 'getMealPlanByType');
    Route::get('get-meal-plan-details/{id}', 'getMealPlanDetails');
    Route::get('get-offday-plan-details/{id}', 'getOffDayPlanDetails');

});

Route::controller(RosterPlansController::class)->group(function () {
    Route::get('get-shift-details/{shift_id}', 'getShiftDetails');
});

Route::controller(OrganizationStructureController::class)->group(function () {
    Route::get('organization-structure', 'index')->name('organization-structure.index');
    Route::get('organization-structure/create', 'create')->name('organization-structure.create');
    Route::post('organization-structure', 'store')->name('organization-structure.store');
    Route::get('organization-structure/{id}/edit', 'edit')->name('organization-structure.edit');
    Route::put('organization-structure/{id}', 'update')->name('organization-structure.update');
    Route::delete('organization-structure/{id}', 'destroy')->name('organization-structure.destroy');
});

Route::prefix('settings')->group(function () {
    Route::controller(SettingsController::class)->group(function (){
       Route::get('general-settings', 'generalSettingIndex')->name('settings.general_settings');
       Route::post('general-settings/save', 'generalSettingSave')->name('settings.general_settings.store');

    });
});
