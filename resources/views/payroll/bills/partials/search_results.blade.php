<table class="table table-hover mb-0">
    <thead>
        <tr>
            <th>#</th>
            <th>Employee</th>
            <th>Billing Type</th>
            <th>Expense Detail</th>
            <th>Amount</th>
            <th>Payment Status</th>
            <th>Date Created</th>
            <th class="text-end">Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse($bills as $bill)
            <tr>
                <td>{{ $loop->iteration + ($bills->currentPage() - 1) * $bills->perPage() }}</td>
                <td>
                    <div class="fw-semibold text-dark">{{ $bill->employee->full_name ?? 'N/A' }}</div>
                    <small class="text-muted">ID: {{ $bill->employee->applicant_id ?? 'N/A' }}</small>
                </td>
                <td>
                    @if($bill->type === 'travel-movement')
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1">Travel Movement</span>
                    @elseif($bill->type === 'claim-expense')
                        <span class="badge bg-info-subtle text-info border border-info-subtle px-2 py-1">Expense - {{ $bill->expense_type }}</span>
                    @else
                        <span class="badge bg-secondary px-2 py-1">{{ ucfirst(str_replace('-', ' ', $bill->type)) }}</span>
                    @endif
                </td>
                <td>{{ $bill->expense_type }}</td>
                <td class="fw-bold text-dark">৳{{ number_format($bill->amount, 2) }}</td>
                <td>
                    @if($bill->payment_status === 'paid')
                        <span class="badge rounded-pill bg-success-subtle text-success px-3 py-1">
                            <i class="bi bi-check-circle-fill me-1"></i> Paid
                        </span>
                        @if($bill->payment_method)
                            <div class="small mt-1 text-muted">
                                <strong>Method:</strong> {{ $bill->payment_method }}
                            </div>
                        @endif
                        @if($bill->remarks)
                            <div class="small text-muted text-wrap" style="max-width: 150px;">
                                <strong>Remarks:</strong> {{ Str::limit($bill->remarks, 30) }}
                            </div>
                        @endif
                        @if($bill->attachment_path)
                            <div class="mt-1">
                                <a href="{{ \App\HelperClass::get_file_url($bill->attachment_path) }}" target="_blank" class="btn btn-xs btn-outline-primary py-0 px-1" style="font-size: 7.5pt;">
                                    <i class="bi bi-paperclip"></i> View Receipt
                                </a>
                            </div>
                        @endif
                    @else
                        <span class="badge rounded-pill bg-danger-subtle text-danger px-3 py-1">
                            <i class="bi bi-x-circle-fill me-1"></i> Unpaid
                        </span>
                    @endif
                </td>
                <td>{{ $bill->created_at->format('M d, Y') }}</td>
                <td class="text-end">
                    <div class="d-flex justify-content-end gap-1">
                        @if($bill->payment_status === 'unpaid')
                            @can('bills.edit')
                            <button type="button" class="btn btn-success btn-sm pay-bill-btn" data-id="{{ $bill->id }}" data-amount="{{ $bill->amount }}" title="Pay Bill">
                                <i class="bi bi-check-circle"></i>
                            </button>
                            @endcan
                        @else
                            @can('bills.edit')
                            <button type="button" class="btn btn-info btn-sm view-payment-btn text-white" 
                                    data-id="{{ $bill->id }}" 
                                    data-amount="{{ $bill->amount }}"
                                    data-method="{{ $bill->payment_method ?? 'N/A' }}"
                                    data-remarks="{{ $bill->remarks ?? 'N/A' }}"
                                    data-attachment="{{ $bill->attachment_path ? \App\HelperClass::get_file_url($bill->attachment_path) : '' }}"
                                    data-date="{{ $bill->updated_at->format('M d, Y h:i A') }}"
                                    title="View Payment Info">
                                <i class="bi bi-eye"></i>
                            </button>
                            <button type="button" class="btn btn-warning btn-sm toggle-payment-status text-white" data-id="{{ $bill->id }}" data-status="unpaid" title="Mark as Unpaid">
                                <i class="bi bi-arrow-counterclockwise"></i>
                            </button>
                            @endcan
                        @endif

                        @can('bills.delete')
                        <button type="button" class="btn btn-danger btn-sm delete-bill" data-id="{{ $bill->id }}" title="Delete">
                            <i class="bi bi-trash"></i>
                        </button>
                        @endcan
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="text-center text-muted py-4">No bills found.</td>
            </tr>
        @endforelse
    </tbody>
</table>

<div class="mt-4 d-flex justify-content-start">
    {{ $bills->links() }}
</div>
