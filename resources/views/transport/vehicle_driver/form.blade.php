@extends('structure.master')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card border-0 shadow-lg rounded-3">
                <div class="card-header bg-primary text-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0 fw-bold">
                            <i data-feather="user-check" class="me-2"></i>
                            {{ isset($vehicleDriver) ? 'Edit' : 'New' }} Driver Assignment
                        </h5>
                        <a href="{{ route('transport.vehicle_drivers.index') }}" class="btn btn-light btn-sm shadow-sm">
                            <i data-feather="arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>

                <div class="card-body p-4">
                    <form
                        action="{{ isset($vehicleDriver) ? route('transport.vehicle_drivers.update', $vehicleDriver->id) : route('transport.vehicle_drivers.store') }}"
                        method="post">
                        @csrf
                        @if (isset($vehicleDriver))
                            @method('PUT')
                        @endif

                        {{-- Form Fields Section --}}
                        <div class="row g-4">
                            {{-- Left Column: Form Fields --}}
                            <div class="col-lg-6">
                                <div class="row g-4">
                                    <div class="col-12">
                                        {{-- Vehicle Selection Section --}}
                                        <div class="card border-0 shadow-sm">
                                            <div class="card-header bg-primary text-white border-0">
                                                <h6 class="mb-0 fw-bold">
                                                    <i data-feather="truck"></i>
                                                    Select Vehicle
                                                </h6>
                                            </div>
                                            <div class="card-body">
                                                <label for="vehicle_id" class="form-label fw-semibold">
                                                    Vehicle <span class="text-danger">*</span>
                                                </label>
                                                <select
                                                    class="form-select select2_list @error('vehicle_id') is-invalid @enderror"
                                                    name="vehicle_id" id="vehicle_id" required>
                                                    <option value="">Select Vehicle</option>
                                                    @foreach ($availableVehicles as $vehicle)
                                                        <option value="{{ $vehicle->id }}"
                                                            {{ (isset($vehicleDriver) && $vehicleDriver->vehicle_id == $vehicle->id) || old('vehicle_id') == $vehicle->id ? 'selected' : '' }}>
                                                            {{ $vehicle->model_number }} - {{ $vehicle->vehicle_category }}
                                                            {{ $vehicle->license_number ? '(' . $vehicle->license_number . ')' : '' }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <small class="text-muted d-block mt-2">
                                                    <i data-feather="info"></i> Only active vehicles without current driver
                                                    assignment are shown.
                                                </small>
                                                @error('vehicle_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            {{-- Driver Selection Section --}}
                                            <div class="card border-0 shadow-sm">
                                                <div class="card-header bg-success text-white border-0">
                                                    <h6 class="mb-0 fw-bold">
                                                        <i data-feather="user"></i>
                                                        Select Driver
                                                    </h6>
                                                </div>
                                                <div class="card-body">
                                                    <label for="driver_id" class="form-label fw-semibold">
                                                        Driver <span class="text-danger">*</span>
                                                    </label>
                                                    <select
                                                        class="form-select select2_list @error('driver_id') is-invalid @enderror"
                                                        name="driver_id" id="driver_id" required>
                                                        <option value="">Select Driver</option>
                                                        @foreach ($eligibleDrivers as $driver)
                                                            <option value="{{ $driver->id }}"
                                                                {{ (isset($vehicleDriver) && $vehicleDriver->driver_id == $driver->id) || old('driver_id') == $driver->id ? 'selected' : '' }}>
                                                                {{ $driver->full_name }} ({{ $driver->system_id }})
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <small class="text-muted d-block mt-2">
                                                        <i data-feather="info"></i> Only employees with Driver designation
                                                        are
                                                        shown.
                                                    </small>
                                                    @error('driver_id')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            {{-- Assignment Details Section --}}
                                            <div class="card border-0 shadow-sm">
                                                <div class="card-header bg-warning border-0">
                                                    <h6 class="mb-0 fw-bold">
                                                        <i data-feather="calendar"></i>
                                                        Assignment Details
                                                    </h6>
                                                </div>
                                                <div class="card-body">
                                                    <div class="mb-3">
                                                        <label for="start_date" class="form-label fw-semibold">
                                                            Start Date <span class="text-danger">*</span>
                                                        </label>
                                                        <input type="date" id="start_date"
                                                            class="form-control @error('start_date') is-invalid @enderror"
                                                            name="start_date"
                                                            value="{{ isset($vehicleDriver) ? \Carbon\Carbon::parse($vehicleDriver->start_date)->format('Y-m-d') : old('start_date', date('Y-m-d')) }}"
                                                            required>
                                                        @error('start_date')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="end_date" class="form-label fw-semibold">
                                                            End Date
                                                        </label>
                                                        <input type="date" id="end_date"
                                                            class="form-control @error('end_date') is-invalid @enderror"
                                                            name="end_date"
                                                            value="{{ isset($vehicleDriver) && $vehicleDriver->end_date ? \Carbon\Carbon::parse($vehicleDriver->end_date)->format('Y-m-d') : old('end_date') }}">
                                                        <small class="text-muted d-block mt-1">Leave empty for ongoing
                                                            assignment.</small>
                                                        @error('end_date')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    @if (isset($vehicleDriver))
                                                        {{-- Only show status for editing (though edit is disabled) --}}
                                                        <div>
                                                            <label for="status" class="form-label fw-semibold">
                                                                Status <span class="text-danger">*</span>
                                                            </label>
                                                            <select
                                                                class="form-select @error('status') is-invalid @enderror"
                                                                name="status" id="status" required>
                                                                @foreach (['active' => 'Active', 'inactive' => 'Inactive'] as $value => $label)
                                                                    <option value="{{ $value }}"
                                                                        {{ (isset($vehicleDriver) && $vehicleDriver->status == $value) || old('status', 'active') == $value ? 'selected' : '' }}>
                                                                        {{ $label }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            @error('status')
                                                                <div class="invalid-feedback">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Right Column: Preview Cards --}}
                            <div class="col-lg-6">
                                {{-- Vehicle Preview Card --}}
                                <div class="card border-0 shadow-lg mb-4 d-none" id="vehiclePreviewCard">
                                    <div class="card-header bg-primary text-white border-0 py-3">
                                        <h6 class="mb-0 fw-bold">
                                            <i data-feather="truck"></i>
                                            Vehicle Information
                                        </h6>
                                    </div>
                                    <div class="card-body p-3">
                                        <div class="d-flex align-items-start">
                                            <div class="me-3">
                                                <img src="" alt="Vehicle"
                                                    class="rounded-3 border border-primary border-3 shadow-sm d-none"
                                                    id="vehicleImage" width="100" height="100"
                                                    style="object-fit: cover;">
                                                <div class="border border-primary border-3 rounded-3 bg-body-secondary d-flex align-items-center justify-content-center shadow-sm d-none"
                                                    id="vehicleImagePlaceholder" style="width: 100px; height: 100px;">
                                                    <i data-feather="image" class="text-primary"
                                                        style="width: 40px; height: 40px;"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h5 class="mb-2 fw-bold text-primary" id="vehicleModel">-</h5>
                                                <div class="row g-2">
                                                    <div class="col-6">
                                                        <div class="p-2 bg-body-secondary rounded-2">
                                                            <small
                                                                class="text-muted text-uppercase d-block mb-0 fw-semibold">Category</small>
                                                            <span class="badge bg-info" id="vehicleCategory">-</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="p-2 bg-body-secondary rounded-2">
                                                            <small
                                                                class="text-muted text-uppercase d-block mb-0 fw-semibold">Year</small>
                                                            <span class="fw-bold" id="vehicleYear">-</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="p-2 bg-body-secondary rounded-2">
                                                            <small
                                                                class="text-muted text-uppercase d-block mb-0 fw-semibold">Fuel</small>
                                                            <span class="fw-bold" id="vehicleFuel">-</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="p-2 bg-body-secondary rounded-2">
                                                            <small
                                                                class="text-muted text-uppercase d-block mb-0 fw-semibold">Color</small>
                                                            <span class="fw-bold" id="vehicleColor">-</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="p-2 bg-body-secondary rounded-2">
                                                            <small
                                                                class="text-muted text-uppercase d-block mb-0 fw-semibold">License</small>
                                                            <span class="fw-bold" id="vehicleLicense">-</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="p-2 bg-body-secondary rounded-2">
                                                            <small
                                                                class="text-muted text-uppercase d-block mb-0 fw-semibold">Seats</small>
                                                            <span class="fw-bold" id="vehicleSeats">-</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Driver Preview Card --}}
                                <div class="card border-0 shadow-lg mb-4 d-none" id="driverPreviewCard">
                                    <div class="card-header bg-success text-white border-0 py-3">
                                        <h6 class="mb-0 fw-bold">
                                            <i data-feather="user"></i>
                                            Driver Information
                                        </h6>
                                    </div>
                                    <div class="card-body p-3">
                                        <div class="d-flex align-items-start">
                                            <div class="me-3">
                                                <img src="" alt="Driver"
                                                    class="rounded-circle border border-success border-3 shadow-sm d-none"
                                                    id="driverImage" width="100" height="100"
                                                    style="object-fit: cover;">
                                                <div class="border-3 rounded-circle d-flex align-items-center justify-content-center shadow-sm text-white fw-bold d-none"
                                                    id="driverImagePlaceholder"
                                                    style="width: 100px; height: 100px; font-size: 40px; background-color: #974063; border-color: #974063;">
                                                    <span id="driverInitials"></span>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h5 class="mb-2 fw-bold text-success" id="driverName">-</h5>
                                                <div class="row g-2">
                                                    <div class="col-6">
                                                        <div class="p-2 bg-body-secondary rounded-2">
                                                            <small
                                                                class="text-muted text-uppercase d-block mb-0 fw-semibold">System
                                                                ID</small>
                                                            <span class="fw-bold" id="driverSystemId">-</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="p-2 bg-body-secondary rounded-2">
                                                            <small
                                                                class="text-muted text-uppercase d-block mb-0 fw-semibold">Designation</small>
                                                            <span class="badge bg-success" id="driverDesignation">-</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="p-2 bg-body-secondary rounded-2">
                                                            <small
                                                                class="text-muted text-uppercase d-block mb-0 fw-semibold">Phone</small>
                                                            <span class="fw-bold" id="driverPhone">-</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="p-2 bg-body-secondary rounded-2">
                                                            <small
                                                                class="text-muted text-uppercase d-block mb-0 fw-semibold">Email</small>
                                                            <span class="fw-bold" id="driverEmail">-</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Submit Section --}}
                        <div class="card border-0 shadow-sm mt-4">
                            <div class="card-body py-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <i data-feather="alert-circle" class="text-info me-2"></i>
                                        <span class="text-muted">Please review all details before submitting.</span>
                                    </div>
                                    <div>
                                        <a href="{{ route('transport.vehicle_drivers.index') }}"
                                            class="btn btn-secondary shadow-sm me-2">
                                            <i data-feather="x"></i> Cancel
                                        </a>
                                        <button type="submit" class="btn btn-primary shadow-sm">
                                            <i data-feather="check"></i>
                                            {{ isset($vehicleDriver) ? 'Update Assignment' : 'Assign Driver' }}
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
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Vehicle selection change handler
            $('#vehicle_id').on('change', function() {
                const vehicleId = $(this).val();
                const previewCard = $('#vehiclePreviewCard');

                if (!vehicleId) {
                    previewCard.addClass('d-none');
                    return;
                }

                // Fetch vehicle details via AJAX
                $.ajax({
                    url: '{{ url('transport/api/vehicle') }}/' + vehicleId,
                    method: 'GET',
                    success: function(data) {
                        // Update preview card
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

                        previewCard.removeClass('d-none');

                        // Reinitialize feather icons
                        if (typeof feather !== 'undefined') {
                            feather.replace();
                        }
                    },
                    error: function() {
                        previewCard.addClass('d-none');
                    }
                });
            });

            // Driver selection change handler
            $('#driver_id').on('change', function() {
                const driverId = $(this).val();
                const previewCard = $('#driverPreviewCard');

                if (!driverId) {
                    previewCard.addClass('d-none');
                    return;
                }

                // Fetch driver details via AJAX
                $.ajax({
                    url: '{{ url('transport/api/driver') }}/' + driverId,
                    method: 'GET',
                    success: function(data) {
                        // Update preview card
                        if (data.photo_path) {
                            $('#driverImage').attr('src', data.photo_path).removeClass(
                                'd-none');
                            $('#driverImagePlaceholder').addClass('d-none');
                        } else {
                            // Generate initials (first + last name)
                            const fullName = data.full_name || 'N/A';
                            let initials = 'NA';
                            if (fullName !== 'N/A' && fullName.trim() !== '') {
                                const names = fullName.trim().split(' ');
                                initials = names[0].charAt(0).toUpperCase();
                                if (names.length > 1) {
                                    initials += names[names.length - 1].charAt(0).toUpperCase();
                                }
                            }
                            $('#driverInitials').text(initials);
                            $('#driverImage').addClass('d-none');
                            $('#driverImagePlaceholder').removeClass('d-none');
                        }

                        $('#driverName').text(data.full_name || '-');
                        $('#driverSystemId').text(data.system_id || '-');
                        $('#driverDesignation').text(data.designation || '-');
                        $('#driverPhone').text(data.personal_mobile || data.work_mobile || '-');
                        $('#driverEmail').text(data.work_email || data.personal_email || '-');

                        previewCard.removeClass('d-none');

                        // Reinitialize feather icons
                        if (typeof feather !== 'undefined') {
                            feather.replace();
                        }
                    },
                    error: function() {
                        previewCard.addClass('d-none');
                    }
                });
            });

            // Trigger change events on page load if values are pre-selected (for edit mode)
            @if (isset($vehicleDriver))
                $('#vehicle_id').trigger('change');
                $('#driver_id').trigger('change');
            @endif

            // Also trigger if there are old values from validation errors
            @if (old('vehicle_id'))
                $('#vehicle_id').trigger('change');
            @endif

            @if (old('driver_id'))
                $('#driver_id').trigger('change');
            @endif
        });
    </script>
@endpush
