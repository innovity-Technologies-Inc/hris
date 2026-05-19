<?php

use App\Http\Controllers\AllowancePlanController;
use App\Http\Controllers\ApiKeyController;
use App\Http\Controllers\AttendancesController;
use App\Http\Controllers\BankAccountsController;
use App\Http\Controllers\BanksController;
use App\Http\Controllers\BonusPlanController;
use App\Http\Controllers\BranchesController;
use App\Http\Controllers\CompanyLocationController;
use App\Http\Controllers\CompanySetupController;
use App\Http\Controllers\DAPlanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\DeductionPlanController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DesignationController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\EmployeeBankAccountController;
use App\Http\Controllers\EmployeeEducationExperienceTrainingController;
use App\Http\Controllers\EmployeeEligibleController;
use App\Http\Controllers\EmployeeMovementsController;
use App\Http\Controllers\EmployeeNomineeController;
use App\Http\Controllers\EmployeePlansController;
use App\Http\Controllers\EmployeeReviewController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\EmployeeProfileController;
use App\Http\Controllers\EmployeeSalaryBreakdownController;
use App\Http\Controllers\EmployeeSearchController;
use App\Http\Controllers\GazetteLocationsController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\JobCreationController;
use App\Http\Controllers\LeavePlanController;
use App\Http\Controllers\LeavesController;
use App\Http\Controllers\MealPlansController;
use App\Http\Controllers\OffDayPlansController;
use App\Http\Controllers\OrganizationStructureController;
use App\Http\Controllers\OTPlanController;
use App\Http\Controllers\Payroll\IncrementController;
use App\Http\Controllers\Payroll\PromotionController;
use App\Http\Controllers\RosterPlansController;
use App\Http\Controllers\SalaryGradesController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ShiftPlanController;
use App\Http\Controllers\TAPlanController;
use App\Http\Controllers\TofsilsController;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

//get all assets file
Route::get('/assets-list', function() {
    $files = collect(File::allFiles(public_path('assets')))
        ->map(function($file) {
            return '/assets/' . str_replace(public_path('assets') . DIRECTORY_SEPARATOR, '', $file);
        });
    return response()->json($files);
});

Route::get('test', function () {
   return view('attendance.attendance_form_1');
});

// QR Code Examples Route
Route::get('qr-examples', function () {
    return view('examples.qr_code_examples');
})->name('qr.examples');

// ID Card Preview with Dummy Data
Route::get('id-card-preview', function () {
    return view('settings.id_design.designs.design_2');
})->name('id.card.preview');

Route::prefix('notifications')->middleware('auth')->group(function () {
    Route::controller(NotificationController::class)->group(function () {
        Route::get('header', 'getHeaderNotifications')->name('notifications.header');
        Route::post('{id}/mark-as-read', 'markAsRead')->name('notifications.mark-read');
        Route::post('mark-all-read', 'markAllAsRead')->name('notifications.mark-all-read');
    });
});

