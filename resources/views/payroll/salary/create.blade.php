@extends('structure.master')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-money-check-alt me-2"></i>
                            <h5 class="mb-0">Salary Process</h5>
                        </div>
                        <a href="#" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>Back to List
                        </a>
                    </div>
                </div>

                <div class="card-body p-4">
                    <form id="salaryProcessForm" action="#" method="post">
                        @csrf

                        <!-- Organization Hierarchy Section -->
                        <div class="mb-4">
                            <h6 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-sitemap me-2"></i>Organization Hierarchy
                            </h6>
                            <div class="row mb-2">
                                <!-- Company -->
                                <div class="col-md-6 mb-3">
                                    <label for="company_id" class="form-label fw-semibold">
                                        <i class="fas fa-building text-success me-1"></i>
                                        Company <span class="text-danger">*</span>
                                    </label>
                                    <select name="company_id" id="company_id" class="form-select select2_list" required>
                                        <option value="">-- Select Company --</option>
                                        @foreach ($companies as $company)
                                            <option value="{{ $company->id }}">{{ $company->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('company_id')
                                        <small class="text-danger"><i
                                                class="fas fa-exclamation-circle me-1"></i>{{ $message }}</small>
                                    @enderror
                                </div>

                                @if (App\HelperClass::getGeneralSetting()->branch_status == 1)
                                    <!-- Branch -->
                                    <div class="col-md-6 mb-3">
                                        <label for="business_unit_id" class="form-label fw-semibold">
                                            <i class="fas fa-map-marker-alt text-danger me-1"></i>
                                            Branch
                                        </label>
                                        <select name="branch_unit_id" id="business_unit_id"
                                            class="form-select select2_list">
                                            <option value="">Select Branch</option>
                                        </select>
                                        @error('branch_unit_id')
                                            <small class="text-danger"><i
                                                    class="fas fa-exclamation-circle me-1"></i>{{ $message }}</small>
                                        @enderror
                                    </div>
                                @endif
                            </div>

                            <div class="row">
                                @if (App\HelperClass::getGeneralSetting()->division_status == 1)
                                    <!-- Division -->
                                    <div class="col-md-6 mb-3">
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

                                @if (App\HelperClass::getGeneralSetting()->department_status == 1)
                                    <!-- Department -->
                                    <div class="col-md-6 mb-3">
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
                            </div>

                            <div class="row">
                                @if (App\HelperClass::getGeneralSetting()->section_status == 1)
                                    <!-- Section -->
                                    <div class="col-md-6 mb-3">
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

                                <!-- Employee Select -->
                                <div class="col-md-12 mb-3">
                                    <label for="employeeSelect" class="form-label fw-semibold">
                                        <i class="fas fa-user text-primary me-1"></i>
                                        Select Employee <span class="text-danger">*</span>
                                    </label>
                                    <select id="employeeSelect" class="form-select select2" name="employee_id" required>
                                        <option value="">-- Select Employee --</option>
                                        @foreach ($employees as $employee)
                                            <option value="{{ $employee->id }}">{{ $employee->full_name }}
                                                ({{ $employee->system_id }})</option>
                                        @endforeach
                                    </select>
                                    @error('employee_id')
                                        <small class="text-danger"><i
                                                class="fas fa-exclamation-circle me-1"></i>{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-flex justify-content-end gap-2 border-top pt-3 mt-4">
                            <a href="#" class="btn btn-secondary px-4">
                                <i class="fas fa-times me-1"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary px-5">
                                <i class="fas fa-save me-1"></i>Process Salary
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

            // Initialize Select2 for all dropdowns with select2_list class
            $('.select2_list').select2({
                placeholder: function() {
                    return $(this).data('placeholder') || $(this).find('option:first').text();
                },
                allowClear: true,
                width: '100%'
            });

            // Initialize Employee Select2
            $('#employeeSelect').select2({
                placeholder: '-- Select Employee --',
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

            // ======================================================================
            // ORGANIZATIONAL FILTERS - EXACT REPLICATION
            // ======================================================================

            // Load Divisions + Chain (Department + Section)
            function loadDivisions() {
                const companyId = $('#company_id').val();
                if (!companyId) return;

                const locationId = $('#business_unit_id').val() || 'null';

                loading($('#division_id'));
                reset($('#department_id'), 'Select Department');
                reset($('#section_id'), 'Select Section');

                $.get(`/get-divisions/${companyId}/${locationId}`, function(data) {
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
            }

            // Load Departments + Chain (Section)
            function loadDepartments() {
                const companyId = $('#company_id').val();
                if (!companyId) return;

                const locationId = $('#business_unit_id').val() || 'null';
                const divisionId = $('#division_id').val() || 'null';

                loading($('#department_id'));
                reset($('#section_id'), 'Select Section');

                $.get(`/get-departments/${companyId}/${locationId}/${divisionId}`, function(data) {
                    reset($('#department_id'), 'Select Department');
                    if (!data.length) {
                        $('#department_id').html('<option value="">No department found</option>');
                    } else {
                        $.each(data, function(_, item) {
                            $('#department_id').append(
                                `<option value="${item.id}">${item.department_name}</option>`);
                        });
                    }
                    // Chain: Load sections after departments
                    loadSections();
                });
            }

            // Load Sections
            function loadSections() {
                const companyId = $('#company_id').val();
                if (!companyId) return;

                const locationId = $('#business_unit_id').val() || 'null';
                const divisionId = $('#division_id').val() || 'null';
                const departmentId = $('#department_id').val() || 'null';

                loading($('#section_id'));

                $.get(`/get-sections/${companyId}/${locationId}/${divisionId}/${departmentId}`, function(data) {
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
            }

            // Company Change → Load Branch + Full Chain
            $('#company_id').on('change', function() {
                const companyId = $(this).val();
                if (!companyId) return;

                reset($('#division_id'), 'Select Division');
                reset($('#department_id'), 'Select Department');
                reset($('#section_id'), 'Select Section');

                @if (App\HelperClass::getGeneralSetting()->branch_status == 1)
                    loading($('#business_unit_id'));

                    $.get(`/get-units/${companyId}`, function(data) {
                        reset($('#business_unit_id'), 'Select Branch');
                        if (!data.length) {
                            $('#business_unit_id').html(
                                '<option value="">No branch found</option>');
                        } else {
                            $.each(data, function(_, item) {
                                $('#business_unit_id').append(
                                    `<option value="${item.id}">${item.name}</option>`);
                            });
                        }
                        // Immediately load the full chain after branches
                        loadDivisions();
                    }).fail(function(xhr, status, error) {
                        console.error('Failed to load branches:', error);
                        reset($('#business_unit_id'), 'Select Branch');
                    });
                @else
                    // No branch → directly load divisions + chain
                    loadDivisions();
                @endif
            });

            // Branch Change → Reload Full Chain
            $('#business_unit_id').on('change', function() {
                loadDivisions(); // This will chain to department → section
            });

            // Division Change → Reload Department + Section
            $('#division_id').on('change', function() {
                loadDepartments(); // This will chain to section
            });

            // Department Change → Reload Section
            $('#department_id').on('change', function() {
                loadSections();
            });

        });
    </script>
@endpush
