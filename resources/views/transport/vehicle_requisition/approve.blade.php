@extends('structure.master')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm rounded">
                <div class="card-header bg-gradient" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0 text-white">
                            <i data-feather="check-circle" class="me-2"></i>
                            Approve Vehicle Requisition #{{ $vehicleRequisition->id }}
                        </h5>
                        <a href="{{ route('transport.vehicle_requisitions.index') }}" class="btn btn-light btn-sm">
                            <i data-feather="arrow-left" style="width: 14px; height: 14px;"></i> Back to List
                        </a>
                    </div>
                </div>

                <div class="card-body p-4">
                    <div class="row">
                        {{-- Left Column - Requisition Summary --}}
                        <div class="col-lg-5">
                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-header bg-light border-bottom">
                                    <h6 class="mb-0 fw-bold text-primary">
                                        <i data-feather="clipboard" style="width: 16px; height: 16px;"></i>
                                        Requisition Summary
                                    </h6>
                                </div>
                                <div class="card-body p-3">
                                    <div class="row mb-2">
                                        <div class="col-5">
                                            <span class="text-muted small fw-semibold">Employee</span>
                                        </div>
                                        <div class="col-7">
                                            <span
                                                class="fw-bold">{{ $vehicleRequisition->getEmployee?->full_name ?? '-' }}</span>
                                        </div>
                                    </div>
                                    <hr class="my-2">
                                    <div class="row mb-2">
                                        <div class="col-5">
                                            <span class="text-muted small fw-semibold">Department</span>
                                        </div>
                                        <div class="col-7">
                                            <span
                                                class="fw-bold">{{ $vehicleRequisition->getDepartment?->department_name ?? '-' }}</span>
                                        </div>
                                    </div>
                                    <hr class="my-2">
                                    <div class="row mb-2">
                                        <div class="col-5">
                                            <span class="text-muted small fw-semibold">Trip Type</span>
                                        </div>
                                        <div class="col-7">
                                            <span
                                                class="badge bg-info text-dark">{{ $vehicleRequisition->trip_type }}</span>
                                            <span class="text-muted small">{{ $vehicleRequisition->trip_mode }}</span>
                                        </div>
                                    </div>
                                    <hr class="my-2">
                                    <div class="row mb-2">
                                        <div class="col-5">
                                            <span class="text-muted small fw-semibold">Schedule</span>
                                        </div>
                                        <div class="col-7">
                                            <small>
                                                {{ $vehicleRequisition->start_date_time?->format('d M Y, h:i A') }}
                                                <br><span class="text-muted">to</span><br>
                                                {{ $vehicleRequisition->end_date_time?->format('d M Y, h:i A') }}
                                            </small>
                                        </div>
                                    </div>
                                    <hr class="my-2">
                                    <div class="row mb-2">
                                        <div class="col-5">
                                            <span class="text-muted small fw-semibold">Route</span>
                                        </div>
                                        <div class="col-7">
                                            <small>
                                                <strong>From:</strong> {{ $vehicleRequisition->pickup_location }}
                                                <br><strong>To:</strong> {{ $vehicleRequisition->destination }}
                                            </small>
                                        </div>
                                    </div>
                                    <hr class="my-2">
                                    <div class="row mb-2">
                                        <div class="col-5">
                                            <span class="text-muted small fw-semibold">Vehicle Type</span>
                                        </div>
                                        <div class="col-7">
                                            <span
                                                class="badge bg-secondary">{{ $vehicleRequisition->vehicle_type_required }}</span>
                                        </div>
                                    </div>
                                    <hr class="my-2">
                                    <div class="row mb-2">
                                        <div class="col-5">
                                            <span class="text-muted small fw-semibold">Passengers</span>
                                        </div>
                                        <div class="col-7">
                                            <span class="fw-bold">{{ $vehicleRequisition->no_of_passengers }}</span>
                                        </div>
                                    </div>
                                    <hr class="my-2">
                                    @if ($vehicleRequisition->driver_required)
                                        <div class="row mb-2">
                                            <div class="col-5">
                                                <span class="text-muted small fw-semibold">Driver Required</span>
                                            </div>
                                            <div class="col-7">
                                                <span class="badge bg-success">Yes</span>
                                            </div>
                                        </div>
                                        <hr class="my-2">
                                    @elseif ($vehicleRequisition->self_drive)
                                        <div class="row mb-2">
                                            <div class="col-5">
                                                <span class="text-muted small fw-semibold">Self Drive</span>
                                            </div>
                                            <div class="col-7">
                                                <span class="badge bg-info text-dark">Yes</span>
                                            </div>
                                        </div>
                                        <hr class="my-2">
                                    @endif
                                    <hr class="my-2">
                                    <div class="row">
                                        <div class="col-5">
                                            <span class="text-muted small fw-semibold">Purpose</span>
                                        </div>
                                        <div class="col-7">
                                            <small>{{ $vehicleRequisition->purpose_of_travel }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- View Full Details Link --}}
                            <a href="{{ route('transport.vehicle_requisitions.show', $vehicleRequisition->id) }}"
                                class="btn btn-outline-primary btn-sm w-100">
                                <i data-feather="external-link" style="width: 14px; height: 14px;"></i> View Full Details
                            </a>
                        </div>

                        {{-- Right Column - Approval Form --}}
                        <div class="col-lg-7">
                            <form
                                action="{{ route('transport.vehicle_requisitions.process_approval', $vehicleRequisition->id) }}"
                                method="post">
                                @csrf

                                {{-- Approval Details Section --}}
                                <div class="form-card mb-4">
                                    <div class="section-header">
                                        <h6 class="mb-0">
                                            <i data-feather="check-circle" style="width: 18px; height: 18px;"></i>
                                            Approval Details
                                        </h6>
                                    </div>

                                    {{-- Vehicle Selection --}}
                                    <div class="mb-4">
                                        <label for="assigned_vehicle_id" class="form-label fw-semibold">
                                            Assign Vehicle <span class="text-danger">*</span>
                                        </label>
                                        <select
                                            class="form-select select2_list @error('assigned_vehicle_id') is-invalid @enderror"
                                            name="assigned_vehicle_id" id="assigned_vehicle_id" required>
                                            <option value="">Select Vehicle to Assign</option>
                                            @foreach ($availableVehicles as $vehicle)
                                                <option value="{{ $vehicle->id }}"
                                                    {{ old('assigned_vehicle_id') == $vehicle->id ? 'selected' : '' }}>
                                                    {{ $vehicle->model_number }} - {{ $vehicle->vehicle_category }}
                                                    {{ $vehicle->license_number ? '(' . $vehicle->license_number . ')' : '' }}
                                                    [{{ $vehicle->seating_capacity ?? '?' }} seats]
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="text-muted d-block mt-1">
                                            <i data-feather="info" style="width: 12px; height: 12px;"></i>
                                            Select a vehicle from the acquisition list to assign to this requisition
                                        </small>
                                        @error('assigned_vehicle_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Schedule Details --}}
                                    <div class="row g-3 mb-4">
                                        <div class="col-md-6">
                                            <label for="dispatch_time" class="form-label fw-semibold">Dispatch
                                                Time</label>
                                            <input type="time" id="dispatch_time"
                                                class="form-control @error('dispatch_time') is-invalid @enderror"
                                                name="dispatch_time" value="{{ old('dispatch_time') }}">
                                            @error('dispatch_time')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label for="expected_return_time" class="form-label fw-semibold">Expected
                                                Return
                                                Time</label>
                                            <input type="time" id="expected_return_time"
                                                class="form-control @error('expected_return_time') is-invalid @enderror"
                                                name="expected_return_time" value="{{ old('expected_return_time') }}">
                                            @error('expected_return_time')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- Approval Remarks --}}
                                    <div class="mb-3">
                                        <label for="approval_remarks" class="form-label fw-semibold">Approval Remarks
                                            (Optional)</label>
                                        <textarea id="approval_remarks" class="form-control @error('approval_remarks') is-invalid @enderror"
                                            name="approval_remarks" rows="3" placeholder="Enter any remarks or notes for approval...">{{ old('approval_remarks') }}</textarea>
                                        @error('approval_remarks')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Vehicle Preview Card --}}
                                <div class="preview-card vehicle-preview mb-4 d-none" id="vehiclePreviewCard">
                                    <div class="preview-header vehicle-header">
                                        <i data-feather="truck" style="width: 16px; height: 16px;"></i>
                                        Vehicle Information
                                    </div>
                                    <div class="card-body p-3">
                                        <div class="d-flex align-items-start">
                                            <div class="me-3">
                                                <img src="" alt="Vehicle"
                                                    class="rounded-3 border border-success border-2 d-none"
                                                    id="vehicleImage" width="80" height="80"
                                                    style="object-fit: cover;">
                                                <div class="border border-success border-2 rounded-3 bg-light d-flex align-items-center justify-content-center d-none"
                                                    id="vehicleImagePlaceholder" style="width: 80px; height: 80px;">
                                                    <i data-feather="truck" class="text-success"
                                                        style="width: 30px; height: 30px;"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-2 fw-bold text-success" id="vehicleModel">-</h6>
                                                <div class="row g-2">
                                                    <div class="col-4">
                                                        <small class="text-muted d-block">Category</small>
                                                        <span class="badge bg-info text-dark"
                                                            id="vehicleCategory">-</span>
                                                    </div>
                                                    <div class="col-4">
                                                        <small class="text-muted d-block">Year</small>
                                                        <span class="fw-bold" id="vehicleYear">-</span>
                                                    </div>
                                                    <div class="col-4">
                                                        <small class="text-muted d-block">Seats</small>
                                                        <span class="fw-bold" id="vehicleSeats">-</span>
                                                    </div>
                                                    <div class="col-4">
                                                        <small class="text-muted d-block">Fuel</small>
                                                        <span class="fw-bold" id="vehicleFuel">-</span>
                                                    </div>
                                                    <div class="col-4">
                                                        <small class="text-muted d-block">Color</small>
                                                        <span class="fw-bold" id="vehicleColor">-</span>
                                                    </div>
                                                    <div class="col-4">
                                                        <small class="text-muted d-block">License</small>
                                                        <span class="fw-bold" id="vehicleLicense">-</span>
                                                    </div>
                                                </div>
                                                <a href="#" class="btn btn-outline-success btn-sm mt-2"
                                                    id="vehicleDetailsLink" target="_blank">
                                                    <i data-feather="external-link"
                                                        style="width: 12px; height: 12px;"></i> View Full Details
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Driver Preview Card --}}
                                <div class="preview-card driver-preview mb-4 d-none" id="driverPreviewCard">
                                    <div class="preview-header driver-header">
                                        <i data-feather="user" style="width: 16px; height: 16px;"></i>
                                        Assigned Driver
                                    </div>
                                    <div class="card-body p-3">
                                        <div class="d-flex align-items-start">
                                            <div class="me-3">
                                                <img src="" alt="Driver"
                                                    class="rounded-circle border border-primary border-2 d-none"
                                                    id="driverImage" width="70" height="70"
                                                    style="object-fit: cover;">
                                                <div class="border border-primary border-2 rounded-circle bg-light d-flex align-items-center justify-content-center d-none"
                                                    id="driverImagePlaceholder" style="width: 70px; height: 70px;">
                                                    <i data-feather="user" class="text-primary"
                                                        style="width: 25px; height: 25px;"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1 fw-bold text-primary" id="driverName">-</h6>
                                                <p class="mb-1 text-muted" id="driverSystemId">-</p>
                                                <small class="text-muted" id="driverPhone">-</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- No Driver Message --}}
                                <div class="alert alert-info d-none" id="noDriverMessage">
                                    <i data-feather="info" style="width: 16px; height: 16px;"></i>
                                    <span class="ms-2">This vehicle does not have an assigned driver. Please assign a
                                        driver first if required.</span>
                                </div>

                                {{-- Submit Section --}}
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body py-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="d-flex align-items-center">
                                                <i data-feather="alert-circle" class="text-info me-2"></i>
                                                <span class="text-muted">Please review before approving.</span>
                                            </div>
                                            <div>
                                                <a href="{{ route('transport.vehicle_requisitions.index') }}"
                                                    class="btn btn-secondary me-2">
                                                    <i data-feather="x" style="width: 14px; height: 14px;"></i> Cancel
                                                </a>
                                                <button type="submit" class="btn btn-success">
                                                    <i data-feather="check-circle" style="width: 14px; height: 14px;"></i>
                                                    Approve Requisition
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Vehicle selection change handler
            $('#assigned_vehicle_id').on('change', function() {
                const vehicleId = $(this).val();
                const vehiclePreviewCard = $('#vehiclePreviewCard');
                const driverPreviewCard = $('#driverPreviewCard');
                const noDriverMessage = $('#noDriverMessage');

                if (!vehicleId) {
                    vehiclePreviewCard.addClass('d-none');
                    driverPreviewCard.addClass('d-none');
                    noDriverMessage.addClass('d-none');
                    return;
                }

                // Fetch vehicle details via AJAX
                $.ajax({
                    url: '{{ url('transport/api/requisition-vehicle') }}/' + vehicleId,
                    method: 'GET',
                    success: function(data) {
                        // Update vehicle preview card
                        if (data.vehicle_image) {
                            $('#vehicleImage').attr('src', data.vehicle_image).removeClass(
                                'd-none');
                            $('#vehicleImagePlaceholder').addClass('d-none');
                        } else {
                            $('#vehicleImage').addClass('d-none');
                            $('#vehicleImagePlaceholder').removeClass('d-none');
                        }

                        $('#vehicleModel').text(data.model_number || '-');
                        $('#vehicleCategory').text(data.vehicle_category || '-');
                        $('#vehicleYear').text(data.manufacture_year || '-');
                        $('#vehicleFuel').text(data.fuel_type || '-');
                        $('#vehicleColor').text(data.color || '-');
                        $('#vehicleLicense').text(data.license_number || '-');
                        $('#vehicleSeats').text(data.seating_capacity || '-');
                        $('#vehicleDetailsLink').attr('href',
                            '{{ url('transport/vehicle-acquisitions') }}/' + data.id);

                        vehiclePreviewCard.removeClass('d-none');

                        // Check if vehicle has driver
                        if (data.has_driver && data.driver) {
                            const driver = data.driver;

                            if (driver.photo_path) {
                                $('#driverImage').attr('src', driver.photo_path).removeClass(
                                    'd-none');
                                $('#driverImagePlaceholder').addClass('d-none');
                            } else {
                                $('#driverImage').addClass('d-none');
                                $('#driverImagePlaceholder').removeClass('d-none');
                            }

                            $('#driverName').text(driver.full_name || '-');
                            $('#driverSystemId').text('ID: ' + (driver.system_id || '-'));
                            $('#driverPhone').text('Phone: ' + (driver.personal_mobile || driver
                                .work_mobile || '-'));

                            driverPreviewCard.removeClass('d-none');
                            noDriverMessage.addClass('d-none');
                        } else {
                            driverPreviewCard.addClass('d-none');
                            noDriverMessage.removeClass('d-none');
                        }

                        // Reinitialize feather icons
                        if (typeof feather !== 'undefined') {
                            feather.replace();
                        }
                    },
                    error: function() {
                        vehiclePreviewCard.addClass('d-none');
                        driverPreviewCard.addClass('d-none');
                        noDriverMessage.addClass('d-none');
                    }
                });
            });
        });
    </script>
@endpush
