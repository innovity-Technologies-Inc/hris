<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompanySetupController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CompanyLocationController;

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

    Route::controller(CompanyLocationController::class)->group(function () {
        Route::get('company-locations', 'index')->name('company_locations.index');
        Route::get('company-locations/create', 'create')->name('company_locations.create');
        Route::post('company-locations', 'store')->name('company_locations.store');
        Route::get('company-locations/{id}/edit', 'edit')->name('company_locations.edit');
        Route::put('company-locations/{id}', 'update')->name('company_locations.update');
        Route::delete('company-locations/{id}', 'destroy')->name('company_locations.destroy');
    });
});


Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
