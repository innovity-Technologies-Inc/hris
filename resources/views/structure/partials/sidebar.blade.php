<!-- Left Sidebar Start -->
<div class="app-sidebar-menu">
    <div class="h-100" data-simplebar="">

        <!--- Sidemenu -->
        <div id="sidebar-menu">

            <div class="logo-box">
                <a class='logo logo-light' href='{{ route('dashboard') }}'>
                    <span class="logo-sm">
                        <img src="{{ asset('assets/images/logo-sm.png') }}" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('assets/images/logo-light.png') }}" alt="" height="24">
                    </span>
                </a>
                <a class='logo logo-dark' href='{{ route('dashboard') }}'>
                    <span class="logo-sm">
                        <img src="{{ asset('assets/images/logo-sm.png') }}" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('assets/images/logo-dark.png') }}" alt="" height="24">
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
                <!-- Company Info Menu -->
                <li>
                    <a href="#sidebarCompany" data-bs-toggle="collapse"
                        class="@if (Route::is('groups.*') ||
                                Route::is('companies.*') ||
                                Route::is('company_types.*') ||
                                Route::is('company_locations.*') ||
                                Route::is('banks.*') ||
                                Route::is('branches.*') ||
                                Route::is('tofsils.*') ||
                                Route::is('salary_grades.*') ||
                                Route::is('gazette_locations.*')) menuitem-active @endif ">
                        <i data-feather="box"></i>
                        <span> Company Info </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarCompany">
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

                            <li>
                                <a class='tp-link @if (Route::is('company_locations.*')) menuitem-active @endif'
                                    href='{{ route('company_locations.index') }}'>Company Locations</a>
                            </li>
                            <li>
                                <a class='tp-link @if (Route::is('divisions.*')) menuitem-active @endif'
                                    href='{{ route('divisions.index') }}'>Divisions</a>
                            </li>
                            <li>
                                <a class='tp-link @if (Route::is('departments.*')) menuitem-active @endif'
                                    href='{{ route('departments.index') }}'>Departments</a>
                            </li>
                            <li>
                                <a class='tp-link @if (Route::is('sections.*')) menuitem-active @endif'
                                    href='{{ route('sections.index') }}'>Sections</a>

                            </li>
                            <li>
                                <a class='tp-link @if (Route::is('designations.*')) menuitem-active @endif'
                                    href='{{ route('designations.index') }}'>Designations</a>
                            </li>

                            <li>
                                <a class='tp-link @if (Route::is('tofsils.*')) menuitem-active @endif'
                                    href='{{ route('tofsils.index') }}'>Tofsils</a>
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
                                    href='{{ route('branches.index') }}'>Branches</a>
                            </li>

                            <li>
                                <a class='tp-link @if (Route::is('bank_accounts.*')) menuitem-active @endif'
                                    href='{{ route('bank_accounts.index') }}'>Bank Accounts</a>
                            </li>

                            <li>
                                <a class='tp-link @if (Route::is('job_creations.*')) menuitem-active @endif'
                                    href='{{ route('job_creations.index') }}'>Job Creations</a>
                            </li>

                            <li>
                                <a class='tp-link @if (Route::is('gazette_locations.*')) menuitem-active @endif'
                                    href='{{ route('gazette_locations.index') }}'>Gazette Locations</a>
                            </li>
                            <li>
                                <a class='tp-link @if (Route::is('gazette_locations.*')) menuitem-active @endif'
                                    href='{{ route('gazette_locations.index') }}'>Gazette Locations</a>
                            </li>

                        </ul>
                    </div>
                </li>

                <!-- Add Employees Information Menu -->
                <li>
                    <a href="#sidebarEmployees" data-bs-toggle="collapse"
                        class="@if (Route::is('employees.*')) menuitem-active @endif">
                        <i data-feather="users"></i>
                        <span> Employees </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarEmployees">
                        <ul class="nav-second-level">
                            <li>
                                <a class='tp-link @if (Route::is('employees.*')) menuitem-active @endif'
                                    href='{{ route('employees.index') }}'>Employee Information</a>
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
