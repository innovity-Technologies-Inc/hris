@extends('structure.master')
@section('content')
    <div class="row">
        <!-- Vehicle Information Card -->
        <div class="col-12 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary bg-opacity-10 border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-primary">
                            <i data-feather="truck" class="me-2"></i>Vehicle Information
                        </h5>
                        <a href="{{ route('transport.vehicles.index') }}" class="btn btn-sm btn-secondary">
                            <i data-feather="arrow-left" style="width: 14px; height: 14px;"></i> Back to List
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2 text-center">
                            @if ($historyData['vehicle']->vehicle_image)
                                <img src="{{ \App\HelperClass::get_file_url($historyData['vehicle']->vehicle_image) }}"
                                    alt="{{ $historyData['vehicle']->model_number }}" class="img-fluid rounded shadow-sm"
                                    style="max-height: 150px; object-fit: cover;">
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                    style="height: 150px;">
                                    <i data-feather="image" class="text-muted" style="width: 48px; height: 48px;"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-5">
                            <table class="table table-sm table-borderless mb-0">
                                <tr>
                                    <th class="text-muted" style="width: 150px;">Category:</th>
                                    <td><span
                                            class="badge bg-info text-dark">{{ $historyData['vehicle']->vehicle_category }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Model:</th>
                                    <td><strong>{{ $historyData['vehicle']->model_number }}</strong></td>
                                </tr>
                                <tr>
                                    <th class="text-muted">License Number:</th>
                                    <td>{{ $historyData['vehicle']->license_number ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Body Type:</th>
                                    <td>{{ $historyData['vehicle']->body_type ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Seating Capacity:</th>
                                    <td>{{ $historyData['vehicle']->seating_capacity ?? 'N/A' }} Seats</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-5">
                            <table class="table table-sm table-borderless mb-0">
                                <tr>
                                    <th class="text-muted" style="width: 150px;">Status:</th>
                                    <td>
                                        @if ($historyData['vehicle']->status == 'Active')
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Allocation Status:</th>
                                    <td>
                                        @if ($historyData['current_status']['is_allocated'])
                                            <span class="badge bg-danger">Currently Allocated</span>
                                        @else
                                            <span class="badge bg-success">Available</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Created On:</th>
                                    <td>{{ $historyData['created_at']->format('d M Y, h:i A') }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Fuel Type:</th>
                                    <td>{{ $historyData['vehicle']->fuel_type }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">Ownership:</th>
                                    <td>{{ $historyData['vehicle']->ownership_type }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="col-12 mb-4">
            <div class="row g-3">
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-2">Total Allocations</h6>
                                    <h2 class="mb-0 text-primary">{{ $historyData['statistics']['total_allocations'] }}
                                    </h2>
                                </div>
                                <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                    <i data-feather="package" class="text-primary" style="width: 24px; height: 24px;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-2">Total Trips</h6>
                                    <h2 class="mb-0 text-info">{{ $historyData['statistics']['total_trips'] }}</h2>
                                </div>
                                <div class="bg-info bg-opacity-10 rounded-circle p-3">
                                    <i data-feather="map" class="text-info" style="width: 24px; height: 24px;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-2">Total Transports</h6>
                                    <h2 class="mb-0 text-success">{{ $historyData['statistics']['total_transports'] }}</h2>
                                </div>
                                <div class="bg-success bg-opacity-10 rounded-circle p-3">
                                    <i data-feather="users" class="text-success" style="width: 24px; height: 24px;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-2">Drivers Assigned</h6>
                                    <h2 class="mb-0 text-warning">
                                        {{ $historyData['statistics']['total_drivers_assigned'] }}
                                    </h2>
                                </div>
                                <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                                    <i data-feather="user" class="text-warning" style="width: 24px; height: 24px;"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Current Status -->
        @if ($historyData['current_status']['is_allocated'])
            <div class="col-12 mb-4">
                <div class="card border-0 shadow-sm border-start border-danger border-4">
                    <div class="card-header bg-danger bg-opacity-10 border-0">
                        <h6 class="mb-0 text-danger">
                            <i data-feather="alert-circle" class="me-2"></i>Current Allocation Status
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-primary mb-3">Active Allocation Details</h6>
                                @if ($historyData['current_status']['active_allocation'])
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <th class="text-muted" style="width: 150px;">Allocation Name:</th>
                                            <td><strong>{{ $historyData['current_status']['active_allocation']['name'] }}</strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-muted">Start Date:</th>
                                            <td>{{ \Carbon\Carbon::parse($historyData['current_status']['active_allocation']['start_date'])->format('d M Y') }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-muted">End Date:</th>
                                            <td>{{ $historyData['current_status']['active_allocation']['end_date'] ? \Carbon\Carbon::parse($historyData['current_status']['active_allocation']['end_date'])->format('d M Y') : 'Ongoing' }}
                                            </td>
                                        </tr>
                                        @if ($historyData['current_status']['active_allocation']['remaining_days'] !== null)
                                            <tr>
                                                <th class="text-muted">Remaining Time:</th>
                                                <td>
                                                    @php
                                                        $remainingDays =
                                                            $historyData['current_status']['active_allocation'][
                                                                'remaining_days'
                                                            ];
                                                        if ($remainingDays > 0) {
                                                            $endDate = \Carbon\Carbon::parse(
                                                                $historyData['current_status']['active_allocation'][
                                                                    'end_date'
                                                                ],
                                                            );
                                                            $diff = now()->diff($endDate);
                                                            $displayText =
                                                                $diff->days . 'd ' . $diff->h . 'h ' . $diff->i . 'min';
                                                            echo '<span class="badge bg-success">' .
                                                                $displayText .
                                                                '</span>';
                                                        } elseif ($remainingDays == 0) {
                                                            echo '<span class="badge bg-warning">Today</span>';
                                                        } else {
                                                            echo '<span class="badge bg-danger">Expired</span>';
                                                        }
                                                    @endphp
                                                </td>
                                            </tr>
                                        @endif
                                    </table>
                                @endif
                            </div>
                            @if ($historyData['current_status']['assigned_driver'])
                                <div class="col-md-6">
                                    <h6 class="text-primary mb-3">Assigned Driver</h6>
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <th class="text-muted" style="width: 150px;">Driver Name:</th>
                                            <td><strong>{{ $historyData['current_status']['assigned_driver']['driver_name'] }}</strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-muted">Employee ID:</th>
                                            <td>{{ $historyData['current_status']['assigned_driver']['employee_id'] }}</td>
                                        </tr>
                                        <tr>
                                            <th class="text-muted">Assigned Since:</th>
                                            <td>{{ \Carbon\Carbon::parse($historyData['current_status']['assigned_driver']['start_date'])->format('d M Y') }}
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Timeline -->
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary bg-opacity-10 border-0">
                    <h5 class="mb-0 text-primary">
                        <i data-feather="clock" class="me-2"></i>Complete History Timeline
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Tabs -->
                    <ul class="nav nav-tabs mb-4" id="historyTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="allocations-tab" data-bs-toggle="tab"
                                data-bs-target="#allocations" type="button" role="tab">
                                <i data-feather="package" style="width: 14px; height: 14px;"></i> Allocations History
                                <span
                                    class="badge bg-primary ms-2">{{ $historyData['allocation_history']->count() }}</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="drivers-tab" data-bs-toggle="tab" data-bs-target="#drivers"
                                type="button" role="tab">
                                <i data-feather="user" style="width: 14px; height: 14px;"></i> Driver History
                                <span class="badge bg-warning ms-2">{{ $historyData['driver_history']->count() }}</span>
                            </button>
                        </li>
                    </ul>

                    <!-- Tab Content -->
                    <div class="tab-content" id="historyTabsContent">
                        <!-- Allocations History Tab -->
                        <div class="tab-pane fade show active" id="allocations" role="tabpanel">
                            @if ($historyData['allocation_history']->count() > 0)
                                <div class="timeline">
                                    @foreach ($historyData['allocation_history'] as $index => $allocation)
                                        <div class="timeline-item mb-4">
                                            <div class="row">
                                                <div class="col-md-2 text-end">
                                                    <small class="text-muted">
                                                        {{ \Carbon\Carbon::parse($allocation['start_date'])->format('d M Y') }}
                                                    </small>
                                                    <div class="mt-1">
                                                        <span
                                                            class="badge bg-{{ $allocation['status'] == 'Active' ? 'success' : ($allocation['status'] == 'Completed' ? 'info' : 'secondary') }}">
                                                            {{ $allocation['status'] }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-md-1 text-center">
                                                    <div
                                                        class="timeline-badge bg-{{ $allocation['allocation_type'] == 'trip' ? 'info' : 'success' }}">
                                                        <i data-feather="{{ $allocation['allocation_type'] == 'trip' ? 'map-pin' : 'users' }}"
                                                            style="width: 16px; height: 16px;"></i>
                                                    </div>
                                                    @if (!$loop->last)
                                                        <div class="timeline-line"></div>
                                                    @endif
                                                </div>
                                                <div class="col-md-9">
                                                    <div
                                                        class="card border-start border-{{ $allocation['allocation_type'] == 'trip' ? 'info' : 'success' }} border-3">
                                                        <div class="card-body">
                                                            <h6 class="card-title mb-2">
                                                                <span
                                                                    class="badge bg-{{ $allocation['allocation_type'] == 'trip' ? 'info' : 'success' }} me-2">
                                                                    {{ ucfirst($allocation['allocation_type']) }}
                                                                </span>
                                                                {{ $allocation['name'] }}
                                                            </h6>

                                                            @if ($allocation['reference_details'])
                                                                <div class="mb-2">
                                                                    <span
                                                                        class="badge bg-secondary">{{ $allocation['reference_details']['type'] }}</span>
                                                                </div>

                                                                <div class="row small">
                                                                    <div class="col-md-6">
                                                                        @if (isset($allocation['reference_details']['purpose']))
                                                                            <p class="mb-1"><strong>Purpose:</strong>
                                                                                {{ $allocation['reference_details']['purpose'] }}
                                                                            </p>
                                                                        @endif
                                                                        @if (isset($allocation['reference_details']['service_name']))
                                                                            <p class="mb-1"><strong>Service:</strong>
                                                                                {{ $allocation['reference_details']['service_name'] }}
                                                                            </p>
                                                                        @endif
                                                                        @if (isset($allocation['reference_details']['requested_by']))
                                                                            <p class="mb-1"><strong>Requested
                                                                                    By:</strong>
                                                                                {{ $allocation['reference_details']['requested_by'] }}
                                                                                <span
                                                                                    class="text-muted">({{ $allocation['reference_details']['employee_id'] }})</span>
                                                                            </p>
                                                                        @endif
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        @if (isset($allocation['reference_details']['from']) && isset($allocation['reference_details']['to']))
                                                                            <p class="mb-1">
                                                                                <strong>Route:</strong>
                                                                                {{ $allocation['reference_details']['from'] }}
                                                                                →
                                                                                {{ $allocation['reference_details']['to'] }}
                                                                            </p>
                                                                        @endif
                                                                        @if (isset($allocation['reference_details']['passengers']))
                                                                            <p class="mb-1"><strong>Passengers:</strong>
                                                                                {{ $allocation['reference_details']['passengers'] }}
                                                                            </p>
                                                                        @endif
                                                                        @if (isset($allocation['reference_details']['trip_type']))
                                                                            <p class="mb-1"><strong>Type:</strong>
                                                                                <span
                                                                                    class="badge bg-info">{{ $allocation['reference_details']['trip_type'] }}</span>
                                                                            </p>
                                                                        @endif
                                                                        @if (isset($allocation['reference_details']['transport_type']))
                                                                            <p class="mb-1"><strong>Transport
                                                                                    Type:</strong>
                                                                                <span
                                                                                    class="badge bg-info">{{ $allocation['reference_details']['transport_type'] }}</span>
                                                                            </p>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            @endif

                                                            <div class="mt-2 small text-muted">
                                                                <i data-feather="calendar"
                                                                    style="width: 12px; height: 12px;"></i>
                                                                {{ \Carbon\Carbon::parse($allocation['start_date'])->format('d M Y') }}
                                                                -
                                                                {{ $allocation['end_date'] ? \Carbon\Carbon::parse($allocation['end_date'])->format('d M Y') : 'Ongoing' }}
                                                                <span class="ms-2">
                                                                    <i data-feather="clock"
                                                                        style="width: 12px; height: 12px;"></i>
                                                                    {{ $allocation['duration_days'] }} days
                                                                </span>
                                                                @if ($allocation['routes_count'] > 0)
                                                                    <span class="ms-2">
                                                                        <i data-feather="map"
                                                                            style="width: 12px; height: 12px;"></i>
                                                                        {{ $allocation['routes_count'] }} routes
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i data-feather="package" class="text-muted" style="width: 48px; height: 48px;"></i>
                                    <p class="mt-3 text-muted">No allocation history found for this vehicle.</p>
                                </div>
                            @endif
                        </div>

                        <!-- Driver History Tab -->
                        <div class="tab-pane fade" id="drivers" role="tabpanel">
                            @if ($historyData['driver_history']->count() > 0)
                                <div class="timeline">
                                    @foreach ($historyData['driver_history'] as $index => $driver)
                                        <div class="timeline-item mb-4">
                                            <div class="row">
                                                <div class="col-md-2 text-end">
                                                    <small class="text-muted">
                                                        {{ \Carbon\Carbon::parse($driver['start_date'])->format('d M Y') }}
                                                    </small>
                                                    <div class="mt-1">
                                                        <span
                                                            class="badge bg-{{ $driver['status'] == 'active' ? 'success' : 'secondary' }}">
                                                            {{ ucfirst($driver['status']) }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="col-md-1 text-center">
                                                    <div class="timeline-badge bg-warning">
                                                        {!! \App\HelperClass::generateAvatar($driver['driver_photo'] ?? null, $driver['driver_name'], 40, '#ff9800') !!}
                                                    </div>
                                                    @if (!$loop->last)
                                                        <div class="timeline-line"></div>
                                                    @endif
                                                </div>
                                                <div class="col-md-9">
                                                    <div class="card border-start border-warning border-3">
                                                        <div class="card-body">
                                                            <h6 class="card-title mb-2">
                                                                <i data-feather="user"
                                                                    style="width: 16px; height: 16px;"></i>
                                                                {{ $driver['driver_name'] }}
                                                            </h6>
                                                            <p class="mb-2 small">
                                                                <strong>Employee ID:</strong>
                                                                <span
                                                                    class="badge bg-light text-dark">{{ $driver['employee_system_id'] }}</span>
                                                            </p>
                                                            <div class="small text-muted">
                                                                <i data-feather="calendar"
                                                                    style="width: 12px; height: 12px;"></i>
                                                                {{ \Carbon\Carbon::parse($driver['start_date'])->format('d M Y') }}
                                                                -
                                                                {{ $driver['end_date'] ? \Carbon\Carbon::parse($driver['end_date'])->format('d M Y') : 'Present' }}
                                                                <span class="ms-2">
                                                                    <i data-feather="clock"
                                                                        style="width: 12px; height: 12px;"></i>
                                                                    {{ $driver['duration_days'] }} days
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i data-feather="user" class="text-muted" style="width: 48px; height: 48px;"></i>
                                    <p class="mt-3 text-muted">No driver assignment history found for this vehicle.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .timeline {
            position: relative;
        }

        .timeline-badge {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            margin: 0 auto;
        }

        .timeline-line {
            width: 2px;
            height: calc(100% + 20px);
            background-color: #e0e0e0;
            margin: 10px auto 0;
        }

        .timeline-item:last-child .timeline-line {
            display: none;
        }
    </style>
@endsection

