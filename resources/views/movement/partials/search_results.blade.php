<table class="table table-bordered mb-0">
    <thead>
    <tr>
        <th scope="col">#</th>
        <th scope="col">Employee Name</th>
        <th scope="col">From</th>
        <th scope="col">To</th>
        <th scope="col">Total Allowance</th>
        <th scope="col">Status</th>
        <th scope="col">Action</th>
    </tr>
    </thead>
    <tbody>
    @php $sl = 1; @endphp
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
                {{-- View Button --}}
                @can('movement.view')
                <button type="button" class="btn btn-info btn-sm" title="View" data-bs-toggle="modal"
                        data-bs-target="#viewMovementModal{{ $movement->id }}"
                        onclick="loadMovementDetails({{ json_encode($movement) }})">
                    <i style="height: 12px; width: 12px" data-feather="eye"></i>
                </button>
                @endcan

                @if ($movement->status == 'pending')
                    @can('movement.hr-approve')
                    <form class="d-inline" action="{{ route('movement.change_status') }}"
                          method="post">
                        @method('put')
                        @csrf
                        <input type="hidden" name="id" value="{{ $movement->id }}">
                        <input type="hidden" name="status" value="approved">
                        <button type="submit" class="btn btn-success btn-sm confirmApprove"
                                title="Approve">
                            <i style="height: 12px; width: 12px" data-feather="check"></i>
                        </button>
                    </form>
                    <form class="d-inline" method="post"
                          action="{{ route('movement.change_status') }}">
                        @method('put')
                        @csrf
                        <input type="hidden" name="id"
                               value="{{ $movement->id }}">
                        <input type="hidden" name="status" value="rejected">
                        <button type="submit" class="btn btn-danger btn-sm confirmReject"
                                title="Reject">
                            <i style="height: 12px; width: 12px" data-feather="x"></i>
                        </button>
                    </form>
                    @endcan
                @endif

                @if ($movement->status == 'pending')
                    {{-- Edit Button --}}
                    @can('movement.edit')
                    <a href="{{ route('movement.edit', $movement->id) }}" class="btn btn-primary btn-sm"
                       title="Edit">
                        <i style="height: 12px; width: 12px" data-feather="edit"></i>
                    </a>
                    @endcan
                @endif

                {{-- Delete Button --}}
                @can('movement.delete')
                <form action="{{ route('movement.destroy', $movement->id) }}" method="POST"
                      class="d-inline">
                    @csrf
                    @method('delete')
                    <button type="submit" class="btn btn-danger btn-sm confirmDelete" title="Delete">
                        <i style="height: 12px; width: 12px" data-feather="trash-2"></i>
                    </button>
                </form>
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

    // Function to load movement details (if needed for dynamic data)
    function loadMovementDetails(movement) {
        console.log('Movement Details:', movement);
    }
</script>

