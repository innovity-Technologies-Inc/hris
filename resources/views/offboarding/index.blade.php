@extends('structure.master')

@section('content')
    <div class="row">
        {{-- Search & Filter Section --}}
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm rounded">
                <div class="card-header">
                    <h5 class="card-title mb-0">Search {{ ucfirst($type) }}</h5>
                </div>
                <div class="card-body">
                    <div class="border rounded shadow-sm p-3 filter-section-bg">
                        <form id="filterForm">
                            {{-- First Row: Keyword & Employee Info --}}
                            <div class="row g-3 mb-3">
                                <div class="col-md-3">
                                    <label for="keywordSearch" class="form-label text-muted small fw-semibold mb-1">
                                        Keyword Search
                                    </label>
                                    <div class="input-group input-group-md">
                                        <input type="text" class="form-control border-end-0" id="keywordSearch"
                                            name="keyword" placeholder="Search by reason, remarks..."
                                            value="{{ request('keyword') }}">
                                        <span class="input-group-text border-start-0 input-group-bg">
                                            <i class="mdi mdi-magnify text-muted"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <label for="employeeName" class="form-label text-muted small fw-semibold mb-1">
                                        Employee Name
                                    </label>
                                    <input type="text" class="form-control" id="employeeName" name="employee_name"
                                        placeholder="Search by name" value="{{ request('employee_name') }}">
                                </div>
                                <div class="col-md-3">
                                    <label for="employeeId" class="form-label text-muted small fw-semibold mb-1">
                                        Employee ID
                                    </label>
                                    <input type="text" class="form-control" id="employeeId" name="employee_id"
                                        placeholder="Search by Employee ID" value="{{ request('employee_id') }}">
                                </div>
                                <div class="col-md-3">
                                    <label for="systemId" class="form-label text-muted small fw-semibold mb-1">
                                        System ID
                                    </label>
                                    <input type="text" class="form-control" id="systemId" name="system_id"
                                        placeholder="Search by System ID" value="{{ request('system_id') }}">
                                </div>
                            </div>

                            {{-- Second Row: Org Hierarchy --}}
                            <div class="row g-3 mb-3">
                                <div class="col-md-4">
                                    <label for="company_id" class="form-label text-muted small fw-semibold mb-1">
                                        Company
                                    </label>
                                    <select name="company_id" id="company_id" class="form-select select2">
                                        <option value="">-- All Companies --</option>
                                        @foreach($companies as $company)
                                            <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>
                                                {{ $company->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                @if(isset($generalSettings->branch_status) && $generalSettings->branch_status == 1)
                                <div class="col-md-4">
                                    <label for="branch_id" class="form-label text-muted small fw-semibold mb-1">
                                        Branch / Location
                                    </label>
                                    <select name="branch_id" id="branch_id" class="form-select select2">
                                        <option value="">-- All Branches --</option>
                                    </select>
                                </div>
                                @endif

                                @if(isset($generalSettings->division_status) && $generalSettings->division_status == 1)
                                <div class="col-md-4">
                                    <label for="division_id" class="form-label text-muted small fw-semibold mb-1">
                                        Division
                                    </label>
                                    <select name="division_id" id="division_id" class="form-select select2">
                                        <option value="">-- All Divisions --</option>
                                    </select>
                                </div>
                                @endif
                            </div>

                            {{-- Third Row: Dept, Section, Status --}}
                            <div class="row g-3 mb-3">
                                @if(isset($generalSettings->department_status) && $generalSettings->department_status == 1)
                                <div class="col-md-4">
                                    <label for="department_id" class="form-label text-muted small fw-semibold mb-1">
                                        Department
                                    </label>
                                    <select name="department_id" id="department_id" class="form-select select2">
                                        <option value="">-- All Departments --</option>
                                    </select>
                                </div>
                                @endif

                                @if(isset($generalSettings->section_status) && $generalSettings->section_status == 1)
                                <div class="col-md-4">
                                    <label for="section_id" class="form-label text-muted small fw-semibold mb-1">
                                        Section
                                    </label>
                                    <select name="section_id" id="section_id" class="form-select select2">
                                        <option value="">-- All Sections --</option>
                                    </select>
                                </div>
                                @endif

                                <div class="col-md-4">
                                    <label for="statusFilter" class="form-label text-muted small fw-semibold mb-1">
                                        Status
                                    </label>
                                    <select class="form-select" id="statusFilter" name="status">
                                        <option value="">All Status</option>
                                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Fourth Row: Date Range & Action --}}
                            <div class="row g-3 align-items-end">
                                <div class="col-md-4">
                                    <label for="fromDate" class="form-label text-muted small fw-semibold mb-1">
                                        From Date (Notice Date)
                                    </label>
                                    <input type="date" class="form-control" id="fromDate" name="from"
                                        value="{{ request('from') }}">
                                </div>
                                <div class="col-md-4">
                                    <label for="toDate" class="form-label text-muted small fw-semibold mb-1">
                                        To Date (Notice Date)
                                    </label>
                                    <input type="date" class="form-control" id="toDate" name="to"
                                        value="{{ request('to') }}">
                                </div>
                                <div class="col-md-4 text-end">
                                    <button type="button" id="resetFilters" class="btn btn-outline-secondary btn-sm">
                                        <i class="mdi mdi-refresh"></i> Reset Filters
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Offboarding List --}}
        <div class="col-lg-12 mt-3">
            <div class="card border-0 shadow-sm rounded">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ ucfirst($type) }} List</h5>
                </div>
                <div class="card-body">
                    {{-- Action Buttons --}}
                    <div class="d-flex justify-content-between mb-3">
                        @php
                            $createPermission = $type === 'termination' ? 'terminations.create' : 'resignations.create';
                        @endphp
                        @can($createPermission)
                        <a class="btn btn-warning btn-sm" href="{{ route('offboarding.' . $type . '.create', ['type' => $type]) }}">
                            <i style="height: 12px; width: 12px" data-feather="plus"></i> Create
                        </a>
                        @endcan
                    </div>
                    <div class="table-responsive" id="search-result">
                        @include('offboarding.search_results')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize Feather icons
            if (typeof feather !== 'undefined') {
                feather.replace();
            }

            // Initialize Select2 if available
            if ($.fn.select2) {
                $('.select2').select2({
                    theme: 'bootstrap-5',
                    allowClear: true,
                    width: '100%'
                });
            }

            function ajaxLoad(url, $select, placeholder, selectedValue = null){
                if (!$select.length) return Promise.resolve();
                return $.get(url).then(function(data){
                    $select.html(`<option value="">${placeholder}</option>`);
                    data.forEach(item=>{
                        $select.append(
                            `<option value="${item.id}">${item.name ?? item.department_name ?? item.full_name}</option>`
                        );
                    });
                    if(selectedValue){
                        $select.val(selectedValue).trigger('change');
                    }
                }).catch(function(){
                    $select.html('<option value="">Error loading data</option>');
                });
            }

            function loadBranches(companyId, selected=null){
                if(!companyId) return Promise.resolve();
                return ajaxLoad(`/get-units/${companyId}`, $('#branch_id'), '-- All Branches --', selected);
            }

            function loadDivisions(companyId, branchId, selected=null){
                return ajaxLoad(`/get-divisions/${companyId}/${branchId ?? 'null'}`, $('#division_id'), '-- All Divisions --', selected);
            }

            function loadDepartments(companyId, branchId, divisionId, selected=null){
                return ajaxLoad(`/get-departments/${companyId}/${branchId ?? 'null'}/${divisionId ?? 'null'}`, $('#department_id'), '-- All Departments --', selected);
            }

            function loadSections(companyId, branchId, divisionId, departmentId, selected=null){
                return ajaxLoad(`/get-sections/${companyId}/${branchId ?? 'null'}/${divisionId ?? 'null'}/${departmentId ?? 'null'}`, $('#section_id'), '-- All Sections --', selected);
            }

            $('#company_id').on('change', function(){
                let company = $(this).val();
                if(!company) {
                    $('#branch_id, #division_id, #department_id, #section_id').html('<option value="">-- All --</option>').trigger('change');
                    return;
                }
                loadBranches(company);
                loadDivisions(company);
                loadDepartments(company);
                loadSections(company);
            });

            $('#branch_id').on('change', function(){
                let company = $('#company_id').val();
                let branch = $(this).val();
                if(company) {
                    loadDivisions(company, branch);
                    loadDepartments(company, branch);
                    loadSections(company, branch);
                }
            });

            $('#division_id').on('change', function(){
                let company = $('#company_id').val();
                let branch = $('#branch_id').val();
                let division = $(this).val();
                if(company) {
                    loadDepartments(company, branch, division);
                    loadSections(company, branch, division);
                }
            });

            $('#department_id').on('change', function(){
                let company = $('#company_id').val();
                let branch = $('#branch_id').val();
                let division = $('#division_id').val();
                let department = $(this).val();
                if(company) {
                    loadSections(company, branch, division, department);
                }
            });

            // Autoload based on request query parameters
            const filterData = {
                company: "{{ request('company_id') ?? '' }}",
                branch: "{{ request('branch_id') ?? '' }}",
                division: "{{ request('division_id') ?? '' }}",
                department: "{{ request('department_id') ?? '' }}",
                section: "{{ request('section_id') ?? '' }}"
            };

            if (filterData.company) {
                loadBranches(filterData.company, filterData.branch)
                    .then(() => loadDivisions(filterData.company, filterData.branch, filterData.division))
                    .then(() => loadDepartments(filterData.company, filterData.branch, filterData.division, filterData.department))
                    .then(() => loadSections(filterData.company, filterData.branch, filterData.division, filterData.department, filterData.section));
            }

            function fetchData(url = "{{ route('offboarding.' . $type . '.index') }}") {
                const queryString = $('#filterForm').serialize();

                $.ajax({
                    url: url,
                    method: "GET",
                    data: queryString,
                    beforeSend: function() {
                        $('#search-result').html('<div class="text-center py-5 text-muted"><i class="mdi mdi-spin mdi-loading fs-2 d-block mb-2"></i> Loading data...</div>');
                    },
                    success: function(response) {
                        $('#search-result').html(response);
                        if (typeof feather !== 'undefined') {
                            feather.replace();
                        }
                        const newUrl = '?' + queryString;
                        window.history.pushState(null, '', newUrl || location.pathname);
                    },
                    error: function(xhr) {
                        console.error('AJAX Error:', xhr.responseText);
                    }
                });
            }

            let searchTimer;
            function triggerSearch() {
                clearTimeout(searchTimer);
                searchTimer = setTimeout(fetchData, 300);
            }

            $('#filterForm').on('input change', function(e) {
                e.preventDefault();
                triggerSearch();
            });

            $('#resetFilters').on('click', function() {
                $('#filterForm')[0].reset();
                if ($.fn.select2) {
                    $('.select2').val(null).trigger('change');
                }
                window.location.href = "{{ route('offboarding.' . $type . '.index') }}";
            });

            $(document).on('click', '#search-result .pagination a', function(e) {
                e.preventDefault();
                const url = $(this).attr('href');
                fetchData(url);
            });

            // Axios delete confirmation
            $(document).on('click', '.confirmDelete', function(e) {
                e.preventDefault();
                const btn = $(this);
                const form = btn.closest('form');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "This offboarding record will be deleted!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        axios.delete(form.attr('action'))
                            .then(response => {
                                const res = response.data;
                                if (res.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Deleted!',
                                        text: res.message,
                                        timer: 1500,
                                        showConfirmButton: false
                                    }).then(() => {
                                        fetchData();
                                    });
                                }
                            })
                            .catch(error => {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: error.response?.data?.message || 'Failed to delete record.'
                                });
                            });
                    }
                });
            });
        });
    </script>
@endsection
