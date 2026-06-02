<?php

use App\Http\Controllers\Plan\AllowancePlanController;
use App\Http\Controllers\Setting\ApiKeyController;
use App\Http\Controllers\Attendance\AttendancesController;
use App\Http\Controllers\Company\BankAccountsController;
use App\Http\Controllers\Company\BanksController;
use App\Http\Controllers\Plan\BonusPlanController;
use App\Http\Controllers\Company\BranchesController;
use App\Http\Controllers\Company\CompanyLocationController;
use App\Http\Controllers\Company\CompanySetupController;
use App\Http\Controllers\Plan\DAPlanController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\Plan\DeductionPlanController;
use App\Http\Controllers\Company\DepartmentController;
use App\Http\Controllers\Company\DesignationController;
use App\Http\Controllers\Company\DivisionController;
use App\Http\Controllers\Employee\EmployeeBankAccountController;
use App\Http\Controllers\Employee\EmployeeEducationExperienceTrainingController;
use App\Http\Controllers\Employee\EmployeeEmploymentHistoryController;
use App\Http\Controllers\Employee\EmployeeEligibleController;
use App\Http\Controllers\Movement\EmployeeMovementsController;
use App\Http\Controllers\Employee\EmployeeNomineeController;
use App\Http\Controllers\Employee\EmployeePlansController;
use App\Http\Controllers\Employee\EmployeeReviewController;
use App\Http\Controllers\Setting\NotificationController;
use App\Http\Controllers\Employee\EmployeeProfileController;
use App\Http\Controllers\Employee\EmployeeSalaryBreakdownController;
use App\Http\Controllers\Employee\EmployeeSearchController;
use App\Http\Controllers\Company\GazetteLocationsController;
use App\Http\Controllers\Company\HolidayController;
use App\Http\Controllers\Company\JobCreationController;
use App\Http\Controllers\Plan\LeavePlanController;
use App\Http\Controllers\Leave\LeavesController;
use App\Http\Controllers\Plan\MealPlansController;
use App\Http\Controllers\Plan\OffDayPlansController;
use App\Http\Controllers\Structure\OrganizationStructureController;
use App\Http\Controllers\Plan\OTPlanController;
use App\Http\Controllers\Payroll\IncrementController;
use App\Http\Controllers\Payroll\PromotionController;
use App\Http\Controllers\Plan\RosterPlansController;
use App\Http\Controllers\Company\SalaryGradesController;
use App\Http\Controllers\Company\SectionController;
use App\Http\Controllers\Setting\SettingsController;
use App\Http\Controllers\Plan\ShiftPlanController;
use App\Http\Controllers\Plan\TAPlanController;
use App\Http\Controllers\Company\TofsilsController;
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
    return view('setting.id_design.designs.design_2');
})->name('id.card.preview');

Route::prefix('notifications')->middleware('auth')->group(function () {
    Route::controller(NotificationController::class)->group(function () {
        Route::get('/', 'index')->name('notifications.index');
        Route::get('header', 'getHeaderNotifications')->name('notifications.header');
        Route::post('{id}/mark-as-read', 'markAsRead')->name('notifications.mark-read');
        Route::post('mark-all-read', 'markAllAsRead')->name('notifications.mark-all-read');
    });
});

Route::get('/', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard.index');




Route::prefix('company-setup')->middleware('auth')->group(function () {

    Route::get('bulk-upload', function () {
        return view('company.bulk_uploads.form');
    })->name('company.bulk_upload')->middleware('permission:employee-management.import');

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
        Route::get('search/employee', 'index')->name('employee.employee');
        Route::get('search/employee/export', 'export')->name('employee.employee.export');
    });
});

