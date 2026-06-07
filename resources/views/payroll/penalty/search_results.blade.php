<div class="table-responsive">
    <table class="table table-bordered mb-0">
        <thead class="table-light">
            <tr>
                <th scope="col">#</th>
                <th scope="col">Employee Name</th>
                <th scope="col">Employee ID</th>
                <th scope="col">Penalty Plan</th>
                <th scope="col">Amount</th>
                <th scope="col">Occurrence Date</th>
                <th scope="col">Status</th>
                <th scope="col" class="text-end">Action</th>
            </tr>
        </thead>
        <tbody>
            @php
                $sl = \App\HelperClass::indexNumberSerialization($penalties);
            @endphp
            @forelse ($penalties as $penalty)
                <tr>
                    <th scope="row">{{ $sl++ }}</th>
                    <td class="fw-bold">{{ $penalty->employee->full_name }}</td>
                    <td>{{ $penalty->employee->applicant_id ?? $penalty->employee->system_id }}</td>
                    <td>{{ $penalty->penaltyPlan->title ?? 'Custom' }}</td>
                    <td>{{ \App\HelperClass::getCurrency() }} {{ number_format($penalty->penalty_amount, 2) }}</td>
                    <td>{{ \Carbon\Carbon::parse($penalty->occurrence_date)->format('d M Y') }}</td>
                    <td>
                        @if ($penalty->status === 'pending')
                            <span class="badge text-bg-warning">Pending</span>
                        @elseif ($penalty->status === 'approved')
                            <span class="badge text-bg-success">Approved</span>
                        @else
                            <span class="badge text-bg-info">Deducted</span>
                        @endif
                    </td>
                    <td class="text-end">
                        @can('penalty-management.edit')
                        <button type="button" class="btn btn-primary btn-sm me-1 edit-penalty" 
                                data-id="{{ $penalty->id }}" title="Edit">
                            <i style="height: 12px; width: 12px" data-feather="edit"></i>
                        </button>
                        @endcan
                        @can('penalty-management.delete')
                        <button type="button" class="btn btn-danger btn-sm delete-penalty" 
                                data-id="{{ $penalty->id }}" title="Delete">
                            <i style="height: 12px; width: 12px" data-feather="trash"></i>
                        </button>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center py-4 text-muted">No penalty records found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3">
    {{ $penalties->links('pagination::bootstrap-5') }}
</div>