Route::get('/', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');




Route::prefix('company-setup')->middleware('auth')->group(function () {

    Route::get('bulk-upload', function () {
        return view('company_setup.bulk_uploads.form');
    })->name('company_setup.bulk_upload')->middleware('permission:employee-management.import');

    Route::controller(CompanySetupController::class)->group(function () {
        Route::middleware('permission:groups.view')->group(function () {
            Route::get('groups', 'groupIndex')->name('groups.index');
        });
        Route::middleware('permission:groups.create')->group(function () {
            Route::post('groups/save', 'groupSave')->name('groups.save');
        });
        Route::middleware('permission:groups.delete')->group(function () {
            Route::delete('groups/delete/{id}', 'groupDelete')->name('groups.delete');
        });

        Route::middleware('permission:company-types.view')->group(function () {
            Route::get('company-types', 'companyTypeIndex')->name('company_types.index');
        });
        Route::middleware('permission:company-types.create')->group(function () {
            Route::post('company-types/save', 'companyTypeSave')->name('company_types.save');
        });
        Route::middleware('permission:company-types.delete')->group(function () {
            Route::delete('company-types/delete/{id}', 'companyTypeDelete')->name('company_types.delete');
        });

        Route::middleware('permission:companies.view')->group(function () {
            Route::get('companies', 'companyIndex')->name('companies.index');
            Route::get('companies/edit/{id}', 'companyEdit')->name('companies.edit');
        });
        Route::middleware('permission:companies.create')->group(function () {
            Route::get('companies/create', 'companyCreate')->name('companies.create');
            Route::post('companies/store', 'companyStore')->name('companies.store');
        });
        Route::middleware('permission:companies.edit')->group(function () {
            Route::put('companies/{id}/update', 'companyUpdate')->name('companies.update');
        });
        Route::middleware('permission:companies.delete')->group(function () {
            Route::delete('companies/delete/{id}', 'companyDelete')->name('companies.delete');
        });
    });

    Route::controller(TofsilsController::class)->group(function () {
        Route::middleware('permission:salary-acts.view')->group(function () {
            Route::get('tofsils', 'index')->name('tofsils.index');
        });
        Route::middleware('permission:salary-acts.create')->group(function () {
            Route::get('tofsils/create', 'create')->name('tofsils.create');
            Route::post('tofsils/store', 'store')->name('tofsils.store');
        });
        Route::middleware('permission:salary-acts.edit')->group(function () {
            Route::get('tofsils/edit/{id}', 'edit')->name('tofsils.edit');
            Route::put('tofsils/{id}/update', 'update')->name('tofsils.update');
        });
        Route::middleware('permission:salary-acts.delete')->group(function () {
            Route::delete('tofsils/delete/{id}', 'delete')->name('tofsils.delete');
        });
    });

    Route::controller(SalaryGradesController::class)->group(function () {
        Route::middleware('permission:salary-grades.view')->group(function () {
            Route::get('salary_grades', 'index')->name('salary_grades.index');
        });
        Route::middleware('permission:salary-grades.create')->group(function () {
            Route::get('salary_grades/create', 'create')->name('salary_grades.create');
            Route::post('salary_grades/store', 'store')->name('salary_grades.store');
        });
        Route::middleware('permission:salary-grades.edit')->group(function () {
            Route::get('salary_grades/edit/{id}', 'edit')->name('salary_grades.edit');
            Route::put('salary_grades/{id}/update', 'update')->name('salary_grades.update');
        });
        Route::middleware('permission:salary-grades.delete')->group(function () {
            Route::delete('salary_grades/delete/{id}', 'delete')->name('salary_grades.delete');
        });
    });

    Route::controller(GazetteLocationsController::class)->group(function () {
        Route::middleware('permission:company-branches.view')->group(function () {
            Route::get('gazette_locations', 'index')->name('gazette_locations.index');
        });
        Route::middleware('permission:company-branches.create')->group(function () {
            Route::get('gazette_locations/create', 'create')->name('gazette_locations.create');
            Route::post('gazette_locations/store', 'store')->name('gazette_locations.store');
        });
        Route::middleware('permission:company-branches.edit')->group(function () {
            Route::get('gazette_locations/edit/{id}', 'edit')->name('gazette_locations.edit');
            Route::put('gazette_locations/{id}/update', 'update')->name('gazette_locations.update');
        });
        Route::middleware('permission:company-branches.delete')->group(function () {
            Route::delete('gazette_locations/delete/{id}', 'delete')->name('gazette_locations.delete');
        });
    });

    Route::controller(CompanyLocationController::class)->group(function () {
        Route::middleware('permission:company-branches.view')->group(function () {
            Route::get('company-locations', 'index')->name('company_locations.index');
        });
        Route::middleware('permission:company-branches.create')->group(function () {
            Route::get('company-locations/create', 'create')->name('company_locations.create');
            Route::post('company-locations', 'store')->name('company_locations.store');
        });
        Route::middleware('permission:company-branches.edit')->group(function () {
            Route::get('company-locations/{id}/edit', 'edit')->name('company_locations.edit');
            Route::put('company-locations/{id}', 'update')->name('company_locations.update');
        });
        Route::middleware('permission:company-branches.delete')->group(function () {
            Route::delete('company-locations/{id}', 'destroy')->name('company_locations.destroy');
        });
    });

    Route::controller(DivisionController::class)->group(function () {
        Route::middleware('permission:divisions.view')->group(function () {
            Route::get('divisions', 'index')->name('divisions.index');
        });
        Route::middleware('permission:divisions.create')->group(function () {
            Route::get('divisions/create', 'create')->name('divisions.create');
            Route::post('divisions', 'store')->name('divisions.store');
        });
        Route::middleware('permission:divisions.edit')->group(function () {
            Route::get('divisions/{id}/edit', 'edit')->name('divisions.edit');
            Route::put('divisions/{id}', 'update')->name('divisions.update');
        });
        Route::middleware('permission:divisions.delete')->group(function () {
            Route::delete('divisions/{id}', 'destroy')->name('divisions.delete');
        });
    });
    Route::controller(DepartmentController::class)->group(function () {
        Route::middleware('permission:departments.view')->group(function () {
            Route::get('departments', 'index')->name('departments.index');
        });
        Route::middleware('permission:departments.create')->group(function () {
            Route::get('departments/create', 'create')->name('departments.create');
            Route::post('departments', 'store')->name('departments.store');
        });
        Route::middleware('permission:departments.edit')->group(function () {
            Route::get('departments/{id}/edit', 'edit')->name('departments.edit');
            Route::put('departments/{id}', 'update')->name('departments.update');
        });
        Route::middleware('permission:departments.delete')->group(function () {
            Route::delete('departments/{id}', 'destroy')->name('departments.delete');
        });
    });
    Route::controller(SectionController::class)->group(function () {
        Route::middleware('permission:sections.view')->group(function () {
            Route::get('sections', 'index')->name('sections.index');
        });
        Route::middleware('permission:sections.create')->group(function () {
            Route::get('sections/create', 'create')->name('sections.create');
            Route::post('sections', 'store')->name('sections.store');
        });
        Route::middleware('permission:sections.edit')->group(function () {
            Route::get('sections/{id}/edit', 'edit')->name('sections.edit');
            Route::put('sections/{id}', 'update')->name('sections.update');
        });
        Route::middleware('permission:sections.delete')->group(function () {
            Route::delete('sections/{id}', 'destroy')->name('sections.delete');
        });
    });
    Route::controller(DesignationController::class)->group(function () {
        Route::middleware('permission:designations.view')->group(function () {
            Route::get('designations', 'index')->name('designations.index');
        });
        Route::middleware('permission:designations.create')->group(function () {
            Route::get('designations/create', 'create')->name('designations.create');
            Route::post('designations', 'store')->name('designations.store');
        });
        Route::middleware('permission:designations.edit')->group(function () {
            Route::get('designations/{id}/edit', 'edit')->name('designations.edit');
            Route::put('designations/{id}', 'update')->name('designations.update');
        });
        Route::middleware('permission:designations.delete')->group(function () {
            Route::delete('designations/{id}', 'destroy')->name('designations.delete');
        });
    });
    Route::controller(JobCreationController::class)->group(function () {
        Route::middleware('permission:job-creations.view')->group(function () {
            Route::get('job_creations', 'index')->name('job_creations.index');
        });
        Route::middleware('permission:job-creations.create')->group(function () {
            Route::get('job_creations/create', 'create')->name('job_creations.create');
            Route::post('job_creations', 'store')->name('job_creations.store');
        });
        Route::middleware('permission:job-creations.edit')->group(function () {
            Route::get('job_creations/{id}/edit', 'edit')->name('job_creations.edit');
            Route::put('job_creations/{id}', 'update')->name('job_creations.update');
        });
        Route::middleware('permission:job-creations.delete')->group(function () {
            Route::delete('job_creations/{id}', 'destroy')->name('job_creations.delete');
        });
    });

    Route::controller(BanksController::class)->group(function () {
            Route::middleware('permission:banks.view')->group(function () {
                Route::get('banks', 'index')->name('banks.index');
            });
            Route::middleware('permission:banks.create')->group(function () {
                Route::get('banks/create', 'create')->name('banks.create');
                Route::post('banks', 'store')->name('banks.store');
            });
            Route::middleware('permission:banks.edit')->group(function () {
                Route::get('banks/{id}/edit', 'edit')->name('banks.edit');
                Route::put('banks/{id}', 'update')->name('banks.update');
            });
            Route::middleware('permission:banks.delete')->group(function () {
                Route::delete('banks/{id}', 'delete')->name('banks.delete');
            });
        });

    Route::controller(BranchesController::class)->group(function () {
        Route::middleware('permission:bank-branches.view')->group(function () {
            Route::get('branches', 'index')->name('branches.index');
        });
        Route::middleware('permission:bank-branches.create')->group(function () {
            Route::get('branches/create', 'create')->name('branches.create');
            Route::post('branches', 'store')->name('branches.store');
        });
        Route::middleware('permission:bank-branches.edit')->group(function () {
            Route::get('branches/{id}/edit', 'edit')->name('branches.edit');
            Route::put('branches/{id}', 'update')->name('branches.update');
        });
        Route::middleware('permission:bank-branches.delete')->group(function () {
            Route::delete('branches/{id}', 'delete')->name('branches.delete');
        });
    });

    Route::controller(BankAccountsController::class)->group(function () {
        Route::middleware('permission:bank-accounts.view')->group(function () {
            Route::get('bank-accounts', 'index')->name('bank_accounts.index');
        });
        Route::middleware('permission:bank-accounts.create')->group(function () {
            Route::get('bank-accounts/create', 'create')->name('bank_accounts.create');
            Route::post('bank-accounts', 'store')->name('bank_accounts.store');
        });
        Route::middleware('permission:bank-accounts.edit')->group(function () {
            Route::get('bank-accounts/{id}/edit', 'edit')->name('bank_accounts.edit');
            Route::put('bank-accounts/{id}', 'update')->name('bank_accounts.update');
        });
        Route::middleware('permission:bank-accounts.delete')->group(function () {
            Route::delete('bank-accounts/{id}', 'delete')->name('bank_accounts.delete');
        });
    });

    Route::controller(HolidayController::class)->group(function () {
        Route::middleware('permission:holidays.view')->group(function () {
            Route::get('holidays', 'index')->name('holidays.index');
            Route::get('holidays/calendar', 'calendar')->name('holidays.calendar');
            Route::get('holidays/get-holidays', 'getHolidays')->name('holidays.get_holidays');
        });
        Route::middleware('permission:holidays.create')->group(function () {
            Route::get('holidays/create', 'create')->name('holidays.create');
            Route::post('holidays', 'store')->name('holidays.store');
        });
        Route::middleware('permission:holidays.edit')->group(function () {
            Route::get('holidays/{id}/edit', 'edit')->name('holidays.edit');
            Route::put('holidays/{id}', 'update')->name('holidays.update');
        });
        Route::middleware('permission:holidays.delete')->group(function () {
            Route::delete('holidays/{id}', 'destroy')->name('holidays.delete');
        });
    });
});

// Search Employee Routes
Route::controller(EmployeeSearchController::class)->middleware('auth')->group(function () {
    Route::middleware('permission:employee-management.view')->group(function () {
        Route::get('search/employee', 'index')->name('search.employee');
        Route::get('search/employee/export', 'export')->name('search.employee.export');
    });
});

Route::prefix('employees')->middleware('auth')->group(function () {

    Route::controller(EmployeeReviewController::class)->group(function () {
        Route::middleware('permission:employee-management.view')->group(function () {
            Route::get('review', 'index')->name('employees.review.index');
        });
        Route::middleware('permission:employee-management.edit')->group(function () {
            Route::post('review/{id}/submit', 'review')->name('employees.review.submit');
        });
    });

    Route::controller(EmployeeProfileController::class)->group(function () {
        Route::get('profile/{id}/general-informations', 'profileView')->name('employees.profile.general_informations');
        Route::get('profile/{id}/office-informations', 'showOfficeInfo')->name('employees.profile.office_informations');

        Route::middleware('permission:employee-management.view')->group(function () {
            Route::get('/', 'index')->name('employees.index');
        });

        Route::middleware('permission:employee-management.import')->group(function () {
            Route::get('import', 'bulkEmployeeImportSections')->name('employees.import');
        });

        Route::middleware('permission:employee-management.create')->group(function () {
            Route::get('general-informations/create', 'generalInfoCreate')->name('employees.general_informations.create');
            Route::post('general-informations/store', 'generalInfoStore')->name('employees.general_informations.store');
            Route::get('office-informations/create/{id}', 'officeInfoCreate')->name('employees.office_informations.create');
            Route::post('office-informations/store', 'officeInfoStore')->name('employees.office_informations.store');
        });

        Route::get('general-informations/edit/{id}', 'generalInfoEdit')->name('employees.general_informations.edit');
        Route::put('general-informations/{id}/update', 'generalInfoUpdate')->name('employees.general_informations.update');

        Route::middleware('permission:employee-management.edit')->group(function () {
            Route::get('office-informations/edit/{id}', 'officeInfoEdit')->name('employees.office_informations.edit');
            Route::put('office-informations/{id}/update', 'officeInfoUpdate')->name('employees.office_informations.update');
            Route::post('{id}/toggle-status', 'toggleStatus')->name('employees.toggle_status');
            Route::post('{id}/update-login-info', 'updateLoginInfo')->name('employees.update_login_info');
            Route::post('store-account', 'storeAccount')->name('employees.store_account');
        });

        Route::middleware('permission:employee-management.import')->group(function () {
            Route::post('general-informations/import', 'generalInfoImport')->name('employees.general_informations.import');
            Route::post('office-informations/import', 'officeInfoImport')->name('employees.office_informations.import');
        });
    });

    // Employee ID Card Routes
    Route::controller(\App\Http\Controllers\EmployeeIdCardController::class)->group(function () {
        Route::middleware('permission:id-card-design.view')->group(function () {
            Route::get('id-cards', 'index')->name('employees.id_cards.index');
            Route::get('{id}/id-card/view', 'view')->name('employees.id_card.view');
            Route::get('{id}/id-card/download', 'download')->name('employees.id_card.download');
            Route::get('{id}/id-card/preview', 'preview')->name('employees.id_card.preview');
            Route::get('id-card/{id}', 'show')->name('employees.id_card.show');
            Route::get('{id}/id-card/status', 'status')->name('employees.id_card.status');
        });

        Route::middleware('permission:id-card-design.create')->group(function () {
            Route::post('{id}/id-card/generate', 'generate')->name('employees.id_card.generate');
            Route::post('{id}/id-card/regenerate', 'regenerate')->name('employees.id_card.regenerate');
        });

        Route::middleware('permission:id-card-design.delete')->group(function () {
            Route::post('{id}/id-card/deactivate', 'deactivate')->name('employees.id_card.deactivate');
        });
    });

    Route::controller(EmployeeEligibleController::class)->group(function(){
        Route::middleware('permission:employee-management.view')->group(function () {
            Route::get('profile/{id}/eligible-plans', 'show')->name('employees.profile.eligible_plans');
        });
        Route::middleware('permission:employee-management.create')->group(function () {
            Route::get('eligible-plans/create/{id}', 'create')->name('employees.eligible_plans.create');
            Route::post('eligible-plans/store', 'store')->name('employees.eligible_plans.store');
        });
        Route::middleware('permission:employee-management.edit')->group(function () {
            Route::get('eligible-plans/edit/{id}', 'edit')->name('employees.eligible_plans.edit');
            Route::put('eligible-plans/{id}/update', 'update')->name('employees.eligible_plans.update');
        });
        Route::middleware('permission:employee-management.import')->group(function () {
            Route::post('eligible-plans/import', 'import')->name('employees.eligible_plans.import');
        });
    });

    Route::controller(EmployeeEducationExperienceTrainingController::class)->group(function(){
        Route::middleware('permission:employee-management.view')->group(function () {
            Route::get('profile/{id}/education-information', 'show')->name('employees.profile.education_information');
        });
        Route::middleware('permission:employee-management.create')->group(function () {
            Route::get('education-information/create/{id}', 'create')->name('employees.education_information.create');
            Route::post('education-information/store', 'store')->name('employees.education_information.store');
        });
        Route::middleware('permission:employee-management.edit')->group(function () {
            Route::get('education-information/edit/{id}', 'edit')->name('employees.education_information.edit');
            Route::put('education-information/{id}/update', 'update')->name('employees.education_information.update');
        });
        Route::middleware('permission:employee-management.import')->group(function () {
            Route::post('education-information/import', 'import')->name('employees.education_information.import');
        });
    });

    Route::controller(EmployeeNomineeController::class)->group(function(){
        Route::middleware('permission:employee-management.view')->group(function () {
            Route::get('profile/{id}/nominee-information', 'show')->name('employees.profile.nominee_information');
        });
        Route::middleware('permission:employee-management.create')->group(function () {
            Route::get('nominee-information/create/{id}', 'create')->name('employees.nominee_information.create');
            Route::post('nominee-information/store', 'store')->name('employees.nominee_information.store');
        });
        Route::middleware('permission:employee-management.edit')->group(function () {
            Route::get('nominee-information/edit/{id}', 'edit')->name('employees.nominee_information.edit');
            Route::put('nominee-information/{id}/update', 'update')->name('employees.nominee_information.update');
        });
        Route::middleware('permission:employee-management.import')->group(function () {
            Route::post('nominee-information/import', 'import')->name('employees.nominee_information.import');
        });
    });

    Route::controller(EmployeeSalaryBreakdownController::class)->group(function(){
        Route::middleware('permission:employee-management.view')->group(function () {
            Route::get('profile/{id}/salary-breakdown', 'show')->name('employees.profile.salary_breakdown');
        });
        Route::middleware('permission:employee-management.create')->group(function () {
            Route::get('salary-breakdown/create/{id}', 'create')->name('employees.salary_breakdown.create');
            Route::post('salary-breakdown/store', 'store')->name('employees.salary_breakdown.store');
        });
        Route::middleware('permission:employee-management.edit')->group(function () {
            Route::get('salary-breakdown/edit/{id}', 'edit')->name('employees.salary_breakdown.edit');
            Route::put('salary-breakdown/{id}/update', 'update')->name('employees.salary_breakdown.update');
        });
        Route::middleware('permission:employee-management.import')->group(function () {
            Route::post('salary-breakdown/import', 'import')->name('employees.salary_breakdown.import');
        });
    });

    Route::controller(EmployeeBankAccountController::class)->group(function () {
        Route::middleware('permission:employee-management.view')->group(function () {
            Route::get('profile/{id}/bank-accounts', 'show')->name('employees.profile.bank_accounts');
        });
        Route::middleware('permission:employee-management.create')->group(function () {
            Route::get('bank-accounts/create/{id}', 'create')->name('employees.bank_accounts.create');
            Route::post('bank-accounts/store', 'store')->name('employees.bank_accounts.store');
        });
        Route::middleware('permission:employee-management.edit')->group(function () {
            Route::get('bank-accounts/edit/{id}', 'edit')->name('employees.bank_accounts.edit');
            Route::put('bank-accounts/{id}/update', 'update')->name('employees.bank_accounts.update');
        });
        Route::middleware('permission:employee-management.import')->group(function () {
            Route::post('bank-accounts/import', 'import')->name('employees.bank_accounts.import');
        });
    });

    Route::controller(EmployeePlansController::class)->group(function () {
        Route::middleware('permission:employee-management.view')->group(function () {
            Route::get('profile/{id}/plans/{type}', 'plansView')->name('employees.profile.plans');
        });
        Route::middleware('permission:employee-management.edit')->group(function () {
            Route::post('profile/plans/{type}/store', 'assignPlan')->name('employees.profile.plans.store');
            Route::put('profile/plans/{type}/remove/{id}', 'removePlan')->name('employees.profile.plans.remove');
        });
        Route::middleware('permission:employee-management.delete')->group(function () {
            Route::delete('profile/plans/{type}/delete/{id}', 'deletePlan')->name('employees.profile.plans.delete');
        });
    });

    Route::get('profile/{id}/leave-info', [LeavesController::class, 'showLeaveInfo'])->name('employees.profile.leave_info')->middleware('permission:leaves.view');

});


Route::prefix('plans')->middleware('auth')->group(function () {
    Route::get('bulk-upload', function () {
        return view('plans.bulk_uploads.form');
    })->name('plans.bulk_upload')->middleware('permission:employee-management.import');

    Route::prefix('meal-plans')->group(function () {
        Route::controller(MealPlansController::class)->group(function(){
            Route::middleware('permission:meal-plans.view')->group(function () {
                Route::get('/', 'index')->name('plans.meal_plans.index');
            });
            Route::middleware('permission:meal-plans.create')->group(function () {
                Route::post('store', 'store')->name('plans.meal_plans.store');
                Route::post('import', 'import')->name('plans.meal_plans.import');
            });
            Route::middleware('permission:meal-plans.edit')->group(function () {
                Route::put('update/{id}', 'update')->name('plans.meal_plans.update');
            });
            Route::middleware('permission:meal-plans.delete')->group(function () {
                Route::delete('delete/{id}', 'delete')->name('plans.meal_plans.delete');
            });
        });
    });

    Route::prefix('shift-plans')->group(function () {
        Route::controller(ShiftPlanController::class)->group(function(){
            Route::middleware('permission:shift-plans.view')->group(function () {
                Route::get('/', 'index')->name('plans.shift_plans.index');
            });
            Route::middleware('permission:shift-plans.create')->group(function () {
                Route::get('create', 'create')->name('plans.shift_plans.create');
                Route::post('store', 'store')->name('plans.shift_plans.store');
                Route::post('import', 'import')->name('plans.shift_plans.import');
            });
            Route::middleware('permission:shift-plans.edit')->group(function () {
                Route::get('edit/{id}', 'edit')->name('plans.shift_plans.edit');
                Route::put('update/{id}', 'update')->name('plans.shift_plans.update');
            });
            Route::middleware('permission:shift-plans.delete')->group(function () {
                Route::delete('delete/{id}', 'delete')->name('plans.shift_plans.delete');
            });
            Route::middleware('permission:shift-plans.view')->group(function () {
                Route::get('{id}', 'show')->name('plans.shift_plans.show');
            });
        });
    });

    Route::prefix('leave-plans')->group(function () {
        Route::controller(LeavePlanController::class)->group(function(){
            Route::middleware('permission:leave-plans.view')->group(function () {
                Route::get('/', 'index')->name('plans.leave_plans.index');
            });
            Route::middleware('permission:leave-plans.create')->group(function () {
                Route::get('create', 'create')->name('plans.leave_plans.create');
                Route::post('store', 'store')->name('plans.leave_plans.store');
                Route::post('import', 'import')->name('plans.leave_plans.import');
            });
            Route::middleware('permission:leave-plans.edit')->group(function () {
                Route::get('edit/{id}', 'edit')->name('plans.leave_plans.edit');
                Route::put('update/{id}', 'update')->name('plans.leave_plans.update');
            });
            Route::middleware('permission:leave-plans.delete')->group(function () {
                Route::delete('delete/{id}', 'delete')->name('plans.leave_plans.delete');
            });
            Route::middleware('permission:leave-plans.view')->group(function () {
                Route::get('{id}', 'show')->name('plans.leave_plans.show');
            });
        });
    });

    Route::prefix('ot-plans')->group(function () {
        Route::controller(OTPlanController::class)->group(function(){
            Route::middleware('permission:ot-plans.view')->group(function () {
                Route::get('/', 'index')->name('plans.ot_plans.index');
            });
            Route::middleware('permission:ot-plans.create')->group(function () {
                Route::get('create', 'create')->name('plans.ot_plans.create');
                Route::post('store', 'store')->name('plans.ot_plans.store');
                Route::post('import', 'import')->name('plans.ot_plans.import');
            });
            Route::middleware('permission:ot-plans.edit')->group(function () {
                Route::get('edit/{id}', 'edit')->name('plans.ot_plans.edit');
                Route::put('update/{id}', 'update')->name('plans.ot_plans.update');
            });
            Route::middleware('permission:ot-plans.delete')->group(function () {
                Route::delete('delete/{id}', 'delete')->name('plans.ot_plans.delete');
            });
            Route::middleware('permission:ot-plans.view')->group(function () {
                Route::get('{id}', 'show')->name('plans.ot_plans.show');
            });
        });
    });

    Route::prefix('roster-plans')->group(function () {
        Route::controller(RosterPlansController::class)->group(function(){
            Route::middleware('permission:roster-plans.view')->group(function () {
                Route::get('/', 'index')->name('plans.roster_plans.index');
            });
            Route::middleware('permission:roster-plans.create')->group(function () {
                Route::get('create', 'create')->name('plans.roster_plans.create');
                Route::post('store', 'store')->name('plans.roster_plans.store');
                Route::post('import', 'import')->name('plans.roster_plans.import');
            });
            Route::middleware('permission:roster-plans.edit')->group(function () {
                Route::get('edit/{id}', 'edit')->name('plans.roster_plans.edit');
                Route::put('update/{id}', 'update')->name('plans.roster_plans.update');
            });
            Route::middleware('permission:roster-plans.delete')->group(function () {
                Route::delete('delete/{id}', 'delete')->name('plans.roster_plans.delete');
            });
            Route::middleware('permission:roster-plans.view')->group(function () {
                Route::get('{id}', 'show')->name('plans.roster_plans.show');
            });
        });
    });

    Route::prefix('off-day-plans')->group(function () {
        Route::controller(OffDayPlansController::class)->group(function(){
            Route::middleware('permission:off-day-work-plans.view')->group(function () {
                Route::get('/', 'index')->name('plans.off_day_plans.index');
            });
            Route::middleware('permission:off-day-work-plans.create')->group(function () {
                Route::get('create', 'create')->name('plans.off_day_plans.create');
                Route::post('store', 'store')->name('plans.off_day_plans.store');
                Route::post('import', 'import')->name('plans.off_day_plans.import');
            });
            Route::middleware('permission:off-day-work-plans.edit')->group(function () {
                Route::get('edit/{id}', 'edit')->name('plans.off_day_plans.edit');
                Route::put('update/{id}', 'update')->name('plans.off_day_plans.update');
            });
            Route::middleware('permission:off-day-work-plans.delete')->group(function () {
                Route::delete('delete/{id}', 'delete')->name('plans.off_day_plans.delete');
            });
            Route::middleware('permission:off-day-work-plans.view')->group(function () {
                Route::get('{id}', 'show')->name('plans.off_day_plans.show');
            });
        });
    });

    Route::prefix('bonus-plans')->group(function () {
        Route::controller(BonusPlanController::class)->group(function(){
            Route::middleware('permission:bonus-plans.view')->group(function () {
                Route::get('/', 'index')->name('plans.bonus_plans.index');
            });
            Route::middleware('permission:bonus-plans.create')->group(function () {
                Route::get('create', 'create')->name('plans.bonus_plans.create');
                Route::post('store', 'store')->name('plans.bonus_plans.store');
                Route::post('import', 'import')->name('plans.bonus_plans.import');
            });
            Route::middleware('permission:bonus-plans.edit')->group(function () {
                Route::get('edit/{id}', 'edit')->name('plans.bonus_plans.edit');
                Route::put('update/{id}', 'update')->name('plans.bonus_plans.update');
            });
            Route::middleware('permission:bonus-plans.delete')->group(function () {
                Route::delete('delete/{id}', 'delete')->name('plans.bonus_plans.delete');
            });
            Route::middleware('permission:bonus-plans.view')->group(function () {
                Route::get('{id}', 'show')->name('plans.bonus_plans.show');
            });
        });
    });

    Route::prefix('allowance-plans')->group(function () {
        Route::controller(AllowancePlanController::class)->group(function(){
            Route::middleware('permission:allowance-plans.view')->group(function () {
                Route::get('/', 'index')->name('plans.allowance_plans.index');
            });
            Route::middleware('permission:allowance-plans.create')->group(function () {
                Route::get('create', 'create')->name('plans.allowance_plans.create');
                Route::post('store', 'store')->name('plans.allowance_plans.store');
                Route::post('import', 'import')->name('plans.allowance_plans.import');
            });
            Route::middleware('permission:allowance-plans.edit')->group(function () {
                Route::get('edit/{id}', 'edit')->name('plans.allowance_plans.edit');
                Route::put('update/{id}', 'update')->name('plans.allowance_plans.update');
            });
            Route::middleware('permission:allowance-plans.delete')->group(function () {
                Route::delete('delete/{id}', 'delete')->name('plans.allowance_plans.delete');
            });
            Route::middleware('permission:allowance-plans.view')->group(function () {
                Route::get('{id}', 'show')->name('plans.allowance_plans.show');
            });
        });
    });

    Route::prefix('ta-plans')->group(function () {
        Route::controller(TAPlanController::class)->group(function(){
            Route::middleware('permission:ta-plans.view')->group(function () {
                Route::get('/', 'index')->name('plans.ta_plans.index');
            });
            Route::middleware('permission:ta-plans.create')->group(function () {
                Route::get('create', 'create')->name('plans.ta_plans.create');
                Route::post('store', 'store')->name('plans.ta_plans.store');
                Route::post('import', 'import')->name('plans.ta_plans.import');
            });
            Route::middleware('permission:ta-plans.edit')->group(function () {
                Route::get('edit/{id}', 'edit')->name('plans.ta_plans.edit');
                Route::put('update/{id}', 'update')->name('plans.ta_plans.update');
            });
            Route::middleware('permission:ta-plans.delete')->group(function () {
                Route::delete('delete/{id}', 'delete')->name('plans.ta_plans.delete');
            });
        });
    });

    Route::prefix('da-plans')->group(function () {
        Route::controller(DAPlanController::class)->group(function(){
            Route::middleware('permission:da-plans.view')->group(function () {
                Route::get('/', 'index')->name('plans.da_plans.index');
            });
            Route::middleware('permission:da-plans.create')->group(function () {
                Route::get('create', 'create')->name('plans.da_plans.create');
                Route::post('store', 'store')->name('plans.da_plans.store');
                Route::post('import', 'import')->name('plans.da_plans.import');
            });
            Route::middleware('permission:da-plans.edit')->group(function () {
                Route::get('edit/{id}', 'edit')->name('plans.da_plans.edit');
                Route::put('update/{id}', 'update')->name('plans.da_plans.update');
            });
            Route::middleware('permission:da-plans.delete')->group(function () {
                Route::delete('delete/{id}', 'delete')->name('plans.da_plans.delete');
            });
        });
    });

    Route::prefix('deduction-plans')->group(function () {
        Route::controller(DeductionPlanController::class)->group(function(){
            Route::middleware('permission:deduction-plan.view')->group(function () {
                Route::get('/', 'index')->name('plans.deduction_plans.index');
            });
            Route::middleware('permission:deduction-plan.create')->group(function () {
                Route::post('store', 'store')->name('plans.deduction_plans.store');
            });
            Route::middleware('permission:deduction-plan.edit')->group(function () {
                Route::get('edit', 'edit')->name('plans.deduction_plans.edit');
                Route::put('update', 'update')->name('plans.deduction_plans.update');
            });
        });
    });

});

Route::controller(OrganizationStructureController::class)->middleware('auth')->group(function () {
    Route::middleware('permission:structural-view.view')->group(function () {
        Route::get('organization-structure/view', 'structuralView')->name('organization-structure.view');
        Route::get('organization-structure/key-people/{level}/{id}', 'getKeyPeople')->name('organization-structure.key-people');
    });

    Route::middleware('permission:members.view')->group(function () {
        Route::get('organization-structure', 'index')->name('organization-structure.index');
    });

    Route::middleware('permission:members.create')->group(function () {
        Route::get('organization-structure/create', 'create')->name('organization-structure.create');
        Route::post('organization-structure', 'store')->name('organization-structure.store');
    });

    Route::middleware('permission:members.view')->group(function () {
        Route::get('organization-structure/{id}', 'show')->name('organization-structure.show');
    });

    Route::middleware('permission:members.edit')->group(function () {
        Route::get('organization-structure/{id}/edit', 'edit')->name('organization-structure.edit');
        Route::put('organization-structure/{id}', 'update')->name('organization-structure.update');
    });

    Route::middleware('permission:members.delete')->group(function () {
        Route::delete('organization-structure/{id}', 'destroy')->name('organization-structure.destroy');
    });
});


Route::get('salary-process', function () {
    return view('payroll.salary-process', [
        'companies' => App\Models\Company::all(),
        'employees' => App\Models\Employee::all(),
    ]);
})->name('salary.process')->middleware(['auth', 'permission:salary.view']);

Route::prefix('settings')->middleware('auth')->group(function () {
    Route::controller(SettingsController::class)->group(function (){
       Route::middleware('permission:general-settings.view')->group(function () {
           Route::get('general-settings', 'generalSettingIndex')->name('settings.general_settings');
       });
       Route::middleware('permission:general-settings.create')->group(function () {
           Route::post('general-settings/save', 'generalSettingSave')->name('settings.general_settings.store');
       });

       Route::middleware('permission:smtp.view')->group(function () {
           Route::get('mail-settings', 'mailSettingIndex')->name('settings.mail_settings');
       });
       Route::middleware('permission:smtp.create')->group(function () {
           Route::post('mail-settings/save', 'mailSettingSave')->name('settings.mail_settings.save');
           Route::post('mail-settings/test', 'sendTestMail')->name('settings.mail_settings.test');
       });
    });

    Route::controller(ApiKeyController::class)->group(function (){
       Route::middleware('permission:api-keys.view')->group(function () {
           Route::get('api-keys', 'index')->name('settings.api_keys');
       });
       Route::middleware('permission:api-keys.create')->group(function () {
           Route::post('api-keys/save', 'save')->name('settings.api_keys.save');
       });
    });

    // ID Card Design Routes
    Route::controller(\App\Http\Controllers\IDCardDesignController::class)->group(function (){
       Route::middleware('permission:id-card-design.view')->group(function () {
           Route::get('id-design', 'index')->name('settings.id_design.index');
       });
       Route::middleware('permission:id-card-design.create')->group(function () {
           Route::get('id-design/create', 'create')->name('settings.id_design.create');
           Route::post('id-design/store', 'store')->name('settings.id_design.store');
       });
       Route::middleware('permission:id-card-design.view')->group(function () {
           Route::get('id-design/{id}', 'show')->name('settings.id_design.show');
           Route::get('id-design/{id}/preview', 'preview')->name('settings.id_design.preview');
           Route::get('id-design/{id}/download', 'download')->name('settings.id_design.download');
       });
       Route::middleware('permission:id-card-design.edit')->group(function () {
           Route::post('id-design/{id}/activate', 'activate')->name('settings.id_design.activate');
           Route::post('id-design/{id}/deactivate', 'deactivate')->name('settings.id_design.deactivate');
       });
       Route::middleware('permission:id-card-design.delete')->group(function () {
           Route::delete('id-design/{id}/delete', 'destroy')->name('settings.id_design.destroy');
       });
    });
});

Route::prefix('leaves')->middleware('auth')->group(function () {
    Route::controller(LeavesController::class)->group(function (){
        Route::middleware('permission:leaves.view')->group(function () {
            Route::get('/', 'index')->name('leaves.index');
        });
        Route::middleware('permission:leaves.create')->group(function () {
            Route::get('create', 'create')->name('leaves.create');
            Route::post('store', 'store')->name('leaves.store');
            Route::post('import', 'import')->name('leaves.import');
        });
        Route::middleware('permission:leaves.hr-approve')->group(function () {
            Route::put('change-status', 'changeStatus')->name('leaves.change_status');
        });
        Route::middleware('permission:leaves.delete')->group(function () {
            Route::delete('{id}/delete', 'destroy')->name('leaves.destroy');
        });
    });
});

Route::controller(EmployeeMovementsController::class)->prefix('movement')->middleware('auth')->group(function (){
    Route::middleware('permission:movement.view')->group(function () {
        Route::get('/', 'index')->name('movement.index');
    });
    Route::middleware('permission:movement.create')->group(function () {
        Route::get('create', 'form')->name('movement.create');
        Route::post('store', 'save')->name('movement.store');
    });
    Route::middleware('permission:movement.edit')->group(function () {
        Route::get('{id}/edit', 'form')->name('movement.edit');
        Route::put('{id}/update', 'save')->name('movement.update');
    });
    Route::middleware('permission:movement.hr-approve')->group(function () {
        Route::put('change-status', 'changeStatus')->name('movement.change_status');
    });
    Route::middleware('permission:movement.delete')->group(function () {
        Route::delete('{id}/delete', 'destroy')->name('movement.destroy');
    });
});

// Payroll - Employee Promotion Routes

Route::prefix('promotion')->name('promotion.')->controller(PromotionController::class)->middleware('auth')->group(function () {
    Route::middleware('permission:promotions.view')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('view/{id}', 'show')->name('show');
    });
    Route::middleware('permission:promotions.create')->group(function () {
        Route::get('create', 'create')->name('create');
        Route::post('store', 'save')->name('store');
    });
    Route::middleware('permission:promotions.edit')->group(function () {
        Route::get('adjustment', 'adjustment')->name('adjustment');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::put('{id}/update', 'save')->name('update');
        Route::put('{id}/status-update', 'statusUpdate')->name('status.update');
    });
    Route::middleware('permission:promotions.delete')->group(function () {
        Route::delete('{id}/delete', 'delete')->name('delete');
    });
});

