<div class="table-responsive">
    <table class="table table-bordered mb-0">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Title</th>
                <th scope="col">Encashment Basis</th>
                <th scope="col">Min Balance</th>
                <th scope="col">Rate</th>
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
                    <td>
                        <span class="fw-bold text-dark">{{ $plan->title }}</span><br>
                        <small class="text-muted">{{ Str::limit($plan->description, 50) }}</small>
                    </td>
                    <td>
                        <span class="badge text-bg-info text-uppercase">{{ $plan->encashment_basis }}</span>
                    </td>
                    <td>{{ $plan->min_balance_to_maintain }} Days</td>
                    <td>{{ number_format($plan->encashment_rate, 2) }}x</td>
                    <td>
                        @if ($plan->status === 'active')
                            <span class="badge text-bg-success">Active</span>
                        @else
                            <span class="badge text-bg-danger">Inactive</span>
                        @endif
                    </td>
                    <td>
                        @can('leave-encashment-plans.edit')
                        <button type="button" class="btn btn-primary btn-sm me-1 edit-plan" 
                                data-id="{{ $plan->id }}" title="Edit">
                            <i style="height: 12px; width: 12px" data-feather="edit"></i>
                        </button>
                        @endcan
                        @can('leave-encashment-plans.delete')
                        <button type="button" class="btn btn-danger btn-sm delete-plan" 
                                data-id="{{ $plan->id }}" title="Delete">
                            <i style="height: 12px; width: 12px" data-feather="trash"></i>
                        </button>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-4 text-muted">No leave encashment plans found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-3">
    {{ $plans->links() }}
</div>
