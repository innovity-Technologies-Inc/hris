<table class="table table-bordered mb-0">
    <thead>
    <tr>
        <th scope="col">#</th>
        <th scope="col">Employee Name</th>
        <th scope="col">From</th>
        <th scope="col">To</th>
        @if(auth()->user()->user_type !== \App\Enums\UserType::Employee)
            <th scope="col">Total Allowance</th>
            <th scope="col">Status</th>
            <th scope="col">Payment</th>
        @endif
        <th scope="col">Action</th>
    </tr>
    </thead>
    <tbody>
    @php 
        $sl = 1;
        $isEmployee = auth()->user()->user_type === \App\Enums\UserType::Employee;
    @endphp
    @foreach ($movements as $movement)
        <tr>
            <th scope="row">{{ $sl++ }}</th>
            <td>
                <div class="fw-semibold">{{ $movement->getEmployee->full_name }}</div>
                <small class="text-muted">{{ $movement->applicant_id }}</small>
            </td>
            <td>
                <div>{{ \Carbon\Carbon::parse($movement->from_date)->format('d M Y') }}</div>
                <small class="text-muted">{{ \Carbon\Carbon::parse($movement->from_date)->format('h:i A') }}</small>
            </td>
            <td>
                <div>{{ \Carbon\Carbon::parse($movement->to_date)->format('d M Y') }}</div>
                <small class="text-muted">{{ \Carbon\Carbon::parse($movement->to_date)->format('h:i A') }}</small>
            </td>
            @if(!$isEmployee)
            <td class="text-end">
                <span class="fw-semibold text-primary">৳{{ number_format($movement->total_allowance, 2) }}</span>
            </td>
            <td>
                @if ($movement->status == 'pending')
                    <span class="badge bg-warning text-light">Pending</span>
                @elseif($movement->status == 'approved')
                    <span class="badge bg-success">Approved</span>
                @elseif($movement->status == 'rejected')
                    <span class="badge bg-danger">Rejected</span>
                @else
                    <span class="badge bg-info">Completed</span>
                @endif
            </td>
            <td>
                @if ($movement->payment_status == 'paid')
                    <span class="badge bg-success">Paid</span>
                @else
                    <span class="badge bg-secondary">Unpaid</span>
                @endif
            </td>
            @endif
            <td>
                {{-- View Button --}}
                @can('movement.view')
                <button type="button" class="btn btn-info btn-sm" title="View Details" data-bs-toggle="modal"
                        data-bs-target="#viewTravelMovementModal{{ $movement->id }}">
                    <i style="height: 12px; width: 12px" data-feather="eye"></i>
                </button>
                @endcan
            </td>
        </tr>

        {{-- Include View Modal for each movement --}}
        @include('movement.partials.view_modal', ['movement' => $movement])
    @endforeach
    </tbody>
</table>

<script>
    // Reinitialize feather icons after table load
    if (typeof feather !== 'undefined') {
        feather.replace();
    }
</script>

