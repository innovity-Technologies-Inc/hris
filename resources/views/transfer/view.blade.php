@extends('structure.master')

@section('content')
<div class="row g-4">
    <!-- Comparison Section -->
    <div class="col-md-12">
        <div class="card glass-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Career Movement Request Details: {{ $transfer->employee->full_name }}</h5>
                <div>
                    @if($transfer->status === 'approved' && auth()->user()->can('transfers.edit'))
                    <button class="btn btn-sm btn-success" id="btnComplete">
                        <i data-feather="check-circle" class="me-1"></i> Mark as Complete
                    </button>
                    @endif
                    <a href="{{ route('transfer.index') }}" class="btn btn-sm btn-light">Back to Logs</a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Current Office Info -->
                    <div class="col-md-6 border-end">
                        <h6 class="text-muted border-bottom pb-2 mb-3">Current Office Info</h6>
                        <div class="info-item mb-2">
                            <label class="text-muted small d-block">Company</label>
                            <span class="fw-medium">{{ $transfer->currentCompany->name ?? 'N/A' }}</span>
                        </div>
                        <div class="info-item mb-2">
                            <label class="text-muted small d-block">Business Unit</label>
                            <span class="fw-medium">{{ $transfer->currentBusinessUnit->name ?? 'N/A' }}</span>
                        </div>
                        <div class="info-item mb-2">
                            <label class="text-muted small d-block">Division / Department</label>
                            <span class="fw-medium">{{ $transfer->currentDivision->name ?? 'N/A' }} / {{ $transfer->currentDepartment->department_name ?? 'N/A' }}</span>
                        </div>
                        <div class="info-item mb-2">
                            <label class="text-muted small d-block">Section</label>
                            <span class="fw-medium">{{ $transfer->currentSection->name ?? 'N/A' }}</span>
                        </div>
                    </div>

                    <!-- Requested Office Info -->
                    <div class="col-md-6 ps-md-4">
                        <h6 class="text-primary border-bottom border-primary-20 pb-2 mb-3">Requested Office Info</h6>
                        <div class="info-item mb-2">
                            <label class="text-muted small d-block">Company</label>
                            <span class="fw-medium">{{ $transfer->requestedCompany->name ?? 'N/A' }}</span>
                        </div>
                        <div class="info-item mb-2">
                            <label class="text-muted small d-block">Business Unit</label>
                            <span class="fw-medium">{{ $transfer->requestedBusinessUnit->name ?? 'N/A' }}</span>
                        </div>
                        <div class="info-item mb-2">
                            <label class="text-muted small d-block">Division / Department</label>
                            <span class="fw-medium">{{ $transfer->requestedDivision->name ?? 'N/A' }} / {{ $transfer->requestedDepartment->department_name ?? 'N/A' }}</span>
                        </div>
                        <div class="info-item mb-2">
                            <label class="text-muted small d-block">Section</label>
                            <span class="fw-medium">{{ $transfer->requestedSection->name ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>

                @if($transfer->remarks)
                <div class="mt-4 p-3 bg-light rounded">
                    <label class="text-muted small d-block">Employee Remarks</label>
                    <p class="mb-0">{{ $transfer->remarks }}</p>
                </div>
                @endif

                <div class="mt-4 row g-3">
                    <div class="col-md-6">
                        <div class="p-2 border rounded bg-light">
                            <label class="text-muted small d-block mb-1">Applied By</label>
                            <span class="">
                                <i data-feather="user" class="me-1" style="width: 14px;"></i>
                                {{ $transfer->creator->name }} on {{ $transfer->created_at->format('d M Y, h:i A') }}
                            </span>
                        </div>
                    </div>
                    @if($transfer->status === 'completed' && $transfer->completer)
                    <div class="col-md-6">
                        <div class="p-2 border border-success-subtle rounded bg-success-light">
                            <label class="text-success small d-block mb-1">Finalized By</label>
                            <span class="">
                                <i data-feather="check-circle" class="me-1" style="width: 14px;"></i>
                                {{ $transfer->completer->name }} on {{ \Carbon\Carbon::parse($transfer->completed_at)->format('d M Y, h:i A') }}
                            </span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if(auth()->user()->user_type !== 'Employee')
    <!-- Approval Workflow Section -->
    <div class="col-md-12">
        <div class="card glass-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Approval Workflow</h5>
                @if($transfer->status === 'pending' && $transfer->approval_count_required === 0 && auth()->user()->can('transfers.approve'))
                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#setupApproversModal">
                    <i data-feather="users" class="me-1"></i> Setup Approvers
                </button>
                @endif
            </div>
            <div class="card-body">
                @if($transfer->approval_count_required > 0)
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Approver</th>
                                <th>Status</th>
                                <th>Remarks</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transfer->approvals as $approval)
                            <tr>
                                <td>{{ $approval->approver->name }}</td>
                                <td>
                                    @if($approval->status === 'approved')
                                        <span class="text-success"><i data-feather="check"></i> Approved</span>
                                    @elseif($approval->status === 'rejected')
                                        <span class="text-danger"><i data-feather="x"></i> Rejected</span>
                                    @else
                                        <span class="text-warning">Pending</span>
                                    @endif
                                </td>
                                <td>{{ $approval->remarks ?? '-' }}</td>
                                <td>{{ $approval->approved_at ? \Carbon\Carbon::parse($approval->approved_at)->format('d M Y') : '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- My Approval Action -->
                @php
                    $myApproval = $transfer->approvals->where('approver_id', auth()->id())->where('status', 'pending')->first();
                @endphp
                @if($myApproval)
                <div class="mt-4 p-3 border border-primary-subtle rounded bg-primary-light">
                    <h6 class="mb-3">Your Approval Required</h6>
                    <button class="btn btn-success" onclick="approveCareerMovement()">Approve Request</button>
                </div>
                @endif
                @else
                <div class="text-center py-4 text-muted">
                    <p>No approval workflow configured yet.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Setup Approvers Modal -->
<div class="modal fade" id="setupApproversModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content glass-card">
            <div class="modal-header">
                <h5 class="modal-title">Setup Career Movement Approvers</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <!-- User Type Filter -->
                    <div class="col-md-4">
                        <label class="small mb-1">User Type</label>
                        <select id="filter_user_type" class="form-select form-select-sm live-filter">
                            <option value="">All Types</option>
                            @foreach(['Group', 'Company', 'Business Unit', 'Division', 'Department', 'Section', 'Employee'] as $type)
                                <option value="{{ $type }}">{{ $type }}</option>
                            @endforeach
                        </select>
                    </div>
                    <!-- Name Search -->
                    <div class="col-md-8">
                        <label class="small mb-1">Search by Name</label>
                        <div class="input-group input-group-sm">
                            <input type="text" id="authoritySearch" class="form-control live-filter" placeholder="Type name...">
                            <button class="btn btn-primary" id="btnSearchAuthorities">Search</button>
                        </div>
                    </div>

                    <!-- Organizational Filters -->
                    <div class="col-md-4">
                        <label class="small mb-1">Company</label>
                        <select id="filter_company_id" class="form-select form-select-sm live-filter">
                            <option value="">All Companies</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="small mb-1">Branch/Unit</label>
                        <select id="filter_unit_id" class="form-select form-select-sm live-filter">
                            <option value="">All Units</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="small mb-1">Division</label>
                        <select id="filter_division_id" class="form-select form-select-sm live-filter">
                            <option value="">All Divisions</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="small mb-1">Department</label>
                        <select id="filter_department_id" class="form-select form-select-sm live-filter">
                            <option value="">All Departments</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="small mb-1">Section</label>
                        <select id="filter_section_id" class="form-select form-select-sm live-filter">
                            <option value="">All Sections</option>
                        </select>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button class="btn btn-outline-secondary btn-sm w-100" id="btnClearFilters">
                            <i data-feather="refresh-ccw" style="width: 12px;"></i> Clear Filters
                        </button>
                    </div>
                    
                    <div class="col-md-12">
                        <div id="authoritiesList" class="list-group mt-2 border rounded" style="max-height: 250px; overflow-y: auto;">
                            <div class="text-center py-4 text-muted small">Use filters to find authorities...</div>
                        </div>
                    </div>

                    <div class="col-md-12 mt-4">
                        <h6 class="small fw-bold">Selected Approvers</h6>
                        <div id="selectedApprovers" class="d-flex flex-wrap gap-2 py-2 border rounded bg-light min-h-50">
                            <!-- Chips -->
                            <div class="text-muted small p-2 w-100 text-center" id="noApproversMsg">No approvers selected.</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="btnSaveApprovers">Assign & Notify</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
$(document).ready(function() {
    const transferId = '{{ $transfer->id }}';
    
    // Filter Selects
    const filterUserType = $('#filter_user_type');
    const filterCompany = $('#filter_company_id');
    const filterUnit = $('#filter_unit_id');
    const filterDivision = $('#filter_division_id');
    const filterDept = $('#filter_department_id');
    const filterSection = $('#filter_section_id');
    const inputSearch = $('#authoritySearch');

    const listContainer = $('#authoritiesList');
    const selectedContainer = $('#selectedApprovers');
    const noApproversMsg = $('#noApproversMsg');

    let selectedApprovers = []; // Array of objects {id, name}

    // -------------------------
    // Initial Fetch for Filters
    // -------------------------
    fetchFilterData();

    function fetchFilterData() {
        axios.get('{{ route('transfer.api.companies') }}').then(res => {
            populateSelect(filterCompany, res.data.data, 'All Companies');
        });
    }

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

    // -------------------------
    // Cascading for Filters
    // -------------------------
    function loadFilterDivisions(prefix) {
        const companyId = $(`#filter_company_id`).val();
        if (!companyId) return;

        const locationId = $(`#filter_unit_id`).val() || 'null';

        loading($(`#filter_division_id`));
        reset($(`#filter_department_id`), 'All Departments');
        reset($(`#filter_section_id`), 'All Sections');

        axios.get(`/get-divisions/${companyId}/${locationId}`)
            .then(res => {
                reset($(`#filter_division_id`), 'All Divisions');
                populateSelect($(`#filter_division_id`), res.data, 'All Divisions');
                searchAuthorities(); // Instant Load
            });
    }

    function loadFilterDepartments(prefix) {
        const companyId = $(`#filter_company_id`).val();
        if (!companyId) return;

        const locationId = $(`#filter_unit_id`).val() || 'null';
        const divisionId = $(`#filter_division_id`).val() || 'null';

        loading($(`#filter_department_id`));
        reset($(`#filter_section_id`), 'All Sections');

        axios.get(`/get-departments/${companyId}/${locationId}/${divisionId}`)
            .then(res => {
                reset($(`#filter_department_id`), 'All Departments');
                const data = res.data;
                data.forEach(item => {
                    const name = item.name || item.department_name || 'N/A';
                    $(`#filter_department_id`).append(`<option value="${item.id}">${name}</option>`);
                });
                searchAuthorities(); // Instant Load
            });
    }

    function loadFilterSections(prefix) {
        const companyId = $(`#filter_company_id`).val();
        if (!companyId) return;

        const locationId = $(`#filter_unit_id`).val() || 'null';
        const divisionId = $(`#filter_division_id`).val() || 'null';
        const departmentId = $(`#filter_department_id`).val() || 'null';

        loading($(`#filter_section_id`));

        axios.get(`/get-sections/${companyId}/${locationId}/${divisionId}/${departmentId}`)
            .then(res => {
                reset($(`#filter_section_id`), 'All Sections');
                populateSelect($(`#filter_section_id`), res.data, 'All Sections');
                searchAuthorities(); // Instant Load
            });
    }

    filterCompany.on('change', function() {
        const companyId = $(this).val();
        resetFilters(['unit', 'division', 'dept', 'section']);
        if (companyId) {
            loading(filterUnit);
            axios.get(`/get-units/${companyId}`).then(res => {
                reset(filterUnit, 'All Units');
                populateSelect(filterUnit, res.data, 'All Units');
                searchAuthorities(); // Instant Load
            });
        } else {
            searchAuthorities();
        }
    });

    filterUnit.on('change', function() {
        loadFilterDivisions();
    });

    filterDivision.on('change', function() {
        loadFilterDepartments();
    });

    filterDept.on('change', function() {
        loadFilterSections();
    });

    filterSection.on('change', function() {
        searchAuthorities();
    });

    filterUserType.on('change', function() {
        searchAuthorities();
    });

    inputSearch.on('input', function() {
        // debounce search? For now simple input event
        searchAuthorities();
    });

    function resetFilters(keys) {
        if (keys.includes('unit')) filterUnit.html('<option value="">All Units</option>');
        if (keys.includes('division')) filterDivision.html('<option value="">All Divisions</option>');
        if (keys.includes('dept')) filterDept.html('<option value="">All Departments</option>');
        if (keys.includes('section')) filterSection.html('<option value="">All Sections</option>');
    }

    $('#btnClearFilters').on('click', function() {
        $('#filter_user_type').val('');
        inputSearch.val('');
        filterCompany.val('').trigger('change');
    });

    // -------------------------
    // Search Authorities
    // -------------------------
    function searchAuthorities() {
        const params = {
            name: inputSearch.val(),
            user_type: filterUserType.val(),
            company_id: filterCompany.val(),
            unit_id: filterUnit.val(),
            division_id: filterDivision.val(),
            department_id: filterDept.val(),
            section_id: filterSection.val()
        };

        listContainer.html('<div class="text-center py-4 small">Searching...</div>');

        axios.get('{{ route('transfer.api.search_authorities') }}', { params })
            .then(res => renderAuthorities(res.data.data))
            .catch(err => {
                listContainer.html('<div class="text-center py-4 text-danger small">Error fetching results.</div>');
            });
    }

    $('#btnSearchAuthorities').on('click', searchAuthorities);

    function renderAuthorities(users) {
        listContainer.empty();
        if (users.length === 0) {
            listContainer.html('<div class="text-center py-4 text-muted small">No authorities found.</div>');
            return;
        }

        users.forEach(user => {
            const office = user.employee && user.employee.office_info 
                ? `${user.employee.office_info.get_current_company.name} / ${user.employee.office_info.get_current_business_unit?.name || 'N/A'}`
                : user.user_type;

            const item = `
                <button class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" 
                    onclick="window.selectApprover(${user.id}, '${user.name}')">
                    <div class="d-flex flex-column">
                        <span class="fw-bold text-dark">${user.name}</span>
                        <small class="text-muted" style="font-size: 11px;">${office}</small>
                    </div>
                    <i data-feather="plus-circle" class="text-primary" style="width: 16px;"></i>
                </button>
            `;
            listContainer.append(item);
        });
        feather.replace();
    }

    window.selectApprover = function(id, name) {
        if (selectedApprovers.some(a => a.id === id)) return;
        selectedApprovers.push({ id, name });
        renderSelected();
    };

    function renderSelected() {
        selectedContainer.empty();
        if (selectedApprovers.length === 0) {
            selectedContainer.append('<div class="text-muted small p-2 w-100 text-center" id="noApproversMsg">No approvers selected.</div>');
            return;
        }

        selectedApprovers.forEach(app => {
            const chip = `
                <div class="badge bg-primary bg-opacity-10 text-primary border border-primary-subtle px-3 py-2 d-flex align-items-center gap-2 rounded-pill">
                    <i data-feather="user" style="width: 12px;"></i>
                    <span>${app.name}</span>
                    <i data-feather="x-circle" class="ms-1 cursor-pointer hover-danger" style="width: 14px;" onclick="window.removeApprover(${app.id})"></i>
                </div>
            `;
            selectedContainer.append(chip);
        });
        feather.replace();
    }

    window.removeApprover = function(id) {
        selectedApprovers = selectedApprovers.filter(a => a.id !== id);
        renderSelected();
    };

    // Save Approvers
    $('#btnSaveApprovers').on('click', function() {
        if (selectedApprovers.length === 0) {
            Swal.fire('Error', 'Please select at least one approver.', 'error');
            return;
        }

        const ids = selectedApprovers.map(a => a.id);
        
        axios.post(`{{ url('transfer/api/set-approvers') }}/${transferId}`, { approver_ids: ids })
            .then(res => {
                Swal.fire('Success', res.data.message, 'success').then(() => location.reload());
            })
            .catch(err => Swal.fire('Error', 'Failed to save approvers.', 'error'));
    });

    // Approve & Complete logic
    window.approveCareerMovement = function() {
        Swal.fire({
            title: 'Approve Career Movement?',
            text: "Are you sure you want to approve this request?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, Approve'
        }).then((result) => {
            if (result.isConfirmed) {
                axios.post(`{{ url('transfer/api/approve') }}/${transferId}`)
                    .then(res => {
                        Swal.fire('Approved!', res.data.message, 'success').then(() => location.reload());
                    })
                    .catch(err => Swal.fire('Error', 'Failed to approve.', 'error'));
            }
        });
    };

    const btnComplete = document.getElementById('btnComplete');
    if (btnComplete) {
        btnComplete.addEventListener('click', function() {
            Swal.fire({
                title: 'Finalize Career Movement?',
                text: "This will update the employee's office info and mark the transfer as complete.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Finalize'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.post(`{{ url('transfer/api/complete') }}/${transferId}`)
                        .then(res => {
                            Swal.fire('Completed!', res.data.message, 'success').then(() => location.reload());
                        })
                        .catch(err => Swal.fire('Error', 'Failed to complete transfer.', 'error'));
                }
            });
        });
    }
});
</script>
<style>
    .cursor-pointer { cursor: pointer; }
    .hover-danger:hover { color: #dc3545 !important; }
    .min-h-50 { min-height: 50px; }
</style>
@endpush