Route::prefix('employees')->middleware('auth')->group(function () {

    Route::controller(EmployeeReviewController::class)->group(function () {
        Route::middleware('permission:employee-management.view')->group(function () {
            Route::get('review', 'index')->name('employee.review.index');
        });
        Route::middleware('permission:employee-management.edit')->group(function () {
            Route::post('review/{id}/submit', 'review')->name('employee.review.submit');
        });
    });

    // Employee Dashboard & Timeline Routes
    Route::controller(\App\Http\Controllers\Employee\EmployeeDashboardController::class)->group(function () {
        Route::get('employee-dashboard', 'index')->name('employee.dashboard');
        Route::get('employee-dashboard/{id}', 'show')->name('employee.dashboard.show');
    });

    Route::post('profile/{id}/verify-nid', [\App\Http\Controllers\Employee\NIDVerificationController::class, 'verify'])->name('employee.profile.verify_nid');

    Route::controller(EmployeeProfileController::class)->group(function () {
        Route::get('profile/{id}/general-informations', 'profileView')->name('employee.profile.general_informations');
        Route::get('profile/{id}/office-informations', 'showOfficeInfo')->name('employee.profile.office_informations');
        Route::get('profile/{id}/detailed-json', 'getDetailedProfileJson')->name('employee.profile.detailed_json');
        Route::get('profile/{id}/download-pdf', 'downloadProfilePdf')->name('employee.profile.download_pdf');

        Route::middleware('permission:employee-management.view')->group(function () {
            Route::get('/', 'index')->name('employee.index');
        });

        Route::middleware('permission:employee-management.import')->group(function () {
            Route::get('import', 'bulkEmployeeImportSections')->name('employee.import');
        });

        Route::middleware('permission:employee-management.create')->group(function () {
            Route::get('general-informations/create', 'generalInfoCreate')->name('employee.general_informations.create');
            Route::post('general-informations/store', 'generalInfoStore')->name('employee.general_informations.store');
            Route::get('office-informations/create/{id}', 'officeInfoCreate')->name('employee.office_informations.create');
            Route::post('office-informations/store', 'officeInfoStore')->name('employee.office_informations.store');
        });

        Route::get('general-informations/edit/{id}', 'generalInfoEdit')->name('employee.general_informations.edit');
        Route::put('general-informations/{id}/update', 'generalInfoUpdate')->name('employee.general_informations.update');

        Route::middleware('permission:employee-management.edit')->group(function () {
            Route::get('office-informations/edit/{id}', 'officeInfoEdit')->name('employee.office_informations.edit');
            Route::put('office-informations/{id}/update', 'officeInfoUpdate')->name('employee.office_informations.update');
            Route::post('{id}/toggle-status', 'toggleStatus')->name('employee.toggle_status');
            Route::post('{id}/update-login-info', 'updateLoginInfo')->name('employee.update_login_info');
            Route::post('store-account', 'storeAccount')->name('employee.store_account');
        });

        Route::middleware('permission:employee-management.import')->group(function () {
            Route::post('general-informations/import', 'generalInfoImport')->name('employee.general_informations.import');
            Route::post('office-informations/import', 'officeInfoImport')->name('employee.office_informations.import');
        });
    });

    Route::get('reports', [\App\Http\Controllers\Employee\EmployeeReportController::class, 'index'])->name('employee.reports');
    Route::get('reports/drill-down', [\App\Http\Controllers\Employee\EmployeeReportController::class, 'getHierarchyDrillDown'])->name('employee.reports.drill_down');

    // Employee ID Card Routes
    Route::controller(\App\Http\Controllers\Employee\EmployeeIdCardController::class)->group(function () {
        Route::middleware('permission:id-card-design.view')->group(function () {
            Route::get('id-cards', 'index')->name('employee.id_cards.index');
            Route::get('{id}/id-card/view', 'view')->name('employee.id_card.view');
            Route::get('{id}/id-card/download', 'download')->name('employee.id_card.download');
            Route::get('{id}/id-card/preview', 'preview')->name('employee.id_card.preview');
            Route::get('id-card/{id}', 'show')->name('employee.id_card.show');
            Route::get('{id}/id-card/status', 'status')->name('employee.id_card.status');
        });

        Route::middleware('permission:id-card-design.create')->group(function () {
            Route::post('{id}/id-card/generate', 'generate')->name('employee.id_card.generate');
            Route::post('{id}/id-card/regenerate', 'regenerate')->name('employee.id_card.regenerate');
        });

        Route::middleware('permission:id-card-design.delete')->group(function () {
            Route::post('{id}/id-card/deactivate', 'deactivate')->name('employee.id_card.deactivate');
        });
    });

    Route::controller(EmployeeEligibleController::class)->group(function(){
        Route::middleware('permission:employee-management.view')->group(function () {
            Route::get('profile/{id}/eligible-plans', 'show')->name('employee.profile.eligible_plans');
        });
        Route::middleware('permission:employee-management.create')->group(function () {
            Route::get('eligible-plans/create/{id}', 'create')->name('employee.eligible_plans.create');
            Route::post('eligible-plans/store', 'store')->name('employee.eligible_plans.store');
        });
        Route::middleware('permission:employee-management.edit')->group(function () {
            Route::get('eligible-plans/edit/{id}', 'edit')->name('employee.eligible_plans.edit');
            Route::put('eligible-plans/{id}/update', 'update')->name('employee.eligible_plans.update');
        });
        Route::middleware('permission:employee-management.import')->group(function () {
            Route::post('eligible-plans/import', 'import')->name('employee.eligible_plans.import');
        });
    });

    Route::controller(EmployeeEducationExperienceTrainingController::class)->group(function(){
        Route::middleware('permission:employee-management.view')->group(function () {
            Route::get('profile/{id}/education-information', 'show')->name('employee.profile.education_information');
        });
        Route::middleware('permission:employee-management.create')->group(function () {
            Route::get('education-information/create/{id}', 'create')->name('employee.education_information.create');
            Route::post('education-information/store', 'store')->name('employee.education_information.store');
        });
        Route::middleware('permission:employee-management.edit')->group(function () {
            Route::get('education-information/edit/{id}', 'edit')->name('employee.education_information.edit');
            Route::put('education-information/{id}/update', 'update')->name('employee.education_information.update');
        });
        Route::middleware('permission:employee-management.import')->group(function () {
            Route::post('education-information/import', 'import')->name('employee.education_information.import');
        });
    });

    Route::controller(EmployeeEmploymentHistoryController::class)->group(function(){
        Route::middleware('permission:employee-management.view')->group(function () {
            Route::get('profile/{id}/employment-history', 'show')->name('employee.profile.employment_history');
        });
        Route::middleware('permission:employee-management.create')->group(function () {
            Route::get('employment-history/create/{id}', 'create')->name('employee.employment_history.create');
            Route::post('employment-history/store', 'store')->name('employee.employment_history.store');
        });
        Route::middleware('permission:employee-management.edit')->group(function () {
            Route::get('employment-history/edit/{id}', 'edit')->name('employee.employment_history.edit');
            Route::put('employment-history/{id}/update', 'update')->name('employee.employment_history.update');
        });
    });

    Route::controller(EmployeeNomineeController::class)->group(function(){
        Route::middleware('permission:employee-management.view')->group(function () {
            Route::get('profile/{id}/nominee-information', 'show')->name('employee.profile.nominee_information');
        });
        Route::middleware('permission:employee-management.create')->group(function () {
            Route::get('nominee-information/create/{id}', 'create')->name('employee.nominee_information.create');
            Route::post('nominee-information/store', 'store')->name('employee.nominee_information.store');
        });
        Route::middleware('permission:employee-management.edit')->group(function () {
            Route::get('nominee-information/edit/{id}', 'edit')->name('employee.nominee_information.edit');
            Route::put('nominee-information/{id}/update', 'update')->name('employee.nominee_information.update');
        });
        Route::middleware('permission:employee-management.import')->group(function () {
            Route::post('nominee-information/import', 'import')->name('employee.nominee_information.import');
        });
    });

    Route::controller(EmployeeSalaryBreakdownController::class)->group(function(){
        Route::middleware('permission:employee-management.view')->group(function () {
            Route::get('profile/{id}/salary-breakdown', 'show')->name('employee.profile.salary_breakdown');
        });
        Route::middleware('permission:employee-management.create')->group(function () {
            Route::get('salary-breakdown/create/{id}', 'create')->name('employee.salary_breakdown.create');
            Route::post('salary-breakdown/store', 'store')->name('employee.salary_breakdown.store');
        });
        Route::middleware('permission:employee-management.edit')->group(function () {
            Route::get('salary-breakdown/edit/{id}', 'edit')->name('employee.salary_breakdown.edit');
            Route::put('salary-breakdown/{id}/update', 'update')->name('employee.salary_breakdown.update');
        });
        Route::middleware('permission:employee-management.import')->group(function () {
            Route::post('salary-breakdown/import', 'import')->name('employee.salary_breakdown.import');
        });
    });

    Route::controller(EmployeeBankAccountController::class)->group(function () {
        Route::middleware('permission:employee-management.view')->group(function () {
            Route::get('profile/{id}/bank-accounts', 'show')->name('employee.profile.bank_accounts');
        });
        Route::middleware('permission:employee-management.create')->group(function () {
            Route::get('bank-accounts/create/{id}', 'create')->name('employee.bank_accounts.create');
            Route::post('bank-accounts/store', 'store')->name('employee.bank_accounts.store');
        });
        Route::middleware('permission:employee-management.edit')->group(function () {
            Route::get('bank-accounts/edit/{id}', 'edit')->name('employee.bank_accounts.edit');
            Route::put('bank-accounts/{id}/update', 'update')->name('employee.bank_accounts.update');
        });
        Route::middleware('permission:employee-management.import')->group(function () {
            Route::post('bank-accounts/import', 'import')->name('employee.bank_accounts.import');
        });
    });

    Route::controller(EmployeePlansController::class)->group(function () {
        Route::middleware('permission:employee-management.view')->group(function () {
            Route::get('profile/{id}/plans/{type}', 'plansView')->name('employee.profile.plans');
        });
        Route::middleware('permission:employee-management.edit')->group(function () {
            Route::post('profile/plans/{type}/store', 'assignPlan')->name('employee.profile.plans.store');
            Route::put('profile/plans/{type}/remove/{id}', 'removePlan')->name('employee.profile.plans.remove');
        });
        Route::middleware('permission:employee-management.delete')->group(function () {
            Route::delete('profile/plans/{type}/delete/{id}', 'deletePlan')->name('employee.profile.plans.delete');
        });
    });

    Route::get('profile/{id}/leave-info', [LeavesController::class, 'showLeaveInfo'])->name('employee.profile.leave_info')->middleware('permission:leaves.view');

});


