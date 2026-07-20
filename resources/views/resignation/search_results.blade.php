<table class="table table-hover align-middle mb-0">
    <thead class="table-light">
        <tr>
            <th scope="col">#</th>
            <th scope="col">Employee Name</th>
            <th scope="col">Resignation Date</th>
            <th scope="col">Notice Period</th>
            <th scope="col">Last Working Day</th>
            <th scope="col">Reason</th>
            <th scope="col">Status</th>
            <th scope="col" style="width: 160px;">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($resignations as $key => $resignation)
            <tr>
                <th scope="row">{{ $resignations->firstItem() + $key }}</th>
                <td>
                    <div class="fw-semibold text-dark">{{ $resignation->employee->full_name ?? 'N/A' }}</div>
                    <small class="text-muted">ID: {{ $resignation->employee->applicant_id ?? '-' }}</small>
                </td>
                <td>{{ \Carbon\Carbon::parse($resignation->resignation_date)->format('M d, Y') }}</td>
                <td><span class="badge bg-info text-dark">{{ $resignation->notice_period_days }} Days</span></td>
                <td>{{ \Carbon\Carbon::parse($resignation->last_working_day)->format('M d, Y') }}</td>
                <td><span class="text-truncate d-inline-block" style="max-width: 200px;" title="{{ $resignation->reason }}">{{ $resignation->reason }}</span></td>
                <td>
                    @if ($resignation->status == 'pending')
                        <span class="badge bg-warning text-dark">Pending</span>
                    @elseif($resignation->status == 'approved')
                        <span class="badge bg-success">Approved</span>
                    @elseif($resignation->status == 'rejected')
                        <span class="badge bg-danger">Rejected</span>
                    @else
                        <span class="badge bg-secondary">Cancelled</span>
                    @endif
                </td>
                <td>
                    @can('resignations.view')
                        <a href="{{ route('resignation.show', $resignation->id) }}" class="btn btn-outline-info btn-sm rounded-circle p-1" title="View Details">
                            <i class="mdi mdi-eye fs-6"></i>
                        </a>
                    @endcan

                    @can('resignations.edit')
                        <a href="{{ route('resignation.edit', $resignation->id) }}" class="btn btn-outline-warning btn-sm rounded-circle p-1 ms-1" title="Edit">
                            <i class="mdi mdi-pencil fs-6"></i>
                        </a>
                    @endcan

                    @can('resignations.delete')
                        <form action="{{ route('resignation.destroy', $resignation->id) }}" method="POST" class="d-inline confirmDeleteForm">
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
                <td colspan="8" class="text-center py-4 text-muted">
                    <i class="mdi mdi-inbox-remove fs-2 d-block mb-2 text-secondary opacity-50"></i>
                    No resignation records found.
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

<div class="d-flex justify-content-between align-items-center mt-3 px-3">
    <div>
        Showing {{ $resignations->firstItem() ?? 0 }} to {{ $resignations->lastItem() ?? 0 }} of {{ $resignations->total() }} entries
    </div>
    <div>
        {{ $resignations->links() }}
    </div>
</div>
