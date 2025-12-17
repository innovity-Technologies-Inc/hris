<table class="table table-bordered mb-0">
    <thead>
        <tr>
            <th scope="col" style="width: 50px;">#</th>
            <th scope="col">Employee Name</th>
            <th scope="col">From</th>
            <th scope="col">To</th>
            <th scope="col">Distance (KM)</th>
            <th scope="col">Total Allowance</th>
            <th scope="col">Status</th>
            <th scope="col" style="width: 150px;">Action</th>
        </tr>
    </thead>
    <tbody>
        @php $sl = 1; @endphp
        @foreach ($movements as $movement)
            <tr>
                <th scope="row">{{ $sl++ }}</th>
                <td>
                    <div class="fw-semibold">{{ $movement->employee_name }}</div>
                    <small class="text-muted">{{ $movement->employee_code }}</small>
                </td>
                <td>
                    <div>{{ \Carbon\Carbon::parse($movement->from_date)->format('d M Y') }}</div>
                    <small class="text-muted">{{ \Carbon\Carbon::parse($movement->from_date)->format('h:i A') }}</small>
                </td>
                <td>
                    <div>{{ \Carbon\Carbon::parse($movement->to_date)->format('d M Y') }}</div>
                    <small class="text-muted">{{ \Carbon\Carbon::parse($movement->to_date)->format('h:i A') }}</small>
                </td>
                <td class="text-center">{{ number_format($movement->covered_distance, 2) }}</td>
                <td class="text-end">
                    <span class="fw-semibold text-primary">৳{{ number_format($movement->total_allowance, 2) }}</span>
                </td>
                <td>
                    @if ($movement->status == 'pending')
                        <span class="badge bg-warning text-dark">Pending</span>
                    @elseif($movement->status == 'approved')
                        <span class="badge bg-success">Approved</span>
                    @elseif($movement->status == 'rejected')
                        <span class="badge bg-danger">Rejected</span>
                    @else
                        <span class="badge bg-info">Completed</span>
                    @endif
                </td>
                <td>
                    {{-- View Button --}}
                    <button type="button" class="btn btn-info btn-sm" title="View" data-bs-toggle="modal"
                        data-bs-target="#viewMovementModal{{ $movement->id }}"
                        onclick="loadMovementDetails({{ json_encode($movement) }})">
                        <i style="height: 12px; width: 12px" data-feather="eye"></i>
                    </button>

                    {{-- Edit Button --}}
                    <a href="{{ route('leaves.movement.edit', $movement->id) }}" class="btn btn-primary btn-sm"
                        title="Edit">
                        <i style="height: 12px; width: 12px" data-feather="edit"></i>
                    </a>

                    {{-- Delete Button --}}
                    <form action="{{ route('leaves.movement.destroy', $movement->id) }}" method="POST"
                        class="d-inline">
                        @csrf
                        @method('delete')
                        <button type="submit" class="btn btn-danger btn-sm confirmDelete" title="Delete">
                            <i style="height: 12px; width: 12px" data-feather="trash-2"></i>
                        </button>
                    </form>
                </td>
            </tr>

            {{-- Include View Modal for each movement --}}
            @include('leaves.movement.partials.view_modal', ['movement' => $movement])
        @endforeach
    </tbody>
</table>

<script>
    // Reinitialize feather icons after table load
    if (typeof feather !== 'undefined') {
        feather.replace();
    }

    // Function to load movement details (if needed for dynamic data)
    function loadMovementDetails(movement) {
        console.log('Movement Details:', movement);
    }
</script>
