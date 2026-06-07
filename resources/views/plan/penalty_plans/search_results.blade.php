<div class="table-responsive">
    <table class="table table-bordered mb-0">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Title</th>
                <th scope="col">Penalty Amount</th>
                <th scope="col">Status</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @php
                $sl = \App\HelperClass::indexNumberSerialization($plans);
            @endphp
            @forelse ($plans as $plan)
                <tr>
                    <th scope="row">{{ $sl++ }}</th>
                    <td>{{ $plan->title }}</td>
                    <td>{{ \App\HelperClass::getCurrency() }} {{ number_format($plan->penalty_amount, 2) }}</td>
                    <td>
                        @if ($plan->status === 'active')
                            <span class="badge text-bg-success">Active</span>
                        @else
                            <span class="badge text-bg-danger">Inactive</span>
                        @endif
                    </td>
                    <td>
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
                    <td colspan="5" class="text-center py-4 text-muted">No penalty plans found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3">
    {{ $plans->links() }}
</div>
