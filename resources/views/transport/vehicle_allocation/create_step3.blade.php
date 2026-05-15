@extends('structure.master')
@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-check-circle me-2"></i>
                            <h5 class="mb-0">New Vehicle Allocation - Step 3</h5>
                        </div>
                        <a href="{{ route('transport.vehicle_allocations.dashboard') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-times me-1"></i>Cancel
                        </a>
                    </div>
                </div>

                <!-- Progress Steps -->
                <div class="card-body border-bottom bg-light">
                    <div class="d-flex justify-content-between align-items-center position-relative">
                        <div class="progress position-absolute" style="height: 2px; width: 100%; z-index: 0;">
                            <div class="progress-bar bg-primary" style="width: 100%;"></div>
                        </div>
                        <div class="step-item text-center position-relative" style="z-index: 1;">
                            <div class="step-circle bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center"
                                style="width: 40px; height: 40px;">
                                <i class="fas fa-check"></i>
                            </div>
                            <small class="d-block mt-1 text-primary">Select Type</small>
                        </div>
                        <div class="step-item text-center position-relative" style="z-index: 1;">
                            <div class="step-circle bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center"
                                style="width: 40px; height: 40px;">
                                <i class="fas fa-check"></i>
                            </div>
                            <small class="d-block mt-1 text-primary">Select Vehicle</small>
                        </div>
                        <div class="step-item text-center position-relative" style="z-index: 1;">
                            <div class="step-circle bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center"
                                style="width: 40px; height: 40px;">
                                <i class="fas fa-check"></i>
                            </div>
                            <small class="d-block mt-1 fw-semibold text-primary">Confirm</small>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <h6 class="text-primary mb-4">
                        <i class="fas fa-clipboard-check me-2"></i>Review & Confirm Allocation
                    </h6>

                    <form action="{{ route('transport.vehicle_allocations.store') }}" method="POST">
                        @csrf

                        <!-- Pass through all data -->
                        <input type="hidden" name="allocation_type" value="{{ $allocationData['allocation_type'] ?? '' }}">
                        <input type="hidden" name="reference_type" value="{{ $allocationData['reference_type'] ?? '' }}">
                        <input type="hidden" name="reference_id" value="{{ $allocationData['reference_id'] ?? '' }}">
                        <input type="hidden" name="name" value="{{ $allocationData['name'] ?? '' }}">
                        <input type="hidden" name="start_date" value="{{ $allocationData['start_date'] ?? '' }}">
                        <input type="hidden" name="end_date" value="{{ $allocationData['end_date'] ?? '' }}">
                        @foreach ($selectedVehicles ?? [] as $vehicle)
                            <input type="hidden" name="vehicle_ids[]" value="{{ $vehicle->id }}">
                        @endforeach

                        <!-- Allocation Summary Card -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-primary bg-opacity-10">
                                <h6 class="mb-0 text-primary">
                                    <i class="fas fa-info-circle me-2"></i>Allocation Details
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="table table-borderless mb-0">
                                            <tr>
                                                <td class="fw-semibold text-muted" style="width: 40%;">Allocation Type:</td>
                                                <td>
                                                    <span
                                                        class="badge bg-primary">{{ $allocationData['allocation_type'] ?? 'N/A' }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="fw-semibold text-muted">Allocation Name:</td>
                                                <td>{{ $allocationData['name'] ?? 'Auto-generated' }}</td>
                                            </tr>
                                            @if (isset($reference))
                                                <tr>
                                                    <td class="fw-semibold text-muted">Reference:</td>
                                                    <td>{{ $reference->service_name ?? ($reference->purpose ?? 'N/A') }}
                                                    </td>
                                                </tr>
                                            @endif
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <table class="table table-borderless mb-0">
                                            <tr>
                                                <td class="fw-semibold text-muted" style="width: 40%;">Start Date:</td>
                                                <td>{{ isset($allocationData['start_date']) ? \Carbon\Carbon::parse($allocationData['start_date'])->format('d M, Y') : 'N/A' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="fw-semibold text-muted">End Date:</td>
                                                <td>{{ isset($allocationData['end_date']) && $allocationData['end_date'] ? \Carbon\Carbon::parse($allocationData['end_date'])->format('d M, Y') : 'Not Set (Permanent)' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="fw-semibold text-muted">Total Vehicles:</td>
                                                <td><span
                                                        class="badge bg-success">{{ count($selectedVehicles ?? []) }}</span>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Application Details -->
                        @if (isset($reference))
                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-header bg-primary text-white">
                                    <h6 class="mb-0 text-white">
                                        <i class="fas fa-file-alt me-2"></i>
                                        {{ $allocationData['allocation_type'] == 'trip_based' ? 'Trip Requisition Details' : 'Employee Transport Details' }}
                                    </h6>
                                </div>
                                <div class="card-body">
                                    @if ($allocationData['allocation_type'] == 'trip_based')
                                        <!-- Trip Requisition Details -->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <table class="table table-borderless mb-0 table-sm">
                                                    <tr>
                                                        <td class="fw-semibold text-muted" style="width: 45%;">Purpose:</td>
                                                        <td>{{ $reference->purpose_of_travel ?? 'N/A' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-semibold text-muted">Trip Type:</td>
                                                        <td>
                                                            <span
                                                                class="badge bg-primary">{{ $reference->trip_type ?? 'N/A' }}</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-semibold text-muted">Trip Mode:</td>
                                                        <td>{{ $reference->trip_mode ?? 'N/A' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-semibold text-muted">Passengers:</td>
                                                        <td>
                                                            <span class="badge bg-primary">
                                                                <i
                                                                    class="fas fa-users me-1"></i>{{ $reference->no_of_passengers ?? 0 }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <div class="col-md-6">
                                                <table class="table table-borderless mb-0 table-sm">
                                                    <tr>
                                                        <td class="fw-semibold text-muted" style="width: 45%;">Start
                                                            Date/Time:</td>
                                                        <td>
                                                            @if ($reference->start_date_time)
                                                                {{ is_object($reference->start_date_time) ? $reference->start_date_time->format('d M Y, H:i') : \Carbon\Carbon::parse($reference->start_date_time)->format('d M Y, H:i') }}
                                                            @else
                                                                <span class="text-muted">N/A</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-semibold text-muted">End Date/Time:</td>
                                                        <td>
                                                            @if ($reference->end_date_time)
                                                                {{ is_object($reference->end_date_time) ? $reference->end_date_time->format('d M Y, H:i') : \Carbon\Carbon::parse($reference->end_date_time)->format('d M Y, H:i') }}
                                                            @else
                                                                <span class="text-muted">N/A</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-semibold text-muted">From:</td>
                                                        <td>{{ $reference->pickup_location ?? 'N/A' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-semibold text-muted">To:</td>
                                                        <td>{{ $reference->destination ?? 'N/A' }}</td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    @else
                                        <!-- Employee Transport Details -->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <table class="table table-borderless mb-0 table-sm">
                                                    <tr>
                                                        <td class="fw-semibold text-muted" style="width: 45%;">Service
                                                            Name:</td>
                                                        <td><strong>{{ $reference->service_name ?? 'N/A' }}</strong></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-semibold text-muted">Transport Type:</td>
                                                        <td>
                                                            <span
                                                                class="badge bg-info">{{ $reference->transport_type ?? 'N/A' }}</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-semibold text-muted">Purpose:</td>
                                                        <td>{{ $reference->purpose ?? 'N/A' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-semibold text-muted">Est. Passengers:</td>
                                                        <td>
                                                            <span class="badge bg-primary">
                                                                <i
                                                                    class="fas fa-users me-1"></i>{{ $reference->estimated_passengers ?? 0 }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <div class="col-md-6">
                                                <table class="table table-borderless mb-0 table-sm">
                                                    <tr>
                                                        <td class="fw-semibold text-muted" style="width: 45%;">From:</td>
                                                        <td>{{ $reference->pickup_location ?? 'N/A' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-semibold text-muted">To:</td>
                                                        <td>{{ $reference->drop_location ?? 'N/A' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-semibold text-muted">Operating Days:</td>
                                                        <td>{{ $reference->operating_days ?? 'N/A' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-semibold text-muted">Status:</td>
                                                        <td>
                                                            <span
                                                                class="badge bg-success">{{ $reference->status ?? 'N/A' }}</span>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Selected Vehicles List -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-primary bg-opacity-10">
                                <h6 class="mb-0 text-primary">
                                    <i class="fas fa-car-side me-2"></i>Selected Vehicles
                                </h6>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>#</th>
                                                <th>Image</th>
                                                <th>Vehicle</th>
                                                <th>Model</th>
                                                <th>Type</th>
                                                <th>Capacity</th>
                                                <th>Driver</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $totalCapacity = 0; @endphp
                                            @foreach ($selectedVehicles ?? [] as $index => $vehicle)
                                                @php $totalCapacity += $vehicle->seating_capacity ?? 0; @endphp
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>
                                                        @if ($vehicle->vehicle_image)
                                                            <img src="{{ asset('storage/' . $vehicle->vehicle_image) }}"
                                                                alt="Vehicle" class="rounded"
                                                                style="width: 50px; height: 50px; object-fit: cover;">
                                                        @else
                                                            <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                                                style="width: 50px; height: 50px;">
                                                                <i class="fas fa-car text-muted"></i>
                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <strong>{{ $vehicle->license_number }}</strong>
                                                    </td>
                                                    <td>{{ $vehicle->model_number ?? ($vehicle->vehicle_category ?? 'N/A') }}
                                                    </td>
                                                    <td>{{ $vehicle->vehicle_category ?? 'N/A' }}</td>
                                                    <td>
                                                        <span class="badge bg-primary">
                                                            <i
                                                                class="fas fa-users me-1"></i>{{ $vehicle->seating_capacity ?? '-' }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        @php
                                                            $driver =
                                                                $vehicle->driverAssignment?->driver ??
                                                                $vehicle->driverAssignment?->getDriver;
                                                        @endphp
                                                        @if ($driver)
                                                            <div class="d-flex align-items-center">
                                                                {!! \App\HelperClass::generateAvatar(
                                                                    $driver->photo_path ?? null,
                                                                    $driver->full_name ?? 'N/A',
                                                                    30,
                                                                    '#974063',
                                                                    'me-2',
                                                                    $driver->id,
                                                                ) !!}
                                                                <div>
                                                                    <a href="{{ route('employees.profile.general_informations', $driver->id) }}"
                                                                        class="text-decoration-none">
                                                                        <div class="fw-semibold text-dark">
                                                                            {{ $driver->full_name ?? 'N/A' }}</div>
                                                                    </a>
                                                                    <small
                                                                        class="text-muted">{{ $driver->system_id ?? '' }}</small>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <span class="text-muted">No driver assigned</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot class="table-light">
                                            <tr>
                                                <td colspan="5" class="text-end fw-bold">Total Capacity:</td>
                                                <td colspan="2">
                                                    <span class="badge bg-primary fs-6">
                                                        <i class="fas fa-users me-1"></i>{{ $totalCapacity }} passengers
                                                    </span>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Route Details (Optional) -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-primary bg-opacity-10">
                                <h6 class="mb-0 text-primary">
                                    <i class="fas fa-route me-2"></i>Route Details (Optional)
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">Start Location</label>
                                        <input type="text" name="route_start" class="form-control"
                                            placeholder="e.g., Company Office"
                                            value="{{ $reference->pickup_location ?? old('route_start') }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">End Location</label>
                                        <input type="text" name="route_end" class="form-control"
                                            placeholder="e.g., Industrial Area"
                                            value="{{ $reference->drop_location ?? old('route_end') }}">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label fw-semibold">Distance (km)</label>
                                        <input type="number" name="distance_km" class="form-control" step="0.01"
                                            placeholder="e.g., 50.5" value="{{ old('distance_km') }}">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label fw-semibold">Estimated Duration (min)</label>
                                        <input type="number" name="estimated_duration_minutes" class="form-control"
                                            placeholder="e.g., 60" value="{{ old('estimated_duration_minutes') }}">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label fw-semibold">Departure Time</label>
                                        <input type="time" name="departure_time" class="form-control"
                                            value="{{ old('departure_time') }}">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label fw-semibold">Arrival Time</label>
                                        <input type="time" name="arrival_time" class="form-control"
                                            value="{{ old('arrival_time') }}">
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label fw-semibold">Route Description</label>
                                        <textarea name="route_description" class="form-control" rows="2"
                                            placeholder="Describe the route with stops, highways, important landmarks...">{{ old('route_description') }}</textarea>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label fw-semibold">Special Instructions</label>
                                        <textarea name="special_instructions" class="form-control" rows="2"
                                            placeholder="e.g., Avoid rush hour traffic, Use alternate route on Fridays, Contact dispatch on arrival...">{{ old('special_instructions') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Approval Remarks -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-primary bg-opacity-10">
                                <h6 class="mb-0 text-primary">
                                    <i class="fas fa-comment me-2"></i>Remarks
                                </h6>
                            </div>
                            <div class="card-body">
                                <textarea name="remarks" class="form-control" rows="3" placeholder="Add any remarks for this allocation...">{{ $allocationData['remarks'] ?? old('remarks') }}</textarea>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex justify-content-between gap-2 border-top pt-4 mt-4">
                            <a href="{{ route('transport.vehicle_allocations.step2') }}" class="btn btn-secondary px-4">
                                <i class="fas fa-arrow-left me-1"></i>Previous Step
                            </a>
                            <button type="submit" class="btn btn-primary px-5">
                                <i class="fas fa-check-circle me-1"></i>Confirm & Create Allocation
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
