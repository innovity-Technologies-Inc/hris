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
                        <span class="badge bg-danger rounded-circle noti-icon-badge d-none" id="notificationCount">0</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end dropdown-lg">
                        <!-- item-->
                        <div class="dropdown-item noti-title">
                            <h5 class="m-0">
                                <span class="float-end"><a href="javascript:void(0);" onclick="markAllNotificationsRead()" class="text-dark"><small>Clear All</small></a></span>Notification
                            </h5>
                        </div>

                        <div class="noti-scroll" data-simplebar="" id="notificationList" style="max-height: 400px; overflow-y: auto;">
                            {{-- Notifications will be loaded here via AJAX --}}
                        </div>

                        <!-- All-->
                        <a href="{{ route('notifications.index') }}" class="dropdown-item text-center text-primary notify-item notify-all">View all
                            <i class="fe-arrow-right"></i>
                        </a>
                    </div>
                </li>

@push('scripts')
<script>
function fetchHeaderNotifications() {
    $.ajax({
        url: "{{ route('notifications.header') }}",
        method: "GET",
        success: function(response) {
            $('#notificationList').html(response.html);
            if (response.unread_count > 0) {
                $('#notificationCount').text(response.unread_count).removeClass('d-none');
            } else {
                $('#notificationCount').addClass('d-none');
            }
        }
    });
}

function markNotificationRead(id) {
    $.ajax({
        url: "/notifications/" + id + "/mark-as-read",
        method: "POST",
        data: { _token: "{{ csrf_token() }}" },
        success: function() {
            fetchHeaderNotifications();
        }
    });
}

function markAllNotificationsRead() {
    $.ajax({
        url: "{{ route('notifications.mark-all-read') }}",
        method: "POST",
        data: { _token: "{{ csrf_token() }}" },
        success: function() {
            fetchHeaderNotifications();
        }
    });
}

$(document).ready(function() {
    fetchHeaderNotifications();
    // Refresh every 60 seconds
    setInterval(fetchHeaderNotifications, 60000);
});
</script>
@endpush

                <!-- User Dropdown -->
                <li class="dropdown notification-list topbar-dropdown">
                    <a class="nav-link dropdown-toggle nav-user me-0" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                        {!! \App\HelperClass::generateAvatar(
                            Auth::user()?->employee?->photo_path,
                            Auth::user()?->name ?? 'Guest',
                            32,
                            '#974063',
                            'rounded-circle',
                        ) !!}
                        <span class="pro-user-name ms-1">{{ Auth::user()?->name ?? 'Guest' }} <i class="mdi mdi-chevron-down"></i></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end profile-dropdown">
                        <!-- item-->
                        <div class="dropdown-header noti-title">
                            <h6 class="text-overflow m-0">Welcome !</h6>
                        </div>

                        <!-- item-->
                        <a class='dropdown-item notify-item' href='{{ Auth::user()?->employee_id ? route('employees.profile.general_informations', Auth::user()->employee_id) : '#' }}'>
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
