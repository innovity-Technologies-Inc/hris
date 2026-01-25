<table class="table table-hover table-striped">
    <thead class="table-light">
        <tr>
            <th>#</th>
            <th>Service Name</th>
            <th>Company</th>
            <th>Transport Type</th>
            <th>Purpose</th>
            <th>Duration</th>
            <th>Status</th>
            <th class="text-center">Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($employeeTransports as $key => $transport)
            <tr>
                <td>{{ $employeeTransports->firstItem() + $key }}</td>
                <td>
                    <strong>{{ $transport->service_name }}</strong>
                </td>
                <td>
                    {{ $transport->getCompany?->name ?? 'N/A' }}
                </td>
                <td>
                    <span class="badge bg-info">{{ $transport->transport_type }}</span>
                </td>
                <td>
                    <span title="{{ $transport->purpose }}">
                        {{ \Illuminate\Support\Str::limit($transport->purpose, 30) }}
                    </span>
                </td>
                <td>
                    <small>
                        <i data-feather="calendar" style="width: 12px; height: 12px;"></i>
                        {{ $transport->start_date?->format('d M Y') }} - {{ $transport->end_date?->format('d M Y') }}
                        @if ($transport->estimated_passengers)
                            <br>
                            <span class="text-muted">~{{ $transport->estimated_passengers }} passengers</span>
                        @endif
                    </small>
                </td>
                <td>
                    <span class="badge {{ $transport->status_badge_class }}">
                        {{ $transport->status }}
                    </span>
                </td>
                <td class="text-center">
                    <div class="btn-group btn-group-sm">
                        <a href="{{ route('transport.employee_transports.show', $transport->id) }}"
                            class="btn btn-outline-info btn-sm" title="View">
                            <i data-feather="eye" style="width: 14px; height: 14px;"></i>
                        </a>
                        @if ($transport->status === 'Pending')
                            <a href="{{ route('transport.employee_transports.edit', $transport->id) }}"
                                class="btn btn-outline-primary btn-sm" title="Edit">
                                <i data-feather="edit" style="width: 14px; height: 14px;"></i>
                            </a>
                            <button type="button" class="btn btn-outline-danger btn-sm" title="Reject"
                                onclick="rejectService({{ $transport->id }})">
                                <i data-feather="x" style="width: 14px; height: 14px;"></i>
                            </button>
                        @endif
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="text-center py-4 text-muted">
                    No transport services found
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

{{-- Pagination --}}
<div class="d-flex justify-content-between align-items-center mt-3">
    <div class="text-muted small">
        Showing {{ $employeeTransports->firstItem() ?? 0 }} to {{ $employeeTransports->lastItem() ?? 0 }}
        of {{ $employeeTransports->total() }} entries
    </div>
    <div>
        {{ $employeeTransports->links('pagination::bootstrap-5') }}
    </div>
</div>

{{-- Rejection Modal --}}
<div class="modal fade" id="rejectionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="rejectionForm" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Reject Transport Service</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="rejection_remarks" class="form-label">Reason for Rejection <span
                                class="text-danger">*</span></label>
                        <textarea class="form-control" name="approval_remarks" id="rejection_remarks" rows="3" required
                            placeholder="Please provide a reason for rejection..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function rejectService(id) {
        const form = document.getElementById('rejectionForm');
        form.action = `/transport/employee-transports/${id}/reject`;
        new bootstrap.Modal(document.getElementById('rejectionModal')).show();
    }
</script>
