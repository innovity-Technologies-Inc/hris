@php
    $generalSettings = \App\HelperClass::getGeneralSetting();
@endphp
<!-- Left Sidebar Start -->
<div class="app-sidebar-menu">
    <div class="h-100" data-simplebar="">

        <!--- Sidemenu -->
        <div id="sidebar-menu">

            <div class="logo-box">
                <a class='logo' href='{{ route('dashboard') }}'>
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

                <li>
                    <a href="{{ route('dashboard') }}" class="@if (Route::is('dashboard')) menuitem-active @endif">
                        <i data-feather="home"></i>
                        <span> Dashboard </span>
                    </a>
                </li>
                <!-- Add Employees Information Menu -->
                <li>
                    <a href="#sidebarEmployees" data-bs-toggle="collapse"
                        aria-expanded="{{ Route::is('employees.*') || Route::is('search.employee') ? 'true' : 'false' }}"
                        class="@if (Route::is('employees.*') || Route::is('search.employee')) menuitem-active @endif">
                        <i data-feather="users"></i>
                        <span> Employees </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse @if (Route::is('employees.*') || Route::is('search.employee')) show @endif" id="sidebarEmployees">
                        <ul class="nav-second-level">
                            <li>
                                <a class='tp-link @if (Route::is('employees.index')) menuitem-active @endif'
                                    href='{{ route('employees.index') }}'>Employee Information</a>
                            </li>
                            <li>
                                <a class='tp-link @if (Route::is('search.employee')) menuitem-active @endif'
                                    href='{{ route('search.employee') }}'>Search Employee</a>
                            </li>
                            <li>
                                <a class='tp-link @if (Route::is('employees.import')) menuitem-active @endif'
                                    href='{{ route('employees.import') }}'>Bulk Upload</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Attendance Menu -->
                @php
                    $attendanceOpen = Route::is('attendance.*');
                @endphp
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
                            <li>
                                <a class='tp-link @if (Route::is('attendance.clock_in_out')) menuitem-active @endif'
                                    href='{{ route('attendance.clock_in_out') }}'>Clock In / Out</a>
                            </li>

                            <li>
                                <a class='tp-link @if (Route::is('attendance.create')) menuitem-active @endif'
                                    href='{{ route('attendance.create') }}'>Create</a>
                            </li>
                            <li>
                                <a class='tp-link @if (Route::is('attendance.bulk-upload')) menuitem-active @endif'
                                    href='{{ route('attendance.bulk-upload') }}'>Bulk Upload</a>
                            </li>
                            <li>
                                <a class='tp-link @if (Route::is('attendance.index')) menuitem-active @endif'
                                    href='{{ route('attendance.index') }}'>Records</a>
                            </li>
                        </ul>
                    </div>
                </li>


                @php
                    $leavesOpen = request()->is('leaves*');
                @endphp
                <li>
                    <a href="#leaves" data-bs-toggle="collapse" aria-expanded="{{ $leavesOpen ? 'true' : 'false' }}"
                        class="@if ($leavesOpen) menuitem-active @endif">
                        <i data-feather="calendar"></i>
                        <span> Leaves </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse @if ($leavesOpen) show @endif" id="leaves">
                        <ul class="nav-second-level">
                            <li>
                                <a class='tp-link @if (request()->is('leaves.create')) menuitem-active @endif'
                                    href='{{ route('leaves.create') }}'>Application</a>
                            </li>
                            <li>
                                <a class='tp-link @if (request()->is('leaves.index')) menuitem-active @endif'
                                    href='{{ route('leaves.index') }}'>Logs</a>
                            </li>

                        </ul>
                    </div>
                </li>

                @php
                    $movementOpen = request()->is('movement*');
                @endphp
                <li>
                    <a href="#movement" data-bs-toggle="collapse"
                        aria-expanded="{{ $movementOpen ? 'true' : 'false' }}"
                        class="@if ($movementOpen) menuitem-active @endif">
                        <i data-feather="move"></i>
                        <span> Movement </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse @if ($movementOpen) show @endif" id="movement">
                        <ul class="nav-second-level">
                            <li>
                                <a class='tp-link @if (request()->is('movement/create')) menuitem-active @endif'
                                    href='{{ route('movement.create') }}'>Application</a>
                            </li>
                            <li>
                                <a class='tp-link @if (request()->is('movement') && !request()->is('movement/create')) menuitem-active @endif'
                                    href='{{ route('movement.index') }}'>Logs</a>
                            </li>

                        </ul>
                    </div>
                </li>

                <!-- Payroll Menu -->
                @php
                    $payrollOpen = request()->is('promotion*') || request()->is('increment*') || request()->is('bonus*');
                @endphp
                <li>
                    <a href="#payroll" data-bs-toggle="collapse" aria-expanded="{{ $payrollOpen ? 'true' : 'false' }}"
                        class="@if ($payrollOpen) menuitem-active @endif">
                        <i data-feather="dollar-sign"></i>
                        <span> Payroll </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse @if ($payrollOpen) show @endif" id="payroll">
                        <ul class="nav-second-level">
                            <li>
                                <a class='tp-link @if (request()->is('promotion') && !request()->is('promotion/create')) menuitem-active @endif'
                                    href='{{ route('promotion.index') }}'>Promotions</a>
                            </li>
                            <li>
                                <a class='tp-link @if (request()->is('increment') && !request()->is('increment/create')) menuitem-active @endif'
                                    href='{{ route('increment.index') }}'>Increments</a>
                            </li>
                            <li>
                                <a class='tp-link @if (request()->is('bonus') && !request()->is('bonus/create')) menuitem-active @endif'
                                   href='{{ route('bonus.index') }}'>Bonuses</a>
                            </li>
                        </ul>
                    </div>
                </li>


                <!-- Add Plans -->
                <li>
                    <a href="#plans" data-bs-toggle="collapse"
                        aria-expanded="{{ Route::is('plans.*') ? 'true' : 'false' }}"
                        class="@if (Route::is('plans.*')) menuitem-active @endif">
                        <i data-feather="layers"></i>
                        <span> Plans </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse @if (Route::is('plans.*')) show @endif" id="plans">
                        <ul class="nav-second-level">
                            <li>
                                <a class='tp-link @if (Route::is('plans.meal_plans.*')) menuitem-active @endif'
                                    href='{{ route('plans.meal_plans.index') }}'>Meal Plans</a>
                            </li>
                            <li>
                                <a class='tp-link @if (Route::is('plans.shift_plans.*')) menuitem-active @endif'
                                    href='{{ route('plans.shift_plans.index') }}'>Shift Plans</a>
                            </li>
                            <li>
                                <a class='tp-link @if (Route::is('plans.leave_plans.*')) menuitem-active @endif'
                                    href='{{ route('plans.leave_plans.index') }}'>Leave Plans</a>
                            </li>
                            <li>
                                <a class='tp-link @if (Route::is('plans.ot_plans.*')) menuitem-active @endif'
                                    href='{{ route('plans.ot_plans.index') }}'>OT Plans</a>
                            </li>
                            <li>
                                <a class='tp-link @if (Route::is('plans.roster_plans.*')) menuitem-active @endif'
                                    href='{{ route('plans.roster_plans.index') }}'>Roster Plans</a>
                            </li>
                            <li>
                                <a class='tp-link @if (Route::is('plans.off_day_plans.*')) menuitem-active @endif'
                                    href='{{ route('plans.off_day_plans.index') }}'>Off-Day Work Plans</a>
                            </li>
                            <li>
                                <a class='tp-link @if (Route::is('plans.bonus_plans.*')) menuitem-active @endif'
                                    href='{{ route('plans.bonus_plans.index') }}'>Bonus Plans</a>
                            </li>
                            <li>
                                <a class='tp-link @if (Route::is('plans.allowance_plans.*')) menuitem-active @endif'
                                    href='{{ route('plans.allowance_plans.index') }}'>Allowance Plans</a>
                            </li>
                            <li>
                                <a class='tp-link @if (Route::is('plans.ta_plans.*')) menuitem-active @endif'
                                    href='{{ route('plans.ta_plans.index') }}'>TA Plans</a>
                            </li>
                            <li>
                                <a class='tp-link @if (Route::is('plans.da_plans.*')) menuitem-active @endif'
                                    href='{{ route('plans.da_plans.index') }}'>DA Plans</a>
                            </li>
                            <li>
                                <a class='tp-link @if (Route::is('plans.deduction_plans.*')) menuitem-active @endif'
                                    href='{{ route('plans.deduction_plans.index') }}'>Deduction Plan</a>
                            </li>
                            <li>
                                <a class='tp-link @if (Route::is('plans.bulk_upload')) menuitem-active @endif'
                                    href='{{ route('plans.bulk_upload') }}'>Bulk Upload</a>
                            </li>
                        </ul>
                    </div>
                </li>


                <!-- Company Info Menu -->
                @php
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
                        Route::is('company_setup.bulk_upload');
                @endphp
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
                            <li>
                                <a class='tp-link @if (Route::is('groups.*')) menuitem-active @endif'
                                    href='{{ route('groups.index') }}'>Groups</a>
                            </li>
                            <li>
                                <a class='tp-link @if (Route::is('company_types.*')) menuitem-active @endif'
                                    href='{{ route('company_types.index') }}'>Company Types</a>
                            </li>

                            <li>
                                <a class='tp-link @if (Route::is('companies.*')) menuitem-active @endif'
                                    href='{{ route('companies.index') }}'>Companies</a>
                            </li>
                            @if (isset($generalSettings->branch_status) && $generalSettings->branch_status == 1)
                                <li>
                                    <a class='tp-link @if (Route::is('company_locations.*')) menuitem-active @endif'
                                        href='{{ route('company_locations.index') }}'>Company Branches</a>
                                </li>
                            @endif
                            @if (isset($generalSettings->division_status) && $generalSettings->division_status == 1)
                                <li>
                                    <a class='tp-link @if (Route::is('divisions.*')) menuitem-active @endif'
                                        href='{{ route('divisions.index') }}'>Divisions</a>
                                </li>
                            @endif
                            @if (isset($generalSettings->department_status) && $generalSettings->department_status == 1)
                                <li>
                                    <a class='tp-link @if (Route::is('departments.*')) menuitem-active @endif'
                                        href='{{ route('departments.index') }}'>Departments</a>
                                </li>
                            @endif
                            @if (isset($generalSettings->section_status) && $generalSettings->section_status == 1)
                                <li>
                                    <a class='tp-link @if (Route::is('sections.*')) menuitem-active @endif'
                                        href='{{ route('sections.index') }}'>Sections</a>

                                </li>
                            @endif
                            <li>
                                <a class='tp-link @if (Route::is('designations.*')) menuitem-active @endif'
                                    href='{{ route('designations.index') }}'>Designations</a>
                            </li>

                            <li>
                                <a class='tp-link @if (Route::is('tofsils.*')) menuitem-active @endif'
                                    href='{{ route('tofsils.index') }}'>Salary Acts</a>
                            </li>

                            <li>
                                <a class='tp-link @if (Route::is('salary_grades.*')) menuitem-active @endif'
                                    href='{{ route('salary_grades.index') }}'>Salary Grades</a>
                            </li>

                            <li>
                                <a class='tp-link @if (Route::is('banks.*')) menuitem-active @endif'
                                    href='{{ route('banks.index') }}'>Banks</a>
                            </li>

                            <li>
                                <a class='tp-link @if (Route::is('branches.*')) menuitem-active @endif'
                                    href='{{ route('branches.index') }}'>Bank Branches</a>
                            </li>

                            <li>
                                <a class='tp-link @if (Route::is('bank_accounts.*')) menuitem-active @endif'
                                    href='{{ route('bank_accounts.index') }}'>Bank Accounts</a>
                            </li>

                            <li>
                                <a class='tp-link @if (Route::is('holidays.*')) menuitem-active @endif'
                                    href='{{ route('holidays.index') }}'>Holidays</a>
                            </li>

                            <li>
                                <a class='tp-link @if (Route::is('job_creations.*')) menuitem-active @endif'
                                    href='{{ route('job_creations.index') }}'>Job Creations</a>
                            </li>

                            <li>
                                <a class='tp-link @if (Route::is('company_setup.bulk_upload')) menuitem-active @endif'
                                    href='{{ route('company_setup.bulk_upload') }}'>Bulk Upload</a>
                            </li>

                            {{-- <li>
                                <a class='tp-link @if (Route::is('gazette_locations.*')) menuitem-active @endif'
                                    href='{{ route('gazette_locations.index') }}'>Gazette Locations</a>
                            </li> --}}

                        </ul>
                    </div>
                </li>

                <!-- Organization Structure Menu -->
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
                            <li>
                                <a class='tp-link @if (Route::is('organization-structure.view')) menuitem-active @endif'
                                    href='{{ route('organization-structure.view') }}'>Structural View</a>
                            </li>
                            <li>
                                <a class='tp-link @if (Route::is('organization-structure.index') ||
                                        Route::is('organization-structure.create') ||
                                        Route::is('organization-structure.edit')) menuitem-active @endif'
                                    href='{{ route('organization-structure.index') }}'>Members</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Transport Module -->
                @php
                    $transportOpen = Route::is('transport.*');
                @endphp
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
                            <li>
                                <a class='tp-link @if (Route::is('transport.vehicles.*')) menuitem-active @endif'
                                    href='{{ route('transport.vehicles.index') }}'>Vehicles</a>
                            </li>
                            <li>
                                <a class='tp-link @if (Route::is('transport.vehicle_drivers.*')) menuitem-active @endif'
                                    href='{{ route('transport.vehicle_drivers.index') }}'>Assign Driver</a>
                            </li>
                            <li>
                                <a class='tp-link @if (Route::is('transport.vehicle_requisitions.*')) menuitem-active @endif'
                                    href='{{ route('transport.vehicle_requisitions.index') }}'>Vehicle Requisition</a>
                            </li>
                            <!-- Employee Transport Submenu -->
                            <li>
                                <a class='tp-link @if (Route::is('transport.employee_transports.*')) menuitem-active @endif'
                                    href='{{ route('transport.employee_transports.index') }}'>Employee Transport</a>
                            </li>
                            <!-- Vehicle Allocation Submenu -->
                            <li>
                                <a class='tp-link @if (Route::is('transport.vehicle_allocations.*')) menuitem-active @endif'
                                    href='{{ route('transport.vehicle_allocations.dashboard') }}'>Vehicle
                                    Allocation</a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Add Plans -->
                <li>
                    <a href="#settings" data-bs-toggle="collapse"
                        aria-expanded="{{ Route::is('settings.*') ? 'true' : 'false' }}"
                        class="@if (Route::is('settings.*')) menuitem-active @endif">
                        <i data-feather="settings"></i>
                        <span> Settings </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse @if (Route::is('settings.*')) show @endif" id="settings">
                        <ul class="nav-second-level">
                            <li>
                                <a class='tp-link @if (Route::is('settings.general_settings.*')) menuitem-active @endif'
                                    href='{{ route('settings.general_settings') }}'>General</a>
                            </li>
                            <li>
                                <a class='tp-link @if (Route::is('settings.id_design.*')) menuitem-active @endif'
                                    href='{{ route('settings.id_design.index') }}'>ID Card Design</a>
                            </li>
                            <li>
                                <a class='tp-link @if (Route::is('settings.api_keys')) menuitem-active @endif'
                                    href='{{ route('settings.api_keys') }}'>API Keys</a>
                            </li>
                            <li>
                                <a class='tp-link @if (Route::is('settings.mail_settings.*')) menuitem-active @endif'
                                    href='{{ route('settings.mail_settings') }}'>SMTP</a>
                            </li>

                            <li>
                                <a class='tp-link @if (Route::is('db_backup')) menuitem-active @endif'
                                   href='{{ route('db_backup') }}'>DB Backup</a>
                            </li>

                        </ul>
                    </div>
                </li>

            </ul>

        </div>
        <!-- End Sidebar -->

        <div class="clearfix"></div>

    </div>
</div>
<!-- Left Sidebar End -->
