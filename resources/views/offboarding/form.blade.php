@extends('structure.master')

@section('content')
@php
    $isEdit = isset($offboarding);
    $currentType = $isEdit ? $offboarding->offboarding_type : ($type ?? 'resignation');
    $typeName = ucfirst($currentType);
    $generalSettings = \App\HelperClass::getGeneralSetting();
@endphp

<div class="row">
    <div class="col-lg-12">
        <div class="card border-0 shadow-sm rounded">
            <div class="card-header">
                <h5 class="card-title mb-0">{{ $isEdit ? 'Edit' : 'Add' }} {{ $typeName }}</h5>
            </div>

            <div class="card-body">
                <form id="offboardingForm" action="{{ $isEdit ? route('offboarding.update', $offboarding->id) : route('offboarding.store') }}" method="POST">
                    @csrf
                    @if($isEdit)
                        @method('PUT')
                    @endif

                    {{-- Offboarding Type --}}
                    <input type="hidden" name="offboarding_type" value="{{ $currentType }}">

                    {{-- Section 1: Organizational Hierarchy Filter (Only on Create) --}}
                    @if(!$isEdit)
                    <div class="row g-3 mb-4 pb-3 border-bottom">
                        <div class="col-12">
                            <h6 class="fw-semibold text-primary mb-0">
                                <i class="mdi mdi-sitemap me-2"></i>1. Organizational Hierarchy Filter (Employee Target)
                            </h6>
                        </div>

                        {{-- Company --}}
                        <div class="col-md-4">
                            <label for="company_id" class="form-label fw-semibold">Company</label>
                            <select class="form-select select2_list hierarchy-select" id="company_id" name="company_id">
                                <option value="">-- All Companies --</option>
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}" {{ (request('company_id') == $company->id) ? 'selected' : '' }}>{{ $company->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Branch --}}
                        @if(isset($generalSettings->branch_status) && $generalSettings->branch_status == 1)
                        <div class="col-md-4">
                            <label for="branch_id" class="form-label fw-semibold">Branch / Location</label>
                            <select class="form-select select2_list hierarchy-select" id="branch_id" name="branch_id">
                                <option value="">-- All Branches --</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}" {{ (request('branch_id') == $branch->id || request('location_id') == $branch->id) ? 'selected' : '' }}>{{ $branch->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        {{-- Division --}}
                        @if(isset($generalSettings->division_status) && $generalSettings->division_status == 1)
                        <div class="col-md-4">
                            <label for="division_id" class="form-label fw-semibold">Division</label>
                            <select class="form-select select2_list hierarchy-select" id="division_id" name="division_id">
                                <option value="">-- All Divisions --</option>
                                @foreach($divisions as $division)
                                    <option value="{{ $division->id }}" {{ (request('division_id') == $division->id) ? 'selected' : '' }}>{{ $division->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        {{-- Department --}}
                        @if(isset($generalSettings->department_status) && $generalSettings->department_status == 1)
                        <div class="col-md-6">
                            <label for="department_id" class="form-label fw-semibold">Department</label>
                            <select class="form-select select2_list hierarchy-select" id="department_id" name="department_id">
                                <option value="">-- All Departments --</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}" {{ (request('department_id') == $department->id) ? 'selected' : '' }}>{{ $department->department_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        {{-- Section --}}
                        @if(isset($generalSettings->section_status) && $generalSettings->section_status == 1)
                        <div class="col-md-6">
                            <label for="section_id" class="form-label fw-semibold">Section</label>
                            <select class="form-select select2_list hierarchy-select" id="section_id" name="section_id">
                                <option value="">-- All Sections --</option>
                                @foreach($sections as $sec)
                                    <option value="{{ $sec->id }}" {{ (request('section_id') == $sec->id || request('id') == $sec->id) ? 'selected' : '' }}>{{ $sec->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                    </div>
                    @endif

                    {{-- Section 2: Offboarding Details --}}
                    <div class="row g-3">
                        @if(!$isEdit)
                        <div class="col-12">
                            <h6 class="fw-semibold text-primary mb-0">
                                <i class="mdi mdi-account-card-details me-2"></i>2. Offboarding Details
                            </h6>
                        </div>
                        @endif

                        {{-- Offboarding Type (Locked / Disabled) --}}
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Offboarding Type <span class="text-danger">*</span></label>
                            <select class="form-select bg-light" disabled>
                                <option value="resignation" {{ $currentType === 'resignation' ? 'selected' : '' }}>Resignation</option>
                                <option value="termination" {{ $currentType === 'termination' ? 'selected' : '' }}>Termination</option>
                            </select>
                            <small class="text-muted">Locked based on menu selection.</small>
                        </div>

                        {{-- Employee Selection --}}
                        <div class="col-md-6">
                            <label for="employee_id" class="form-label fw-semibold">Select Employee <span class="text-danger">*</span></label>
                            @if($isEdit)
                                <input type="text" class="form-control bg-light" value="{{ $offboarding->employee?->full_name ?? 'N/A' }} (ID: {{ $offboarding->employee?->applicant_id ?? $offboarding->employee_id }})" readonly>
                                <input type="hidden" name="employee_id" value="{{ $offboarding->employee_id }}">
                            @else
                                <select class="form-select select2_list" id="employee_id" name="employee_id" required>
                                    <option value="">-- Select Employee --</option>
                                </select>
                                <small class="text-muted" id="employee-count-hint">Loading eligible employees...</small>
                            @endif
                        </div>

                        {{-- Status (Only on Edit) --}}
                        @if($isEdit)
                        <div class="col-md-6">
                            <label for="status" class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="pending" {{ $offboarding->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ $offboarding->status == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ $offboarding->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                <option value="cancelled" {{ $offboarding->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        @endif

                        {{-- Resignation / Notice Date --}}
                        <div class="col-md-6">
                            <label for="resignation_date" class="form-label fw-semibold">{{ $currentType === 'termination' ? 'Termination / Notice Date' : 'Resignation Date' }} <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="resignation_date" name="resignation_date" value="{{ $isEdit ? \Carbon\Carbon::parse($offboarding->resignation_date)->format('Y-m-d') : date('Y-m-d') }}" required>
                        </div>

                        {{-- Notice Period Days --}}
                        <div class="col-md-6">
                            <label for="notice_period_days" class="form-label fw-semibold">Notice Period (Days) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="notice_period_days" name="notice_period_days" value="{{ $isEdit ? $offboarding->notice_period_days : 30 }}" min="0" required>
                        </div>

                        {{-- Last Working Day --}}
                        <div class="col-md-6">
                            <label for="last_working_day" class="form-label fw-semibold">Last Working Day <span class="text-danger">*</span></label>
                            <input type="date" class="form-control bg-light" id="last_working_day" name="last_working_day" value="{{ $isEdit ? \Carbon\Carbon::parse($offboarding->last_working_day)->format('Y-m-d') : '' }}" required readonly>
                            <small class="text-muted">Auto-calculated based on date + notice period</small>
                        </div>

                        {{-- Reason --}}
                        <div class="col-md-12">
                            <label for="reason" class="form-label fw-semibold">Reason for {{ $typeName }} <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="reason" name="reason" rows="3" placeholder="Enter detailed reason..." required>{{ $isEdit ? $offboarding->reason : '' }}</textarea>
                        </div>

                        {{-- Remarks --}}
                        <div class="col-md-12">
                            <label for="remarks" class="form-label fw-semibold">Additional Remarks (Optional)</label>
                            <textarea class="form-control" id="remarks" name="remarks" rows="2" placeholder="Any additional comments...">{{ $isEdit ? $offboarding->remarks : '' }}</textarea>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="row mt-4 pt-3 border-top">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <a href="{{ route('offboarding.' . $currentType . '.index') }}" class="btn btn-light ms-2">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
$(document).ready(function() {
    // Initialize Select2 if available
    if ($.fn.select2) {
        $('.select2_list').select2({
            theme: 'bootstrap-5',
            allowClear: true,
            width: '100%'
        });
    }

    function calculateLastWorkingDay() {
        const dateVal = $('#resignation_date').val();
        const noticeDays = parseInt($('#notice_period_days').val()) || 0;

        if (dateVal) {
            const dateObj = new Date(dateVal);
            dateObj.setDate(dateObj.getDate() + noticeDays);
            
            const year = dateObj.getFullYear();
            const month = String(dateObj.getMonth() + 1).padStart(2, '0');
            const day = String(dateObj.getDate()).padStart(2, '0');

            $('#last_working_day').val(`${year}-${month}-${day}`);
        }
    }

    calculateLastWorkingDay();
    $('#resignation_date, #notice_period_days').on('input change', calculateLastWorkingDay);

    @if(!$isEdit)
    function loadEmployees() {
        const filters = {
            company_id: $('#company_id').val(),
            branch_id: $('#branch_id').val(),
            division_id: $('#division_id').val(),
            department_id: $('#department_id').val(),
            section_id: $('#section_id').val()
        };

        const $employeeSelect = $('#employee_id');
        $employeeSelect.html('<option value="">Loading eligible employees...</option>').prop('disabled', true);
        if ($.fn.select2 && $employeeSelect.hasClass('select2-hidden-accessible')) {
            $employeeSelect.trigger('change');
        }

        axios.get("{{ route('offboarding.get_employees_by_hierarchy') }}", { params: filters })
            .then(response => {
                const employees = response.data.data || response.data;
                $employeeSelect.html('<option value="">-- Select Employee --</option>');

                if (Array.isArray(employees) && employees.length) {
                    employees.forEach(emp => {
                        $employeeSelect.append(
                            `<option value="${emp.id}">${emp.full_name} (ID: ${emp.applicant_id || emp.id})</option>`
                        );
                    });
                    $('#employee-count-hint').text(`Found ${employees.length} eligible employee(s).`);
                } else {
                    $employeeSelect.append('<option value="" disabled>No active employees found matching selection</option>');
                    $('#employee-count-hint').text('No active employees found.');
                }
            })
            .catch(error => {
                console.error('Error loading employees:', error);
                $employeeSelect.html('<option value="">-- Error Loading Employees --</option>');
            })
            .finally(() => {
                $employeeSelect.prop('disabled', false);
                if ($.fn.select2 && $employeeSelect.hasClass('select2-hidden-accessible')) {
                    $employeeSelect.trigger('change');
                }
            });
    }

    loadEmployees();
    $('.hierarchy-select').on('change', loadEmployees);
    @endif

    // Axios Form Submission
    $('#offboardingForm').on('submit', function(e) {
        e.preventDefault();
        const form = this;
        const submitBtn = form.querySelector('button[type="submit"]');

        $('.invalid-feedback').remove();
        $('.is-invalid').removeClass('is-invalid');
        if (submitBtn) submitBtn.disabled = true;

        const formData = new FormData(form);

        axios.post(form.action, formData)
            .then(response => {
                const res = response.data;
                if (res.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: res.message,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = res.redirect || "{{ route('offboarding.' . $currentType . '.index') }}";
                    });
                }
            })
            .catch(error => {
                if (submitBtn) submitBtn.disabled = false;
                if (error.response && error.response.status === 422) {
                    const errors = error.response.data.errors;
                    if (errors) {
                        Object.keys(errors).forEach(key => {
                            const input = form.querySelector(`[name="${key}"]`);
                            if (input) {
                                input.classList.add('is-invalid');
                                const feedback = document.createElement('div');
                                feedback.className = 'invalid-feedback d-block';
                                feedback.innerText = errors[key][0];
                                input.after(feedback);
                            }
                        });
                    }
                    const msg = error.response.data.message || 'Validation error. Please check your inputs.';
                    Swal.fire({ icon: 'error', title: 'Validation Error', text: msg });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: error.response?.data?.message || 'Something went wrong. Please try again later.'
                    });
                }
            });
    });
});
</script>
@endsection
