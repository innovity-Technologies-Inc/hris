@extends('structure.master')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-history me-2"></i>
                            <h5 class="mb-0">Vehicle Allocation History</h5>
                        </div>
                        <div>
                            <a href="{{ route('transport.vehicle_allocations.dashboard') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                            </a>
                            <a href="{{ route('transport.vehicle_allocations.create') }}" class="btn btn-success btn-sm">
                                <i class="fas fa-plus me-1"></i>New Allocation
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Filters -->
                    <form action="{{ route('transport.vehicle_allocations.history') }}" method="GET" class="mb-4">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-3">
                                <label class="form-label small fw-semibold">Vehicle</label>
                                <select name="vehicle_id" class="form-select select2_list">
                                    <option value="">All Vehicles</option>
                                    @foreach ($vehicles ?? [] as $vehicle)
                                        <option value="{{ $vehicle->id }}"
                                            {{ request('vehicle_id') == $vehicle->id ? 'selected' : '' }}>
                                            {{ $vehicle->reg_no }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small fw-semibold">Allocation Type</label>
                                <select name="allocation_type" class="form-select">
                                    <option value="">All Types</option>
                                    <option value="Trip" {{ request('allocation_type') == 'Trip' ? 'selected' : '' }}>Trip
                                    </option>
                                    <option value="Employee Transport"
                                        {{ request('allocation_type') == 'Employee Transport' ? 'selected' : '' }}>Employee
                                        Transport</option>
                                    <option value="Temporary"
                                        {{ request('allocation_type') == 'Temporary' ? 'selected' : '' }}>Temporary</option>
                                    <option value="Permanent"
                                        {{ request('allocation_type') == 'Permanent' ? 'selected' : '' }}>Permanent</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small fw-semibold">Status</label>
                                <select name="status" class="form-select">
                                    <option value="">All Status</option>
                                    <option value="Allocated" {{ request('status') == 'Allocated' ? 'selected' : '' }}>
                                        Allocated</option>
                                    <option value="Active" {{ request('status') == 'Active' ? 'selected' : '' }}>Active
                                    </option>
                                    <option value="Released" {{ request('status') == 'Released' ? 'selected' : '' }}>
                                        Released</option>
                                    <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>
                                        Completed</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small fw-semibold">From Date</label>
                                <input type="date" name="from_date" class="form-control"
                                    value="{{ request('from_date') }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small fw-semibold">To Date</label>
                                <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                            </div>
                            <div class="col-md-1">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-filter"></i>
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Results Table -->
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Vehicle</th>
                                    <th>Allocation Name</th>
                                    <th>Type</th>
                                    <th>Reference</th>
                                    <th>Period</th>
                                    <th>Status</th>
                                    <th>Approved By</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($allocations as $index => $allocation)
                                    <tr>
                                        <td>{{ $allocations->firstItem() + $index }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if ($allocation->getVehicle && $allocation->getVehicle->vehicle_image)
                                                    <img src="{{ \App\HelperClass::get_file_url($allocation->getVehicle->vehicle_image) }}"
                                                        alt="Vehicle" class="rounded-2 me-2"
                                                        style="width: 40px; height: 40px; object-fit: cover;">
                                                @else
                                                    <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-2">
                                                        <i class="fas fa-car text-primary"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <strong>{{ $allocation->getVehicle->vehicle_category ?? 'N/A' }}</strong>
                                                    <small class="text-muted d-block">
                                                        {{ $allocation->getVehicle->model_number ?? $allocation->getVehicle->vehicle_category }}
                                                    </small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $allocation->name }}</td>
                                        <td>
                                            <span class="badge bg-info">{{ $allocation->allocation_type }}</span>
                                        </td>
                                        <td>
                                            @if ($allocation->reference_type && $allocation->reference_id)
                                                @php
                                                    $refLabel = match ($allocation->reference_type) {
                                                        'App\\Models\\Transport\\EmployeeTransport' => 'Emp. Transport',
                                                        'App\\Models\\Transport\\VehicleRequisition' => 'Requisition',
                                                        default => 'Ref',
                                                    };
                                                @endphp
                                                <span class="badge bg-secondary">{{ $refLabel }}
                                                    #{{ $allocation->reference_id }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <small>
                                                {{ $allocation->start_date ? \Carbon\Carbon::parse($allocation->start_date)->format('d/m/Y') : '-' }}
                                                <br>
                                                <span class="text-muted">to</span>
                                                {{ $allocation->end_date ? \Carbon\Carbon::parse($allocation->end_date)->format('d/m/Y') : '-' }}
                                            </small>
                                        </td>
                                        <td>
                                            @php
                                                $statusClass = match ($allocation->status) {
                                                    'Allocated' => 'warning',
                                                    'Active' => 'success',
                                                    'Released' => 'secondary',
                                                    'Completed' => 'primary',
                                                    'Cancelled' => 'danger',
                                                    default => 'secondary',
                                                };
                                            @endphp
                                            <span class="badge bg-{{ $statusClass }}">{{ $allocation->status }}</span>
                                        </td>
                                        <td>
                                            @if ($allocation->approved_at)
                                                <small class="text-muted d-block">
                                                    {{ \Carbon\Carbon::parse($allocation->approved_at)->format('d/m/Y') }}
                                                </small>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('transport.vehicle_allocations.show', $allocation->id) }}"
                                                    class="btn btn-outline-info" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if ($allocation->status == 'Active' || $allocation->status == 'Allocated')
                                                    <button type="button" class="btn btn-outline-danger"
                                                        onclick="releaseVehicle({{ $allocation->id }})" title="Release">
                                                        <i class="fas fa-unlock"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-5">
                                            <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                                            <p class="text-muted mb-0">No allocation records found</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if ($allocations->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $allocations->withQueryString()->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Release Vehicle Modal -->
    <div class="modal fade" id="releaseModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Release Vehicle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="releaseForm" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body">
                        <p>Are you sure you want to release this vehicle allocation?</p>
                        <div class="mb-3">
                            <label for="release_remarks" class="form-label">Remarks (Optional)</label>
                            <textarea name="release_remarks" id="release_remarks" class="form-control" rows="3"
                                placeholder="Enter any remarks for releasing this vehicle..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-unlock me-1"></i>Release Vehicle
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function releaseVehicle(allocationId) {
            const form = document.getElementById('releaseForm');
            form.action = `/transport/vehicle-allocations/${allocationId}/release`;
            const modal = new bootstrap.Modal(document.getElementById('releaseModal'));
            modal.show();
        }
    </script>
@endpush

