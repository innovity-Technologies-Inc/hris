@extends('structure.master')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card border-0 shadow-sm rounded">
            <div class="card-header">
                <h5 class="card-title mb-0">Search Career Movements</h5>
            </div>
            <div class="card-header border-bottom p-4">
                <div class="row align-items-start">
                    <div class="col-md-12">
                        <div class="border rounded shadow-sm p-3 filter-section-bg">
                            <div class="row g-3">
                                <!-- Employee Search -->
                                <div class="col-md-4">
                                    <label class="form-label text-muted small fw-semibold mb-1">Search Employee</label>
                                    <input type="text" id="employee_search" class="form-control form-control-sm live-filter" 
                                           placeholder="Name, ID, or System ID...">
                                </div>

                                <!-- Organizational Filters -->
                                <div class="col-md-4">
                                    <label class="form-label text-muted small fw-semibold mb-1">Company</label>
                                    <select id="filter_company_id" class="form-select form-select-sm live-filter select2_list">
                                        <option value="">All Companies</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label text-muted small fw-semibold mb-1">Branch/Unit</label>
                                    <select id="filter_unit_id" class="form-select form-select-sm live-filter select2_list">
                                        <option value="">All Units</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label text-muted small fw-semibold mb-1">Division</label>
                                    <select id="filter_division_id" class="form-select form-select-sm live-filter select2_list">
                                        <option value="">All Divisions</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label text-muted small fw-semibold mb-1">Department</label>
                                    <select id="filter_department_id" class="form-select form-select-sm live-filter select2_list">
                                        <option value="">All Departments</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label text-muted small fw-semibold mb-1">Section</label>
                                    <select id="filter_section_id" class="form-select form-select-sm live-filter select2_list">
                                        <option value="">All Sections</option>
                                    </select>
                                </div>
                                <div class="col-md-3 d-flex align-items-end">
                                    <button class="btn btn-outline-danger btn-sm w-100" id="btnClearFilters" style="height: 31px; display: flex; align-items: center; justify-content: center;">
                                        <i class="bi bi-trash3 me-1"></i> Clear Filters
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-12 mt-3">
        <div class="card border-0 shadow-sm rounded">
            <div class="card-header">
                <h5 class="card-title mb-0">Career Movement Logs</h5>
            </div>
            <div class="card-body">
                {{-- Action Buttons --}}
                <div class="d-flex justify-content-between mb-3">
                    <div class="d-flex gap-2">
                        @can('transfers.create')
                        <a href="{{ route('transfer.create') }}" class="btn btn-warning btn-sm">
                            <i style="height: 12px; width: 12px" data-feather="plus"></i> Create
                        </a>
                        @endcan
                        @can('transfers.edit')
                        <button type="button" id="btnAdjustment" class="btn btn-success btn-sm">
                            <i style="height: 12px; width: 12px" data-feather="check"></i> Adjustment
                        </button>
                        @endcan
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Employee</th>
                                <th scope="col">Requested Placement</th>
                                <th scope="col">Effective From</th>
                                <th scope="col">Status</th>
                                <th scope="col">Created At</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody id="transferTableBody">
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
            tableBody.html('<tr><td colspan="7" class="text-center py-5 text-muted">No transfer records found.</td></tr>');
            return;
        }

        tableBody.empty();
        data.forEach((item, index) => {
            const statusBadge = getStatusBadge(item.status);
            const employeeName = item.employee ? item.employee.full_name : 'Unknown Employee';
            const applicantId = item.employee ? item.employee.applicant_id : 'N/A';
            const companyName = item.requested_company ? item.requested_company.name : 'Unknown Company';
            const unitName = item.requested_business_unit ? item.requested_business_unit.name : 'N/A';
            const movementTypeBadge = item.movement_type ? `<span class="badge bg-secondary ms-1">${item.movement_type.name}</span>` : '';
            const effectiveFromDate = item.effective_from ? new Date(item.effective_from).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' }) : 'N/A';

            const deleteButton = (item.can_delete && item.status === 'pending') ? `
                <button type="button" class="btn btn-danger btn-sm btnDeleteTransfer" data-id="${item.id}" title="Delete">
                    <i style="height: 12px; width: 12px" data-feather="trash"></i>
                </button>
            ` : '';

            const row = `
                <tr>
                    <td>${index + 1}</td>
                    <td>
                        <div class="fw-semibold text-dark">${employeeName}</div>
                        <small class="text-muted">${applicantId}</small>
                    </td>
                    <td>
                        <div class="text-dark fw-medium">${companyName} ${movementTypeBadge}</div>
                        <small class="text-muted">${unitName}</small>
                    </td>
                    <td><span class="small">${effectiveFromDate}</span></td>
                    <td>${statusBadge}</td>
                    <td>${new Date(item.created_at).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' })}</td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ url('transfer/view') }}/${item.id}" class="btn btn-info btn-sm" title="View Details">
                                <i style="height: 12px; width: 12px" data-feather="eye"></i>
                            </a>
                            ${deleteButton}
                        </div>
                    </td>
                </tr>
            `;
            tableBody.append(row);
        });
        if (typeof feather !== 'undefined') feather.replace();
    }

    $(document).on('click', '.btnDeleteTransfer', function(event) {
        event.preventDefault();
        const transferId = $(this).data('id');
        Swal.fire({
            title: 'Are you sure you want to delete?',
            text: 'You won\'t be able to revert this!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Delete'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Deleting...',
                    didOpen: () => { Swal.showLoading(); }
                });
                axios.delete(`{{ url('transfer/api/delete') }}/${transferId}`)
                    .then(res => {
                        Swal.fire('Deleted!', res.data.message, 'success').then(() => {
                            fetchCareerMovements();
                        });
                    })
                    .catch(err => {
                        const msg = err.response?.data?.message || 'Failed to delete transfer.';
                        Swal.fire('Error', msg, 'error');
                    });
            }
        });
    });

    $('#btnAdjustment').on('click', function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Process Career Movement Adjustments?',
            text: "This will bulk adjust all due approved transfers to their requested placements.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Process'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Processing...',
                    didOpen: () => { Swal.showLoading(); }
                });
                axios.post('{{ route('transfer.api.adjustment') }}')
                    .then(res => {
                        Swal.fire('Success!', res.data.message, 'success').then(() => {
                            fetchCareerMovements();
                        });
                    })
                    .catch(err => {
                        const msg = err.response?.data?.message || 'Something went wrong.';
                        Swal.fire('Error', msg, 'error');
                    });
            }
        });
    });

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
