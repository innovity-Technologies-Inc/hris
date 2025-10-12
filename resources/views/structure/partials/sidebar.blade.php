<!-- Left Sidebar Start -->
<div class="app-sidebar-menu">
    <div class="h-100" data-simplebar="">

        <!--- Sidemenu -->
        <div id="sidebar-menu">

            <div class="logo-box">
                <a class='logo logo-light' href='{{route('dashboard')}}'>
                                <span class="logo-sm">
                                    <img src="{{asset('assets/images/logo-sm.png')}}" alt="" height="22">
                                </span>
                    <span class="logo-lg">
                                    <img src="{{asset('assets/images/logo-light.png')}}" alt="" height="24">
                                </span>
                </a>
                <a class='logo logo-dark' href='{{route('dashboard')}}'>
                                <span class="logo-sm">
                                    <img src="{{asset('assets/images/logo-sm.png')}}" alt="" height="22">
                                </span>
                    <span class="logo-lg">
                                    <img src="{{asset('assets/images/logo-dark.png')}}" alt="" height="24">
                                </span>
                </a>
            </div>

            <ul id="side-menu">

                <li class="menu-title">Menu</li>

                <li>
                    <a href="{{route('dashboard')}}"
                       class="@if(Route::currentRouteName() == 'dashboard') menuitem-active @endif ">
                        <i data-feather="home"></i>
                        <span> Dashboard </span>
                    </a>
                </li>

                <li>
                    <a href="#sidebarCompany" data-bs-toggle="collapse"
                       class="@if(Route::currentRouteName() == 'groups.index' ) menuitem-active @endif ">
                        <i data-feather="box"></i>
                        <span> Company Info </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sidebarCompany">
                        <ul class="nav-second-level">
                            <li>
                                <a class='tp-link @if(Route::currentRouteName() == 'groups.index' ||
Route::currentRouteName() == 'company_types.index') menuitem-active @endif'
                                   href='{{route('groups.index')}}'>Groups</a>
                            </li>
                            <li>
                                <a class='tp-link' href='{{route('company_types.index')}}'>Company Types</a>
                            </li>

                            <li>
                                <a class='tp-link' href='{{route('companies.index')}}'>Companies</a>
                            </li>
                            {{-- Add company locations link here --}}
                            <li>
                                <a class='tp-link' href='{{route('company_locations.index')}}'>Company Locations</a>
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
