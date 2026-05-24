@extends('structure.master')

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Main Card -->
        <div class="card shadow-lg border-0 rounded-4 my-4">
        <!-- Form Body -->
        <div class="card-body p-4 p-md-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fs-4 fw-bold text-dark mb-0">Career Movement Application</h2>
                <a href="{{ route('transfer.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                    <i class="bi bi-list me-1"></i> View Logs
                </a>
            </div>

            @php
                $isEmployee = auth()->user()->user_type === 'Employee';
                $loggedInEmployeeId = auth()->user()->employee_id;
                $loggedInEmployeeName = auth()->user()->employee?->full_name ?? auth()->user()->name;
                
                // Level Weights
                $levels = [
                    'company' => 1,
                    'business_unit' => 2,
                    'division' => 3,
                    'department' => 4,
                    'section' => 5,
                ];
                
                $currentLevel = $isEmployee ? ($setting->employee_transfer_level ?? 'company') : ($setting->supervisor_transfer_level ?? 'company');
                $levelWeight = $levels[$currentLevel] ?? 1;
            @endphp

            <form id="transferForm">
                <!-- Basic Information Section -->
                <div class="mb-5">
                    <div class="d-flex align-items-center mb-4">
                        <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3 d-inline-flex align-items-center justify-content-center">
                            <i class="bi bi-person-badge text-primary fs-4"></i>
                        </div>
                        <h3 class="fs-5 fw-bold text-dark mb-0">Employee Information</h3>
                    </div>

                    <!-- Employee Selection -->
                    <div class="card border shadow-sm mb-4">
                        <div class="card-body p-4">
                            <label for="employee_id" class="form-label fw-semibold text-dark mb-3 d-flex align-items-center">
                                <i class="bi bi-person text-primary me-2 fs-5"></i>
                                <span>Select Employee</span>
                                <span class="badge bg-danger ms-2">Required</span>
                            </label>
                            <select name="employee_id" id="employee_id" class="form-select form-select-lg select2" required @if($isEmployee) disabled @endif>
                                <option value="">Select Employee</option>
                                @if($isEmployee)
                                    <option value="{{ $loggedInEmployeeId }}" selected>{{ $loggedInEmployeeName }}</option>
                                @endif
                            </select>
                            @if($isEmployee)
                                <input type="hidden" name="employee_id" id="hidden_employee_id" value="{{ $loggedInEmployeeId }}">
                            @endif
                            <div class="form-text mt-2">
                                <i class="bi bi-info-circle me-1"></i>
                                Choose the employee whose office location will be updated.
                            </div>
                        </div>
                    </div>

                    <!-- Remarks -->
                    <div class="card border shadow-sm mb-4">
                        <div class="card-body p-4">
                            <label for="remarks" class="form-label fw-semibold text-dark mb-3 d-flex align-items-center">
                                <i class="bi bi-chat-left-text text-info me-2 fs-5"></i>
                                <span>Remarks (Career Movement Cause)</span>
                            </label>
                            <textarea name="remarks" id="remarks" class="form-control form-control-lg" rows="3" placeholder="Enter transfer reason or additional notes..."></textarea>
                        </div>
                    </div>
                </div>

                <!-- Office Information Section -->
                <div class="mb-5">
                    <div class="d-flex align-items-center mb-4">
                        <div class="bg-success bg-opacity-10 rounded-circle p-3 me-3 d-inline-flex align-items-center justify-content-center">
                            <i class="bi bi-geo-alt text-success fs-4"></i>
                        </div>
                        <h3 class="fs-5 fw-bold text-dark mb-0">Requested Office Information</h3>
                    </div>

                    <div class="card border shadow-sm">
                        <div class="card-body p-4">
                            <div class="row g-4">
                                <!-- Company -->
                                <div class="col-md-6">
                                    <label for="requested_company_id" class="form-label fw-semibold">Company <span class="text-danger">*</span></label>
                                    <select name="requested_company_id" id="requested_company_id" class="form-select select2_list" required @if($levelWeight > 1) disabled @endif>
                                        <option value="">Select Company</option>
                                    </select>
                                    @if($levelWeight > 1) <input type="hidden" name="requested_company_id" id="hidden_company_id"> @endif
                                </div>

                                <!-- Business Unit -->
                                <div class="col-md-6">
                                    <label for="requested_business_unit_id" class="form-label fw-semibold">Business Unit / Branch</label>
                                    <select name="requested_business_unit_id" id="requested_business_unit_id" class="form-select select2_list" @if($levelWeight > 2) disabled @endif>
                                        <option value="">Select Business Unit</option>
                                    </select>
                                    @if($levelWeight > 2) <input type="hidden" name="requested_business_unit_id" id="hidden_unit_id"> @endif
                                </div>

                                <!-- Division -->
                                <div class="col-md-6">
                                    <label for="requested_division_id" class="form-label fw-semibold">Division</label>
                                    <select name="requested_division_id" id="requested_division_id" class="form-select select2_list" @if($levelWeight > 3) disabled @endif>
                                        <option value="">Select Division</option>
                                    </select>
                                    @if($levelWeight > 3) <input type="hidden" name="requested_division_id" id="hidden_division_id"> @endif
                                </div>

                                <!-- Department -->
                                <div class="col-md-6">
                                    <label for="requested_department_id" class="form-label fw-semibold">Department</label>
                                    <select name="requested_department_id" id="requested_department_id" class="form-select select2_list" @if($levelWeight > 4) disabled @endif>
                                        <option value="">Select Department</option>
                                    </select>
                                    @if($levelWeight > 4) <input type="hidden" name="requested_department_id" id="hidden_department_id"> @endif
                                </div>

                                <!-- Section -->
                                <div class="col-md-12">
                                    <label for="requested_section_id" class="form-label fw-semibold">Section</label>
                                    <select name="requested_section_id" id="requested_section_id" class="form-select select2_list" @if($levelWeight > 5) disabled @endif>
                                        <option value="">Select Section</option>
                                    </select>
                                    @if($levelWeight > 5) <input type="hidden" name="requested_section_id" id="hidden_section_id"> @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex justify-content-end gap-3 mt-4">
                    <a href="{{ route('transfer.index') }}" class="btn btn-lg btn-outline-secondary px-4 px-md-5 rounded-3">
                        <i class="bi bi-x-circle me-2"></i>Cancel
                    </a>
                    <button type="submit" class="btn btn-lg btn-dark px-4 px-md-5 rounded-3 shadow">
                        <i class="bi bi-send-fill me-2"></i>Submit Application
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Footer Info -->
    <div class="text-center mt-4 text-muted small">
        <i class="bi bi-info-circle me-1"></i>
        Applications will follow the configured approval workflow
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
$(document).ready(function() {
    // Select Elements
    const employeeSelect = $('#employee_id');
    const companySelect = $('#requested_company_id');
    const unitSelect = $('#requested_business_unit_id');
    const divisionSelect = $('#requested_division_id');
    const departmentSelect = $('#requested_department_id');
    const sectionSelect = $('#requested_section_id');

    const levelWeight = {{ $levelWeight }};
    const fieldWeights = {
        'requested_company_id': 1,
        'requested_business_unit_id': 2,
        'requested_division_id': 3,
        'requested_department_id': 4,
        'requested_section_id': 5
    };

    function shouldBeDisabled(el) {
        const id = $(el).attr('id');
        return levelWeight > (fieldWeights[id] || 0);
    }

    function loading($el, text = 'Loading...') {
        $el.prop('disabled', true).html(`<option value="">${text}</option>`);
    }

    function reset($el, text) {
        if (shouldBeDisabled($el)) {
            $el.prop('disabled', true);
        } else {
            $el.prop('disabled', false).html(`<option value="">${text}</option>`);
        }
    }

    // -------------------------
    // Load Divisions + Chain (Department + Section)
    // -------------------------
    function loadDivisions(prefix) {
        const companyId = $(`#${prefix}_company_id`).val();
        if (!companyId) return;

        const locationId = $(`#${prefix}_business_unit_id`).val() || 'null';

        loading($(`#${prefix}_division_id`));
        reset($(`#${prefix}_department_id`), 'Select Department');
        reset($(`#${prefix}_section_id`), 'Select Section');

        axios.get(`/get-divisions/${companyId}/${locationId}`)
            .then(res => {
                const data = res.data;
                reset($(`#${prefix}_division_id`), 'Select Division');
                if (!data.length) {
                    $(`#${prefix}_division_id`).html('<option value="">No division found</option>');
                } else {
                    data.forEach(item => {
                        $(`#${prefix}_division_id`).append(`<option value="${item.id}">${item.name}</option>`);
                    });
                }
                // Chain: Load departments after divisions
                loadDepartments(prefix);
            })
            .catch(err => console.error(err));
    }

    // -------------------------
    // Load Departments + Chain (Section)
    // -------------------------
    function loadDepartments(prefix) {
        const companyId = $(`#${prefix}_company_id`).val();
        if (!companyId) return;

        const locationId = $(`#${prefix}_business_unit_id`).val() || 'null';
        const divisionId = $(`#${prefix}_division_id`).val() || 'null';

        loading($(`#${prefix}_department_id`));
        reset($(`#${prefix}_section_id`), 'Select Section');

        axios.get(`/get-departments/${companyId}/${locationId}/${divisionId}`)
            .then(res => {
                const data = res.data;
                reset($(`#${prefix}_department_id`), 'Select Department');
                if (!data.length) {
                    $(`#${prefix}_department_id`).html('<option value="">No department found</option>');
                } else {
                    data.forEach(item => {
                        $(`#${prefix}_department_id`).append(`<option value="${item.id}">${item.department_name}</option>`);
                    });
                }
                // Chain: Load sections after departments
                loadSections(prefix);
            })
            .catch(err => console.error(err));
    }

    // -------------------------
    // Load Sections
    // -------------------------
    function loadSections(prefix) {
        const companyId = $(`#${prefix}_company_id`).val();
        if (!companyId) return;

        const locationId = $(`#${prefix}_business_unit_id`).val() || 'null';
        const divisionId = $(`#${prefix}_division_id`).val() || 'null';
        const departmentId = $(`#${prefix}_department_id`).val() || 'null';

        loading($(`#${prefix}_section_id`));

        axios.get(`/get-sections/${companyId}/${locationId}/${divisionId}/${departmentId}`)
            .then(res => {
                const data = res.data;
                reset($(`#${prefix}_section_id`), 'Select Section');
                if (!data.length) {
                    $(`#${prefix}_section_id`).html('<option value="">No section found</option>');
                } else {
                    data.forEach(item => {
                        $(`#${prefix}_section_id`).append(`<option value="${item.id}">${item.name}</option>`);
                    });
                }
            })
            .catch(err => console.error(err));
    }

    // -------------------------
    // Company Change → Load Branch + Full Chain
    // -------------------------
    $('#requested_company_id').on('change', function () {
        const prefix = 'requested';
        const companyId = $(this).val();
        if (!companyId) return;

        reset($(`#${prefix}_division_id`), 'Select Division');
        reset($(`#${prefix}_department_id`), 'Select Department');
        reset($(`#${prefix}_section_id`), 'Select Section');

        @if(\App\HelperClass::getGeneralSetting()->branch_status == '1')
        loading($(`#${prefix}_business_unit_id`));

        axios.get(`/get-units/${companyId}`)
            .then(res => {
                const data = res.data;
                reset($(`#${prefix}_business_unit_id`), 'Select Branch');
                if (!data.length) {
                    $(`#${prefix}_business_unit_id`).html('<option value="">No branch found</option>');
                } else {
                    data.forEach(item => {
                        $(`#${prefix}_business_unit_id`).append(`<option value="${item.id}">${item.name}</option>`);
                    });
                }
                // Immediately load the full chain after branches
                loadDivisions(prefix);
            })
            .catch(err => console.error(err));
        @else
        // No branch → directly load divisions + chain
        loadDivisions(prefix);
        @endif
    });

    // -------------------------
    // Branch Change → Reload Full Chain
    // -------------------------
    $('#requested_business_unit_id').on('change', function () {
        loadDivisions('requested');
    });

    // -------------------------
    // Division Change → Reload Department + Section
    // -------------------------
    $('#requested_division_id').on('change', function () {
        loadDepartments('requested');
    });

    // -------------------------
    // Department Change → Reload Section
    // -------------------------
    $('#requested_department_id').on('change', function () {
        loadSections('requested');
    });

    // Initial Data Fetch
    @if(!$isEmployee)
    fetchEmployees();
    @endif
    fetchCompanies();

    @if(!$isEmployee)
    employeeSelect.on('change', function() {
        if (this.value) {
            fetchCurrentOfficeInfo(this.value);
        }
    });
    @else
    fetchCurrentOfficeInfo('{{ $loggedInEmployeeId }}');
    @endif

    // Fetch Functions
    function fetchEmployees() {
        axios.get('{{ route('transfer.api.employees') }}')
            .then(res => {
                employeeSelect.html('<option value="">Select Employee</option>');
                res.data.data.forEach(item => {
                    employeeSelect.append(`<option value="${item.id}">${item.full_name} (${item.applicant_id})</option>`);
                });
            })
            .catch(err => console.error(err));
    }

    function fetchCompanies() {
        axios.get('{{ route('transfer.api.companies') }}')
            .then(res => {
                companySelect.html('<option value="">Select Company</option>');
                res.data.data.forEach(item => {
                    companySelect.append(`<option value="${item.id}">${item.name}</option>`);
                });
            })
            .catch(err => console.error(err));
    }

    function fetchCurrentOfficeInfo(employeeId) {
        axios.get(`/get-office-info/${employeeId}`)
            .then(res => {
                const info = res.data;
                if (info) {
                    const weight = {{ $levelWeight }};
                    
                    // 1. Company (Weight 1)
                    // If weight > 1, Company is locked to current
                    if (info.current_company_id && weight > 1) {
                        companySelect.val(info.current_company_id).trigger('change');
                        $('#hidden_company_id').val(info.current_company_id);
                    }
                    
                    // 2. Branch/Unit (Weight 2)
                    if (info.current_business_unit_id && weight > 2) {
                        setTimeout(() => {
                            unitSelect.val(info.current_business_unit_id).trigger('change');
                            $('#hidden_unit_id').val(info.current_business_unit_id);
                        }, 600);
                    }

                    // 3. Division (Weight 3)
                    if (info.current_division_id && weight > 3) {
                        setTimeout(() => {
                            divisionSelect.val(info.current_division_id).trigger('change');
                            $('#hidden_division_id').val(info.current_division_id);
                        }, 1000);
                    }

                    // 4. Department (Weight 4)
                    if (info.current_department_id && weight > 4) {
                        setTimeout(() => {
                            departmentSelect.val(info.current_department_id).trigger('change');
                            $('#hidden_department_id').val(info.current_department_id);
                        }, 1400);
                    }

                    // 5. Section (Weight 5)
                    if (info.current_section_id && weight > 5) {
                        setTimeout(() => {
                            sectionSelect.val(info.current_section_id);
                            $('#hidden_section_id').val(info.current_section_id);
                        }, 1800);
                    }
                }
            })
            .catch(err => console.error(err));
    }

    // Form Submission
    $('#transferForm').on('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const data = Object.fromEntries(formData.entries());

        axios.post('{{ route('transfer.api.store') }}', data)
            .then(res => {
                if (res.data.success) {
                    Swal.fire('Success!', res.data.message, 'success')
                        .then(() => window.location.href = '{{ route('transfer.index') }}');
                } else {
                    Swal.fire('Error!', res.data.message, 'error');
                }
            })
            .catch(err => {
                const message = err.response?.data?.message || 'Validation failed.';
                Swal.fire('Error!', message, 'error');
            });
    });
});
</script>
@endpush
