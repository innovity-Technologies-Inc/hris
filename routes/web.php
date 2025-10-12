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
use App\Http\Controllers\SectionController;

Route::get('/', function () {
    return view('welcome');
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
        Route::delete('divisions/{id}', 'destroy')->name('divisions.destroy');
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
});


Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
