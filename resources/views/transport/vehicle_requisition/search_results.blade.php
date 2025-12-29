<table class="table table-bordered table-hover mb-0">
    <thead class="table-light">
        <tr>
            <th scope="col" class="text-center" style="width: 50px;">#</th>
            <th scope="col">Employee</th>
            <th scope="col">Trip Type</th>
            <th scope="col">Vehicle Type</th>
            <th scope="col">Schedule</th>
            <th scope="col">Route</th>
            <th scope="col" class="text-center">Status</th>
            <th scope="col" class="text-center" style="width: 150px;">Action</th>
        </tr>
    </thead>
    <tbody>
        @php
            $sl = ($vehicleRequisitions->currentPage() - 1) * $vehicleRequisitions->perPage() + 1;
        @endphp
        @forelse($vehicleRequisitions as $item)
            <tr>
                <th scope="row" class="text-center">{{ $sl++ }}</th>
                <td>
                    @if ($item->getEmployee)
                        <strong>{{ $item->getEmployee->full_name }}</strong>
                        <br><small class="text-muted">{{ $item->getEmployee->system_id }}</small>
                    @else
                        <span class="text-muted">-</span>
                    @endif
                </td>
                <td>
                    <span class="badge bg-info text-dark">{{ $item->trip_type }}</span>
                    <br><small class="text-muted">{{ $item->trip_mode }}</small>
                </td>
                <td>
                    <span class="badge bg-secondary">{{ $item->vehicle_type_required }}</span>
                    <br><small class="text-muted">{{ $item->no_of_passengers }} passengers</small>
                </td>
                <td>
                    <small>
                        <strong>From:</strong>
                        {{ $item->start_date_time ? $item->start_date_time->format('d M Y, h:i A') : '-' }}
                        <br><strong>To:</strong>
                        {{ $item->end_date_time ? $item->end_date_time->format('d M Y, h:i A') : '-' }}
                    </small>
                </td>
                <td>
                    <small>
                        <strong>From:</strong> {{ $item->pickup_location }}
                        <br><strong>To:</strong> {{ $item->destination }}
                    </small>
                </td>
                <td class="text-center">
                    <span class="badge {{ $item->approval_status_badge }}">
                        {{ $item->approval_status }}
                    </span>
                    @if ($item->approval_status === 'Approved' && $item->getAssignedVehicle)
                        <br><small class="text-success">{{ $item->getAssignedVehicle->model_number }}</small>
                    @endif
                </td>
                <td class="text-center">
                    {{-- View Button --}}
                    <a href="{{ route('transport.vehicle_requisitions.show', $item->id) }}" class="btn btn-info btn-sm"
                        title="View Details">
                        <i style="height: 12px; width: 12px" data-feather="eye"></i>
                    </a>

                    @if ($item->approval_status === 'Pending')
                        {{-- Approve Button --}}
                        <a href="{{ route('transport.vehicle_requisitions.approve', $item->id) }}"
                            class="btn btn-success btn-sm" title="Approve">
                            <i style="height: 12px; width: 12px" data-feather="check-circle"></i>
                        </a>

                        {{-- Reject Button --}}
                        <button type="button" class="btn btn-danger btn-sm rejectBtn" data-id="{{ $item->id }}"
                            title="Reject">
                            <i style="height: 12px; width: 12px" data-feather="x-circle"></i>
                        </button>
                    @elseif($item->approval_status === 'Rejected')
                        {{-- Approve Button for Rejected --}}
                        <a href="{{ route('transport.vehicle_requisitions.approve', $item->id) }}"
                            class="btn btn-success btn-sm" title="Approve">
                            <i style="height: 12px; width: 12px" data-feather="check-circle"></i>
                        </a>
                    @elseif($item->approval_status === 'Approved')
                        {{-- Reject Button for Approved --}}
                        <button type="button" class="btn btn-danger btn-sm rejectBtn" data-id="{{ $item->id }}"
                            title="Reject">
                            <i style="height: 12px; width: 12px" data-feather="x-circle"></i>
                        </button>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="text-center py-4">
                    <div class="text-muted">
                        <i data-feather="inbox" style="width: 48px; height: 48px;"></i>
                        <p class="mt-2 mb-0">No vehicle requisitions found</p>
                    </div>
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

<div class="mt-3">
    {{ $vehicleRequisitions->links() }}
</div>
