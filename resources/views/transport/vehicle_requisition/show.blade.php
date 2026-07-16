@extends('structure.master')

@section('content')
    <style>
        .detail-card {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .detail-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .section-header {
            background: var(--primary-color);
            color: white;
            padding: 15px 20px;
        }

        .detail-row {
            padding: 12px 20px;
            border-bottom: 1px solid #f0f0f0;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            font-weight: 600;
            color: var(--bs-secondary-color);
            font-size: 0.875rem;
        }

        .detail-value {
            color: var(--bs-body-color);
            font-weight: 500;
        }

        .preview-card {
            background: var(--bs-body-bg);
            border: 1px solid var(--bs-border-color);
            border-radius: 12px;
            overflow: hidden;
        }

        .preview-header {
            padding: 15px 20px;
            border-bottom: 1px solid var(--bs-border-color);
        }
    </style>

    <div class="row">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm rounded">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0 text-white">
                            <i data-feather="clipboard" class="me-2"></i>
                            Vehicle Requisition Details
                        </h5>
                        <a href="{{ route('transport.vehicle_requisitions.index') }}" class="btn btn-light btn-sm">
                            <i data-feather="arrow-left" style="width: 14px; height: 14px;"></i> Back to List
                        </a>
                    </div>
                </div>

                <div class="card-body p-4">
                    {{-- Status Banner --}}
                    <div
                        class="alert alert-{{ $vehicleRequisition->approval_status === 'Approved' ? 'success' : ($vehicleRequisition->approval_status === 'Rejected' ? 'danger' : 'warning') }} d-flex align-items-center mb-4">
                        <i data-feather="{{ $vehicleRequisition->approval_status === 'Approved' ? 'check-circle' : ($vehicleRequisition->approval_status === 'Rejected' ? 'x-circle' : 'clock') }}"
                            class="me-2"></i>
                        <div>
                            <strong>Status:</strong> {{ $vehicleRequisition->approval_status }}
                            @if ($vehicleRequisition->approval_remarks)
                                <span class="ms-2">| {{ $vehicleRequisition->approval_remarks }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="row">
                        {{-- Left Column --}}
                        <div class="col-lg-6">
                            {{-- Basic Details --}}
                            <div class="detail-card mb-4">
                                <div class="section-header">
                                    <h6 class="mb-0">
                                        <i data-feather="user" style="width: 18px; height: 18px;"></i>
                                        Basic Details
                                    </h6>
                                </div>
                                <div class="detail-row">
                                    <div class="row">
                                        <div class="col-5 detail-label">Requisition ID</div>
                                        <div class="col-7 detail-value">#{{ $vehicleRequisition->id }}</div>
                                    </div>
                                </div>
                                <div class="detail-row">
                                    <div class="row">
                                        <div class="col-5 detail-label">Employee</div>
                                        <div class="col-7 detail-value">
                                            @if ($vehicleRequisition->getEmployee)
                                                <div class="d-flex align-items-center">
                                                    {!! \App\HelperClass::generateAvatar(
                                                        null,
                                                        $vehicleRequisition->getEmployee->full_name ?? 'N/A',
                                                        40,
                                                        '#974063',
                                                        'me-2',
                                                        $vehicleRequisition->employee_id,
                                                    ) !!}
                                                    <div>
                                                        <a href="{{ route('employee.profile.general_informations', $vehicleRequisition->employee_id) }}"
                                                            class="text-decoration-none text-dark">
                                                            {{ $vehicleRequisition->getEmployee->full_name }}
                                                        </a>
                                                        <br><small class="text-muted">ID:
                                                            {{ $vehicleRequisition->getEmployee->system_id }}</small>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="detail-row">
                                    <div class="row">
                                        <div class="col-5 detail-label">Department</div>
                                        <div class="col-7 detail-value">
                                            {{ $vehicleRequisition->getDepartment?->department_name ?? '-' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="detail-row">
                                    <div class="row">
                                        <div class="col-5 detail-label">Submitted On</div>
                                        <div class="col-7 detail-value">
                                            {{ $vehicleRequisition->created_at->format('d M Y, h:i A') }}</div>
                                    </div>
                                </div>
                            </div>

                            {{-- Trip Details --}}
                            <div class="detail-card mb-4">
                                <div class="section-header">
                                    <h6 class="mb-0">
                                        <i data-feather="navigation" style="width: 18px; height: 18px;"></i>
                                        Trip Details
                                    </h6>
                                </div>
                                <div class="detail-row">
                                    <div class="row">
                                        <div class="col-5 detail-label">Trip Type</div>
                                        <div class="col-7 detail-value">
                                            <span
                                                class="badge bg-info text-dark">{{ $vehicleRequisition->trip_type }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="detail-row">
                                    <div class="row">
                                        <div class="col-5 detail-label">Trip Mode</div>
                                        <div class="col-7 detail-value">{{ $vehicleRequisition->trip_mode }}</div>
                                    </div>
                                </div>
                                <div class="detail-row">
                                    <div class="row">
                                        <div class="col-5 detail-label">Passengers</div>
                                        <div class="col-7 detail-value">{{ $vehicleRequisition->no_of_passengers }}</div>
                                    </div>
                                </div>
                                <div class="detail-row">
                                    <div class="row">
                                        <div class="col-5 detail-label">Purpose</div>
                                        <div class="col-7 detail-value">{{ $vehicleRequisition->purpose_of_travel }}</div>
                                    </div>
                                </div>
                            </div>

                            {{-- Schedule --}}
                            <div class="detail-card mb-4">
                                <div class="section-header">
                                    <h6 class="mb-0">
                                        <i data-feather="calendar" style="width: 18px; height: 18px;"></i>
                                        Schedule
                                    </h6>
                                </div>
                                <div class="detail-row">
                                    <div class="row">
                                        <div class="col-5 detail-label">Start Date & Time</div>
                                        <div class="col-7 detail-value">
                                            {{ $vehicleRequisition->start_date_time ? $vehicleRequisition->start_date_time->format('d M Y, h:i A') : '-' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="detail-row">
                                    <div class="row">
                                        <div class="col-5 detail-label">End Date & Time</div>
                                        <div class="col-7 detail-value">
                                            {{ $vehicleRequisition->end_date_time ? $vehicleRequisition->end_date_time->format('d M Y, h:i A') : '-' }}
                                        </div>
                                    </div>
                                </div>
                                @if ($vehicleRequisition->dispatch_time)
                                    <div class="detail-row">
                                        <div class="row">
                                            <div class="col-5 detail-label">Dispatch Time</div>
                                            <div class="col-7 detail-value">{{ $vehicleRequisition->dispatch_time }}</div>
                                        </div>
                                    </div>
                                @endif
                                @if ($vehicleRequisition->expected_return_time)
                                    <div class="detail-row">
                                        <div class="row">
                                            <div class="col-5 detail-label">Expected Return</div>
                                            <div class="col-7 detail-value">{{ $vehicleRequisition->expected_return_time }}
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Right Column --}}
                        <div class="col-lg-6">
                            {{-- Locations --}}
                            <div class="detail-card mb-4">
                                <div class="section-header">
                                    <h6 class="mb-0">
                                        <i data-feather="map-pin" style="width: 18px; height: 18px;"></i>
                                        Locations
                                    </h6>
                                </div>
                                <div class="detail-row">
                                    <div class="row">
                                        <div class="col-5 detail-label">Pickup Location</div>
                                        <div class="col-7 detail-value">{{ $vehicleRequisition->pickup_location }}</div>
                                    </div>
                                </div>
                                <div class="detail-row">
                                    <div class="row">
                                        <div class="col-5 detail-label">Destination</div>
                                        <div class="col-7 detail-value">{{ $vehicleRequisition->destination }}</div>
                                    </div>
                                </div>
                                <div class="detail-row">
                                    <div class="row">
                                        <div class="col-5 detail-label">Route</div>
                                        <div class="col-7 detail-value">{{ $vehicleRequisition->route ?? '-' }}</div>
                                    </div>
                                </div>
                            </div>

                            {{-- Vehicle Preferences --}}
                            <div class="detail-card mb-4">
                                <div class="section-header">
                                    <h6 class="mb-0">
                                        <i data-feather="truck" style="width: 18px; height: 18px;"></i>
                                        Vehicle Preferences
                                    </h6>
                                </div>
                                <div class="detail-row">
                                    <div class="row">
                                        <div class="col-5 detail-label">Vehicle Type</div>
                                        <div class="col-7 detail-value">
                                            <span
                                                class="badge bg-secondary">{{ $vehicleRequisition->vehicle_type_required }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="detail-row">
                                    <div class="row">
                                        <div class="col-5 detail-label">Preferred Vehicle</div>
                                        <div class="col-7 detail-value">
                                            {{ $vehicleRequisition->preferred_vehicle ?? '-' }}</div>
                                    </div>
                                </div>
                                <div class="detail-row">
                                    <div class="row">
                                        <div class="col-5 detail-label">Special Requirements</div>
                                        <div class="col-7 detail-value">
                                            {{ $vehicleRequisition->special_requirement ?? '-' }}</div>
                                    </div>
                                </div>
                                @if ($vehicleRequisition->driver_required)
                                    <div class="detail-row">
                                        <div class="row">
                                            <div class="col-5 detail-label">Driver Required</div>
                                            <div class="col-7 detail-value">
                                                <span class="badge bg-success">Yes</span>
                                            </div>
                                        </div>
                                    </div>
                                @elseif ($vehicleRequisition->self_drive)
                                    <div class="detail-row">
                                        <div class="row">
                                            <div class="col-5 detail-label">Self Drive</div>
                                            <div class="col-7 detail-value">
                                                <span class="badge bg-info">Yes</span>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            {{-- Assigned Vehicle Preview (if approved) --}}
                            @if ($vehicleRequisition->approval_status === 'Approved' && $vehicleRequisition->getAssignedVehicle)
                                <div class="preview-card mb-4">
                                    <div class="preview-header bg-success text-white">
                                        <h6 class="mb-0 fw-bold">
                                            <i data-feather="truck" style="width: 18px; height: 18px;"></i>
                                            Assigned Vehicle
                                        </h6>
                                    </div>
                                    <div class="card-body p-3">
                                        <div class="d-flex align-items-start">
                                            <div class="me-3">
                                                @if ($vehicleRequisition->getAssignedVehicle->vehicle_image)
                                                    <img src="{{ asset('storage/' . $vehicleRequisition->getAssignedVehicle->vehicle_image) }}"
                                                        alt="Vehicle" class="rounded-3 border border-success border-2"
                                                        width="80" height="80" style="object-fit: cover;">
                                                @else
                                                    <div class="border border-success border-2 rounded-3 bg-light d-flex align-items-center justify-content-center"
                                                        style="width: 80px; height: 80px;">
                                                        <i data-feather="truck" class="text-success"
                                                            style="width: 30px; height: 30px;"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1 fw-bold text-success">
                                                    {{ $vehicleRequisition->getAssignedVehicle->model_number }}</h6>
                                                <p class="mb-1">
                                                    <span
                                                        class="badge bg-info text-dark">{{ $vehicleRequisition->getAssignedVehicle->vehicle_category }}</span>
                                                </p>
                                                <small class="text-muted">
                                                    License:
                                                    {{ $vehicleRequisition->getAssignedVehicle->license_number ?? '-' }}
                                                </small>
                                                <br>
                                                <a href="{{ route('transport.vehicles.show', $vehicleRequisition->getAssignedVehicle->id) }}"
                                                    class="btn btn-outline-success btn-sm mt-2">
                                                    <i data-feather="eye" style="width: 12px; height: 12px;"></i> View
                                                    Details
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Assigned Driver Preview (if vehicle has driver) --}}
                                @if ($assignedDriver)
                                    <div class="preview-card mb-4">
                                        <div class="preview-header bg-primary text-white">
                                            <h6 class="mb-0 fw-bold">
                                                <i data-feather="user" style="width: 18px; height: 18px;"></i>
                                                Assigned Driver
                                            </h6>
                                        </div>
                                        <div class="card-body p-3">
                                            <div class="d-flex align-items-start">
                                                <div class="me-3">
                                                    {!! \App\HelperClass::generateAvatar(
                                                        $assignedDriver->photo_path ?? null,
                                                        $assignedDriver->full_name ?? 'N/A',
                                                        80,
                                                        '#974063',
                                                        'border border-2',
                                                        $assignedDriver->id,
                                                    ) !!}
                                                </div>
                                                <div class="flex-grow-1">
                                                    <a href="{{ route('employee.profile.general_informations', $assignedDriver->id) }}"
                                                        class="text-decoration-none">
                                                        <h6 class="mb-1 fw-bold text-primary">
                                                            {{ $assignedDriver->full_name }}</h6>
                                                    </a>
                                                    <p class="mb-1">
                                                        <small class="text-muted">ID:
                                                            {{ $assignedDriver->system_id }}</small>
                                                    </p>
                                                    <small class="text-muted">
                                                        Phone:
                                                        {{ $assignedDriver->personal_mobile ?? ($assignedDriver->work_mobile ?? '-') }}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="d-flex justify-content-between align-items-center mt-4 pt-4 border-top">
                        <a href="{{ route('transport.vehicle_requisitions.index') }}" class="btn btn-secondary">
                            <i data-feather="arrow-left" style="width: 14px; height: 14px;"></i> Back to List
                        </a>
                        @if ($vehicleRequisition->approval_status === 'Pending' || $vehicleRequisition->approval_status === 'Approved')
                            <div>
                                <form
                                    action="{{ route('transport.vehicle_requisitions.reject', $vehicleRequisition->id) }}"
                                    method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-danger"
                                        onclick="return confirm('Are you sure you want to reject this requisition?')">
                                        <i data-feather="x-circle" style="width: 14px; height: 14px;"></i> Reject
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