// Payroll - Employee Increment Routes

Route::prefix('increment')->name('increment.')->controller(IncrementController::class)->middleware('auth')->group(function () {
    Route::middleware('permission:increments.view')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('view/{id}', 'show')->name('show');
    });
    Route::middleware('permission:increments.create')->group(function () {
        Route::get('create', 'create')->name('create');
        Route::post('store', 'save')->name('store');
    });
    Route::middleware('permission:increments.edit')->group(function () {
        Route::get('adjustment', 'adjustment')->name('adjustment');
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::put('{id}/update', 'save')->name('update');
        Route::put('{id}/status-update', 'statusUpdate')->name('status.update');
    });
    Route::middleware('permission:increments.delete')->group(function () {
        Route::delete('{id}/delete', 'delete')->name('delete');
    });
});

Route::prefix('bonus')->name('bonus.')->controller(\App\Http\Controllers\Payroll\BonusController::class)->middleware('auth')->group(function () {
    Route::middleware('permission:bonuses.view')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('view/{id}', 'show')->name('show');
    });
    Route::middleware('permission:bonuses.create')->group(function () {
        Route::get('create', 'create')->name('create');
        Route::post('store', 'save')->name('store');
    });
    Route::middleware('permission:bonuses.edit')->group(function () {
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::put('{id}/update', 'save')->name('update');
        Route::put('{id}/status-update', 'statusUpdate')->name('status.update');
    });
    Route::middleware('permission:bonuses.delete')->group(function () {
        Route::delete('{id}/delete', 'delete')->name('delete');
    });
});

