@extends('structure.master')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-lg border-0 rounded-4 my-4">
            <div class="card-body p-4 p-md-5">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3 d-inline-flex align-items-center justify-content-center">
                            <i class="bi bi-journal-text text-primary fs-4"></i>
                        </div>
                        <h2 class="fs-4 fw-bold text-dark mb-0">Career Movement Logs</h2>
                    </div>
                    @can('transfers.create')
                    <a href="{{ route('transfer.create') }}" class="btn btn-dark btn-lg rounded-3 shadow px-4">
                        <i class="bi bi-plus-circle me-2"></i>New Application
                    </a>
                    @endcan
                </div>

                <!-- Advanced Filters Section -->
                <div class="card border-0 bg-light bg-opacity-50 rounded-4 mb-4">
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <!-- Employee Search -->
                            <div class="col-md-4">
                                <label class="small fw-bold text-muted mb-1">Search Employee</label>
                                <input type="text" id="employee_search" class="form-control form-control-sm live-filter" 
                                       placeholder="Name, ID, or System ID...">
                            </div>

                            <!-- Organizational Filters -->
                            <div class="col-md-4">
                                <label class="small fw-bold text-muted mb-1">Company</label>
                                <select id="filter_company_id" class="form-select form-select-sm live-filter select2_list">
                                    <option value="">All Companies</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="small fw-bold text-muted mb-1">Branch/Unit</label>
                                <select id="filter_unit_id" class="form-select form-select-sm live-filter select2_list">
                                    <option value="">All Units</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="small fw-bold text-muted mb-1">Division</label>
                                <select id="filter_division_id" class="form-select form-select-sm live-filter select2_list">
                                    <option value="">All Divisions</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="small fw-bold text-muted mb-1">Department</label>
                                <select id="filter_department_id" class="form-select form-select-sm live-filter select2_list">
                                    <option value="">All Departments</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="small fw-bold text-muted mb-1">Section</label>
                                <select id="filter_section_id" class="form-select form-select-sm live-filter select2_list">
                                    <option value="">All Sections</option>
                                </select>
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button class="btn btn-outline-danger btn-sm w-100" id="btnClearFilters" style="height: 31px; border-radius: 0.25rem;">
                                    <i class="bi bi-trash3 me-1"></i> Clear All Filters
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle border-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0 rounded-start ps-4">#</th>
                                <th class="border-0">Employee</th>
                                <th class="border-0">Requested Unit</th>
                                <th class="border-0">Status</th>
                                <th class="border-0">Created At</th>
                                <th class="border-0 rounded-end text-end pe-4">Action</th>
                            </tr>
                        </thead>
                        <tbody id="transferTableBody" class="border-top-0">
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="spinner-border spinner-border-sm text-primary me-2"></div>
                                    Loading transfers...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination Placeholder -->
                <div id="pagination" class="mt-4 d-flex justify-content-end"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
