@extends('structure.master')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card border-0 shadow-lg rounded-4 my-4">
            <div class="card-header border-bottom rounded-top-4 p-4 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3 d-inline-flex align-items-center justify-content-center">
                        <i class="mdi mdi-door-open text-primary fs-4"></i>
                    </div>
                    <div>
                        <h5 class="card-title mb-0 text-dark fw-bold">Submit Employee Resignation</h5>
                        <small class="text-muted">Select employee via organizational hierarchy and enter resignation parameters</small>
                    </div>
                </div>
                <a href="{{ route('resignation.index') }}" class="btn btn-outline-secondary btn-sm rounded-3">
                    <i class="mdi mdi-arrow-left me-1"></i> Back to List
                </a>
            </div>

            <div class="card-body p-4">
                <form id="resignationForm" action="{{ route('resignation.store') }}" method="POST">
                    @csrf

                    {{-- Section 1: Organizational Hierarchy Filter --}}
                    <div class="card border mb-4 rounded-3">
                        <div class="card-header bg-light">
                            <h6 class="mb-0 fw-semibold text-primary">
                                <i class="mdi mdi-sitemap me-2"></i>1. Organizational Hierarchy Filter (Employee Target)
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                {{-- Company --}}
                                <div class="col-md-4">
                                    <label for="company_id" class="form-label fw-semibold">Company</label>
                                    <select class="form-select hierarchy-select" id="company_id" name="company_id">
                                        <option value="">-- All Companies --</option>
                                        @foreach($companies as $company)
                                            <option value="{{ $company->id }}">{{ $company->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Branch / Company Location --}}
                                <div class="col-md-4">
                                    <label for="branch_id" class="form-label fw-semibold">Branch / Location</label>
                                    <select class="form-select hierarchy-select" id="branch_id" name="branch_id">
                                        <option value="">-- All Branches --</option>
                                        @foreach($branches as $branch)
                                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Division --}}
                                <div class="col-md-4">
                                    <label for="division_id" class="form-label fw-semibold">Division</label>
                                    <select class="form-select hierarchy-select" id="division_id" name="division_id">
                                        <option value="">-- All Divisions --</option>
                                        @foreach($divisions as $division)
                                            <option value="{{ $division->id }}">{{ $division->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Department --}}
                                <div class="col-md-6">
                                    <label for="department_id" class="form-label fw-semibold">Department</label>
                                    <select class="form-select hierarchy-select" id="department_id" name="department_id">
                                        <option value="">-- All Departments --</option>
                                        @foreach($departments as $department)
                                            <option value="{{ $department->id }}">{{ $department->department_name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Section --}}
                                <div class="col-md-6">
                                    <label for="section_id" class="form-label fw-semibold">Section</label>
                                    <select class="form-select hierarchy-select" id="section_id" name="section_id">
                                        <option value="">-- All Sections --</option>
                                        @foreach($sections as $section)
                                            <option value="{{ $section->id }}">{{ $section->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Section 2: Employee & Resignation Details --}}
                    <div class="card border mb-4 rounded-3">
                        <div class="card-header bg-light">
                            <h6 class="mb-0 fw-semibold text-primary">
                                <i class="mdi mdi-account-card-details me-2"></i>2. Employee & Resignation Parameters
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                {{-- Target Employee --}}
                                <div class="col-md-12">
                                    <label for="employee_id" class="form-label fw-semibold">Select Employee <span class="text-danger">*</span></label>
                                    <select class="form-select" id="employee_id" name="employee_id" required>
                                        <option value="">-- Select Employee --</option>
                                    </select>
                                    <small class="text-muted" id="employee-count-hint">Loading eligible employees...</small>
                                </div>

                                {{-- Resignation Date --}}
                                <div class="col-md-4">
                                    <label for="resignation_date" class="form-label fw-semibold">Resignation Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="resignation_date" name="resignation_date" value="{{ date('Y-m-d') }}" required>
                                </div>

                                {{-- Notice Period Days --}}
                                <div class="col-md-4">
                                    <label for="notice_period_days" class="form-label fw-semibold">Notice Period (Days) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="notice_period_days" name="notice_period_days" value="30" min="0" required>
                                </div>

                                {{-- Last Working Day --}}
                                <div class="col-md-4">
                                    <label for="last_working_day" class="form-label fw-semibold">Last Working Day <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="last_working_day" name="last_working_day" required readonly>
                                    <small class="text-muted">Auto-calculated based on resignation date + notice period</small>
                                </div>

                                {{-- Reason --}}
                                <div class="col-md-12">
                                    <label for="reason" class="form-label fw-semibold">Reason for Resignation <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="reason" name="reason" rows="3" placeholder="Enter detailed reason for resignation..." required></textarea>
                                </div>

                                {{-- Remarks --}}
                                <div class="col-md-12">
                                    <label for="remarks" class="form-label fw-semibold">Additional Remarks (Optional)</label>
                                    <textarea class="form-control" id="remarks" name="remarks" rows="2" placeholder="Any additional notes or comments..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('resignation.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="mdi mdi-check-circle me-1"></i> Submit Resignation
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
$(document).ready(function() {
    // ── 1. Calculate Last Working Day ───────────────────────────────────────
    function calculateLastWorkingDay() {
        const resignationDateVal = $('#resignation_date').val();
        const noticeDays = parseInt($('#notice_period_days').val()) || 0;

        if (resignationDateVal) {
            const dateObj = new Date(resignationDateVal);
            dateObj.setDate(dateObj.getDate() + noticeDays);
            
            const year = dateObj.getFullYear();
            const month = String(dateObj.getMonth() + 1).padStart(2, '0');
            const day = String(dateObj.getDate()).padStart(2, '0');

            $('#last_working_day').val(`${year}-${month}-${day}`);
        }
    }

    calculateLastWorkingDay();
    $('#resignation_date, #notice_period_days').on('input change', calculateLastWorkingDay);

    // ── 2. Load Employees based on 5-tier Hierarchy Cascade ────────────────
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

        axios.get("{{ route('resignation.get_employees_by_hierarchy') }}", { params: filters })
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
            });
    }

    // Initial load
    loadEmployees();

    // Trigger load on hierarchy selection change
    $('.hierarchy-select').on('change', function() {
        loadEmployees();
    });

    // ── 3. Axios Form Submission ──────────────────────────────────────────────
    $('#resignationForm').on('submit', function(e) {
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
                        window.location.href = res.redirect || "{{ route('resignation.index') }}";
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