Route::prefix('salary-process')->name('salary.')->controller(\App\Http\Controllers\Payroll\SalaryController::class)->middleware('auth')->group(function () {
    Route::middleware('permission:salary.view')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('view/{id}', 'show')->name('show');
        Route::get('payroll-detail/{id}', 'showPayroll')->name('payroll.show');
        Route::get('generate-payslip/{id}', 'generatePayslip')->name('payroll.payslip');
        Route::get('generate-salary-certificate/{id}', 'generateSalaryCertificate')->name('payroll.certificate');
        Route::get('profile-salary-certificate/{employee_id}', 'generateSalaryCertificateFromProfile')->name('payroll.profile_certificate');
    });
    Route::middleware('permission:salary.create')->group(function () {
        Route::get('create', 'create')->name('create');
        Route::post('store', 'save')->name('store');
    });
    Route::middleware('permission:salary.edit')->group(function () {
        Route::get('edit/{id}', 'edit')->name('edit');
        Route::put('{id}/update', 'save')->name('update');
        Route::put('{id}/status-update', 'statusUpdate')->name('status.update');
    });
    Route::middleware('permission:salary.delete')->group(function () {
        Route::delete('{id}/delete', 'delete')->name('delete');
    });
});


Route::controller(DataController::class)->middleware('auth')->group(function () {

    //company-details
    Route::get('get-grades/{tofsil_id}', 'getGradeByAct');
    Route::get('get-units/{company_id}', 'getUnit');
    Route::get('get-divisions/{company_id}/{location_id?}', 'getDivisions');
    Route::get('/get-departments/{company_id}/{location_id?}/{division_id?}', 'getDepartments');
    Route::get('/get-sections/{company_id}/{location_id?}/{division_id?}/{department_id?}', 'getSections');
    Route::get('/get-employees/{company_id}/{location_id?}/{division_id?}/{department_id?}/{section_id?}', 'getEmployees');
    Route::get('get-branches/{bank_id}', 'getBranchesByBank');

    //plan_details
    Route::get('get-meal-plans/{type}', 'getMealPlanByType');
    Route::get('get-meal-plan-details/{id}', 'getMealPlanDetails');
    Route::get('get-offday-plan-details/{id}', 'getOffDayPlanDetails');
    Route::get('get-ot-plan-details/{id}', 'getOtPlanDetails');
    Route::get('get-shift-plan-details/{id}', 'getShiftPlanDetails');
    Route::get('get-roster-plan-details/{id}', 'getRosterPlanDetails');
    Route::get('get-bonus-plan-details/{id}', 'getBonusPlanDetails');
    Route::get('get-leave-plan-details/{id}', 'getLeavePlanDetails');
    Route::get('get-shift-details/{shift_id}', 'getShiftDetails');

    //leave-details
    Route::get('get-leave-plans/{employee_id}', 'getLeavePlan');
    Route::get('get-leave-details/{employee_id}/{plan_id}', 'getLeaveDetails');

    //attendance-details
    Route::get('get-attendance-details/{employee_id}', 'getAttendanceDetails');
    Route::get('get-attendance-records/{employee_id}', 'getAttendanceDetails');

    //employee_details
    Route::get('get-current-designation/{employee_id}', 'getEmployeeCurrentDesignation');
    Route::get('get-employee-salary/{employee_id}', 'getEmployeeSalary');
    Route::get('get-employees-for-account', 'getEmployeesForAccount')->name('get-employees-for-account');


});

    Route::controller(AttendancesController::class)->prefix('attendance')->middleware('auth')->group(function (){
        Route::middleware('permission:attendance.view')->group(function () {
            Route::get('/', 'index')->name('attendance.index');
            Route::get('print', 'printIndex')->name('attendance.print');
            Route::get('print/{id}', 'printDetail')->name('attendance.print-detail');
        });
        Route::middleware('permission:attendance.create')->group(function () {
            Route::get('create', 'create')->name('attendance.create');
            Route::post('store', 'store')->name('attendance.store');
        });
        Route::middleware('permission:attendance.import')->group(function () {
            Route::get('bulk-upload', 'bulkUpload')->name('attendance.bulk-upload');
            Route::post('import', 'import')->name('attendance.import');
        });
        Route::middleware('permission:attendance.view')->group(function () {
            Route::get('clock-in-out', 'clock_in_out')->name('attendance.clock_in_out');
            Route::post('clock-in-store', 'clockInOutStore')->name('attendance.clock_in_out_store');
        });
    });

