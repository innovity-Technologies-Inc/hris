@extends('structure.master')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-bus me-2"></i>
                            <h5 class="mb-0">
                                @if (isset($employeeTransport))
                                    Edit Employee Transport Service
                                @else
                                    New Employee Transport Service
                                @endif
                            </h5>
                        </div>
                        <a href="{{ route('transport.employee_transports.index') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>Back to List
                        </a>
                    </div>
                </div>

                <div class="card-body p-4">
                    <form id="transportForm"
                        action="{{ isset($employeeTransport) ? route('transport.employee_transports.update', $employeeTransport->id) : route('transport.employee_transports.store') }}"
                        method="post">
                        @csrf
                        @if (isset($employeeTransport))
                            @method('PUT')
                        @endif

                        <!-- Type Section -->
                        <div class="mb-4">
                            <h6 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-tag me-2"></i>Type
                            </h6>
                            <div class="row">
                                <!-- Type -->
                                <div class="col-md-4 mb-3">
                                    <label for="type" class="form-label fw-semibold">
                                        <i class="fas fa-tag text-primary me-1"></i>
                                        Type <span class="text-danger">*</span>
                                    </label>
                                    <select name="type" id="type" class="form-select select2_list" required>
                                        <option value="">-- Select Type --</option>
                                        @foreach ($types as $key => $label)
                                            <option value="{{ $key }}"
                                                {{ old('type', $employeeTransport->type ?? '') == $key ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('type')
                                        <small class="text-danger"><i
                                                class="fas fa-exclamation-circle me-1"></i>{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Organization Hierarchy Section -->
                        <div class="mb-4">
                            <h6 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-sitemap me-2"></i>Organization Hierarchy
                            </h6>
                            <div class="row mb-2">
                                <!-- Company -->
                                <div class="col-md-4 mb-3">
                                    <label for="company_id" class="form-label fw-semibold">
                                        <i class="fas fa-building text-primary me-1"></i>
                                        Company <span class="text-danger">*</span>
                                    </label>
                                    <select name="company_id" id="company_id" class="form-select select2_list" required>
                                        <option value="">-- Select Company --</option>
                                        @foreach ($companies as $company)
                                            <option value="{{ $company->id }}"
                                                {{ old('company_id', $employeeTransport->company_id ?? '') == $company->id ? 'selected' : '' }}>
                                                {{ $company->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('company_id')
                                        <small class="text-danger"><i
                                                class="fas fa-exclamation-circle me-1"></i>{{ $message }}</small>
                                    @enderror
                                </div>

                                @if ($generalSettings->branch_status == 1)
                                    <!-- Branch -->
                                    <div class="col-md-4 mb-3">
                                        <label for="branch_id" class="form-label fw-semibold">
                                            <i class="fas fa-map-marker-alt text-primary me-1"></i>
                                            Branch
                                        </label>
                                        <select name="branch_id" id="branch_id" class="form-select select2_list">
                                            <option value="">Select Branch</option>
                                        </select>
                                        @error('branch_id')
                                            <small class="text-danger"><i
                                                    class="fas fa-exclamation-circle me-1"></i>{{ $message }}</small>
                                        @enderror
                                    </div>
                                @endif

                                @if ($generalSettings->division_status == 1)
                                    <!-- Division -->
                                    <div class="col-md-4 mb-3">
                                        <label for="division_id" class="form-label fw-semibold">
                                            <i class="fas fa-project-diagram text-warning me-1"></i>
                                            Division
                                        </label>
                                        <select name="division_id" id="division_id" class="form-select select2_list">
                                            <option value="">Select Division</option>
                                        </select>
                                        @error('division_id')
                                            <small class="text-danger"><i
                                                    class="fas fa-exclamation-circle me-1"></i>{{ $message }}</small>
                                        @enderror
                                    </div>
                                @endif
                            </div>

                            <div class="row">
                                @if ($generalSettings->department_status == 1)
                                    <!-- Department -->
                                    <div class="col-md-4 mb-3">
                                        <label for="department_id" class="form-label fw-semibold">
                                            <i class="fas fa-sitemap text-info me-1"></i>
                                            Department
                                        </label>
                                        <select name="department_id" id="department_id" class="form-select select2_list">
                                            <option value="">Select Department</option>
                                        </select>
                                        @error('department_id')
                                            <small class="text-danger"><i
                                                    class="fas fa-exclamation-circle me-1"></i>{{ $message }}</small>
                                        @enderror
                                    </div>
                                @endif

                                @if ($generalSettings->section_status == 1)
                                    <!-- Section -->
                                    <div class="col-md-4 mb-3">
                                        <label for="section_id" class="form-label fw-semibold">
                                            <i class="fas fa-network-wired text-secondary me-1"></i>
                                            Section
                                        </label>
                                        <select name="section_id" id="section_id" class="form-select select2_list">
                                            <option value="">Select Section</option>
                                        </select>
                                        @error('section_id')
                                            <small class="text-danger"><i
                                                    class="fas fa-exclamation-circle me-1"></i>{{ $message }}</small>
                                        @enderror
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Service Information Section -->
                        <div class="mb-4">
                            <h6 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-info-circle me-2"></i>Service Information
                            </h6>
                            <div class="row">
                                <!-- Service Name -->
                                <div class="col-md-6 mb-3">
                                    <label for="service_name" class="form-label fw-semibold">
                                        <i class="fas fa-tag text-primary me-1"></i>
                                        Service Name <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="service_name" id="service_name" class="form-control"
                                        placeholder="e.g., Morning Shuttle, Evening Drop"
                                        value="{{ old('service_name', $employeeTransport->service_name ?? '') }}"
                                        required>
                                    @error('service_name')
                                        <small class="text-danger"><i
                                                class="fas fa-exclamation-circle me-1"></i>{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Transport Type -->
                                <div class="col-md-6 mb-3">
                                    <label for="transport_type" class="form-label fw-semibold">
                                        <i class="fas fa-list text-primary me-1"></i>
                                        Transport Type <span class="text-danger">*</span>
                                    </label>
                                    <select name="transport_type" id="transport_type" class="form-select" required>
                                        <option value="">-- Select Transport Type --</option>
                                        @foreach ($transportTypes as $tType)
                                            <option value="{{ $tType }}"
                                                {{ old('transport_type', $employeeTransport->transport_type ?? '') == $tType ? 'selected' : '' }}>
                                                {{ $tType }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('transport_type')
                                        <small class="text-danger"><i
                                                class="fas fa-exclamation-circle me-1"></i>{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Estimated Passengers -->
                                <div class="col-md-4 mb-3">
                                    <label for="estimated_passengers" class="form-label fw-semibold">
                                        <i class="fas fa-users text-primary me-1"></i>
                                        Estimated Passengers
                                    </label>
                                    <input type="number" name="estimated_passengers" id="estimated_passengers"
                                        class="form-control" placeholder="Number of passengers" min="1"
                                        value="{{ old('estimated_passengers', $employeeTransport->estimated_passengers ?? '') }}">
                                    @error('estimated_passengers')
                                        <small class="text-danger"><i
                                                class="fas fa-exclamation-circle me-1"></i>{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Purpose -->
                                <div class="col-md-12 mb-3">
                                    <label for="purpose" class="form-label fw-semibold">
                                        <i class="fas fa-clipboard text-primary me-1"></i>
                                        Purpose <span class="text-danger">*</span>
                                    </label>
                                    <textarea name="purpose" id="purpose" class="form-control" rows="3"
                                        placeholder="Describe the purpose of this transport service" required>{{ old('purpose', $employeeTransport->purpose ?? '') }}</textarea>
                                    @error('purpose')
                                        <small class="text-danger"><i
                                                class="fas fa-exclamation-circle me-1"></i>{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Schedule Section -->
                        <div class="mb-4">
                            <h6 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-calendar-alt me-2"></i>Schedule
                            </h6>
                            <div class="row">
                                <!-- Start Date -->
                                <div class="col-md-3 mb-3">
                                    <label for="start_date" class="form-label fw-semibold">
                                        <i class="fas fa-calendar text-primary me-1"></i>
                                        Start Date <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" name="start_date" id="start_date" class="form-control"
                                        value="{{ old('start_date', isset($employeeTransport) ? $employeeTransport->start_date->format('Y-m-d') : '') }}"
                                        required>
                                    @error('start_date')
                                        <small class="text-danger"><i
                                                class="fas fa-exclamation-circle me-1"></i>{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- End Date -->
                                <div class="col-md-3 mb-3">
                                    <label for="end_date" class="form-label fw-semibold">
                                        <i class="fas fa-calendar text-primary me-1"></i>
                                        End Date <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" name="end_date" id="end_date" class="form-control"
                                        value="{{ old('end_date', isset($employeeTransport) ? $employeeTransport->end_date->format('Y-m-d') : '') }}"
                                        required>
                                    @error('end_date')
                                        <small class="text-danger"><i
                                                class="fas fa-exclamation-circle me-1"></i>{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Pickup Time -->
                                <div class="col-md-3 mb-3">
                                    <label for="pickup_time" class="form-label fw-semibold">
                                        <i class="fas fa-clock text-primary me-1"></i>
                                        Pickup Time
                                    </label>
                                    <input type="time" name="pickup_time" id="pickup_time" class="form-control"
                                        value="{{ old('pickup_time', $employeeTransport->pickup_time ?? '') }}">
                                    @error('pickup_time')
                                        <small class="text-danger"><i
                                                class="fas fa-exclamation-circle me-1"></i>{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Drop Time -->
                                <div class="col-md-3 mb-3">
                                    <label for="drop_time" class="form-label fw-semibold">
                                        <i class="fas fa-clock text-primary me-1"></i>
                                        Drop Time
                                    </label>
                                    <input type="time" name="drop_time" id="drop_time" class="form-control"
                                        value="{{ old('drop_time', $employeeTransport->drop_time ?? '') }}">
                                    @error('drop_time')
                                        <small class="text-danger"><i
                                                class="fas fa-exclamation-circle me-1"></i>{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Route Information Section -->
                        <div class="mb-4">
                            <h6 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-route me-2"></i>Route Information
                            </h6>
                            <div class="row">
                                <!-- Pickup Location -->
                                <div class="col-md-6 mb-3">
                                    <label for="pickup_location" class="form-label fw-semibold">
                                        <i class="fas fa-map-marker-alt text-primary me-1"></i>
                                        Pickup Location
                                    </label>
                                    <input type="text" name="pickup_location" id="pickup_location"
                                        class="form-control" placeholder="Enter pickup location"
                                        value="{{ old('pickup_location', $employeeTransport->pickup_location ?? '') }}">
                                    @error('pickup_location')
                                        <small class="text-danger"><i
                                                class="fas fa-exclamation-circle me-1"></i>{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Drop Location -->
                                <div class="col-md-6 mb-3">
                                    <label for="drop_location" class="form-label fw-semibold">
                                        <i class="fas fa-map-marker-alt text-primary me-1"></i>
                                        Drop Location
                                    </label>
                                    <input type="text" name="drop_location" id="drop_location" class="form-control"
                                        placeholder="Enter drop location"
                                        value="{{ old('drop_location', $employeeTransport->drop_location ?? '') }}">
                                    @error('drop_location')
                                        <small class="text-danger"><i
                                                class="fas fa-exclamation-circle me-1"></i>{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Route Details -->
                                <div class="col-md-12 mb-3">
                                    <label for="route_details" class="form-label fw-semibold">
                                        <i class="fas fa-road text-primary me-1"></i>
                                        Route Details
                                    </label>
                                    <textarea name="route_details" id="route_details" class="form-control" rows="2"
                                        placeholder="Describe the route with stops/waypoints">{{ old('route_details', $employeeTransport->route_details ?? '') }}</textarea>
                                    @error('route_details')
                                        <small class="text-danger"><i
                                                class="fas fa-exclamation-circle me-1"></i>{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Additional Information Section -->
                        <div class="mb-4">
                            <h6 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-sticky-note me-2"></i>Additional Information
                            </h6>
                            <div class="row">
                                <!-- Special Requirements -->
                                <div class="col-md-6 mb-3">
                                    <label for="special_requirements" class="form-label fw-semibold">
                                        <i class="fas fa-exclamation-triangle text-primary me-1"></i>
                                        Special Requirements
                                    </label>
                                    <textarea name="special_requirements" id="special_requirements" class="form-control" rows="3"
                                        placeholder="Any special requirements...">{{ old('special_requirements', $employeeTransport->special_requirements ?? '') }}</textarea>
                                    @error('special_requirements')
                                        <small class="text-danger"><i
                                                class="fas fa-exclamation-circle me-1"></i>{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Remarks -->
                                <div class="col-md-6 mb-3">
                                    <label for="remarks" class="form-label fw-semibold">
                                        <i class="fas fa-comment text-secondary me-1"></i>
                                        Remarks
                                    </label>
                                    <textarea name="remarks" id="remarks" class="form-control" rows="3" placeholder="Additional remarks...">{{ old('remarks', $employeeTransport->remarks ?? '') }}</textarea>
                                    @error('remarks')
                                        <small class="text-danger"><i
                                                class="fas fa-exclamation-circle me-1"></i>{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-flex justify-content-end gap-2 border-top pt-3 mt-4">
                            <a href="{{ route('transport.employee_transports.index') }}" class="btn btn-secondary px-4">
                                <i class="fas fa-times me-1"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary px-5">
                                <i class="fas fa-save me-1"></i>
                                {{ isset($employeeTransport) ? 'Update Service' : 'Create Service' }}
                            </button>
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
            'use strict';

            // Initialize Select2
            $('.select2_list').select2({
                placeholder: function() {
                    return $(this).data('placeholder') || $(this).find('option:first').text();
                },
                allowClear: true,
                width: '100%'
            });

            // Helper functions
            function loading($el, text = 'Loading...') {
                $el.prop('disabled', true).html(`<option value="">${text}</option>`);
            }

            function reset($el, text) {
                $el.prop('disabled', false).html(`<option value="">${text}</option>`);
            }

            // Load Divisions + Chain (Department + Section)
            function loadDivisions() {
                const companyId = $('#company_id').val();
                if (!companyId) return;

                const branchId = $('#branch_id').val() || 'null';

                @if ($generalSettings->division_status == 1)
                    loading($('#division_id'));
                @endif
                @if ($generalSettings->department_status == 1)
                    reset($('#department_id'), 'Select Department');
                @endif
                @if ($generalSettings->section_status == 1)
                    reset($('#section_id'), 'Select Section');
                @endif

                @if ($generalSettings->division_status == 1)
                    $.get(`/get-divisions/${companyId}/${branchId}`, function(data) {
                        reset($('#division_id'), 'Select Division');
                        if (!data.length) {
                            $('#division_id').html('<option value="">No division found</option>');
                        } else {
                            $.each(data, function(_, item) {
                                $('#division_id').append(
                                    `<option value="${item.id}">${item.name}</option>`);
                            });
                        }
                        // Chain: Load departments after divisions
                        loadDepartments();
                    });
                @else
                    loadDepartments();
                @endif
            }

            // Load Departments + Chain (Section)
            function loadDepartments() {
                const companyId = $('#company_id').val();
                if (!companyId) return;

                const branchId = $('#branch_id').val() || 'null';
                const divisionId = $('#division_id').val() || 'null';

                @if ($generalSettings->department_status == 1)
                    loading($('#department_id'));
                @endif
                @if ($generalSettings->section_status == 1)
                    reset($('#section_id'), 'Select Section');
                @endif

                @if ($generalSettings->department_status == 1)
                    $.get(`/get-departments/${companyId}/${branchId}/${divisionId}`, function(data) {
                        reset($('#department_id'), 'Select Department');
                        if (!data.length) {
                            $('#department_id').html('<option value="">No department found</option>');
                        } else {
                            $.each(data, function(_, item) {
                                $('#department_id').append(
                                    `<option value="${item.id}">${item.department_name}</option>`
                                    );
                            });
                        }
                        // Chain: Load sections after departments
                        loadSections();
                    });
                @else
                    loadSections();
                @endif
            }

            // Load Sections
            function loadSections() {
                const companyId = $('#company_id').val();
                if (!companyId) return;

                const branchId = $('#branch_id').val() || 'null';
                const divisionId = $('#division_id').val() || 'null';
                const departmentId = $('#department_id').val() || 'null';

                @if ($generalSettings->section_status == 1)
                    loading($('#section_id'));

                    $.get(`/get-sections/${companyId}/${branchId}/${divisionId}/${departmentId}`, function(data) {
                        reset($('#section_id'), 'Select Section');
                        if (!data.length) {
                            $('#section_id').html('<option value="">No section found</option>');
                        } else {
                            $.each(data, function(_, item) {
                                $('#section_id').append(
                                    `<option value="${item.id}">${item.name}</option>`);
                            });
                        }
                    });
                @endif
            }

            // Company Change → Load Branch + Full Chain
            $('#company_id').on('change', function() {
                const companyId = $(this).val();
                if (!companyId) return;

                @if ($generalSettings->division_status == 1)
                    reset($('#division_id'), 'Select Division');
                @endif
                @if ($generalSettings->department_status == 1)
                    reset($('#department_id'), 'Select Department');
                @endif
                @if ($generalSettings->section_status == 1)
                    reset($('#section_id'), 'Select Section');
                @endif

                @if ($generalSettings->branch_status == 1)
                    loading($('#branch_id'));

                    $.get(`/get-units/${companyId}`, function(data) {
                        reset($('#branch_id'), 'Select Branch');
                        if (!data.length) {
                            $('#branch_id').html('<option value="">No branch found</option>');
                        } else {
                            $.each(data, function(_, item) {
                                $('#branch_id').append(
                                    `<option value="${item.id}">${item.name}</option>`);
                            });
                        }
                        // Chain: Load divisions after branches
                        loadDivisions();
                    }).fail(function(xhr, status, error) {
                        console.error('Failed to load branches:', error);
                        reset($('#branch_id'), 'Select Branch');
                        loadDivisions();
                    });
                @else
                    // No branches, load divisions directly
                    loadDivisions();
                @endif
            });

            // Branch Change → Load Division Chain
            @if ($generalSettings->branch_status == 1)
                $('#branch_id').on('change', function() {
                    loadDivisions();
                });
            @endif

            // Division Change → Load Departments + Sections
            @if ($generalSettings->division_status == 1)
                $('#division_id').on('change', function() {
                    loadDepartments();
                });
            @endif

            // Department Change → Load Sections
            @if ($generalSettings->department_status == 1)
                $('#department_id').on('change', function() {
                    loadSections();
                });
            @endif

            // Pre-populate hierarchy dropdowns on edit
            @if (isset($employeeTransport) && $employeeTransport->company_id)
                // Trigger company change to load cascade
                setTimeout(function() {
                    $('#company_id').trigger('change');

                    // Set values after load completes
                    @if ($generalSettings->branch_status == 1 && isset($employeeTransport->branch_id) && $employeeTransport->branch_id)
                        setTimeout(function() {
                            $('#branch_id').val('{{ $employeeTransport->branch_id }}').trigger(
                                'change');
                        }, 500);
                    @endif

                    @if ($generalSettings->division_status == 1 && isset($employeeTransport->division_id) && $employeeTransport->division_id)
                        setTimeout(function() {
                            $('#division_id').val('{{ $employeeTransport->division_id }}').trigger(
                                'change');
                        }, 1000);
                    @endif

                    @if (
                        $generalSettings->department_status == 1 &&
                            isset($employeeTransport->department_id) &&
                            $employeeTransport->department_id)
                        setTimeout(function() {
                            $('#department_id').val('{{ $employeeTransport->department_id }}')
                                .trigger('change');
                        }, 1500);
                    @endif

                    @if ($generalSettings->section_status == 1 && isset($employeeTransport->section_id) && $employeeTransport->section_id)
                        setTimeout(function() {
                            $('#section_id').val('{{ $employeeTransport->section_id }}');
                        }, 2000);
                    @endif
                }, 300);
            @endif
        });
    </script>
@endpush

