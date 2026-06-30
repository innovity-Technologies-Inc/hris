@forelse($notifications as $notification)
    @php
        $redirectUrl = isset($notification->data['employee_id']) ? route('employee.profile.general_informations', $notification->data['employee_id']) : (isset($notification->data['url']) ? url($notification->data['url']) : null);
    @endphp
    <a href="javascript:void(0);" class="dropdown-item notify-item text-muted link-primary" onclick="markNotificationRead({{ $notification->id }}{{ $redirectUrl ? ", '$redirectUrl'" : '' }})">
        <div class="notify-icon bg-soft-primary">
            <i class="fas fa-info-circle text-primary"></i>
        </div>
        <div class="d-flex align-items-center justify-content-between">
            <p class="notify-details">{{ $notification->title }}</p>
            <small class="text-muted">{{ $notification->created_at->diffForHumans(null, true, true) }}</small>
        </div>
        <p class="mb-0 user-msg">
            <small class="fs-14">{{ $notification->message }}</small>
        </p>
    </a>
@empty
    <div class="text-center py-3 text-muted">
        <p class="mb-0">No new notifications</p>
    </div>
@endforelse