// Transport Module Routes
Route::prefix('transport')->name('transport.')->middleware('auth')->group(function () {
    Route::controller(\App\Http\Controllers\Transport\VehicleController::class)->group(function () {
        Route::middleware('permission:vehicles.create')->group(function () {
            Route::get('vehicles/create', 'create')->name('vehicles.create');
            Route::post('vehicles', 'store')->name('vehicles.store');
        });
        Route::middleware('permission:vehicles.view')->group(function () {
            Route::get('vehicles', 'index')->name('vehicles.index');
            Route::get('vehicles/{id}/history', 'history')->name('vehicles.history');
            Route::get('vehicles/{id}', 'show')->name('vehicles.show');
        });
        Route::middleware('permission:vehicles.edit')->group(function () {
            Route::get('vehicles/{id}/edit', 'edit')->name('vehicles.edit');
            Route::put('vehicles/{id}', 'update')->name('vehicles.update');
        });
        Route::middleware('permission:vehicles.delete')->group(function () {
            Route::delete('vehicles/{id}', 'destroy')->name('vehicles.destroy');
        });
    });

    // Vehicle Driver Assignment Routes
    Route::controller(\App\Http\Controllers\Transport\VehicleDriverController::class)->group(function () {
        Route::middleware('permission:assign-driver.create')->group(function () {
            Route::get('vehicle-drivers/create', 'create')->name('vehicle_drivers.create');
            Route::post('vehicle-drivers', 'store')->name('vehicle_drivers.store');
        });
        Route::middleware('permission:assign-driver.view')->group(function () {
            Route::get('vehicle-drivers', 'index')->name('vehicle_drivers.index');
            Route::get('vehicle-drivers/history', 'history')->name('vehicle_drivers.history');
            Route::get('api/vehicle/{id}', 'getVehicleDetails');
            Route::get('api/driver/{id}', 'getDriverDetails');
            Route::get('vehicle-drivers/{id}', 'show')->name('vehicle_drivers.show');
        });
        Route::middleware('permission:assign-driver.edit')->group(function () {
            Route::get('vehicle-drivers/{id}/edit', 'edit')->name('vehicle_drivers.edit');
            Route::put('vehicle-drivers/{id}', 'update')->name('vehicle_drivers.update');
        });
        Route::middleware('permission:assign-driver.delete')->group(function () {
            Route::delete('vehicle-drivers/{id}', 'destroy')->name('vehicle_drivers.destroy');
        });
    });

    // Vehicle Requisition Routes
    Route::controller(\App\Http\Controllers\Transport\VehicleRequisitionController::class)->group(function () {
        Route::middleware('permission:vehicle-requisition.create')->group(function () {
            Route::get('vehicle-requisitions/create', 'create')->name('vehicle_requisitions.create');
            Route::post('vehicle-requisitions', 'store')->name('vehicle_requisitions.store');
        });
        Route::middleware('permission:vehicle-requisition.view')->group(function () {
            Route::get('vehicle-requisitions', 'index')->name('vehicle_requisitions.index');
            Route::get('vehicle-requisitions/{id}', 'show')->name('vehicle_requisitions.show');
        });
        Route::middleware('permission:vehicle-requisition.edit')->group(function () {
            Route::get('vehicle-requisitions/{id}/approve', 'approve')->name('vehicle_requisitions.approve');
            Route::post('vehicle-requisitions/{id}/approve', 'processApproval')->name('vehicle_requisitions.process_approval');
            Route::post('vehicle-requisitions/{id}/reject', 'reject')->name('vehicle_requisitions.reject');
        });
    });

    // Employee Transport Routes
    Route::controller(\App\Http\Controllers\Transport\EmployeeTransportController::class)->group(function () {
        Route::middleware('permission:employee-transport.create')->group(function () {
            Route::get('employee-transports/create', 'create')->name('employee_transports.create');
            Route::post('employee-transports', 'store')->name('employee_transports.store');
        });
        Route::middleware('permission:employee-transport.view')->group(function () {
            Route::get('employee-transports', 'index')->name('employee_transports.index');
            Route::get('employee-transports/search', 'search')->name('employee_transports.search');
            Route::get('employee-transports/{id}', 'show')->name('employee_transports.show');
        });
        Route::middleware('permission:employee-transport.edit')->group(function () {
            Route::get('employee-transports/{id}/edit', 'edit')->name('employee_transports.edit');
            Route::put('employee-transports/{id}', 'update')->name('employee_transports.update');
            Route::patch('employee-transports/{id}/approve', 'approve')->name('employee_transports.approve');
            Route::patch('employee-transports/{id}/reject', 'reject')->name('employee_transports.reject');
            Route::patch('employee-transports/{id}/cancel', 'cancel')->name('employee_transports.cancel');
        });
        Route::middleware('permission:employee-transport.delete')->group(function () {
            Route::delete('employee-transports/{id}', 'destroy')->name('employee_transports.destroy');
        });
    });

    // Vehicle Allocation Routes
    Route::controller(\App\Http\Controllers\Transport\VehicleAllocationController::class)->group(function () {
        Route::middleware('permission:vehicle-allocation.create')->group(function () {
            Route::get('vehicle-allocations/create', 'create')->name('vehicle_allocations.create');
            Route::post('vehicle-allocations/step2', 'step2')->name('vehicle_allocations.step2');
            Route::get('vehicle-allocations/step2', 'step2')->name('vehicle_allocations.step2.get');
            Route::post('vehicle-allocations/step3', 'step3')->name('vehicle_allocations.step3');
            Route::get('vehicle-allocations/step3', 'step3')->name('vehicle_allocations.step3.get');
            Route::post('vehicle-allocations', 'store')->name('vehicle_allocations.store');
        });
        Route::middleware('permission:vehicle-allocation.view')->group(function () {
            Route::get('vehicle-allocations', 'dashboard')->name('vehicle_allocations.dashboard');
            Route::get('vehicle-allocations/history', 'history')->name('vehicle_allocations.history');
            Route::get('api/application-details', 'getApplicationDetails')->name('vehicle_allocations.application_details');
            Route::get('vehicle-allocations/{id}', 'show')->name('vehicle_allocations.show');
        });
        Route::middleware('permission:vehicle-allocation.edit')->group(function () {
            Route::patch('vehicle-allocations/{id}/release', 'release')->name('vehicle_allocations.release');
            Route::patch('vehicle-allocations/{id}/extend', 'extend')->name('vehicle_allocations.extend');
        });
    });
});

Route::get('db-backup', function () {
    $title = 'Database Backup';
    $section = 'Settings';
    $sub_section = 'Database Backup';
    return view('settings.database_backup')->with(compact('title', 'section', 'sub_section'));
})->name('db_backup')->middleware(['auth', 'permission:db-backup.download']);

Route::get('flex/db-dump', [\Flex\DbDump\Http\Controllers\DbDumpController::class, 'download'])->name('flex_db_dump')->middleware(['auth', 'permission:db-backup.download']);

// Trial Routes - Payroll Process Index
Route::get('/trial/payroll-process', function() {
    return view('trial.payroll-process-index');
})->name('trial.payroll_process.index')->middleware(['auth', 'permission:salary.view']);

// Role Management
Route::prefix('settings')->name('settings.')->middleware(['auth', 'permission:role-management.view'])->group(function () {
    Route::resource('roles', \App\Http\Controllers\Settings\RoleController::class);
});

require __DIR__.'/auth.php';