Route::prefix('plans')->middleware('auth')->group(function () {
    Route::get('bulk-upload', function () {
        return view('plan.bulk_uploads.form');
    })->name('plan.bulk_upload')->middleware('permission:employee-management.import');

    Route::prefix('meal-plans')->group(function () {
        Route::controller(MealPlansController::class)->group(function(){
            Route::middleware('permission:meal-plans.view')->group(function () {
                Route::get('/', 'index')->name('plan.meal_plans.index');
            });
            Route::middleware('permission:meal-plans.create')->group(function () {
                Route::post('store', 'store')->name('plan.meal_plans.store');
                Route::post('import', 'import')->name('plan.meal_plans.import');
            });
            Route::middleware('permission:meal-plans.edit')->group(function () {
                Route::put('update/{id}', 'update')->name('plan.meal_plans.update');
            });
            Route::middleware('permission:meal-plans.delete')->group(function () {
                Route::delete('delete/{id}', 'delete')->name('plan.meal_plans.delete');
            });
        });
    });

    Route::prefix('shift-plans')->group(function () {
        Route::controller(ShiftPlanController::class)->group(function(){
            Route::middleware('permission:shift-plans.view')->group(function () {
                Route::get('/', 'index')->name('plan.shift_plans.index');
            });
            Route::middleware('permission:shift-plans.create')->group(function () {
                Route::get('create', 'create')->name('plan.shift_plans.create');
                Route::post('store', 'store')->name('plan.shift_plans.store');
                Route::post('import', 'import')->name('plan.shift_plans.import');
            });
            Route::middleware('permission:shift-plans.edit')->group(function () {
                Route::get('edit/{id}', 'edit')->name('plan.shift_plans.edit');
                Route::put('update/{id}', 'update')->name('plan.shift_plans.update');
            });
            Route::middleware('permission:shift-plans.delete')->group(function () {
                Route::delete('delete/{id}', 'delete')->name('plan.shift_plans.delete');
            });
            Route::middleware('permission:shift-plans.view')->group(function () {
                Route::get('{id}', 'show')->name('plan.shift_plans.show');
            });
        });
    });

    Route::prefix('leave-plans')->group(function () {
        Route::controller(LeavePlanController::class)->group(function(){
            Route::middleware('permission:leave-plans.view')->group(function () {
                Route::get('/', 'index')->name('plan.leave_plans.index');
            });
            Route::middleware('permission:leave-plans.create')->group(function () {
                Route::get('create', 'create')->name('plan.leave_plans.create');
                Route::post('store', 'store')->name('plan.leave_plans.store');
                Route::post('import', 'import')->name('plan.leave_plans.import');
            });
            Route::middleware('permission:leave-plans.edit')->group(function () {
                Route::get('edit/{id}', 'edit')->name('plan.leave_plans.edit');
                Route::put('update/{id}', 'update')->name('plan.leave_plans.update');
            });
            Route::middleware('permission:leave-plans.delete')->group(function () {
                Route::delete('delete/{id}', 'delete')->name('plan.leave_plans.delete');
            });
            Route::middleware('permission:leave-plans.view')->group(function () {
                Route::get('{id}', 'show')->name('plan.leave_plans.show');
            });
        });
    });

    Route::prefix('ot-plans')->group(function () {
        Route::controller(OTPlanController::class)->group(function(){
            Route::middleware('permission:ot-plans.view')->group(function () {
                Route::get('/', 'index')->name('plan.ot_plans.index');
            });
            Route::middleware('permission:ot-plans.create')->group(function () {
                Route::get('create', 'create')->name('plan.ot_plans.create');
                Route::post('store', 'store')->name('plan.ot_plans.store');
                Route::post('import', 'import')->name('plan.ot_plans.import');
            });
            Route::middleware('permission:ot-plans.edit')->group(function () {
                Route::get('edit/{id}', 'edit')->name('plan.ot_plans.edit');
                Route::put('update/{id}', 'update')->name('plan.ot_plans.update');
            });
            Route::middleware('permission:ot-plans.delete')->group(function () {
                Route::delete('delete/{id}', 'delete')->name('plan.ot_plans.delete');
            });
            Route::middleware('permission:ot-plans.view')->group(function () {
                Route::get('{id}', 'show')->name('plan.ot_plans.show');
            });
        });
    });

    Route::prefix('roster-plans')->group(function () {
        Route::controller(RosterPlansController::class)->group(function(){
            Route::middleware('permission:roster-plans.view')->group(function () {
                Route::get('/', 'index')->name('plan.roster_plans.index');
            });
            Route::middleware('permission:roster-plans.create')->group(function () {
                Route::get('create', 'create')->name('plan.roster_plans.create');
                Route::post('store', 'store')->name('plan.roster_plans.store');
                Route::post('import', 'import')->name('plan.roster_plans.import');
            });
            Route::middleware('permission:roster-plans.edit')->group(function () {
                Route::get('edit/{id}', 'edit')->name('plan.roster_plans.edit');
                Route::put('update/{id}', 'update')->name('plan.roster_plans.update');
            });
            Route::middleware('permission:roster-plans.delete')->group(function () {
                Route::delete('delete/{id}', 'delete')->name('plan.roster_plans.delete');
            });
            Route::middleware('permission:roster-plans.view')->group(function () {
                Route::get('{id}', 'show')->name('plan.roster_plans.show');
            });
        });
    });

    Route::prefix('off-day-plans')->group(function () {
        Route::controller(OffDayPlansController::class)->group(function(){
            Route::middleware('permission:off-day-work-plans.view')->group(function () {
                Route::get('/', 'index')->name('plan.off_day_plans.index');
            });
            Route::middleware('permission:off-day-work-plans.create')->group(function () {
                Route::get('create', 'create')->name('plan.off_day_plans.create');
                Route::post('store', 'store')->name('plan.off_day_plans.store');
                Route::post('import', 'import')->name('plan.off_day_plans.import');
            });
            Route::middleware('permission:off-day-work-plans.edit')->group(function () {
                Route::get('edit/{id}', 'edit')->name('plan.off_day_plans.edit');
                Route::put('update/{id}', 'update')->name('plan.off_day_plans.update');
            });
            Route::middleware('permission:off-day-work-plans.delete')->group(function () {
                Route::delete('delete/{id}', 'delete')->name('plan.off_day_plans.delete');
            });
            Route::middleware('permission:off-day-work-plans.view')->group(function () {
                Route::get('{id}', 'show')->name('plan.off_day_plans.show');
            });
        });
    });

    Route::prefix('bonus-plans')->group(function () {
        Route::controller(BonusPlanController::class)->group(function(){
            Route::middleware('permission:bonus-plans.view')->group(function () {
                Route::get('/', 'index')->name('plan.bonus_plans.index');
            });
            Route::middleware('permission:bonus-plans.create')->group(function () {
                Route::get('create', 'create')->name('plan.bonus_plans.create');
                Route::post('store', 'store')->name('plan.bonus_plans.store');
                Route::post('import', 'import')->name('plan.bonus_plans.import');
            });
            Route::middleware('permission:bonus-plans.edit')->group(function () {
                Route::get('edit/{id}', 'edit')->name('plan.bonus_plans.edit');
                Route::put('update/{id}', 'update')->name('plan.bonus_plans.update');
            });
            Route::middleware('permission:bonus-plans.delete')->group(function () {
                Route::delete('delete/{id}', 'delete')->name('plan.bonus_plans.delete');
            });
            Route::middleware('permission:bonus-plans.view')->group(function () {
                Route::get('{id}', 'show')->name('plan.bonus_plans.show');
            });
        });
    });

    Route::prefix('allowance-plans')->group(function () {
        Route::controller(AllowancePlanController::class)->group(function(){
            Route::middleware('permission:allowance-plans.view')->group(function () {
                Route::get('/', 'index')->name('plan.allowance_plans.index');
            });
            Route::middleware('permission:allowance-plans.create')->group(function () {
                Route::get('create', 'create')->name('plan.allowance_plans.create');
                Route::post('store', 'store')->name('plan.allowance_plans.store');
                Route::post('import', 'import')->name('plan.allowance_plans.import');
            });
            Route::middleware('permission:allowance-plans.edit')->group(function () {
                Route::get('edit/{id}', 'edit')->name('plan.allowance_plans.edit');
                Route::put('update/{id}', 'update')->name('plan.allowance_plans.update');
            });
            Route::middleware('permission:allowance-plans.delete')->group(function () {
                Route::delete('delete/{id}', 'delete')->name('plan.allowance_plans.delete');
            });
            Route::middleware('permission:allowance-plans.view')->group(function () {
                Route::get('{id}', 'show')->name('plan.allowance_plans.show');
            });
        });
    });

    Route::prefix('ta-plans')->group(function () {
        Route::controller(TAPlanController::class)->group(function(){
            Route::middleware('permission:ta-plans.view')->group(function () {
                Route::get('/', 'index')->name('plan.ta_plans.index');
            });
            Route::middleware('permission:ta-plans.create')->group(function () {
                Route::get('create', 'create')->name('plan.ta_plans.create');
                Route::post('store', 'store')->name('plan.ta_plans.store');
                Route::post('import', 'import')->name('plan.ta_plans.import');
            });
            Route::middleware('permission:ta-plans.edit')->group(function () {
                Route::get('edit/{id}', 'edit')->name('plan.ta_plans.edit');
                Route::put('update/{id}', 'update')->name('plan.ta_plans.update');
            });
            Route::middleware('permission:ta-plans.delete')->group(function () {
                Route::delete('delete/{id}', 'delete')->name('plan.ta_plans.delete');
            });
        });
    });

    Route::prefix('da-plans')->group(function () {
        Route::controller(DAPlanController::class)->group(function(){
            Route::middleware('permission:da-plans.view')->group(function () {
                Route::get('/', 'index')->name('plan.da_plans.index');
            });
            Route::middleware('permission:da-plans.create')->group(function () {
                Route::get('create', 'create')->name('plan.da_plans.create');
                Route::post('store', 'store')->name('plan.da_plans.store');
                Route::post('import', 'import')->name('plan.da_plans.import');
            });
            Route::middleware('permission:da-plans.edit')->group(function () {
                Route::get('edit/{id}', 'edit')->name('plan.da_plans.edit');
                Route::put('update/{id}', 'update')->name('plan.da_plans.update');
            });
            Route::middleware('permission:da-plans.delete')->group(function () {
                Route::delete('delete/{id}', 'delete')->name('plan.da_plans.delete');
            });
        });
    });

    Route::prefix('deduction-plans')->group(function () {
        Route::controller(DeductionPlanController::class)->group(function(){
            Route::middleware('permission:deduction-plan.view')->group(function () {
                Route::get('/', 'index')->name('plan.deduction_plans.index');
            });
            Route::middleware('permission:deduction-plan.create')->group(function () {
                Route::post('store', 'store')->name('plan.deduction_plans.store');
            });
            Route::middleware('permission:deduction-plan.edit')->group(function () {
                Route::get('edit', 'edit')->name('plan.deduction_plans.edit');
                Route::put('update', 'update')->name('plan.deduction_plans.update');
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
        'companies' => App\Models\Company\Company::all(),
        'employees' => App\Models\Employee\Employee::all(),
    ]);
})->name('salary.process')->middleware(['auth', 'permission:salary.view']);

Route::prefix('settings')->middleware('auth')->group(function () {
    Route::controller(SettingsController::class)->group(function (){
       Route::middleware('permission:general-settings.view')->group(function () {
           Route::get('general-settings', 'generalSettingIndex')->name('setting.general_settings');
       });
       Route::middleware('permission:general-settings.create')->group(function () {
           Route::post('general-settings/save', 'generalSettingSave')->name('setting.general_settings.store');
       });

       Route::middleware('permission:smtp.view')->group(function () {
           Route::get('mail-settings', 'mailSettingIndex')->name('setting.mail_settings');
       });
       Route::middleware('permission:smtp.create')->group(function () {
           Route::post('mail-settings/save', 'mailSettingSave')->name('setting.mail_settings.save');
           Route::post('mail-settings/test', 'sendTestMail')->name('setting.mail_settings.test');
       });
    });

    Route::controller(ApiKeyController::class)->group(function (){
       Route::middleware('permission:api-keys.view')->group(function () {
           Route::get('api-keys', 'index')->name('setting.api_keys');
       });
       Route::middleware('permission:api-keys.create')->group(function () {
           Route::post('api-keys/save', 'save')->name('setting.api_keys.save');
       });
    });

    // ID Card Design Routes
    Route::controller(\App\Http\Controllers\Setting\IDCardDesignController::class)->group(function (){
       Route::middleware('permission:id-card-design.view')->group(function () {
           Route::get('id-design', 'index')->name('setting.id_design.index');
       });
       Route::middleware('permission:id-card-design.create')->group(function () {
           Route::get('id-design/create', 'create')->name('setting.id_design.create');
           Route::post('id-design/store', 'store')->name('setting.id_design.store');
       });
       Route::middleware('permission:id-card-design.view')->group(function () {
           Route::get('id-design/{id}', 'show')->name('setting.id_design.show');
           Route::get('id-design/{id}/preview', 'preview')->name('setting.id_design.preview');
           Route::get('id-design/{id}/download', 'download')->name('setting.id_design.download');
       });
       Route::middleware('permission:id-card-design.edit')->group(function () {
           Route::post('id-design/{id}/activate', 'activate')->name('setting.id_design.activate');
           Route::post('id-design/{id}/deactivate', 'deactivate')->name('setting.id_design.deactivate');
       });
       Route::middleware('permission:id-card-design.delete')->group(function () {
           Route::delete('id-design/{id}/delete', 'destroy')->name('setting.id_design.destroy');
       });
    });

    // Transfer Settings Routes
    Route::controller(\App\Http\Controllers\Setting\TransferSettingController::class)->group(function () {
        Route::middleware('permission:general-settings.view')->group(function () {
            Route::get('transfer-settings', 'index')->name('setting.transfer.index');
            Route::post('transfer-settings/update', 'update')->name('setting.transfer.update');
        });
    });

    // Notification Settings Routes
    Route::controller(\App\Http\Controllers\Setting\NotificationSettingController::class)->group(function () {
        Route::middleware('permission:general-settings.view')->group(function () {
            Route::get('notification-settings', 'index')->name('setting.notification_settings.index');
            Route::post('notification-settings/store', 'store')->name('setting.notification_settings.store');
        });
    });
});

Route::prefix('leaves')->middleware('auth')->group(function () {
    Route::controller(LeavesController::class)->group(function (){
        Route::middleware('permission:leaves.view')->group(function () {
            Route::get('/', 'index')->name('leave.index');
        });
        Route::middleware('permission:leaves.create')->group(function () {
            Route::get('create', 'create')->name('leave.create');
            Route::post('store', 'store')->name('leave.store');
            Route::post('import', 'import')->name('leave.import');
        });
        Route::middleware('permission:leaves.hr-approve')->group(function () {
            Route::put('change-status', 'changeStatus')->name('leave.change_status');
        });
        Route::middleware('permission:leaves.delete')->group(function () {
            Route::delete('{id}/delete', 'destroy')->name('leave.destroy');
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
        Route::put('change-payment-status', 'changePaymentStatus')->name('movement.change_payment_status');
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
    Route::get('get-office-info/{employee_id}', 'getEmployeeOfficeInfo');

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
            Route::get('vehicle-allocations', 'dashboard.index')->name('vehicle_allocations.dashboard');
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
    return view('setting.database_backup')->with(compact('title', 'section', 'sub_section'));
})->name('db_backup')->middleware(['auth', 'permission:db-backup.download']);

Route::get('flex/db-dump', [\Flex\DbDump\Http\Controllers\DbDumpController::class, 'download'])->name('flex_db_dump')->middleware(['auth', 'permission:db-backup.download']);

// Trial Routes - Payroll Process Index
Route::get('/trial/payroll-process', function() {
    return view('trial.payroll-process-index');
})->name('trial.payroll_process.index')->middleware(['auth', 'permission:salary.view']);

// Role Management
Route::prefix('settings')->name('setting.')->middleware(['auth', 'permission:role-management.view'])->group(function () {
    Route::resource('roles', \App\Http\Controllers\Setting\RoleController::class);
});

// Transfer Routes
Route::prefix('transfer')->name('transfer.')->middleware('auth')->group(function () {
    Route::controller(\App\Http\Controllers\Transfer\TransferController::class)->group(function () {
        Route::middleware('permission:transfers.view')->group(function () {
            Route::get('logs', 'index')->name('index');
            Route::get('view/{id}', 'show')->name('show');
        });
        Route::middleware('permission:transfers.create')->group(function () {
            Route::get('application', 'create')->name('create');
        });
    });

    // API Routes (Returning JSON)
    Route::prefix('api')->name('api.')->controller(\App\Http\Controllers\Transfer\TransferAPIController::class)->group(function () {
        Route::get('employees', 'getEmployees')->name('employees');
        Route::get('companies', 'getCompanies')->name('companies');
        Route::get('units/{companyId}', 'getUnits')->name('units');
        Route::get('divisions/{companyId}/{locationId}', 'getDivisions')->name('divisions');
        Route::get('departments/{companyId}/{locationId}/{divisionId}', 'getDepartments')->name('departments');
        Route::get('sections/{companyId}/{locationId}/{divisionId}/{departmentId}', 'getSections')->name('sections');
        Route::get('designations', 'getDesignations')->name('designations');
        
        Route::post('store', 'store')->name('store')->middleware('permission:transfers.create');
        Route::get('list', 'list')->name('list')->middleware('permission:transfers.view');
        
        Route::post('set-approvers/{id}', 'setApprovers')->name('set_approvers')->middleware('permission:transfers.approve');
        Route::post('approve/{id}', 'approve')->name('approve')->middleware('permission:transfers.approve');
        Route::post('complete/{id}', 'complete')->name('complete')->middleware('permission:transfers.edit');
        Route::get('search-authorities', 'searchAuthorities')->name('search_authorities');
    });
});

require __DIR__.'/auth.php';


