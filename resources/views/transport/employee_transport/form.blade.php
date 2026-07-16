@extends('structure.master')
@section('content')
    <style>
        /* Serpentine timeline styles for Route Map preview */
        .route-line {
            position: absolute;
            top: 16px;
            height: 4px;
            background: var(--bs-border-color, #e9ecef);
            z-index: 1;
        }
        .route-connector-right {
            position: absolute;
            right: 5%;
            width: 7.5%;
            top: 16px;
            height: calc(100% + 48px);
            border: 4px solid var(--bs-border-color, #e9ecef);
            border-left: 0;
            border-radius: 0 16px 16px 0;
            z-index: 1;
        }
        .route-connector-left {
            position: absolute;
            left: 5%;
            width: 7.5%;
            top: 16px;
            height: calc(100% + 48px);
            border: 4px solid var(--bs-border-color, #e9ecef);
            border-right: 0;
            border-radius: 16px 0 0 16px;
            z-index: 1;
        }
        .route-step {
            text-align: center;
            position: relative;
        }
        .step-icon {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px auto;
            border: 3px solid var(--bs-modal-bg, #fff);
            box-shadow: 0 0 0 2px var(--bs-border-color, #e9ecef);
            font-size: 14px;
            color: white;
            font-weight: bold;
            z-index: 3;
            position: relative;
            transition: all 0.3s ease;
        }
        .step-icon.bg-success {
            box-shadow: 0 0 0 2px #2ecc71;
            background-color: #2ecc71 !important;
        }
        .step-icon.bg-warning {
            box-shadow: 0 0 0 2px #f1c40f;
            background-color: #f1c40f !important;
            color: #333;
        }
        .step-icon.bg-danger {
            box-shadow: 0 0 0 2px #e74c3c;
            background-color: #e74c3c !important;
        }
        .step-label {
            font-size: 0.72rem;
            color: #888;
            text-transform: uppercase;
            font-weight: 700;
            display: block;
            margin-bottom: 2px;
        }
        .step-name {
            font-size: 0.82rem;
            color: var(--bs-body-color, #212529);
            font-weight: 700;
            display: block;
            padding: 0 5px;
            word-break: break-word;
        }
        #route_preview_wrapper .bg-light {
            background-color: var(--bs-tertiary-bg, #f8f9fa) !important;
        }
    </style>
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
                                <!-- Route Map Selection -->
                                <div class="col-md-12 mb-3">
                                    <label for="route_map_id" class="form-label fw-semibold">
                                        <i class="fas fa-map text-primary me-1"></i>
                                        Route Map <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('route_map_id') is-invalid @enderror"
                                        name="route_map_id" id="route_map_id" required>
                                        <option value="">Select Predefined Route Map</option>
                                        @foreach ($routeMaps as $route)
                                            <option value="{{ $route->id }}"
                                                {{ (isset($employeeTransport) && $employeeTransport->route_map_id == $route->id) || old('route_map_id') == $route->id ? 'selected' : '' }}>
                                                {{ $route->route_name }} ({{ $route->start_point }} to {{ $route->end_point }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('route_map_id')
                                        <small class="text-danger"><i
                                                class="fas fa-exclamation-circle me-1"></i>{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Route Map Preview -->
                                <div class="col-md-12 mb-3 d-none" id="route_preview_wrapper">
                                    <label class="form-label fw-semibold">
                                        <i class="fas fa-eye text-primary me-1"></i>Route Preview
                                    </label>
                                    <div class="border rounded p-4 bg-light position-relative">
                                        <div class="position-relative" id="route_preview_steps" style="z-index: 2;">
                                            <!-- Populated via JS -->
                                        </div>
                                    </div>
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

            // Route Map Preview Logic
            const routeMaps = @json($routeMaps);

            function updateRoutePreview() {
                const routeId = $('#route_map_id').val();
                const previewWrapper = $('#route_preview_wrapper');
                const stepsContainer = $('#route_preview_steps');
                
                stepsContainer.empty();

                if (!routeId) {
                    previewWrapper.addClass('d-none');
                    return;
                }

                const routeMap = routeMaps.find(r => r.id == routeId);
                if (!routeMap) {
                    previewWrapper.addClass('d-none');
                    return;
                }

                previewWrapper.removeClass('d-none');

                const steps = [];

                // 1. Start Point
                steps.push({
                    label: 'Start',
                    name: routeMap.start_point,
                    class: 'bg-success',
                    icon: '<i class="mdi mdi-play" style="font-size: 12px; margin-left: 2px;"></i>'
                });

                // 2. Via Points
                const vias = Array.isArray(routeMap.via_points) ? routeMap.via_points : [];
                vias.forEach(function(point, index) {
                    steps.push({
                        label: `Stopover ${index + 1}`,
                        name: point,
                        class: 'bg-warning',
                        icon: (index + 1).toString()
                    });
                });

                // 3. End Point
                steps.push({
                    label: 'Destination',
                    name: routeMap.end_point,
                    class: 'bg-danger',
                    icon: '<i class="mdi mdi-flag-variant" style="font-size: 12px;"></i>'
                });

                // Break steps into rows of 4
                const chunks = [];
                for (let i = 0; i < steps.length; i += 4) {
                    chunks.push(steps.slice(i, i + 4));
                }

                chunks.forEach(function(chunk, rowIndex) {
                    const isLastRow = (rowIndex === chunks.length - 1);
                    const isOddRow = (rowIndex % 2 === 1);
                    const m = chunk.length;

                    // Determine horizontal line style
                    let lineStyle = '';
                    if (!isLastRow) {
                        lineStyle = 'left: 12.5%; right: 12.5%;';
                    } else {
                        if (m > 1) {
                            if (!isOddRow) {
                                lineStyle = `left: 12.5%; width: ${(m - 1) * 25}%;`;
                            } else {
                                lineStyle = `right: 12.5%; width: ${(m - 1) * 25}%;`;
                            }
                        } else {
                            lineStyle = 'display: none;';
                        }
                    }

                    // Build row container
                    let rowHtml = `
                        <div class="route-row position-relative d-flex align-items-start justify-content-start ${isOddRow ? 'flex-row-reverse' : ''}" style="margin-bottom: 48px;">
                            <!-- Horizontal Connector Line -->
                            <div class="route-line" style="${lineStyle}"></div>
                    `;

                    // Add U-turn connector if not the last row
                    if (!isLastRow) {
                        if (!isOddRow) {
                            rowHtml += `<div class="route-connector-right"></div>`;
                        } else {
                            rowHtml += `<div class="route-connector-left"></div>`;
                        }
                    }

                    // Render each step in this row (width 25%)
                    chunk.forEach(function(step) {
                        rowHtml += `
                            <div class="route-step" style="width: 25%; z-index: 2;">
                                <div class="step-icon ${step.class}">
                                    ${step.icon}
                                </div>
                                <span class="step-label">${step.label}</span>
                                <span class="step-name">${step.name}</span>
                            </div>
                        `;
                    });

                    rowHtml += `</div>`;
                    stepsContainer.append(rowHtml);
                });
            }

            $('#route_map_id').on('change', updateRoutePreview);

            // Trigger on load
            updateRoutePreview();
        });
    </script>
@endpush