$(document).ready(function() {
    const tableBody = $('#transferTableBody');
    const pagination = $('#pagination');
    
    // Filter Selects
    const filterEmployeeSearch = $('#employee_search');
    const filterCompany = $('#filter_company_id');
    const filterUnit = $('#filter_unit_id');
    const filterDivision = $('#filter_division_id');
    const filterDept = $('#filter_department_id');
    const filterSection = $('#filter_section_id');

    // Initial Load
    fetchFilterData();
    fetchCareerMovements();

    function loading($el, text = 'Loading...') {
        $el.prop('disabled', true).html(`<option value="">${text}</option>`);
    }

    function reset($el, text) {
        $el.prop('disabled', false).html(`<option value="">${text}</option>`);
    }

    function populateSelect($el, data, placeholder, labelKey = 'name') {
        $el.html(`<option value="">${placeholder}</option>`);
        data.forEach(item => {
            $el.append(`<option value="${item.id}">${item[labelKey]}</option>`);
        });
    }

    function fetchFilterData() {
        axios.get('{{ route('transfer.api.companies') }}').then(res => {
            populateSelect(filterCompany, res.data.data, 'All Companies');
        });
    }

    // -------------------------
    // Structured Cascading Filters (Matching Office Info style)
    // -------------------------
    function loadFilterDivisions() {
        const companyId = filterCompany.val();
        if (!companyId) return;

        const locationId = filterUnit.val() || 'null';

        loading(filterDivision);
        reset(filterDept, 'All Departments');
        reset(filterSection, 'All Sections');

        axios.get(`/get-divisions/${companyId}/${locationId}`)
            .then(res => {
                reset(filterDivision, 'All Divisions');
                populateSelect(filterDivision, res.data, 'All Divisions');
                // Chain: Load departments after divisions
                loadFilterDepartments();
            });
    }

    function loadFilterDepartments() {
        const companyId = filterCompany.val();
        if (!companyId) return;

        const locationId = filterUnit.val() || 'null';
        const divisionId = filterDivision.val() || 'null';

        loading(filterDept);
        reset(filterSection, 'All Sections');

        axios.get(`/get-departments/${companyId}/${locationId}/${divisionId}`)
            .then(res => {
                reset(filterDept, 'All Departments');
                const data = res.data;
                data.forEach(item => {
                    const name = item.name || item.department_name || 'N/A';
                    filterDept.append(`<option value="${item.id}">${name}</option>`);
                });
                // Chain: Load sections after departments
                loadFilterSections();
            });
    }

    function loadFilterSections() {
        const companyId = filterCompany.val();
        if (!companyId) return;

        const locationId = filterUnit.val() || 'null';
        const divisionId = filterDivision.val() || 'null';
        const departmentId = filterDept.val() || 'null';

        loading(filterSection);

        axios.get(`/get-sections/${companyId}/${locationId}/${divisionId}/${departmentId}`)
            .then(res => {
                reset(filterSection, 'All Sections');
                populateSelect(filterSection, res.data, 'All Sections');
                fetchCareerMovements(); // Final chain trigger search
            });
    }

    // Event Listeners for Filters
    filterCompany.on('change', function() {
        const companyId = $(this).val();
        resetFilters(['unit', 'division', 'dept', 'section']);
        if (companyId) {
            loading(filterUnit);
            axios.get(`/get-units/${companyId}`).then(res => {
                reset(filterUnit, 'All Units');
                populateSelect(filterUnit, res.data, 'All Units');
                loadFilterDivisions();
            });
        } else {
            fetchCareerMovements();
        }
    });

    filterUnit.on('change', loadFilterDivisions);
    filterDivision.on('change', loadFilterDepartments);
    filterDept.on('change', loadFilterSections);
    filterSection.on('change', fetchCareerMovements);

    filterEmployeeSearch.on('input', function() {
        if (this.value.length > 2 || this.value.length === 0) {
            fetchCareerMovements();
        }
    });

    function resetFilters(keys) {
        if (keys.includes('unit')) filterUnit.html('<option value="">All Units</option>');
        if (keys.includes('division')) filterDivision.html('<option value="">All Divisions</option>');
        if (keys.includes('dept')) filterDept.html('<option value="">All Departments</option>');
        if (keys.includes('section')) filterSection.html('<option value="">All Sections</option>');
    }

    $('#btnClearFilters').on('click', function() {
        filterEmployeeSearch.val('');
        filterCompany.val('').trigger('change');
    });

    // -------------------------
    // Main Fetch
    // -------------------------
    function fetchCareerMovements(page = 1) {
        const params = {
            page: page,
            employee_search: filterEmployeeSearch.val(),
            requested_company_id: filterCompany.val(),
            requested_business_unit_id: filterUnit.val(),
            requested_division_id: filterDivision.val(),
            requested_department_id: filterDept.val(),
            requested_section_id: filterSection.val()
        };

        tableBody.html('<tr><td colspan="6" class="text-center py-5"><div class="spinner-border spinner-border-sm text-primary me-2"></div>Searching...</td></tr>');

        axios.get('{{ route('transfer.api.list') }}', { params })
            .then(res => {
                renderTable(res.data.data.data);
                renderPagination(res.data.data);
            })
            .catch(err => {
                tableBody.html('<tr><td colspan="6" class="text-center text-danger py-5">Failed to load data.</td></tr>');
            });
    }

    function renderTable(data) {
        if (data.length === 0) {
            tableBody.html('<tr><td colspan="6" class="text-center py-5 text-muted">No transfer records found.</td></tr>');
            return;
        }

        tableBody.empty();
        data.forEach((item, index) => {
            const statusBadge = getStatusBadge(item.status);
            const employeeName = item.employee ? item.employee.full_name : 'Unknown Employee';
            const applicantId = item.employee ? item.employee.applicant_id : 'N/A';
            const companyName = item.requested_company ? item.requested_company.name : 'Unknown Company';
            const unitName = item.requested_business_unit ? item.requested_business_unit.name : 'N/A';

            const row = `
                <tr>
                    <td class="ps-4 text-muted">${index + 1}</td>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="bg-light rounded-circle p-2 me-3">
                                <i class="bi bi-person text-secondary"></i>
                            </div>
                            <div>
                                <div class="fw-bold text-dark">${employeeName}</div>
                                <small class="text-muted">${applicantId}</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="text-dark fw-medium">${companyName}</div>
                        <small class="text-muted">${unitName}</small>
                    </td>
                    <td>${statusBadge}</td>
                    <td class="text-muted">${new Date(item.created_at).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' })}</td>
                    <td class="text-end pe-4">
                        <a href="{{ url('transfer/view') }}/${item.id}" class="btn btn-sm btn-light border rounded-pill px-3">
                            <i class="bi bi-eye me-1"></i> View
                        </a>
                    </td>
                </tr>
            `;
            tableBody.append(row);
        });
        if (typeof feather !== 'undefined') feather.replace();
    }

    function getStatusBadge(status) {
        switch(status) {
            case 'pending': return '<span class="badge bg-warning text-dark">Pending</span>';
            case 'approved': return '<span class="badge bg-info">Approved</span>';
            case 'completed': return '<span class="badge bg-success">Completed</span>';
            case 'rejected': return '<span class="badge bg-danger">Rejected</span>';
            default: return `<span class="badge bg-secondary">${status}</span>`;
        }
    }

    function renderPagination(meta) {
        pagination.empty();
        if (meta.last_page <= 1) return;

        const nav = $('<nav></nav>');
        const ul = $('<ul class="pagination pagination-sm mb-0"></ul>');

        for (let i = 1; i <= meta.last_page; i++) {
            const li = $(`<li class="page-item ${meta.current_page === i ? 'active' : ''}"></li>`);
            li.append(`<button class="page-link" onclick="window.fetchCareerMovements(${i})">${i}</button>`);
            ul.append(li);
        }

        nav.append(ul);
        pagination.append(nav);
    }

    // Expose to global for onclick
    window.fetchCareerMovements = fetchCareerMovements;
});
</script>
@endpush
