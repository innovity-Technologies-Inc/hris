@extends('structure.master')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-car-side me-2"></i>
                            <h5 class="mb-0">Vehicle Allocation Details</h5>
                        </div>
                        <div>
                            <a href="{{ route('transport.vehicle_allocations.history') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-arrow-left me-1"></i>Back to History
                            </a>
                            @if ($allocation->status == 'Active' || $allocation->status == 'Allocated')
                                <button type="button" class="btn btn-danger btn-sm"
                                    onclick="releaseVehicle({{ $allocation->id }})">
                                    <i class="fas fa-unlock me-1"></i>Release
                                </button>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <!-- Status Badge -->
                    <div class="mb-4 text-center">
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
                        <span class="badge bg-{{ $statusClass }} fs-6 px-4 py-2">
                            <i class="fas fa-circle me-1"></i>{{ $allocation->status }}
                        </span>
                    </div>

                    <div class="row">
                        <!-- Vehicle Information -->
                        <div class="col-lg-6 mb-4">
                            <div class="card border h-100">
                                <div class="card-header bg-primary bg-opacity-10">
                                    <h6 class="mb-0 text-primary">
                                        <i class="fas fa-car me-2"></i>Vehicle Information
                                    </h6>
                                </div>
                                <div class="card-body">
                                    @if ($allocation->getVehicle)
                                        <div class="text-center mb-4">
                                            @if ($allocation->getVehicle->vehicle_image)
                                                <img src="{{ asset('storage/' . $allocation->getVehicle->vehicle_image) }}"
                                                    alt="Vehicle" class="rounded shadow-sm"
                                                    style="max-width: 100%; height: 200px; object-fit: cover;">
                                            @else
                                                <div class="bg-primary bg-opacity-10 rounded d-flex align-items-center justify-content-center"
                                                    style="height: 200px;">
                                                    <i class="fas fa-car fa-4x text-primary"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="text-center mb-3">
                                            <h5 class="mb-1">{{ $allocation->getVehicle->vehicle_category }}</h5>
                                            <p class="text-muted mb-0">{{ $allocation->getVehicle->model_number }}</p>
                                        </div>
                                        <table class="table table-borderless mb-0">
                                            <tr>
                                                <td class="fw-semibold text-muted" style="width: 40%;">License Number:</td>
                                                <td>{{ $allocation->getVehicle->license_number ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-semibold text-muted">Seating Capacity:</td>
                                                <td>
                                                    <span class="badge bg-info">
                                                        <i
                                                            class="fas fa-users me-1"></i>{{ $allocation->getVehicle->seating_capacity ?? '-' }}
                                                        passengers
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="fw-semibold text-muted">Fuel Type:</td>
                                                <td>{{ $allocation->getVehicle->fuel_type ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-semibold text-muted">Color:</td>
                                                <td>{{ $allocation->getVehicle->color ?? 'N/A' }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-semibold text-muted">Manufacture Year:</td>
                                                <td>{{ $allocation->getVehicle->manufacture_year ?? 'N/A' }}</td>
                                            </tr>
                                        </table>
                                    @else
                                        <p class="text-muted text-center mb-0">Vehicle information not available</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Allocation Information -->
                        <div class="col-lg-6 mb-4">
                            <div class="card border h-100">
                                <div class="card-header bg-success bg-opacity-10">
                                    <h6 class="mb-0 text-success">
                                        <i class="fas fa-clipboard me-2"></i>Allocation Information
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-borderless mb-0">
                                        <tr>
                                            <td class="fw-semibold text-muted" style="width: 40%;">Allocation Name:</td>
                                            <td>{{ $allocation->name ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold text-muted">Allocation Type:</td>
                                            <td>
                                                <span class="badge bg-primary">{{ $allocation->allocation_type }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold text-muted">Start Date:</td>
                                            <td>{{ $allocation->start_date ? \Carbon\Carbon::parse($allocation->start_date)->format('d M, Y') : 'N/A' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-semibold text-muted">End Date:</td>
                                            <td>{{ $allocation->end_date ? \Carbon\Carbon::parse($allocation->end_date)->format('d M, Y') : 'Not Set' }}
                                            </td>
                                        </tr>
                                        @if ($allocation->end_date && $allocation->status == 'Active')
                                            @php
                                                $remaining = now()->diffInDays($allocation->end_date, false);
                                            @endphp
                                            <tr>
                                                <td class="fw-semibold text-muted">Remaining:</td>
                                                <td>
                                                    @if ($remaining > 0)
                                                        <span
                                                            class="badge bg-{{ $remaining > 7 ? 'success' : ($remaining > 3 ? 'warning' : 'danger') }}">
                                                            {{ $remaining }} days
                                                        </span>
                                                    @else
                                                        <span class="badge bg-danger">Expired</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Reference Information -->
                    @if ($allocation->reference)
                        <div class="card border mb-4">
                            <div class="card-header bg-info bg-opacity-10">
                                <h6 class="mb-0 text-info">
                                    <i class="fas fa-link me-2"></i>Reference Information
                                </h6>
                            </div>
                            <div class="card-body">
                                @php
                                    $refType = class_basename($allocation->reference_type ?? '');
                                @endphp
                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="table table-borderless mb-0">
                                            <tr>
                                                <td class="fw-semibold text-muted" style="width: 40%;">Reference Type:</td>
                                                <td>
                                                    <span class="badge bg-secondary">{{ $refType }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="fw-semibold text-muted">Reference ID:</td>
                                                <td>#{{ $allocation->reference_id }}</td>
                                            </tr>
                                            @if ($allocation->reference && isset($allocation->reference->service_name))
                                                <tr>
                                                    <td class="fw-semibold text-muted">Service Name:</td>
                                                    <td>{{ $allocation->reference->service_name }}</td>
                                                </tr>
                                            @endif
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        @if ($allocation->reference && isset($allocation->reference->company))
                                            <table class="table table-borderless mb-0">
                                                <tr>
                                                    <td class="fw-semibold text-muted" style="width: 40%;">Company:</td>
                                                    <td>{{ $allocation->reference->company->name ?? 'N/A' }}</td>
                                                </tr>
                                                @if (isset($allocation->reference->purpose))
                                                    <tr>
                                                        <td class="fw-semibold text-muted">Purpose:</td>
                                                        <td>{{ $allocation->reference->purpose }}</td>
                                                    </tr>
                                                @endif
                                            </table>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Driver Information -->
                    @if ($allocation->getVehicle && $allocation->getVehicle->driverAssignment)
                        <div class="card border mb-4">
                            <div class="card-header bg-warning bg-opacity-10">
                                <h6 class="mb-0 text-warning">
                                    <i class="fas fa-user me-2"></i>Driver Information
                                </h6>
                            </div>
                            <div class="card-body">
                                @php
                                    $driver =
                                        $allocation->getVehicle->driverAssignment->driver ??
                                        $allocation->getVehicle->driverAssignment->getDriver;
                                @endphp
                                @if ($driver)
                                    <div class="d-flex align-items-center mb-3">
                                        @if ($driver->photo_path)
                                            <img src="{{ asset('storage/' . $driver->photo_path) }}" alt="Driver"
                                                class="rounded-circle me-3"
                                                style="width: 80px; height: 80px; object-fit: cover;">
                                        @else
                                            <div class="bg-warning bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3"
                                                style="width: 80px; height: 80px;">
                                                <i class="fas fa-user fa-2x text-warning"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <h5 class="mb-1">
                                                {{ $driver->full_name ?? 'N/A' }}
                                            </h5>
                                            <p class="text-muted mb-0">
                                                <small>{{ $driver->system_id ?? '' }}</small>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table table-borderless mb-0">
                                                <tr>
                                                    <td class="fw-semibold text-muted" style="width: 40%;">Mobile:</td>
                                                    <td>{{ $driver->personal_mobile ?? 'N/A' }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <table class="table table-borderless mb-0">
                                                <tr>
                                                    <td class="fw-semibold text-muted" style="width: 40%;">Email:</td>
                                                    <td>{{ $driver->work_email ?? 'N/A' }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                @else
                                    <p class="text-muted text-center mb-0">No driver assigned to this vehicle</p>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="card border mb-4">
                            <div class="card-header bg-warning bg-opacity-10">
                                <h6 class="mb-0 text-warning">
                                    <i class="fas fa-user me-2"></i>Driver Information
                                </h6>
                            </div>
                            <div class="card-body">
                                <p class="text-muted text-center mb-0">No driver assigned to this vehicle</p>
                            </div>
                        </div>
                    @endif

                    <!-- Route Information -->
                    @if ($allocation->getRoutes && $allocation->getRoutes->count() > 0)
                        <div class="card border mb-4">
                            <div class="card-header bg-secondary bg-opacity-10">
                                <h6 class="mb-0 text-secondary">
                                    <i class="fas fa-route me-2"></i>Route Information
                                </h6>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>#</th>
                                                <th>Start Location</th>
                                                <th>End Location</th>
                                                <th>Description</th>
                                                <th>Distance</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($allocation->getRoutes as $index => $route)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>
                                                        <i class="fas fa-map-marker-alt text-success me-1"></i>
                                                        {{ $route->start_location ?? 'N/A' }}
                                                    </td>
                                                    <td>
                                                        <i class="fas fa-map-marker-alt text-danger me-1"></i>
                                                        {{ $route->end_location ?? 'N/A' }}
                                                    </td>
                                                    <td>{{ $route->description ?? '-' }}</td>
                                                    <td>{{ $route->distance ? $route->distance . ' km' : '-' }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Approval Information -->
                    @if ($allocation->approved_by)
                        <div class="card border mb-4">
                            <div class="card-header bg-success bg-opacity-10">
                                <h6 class="mb-0 text-success">
                                    <i class="fas fa-user-check me-2"></i>Approval Information
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <p class="text-muted mb-1">Approved At</p>
                                        <strong>{{ $allocation->approved_at ? \Carbon\Carbon::parse($allocation->approved_at)->format('d M, Y h:i A') : 'N/A' }}</strong>
                                    </div>
                                    @if ($allocation->approval_remarks)
                                        <div class="col-md-12 mt-3">
                                            <p class="text-muted mb-1">Remarks</p>
                                            <div class="bg-light p-3 rounded">
                                                {{ $allocation->approval_remarks }}
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Timestamps -->
                    <div class="row">
                        <div class="col-md-6">
                            <small class="text-muted">
                                <i class="fas fa-clock me-1"></i>
                                Created: {{ $allocation->created_at->format('d M, Y h:i A') }}
                            </small>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <small class="text-muted">
                                <i class="fas fa-sync me-1"></i>
                                Last Updated: {{ $allocation->updated_at->format('d M, Y h:i A') }}
                            </small>
                        </div>
                    </div>
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
