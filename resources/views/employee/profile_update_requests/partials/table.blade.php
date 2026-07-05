<div class="table-responsive">
    <table class="table table-bordered align-middle mb-0">
        <thead>
            <tr>
                <th scope="col" class="text-muted small fw-bold text-uppercase">Employee</th>
                <th scope="col" class="text-muted small fw-bold text-uppercase">Type</th>
                <th scope="col" class="text-muted small fw-bold text-uppercase">Section</th>
                <th scope="col" class="text-muted small fw-bold text-uppercase">Status</th>
                <th scope="col" class="text-muted small fw-bold text-uppercase">Requested At</th>
                <th scope="col" class="text-center text-muted small fw-bold text-uppercase" style="width: 130px;">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($requests as $updateRequest)
            <tr>
                <td>
                    <div class="d-flex align-items-center">
                        {!! \App\HelperClass::generateAvatar(
                             $updateRequest->employee?->photo_path ?? null,
                             $updateRequest->employee?->full_name ?? 'N/A',
                             32,
                             '#974063',
                             'me-2',
                             $updateRequest->employee?->id ?? 0,
                         ) !!}
                        <div>
                            <h6 class="mb-0 fw-bold">{{ $updateRequest->employee?->full_name ?? 'N/A' }}</h6>
                            <small class="text-muted">{{ $updateRequest->employee?->punch_card_no ?? '' }}</small>
                        </div>
                    </div>
                </td>
                <td>
                    @if(($updateRequest->type ?? 'employee') === 'admin')
                        <span class="badge bg-primary-subtle text-primary fw-normal px-2 py-1 text-capitalize">Admin</span>
                    @else
                        <span class="badge bg-secondary-subtle text-secondary fw-normal px-2 py-1 text-capitalize">Employee</span>
                    @endif
                </td>
                <td>
                    <span class="badge bg-info-subtle text-info fw-normal px-2 py-1 text-capitalize">
                        {{ str_replace('_', ' ', $updateRequest->section) }}
                    </span>
                </td>
                <td>
                    @if($updateRequest->status === 'pending')
                        <span class="badge bg-warning-subtle text-warning fw-normal px-2 py-1">Pending</span>
                    @elseif($updateRequest->status === 'approved')
                        <span class="badge bg-success-subtle text-success fw-normal px-2 py-1">Approved</span>
                    @else
                        <span class="badge bg-danger-subtle text-danger fw-normal px-2 py-1">Rejected</span>
                    @endif
                </td>
                <td>{{ $updateRequest->created_at->format('d M, Y') }}</td>
                <td class="text-center">
                    <a href="{{ route('profile_update_requests.show', $updateRequest->id) }}" class="btn btn-secondary btn-sm" title="View">
                        <i style="height: 12px; width: 12px" data-feather="eye"></i>
                    </a>
                    @if(auth()->user()->can('profile-update-requests.delete'))
                    <button type="button" class="btn btn-danger btn-sm" title="Delete" onclick="deleteRequest({{ $updateRequest->id }})">
                        <i style="height: 12px; width: 12px" data-feather="trash-2"></i>
                    </button>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center py-5">
                    <div class="text-muted">
                        <i data-feather="clipboard" class="mb-3" style="height: 48px; width: 48px; opacity: 0.5;"></i>
                        <p class="mb-0">No profile update requests found.</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($requests->hasPages())
<div class="mt-3">
    {{ $requests->appends(request()->query())->links() }}
</div>
@endif

<script>
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
</script>
