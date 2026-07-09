@extends('structure.master')

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Main Card -->
        <div class="card shadow-lg border-0 rounded-4 my-4">
        <!-- Form Body -->
        <div class="card-body p-4 p-md-5">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3 d-inline-flex align-items-center justify-content-center">
                            <i class="bi bi-file-earmark-plus text-primary fs-4"></i>
                        </div>
                        <h2 class="fs-4 fw-bold text-dark mb-0">New Career Movement Application</h2>
                    </div>
                    <a href="{{ route('transfer.index') }}" class="btn btn-light rounded-pill px-4 shadow-sm">
                        <i class="bi bi-arrow-left me-2"></i>Back to Logs
                    </a>
                </div>

                <form id="transferForm" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-4">
                        <!-- Employee Information Section -->
                        <div class="col-lg-5 border-end pe-lg-5">
                            <div class="mb-4">
                                <h5 class="fw-bold text-primary mb-3 border-bottom pb-2">Employee Selection</h5>
                                <div class="mb-3">
                                    <label for="employee_id" class="form-label fw-semibold">Select Employee <span class="text-danger">*</span></label>
                                    @if($isEmployee)
                                        <div class="p-3 bg-light rounded border border-primary-subtle d-flex align-items-center">
                                            <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                                <i class="bi bi-person-check text-primary"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark">{{ auth()->user()->employee->full_name }}</div>
                                                <div class="text-muted small">{{ auth()->user()->employee->applicant_id }}</div>
                                            </div>
                                            <input type="hidden" name="employee_id" id="employee_id" value="{{ auth()->user()->employee_id }}">
                                        </div>
                                    @else
                                        <select name="employee_id" id="employee_id" class="form-select select2_list" required>
                                            <option value="">Choose Employee</option>
                                        </select>
                                    @endif
                                </div>
                                <div id="employeeInfo" class="mt-4 p-3 bg-light rounded border-start border-4 border-primary d-none">
                                    <label class="small text-muted fw-bold text-uppercase mb-2 d-block">Current Placement</label>
                                    <div id="currentPlacementDetails" class="small">
                                        <!-- Populated via AJAX -->
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 p-4 bg-primary bg-opacity-10 rounded-4 border border-primary-subtle">
                                <h6 class="fw-bold text-primary mb-2">Note:</h6>
                                <p class="small text-muted mb-0">Your request will undergo a multi-stage approval process by the relevant authorities. Ensure all requested data is accurate.</p>
                            </div>
                        </div>

                        <!-- Movement Details Section -->
                        <div class="col-lg-7 ps-lg-5">
                            <h5 class="fw-bold text-primary mb-3 border-bottom pb-2">Requested Information</h5>
                            
                            <div class="row g-3">
                                <!-- Company -->
                                <div class="col-md-6">
                                    <label for="requested_company_id" class="form-label fw-semibold">Company <span class="text-danger">*</span></label>
                                    <select name="requested_company_id" id="requested_company_id" class="form-select select2_list" required @if($levelWeight > 1) disabled @endif>
                                        <option value="">Select Company</option>
                                    </select>
                                    @if($levelWeight > 1) <input type="hidden" name="requested_company_id" id="hidden_company_id"> @endif
                                </div>

                                <!-- Movement Type -->
                                <div class="col-md-6">
                                    <label for="movement_type_id" class="form-label fw-semibold">Movement Type</label>
                                    <select name="movement_type_id" id="movement_type_id" class="form-select select2_list">
                                        <option value="">Select Movement Type</option>
                                        @foreach($movementTypes as $type)
                                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                                        @endforeach
                                    </select>
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

                            <div class="mt-4">
                                <label for="remarks" class="form-label fw-semibold">Reason / Remarks</label>
                                <textarea name="remarks" id="remarks" rows="4" class="form-control bg-light" placeholder="Explain the reason for this career movement..."></textarea>
                            </div>

                            <div class="mt-4">
                                <label for="attachments" class="form-label fw-semibold">Attachments</label>
                                <input type="file" name="attachments[]" id="attachments" class="form-control" multiple>
                                <small class="text-muted">You can select multiple files (max 10MB per file).</small>
                            </div>

                            <div class="mt-5 pt-3 d-flex justify-content-end gap-3">
                                <button type="reset" class="btn btn-light btn-lg rounded-pill px-5">Reset</button>
                                <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5 shadow">Submit Application</button>
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

    function populateSelect($el, data, placeholder, labelKey = 'name') {
        reset($el, placeholder);
        data.forEach(item => {
            const label = item[labelKey] || item['name'] || 'N/A';
            $el.append(`<option value="${item.id}">${label}</option>`);
        });
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
                        $(`#${prefix}_department_id`).append(`<option value="${item.id}">${item.name}</option>`);
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
                    
                    // Show Current Placement in Sidebar
                    $('#employeeInfo').removeClass('d-none');
                    $('#currentPlacementDetails').html(`
                        <div class="mb-1"><strong>Company:</strong> ${info.get_current_company?.name || 'N/A'}</div>
                        <div class="mb-1"><strong>Unit:</strong> ${info.get_current_business_unit?.name || 'N/A'}</div>
                        <div class="mb-1"><strong>Division:</strong> ${info.get_current_division?.name || 'N/A'}</div>
                        <div class="mb-1"><strong>Dept:</strong> ${info.get_current_department?.department_name || 'N/A'}</div>
                        <div class="mb-0"><strong>Section:</strong> ${info.get_current_section?.name || 'N/A'}</div>
                    `);

                    // 1. Company (Weight 1)
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

    $('#transferForm').on('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        axios.post('{{ route('transfer.api.store') }}', formData)
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
