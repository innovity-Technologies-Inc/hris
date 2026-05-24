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
                                    <select name="requested_company_id" id="requested_company_id" class="form-select" required @if($levelWeight > 1) disabled @endif>
                                        <option value="">Select Company</option>
                                    </select>
                                    @if($levelWeight > 1) <input type="hidden" name="requested_company_id" id="hidden_company_id"> @endif
                                </div>

                                <!-- Business Unit -->
                                <div class="col-md-6">
                                    <label for="requested_business_unit_id" class="form-label fw-semibold">Business Unit / Branch</label>
                                    <select name="requested_business_unit_id" id="requested_business_unit_id" class="form-select" @if($levelWeight > 2) disabled @endif>
                                        <option value="">Select Business Unit</option>
                                    </select>
                                    @if($levelWeight > 2) <input type="hidden" name="requested_business_unit_id" id="hidden_unit_id"> @endif
                                </div>

                                <!-- Division -->
                                <div class="col-md-6">
                                    <label for="requested_division_id" class="form-label fw-semibold">Division</label>
                                    <select name="requested_division_id" id="requested_division_id" class="form-select" @if($levelWeight > 3) disabled @endif>
                                        <option value="">Select Division</option>
                                    </select>
                                    @if($levelWeight > 3) <input type="hidden" name="requested_division_id" id="hidden_division_id"> @endif
                                </div>

                                <!-- Department -->
                                <div class="col-md-6">
                                    <label for="requested_department_id" class="form-label fw-semibold">Department</label>
                                    <select name="requested_department_id" id="requested_department_id" class="form-select" @if($levelWeight > 4) disabled @endif>
                                        <option value="">Select Department</option>
                                    </select>
                                    @if($levelWeight > 4) <input type="hidden" name="requested_department_id" id="hidden_department_id"> @endif
                                </div>

                                <!-- Section -->
                                <div class="col-md-12">
                                    <label for="requested_section_id" class="form-label fw-semibold">Section</label>
                                    <select name="requested_section_id" id="requested_section_id" class="form-select" @if($levelWeight > 5) disabled @endif>
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
document.addEventListener('DOMContentLoaded', function() {
    // Select Elements
    const employeeSelect = document.getElementById('employee_id');
    const companySelect = document.getElementById('requested_company_id');
    const unitSelect = document.getElementById('requested_business_unit_id');
    const divisionSelect = document.getElementById('requested_division_id');
    const departmentSelect = document.getElementById('requested_department_id');
    const sectionSelect = document.getElementById('requested_section_id');
    const designationSelect = document.getElementById('requested_designation_id');
    const transferForm = document.getElementById('transferForm');

    // Initial Fetch
    @if(!$isEmployee)
    fetchEmployees();
    @endif
    fetchCompanies();
    fetchDesignations();

    // Event Listeners for Cascading
    @if(!$isEmployee)
    employeeSelect.addEventListener('change', function() {
        if (this.value) {
            fetchOfficeInfo(this.value);
        }
    });
    @else
    fetchOfficeInfo('{{ $loggedInEmployeeId }}');
    @endif

    companySelect.addEventListener('change', function() {
        const companyId = this.value;
        resetDropdowns([unitSelect, divisionSelect, departmentSelect, sectionSelect]);
        if (companyId) {
            fetchUnits(companyId);
            fetchDivisions(companyId, 'null');
            fetchDepartments(companyId, 'null', 'null');
            fetchSections(companyId, 'null', 'null', 'null');
        }
    });

    unitSelect.addEventListener('change', function() {
        const companyId = companySelect.value;
        const unitId = this.value || 'null';
        resetDropdowns([divisionSelect, departmentSelect, sectionSelect]);
        fetchDivisions(companyId, unitId);
    });

    divisionSelect.addEventListener('change', function() {
        const companyId = companySelect.value;
        const unitId = unitSelect.value || 'null';
        const divisionId = this.value || 'null';
        resetDropdowns([departmentSelect, sectionSelect]);
        fetchDepartments(companyId, unitId, divisionId);
    });

    departmentSelect.addEventListener('change', function() {
        const companyId = companySelect.value;
        const unitId = unitSelect.value || 'null';
        const divisionId = divisionSelect.value || 'null';
        const departmentId = this.value || 'null';
        resetDropdowns([sectionSelect]);
        fetchSections(companyId, unitId, divisionId, departmentId);
    });

    // Fetch Functions
    function fetchEmployees() {
        axios.get('{{ route('transfer.api.employees') }}')
            .then(res => populateSelect(employeeSelect, res.data.data, 'Select Employee', 'id', (item) => `${item.full_name} (${item.applicant_id})`))
            .catch(err => console.error(err));
    }

    function fetchCompanies() {
        axios.get('{{ route('transfer.api.companies') }}')
            .then(res => populateSelect(companySelect, res.data.data, 'Select Company'))
            .catch(err => console.error(err));
    }

    function fetchUnits(companyId) {
        axios.get(`{{ url('transfer/api/units') }}/${companyId}`)
            .then(res => populateSelect(unitSelect, res.data.data, 'Select Business Unit'))
            .catch(err => console.error(err));
    }

    function fetchDivisions(companyId, unitId) {
        axios.get(`{{ url('transfer/api/divisions') }}/${companyId}/${unitId}`)
            .then(res => populateSelect(divisionSelect, res.data.data, 'Select Division'))
            .catch(err => console.error(err));
    }

    function fetchDepartments(companyId, unitId, divisionId) {
        axios.get(`{{ url('transfer/api/departments') }}/${companyId}/${unitId}/${divisionId}`)
            .then(res => populateSelect(departmentSelect, res.data.data, 'Select Department'))
            .catch(err => console.error(err));
    }

    function fetchSections(companyId, unitId, divisionId, departmentId) {
        axios.get(`{{ url('transfer/api/sections') }}/${companyId}/${unitId}/${divisionId}/${departmentId}`)
            .then(res => populateSelect(sectionSelect, res.data.data, 'Select Section'))
            .catch(err => console.error(err));
    }

    function fetchOfficeInfo(employeeId) {
        axios.get(`{{ url('get-office-info') }}/${employeeId}`)
            .then(res => {
                const info = res.data;
                if (info) {
                    const weight = {{ $levelWeight }};
                    
                    if (weight > 1) {
                        setField(companySelect, 'hidden_company_id', info.current_company_id, info.get_current_company?.name);
                        fetchUnits(info.current_company_id);
                    }
                    if (weight > 2) {
                        setTimeout(() => {
                            setField(unitSelect, 'hidden_unit_id', info.current_business_unit_id, info.get_current_business_unit?.name);
                            fetchDivisions(info.current_company_id, info.current_business_unit_id || 'null');
                        }, 500);
                    }
                    if (weight > 3) {
                        setTimeout(() => {
                            setField(divisionSelect, 'hidden_division_id', info.current_division_id, info.get_current_division?.name);
                            fetchDepartments(info.current_company_id, info.current_business_unit_id || 'null', info.current_division_id || 'null');
                        }, 1000);
                    }
                    if (weight > 4) {
                        setTimeout(() => {
                            setField(departmentSelect, 'hidden_department_id', info.current_department_id, info.get_current_department?.department_name);
                            fetchSections(info.current_company_id, info.current_business_unit_id || 'null', info.current_division_id || 'null', info.current_department_id || 'null');
                        }, 1500);
                    }
                    if (weight > 5) {
                        setTimeout(() => {
                            setField(sectionSelect, 'hidden_section_id', info.current_section_id, info.get_current_section?.name);
                        }, 2000);
                    }
                }
            })
            .catch(err => console.error(err));
    }

    function setField(select, hiddenId, value, label) {
        if (value) {
            select.innerHTML = `<option value="${value}" selected>${label}</option>`;
            const hidden = document.getElementById(hiddenId);
            if (hidden) hidden.value = value;
        }
    }

    // Helper Functions
    function populateSelect(select, data, placeholder, valueKey = 'id', labelKey = 'name') {
        select.innerHTML = `<option value="">${placeholder}</option>`;
        data.forEach(item => {
            const label = typeof labelKey === 'function' ? labelKey(item) : item[labelKey];
            const option = new Option(label, item[valueKey]);
            select.add(option);
        });
    }

    function resetDropdowns(selects) {
        selects.forEach(s => s.innerHTML = '<option value="">Select...</option>');
    }

    // Form Submission
    transferForm.addEventListener('submit', function(e) {
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
