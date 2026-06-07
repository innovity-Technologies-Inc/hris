<div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
            <tr>
                <th>Title</th>
                <th>Penalty Amount</th>
                <th>Status</th>
                <th class="text-end">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($plans as $plan)
                <tr>
                    <td class="fw-bold">{{ $plan->title }}</td>
                    <td>{{ \App\HelperClass::getCurrency() }} {{ number_format($plan->penalty_amount, 2) }}</td>
                    <td>
                        @if ($plan->status === 'active')
                            <span class="badge badge-soft-success">Active</span>
                        @else
                            <span class="badge badge-soft-danger">Inactive</span>
                        @endif
                    </td>
                    <td class="text-end">
                        @can('penalty-plans.edit')
                        <button type="button" class="btn btn-primary btn-sm me-1 edit-penalty-plan" 
                                data-id="{{ $plan->id }}" title="Edit">
                            <i style="height: 12px; width: 12px" data-feather="edit"></i>
                        </button>
                        @endcan
                        @can('penalty-plans.delete')
                        <button type="button" class="btn btn-danger btn-sm delete-penalty-plan" 
                                data-id="{{ $plan->id }}" title="Delete">
                            <i style="height: 12px; width: 12px" data-feather="trash"></i>
                        </button>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center py-4 text-muted">No penalty plans found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3">
    {{ $plans->links('pagination::bootstrap-5') }}
</div>
