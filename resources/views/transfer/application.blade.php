@extends('structure.master')

@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card glass-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0 text-white">Employee Transfer Application</h5>
                <a href="{{ route('transfer.index') }}" class="btn btn-sm btn-light">
                    <i data-feather="list" class="me-1"></i> Logs
                </a>
            </div>
            <div class="card-body">
                <form id="transferForm">
                    <div class="row g-3">
                        <!-- Employee Selection -->
                        <div class="col-md-6">
                            <label class="form-label">Target Employee <span class="text-danger">*</span></label>
                            <select name="employee_id" id="employee_id" class="form-select select2" required>
                                <option value="">Select Employee</option>
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Remarks</label>
                            <textarea name="remarks" class="form-control" rows="1"></textarea>
                        </div>

                        <hr class="my-4 text-white-50">
                        <h6 class="text-white mb-3"><i data-feather="map-pin" class="me-2"></i>Requested Office Information</h6>

                        <!-- Cascading Dropdowns -->
                        <div class="col-md-4">
                            <label class="form-label">Company <span class="text-danger">*</span></label>
                            <select name="requested_company_id" id="requested_company_id" class="form-select" required>
                                <option value="">Select Company</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Business Unit</label>
                            <select name="requested_business_unit_id" id="requested_business_unit_id" class="form-select">
                                <option value="">Select Business Unit</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Division</label>
                            <select name="requested_division_id" id="requested_division_id" class="form-select">
                                <option value="">Select Division</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Department</label>
                            <select name="requested_department_id" id="requested_department_id" class="form-select">
                                <option value="">Select Department</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Section</label>
                            <select name="requested_section_id" id="requested_section_id" class="form-select">
                                <option value="">Select Section</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Designation <span class="text-danger">*</span></label>
                            <select name="requested_designation_id" id="requested_designation_id" class="form-select" required>
                                <option value="">Select Designation</option>
                            </select>
                        </div>

                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-primary px-4">
                                <i data-feather="send" class="me-1"></i> Submit Application
                            </button>
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
    fetchEmployees();
    fetchCompanies();
    fetchDesignations();

    // Event Listeners for Cascading
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

    function fetchDesignations() {
        axios.get('{{ route('transfer.api.designations') }}')
            .then(res => populateSelect(designationSelect, res.data.data, 'Select Designation'))
            .catch(err => console.error(err));
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
