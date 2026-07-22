@extends('structure.master')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-bus me-2"></i>
                            <h5 class="mb-0">Employee Transport Service Details</h5>
                        </div>
                        <div>
                            <a href="{{ route('transport.employee_transports.index') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-arrow-left me-1"></i>Back to List
                            </a>
                            @if ($employeeTransport->status == 'Pending')
                                <a href="{{ route('transport.employee_transports.edit', $employeeTransport->id) }}"
                                    class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit me-1"></i>Edit
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <!-- Status Badge -->
                    <div class="mb-4 text-center">
                        @php
                            $statusClass = match ($employeeTransport->status) {
                                'Pending' => 'warning',
                                'Approved' => 'success',
                                'Rejected' => 'danger',
                                'Allocated' => 'info',
                                'Completed' => 'primary',
                                'Cancelled' => 'secondary',
                                default => 'secondary',
                            };
                        @endphp
                        <span class="badge bg-{{ $statusClass }} fs-6 px-4 py-2">
                            <i class="fas fa-circle me-1"></i>{{ $employeeTransport->status }}
                        </span>
                    </div>

                    <!-- Service Information -->
                    <div class="mb-4">
                        <h6 class="text-primary border-bottom pb-2 mb-3">
                            <i class="fas fa-info-circle me-2"></i>Service Information
                        </h6>
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td class="fw-semibold text-muted" style="width: 40%;">Service Name:</td>
                                        <td>{{ $employeeTransport->service_name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold text-muted">Company:</td>
                                        <td>{{ $employeeTransport->getCompany->name ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold text-muted">Transport Type:</td>
                                        <td>
                                            <span class="badge bg-primary">{{ $employeeTransport->transport_type }}</span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td class="fw-semibold text-muted" style="width: 40%;">Estimated Passengers:</td>
                                        <td>
                                            <i class="fas fa-users text-primary me-1"></i>
                                            {{ $employeeTransport->estimated_passengers ?? 'Not specified' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold text-muted">Created At:</td>
                                        <td>{{ $employeeTransport->created_at->format('d M, Y h:i A') }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-semibold text-muted">Updated At:</td>
                                        <td>{{ $employeeTransport->updated_at->format('d M, Y h:i A') }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Purpose -->
                    <div class="mb-4">
                        <h6 class="text-primary border-bottom pb-2 mb-3">
                            <i class="fas fa-clipboard me-2"></i>Purpose
                        </h6>
                        <div class="bg-light p-3 rounded">
                            {{ $employeeTransport->purpose }}
                        </div>
                    </div>

                    <!-- Schedule Information -->
                    <div class="mb-4">
                        <h6 class="text-primary border-bottom pb-2 mb-3">
                            <i class="fas fa-calendar-alt me-2"></i>Schedule Information
                        </h6>
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <div class="card bg-light border-0">
                                    <div class="card-body text-center py-3">
                                        <i class="fas fa-calendar-check text-success fa-2x mb-2"></i>
                                        <p class="text-muted mb-1 small">Start Date</p>
                                        <h6 class="mb-0">{{ $employeeTransport->start_date->format('d M, Y') }}</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="card bg-light border-0">
                                    <div class="card-body text-center py-3">
                                        <i class="fas fa-calendar-times text-danger fa-2x mb-2"></i>
                                        <p class="text-muted mb-1 small">End Date</p>
                                        <h6 class="mb-0">{{ $employeeTransport->end_date->format('d M, Y') }}</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="card bg-light border-0">
                                    <div class="card-body text-center py-3">
                                        <i class="fas fa-clock text-info fa-2x mb-2"></i>
                                        <p class="text-muted mb-1 small">Pickup Time</p>
                                        <h6 class="mb-0">{{ $employeeTransport->pickup_time ?? 'Not Set' }}</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="card bg-light border-0">
                                    <div class="card-body text-center py-3">
                                        <i class="fas fa-clock text-warning fa-2x mb-2"></i>
                                        <p class="text-muted mb-1 small">Drop Time</p>
                                        <h6 class="mb-0">{{ $employeeTransport->drop_time ?? 'Not Set' }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Route Information -->
                    <div class="mb-4">
                        <h6 class="text-primary border-bottom pb-2 mb-3">
                            <i class="fas fa-route me-2"></i>Route Map Details
                        </h6>
                        @if ($employeeTransport->routeMap)
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <small class="text-muted d-block">Route Name</small>
                                    <strong>{{ $employeeTransport->routeMap->route_name }}</strong>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <small class="text-muted d-block">Status</small>
                                    <span class="badge {{ $employeeTransport->routeMap->status_badge_class }}">{{ $employeeTransport->routeMap->status }}</span>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="d-flex align-items-start">
                                        <div class="bg-success text-white rounded-circle p-2 me-3">
                                            <i class="fas fa-map-marker-alt"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">Start Point</small>
                                            <strong>{{ $employeeTransport->routeMap->start_point }}</strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="d-flex align-items-start">
                                        <div class="bg-danger text-white rounded-circle p-2 me-3">
                                            <i class="fas fa-map-marker-alt"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">End Point</small>
                                            <strong>{{ $employeeTransport->routeMap->end_point }}</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if ($employeeTransport->routeMap->via_points && is_array($employeeTransport->routeMap->via_points) && count($employeeTransport->routeMap->via_points) > 0)
                                <div class="bg-light p-3 rounded mt-2">
                                    <small class="text-muted d-block mb-1">Via Points / Stopovers</small>
                                    <div class="d-flex flex-wrap gap-1 mt-1">
                                        @foreach($employeeTransport->routeMap->via_points as $point)
                                            <span class="badge bg-warning text-dark p-2" style="font-size: 0.85rem;">{{ $point }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            @if ($employeeTransport->routeMap->route_details)
                                <div class="bg-light p-3 rounded mt-2">
                                    <small class="text-muted d-block mb-1">Route Details / Directions</small>
                                    {{ $employeeTransport->routeMap->route_details }}
                                </div>
                            @endif
                        @else
                            <div class="text-muted">No Route Map associated.</div>
                        @endif
                    </div>

                    <!-- Special Requirements -->
                    @if ($employeeTransport->special_requirements)
                        <div class="mb-4">
                            <h6 class="text-warning border-bottom pb-2 mb-3">
                                <i class="fas fa-exclamation-triangle me-2"></i>Special Requirements
                            </h6>
                            <div class="bg-warning bg-opacity-10 p-3 rounded border-start border-warning border-3">
                                {{ $employeeTransport->special_requirements }}
                            </div>
                        </div>
                    @endif

                    <!-- Remarks -->
                    @if ($employeeTransport->remarks)
                        <div class="mb-4">
                            <h6 class="text-secondary border-bottom pb-2 mb-3">
                                <i class="fas fa-comment me-2"></i>Remarks
                            </h6>
                            <div class="bg-light p-3 rounded">
                                {{ $employeeTransport->remarks }}
                            </div>
                        </div>
                    @endif

                    <!-- Approval Information -->
                    @if ($employeeTransport->status != 'Pending')
                        <div class="mb-4">
                            <h6 class="text-info border-bottom pb-2 mb-3">
                                <i class="fas fa-user-check me-2"></i>Approval Information
                            </h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <p class="text-muted mb-1">Approved At</p>
                                    <strong>{{ $employeeTransport->approved_at ? $employeeTransport->approved_at->format('d M, Y h:i A') : 'N/A' }}</strong>
                                </div>
                                @if ($employeeTransport->approval_remarks)
                                    <div class="col-md-12 mt-3">
                                        <p class="text-muted mb-1">Approval Remarks</p>
                                        <div class="bg-light p-3 rounded">
                                            {{ $employeeTransport->approval_remarks }}
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Vehicle Allocation Details -->
                    @if ($employeeTransport->allocations && $employeeTransport->allocations->count() > 0)
                        <div class="mb-4">
                            <h6 class="text-success border-bottom pb-2 mb-3">
                                <i class="fas fa-car-side me-2"></i>Allocated Vehicles & Drivers
                            </h6>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Vehicle</th>
                                            <th>Driver</th>
                                            <th>Allocation Purpose</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($employeeTransport->allocations as $allocation)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        @if ($allocation->vehicle && $allocation->vehicle->vehicle_image)
                                                            <img src="{{ \App\HelperClass::get_file_url($allocation->vehicle->vehicle_image) }}"
                                                                alt="Vehicle" class="rounded me-2"
                                                                style="width: 60px; height: 60px; object-fit: cover;">
                                                        @else
                                                            <div class="bg-secondary rounded me-2 d-flex align-items-center justify-content-center"
                                                                style="width: 60px; height: 60px;">
                                                                <i class="fas fa-car text-white fa-2x"></i>
                                                            </div>
                                                        @endif
                                                        <div>
                                                            <strong
                                                                class="d-block">{{ $allocation->vehicle->license_number ?? 'N/A' }}</strong>
                                                            <small
                                                                class="text-muted">{{ $allocation->vehicle->vehicle_category ?? '' }}</small><br>
                                                            <small
                                                                class="text-muted">{{ $allocation->vehicle->model_number ?? '' }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if ($allocation->assigned_driver)
                                                        <div class="d-flex align-items-center">
                                                            {!! \App\HelperClass::generateAvatar(
                                                                $allocation->assigned_driver->photo_path ?? null,
                                                                $allocation->assigned_driver->full_name ?? ($allocation->assigned_driver->name ?? 'N/A'),
                                                                50,
                                                                '#974063',
                                                                'me-2',
                                                                $allocation->assigned_driver->id,
                                                            ) !!}
                                                            <div>
                                                                <a href="{{ route('employee.profile.general_informations', $allocation->assigned_driver->id) }}"
                                                                    class="text-decoration-none">
                                                                    <strong
                                                                        class="d-block text-dark">{{ $allocation->assigned_driver->full_name ?? $allocation->assigned_driver->name }}</strong>
                                                                </a>
                                                                <small class="text-muted">Driver</small>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <span class="text-muted">Not Assigned</span>
                                                    @endif
                                                </td>
                                                <td>{{ $allocation->allocation_purpose ?? $allocation->allocation_type }}
                                                </td>
                                                <td>{{ $allocation->start_date ? $allocation->start_date->format('d M, Y') : 'N/A' }}
                                                </td>
                                                <td>{{ $allocation->end_date ? $allocation->end_date->format('d M, Y') : 'N/A' }}
                                                </td>
                                                <td>
                                                    @php
                                                        $allocStatusClass = match ($allocation->status) {
                                                            'Active' => 'success',
                                                            'Completed' => 'info',
                                                            'Inactive' => 'secondary',
                                                            default => 'secondary',
                                                        };
                                                    @endphp
                                                    <span class="badge bg-{{ $allocStatusClass }}">
                                                        {{ $allocation->status }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif


                </div>
            </div>
        </div>
    </div>
@endsection



