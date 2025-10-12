<?php

use App\Http\Controllers\TofsilsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompanySetupController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SalaryGradesController;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('company-setup')->group(function (){
    Route::controller(CompanySetupController::class)->group(function (){
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

    Route::controller(TofsilsController::class)->group(function (){
        Route::get('tofsils', 'index')->name('tofsils.index');
        Route::get('tofsils/create', 'create')->name('tofsils.create');
        Route::post('tofsils/store', 'store')->name('tofsils.store');
        Route::get('tofsils/edit/{id}', 'edit')->name('tofsils.edit');
        Route::put('tofsils/{id}/update', 'update')->name('tofsils.update');
        Route::delete('tofsils/delete/{id}', 'delete')->name('tofsils.delete');
    });

    Route::controller(SalaryGradesController::class)->group(function (){
        Route::get('salary_grades', 'index')->name('salary_grades.index');
        Route::get('salary_grades/create', 'create')->name('salary_grades.create');
        Route::post('salary_grades/store', 'store')->name('salary_grades.store');
        Route::get('salary_grades/edit/{id}', 'edit')->name('salary_grades.edit');
        Route::put('salary_grades/{id}/update', 'update')->name('salary_grades.update');
        Route::delete('salary_grades/delete/{id}', 'delete')->name('salary_grades.delete');
    });

});

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
