<table class="table table-hover align-middle mb-0">
    <thead class="table-light">
        <tr>
            <th scope="col">#</th>
            <th scope="col">Employee Name</th>
            <th scope="col">Type</th>
            <th scope="col">Resignation/Notice Date</th>
            <th scope="col">Notice Period</th>
            <th scope="col">Last Working Day</th>
            <th scope="col">Reason</th>
            <th scope="col">Status</th>
            <th scope="col" style="width: 160px;">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($offboardings as $key => $offboarding)
            <tr>
                <th scope="row">{{ $offboardings->firstItem() + $key }}</th>
                <td>
                    <div class="fw-semibold text-dark">{{ $offboarding->employee?->full_name ?? 'N/A' }}</div>
                    <small class="text-muted">ID: {{ $offboarding->employee?->applicant_id ?? '-' }}</small>
                </td>
                <td>
                    @if($offboarding->offboarding_type === 'termination')
                        <span class="badge bg-danger text-white">Termination</span>
                    @else
                        <span class="badge bg-primary text-white">Resignation</span>
                    @endif
                </td>
                <td>{{ \Carbon\Carbon::parse($offboarding->resignation_date)->format('M d, Y') }}</td>
                <td><span class="badge bg-info text-dark">{{ $offboarding->notice_period_days }} Days</span></td>
                <td>{{ \Carbon\Carbon::parse($offboarding->last_working_day)->format('M d, Y') }}</td>
                <td><span class="text-truncate d-inline-block" style="max-width: 180px;" title="{{ $offboarding->reason }}">{{ $offboarding->reason }}</span></td>
                <td>
                    @if ($offboarding->status == 'pending')
                        <span class="badge bg-warning text-dark">Pending</span>
                    @elseif($offboarding->status == 'approved')
                        <span class="badge bg-success">Approved</span>
                    @elseif($offboarding->status == 'rejected')
                        <span class="badge bg-danger">Rejected</span>
                    @else
                        <span class="badge bg-secondary">Cancelled</span>
                    @endif
                </td>
                <td>
                    @php
                        $permissionPrefix = $offboarding->offboarding_type === 'termination' ? 'terminations' : 'resignations';
                    @endphp

                    @can($permissionPrefix . '.view')
                        <a href="{{ route('offboarding.show', $offboarding->id) }}" class="btn btn-outline-info btn-sm rounded-circle p-1" title="View Details">
                            <i class="mdi mdi-eye fs-6"></i>
                        </a>
                    @endcan

                    @can($permissionPrefix . '.edit')
                        <a href="{{ route('offboarding.edit', $offboarding->id) }}" class="btn btn-outline-warning btn-sm rounded-circle p-1 ms-1" title="Edit">
                            <i class="mdi mdi-pencil fs-6"></i>
                        </a>
                    @endcan

                    @can($permissionPrefix . '.delete')
                        <form action="{{ route('offboarding.destroy', $offboarding->id) }}" method="POST" class="d-inline confirmDeleteForm">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm rounded-circle p-1 ms-1 confirmDelete" title="Delete">
                                <i class="mdi mdi-trash-can fs-6"></i>
                            </button>
                        </form>
                    @endcan
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="9" class="text-center py-4 text-muted">
                    <i class="mdi mdi-inbox-remove fs-2 d-block mb-2 text-secondary opacity-50"></i>
                    No {{ ucfirst($type ?? 'offboarding') }} records found.
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

<div class="d-flex justify-content-between align-items-center mt-3 px-3">
    <div>
        Showing {{ $offboardings->firstItem() ?? 0 }} to {{ $offboardings->lastItem() ?? 0 }} of {{ $offboardings->total() }} entries
    </div>
    <div>
        {{ $offboardings->links() }}
    </div>
</div>
