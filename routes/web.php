<?php

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

Route::get('test', function () {
    // return view('employees.index');
    // return view('employees.partial.form');
    // return view('employees.office_informations.form');
    return view('employees.office_informations.office_info');
});

Route::prefix('company-setup')->group(function () {
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
        Route::get('{id}/profile', 'profileView')->name('employees.profile');
        Route::get('general-informations/create', 'generalInfoCreate')->name('employees.general_informations.create');
        Route::post('general-informations/store', 'generalInfoStore')->name('employees.general_informations.store');
        Route::get('general-informations/edit/{id}', 'generalInfoEdit')->name('employees.general_informations.edit');
        Route::put('general-informations/{id}/update', 'generalInfoUpdate')->name('employees.general_informations.update');


    });
});


Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
