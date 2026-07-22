@extends('structure.master')

@section('content')

    {{-- Leave Applications List --}}
    <div class="row">
        <div class="col-lg-12 mt-3">
            @can('leaves.view')
            <div class="card border-0 shadow-sm rounded">
                <div class="card-header">
                    <h5 class="card-title mb-0">Search Leave Applications</h5>
                </div>
                <div class="card-body">
                    <div class="border rounded shadow-sm p-3 filter-section-bg">
                        <form id="filterForm">
                            <div class="row g-3">
                                        {{-- Employee Name / Keyword --}}
                                        <div class="col-md-4">
                                            <label for="keyword" class="form-label text-muted small fw-semibold mb-1">
                                                Employee Name
                                            </label>
                                            <input type="text" class="form-control" id="keyword" name="keyword"
                                                placeholder="Search by employee name, leave plan..." value="{{ request('keyword') }}">
                                        </div>

                                        {{-- From Date --}}
                                        <div class="col-md-4">
                                            <label for="from" class="form-label text-muted small fw-semibold mb-1">
                                                From Date
                                            </label>
                                            <input type="date" id="from" name="from" class="form-control"
                                                value="{{ request('from') }}">
                                        </div>

                                        {{-- To Date --}}
                                        <div class="col-md-4">
                                            <label for="to" class="form-label text-muted small fw-semibold mb-1">
                                                To Date
                                            </label>
                                            <input type="date" id="to" name="to" class="form-control"
                                                value="{{ request('to') }}">
                                        </div>
                                    </div>

                                    <div class="row g-3 mt-1">
                                        {{-- Company --}}
                                        <div class="col-md-4">
                                            <label for="search_company_id" class="form-label text-muted small fw-semibold mb-1">
                                                Company
                                            </label>
                                            <select id="search_company_id" name="company"
                                                class="form-select select2_list"
                                                data-placeholder="Select Company">
                                                <option value="">Choose One</option>
                                                @foreach ($companies as $company)
                                                    <option value="{{ $company->id }}" {{ request('company') == $company->id ? 'selected' : '' }}>
                                                        {{ $company->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        {{-- Branch --}}
                                        @if (App\HelperClass::getGeneralSetting()->branch_status == 1)
                                            <div class="col-md-4">
                                                <label for="search_business_unit_id" class="form-label text-muted small fw-semibold mb-1">
                                                    Branch
                                                </label>
                                                <select id="search_business_unit_id" name="business_unit"
                                                    class="form-select select2_list"
                                                    data-placeholder="Select Branch">
                                                    <option value="">Select Branch</option>
                                                    @if ($selectedBranch)
                                                        <option value="{{ $selectedBranch->id }}" selected>{{ $selectedBranch->name }}</option>
                                                    @endif
                                                </select>
                                            </div>
                                        @endif

                                        {{-- Division --}}
                                        @if (App\HelperClass::getGeneralSetting()->division_status == 1)
                                            <div class="col-md-4">
                                                <label for="search_division_id" class="form-label text-muted small fw-semibold mb-1">
                                                    Division
                                                </label>
                                                <select id="search_division_id" name="division"
                                                    class="form-select select2_list"
                                                    data-placeholder="Select Division">
                                                    <option value="">Select Division</option>
                                                    @if ($selectedDivision)
                                                        <option value="{{ $selectedDivision->id }}" selected>{{ $selectedDivision->name }}</option>
                                                    @endif
                                                </select>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="row g-3 mt-1">
                                        {{-- Department --}}
                                        @if (App\HelperClass::getGeneralSetting()->department_status == 1)
                                            <div class="col-md-4">
                                                <label for="search_department_id" class="form-label text-muted small fw-semibold mb-1">
                                                    Department
                                                </label>
                                                <select id="search_department_id" name="department"
                                                    class="form-select select2_list"
                                                    data-placeholder="Select Department">
                                                    <option value="">Select Department</option>
                                                    @if ($selectedDepartment)
                                                        <option value="{{ $selectedDepartment->id }}" selected>{{ $selectedDepartment->department_name }}</option>
                                                    @endif
                                                </select>
                                            </div>
                                        @endif

                                        {{-- Section --}}
                                        @if (App\HelperClass::getGeneralSetting()->section_status == 1)
                                            <div class="col-md-4">
                                                <label for="search_section_id" class="form-label text-muted small fw-semibold mb-1">
                                                    Section
                                                </label>
                                                <select id="search_section_id" name="section"
                                                    class="form-select select2_list"
                                                    data-placeholder="Select Section">
                                                    <option value="">Select Section</option>
                                                    @if ($selectedSection)
                                                        <option value="{{ $selectedSection->id }}" selected>{{ $selectedSection->name }}</option>
                                                    @endif
                                                </select>
                                            </div>
                                        @endif

                                        {{-- Reset Button --}}
                                        <div class="col-md-4">
                                            <label class="form-label mb-1">&nbsp;</label>
                                            <button type="button" id="resetFilters" class="btn btn-outline-secondary w-100">
                                                <i style="height: 14px; width: 14px" data-feather="refresh-cw" class="me-1"></i> Reset Filters
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endcan
            </div>
        </div>


        <div class="col-lg-12">
            <div class="card border-0 shadow-sm rounded">
                <div class="card-header">
                    <h5 class="card-title mb-0">Leave Applications List</h5>
                </div>
                <div class="card-body">
                    {{-- Action Buttons --}}
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        @can('leaves.create')
                        <a class="btn btn-warning btn-sm" href="{{ route('leave.create') }}">
                            <i style="height: 12px; width: 12px" data-feather="plus" class="me-1"></i> Create
                        </a>
                        @else
                        <div></div>
                        @endcan

                        <div class="d-flex gap-2">
                            @can('leaves.view')
                            <div class="d-flex gap-2">
                                <button type="button" id="exportExcelBtn" class="btn btn-success btn-sm no-loader">
                                    <i class="bi bi-file-earmark-excel me-1"></i> Excel
                                </button>
                                <button type="button" id="printBtn" class="btn btn-secondary btn-sm no-loader">
                                    <i class="bi bi-printer me-1"></i> Print
                                </button>
                            </div>
                            @endcan

                            @can('leaves.import')
                            <button type="button" class="btn btn-outline-success btn-sm" data-bs-toggle="modal"
                                data-bs-target="#bulkUploadModal">
                                <i style="height: 12px; width: 12px" data-feather="upload" class="me-1"></i> Upload Bulk
                            </button>
                            @endcan
                        </div>
                    </div>
                    @if ($leaves->isEmpty())
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-3 opacity-25"></i>
                            No leave applications found.
                        </div>
                    @else
                        <div class="table-responsive" id="search-result">
                            @include('leave.search_results')
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>



    {{-- Include Import Modal --}}
    @include('leave.partials.import_modal')

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize Select2 for all select2_list elements
            $('.select2_list').select2({
                placeholder: function() {
                    return $(this).data('placeholder');
                },
                allowClear: true,
                width: '100%'
            });

            // Initialize Feather icons
            if (typeof feather !== 'undefined') {
                feather.replace();
            }

            let silenceChangeEvents = false;

            // Function to perform AJAX search
            function fetchData(url = "{{ route('leave.index') }}") {
                const queryString = $('#filterForm').serialize();

                $.ajax({
                    url: url,
                    method: "GET",
                    data: queryString,
                    beforeSend: function() {
                        $('#search-result').html(
                            '<div class="text-center py-4 text-muted">Loading Data...</div>'
                        );
                    },
                    success: function(response) {
                        $('#search-result').html(response);
                        // Reinitialize Feather icons if used in results
                        if (typeof feather !== 'undefined') {
                            feather.replace();
                        }
                        // Update URL without page param
                        const newUrl = '?' + queryString;
                        window.history.pushState(null, '', newUrl || location.pathname);
                    },
                    error: function(xhr) {
                        console.error('AJAX Error:', xhr.responseText);
                    }
                });
            }

            // Standard Ajax Loader with Pre-selection support
            function ajaxLoad(url, $select, placeholder, selectedValue = null) {
                if (!$select.length) return Promise.resolve();
                return $.get(url).then(function (data) {
                    $select.html(`<option value="">${placeholder}</option>`);
                    $.each(data, function (_, item) {
                        $select.append(
                            `<option value="${item.id}">${item.name ?? item.department_name ?? item.full_name}</option>`
                        );
                    });
                    if (selectedValue && selectedValue !== 'null' && selectedValue !== '') {
                        $select.val(selectedValue).trigger('change.select2');
                    } else {
                        $select.val('').trigger('change.select2');
                    }
                }).catch(function () {
                    $select.html('<option value="">Error loading data</option>');
                });
            }

            function loadHierarchy(companyId, branchId = null, divisionId = null, departmentId = null, sectionId = null) {
                if (!companyId) {
                    resetSelect($('#search_business_unit_id'), 'Select Branch');
                    resetSelect($('#search_division_id'), 'Select Division');
                    resetSelect($('#search_department_id'), 'Select Department');
                    resetSelect($('#search_section_id'), 'Select Section');
                    return Promise.resolve();
                }

                let branchPromise = Promise.resolve();
                if ($('#search_business_unit_id').length) {
                    branchPromise = ajaxLoad(`/get-units/${companyId}`, $('#search_business_unit_id'), 'Select Branch', branchId);
                }

                return branchPromise.then(() => {
                    const currentBranchId = $('#search_business_unit_id').val() || 'null';
                    return ajaxLoad(`/get-divisions/${companyId}/${currentBranchId}`, $('#search_division_id'), 'Select Division', divisionId);
                }).then(() => {
                    const currentBranchId = $('#search_business_unit_id').val() || 'null';
                    const currentDivisionId = $('#search_division_id').val() || 'null';
                    return ajaxLoad(`/get-departments/${companyId}/${currentBranchId}/${currentDivisionId}`, $('#search_department_id'), 'Select Department', departmentId);
                }).then(() => {
                    const currentBranchId = $('#search_business_unit_id').val() || 'null';
                    const currentDivisionId = $('#search_division_id').val() || 'null';
                    const currentDeptId = $('#search_department_id').val() || 'null';
                    return ajaxLoad(`/get-sections/${companyId}/${currentBranchId}/${currentDivisionId}/${currentDeptId}`, $('#search_section_id'), 'Select Section', sectionId);
                });
            }

            function resetSelect($select, placeholder) {
                $select.html(`<option value="">${placeholder}</option>`).val('').trigger('change.select2');
            }

            // On page load, if company is preloaded/selected:
            const initialCompanyId = $('#search_company_id').val();
            if (initialCompanyId) {
                const initialBranchId = "{{ request('business_unit') }}";
                const initialDivisionId = "{{ request('division') }}";
                const initialDepartmentId = "{{ request('department') }}";
                const initialSectionId = "{{ request('section') }}";

                silenceChangeEvents = true;
                loadHierarchy(initialCompanyId, initialBranchId, initialDivisionId, initialDepartmentId, initialSectionId).then(() => {
                    silenceChangeEvents = false;
                });
            }

            // Change listeners for cascading dropdowns
            $('#search_company_id').on('change', function (e) {
                if (silenceChangeEvents) return;
                silenceChangeEvents = true;
                loadHierarchy($(this).val()).then(() => {
                    silenceChangeEvents = false;
                    fetchData();
                });
            });

            $('#search_business_unit_id').on('change', function (e) {
                if (silenceChangeEvents) return;
                silenceChangeEvents = true;
                const companyId = $('#search_company_id').val();
                loadHierarchy(companyId, $(this).val()).then(() => {
                    silenceChangeEvents = false;
                    fetchData();
                });
            });

            $('#search_division_id').on('change', function (e) {
                if (silenceChangeEvents) return;
                silenceChangeEvents = true;
                const companyId = $('#search_company_id').val();
                const branchId = $('#search_business_unit_id').val() || 'null';
                loadHierarchy(companyId, branchId, $(this).val()).then(() => {
                    silenceChangeEvents = false;
                    fetchData();
                });
            });

            $('#search_department_id').on('change', function (e) {
                if (silenceChangeEvents) return;
                silenceChangeEvents = true;
                const companyId = $('#search_company_id').val();
                const branchId = $('#search_business_unit_id').val() || 'null';
                const divisionId = $('#search_division_id').val() || 'null';
                loadHierarchy(companyId, branchId, divisionId, $(this).val()).then(() => {
                    silenceChangeEvents = false;
                    fetchData();
                });
            });

            $('#search_section_id').on('change', function (e) {
                if (silenceChangeEvents) return;
                fetchData();
            });

            // Trigger search on input or change
            $('#filterForm').on('input change', function(e) {
                e.preventDefault();
                // If it's a programmatic change from a select2_list, we skip it because
                // it is handled by the dedicated change listeners above.
                if (silenceChangeEvents) return;
                if (e.type === 'change' && e.target.classList.contains('select2_list') && !e.originalEvent) {
                    return;
                }
                fetchData();
            });

            // Reset filters: clear form, select2 inputs, and reload base URL
            $('#resetFilters').on('click', function() {
                $('#filterForm')[0].reset();
                $('.select2_list').val('').trigger('change.select2');
                window.location.href = "{{ route('leave.index') }}";
            });

            // Handle pagination via AJAX
            $(document).on('click', '#search-result .pagination a', function(e) {
                e.preventDefault();
                const url = $(this).attr('href');
                fetchData(url);
            });

            // Handle delete via Axios & SweetAlert2
            $(document).on('click', '.confirmDelete', function(e) {
                e.preventDefault();
                const btn = $(this);
                const form = btn.closest('form');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
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
                                    text: error.response?.data?.message || 'Failed to delete leave request.'
                                });
                            });
                    }
                });
            });

            // Excel Export click handler
            $(document).on('click', '#exportExcelBtn', function(e) {
                e.preventDefault();
                window.ignoreBeforeUnload = true;
                setTimeout(() => {
                    window.ignoreBeforeUnload = false;
                }, 2000);
                let queryString = $('#filterForm').serialize();
                let baseUrl = "{{ route('leave.export.excel') }}";
                window.location.href = baseUrl + '?' + queryString;
            });

            // Print click handler
            $(document).on('click', '#printBtn', function(e) {
                e.preventDefault();
                let queryString = $('#filterForm').serialize();
                let baseUrl = "{{ route('leave.print') }}";
                window.open(baseUrl + '?' + queryString, '_blank');
            });
        });
    </script>

    {{-- Style overrides matching standard aesthetics --}}
    <style>
        .filter-section-bg {
            background-color: var(--bs-tertiary-bg);
        }

        .leave-row:hover {
            background-color: var(--bs-tertiary-bg);
        }

        .badge {
            font-weight: 500;
            font-size: 0.75rem;
        }

        #leaveTable th,
        #leaveTable td {
            white-space: nowrap;
            padding: 0.75rem 0.5rem !important;
        }

        #leaveTable th:first-child,
        #leaveTable td:first-child {
            padding-left: 1rem !important;
        }

        #leaveTable th:last-child,
        #leaveTable td:last-child {
            padding-right: 1rem !important;
        }
    </style>
@endsection

