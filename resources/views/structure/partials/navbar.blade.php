<!-- Topbar Start -->
<div class="topbar-custom">
    <div class="container-fluid">
        <div class="d-flex justify-content-between">
            <ul class="list-unstyled topnav-menu mb-0 d-flex align-items-center">
                <li>
                    <button class="button-toggle-menu nav-link">
                        <i data-feather="menu" class="noti-icon"></i>
                    </button>
                </li>
                <li class="d-none d-lg-block">
                    <h5 class="mb-0">Good Morning, {{ Auth::user()?->name ?? 'Guest' }}</h5>
                </li>
            </ul>

            <ul class="list-unstyled topnav-menu mb-0 d-flex align-items-center">

                <!-- Button Trigger Customizer Offcanvas -->
                <li class="d-none d-sm-flex">
                    <button type="button" class="btn nav-link" data-toggle="fullscreen">
                        <i data-feather="maximize" class="align-middle fullscreen noti-icon"></i>
                    </button>
                </li>

                <!-- Light/Dark Mode Button Themes -->
                <li class="d-none d-sm-flex">
                    <button type="button" class="btn nav-link" id="light-dark-mode">
                        <i data-feather="moon" class="align-middle dark-mode"></i>
                        <i data-feather="sun" class="align-middle light-mode"></i>
                    </button>
                </li>

                <li class="dropdown notification-list topbar-dropdown">
                    <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                        <i data-feather="bell" class="noti-icon"></i>
                        <span class="badge bg-danger rounded-circle noti-icon-badge">9</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end dropdown-lg">
                        <!-- item-->
                        <div class="dropdown-item noti-title">
                            <h5 class="m-0">
                                <span class="float-end"><a href="" class="text-dark"><small>Clear All</small></a></span>Notification
                            </h5>
                        </div>

                        <div class="noti-scroll" data-simplebar="">
                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item notify-item text-muted link-primary active">
                                <div class="notify-icon">
                                    <img src="{{asset('')}}assets/images/users/user-12.jpg" class="img-fluid rounded-circle" alt="">
                                </div>
                                <div class="d-flex align-items-center justify-content-between">
                                    <p class="notify-details">Carl Steadham</p>
                                    <small class="text-muted">5 min ago</small>
                                </div>
                                <p class="mb-0 user-msg">
                                    <small class="fs-14">Completed <span class="text-reset">Improve workflow in Figma</span></small>
                                </p>
                            </a>

                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item notify-item text-muted link-primary">
                                <div class="notify-icon">
                                    <img src="{{asset('')}}assets/images/users/user-2.jpg" class="img-fluid rounded-circle" alt="">
                                </div>
                                <div class="notify-content">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <p class="notify-details">Olivia McGuire</p>
                                        <small class="text-muted">1 min ago</small>
                                    </div>

                                    <div class="d-flex mt-2 align-items-center">
                                        <div class="notify-sub-icon">
                                            <i class="mdi mdi-download-box text-dark"></i>
                                        </div>

                                        <div>
                                            <p class="notify-details mb-0">dark-themes.zip</p>
                                            <small class="text-muted">2.4 MB</small>
                                        </div>
                                    </div>
                                </div>
                            </a>

                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item notify-item text-muted link-primary">
                                <div class="notify-icon">
                                    <img src="{{asset('')}}assets/images/users/user-3.jpg" class="img-fluid rounded-circle" alt="">
                                </div>
                                <div class="notify-content">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <p class="notify-details">Travis Williams</p>
                                        <small class="text-muted">7 min ago</small>
                                    </div>
                                    <p class="noti-mentioned p-2 rounded-2 mb-0 mt-2">
                                        <span class="text-primary">@Patryk</span> Please make sure that you're....
                                    </p>
                                </div>
                            </a>

                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item notify-item text-muted link-primary">
                                <div class="notify-icon">
                                    <img src="{{asset('')}}assets/images/users/user-8.jpg" class="img-fluid rounded-circle" alt="">
                                </div>
                                <div class="d-flex align-items-center justify-content-between">
                                    <p class="notify-details">Violette Lasky</p>
                                    <small class="text-muted">5 min ago</small>
                                </div>
                                <p class="mb-0 user-msg">
                                    <small class="fs-14">Completed <span class="text-reset">Create new components</span></small>
                                </p>
                            </a>

                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item notify-item text-muted link-primary">
                                <div class="notify-icon">
                                    <img src="{{asset('')}}assets/images/users/user-5.jpg" class="img-fluid rounded-circle" alt="">
                                </div>
                                <div class="d-flex align-items-center justify-content-between">
                                    <p class="notify-details">Ralph Edwards</p>
                                    <small class="text-muted">5 min ago</small>
                                </div>
                                <p class="mb-0 user-msg">
                                    <small class="fs-14">Completed<span class="text-reset">Improve workflow in React</span></small>
                                </p>
                            </a>

                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item notify-item text-muted link-primary">
                                <div class="notify-icon">
                                    <img src="{{asset('')}}assets/images/users/user-6.jpg" class="img-fluid rounded-circle" alt="">
                                </div>
                                <div class="notify-content">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <p class="notify-details">Jocab jones</p>
                                        <small class="text-muted">7 min ago</small>
                                    </div>
                                    <p class="noti-mentioned p-2 rounded-2 mb-0 mt-2">
                                        <span class="text-reset">@Patryk</span> Please make sure that you're....
                                    </p>
                                </div>
                            </a>
                        </div>

                        <!-- All-->
                        <a href="javascript:void(0);" class="dropdown-item text-center text-primary notify-item notify-all">View all
                            <i class="fe-arrow-right"></i>
                        </a>
                    </div>
                </li>

                <!-- User Dropdown -->
                <li class="dropdown notification-list topbar-dropdown">
                    <a class="nav-link dropdown-toggle nav-user me-0" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                        {!! \App\HelperClass::generateAvatar(
                            Auth::user()?->employee?->photo_path,
                            Auth::user()?->name ?? 'Guest',
                            32,
                            '#974063',
                            'rounded-circle',
                            Auth::user()?->employee_id,
                        ) !!}
                        <span class="pro-user-name ms-1">{{ Auth::user()?->name ?? 'Guest' }} <i class="mdi mdi-chevron-down"></i></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end profile-dropdown">
                        <!-- item-->
                        <div class="dropdown-header noti-title">
                            <h6 class="text-overflow m-0">Welcome !</h6>
                        </div>

                        <!-- item-->
                        <a class='dropdown-item notify-item' href='{{ route('profile.edit') }}'>
                            <i class="mdi mdi-account-circle-outline fs-16 align-middle"></i>
                            <span>My Account</span>
                        </a>

                        <div class="dropdown-divider"></div>

                        <!-- item-->
                        <form method="POST" action="{{ route('logout') }}" id="logout-form">
                            @csrf
                            <a class='dropdown-item notify-item' href='#' onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="mdi mdi-location-exit fs-16 align-middle"></i>
                                <span>Logout</span>
                            </a>
                        </form>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- end Topbar -->
