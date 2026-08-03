@php
    $generalSettings = \App\HelperClass::getGeneralSetting();
@endphp
<!-- Left Sidebar Start -->
<div class="app-sidebar-menu">
    <div class="h-100" data-simplebar="">

        <!--- Sidemenu -->
        <div id="sidebar-menu">

            <div class="logo-box">
                <a class='logo' href='{{ route('dashboard.index') }}'>
                    <span class="logo-sm">
                        <img src="{{ asset('assets/images/logo-sm.png') }}" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img class="logo-img-light"
                            src="{{ isset($generalSettings->logo_dark) ? \App\HelperClass::get_file_url($generalSettings->logo_dark) : asset('assets/images/logo-light.png') }}"
                            alt="">
                        <img class="logo-img-dark"
                            src="{{ isset($generalSettings->logo_light) ? \App\HelperClass::get_file_url($generalSettings->logo_light) : asset('assets/images/logo-dark.png') }}"
                            alt="">
                    </span>
                </a>
            </div>

            <ul id="side-menu">
                @php
                    $isEmployeeUser = auth()->user()->user_type === \App\Enums\UserType::Employee;
                    $isEmployeeType = auth()->user()->user_type === \App\Enums\UserType::Employee;
                    $canViewEmployeeInfo = auth()->user()->can('employee-management.view');
                    $canReviewProfile = auth()->user()->can('employee-management.profile-review');
                    $canSearchEmployee = auth()->user()->can('employee-management.view') && !$isEmployeeType;
                    $canBulkUploadEmployee = auth()->user()->can('employee-management.import');
                    $showEmployeesMenu = $canViewEmployeeInfo || $canReviewProfile || $canSearchEmployee || $canBulkUploadEmployee;

                    // Career Movement (Transfer)
                    $canTransferApplication = auth()->user()->can('transfers.create');
                    $canTransferLogs = auth()->user()->can('transfers.view');
                    $showTransferMenu = $canTransferApplication || $canTransferLogs;
                    $transferOpen = Route::is('transfer.*');

                    // General Section
                    $showGeneralTitle = auth()->user()->can('dashboard.view') || auth()->user()->can('announcements.view');

                    // Employee Management Section
                    $showEmployeeManagementTitle = ($isEmployeeUser || auth()->user()->can('employee-management.view')) || $showEmployeesMenu || $showTransferMenu;

                    // Attendance, Leaves, and Plans
                    $canClockInOut = auth()->user()->can('attendance.clock-in-out');
                    $canCreateAttendance = auth()->user()->can('attendance.create');
                    $canBulkUploadAttendance = auth()->user()->can('attendance.import');
                    $canRecords = auth()->user()->can('attendance.view');
                    $showAttendanceMenu = $canClockInOut || $canCreateAttendance || $canBulkUploadAttendance || $canRecords;
                    $attendanceOpen = Route::is('attendance.*');

                    $canLeaveApplication = auth()->user()->can('leaves.create');
                    $canLeaveLogs = auth()->user()->can('leaves.view');
                    $showLeavesMenu = $canLeaveApplication || $canLeaveLogs;
                    $leavesOpen = Route::is('leave.*');

                    $canMealPlans = auth()->user()->can('leave-plans.view') || auth()->user()->can('meal-plans.view');
                    $canShiftPlans = auth()->user()->can('shift-plans.view');
                    $canLeavePlans = auth()->user()->can('leave-plans.view');
                    $canOTPlans = auth()->user()->can('ot-plans.view');
                    $canRosterPlans = auth()->user()->can('roster-plans.view');
                    $canOffDayPlans = auth()->user()->can('off-day-work-plans.view');
                    $canBonusPlans = auth()->user()->can('bonus-plans.view');
                    $canAllowancePlans = auth()->user()->can('allowance-plans.view');
                    $canTAPlans = auth()->user()->can('ta-plans.view');
                    $canDAPlans = auth()->user()->can('da-plans.view');
                    $canDeductionPlans = auth()->user()->can('deduction-plan.view');
                    $canBulkUploadPlans = auth()->user()->can('employee-management.import');
                    $showPlansMenu = $canMealPlans || $canShiftPlans || $canLeavePlans || $canOTPlans || $canRosterPlans || $canOffDayPlans || $canBonusPlans || $canAllowancePlans || $canTAPlans || $canDAPlans || $canDeductionPlans || $canBulkUploadPlans;

                    $showTimeAndLeavesTitle = $showAttendanceMenu || $showLeavesMenu || $showPlansMenu;

                    // Transport Menu
                    $canVehicles = auth()->user()->can('vehicles.view');
                    $canAssignDriver = auth()->user()->can('assign-driver.view');
                    $canVehicleRequisition = auth()->user()->can('vehicle-requisition.view');
                    $canEmployeeTransport = auth()->user()->can('employee-transport.view');
                    $canVehicleAllocation = auth()->user()->can('vehicle-allocation.view');

                    $showVehicleRouteMenu = $canVehicles || $canAssignDriver || $canEmployeeTransport;
                    $vehicleRouteOpen = Route::is('transport.vehicles.*') || Route::is('transport.vehicle_drivers.*') || Route::is('transport.route_maps.*');

                    $showRequisitionMenu = $canVehicleRequisition || $canEmployeeTransport;
                    $requisitionOpen = Route::is('transport.vehicle_requisitions.*') || Route::is('transport.employee_transports.*');

                    $showTransportMenu = $showVehicleRouteMenu || $showRequisitionMenu || $canVehicleAllocation;
                    $transportOpen = Route::is('transport.*');

                    // Onboarding
                    $canCvBankView = auth()->user()->can('cv-bank.view');
                    $showOnboardingTitle = $canCvBankView;
                    $onboardingOpen = Route::is('cv_bank.*');

                    // Offboarding
                    $canResignationsView = auth()->user()->can('resignations.view') || auth()->user()->can('offboarding-resignation.view');
                    $canResignationsCreate = auth()->user()->can('resignations.create');
                    $canTerminationsView = auth()->user()->can('terminations.view') || auth()->user()->can('offboarding-termination.view');
                    $canTerminationsCreate = auth()->user()->can('terminations.create');
                    $showOffboardingTitle = $canResignationsView || $canResignationsCreate || $canTerminationsView || $canTerminationsCreate;

                    // Travel Movement, Claim Expense, Payroll
                    $canMovementApplication = auth()->user()->can('movement.create');
                    $canMovementLogs = auth()->user()->can('movement.view');
                    $showMovementMenu = $canMovementApplication || $canMovementLogs;
                    $movementOpen = Route::is('movement.*');

                    $canClaimExpenseApplication = auth()->user()->can('claim-expenses.create');
                    $canClaimExpenseLogs = auth()->user()->can('claim-expenses.view');
                    $showClaimExpenseMenu = $canClaimExpenseApplication || $canClaimExpenseLogs;
                    $claimExpenseOpen = Route::is('claim_expenses.*');

                    $canPromotions = auth()->user()->can('promotions.view');
                    $canDemotions = auth()->user()->can('demotions.view');
                    $canIncrements = auth()->user()->can('increments.view');
                    $canDecrements = auth()->user()->can('decrements.view');
                    $canBonuses = auth()->user()->can('bonuses.view');
                    $canAdvanceSalary = auth()->user()->can('advance-salary.view');
                    $canArrear = auth()->user()->can('arrear.view');
                    $canSalary = auth()->user()->can('salary.view');
                    $canBills = auth()->user()->can('bills.view');
                    $showPayrollMenu = $canPromotions || $canDemotions || $canIncrements || $canDecrements || $canBonuses || $canAdvanceSalary || $canArrear || $canSalary || $canBills;
                    $payrollOpen = request()->is('promotion*') || request()->is('demotion*') || request()->is('increment*') || request()->is('decrement*') || request()->is('bonus*')
                    || request()->is('advance-salary*') || request()->is('arrear*') || request()->is('salary*') || request()->is('bills*');

                    $canTaxPolicyView = auth()->user()->can('tax-policy.view');
                    $canTaxCalculateView = auth()->user()->can('tax-calculate.view');
                    $canEstimatedTaxView = auth()->user()->can('estimated-tax.view');
                    $canTaxDeductionView = auth()->user()->can('tax-deduction.view');
                    $canTaxChallanView = auth()->user()->can('tax-challan.view');
                    $showTaxMenu = $canTaxPolicyView || $canTaxCalculateView || $canEstimatedTaxView || $canTaxDeductionView || $canTaxChallanView;

                    $showFinanceTitle = $showMovementMenu || $showClaimExpenseMenu || $showPayrollMenu || $showTaxMenu;

                    // Company Setup / Setup, Structure, Settings
                    $canGroups = auth()->user()->can('groups.view');
                    $canCompanyTypes = auth()->user()->can('company-types.view');
                    $canCompanies = auth()->user()->can('companies.view');
                    $canCompanyBranches = auth()->user()->can('company-branches.view') && (isset($generalSettings->branch_status) && $generalSettings->branch_status == 1);
                    $canDivisions = auth()->user()->can('divisions.view') && (isset($generalSettings->division_status) && $generalSettings->division_status == 1);
                    $canDepartments = auth()->user()->can('departments.view') && (isset($generalSettings->department_status) && $generalSettings->department_status == 1);
                    $canSections = auth()->user()->can('sections.view') && (isset($generalSettings->section_status) && $generalSettings->section_status == 1);
                    $canDesignations = auth()->user()->can('designations.view');
                    $canPayGroups = auth()->user()->can('pay-groups.view');
                    $canPayScales = auth()->user()->can('pay-scales.view');
                    $canMovementTypes = auth()->user()->can('movement-types.view');
                    $canSalaryGrades = auth()->user()->can('salary-grades.view');
                    $canBanks = auth()->user()->can('banks.view');
                    $canBankBranches = auth()->user()->can('bank-branches.view');
                    $canBankAccounts = auth()->user()->can('bank-accounts.view');
                    $canHolidays = auth()->user()->can('holidays.view');
                    $canJobCreations = auth()->user()->can('job-creations.view');
                    $canBulkUploadCompany = auth()->user()->can('employee-management.import');
                    $canExpenseTypes = auth()->user()->can('expense-types.view');

                    $showCompanyMenu = $canGroups || $canCompanyTypes || $canCompanies || $canCompanyBranches || $canDivisions || $canDepartments || $canSections || $canDesignations || $canPayGroups || $canPayScales || $canMovementTypes || $canSalaryGrades || $canBanks || $canBankBranches || $canBankAccounts || $canHolidays || $canJobCreations || $canBulkUploadCompany || $canExpenseTypes;

                    $companyOpen =
                        Route::is('groups.*') ||
                        Route::is('companies.*') ||
                        Route::is('company_types.*') ||
                        Route::is('company_locations.*') ||
                        Route::is('pay_groups.*') ||
                        Route::is('pay_scales.*') ||
                        Route::is('movement_types.*') ||
                        Route::is('banks.*') ||
                        Route::is('branches.*') ||
                        Route::is('bank_accounts.*') ||
                        Route::is('salary_grades.*') ||
                        Route::is('holidays.*') ||
                        Route::is('job_creations.*') ||
                        Route::is('gazette_locations.*') ||
                        Route::is('company.bulk_upload') ||
                        Route::is('expense_types.*');

                    $canStructuralView = auth()->user()->can('structural-view.view');
                    $canMembers = auth()->user()->can('members.view');
                    $showStructureMenu = $canStructuralView || $canMembers;

                    $canGeneralSettings = auth()->user()->can('general-settings.view');
                    $canIDCardDesign = auth()->user()->can('id-card-design.view');
                    $canAPIKeys = auth()->user()->can('api-keys.view');
                    $canSMTP = auth()->user()->can('smtp.view');
                    $canDBBackup = auth()->user()->can('db-backup.download');
                    $canRoleManagement = auth()->user()->can('role-management.view');
                    $canApprovalWorkflows = auth()->user()->can('approval-workflows.view');
                    $canAuditLogs = auth()->user()->can('audit-logs.view');
                    $showSettingsMenu = $canGeneralSettings || $canIDCardDesign || $canAPIKeys || $canSMTP || $canDBBackup || $canRoleManagement || $canApprovalWorkflows || $canAuditLogs;
                    $settingsOpen = Route::is('setting.*') || Route::is('audit_logs.*') || Route::is('db_backup');

                    $showAdministrationTitle = $showCompanyMenu || $showStructureMenu || $showSettingsMenu;
                @endphp

                {{-- General Section --}}
                @if($showGeneralTitle)
                <li class="menu-title">General</li>
                @endif

                @if(auth()->user()->can('dashboard.view'))
                <li>
                    <a href="{{ route('dashboard.index') }}" class="@if (Route::is('dashboard.index')) menuitem-active @endif">
                        <i data-feather="home"></i>
                        <span> Dashboard </span>
                    </a>
                </li>
                @endif

                @if(auth()->user()->can('announcements.view'))
                <li>
                    <a href="{{ route('announcements.index') }}" class="@if (Route::is('announcements.*')) menuitem-active @endif">
                        <i data-feather="volume-2"></i>
                        <span> Announcements </span>
                    </a>
                </li>
                @endif

                {{-- Employee Management Section --}}
                @if($showEmployeeManagementTitle)
                <li class="menu-title">Employee Management</li>
                @endif

                @if($isEmployeeUser || auth()->user()->can('employee-management.view'))
                <li>
                    <a href="{{ route('employee.dashboard') }}" class="@if (Route::is('employee.dashboard*')) menuitem-active @endif">
                        <i data-feather="activity"></i>
                        <span> Employee Dashboard </span>
                    </a>
                </li>
                @endif

                <!-- Employees Menu -->
                @if($showEmployeesMenu)
                <li>
                    @if($isEmployeeType && !$canViewEmployeeInfo && !$canReviewProfile)
                        @php
                            $employeeInfoUrl = auth()->user()->employee_id 
                                ? route('employee.profile.general_informations', auth()->user()->employee_id) 
                                : '#';
                        @endphp
                        <a href="{{ $employeeInfoUrl }}" class="tp-link @if (Route::is('employee.profile.*')) menuitem-active @endif">
                            <i data-feather="users"></i>
                            <span> Employee Profile </span>
                        </a>
                    @else
                        <a href="#sidebarEmployees" data-bs-toggle="collapse"
                            aria-expanded="{{ Route::is('employee.*') || Route::is('employee.employee') || Route::is('profile_update_requests.*') ? 'true' : 'false' }}"
                            class="@if (Route::is('employee.*') || Route::is('employee.employee') || Route::is('profile_update_requests.*')) menuitem-active @endif">
                            <i data-feather="users"></i>
                            <span> Employees </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse @if (Route::is('employee.*') || Route::is('employee.employee') || Route::is('profile_update_requests.*')) show @endif" id="sidebarEmployees">
                            <ul class="nav-second-level">
                                @if($canViewEmployeeInfo)
                                <li>
                                    @php
                                        $empInfoUrl = ($isEmployeeType && auth()->user()->employee_id) 
                                            ? route('employee.profile.general_informations', auth()->user()->employee_id) 
                                            : route('employee.index');
                                    @endphp
                                    <a class='tp-link @if (Route::is('employee.index') || (Route::is('employee.profile.*') && $isEmployeeType)) menuitem-active @endif'
                                        href='{{ $empInfoUrl }}'>Employee Information</a>
                                </li>
                                @endif

                                @if($canReviewProfile)
                                <li>
                                    <a class='tp-link @if (Route::is('employee.review.index')) menuitem-active @endif'
                                        href='{{ route('employee.review.index') }}'>Profile Review</a>
                                </li>
                                @endif

                                @if(auth()->user()->can('profile-update-requests.view'))
                                <li>
                                    <a class='tp-link @if (Route::is('profile_update_requests.*')) menuitem-active @endif'
                                        href='{{ route('profile_update_requests.index') }}'>Profile Update Requests</a>
                                </li>
                                @endif

                                @if($canSearchEmployee)
                                <li>
                                    <a class='tp-link @if (Route::is('employee.employee')) menuitem-active @endif'
                                        href='{{ route('employee.employee') }}'>Search Employee</a>
                                </li>
                                @endif
                                @if($canBulkUploadEmployee)
                                <li>
                                    <a class='tp-link @if (Route::is('employee.import')) menuitem-active @endif'
                                        href='{{ route('employee.import') }}'>Bulk Upload</a>
                                </li>
                                @endif
                                @if(auth()->user()->can('employee-management.analytics') && auth()->user()->user_type !== \App\Enums\UserType::Employee)
                                <li>
                                    <a class='tp-link @if (Route::is('employee.reports')) menuitem-active @endif'
                                        href='{{ route('employee.reports') }}'>Analytics</a>
                                </li>
                                @endif
                            </ul>
                        </div>
                    @endif
                </li>
                @endif

                <!-- Career Movement (Transfer) -->
                @if($showTransferMenu)
                <li>
                    <a href="#transfer" data-bs-toggle="collapse"
                        aria-expanded="{{ $transferOpen ? 'true' : 'false' }}"
                        class="@if ($transferOpen) menuitem-active @endif">
                        <i data-feather="shuffle"></i>
                        <span> Career Movement </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse @if ($transferOpen) show @endif" id="transfer">
                        <ul class="nav-second-level">
                            @if($canTransferApplication)
                            <li>
                                <a class='tp-link @if (Route::is('transfer.create')) menuitem-active @endif'
                                    href='{{ route('transfer.create') }}'>Application</a>
                            </li>
                            @endif
                            @if($canTransferLogs)
                            <li>
                                <a class='tp-link @if (Route::is('transfer.index')) menuitem-active @endif'
                                    href='{{ route('transfer.index') }}'>Logs</a>
                            </li>
                            @endif
                        </ul>
                    </div>
                </li>
                @endif

                {{-- Onboarding Section --}}
                @if($showOnboardingTitle)
                <li class="menu-title">Onboarding</li>
                @endif

                @if($canCvBankView)
                <li>
                    <a href="{{ route('cv_bank.index') }}" class="tp-link @if (Route::is('cv_bank.*')) menuitem-active @endif">
                        <i data-feather="user-plus"></i>
                        <span> CV Bank </span>
                    </a>
                </li>
                @endif

                {{-- Time and Leaves Section --}}
                @if($showTimeAndLeavesTitle)
                <li class="menu-title">Time and Leaves</li>
                @endif

                @if($showAttendanceMenu)
                <li>
                    <a href="#attendance" data-bs-toggle="collapse"
                        aria-expanded="{{ $attendanceOpen ? 'true' : 'false' }}"
                        class="@if ($attendanceOpen) menuitem-active @endif">
                        <i data-feather="clock"></i>
                        <span> Attendance </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse @if ($attendanceOpen) show @endif" id="attendance">
                        <ul class="nav-second-level">
                            @if($canClockInOut)
                            <li>
                                <a class='tp-link @if (Route::is('attendance.clock_in_out')) menuitem-active @endif'
                                    href='{{ route('attendance.clock_in_out') }}'>Clock In / Out</a>
                            </li>
                            @endif

                            @if($canCreateAttendance)
                            <li>
                                <a class='tp-link @if (Route::is('attendance.create')) menuitem-active @endif'
                                    href='{{ route('attendance.create') }}'>Create</a>
                            </li>
                            @endif

                            @if($canBulkUploadAttendance)
                            <li>
                                <a class='tp-link @if (Route::is('attendance.bulk-upload')) menuitem-active @endif'
                                    href='{{ route('attendance.bulk-upload') }}'>Bulk Upload</a>
                            </li>
                            @endif

                            @if($canRecords)
                            <li>
                                <a class='tp-link @if (Route::is('attendance.index')) menuitem-active @endif'
                                    href='{{ route('attendance.index') }}'>Records</a>
                            </li>
                            @endif
                        </ul>
                    </div>
                </li>
                @endif

                @if($showLeavesMenu)
                <li>
                    <a href="#leaves" data-bs-toggle="collapse" aria-expanded="{{ $leavesOpen ? 'true' : 'false' }}"
                        class="@if ($leavesOpen) menuitem-active @endif">
                        <i data-feather="calendar"></i>
                        <span> Leaves </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse @if ($leavesOpen) show @endif" id="leaves">
                        <ul class="nav-second-level">
                            @if($canLeaveApplication)
                            <li>
                                <a class='tp-link @if (Route::is('leave.create')) menuitem-active @endif'
                                    href='{{ route('leave.create') }}'>Application</a>
                            </li>
                            @endif
                            @if($canLeaveLogs)
                            <li>
                                <a class='tp-link @if (Route::is('leave.index')) menuitem-active @endif'
                                    href='{{ route('leave.index') }}'>Logs</a>
                            </li>
                            @endif
                        </ul>
                    </div>
                </li>
                @endif

                @if($showPlansMenu)
                <li>
                    <a href="#plans" data-bs-toggle="collapse"
                        aria-expanded="{{ Route::is('plan.*') ? 'true' : 'false' }}"
                        class="@if (Route::is('plan.*')) menuitem-active @endif">
                        <i data-feather="layers"></i>
                        <span> Plans </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse @if (Route::is('plan.*')) show @endif" id="plans">
                        <ul class="nav-second-level">
                            @if($canMealPlans)
                            <li>
                                <a class='tp-link @if (Route::is('plan.meal_plans.*')) menuitem-active @endif'
                                    href='{{ route('plan.meal_plans.index') }}'>Meal</a>
                            </li>
                            @endif

                            @if($canShiftPlans)
                            <li>
                                <a class='tp-link @if (Route::is('plan.shift_plans.*')) menuitem-active @endif'
                                    href='{{ route('plan.shift_plans.index') }}'>Shift</a>
                            </li>
                            @endif

                            @if($canLeavePlans)
                            <li>
                                <a class='tp-link @if (Route::is('plan.leave_plans.*')) menuitem-active @endif'
                                    href='{{ route('plan.leave_plans.index') }}'>Leave</a>
                            </li>
                            @endif

                            @if($canOTPlans)
                            <li>
                                <a class='tp-link @if (Route::is('plan.ot_plans.*')) menuitem-active @endif'
                                    href='{{ route('plan.ot_plans.index') }}'>Overtime</a>
                            </li>
                            @endif

                            @if($canRosterPlans)
                            <li>
                                <a class='tp-link @if (Route::is('plan.roster_plans.*')) menuitem-active @endif'
                                    href='{{ route('plan.roster_plans.index') }}'>Roster</a>
                            </li>
                            @endif

                            @if($canOffDayPlans)
                            <li>
                                <a class='tp-link @if (Route::is('plan.off_day_plans.*')) menuitem-active @endif'
                                    href='{{ route('plan.off_day_plans.index') }}'>Off-Day Work</a>
                            </li>
                            @endif

                            @if($canBonusPlans)
                            <li>
                                <a class='tp-link @if (Route::is('plan.bonus_plans.*')) menuitem-active @endif'
                                    href='{{ route('plan.bonus_plans.index') }}'>Bonus & Reward</a>
                            </li>
                            @endif

                            @if(auth()->user()->can('penalty-plans.view'))
                            <li>
                                <a class='tp-link @if (Route::is('plan.penalty_plans.*')) menuitem-active @endif'
                                    href='{{ route('plan.penalty_plans.index') }}'>Penalty</a>
                            </li>
                            @endif

                            @if(auth()->user()->can('leave-encashment-plans.view'))
                            <li>
                                <a class='tp-link @if (Route::is('plan.leave_encashment_plans.*')) menuitem-active @endif'
                                    href='{{ route('plan.leave_encashment_plans.index') }}'>Leave Encashment</a>
                            </li>
                            @endif

                            @if($canAllowancePlans)
                            <li>
                                <a class='tp-link @if (Route::is('plan.allowance_plans.*')) menuitem-active @endif'
                                    href='{{ route('plan.allowance_plans.index') }}'>Allowance</a>
                            </li>
                            @endif

                            @if($canTAPlans)
                            <li>
                                <a class='tp-link @if (Route::is('plan.ta_plans.*')) menuitem-active @endif'
                                    href='{{ route('plan.ta_plans.index') }}'>TA</a>
                            </li>
                            @endif

                            @if($canDAPlans)
                            <li>
                                <a class='tp-link @if (Route::is('plan.da_plans.*')) menuitem-active @endif'
                                    href='{{ route('plan.da_plans.index') }}'>DA</a>
                            </li>
                            @endif

                            @if($canDeductionPlans)
                            <li>
                                <a class='tp-link @if (Route::is('plan.deduction_plans.*')) menuitem-active @endif'
                                    href='{{ route('plan.deduction_plans.index') }}'>Deduction</a>
                            </li>
                            @endif

                            @if($canBulkUploadPlans)
                            <li>
                                <a class='tp-link @if (Route::is('plan.bulk_upload')) menuitem-active @endif'
                                    href='{{ route('plan.bulk_upload') }}'>Bulk Upload</a>
                            </li>
                            @endif
                        </ul>
                    </div>
                </li>
                @endif

                {{-- Transport Section --}}
                @if($showTransportMenu)
                <li class="menu-title">Transport</li>
                @endif

                @if($showVehicleRouteMenu)
                <li>
                    <a href="#sidebarVehicleRoute" data-bs-toggle="collapse"
                        aria-expanded="{{ $vehicleRouteOpen ? 'true' : 'false' }}"
                        class="@if ($vehicleRouteOpen) menuitem-active @endif">
                        <i data-feather="truck"></i>
                        <span> Vehicle & Route </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse @if ($vehicleRouteOpen) show @endif" id="sidebarVehicleRoute">
                        <ul class="nav-second-level">
                            @if($canVehicles)
                            <li>
                                <a class='tp-link @if (Route::is('transport.vehicles.*')) menuitem-active @endif'
                                    href='{{ route('transport.vehicles.index') }}'>Vehicles</a>
                            </li>
                            @endif
                            @if($canAssignDriver)
                            <li>
                                <a class='tp-link @if (Route::is('transport.vehicle_drivers.*')) menuitem-active @endif'
                                    href='{{ route('transport.vehicle_drivers.index') }}'>Assign Driver</a>
                            </li>
                            @endif
                            @if($canEmployeeTransport)
                            <li>
                                <a class='tp-link @if (Route::is('transport.route_maps.*')) menuitem-active @endif'
                                    href='{{ route('transport.route_maps.index') }}'>Route Maps</a>
                            </li>
                            @endif
                        </ul>
                    </div>
                </li>
                @endif

                @if($showRequisitionMenu)
                <li>
                    <a href="#sidebarRequisition" data-bs-toggle="collapse"
                        aria-expanded="{{ $requisitionOpen ? 'true' : 'false' }}"
                        class="@if ($requisitionOpen) menuitem-active @endif">
                        <i data-feather="file-text"></i>
                        <span> Requisition </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse @if ($requisitionOpen) show @endif" id="sidebarRequisition">
                        <ul class="nav-second-level">
                            @if($canVehicleRequisition)
                            <li>
                                <a class='tp-link @if (Route::is('transport.vehicle_requisitions.*')) menuitem-active @endif'
                                    href='{{ route('transport.vehicle_requisitions.index') }}'>Vehicle Requisition</a>
                            </li>
                            @endif
                            @if($canEmployeeTransport)
                            <li>
                                <a class='tp-link @if (Route::is('transport.employee_transports.*')) menuitem-active @endif'
                                    href='{{ route('transport.employee_transports.index') }}'>Employee Transport</a>
                            </li>
                            @endif
                        </ul>
                    </div>
                </li>
                @endif

                @if($canVehicleAllocation)
                <li>
                    <a href="{{ route('transport.vehicle_allocations.dashboard') }}"
                        class="tp-link @if (Route::is('transport.vehicle_allocations.*')) menuitem-active @endif">
                        <i data-feather="key"></i>
                        <span> Allocation </span>
                    </a>
                </li>
                @endif

                {{-- Offboarding Section --}}
                @if($showOffboardingTitle)
                <li class="menu-title">Offboarding</li>
                @endif

                @if($showOffboardingTitle)
                {{-- Resignation Menu --}}
                @if($canResignationsView || $canResignationsCreate)
                @php
                    $resignationOpen = Route::is('offboarding.resignation.*');
                @endphp
                <li>
                    <a href="#sidebarResignation" data-bs-toggle="collapse"
                        aria-expanded="{{ $resignationOpen ? 'true' : 'false' }}"
                        class="@if ($resignationOpen) menuitem-active @endif">
                        <i data-feather="file-text"></i>
                        <span> Resignation </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse @if ($resignationOpen) show @endif" id="sidebarResignation">
                        <ul class="nav-second-level">
                            @if($canResignationsCreate)
                            <li>
                                <a class='tp-link @if (Route::is('offboarding.resignation.create')) menuitem-active @endif'
                                    href='{{ route('offboarding.resignation.create', ['type' => 'resignation']) }}'>Request</a>
                            </li>
                            @endif
                            @if($canResignationsView)
                            <li>
                                <a class='tp-link @if (Route::is('offboarding.resignation.index')) menuitem-active @endif'
                                    href='{{ route('offboarding.resignation.index') }}'>Logs</a>
                            </li>
                            @endif
                        </ul>
                    </div>
                </li>
                @endif

                {{-- Termination Menu --}}
                @if($canTerminationsView || $canTerminationsCreate)
                @php
                    $terminationOpen = Route::is('offboarding.termination.*');
                @endphp
                <li>
                    <a href="#sidebarTermination" data-bs-toggle="collapse"
                        aria-expanded="{{ $terminationOpen ? 'true' : 'false' }}"
                        class="@if ($terminationOpen) menuitem-active @endif">
                        <i data-feather="user-minus"></i>
                        <span> Termination </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse @if ($terminationOpen) show @endif" id="sidebarTermination">
                        <ul class="nav-second-level">
                            @if($canTerminationsCreate)
                            <li>
                                <a class='tp-link @if (Route::is('offboarding.termination.create')) menuitem-active @endif'
                                    href='{{ route('offboarding.termination.create', ['type' => 'termination']) }}'>Request</a>
                            </li>
                            @endif
                            @if($canTerminationsView)
                            <li>
                                <a class='tp-link @if (Route::is('offboarding.termination.index')) menuitem-active @endif'
                                    href='{{ route('offboarding.termination.index') }}'>Logs</a>
                            </li>
                            @endif
                        </ul>
                    </div>
                </li>
                @endif
                @endif

                {{-- Finance Section --}}
                @if($showFinanceTitle)
                <li class="menu-title">Finance</li>
                @endif

                @if($showMovementMenu)
                <li>
                    <a href="#movement" data-bs-toggle="collapse"
                        aria-expanded="{{ $movementOpen ? 'true' : 'false' }}"
                        class="@if ($movementOpen) menuitem-active @endif">
                        <i data-feather="move"></i>
                        <span> Travel Movement </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse @if ($movementOpen) show @endif" id="movement">
                        <ul class="nav-second-level">
                            @if($canMovementApplication)
                            <li>
                                <a class='tp-link @if (Route::is('movement.create')) menuitem-active @endif'
                                    href='{{ route('movement.create') }}'>Application</a>
                            </li>
                            @endif
                            @if($canMovementLogs)
                            <li>
                                <a class='tp-link @if (Route::is('movement.index')) menuitem-active @endif'
                                    href='{{ route('movement.index') }}'>Logs</a>
                            </li>
                            @endif
                        </ul>
                    </div>
                </li>
                @endif

                @if($showClaimExpenseMenu)
                <li>
                    <a href="#claimExpense" data-bs-toggle="collapse"
                        aria-expanded="{{ $claimExpenseOpen ? 'true' : 'false' }}"
                        class="@if ($claimExpenseOpen) menuitem-active @endif">
                        <i data-feather="dollar-sign"></i>
                        <span> Claim Expense </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse @if ($claimExpenseOpen) show @endif" id="claimExpense">
                        <ul class="nav-second-level">
                            @if($canClaimExpenseApplication)
                            <li>
                                <a class='tp-link @if (Route::is('claim_expenses.create')) menuitem-active @endif'
                                    href='{{ route('claim_expenses.create') }}'>Application</a>
                            </li>
                            @endif
                            @if($canClaimExpenseLogs)
                            <li>
                                <a class='tp-link @if (Route::is('claim_expenses.index')) menuitem-active @endif'
                                    href='{{ route('claim_expenses.index') }}'>Logs</a>
                            </li>
                            @endif
                        </ul>
                    </div>
                </li>
                @endif

                @php
                    $taxConfigOpen = request()->is('tax-policy*') || request()->is('tax-calculate*') || request()->is('tax-deduction*') || request()->is('tax-challan*');
                @endphp
                @if($showTaxMenu)
                <li>
                    <a href="#taxConfig" data-bs-toggle="collapse" aria-expanded="{{ $taxConfigOpen ? 'true' : 'false' }}"
                          class="@if ($taxConfigOpen) menuitem-active @endif">
                        <i data-feather="percent"></i>
                        <span> Tax </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse @if ($taxConfigOpen) show @endif" id="taxConfig">
                        <ul class="nav-second-level">
                            @if($canTaxPolicyView)
                            <li>
                                <a class='tp-link @if (Route::is('tax-policy.index') || Route::is('tax-policy.create') || Route::is('tax-policy.edit')) menuitem-active @endif'
                                    href='{{ route('tax-policy.index') }}'>Tax Policy</a>
                            </li>
                            @endif
                            @if($canTaxCalculateView)
                            <li>
                                <a class='tp-link @if (Route::is('tax-calculate.process')) menuitem-active @endif'
                                    href='{{ route('tax-calculate.process') }}'>Calculate Tax</a>
                            </li>
                            @endif
                            @if($canEstimatedTaxView)
                            <li>
                                <a class='tp-link @if (Route::is('tax-calculate.index')) menuitem-active @endif'
                                    href='{{ route('tax-calculate.index') }}'>Estimated Tax</a>
                            </li>
                            @endif
                            @if($canTaxDeductionView)
                            <li>
                                <a class='tp-link @if (Route::is('tax-deduction.index')) menuitem-active @endif'
                                    href='{{ route('tax-deduction.index') }}'>Tax Deduction</a>
                            </li>
                            @endif
                            @if($canTaxChallanView)
                            <li>
                                <a class='tp-link @if (Route::is('tax-challan.index')) menuitem-active @endif'
                                    href='{{ route('tax-challan.index') }}'>Tax Challan</a>
                            </li>
                            @endif
                        </ul>
                    </div>
                </li>
                @endif

                @if($showPayrollMenu)
                <li>
                    <a href="#payroll" data-bs-toggle="collapse" aria-expanded="{{ $payrollOpen ? 'true' : 'false' }}"
                        class="@if ($payrollOpen) menuitem-active @endif">
                        <i data-feather="dollar-sign"></i>
                        <span> Payroll </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse @if ($payrollOpen) show @endif" id="payroll">
                        <ul class="nav-second-level">
                            @if($canPromotions)
                            <li>
                                <a class='tp-link @if (request()->is('promotion') && !request()->is('promotion/create')) menuitem-active @endif'
                                    href='{{ route('promotion.index') }}'>Promotions</a>
                            </li>
                            @endif
                            @if($canDemotions)
                            <li>
                                <a class='tp-link @if (request()->is('demotion') && !request()->is('demotion/create')) menuitem-active @endif'
                                    href='{{ route('demotion.index') }}'>Demotions</a>
                            </li>
                            @endif
                            @if($canIncrements)
                            <li>
                                <a class='tp-link @if (request()->is('increment') && !request()->is('increment/create')) menuitem-active @endif'
                                    href='{{ route('increment.index') }}'>Increments</a>
                            </li>
                            @endif
                            @if($canDecrements)
                            <li>
                                <a class='tp-link @if (request()->is('decrement') && !request()->is('decrement/create')) menuitem-active @endif'
                                    href='{{ route('decrement.index') }}'>Decrements</a>
                            </li>
                            @endif
                            @if($canBonuses)
                            <li>
                                <a class='tp-link @if (request()->is('bonus') && !request()->is('bonus/create')) menuitem-active @endif'
                                   href='{{ route('bonus.index') }}'>Bonus & Reward</a>
                            </li>
                            @endif
                            @if(auth()->user()->can('penalty-management.view'))
                            <li>
                                <a class='tp-link @if (Route::is('payroll.penalty.*')) menuitem-active @endif'
                                   href='{{ route('payroll.penalty.index') }}'>Penalty Management</a>
                            </li>
                            @endif
                            @if($canAdvanceSalary)
                            <li>
                                <a class='tp-link @if (request()->is('advance-salary*')) menuitem-active @endif'
                                   href='{{ route('advance-salary.index') }}'>Advance Salary</a>
                            </li>
                            @endif
                            @if($canArrear)
                            <li>
                                <a class='tp-link @if (request()->is('arrear*')) menuitem-active @endif'
                                   href='{{ route('arrear.index') }}'>Arrear Management</a>
                            </li>
                            @endif
                            @if($canSalary)
                            <li>
                                <a class='tp-link @if (request()->is('salary') && !request()->is('salary/create')) menuitem-active @endif'
                                   href='{{ route('salary.index') }}'>Salary</a>
                            </li>
                            @endif
                            @if($canBills)
                            <li>
                                <a class='tp-link @if (request()->is('bills*')) menuitem-active @endif'
                                   href='{{ route('bills.index') }}'>Bill Pay</a>
                            </li>
                            @endif
                            @if(auth()->user()->can('disbursement.view'))
                            <li>
                                <a class='tp-link @if (request()->is('disbursement*')) menuitem-active @endif'
                                   href='{{ route('disbursement.index') }}'>Disbursement</a>
                            </li>
                            @endif
                        </ul>
                    </div>
                </li>
                @endif

                {{-- Administration Section --}}
                @if($showAdministrationTitle)
                <li class="menu-title">Administration</li>
                @endif

                @if($showCompanyMenu)
                <li>
                    <a href="#sidebarCompany" data-bs-toggle="collapse"
                        aria-expanded="{{ $companyOpen ? 'true' : 'false' }}"
                        class="@if ($companyOpen) menuitem-active @endif">
                        <i data-feather="briefcase"></i>
                        <span> Setup </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse @if ($companyOpen) show @endif" id="sidebarCompany">
                        <ul class="nav-second-level">
                            @if($canGroups)
                            <li>
                                <a class='tp-link @if (Route::is('groups.*')) menuitem-active @endif'
                                    href='{{ route('groups.index') }}'>Groups</a>
                            </li>
                            @endif
                            @if($canCompanyTypes)
                            <li>
                                <a class='tp-link @if (Route::is('company_types.*')) menuitem-active @endif'
                                    href='{{ route('company_types.index') }}'>Company Types</a>
                            </li>
                            @endif
                            @if($canCompanies)
                            <li>
                                <a class='tp-link @if (Route::is('companies.*')) menuitem-active @endif'
                                    href='{{ route('companies.index') }}'>Companies</a>
                            </li>
                            @endif
                            @if($canCompanyBranches)
                            <li>
                                <a class='tp-link @if (Route::is('company_locations.*')) menuitem-active @endif'
                                    href='{{ route('company_locations.index') }}'>Branches</a>
                            </li>
                            @endif
                            @if($canDivisions)
                            <li>
                                <a class='tp-link @if (Route::is('divisions.*')) menuitem-active @endif'
                                    href='{{ route('divisions.index') }}'>Divisions</a>
                            </li>
                            @endif
                            @if($canDepartments)
                            <li>
                                <a class='tp-link @if (Route::is('departments.*')) menuitem-active @endif'
                                    href='{{ route('departments.index') }}'>Departments</a>
                            </li>
                            @endif
                            @if($canSections)
                            <li>
                                <a class='tp-link @if (Route::is('sections.*')) menuitem-active @endif'
                                    href='{{ route('sections.index') }}'>Sections</a>
                            </li>
                            @endif
                            @if($canDesignations)
                            <li>
                                <a class='tp-link @if (Route::is('designations.*')) menuitem-active @endif'
                                    href='{{ route('designations.index') }}'>Designations</a>
                            </li>
                            @endif
                            @if($canPayGroups)
                            <li>
                                <a class='tp-link @if (Route::is('pay_groups.*')) menuitem-active @endif'
                                    href='{{ route('pay_groups.index') }}'>Pay Groups</a>
                            </li>
                            @endif
                            @if($canSalaryGrades)
                            <li>
                                <a class='tp-link @if (Route::is('salary_grades.*')) menuitem-active @endif'
                                    href='{{ route('salary_grades.index') }}'>Salary Grades</a>
                            </li>
                            @endif
                            @if($canExpenseTypes)
                            <li>
                                <a class='tp-link @if (Route::is('expense_types.*')) menuitem-active @endif'
                                    href='{{ route('expense_types.index') }}'>Expense Types</a>
                            </li>
                            @endif
                            @if($canPayScales)
                            <li>
                                <a class='tp-link @if (Route::is('pay_scales.*')) menuitem-active @endif'
                                    href='{{ route('pay_scales.index') }}'>Pay Scales</a>
                            </li>
                            @endif
                            @if($canMovementTypes)
                            <li>
                                <a class='tp-link @if (Route::is('movement_types.*')) menuitem-active @endif'
                                    href='{{ route('movement_types.index') }}'>Movement Types</a>
                            </li>
                            @endif
                            @if($canBanks)
                            <li>
                                <a class='tp-link @if (Route::is('banks.*')) menuitem-active @endif'
                                    href='{{ route('banks.index') }}'>Banks</a>
                            </li>
                            @endif
                            @if($canBankBranches)
                            <li>
                                <a class='tp-link @if (Route::is('branches.*')) menuitem-active @endif'
                                    href='{{ route('branches.index') }}'>Bank Branches</a>
                            </li>
                            @endif
                            @if($canBankAccounts)
                            <li>
                                <a class='tp-link @if (Route::is('bank_accounts.*')) menuitem-active @endif'
                                    href='{{ route('bank_accounts.index') }}'>Bank Accounts</a>
                            </li>
                            @endif
                            @if($canHolidays)
                            <li>
                                <a class='tp-link @if (Route::is('holidays.*')) menuitem-active @endif'
                                    href='{{ route('holidays.index') }}'>Holidays</a>
                            </li>
                            @endif
                            @if($canJobCreations)
                            <li>
                                <a class='tp-link @if (Route::is('job_creations.*')) menuitem-active @endif'
                                    href='{{ route('job_creations.index') }}'>Job Creations</a>
                            </li>
                            @endif
                            @if(false) {{-- Hidden for now --}}
                            <li>
                                <a class='tp-link @if (Route::is('company.bulk_upload')) menuitem-active @endif'
                                    href='{{ route('company.bulk_upload') }}'>Bulk Upload</a>
                            </li>
                            @endif
                        </ul>
                    </div>
                </li>
                @endif

                @if($showStructureMenu)
                <li>
                    <a href="#organization-structure-menu" data-bs-toggle="collapse"
                        aria-expanded="{{ Route::is('organization-structure.*') ? 'true' : 'false' }}"
                        class="@if (Route::is('organization-structure.*')) menuitem-active @endif">
                        <i data-feather="git-branch"></i>
                        <span> Structure </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse @if (Route::is('organization-structure.*')) show @endif"
                        id="organization-structure-menu">
                        <ul class="nav-second-level">
                            @if($canStructuralView)
                            <li>
                                <a class='tp-link @if (Route::is('organization-structure.view')) menuitem-active @endif'
                                    href='{{ route('organization-structure.view') }}'>Structural View</a>
                            </li>
                            @endif
                            @if($canMembers)
                            <li>
                                <a class='tp-link @if (Route::is('organization-structure.index') ||
                                        Route::is('organization-structure.create') ||
                                        Route::is('organization-structure.edit')) menuitem-active @endif'
                                    href='{{ route('organization-structure.index') }}'>Members</a>
                            </li>
                            @endif
                        </ul>
                    </div>
                </li>
                @endif

                @if($showSettingsMenu)
                <li>
                    <a href="#settings" data-bs-toggle="collapse"
                        aria-expanded="{{ $settingsOpen ? 'true' : 'false' }}"
                        class="@if ($settingsOpen) menuitem-active @endif">
                        <i data-feather="settings"></i>
                        <span> Settings </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse @if ($settingsOpen) show @endif" id="settings">
                        <ul class="nav-second-level">
                            @if($canGeneralSettings)
                            <li>
                                <a class='tp-link @if (Route::is('setting.general_settings.*')) menuitem-active @endif'
                                    href='{{ route('setting.general_settings') }}'>General</a>
                            </li>
                            <li>
                                <a class='tp-link @if (Route::is('setting.profile_field_config*')) menuitem-active @endif'
                                    href='{{ route('setting.profile_field_config') }}'>Profile Fields</a>
                            </li>
                            @endif
                            @if(auth()->user()->can('general-settings.view'))
                            <li>
                                <a class='tp-link @if (Route::is('setting.notification_settings.*')) menuitem-active @endif'
                                   href='{{ route('setting.notification_settings.index') }}'>Notification Alerts</a>
                            </li>
                            @endif
                            @if($canAuditLogs)
                            <li>
                                <a class='tp-link @if (Route::is('audit_logs.*')) menuitem-active @endif'
                                   href='{{ route('audit_logs.index') }}'>Audit Logs</a>
                            </li>
                            @endif
                            @if($canRoleManagement)
                            <li>
                                <a class='tp-link @if (Route::is('setting.roles.*')) menuitem-active @endif'
                                   href='{{ route('setting.roles.index') }}'>Role Management</a>
                            </li>
                            @endif
                            @if($canApprovalWorkflows)
                            <li>
                                <a class='tp-link @if (Route::is('setting.approval_workflows.*')) menuitem-active @endif'
                                   href='{{ route('setting.approval_workflows.index') }}'>Approval Workflows</a>
                            </li>
                            @endif
                            @if(auth()->user()->can('general-settings.view'))
                            <li>
                                <a class='tp-link @if (Route::is('setting.transfer.index')) menuitem-active @endif'
                                   href='{{ route('setting.transfer.index') }}'>Transfer Settings</a>
                            </li>
                            @endif
                            @if($canIDCardDesign)
                            <li>
                                <a class='tp-link @if (Route::is('setting.id_design.*')) menuitem-active @endif'
                                    href='{{ route('setting.id_design.index') }}'>ID Card Design</a>
                            </li>
                            @endif
                            @if($canAPIKeys)
                             <li>
                                 <a class='tp-link @if (Route::is('setting.google_map_api')) menuitem-active @endif'
                                     href='{{ route('setting.google_map_api') }}'>Google Map API</a>
                             </li>
                             @endif
                            @if($canSMTP)
                            <li>
                                <a class='tp-link @if (Route::is('setting.mail_settings.*')) menuitem-active @endif'
                                    href='{{ route('setting.mail_settings') }}'>SMTP</a>
                            </li>
                            @endif
                            @if($canDBBackup)
                            <li>
                                <a class='tp-link @if (Route::is('db_backup')) menuitem-active @endif'
                                   href='{{ route('db_backup') }}'>DB Backup</a>
                            </li>
                            @endif
                        </ul>
                    </div>
                </li>
                @endif

            </ul>

        </div>
        <!-- End Sidebar -->

        <div class="clearfix"></div>

    </div>
</div>
<!-- Left Sidebar End -->
