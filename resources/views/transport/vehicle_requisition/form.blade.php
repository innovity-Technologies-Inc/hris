@extends('structure.master')

@section('content')
    <style>
        .form-card {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 20px;
            transition: all 0.3s ease;
        }

        .form-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .section-header {
            background: var(--primary-color);
            color: white;
            padding: 15px 20px;
            border-radius: 8px 8px 0 0;
            margin: -20px -20px 20px -20px;
        }

        .section-header.success {
            background: #11998e;
        }

        .section-header.warning {
            background: #f093fb;
        }

        .section-header.info {
            background: #4facfe;
        }

        .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 8px;
        }

        .submit-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-top: 30px;
        }
    </style>

    <div class="row">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm rounded">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0 text-white">
                            <i data-feather="clipboard" class="me-2"></i>
                            New Vehicle Requisition
                        </h5>
                        <a href="{{ route('transport.vehicle_requisitions.index') }}" class="btn btn-light btn-sm">
                            <i data-feather="arrow-left" style="width: 14px; height: 14px;"></i> Back to List
                        </a>
                    </div>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('transport.vehicle_requisitions.store') }}" method="post" id="requisitionForm">
                        @csrf

                        {{-- Basic Details Section --}}
                        <div class="form-card mb-4">
                            <div class="section-header">
                                <h6 class="mb-0">
                                    <i data-feather="user" style="width: 18px; height: 18px;"></i>
                                    Basic Details
                                </h6>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="employee_id" class="form-label">
                                        Employee <small class="text-muted">(Optional)</small>
                                    </label>
                                    <select class="form-select select2_list @error('employee_id') is-invalid @enderror"
                                        name="employee_id" id="employee_id">
                                        <option value="">Select Employee (Optional)</option>
                                        @foreach ($employees as $employee)
                                            <option value="{{ $employee->id }}"
                                                {{ old('employee_id') == $employee->id ? 'selected' : '' }}>
                                                {{ $employee->full_name }} ({{ $employee->system_id }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('employee_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="department" class="form-label">Department</label>
                                    <select class="form-select select2_list @error('department') is-invalid @enderror"
                                        name="department" id="department">
                                        <option value="">Select Department</option>
                                        @foreach ($departments as $dept)
                                            <option value="{{ $dept->id }}"
                                                {{ old('department') == $dept->id ? 'selected' : '' }}>
                                                {{ $dept->department_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('department')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Trip Details Section --}}
                        <div class="form-card mb-4">
                            <div class="section-header success">
                                <h6 class="mb-0">
                                    <i data-feather="navigation" style="width: 18px; height: 18px;"></i>
                                    Trip Details
                                </h6>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="trip_type" class="form-label">
                                        Trip Type <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('trip_type') is-invalid @enderror" name="trip_type"
                                        id="trip_type" required>
                                        <option value="">Select Trip Type</option>
                                        @foreach (['Official', 'Personal', 'Visitor'] as $type)
                                            <option value="{{ $type }}"
                                                {{ old('trip_type') == $type ? 'selected' : '' }}>
                                                {{ $type }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('trip_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="trip_mode" class="form-label">
                                        Trip Mode <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('trip_mode') is-invalid @enderror" name="trip_mode"
                                        id="trip_mode" required>
                                        <option value="">Select Trip Mode</option>
                                        @foreach (['One-way', 'Round-trip', 'Multi-stop'] as $mode)
                                            <option value="{{ $mode }}"
                                                {{ old('trip_mode') == $mode ? 'selected' : '' }}>
                                                {{ $mode }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('trip_mode')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="no_of_passengers" class="form-label">
                                        Number of Passengers <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" id="no_of_passengers"
                                        class="form-control @error('no_of_passengers') is-invalid @enderror"
                                        name="no_of_passengers" placeholder="Enter number of passengers" min="1"
                                        max="100" value="{{ old('no_of_passengers', 1) }}" required>
                                    @error('no_of_passengers')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label for="purpose_of_travel" class="form-label">
                                        Purpose of Travel <span class="text-danger">*</span>
                                    </label>
                                    <textarea id="purpose_of_travel" class="form-control @error('purpose_of_travel') is-invalid @enderror"
                                        name="purpose_of_travel" rows="3" placeholder="Enter the purpose of travel..." required>{{ old('purpose_of_travel') }}</textarea>
                                    @error('purpose_of_travel')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Schedule Section --}}
                        <div class="form-card mb-4">
                            <div class="section-header warning">
                                <h6 class="mb-0">
                                    <i data-feather="calendar" style="width: 18px; height: 18px;"></i>
                                    Schedule
                                </h6>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="start_date_time" class="form-label">
                                        Start Date & Time <span class="text-danger">*</span>
                                    </label>
                                    <input type="datetime-local" id="start_date_time"
                                        class="form-control @error('start_date_time') is-invalid @enderror"
                                        name="start_date_time" value="{{ old('start_date_time') }}" required>
                                    @error('start_date_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="end_date_time" class="form-label">
                                        End Date & Time <span class="text-danger">*</span>
                                    </label>
                                    <input type="datetime-local" id="end_date_time"
                                        class="form-control @error('end_date_time') is-invalid @enderror"
                                        name="end_date_time" value="{{ old('end_date_time') }}" required>
                                    @error('end_date_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Locations Section --}}
                        <div class="form-card mb-4">
                            <div class="section-header info">
                                <h6 class="mb-0">
                                    <i data-feather="map-pin" style="width: 18px; height: 18px;"></i>
                                    Locations
                                </h6>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="pickup_location" class="form-label">
                                        Pickup Location <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" id="pickup_location"
                                        class="form-control @error('pickup_location') is-invalid @enderror"
                                        name="pickup_location" placeholder="Enter pickup location"
                                        value="{{ old('pickup_location') }}" required>
                                    @error('pickup_location')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="destination" class="form-label">
                                        Destination <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" id="destination"
                                        class="form-control @error('destination') is-invalid @enderror" name="destination"
                                        placeholder="Enter destination" value="{{ old('destination') }}" required>
                                    @error('destination')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="route" class="form-label">Route (Optional)</label>
                                    <input type="text" id="route"
                                        class="form-control @error('route') is-invalid @enderror" name="route"
                                        placeholder="Preferred route if any" value="{{ old('route') }}">
                                    @error('route')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Vehicle & Preferences Section --}}
                        <div class="form-card mb-4">
                            <div class="section-header">
                                <h6 class="mb-0">
                                    <i data-feather="truck" style="width: 18px; height: 18px;"></i>
                                    Vehicle & Preferences
                                </h6>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="vehicle_type_required" class="form-label">
                                        Vehicle Type Required <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('vehicle_type_required') is-invalid @enderror"
                                        name="vehicle_type_required" id="vehicle_type_required" required>
                                        <option value="">Select Vehicle Type</option>
                                        @foreach (['Car', 'Bus', 'Micro'] as $vehicleType)
                                            <option value="{{ $vehicleType }}"
                                                {{ old('vehicle_type_required') == $vehicleType ? 'selected' : '' }}>
                                                {{ $vehicleType }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('vehicle_type_required')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="preferred_vehicle" class="form-label">Preferred Vehicle (Optional)</label>
                                    <input type="text" id="preferred_vehicle"
                                        class="form-control @error('preferred_vehicle') is-invalid @enderror"
                                        name="preferred_vehicle" placeholder="e.g., Toyota Corolla"
                                        value="{{ old('preferred_vehicle') }}">
                                    @error('preferred_vehicle')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label for="special_requirement" class="form-label">Special Requirements
                                        (Optional)</label>
                                    <input type="text" id="special_requirement"
                                        class="form-control @error('special_requirement') is-invalid @enderror"
                                        name="special_requirement" placeholder="e.g., AC required, Large boot"
                                        value="{{ old('special_requirement') }}">
                                    @error('special_requirement')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch"
                                            id="driver_required" name="driver_required" value="1"
                                            {{ old('driver_required', true) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold" for="driver_required">
                                            Driver Required
                                        </label>
                                    </div>
                                    <small class="text-muted">Check if you need a driver for this trip</small>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="self_drive"
                                            name="self_drive" value="1" {{ old('self_drive') ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold" for="self_drive">
                                            Self Drive
                                        </label>
                                    </div>
                                    <small class="text-muted">Check if you are eligible and want to drive yourself</small>
                                </div>
                            </div>
                        </div>

                        {{-- Submit Section --}}
                        <div class="submit-section">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <i data-feather="alert-circle" class="text-info me-2"></i>
                                    <span class="text-muted">Please review all details before submitting.</span>
                                </div>
                                <div>
                                    <a href="{{ route('transport.vehicle_requisitions.index') }}"
                                        class="btn btn-secondary me-2">
                                        <i data-feather="x" style="width: 14px; height: 14px;"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i data-feather="send" style="width: 14px; height: 14px;"></i> Submit Requisition
                                    </button>
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
            // Mutual exclusivity between Driver Required and Self Drive
            $('#driver_required').on('change', function() {
                if ($(this).is(':checked')) {
                    $('#self_drive').prop('checked', false);
                }
            });

            $('#self_drive').on('change', function() {
                if ($(this).is(':checked')) {
                    $('#driver_required').prop('checked', false);
                }
            });

            // Axios Submit Handling
            $('#requisitionForm').on('submit', function(e) {
                e.preventDefault();
                const form = this;
                const submitBtn = $(form).find('[type="submit"]');
                submitBtn.prop('disabled', true);

                // Clear previous validation errors
                $(form).find('.is-invalid').removeClass('is-invalid');
                $(form).find('.invalid-feedback').text('');

                const formData = new FormData(form);

                axios.post(form.action, formData)
                    .then(response => {
                        if (response.data.success) {
                            window.location.href = response.data.redirect;
                        }
                    })
                    .catch(error => {
                        submitBtn.prop('disabled', false);
                        if (error.response && error.response.status === 422) {
                            const errors = error.response.data.errors;
                            Object.keys(errors).forEach(key => {
                                const input = $(form).find(`[name="${key}"]`);
                                if (input.length) {
                                    input.addClass('is-invalid');
                                    
                                    let container = input;
                                    if (input.parent().hasClass('input-group')) {
                                        container = input.parent();
                                    }
                                    
                                    let feedback = container.siblings('.invalid-feedback');
                                    if (!feedback.length && container.parent().find('.invalid-feedback').length) {
                                        feedback = container.parent().find('.invalid-feedback');
                                    }
                                    if (!feedback.length) {
                                        feedback = $('<div class="invalid-feedback"></div>');
                                        container.after(feedback);
                                    }
                                    feedback.text(errors[key][0]);
                                    feedback.show();
                                }
                            });
                        } else {
                            alert(error.response?.data?.message || 'Something went wrong.');
                        }
                    });
            });
        });
    </script>
@endpush

