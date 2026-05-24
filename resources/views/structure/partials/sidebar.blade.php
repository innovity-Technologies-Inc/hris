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
                            src="{{ isset($generalSettings->logo_dark) ? asset('storage/' . $generalSettings->logo_dark) : asset('assets/images/logo-light.png') }}"
                            alt="" height="24">
                        <img class="logo-img-dark"
                            src="{{ isset($generalSettings->logo_light) ? asset('storage/' . $generalSettings->logo_light) : asset('assets/images/logo-dark.png') }}"
                            alt="" height="24">
                    </span>
                </a>
            </div>

            <ul id="side-menu">
                <li class="menu-title">Menu</li>

                @if(auth()->user()->can('dashboard.view'))
                <li>
                    <a href="{{ route('dashboard.index') }}" class="@if (Route::is('dashboard.index')) menuitem-active @endif">
                        <i data-feather="home"></i>
                        <span> Dashboard </span>
                    </a>
                </li>
                @endif

                @php
                    $isEmployeeUser = auth()->user()->user_type === 'Employee';
                @endphp
                @if($isEmployeeUser || auth()->user()->can('employee-management.view'))
                <li>
                    <a href="{{ route('employee.dashboard') }}" class="@if (Route::is('employee.dashboard*')) menuitem-active @endif">
                        <i data-feather="activity"></i>
                        <span> Employee Dashboard </span>
                    </a>
                </li>
                @endif

                <!-- Employees Menu -->
                @php
                    $isEmployeeType = auth()->user()->user_type === 'Employee';
                    $canViewEmployeeInfo = auth()->user()->can('employee-management.view');
                    $canReviewProfile = auth()->user()->can('employee-management.profile-review');
                    $canSearchEmployee = auth()->user()->can('employee-management.view') && !$isEmployeeType;
                    $canBulkUploadEmployee = auth()->user()->can('employee-management.import');
                    $showEmployeesMenu = $canViewEmployeeInfo || $canReviewProfile || $canSearchEmployee || $canBulkUploadEmployee;
                @endphp
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
                            aria-expanded="{{ Route::is('employee.*') || Route::is('employee.employee') ? 'true' : 'false' }}"
                            class="@if (Route::is('employee.*') || Route::is('employee.employee')) menuitem-active @endif">
                            <i data-feather="users"></i>
                            <span> Employees </span>
                            <span class="menu-arrow"></span>
                        </a>
                        <div class="collapse @if (Route::is('employee.*') || Route::is('employee.employee')) show @endif" id="sidebarEmployees">
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
                            </ul>
                        </div>
                    @endif
                </li>
                @endif

                <!-- Attendance Menu -->
                @php
                    $canClockInOut = auth()->user()->can('attendance.clock-in-out');
                    $canCreateAttendance = auth()->user()->can('attendance.create');
                    $canBulkUploadAttendance = auth()->user()->can('attendance.import');
                    $canRecords = auth()->user()->can('attendance.view');
                    $showAttendanceMenu = $canClockInOut || $canCreateAttendance || $canBulkUploadAttendance || $canRecords;
                    $attendanceOpen = Route::is('attendance.*');
                @endphp
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


                <!-- Leaves Menu -->
                @php
                    $canLeaveApplication = auth()->user()->can('leaves.create');
                    $canLeaveLogs = auth()->user()->can('leaves.view');
                    $showLeavesMenu = $canLeaveApplication || $canLeaveLogs;
                    $leavesOpen = Route::is('leave.*');
                @endphp
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

                <!-- Movement Menu -->
                @php
                    $canMovementApplication = auth()->user()->can('movement.create');
                    $canMovementLogs = auth()->user()->can('movement.view');
                    $showMovementMenu = $canMovementApplication || $canMovementLogs;
                    $movementOpen = Route::is('movement.*');
                @endphp
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

                <!-- Transfer Menu -->
                @php
                    $canTransferApplication = auth()->user()->can('transfers.create');
                    $canTransferLogs = auth()->user()->can('transfers.view');
                    $showTransferMenu = $canTransferApplication || $canTransferLogs;
                    $transferOpen = Route::is('transfer.*');
                @endphp
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

                <!-- Payroll Menu -->
                @php
                    $canPromotions = auth()->user()->can('promotions.view');
                    $canIncrements = auth()->user()->can('increments.view');
                    $canBonuses = auth()->user()->can('bonuses.view');
                    $canSalary = auth()->user()->can('salary.view');
                    $showPayrollMenu = $canPromotions || $canIncrements || $canBonuses || $canSalary;
                    $payrollOpen = request()->is('promotion*') || request()->is('increment*') || request()->is('bonus*')
                    || request()->is('salary*');
                @endphp
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
                            @if($canIncrements)
                            <li>
                                <a class='tp-link @if (request()->is('increment') && !request()->is('increment/create')) menuitem-active @endif'
                                    href='{{ route('increment.index') }}'>Increments</a>
                            </li>
                            @endif
                            @if($canBonuses)
                            <li>
                                <a class='tp-link @if (request()->is('bonus') && !request()->is('bonus/create')) menuitem-active @endif'
                                   href='{{ route('bonus.index') }}'>Bonuses</a>
                            </li>
                            @endif
                            @if($canSalary)
                            <li>
                                <a class='tp-link @if (request()->is('salary') && !request()->is('salary/create')) menuitem-active @endif'
                                   href='{{ route('salary.index') }}'>Salary</a>
                            </li>
                            @endif
                        </ul>
                    </div>
                </li>
                @endif


                <!-- Plans Menu -->
                @php
                    $canMealPlans = auth()->user()->can('meal-plans.view');
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
                @endphp
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
                                    href='{{ route('plan.meal_plans.index') }}'>Meal Plans</a>
                            </li>
                            @endif
                            @if($canShiftPlans)
                            <li>
                                <a class='tp-link @if (Route::is('plan.shift_plans.*')) menuitem-active @endif'
                                    href='{{ route('plan.shift_plans.index') }}'>Shift Plans</a>
                            </li>
                            @endif
                            @if($canLeavePlans)
                            <li>
                                <a class='tp-link @if (Route::is('plan.leave_plans.*')) menuitem-active @endif'
                                    href='{{ route('plan.leave_plans.index') }}'>Leave Plans</a>
                            </li>
                            @endif
                            @if($canOTPlans)
                            <li>
                                <a class='tp-link @if (Route::is('plan.ot_plans.*')) menuitem-active @endif'
                                    href='{{ route('plan.ot_plans.index') }}'>OT Plans</a>
                            </li>
                            @endif
                            @if($canRosterPlans)
                            <li>
                                <a class='tp-link @if (Route::is('plan.roster_plans.*')) menuitem-active @endif'
                                    href='{{ route('plan.roster_plans.index') }}'>Roster Plans</a>
                            </li>
                            @endif
                            @if($canOffDayPlans)
                            <li>
                                <a class='tp-link @if (Route::is('plan.off_day_plans.*')) menuitem-active @endif'
                                    href='{{ route('plan.off_day_plans.index') }}'>Off-Day Work Plans</a>
                            </li>
                            @endif
                            @if($canBonusPlans)
                            <li>
                                <a class='tp-link @if (Route::is('plan.bonus_plans.*')) menuitem-active @endif'
                                    href='{{ route('plan.bonus_plans.index') }}'>Bonus Plans</a>
                            </li>
                            @endif
                            @if($canAllowancePlans)
                            <li>
                                <a class='tp-link @if (Route::is('plan.allowance_plans.*')) menuitem-active @endif'
                                    href='{{ route('plan.allowance_plans.index') }}'>Allowance Plans</a>
                            </li>
                            @endif
                            @if($canTAPlans)
                            <li>
                                <a class='tp-link @if (Route::is('plan.ta_plans.*')) menuitem-active @endif'
                                    href='{{ route('plan.ta_plans.index') }}'>TA Plans</a>
                            </li>
                            @endif
                            @if($canDAPlans)
                            <li>
                                <a class='tp-link @if (Route::is('plan.da_plans.*')) menuitem-active @endif'
                                    href='{{ route('plan.da_plans.index') }}'>DA Plans</a>
                            </li>
                            @endif
                            @if($canDeductionPlans)
                            <li>
                                <a class='tp-link @if (Route::is('plan.deduction_plans.*')) menuitem-active @endif'
                                    href='{{ route('plan.deduction_plans.index') }}'>Deduction Plan</a>
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


                <!-- Company Info Menu -->
                @php
                    $canGroups = auth()->user()->can('groups.view');
                    $canCompanyTypes = auth()->user()->can('company-types.view');
                    $canCompanies = auth()->user()->can('companies.view');
                    $canCompanyBranches = auth()->user()->can('company-branches.view') && (isset($generalSettings->branch_status) && $generalSettings->branch_status == 1);
                    $canDivisions = auth()->user()->can('divisions.view') && (isset($generalSettings->division_status) && $generalSettings->division_status == 1);
                    $canDepartments = auth()->user()->can('departments.view') && (isset($generalSettings->department_status) && $generalSettings->department_status == 1);
                    $canSections = auth()->user()->can('sections.view') && (isset($generalSettings->section_status) && $generalSettings->section_status == 1);
                    $canDesignations = auth()->user()->can('designations.view');
                    $canSalaryActs = auth()->user()->can('salary-acts.view');
                    $canSalaryGrades = auth()->user()->can('salary-grades.view');
                    $canBanks = auth()->user()->can('banks.view');
                    $canBankBranches = auth()->user()->can('bank-branches.view');
                    $canBankAccounts = auth()->user()->can('bank-accounts.view');
                    $canHolidays = auth()->user()->can('holidays.view');
                    $canJobCreations = auth()->user()->can('job-creations.view');
                    $canBulkUploadCompany = auth()->user()->can('employee-management.import');

                    $showCompanyMenu = $canGroups || $canCompanyTypes || $canCompanies || $canCompanyBranches || $canDivisions || $canDepartments || $canSections || $canDesignations || $canSalaryActs || $canSalaryGrades || $canBanks || $canBankBranches || $canBankAccounts || $canHolidays || $canJobCreations || $canBulkUploadCompany;


                    $companyOpen =
                        Route::is('groups.*') ||
                        Route::is('companies.*') ||
                        Route::is('company_types.*') ||
                        Route::is('company_locations.*') ||
                        Route::is('banks.*') ||
                        Route::is('branches.*') ||
                        Route::is('tofsils.*') ||
                        Route::is('salary_grades.*') ||
                        Route::is('gazette_locations.*') ||
                        Route::is('company.bulk_upload');
                @endphp
                @if($showCompanyMenu)
                <li>
                    <a href="#sidebarCompany" data-bs-toggle="collapse"
                        aria-expanded="{{ $companyOpen ? 'true' : 'false' }}"
                        class="@if ($companyOpen) menuitem-active @endif ">
                        <i data-feather="box"></i>
                        <span> Company Info </span>
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
                            @if ($canCompanyBranches)
                                <li>
                                    <a class='tp-link @if (Route::is('company_locations.*')) menuitem-active @endif'
                                        href='{{ route('company_locations.index') }}'>Company Branches</a>
                                </li>
                            @endif
                            @if ($canDivisions)
                                <li>
                                    <a class='tp-link @if (Route::is('divisions.*')) menuitem-active @endif'
                                        href='{{ route('divisions.index') }}'>Divisions</a>
                                </li>
                            @endif
                            @if ($canDepartments)
                                <li>
                                    <a class='tp-link @if (Route::is('departments.*')) menuitem-active @endif'
                                        href='{{ route('departments.index') }}'>Departments</a>
                                </li>
                            @endif
                            @if ($canSections)
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

                            @if($canSalaryActs)
                            <li>
                                <a class='tp-link @if (Route::is('tofsils.*')) menuitem-active @endif'
                                    href='{{ route('tofsils.index') }}'>Salary Acts</a>
                            </li>
                            @endif

                            @if($canSalaryGrades)
                            <li>
                                <a class='tp-link @if (Route::is('salary_grades.*')) menuitem-active @endif'
                                    href='{{ route('salary_grades.index') }}'>Salary Grades</a>
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

                            @if($canBulkUploadCompany)
                            <li>
                                <a class='tp-link @if (Route::is('company.bulk_upload')) menuitem-active @endif'
                                    href='{{ route('company.bulk_upload') }}'>Bulk Upload</a>
                            </li>
                            @endif

                            {{-- <li>
                                <a class='tp-link @if (Route::is('gazette_locations.*')) menuitem-active @endif'
                                    href='{{ route('gazette_locations.index') }}'>Gazette Locations</a>
                            </li> --}}

                        </ul>
                    </div>
                </li>
                @endif

                <!-- Organization Structure Menu -->
                @php
                    $canStructuralView = auth()->user()->can('structural-view.view');
                    $canMembers = auth()->user()->can('members.view');
                    $showStructureMenu = $canStructuralView || $canMembers;
                @endphp
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

                <!-- Transport Menu -->
                @php
                    $canVehicles = auth()->user()->can('vehicles.view');
                    $canAssignDriver = auth()->user()->can('assign-driver.view');
                    $canVehicleRequisition = auth()->user()->can('vehicle-requisition.view');
                    $canEmployeeTransport = auth()->user()->can('employee-transport.view');
                    $canVehicleAllocation = auth()->user()->can('vehicle-allocation.view');
                    $showTransportMenu = $canVehicles || $canAssignDriver || $canVehicleRequisition || $canEmployeeTransport || $canVehicleAllocation;
                    $transportOpen = Route::is('transport.*');
                @endphp
                @if($showTransportMenu)
                <li>
                    <a href="#sidebarTransport" data-bs-toggle="collapse"
                        aria-expanded="{{ $transportOpen ? 'true' : 'false' }}"
                        class="@if ($transportOpen) menuitem-active @endif">
                        <i data-feather="truck"></i>
                        <span> Transport </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse @if ($transportOpen) show @endif" id="sidebarTransport">
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
                            @if($canVehicleRequisition)
                            <li>
                                <a class='tp-link @if (Route::is('transport.vehicle_requisitions.*')) menuitem-active @endif'
                                    href='{{ route('transport.vehicle_requisitions.index') }}'>Vehicle Requisition</a>
                            </li>
                            @endif
                            <!-- Employee Transport Submenu -->
                            @if($canEmployeeTransport)
                            <li>
                                <a class='tp-link @if (Route::is('transport.employee_transports.*')) menuitem-active @endif'
                                    href='{{ route('transport.employee_transports.index') }}'>Employee Transport</a>
                            </li>
                            @endif
                            <!-- Vehicle Allocation Submenu -->
                            @if($canVehicleAllocation)
                            <li>
                                <a class='tp-link @if (Route::is('transport.vehicle_allocations.*')) menuitem-active @endif'
                                    href='{{ route('transport.vehicle_allocations.dashboard') }}'>Vehicle
                                    Allocation</a>
                            </li>
                            @endif
                        </ul>
                    </div>
                </li>
                @endif

                <!-- Settings Menu -->
                @php
                    $canGeneralSettings = auth()->user()->can('general-settings.view');
                    $canIDCardDesign = auth()->user()->can('id-card-design.view');
                    $canAPIKeys = auth()->user()->can('api-keys.view');
                    $canSMTP = auth()->user()->can('smtp.view');
                    $canDBBackup = auth()->user()->can('db-backup.download');
                    $canRoleManagement = auth()->user()->can('role-management.view');
                    $showSettingsMenu = $canGeneralSettings || $canIDCardDesign || $canAPIKeys || $canSMTP || $canDBBackup || $canRoleManagement;
                @endphp
                @if($showSettingsMenu)
                <li>
                    <a href="#settings" data-bs-toggle="collapse"
                        aria-expanded="{{ Route::is('setting.*') ? 'true' : 'false' }}"
                        class="@if (Route::is('setting.*')) menuitem-active @endif">
                        <i data-feather="settings"></i>
                        <span> Settings </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse @if (Route::is('setting.*')) show @endif" id="settings">
                        <ul class="nav-second-level">
                            @if($canGeneralSettings)
                            <li>
                                <a class='tp-link @if (Route::is('setting.general_settings.*')) menuitem-active @endif'
                                    href='{{ route('setting.general_settings') }}'>General</a>
                            </li>
                            @endif
                            @if($canRoleManagement)
                            <li>
                                <a class='tp-link @if (Route::is('setting.roles.*')) menuitem-active @endif'
                                   href='{{ route('setting.roles.index') }}'>Role Management</a>
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
                                <a class='tp-link @if (Route::is('setting.api_keys')) menuitem-active @endif'
                                    href='{{ route('setting.api_keys') }}'>API Keys</a>
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

