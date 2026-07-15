<table class="table table-hover mb-0">
    <thead>
        <tr>
            <th>#</th>
            <th>Employee</th>
            <th>Expense Type</th>
            <th>Amount</th>
            <th>Payment Method</th>
            <th>Purpose</th>
            <th>Status</th>
            <th>Applied Date</th>
            <th class="text-end">Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($applications as $app)
            <tr>
                <td>{{ $loop->iteration + ($applications->currentPage() - 1) * $applications->perPage() }}</td>
                <td>
                    <div class="fw-semibold text-dark">{{ $app->employee->full_name ?? 'N/A' }}</div>
                    <small class="text-muted">ID: {{ $app->employee->applicant_id ?? 'N/A' }}</small>
                </td>
                <td>{{ $app->expenseType->name ?? 'N/A' }}</td>
                <td class="fw-bold">{{ number_format($app->amount, 2) }}</td>
                <td>{{ ucfirst(str_replace('_', ' ', $app->payment_method)) }}</td>
                <td>{{ Str::limit($app->purpose, 40) }}</td>
                <td>
                    <span class="badge rounded-pill 
                        @if($app->status === 'pending') bg-warning-subtle text-warning 
                        @elseif($app->status === 'approved') bg-success-subtle text-success 
                        @else bg-danger-subtle text-danger 
                        @endif px-3 py-1">
                        {{ ucfirst($app->status) }}
                    </span>
                </td>
                <td>{{ $app->created_at->format('M d, Y') }}</td>
                <td class="text-end">
                    <div class="d-flex justify-content-end gap-1">
                        <a href="{{ route('claim_expenses.show', $app->id) }}" class="btn btn-info btn-sm" title="View Details">
                            <i style="height: 12px; width: 12px" data-feather="eye"></i>
                        </a>
                        @if(in_array($app->status, ['pending', 'approved']))
                            @if(auth()->user()->can('claim-expenses.delete') || auth()->user()->id === $app->created_by)
                            <button type="button" class="btn btn-danger btn-sm delete-application" data-id="{{ $app->id }}" title="Cancel / Delete">
                                <i style="height: 12px; width: 12px" data-feather="trash-2"></i>
                            </button>
                            @endif
                        @endif
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="9" class="text-center text-muted py-4">No expense applications found.</td>
            </tr>
        @endforelse
    </tbody>
</table>

<div class="mt-4 d-flex justify-content-end">
    {{ $applications->links() }}
</div>
