@extends('structure.master')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card border-0 shadow-sm rounded">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Notifications</h5>
                <button type="button" id="markAllReadBtn" class="btn btn-sm btn-outline-primary">
                    <i class="mdi mdi-check-all me-1"></i> Mark All as Read
                </button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3 text-muted small fw-bold text-uppercase" style="width: 50px;">Status</th>
                                <th class="py-3 text-muted small fw-bold text-uppercase">Notification</th>
                                <th class="py-3 text-muted small fw-bold text-uppercase">Time</th>
                                <th class="pe-4 py-3 text-center text-muted small fw-bold text-uppercase" style="width: 100px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($notifications as $notification)
                            <tr class="{{ is_null($notification->read_at) ? 'bg-light-subtle' : '' }}" id="notification-row-{{ $notification->id }}">
                                <td class="ps-4">
                                    @if(is_null($notification->read_at))
                                        <span class="badge bg-primary rounded-circle p-1"><span class="visually-hidden">Unread</span></span>
                                    @else
                                        <span class="badge bg-secondary rounded-circle p-1"><span class="visually-hidden">Read</span></span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <h6 class="mb-1 fw-bold text-dark">{{ $notification->title }}</h6>
                                        <p class="text-muted small mb-0">{{ $notification->message }}</p>
                                    </div>
                                </td>
                                <td>
                                    <span class="text-muted small">{{ $notification->created_at->diffForHumans(null, true, true) }}</span>
                                </td>
                                <td class="pe-4">
                                    <div class="d-flex justify-content-center gap-1">
                                        @if(is_null($notification->read_at))
                                        <button class="btn btn-sm btn-light border mark-read-btn" data-id="{{ $notification->id }}" title="Mark as Read">
                                            <i class="mdi mdi-check"></i>
                                        </button>
                                        @endif
                                        
                                        @if(isset($notification->data['employee_id']))
                                        <a href="{{ route('employees.profile.general_informations', $notification->data['employee_id']) }}" class="btn btn-sm btn-info text-white" title="View Related Profile">
                                            <i class="mdi mdi-eye"></i>
                                        </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="mdi mdi-bell-off-outline mb-3 d-block" style="font-size: 48px; opacity: 0.5;"></i>
                                        <p class="mb-0">No notifications found.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($notifications->hasPages())
            <div class="card-footer bg-white border-top">
                <div class="d-flex justify-content-center">
                    {{ $notifications->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Mark single notification as read
    $('.mark-read-btn').on('click', function() {
        const btn = $(this);
        const id = btn.data('id');
        const row = $('#notification-row-' + id);

        $.ajax({
            url: `/notifications/${id}/mark-as-read`,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    btn.fadeOut();
                    row.removeClass('bg-light-subtle');
                    row.find('.badge.bg-primary').removeClass('bg-primary').addClass('bg-secondary');
                    
                    // Update header count if visible
                    updateNotificationBadge();
                }
            }
        });
    });

    // Mark all as read
    $('#markAllReadBtn').on('click', function() {
        Swal.fire({
            title: 'Are you sure?',
            text: "Mark all notifications as read?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, mark all'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route('notifications.mark-all-read') }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            location.reload();
                        }
                    }
                });
            }
        });
    });
});
</script>
@endpush
