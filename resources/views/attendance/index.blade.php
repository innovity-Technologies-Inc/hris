@extends('structure.master')

@section('content')
    <div class="row">
        {{-- Search & Filter Section --}}
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm rounded">
                <div class="card-header">
                    <h5 class="card-title mb-0">Search Attendance</h5>
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
                                        placeholder="Search by employee name" value="{{ request('keyword') }}">
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
                                <div class="col-md-3">
                                    <label for="search_company_id" class="form-label text-muted small fw-semibold mb-1">
                                        Company
                                    </label>
                                    <select id="search_company_id" name="company"
                                        class="form-select form-select-sm select2_list"
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
                                    <div class="col-md-2">
                                        <label for="search_business_unit_id" class="form-label text-muted small fw-semibold mb-1">
                                            Branch
                                        </label>
                                        <select id="search_business_unit_id" name="business_unit"
                                            class="form-select form-select-sm select2_list"
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
                                    <div class="col-md-2">
                                        <label for="search_division_id" class="form-label text-muted small fw-semibold mb-1">
                                            Division
                                        </label>
                                        <select id="search_division_id" name="division"
                                            class="form-select form-select-sm select2_list"
                                            data-placeholder="Select Division">
                                            <option value="">Select Division</option>
                                            @if ($selectedDivision)
                                                <option value="{{ $selectedDivision->id }}" selected>{{ $selectedDivision->name }}</option>
                                            @endif
                                        </select>
                                    </div>
                                @endif

                                {{-- Department --}}
                                @if (App\HelperClass::getGeneralSetting()->department_status == 1)
                                    <div class="col-md-3">
                                        <label for="search_department_id" class="form-label text-muted small fw-semibold mb-1">
                                            Department
                                        </label>
                                        <select id="search_department_id" name="department"
                                            class="form-select form-select-sm select2_list"
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
                                    <div class="col-md-2">
                                        <label for="search_section_id" class="form-label text-muted small fw-semibold mb-1">
                                            Section
                                        </label>
                                        <select id="search_section_id" name="section"
                                            class="form-select form-select-sm select2_list"
                                            data-placeholder="Select Section">
                                            <option value="">Select Section</option>
                                            @if ($selectedSection)
                                                <option value="{{ $selectedSection->id }}" selected>{{ $selectedSection->name }}</option>
                                            @endif
                                        </select>
                                    </div>
                                @endif
                            </div>

                            {{-- Reset Button --}}
                            <div class="row mt-3">
                                <div class="col-md-12 text-end">
                                    <button type="button" id="resetFilters" class="btn btn-outline-secondary btn-sm">
                                        <i style="height: 14px; width: 14px" data-feather="refresh-cw" class="me-1"></i> Reset Filters
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Attendance List Card --}}
        <div class="col-lg-12 mt-3">
            <div class="card border-0 shadow-sm rounded">
                <div class="card-header">
                    <h5 class="card-title mb-0">Attendance List</h5>
                </div>
                <div class="card-body">
                    {{-- Action Buttons --}}
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        @can('attendance.create')
                        <a class="btn btn-warning btn-sm" href="{{ route('attendance.create') }}">
                            <i style="height: 12px; width: 12px" data-feather="plus"></i> Create
                        </a>
                        @else
                        <div></div>
                        @endcan

                        @can('attendance.view')
                        <div class="btn-group">
                            <button type="button" id="exportExcelBtn" class="btn btn-outline-success btn-sm no-loader">
                                <i style="height: 12px; width: 12px" data-feather="file-text" class="me-1"></i> Export Excel
                            </button>
                            <button type="button" id="printBtn" class="btn btn-outline-danger btn-sm no-loader">
                                <i style="height: 12px; width: 12px" data-feather="printer" class="me-1"></i> Print
                            </button>
                        </div>
                        @endcan
                    </div>

                    <div class="table-responsive" id="search-result">
                        @include('attendance.partials.search_results')
                    </div>
                </div>
            </div>
        </div>
    </div>

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
            function fetchData(url = "{{ route('attendance.index') }}") {
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
                window.location.href = "{{ route('attendance.index') }}";
            });

            // Handle pagination via AJAX
            $(document).on('click', '#search-result .pagination a', function(e) {
                e.preventDefault();
                const url = $(this).attr('href');
                fetchData(url);
            });

            // Excel Export click handler
            $(document).on('click', '#exportExcelBtn', function(e) {
                e.preventDefault();
                window.ignoreBeforeUnload = true;
                setTimeout(() => {
                    window.ignoreBeforeUnload = false;
                }, 2000);
                let queryString = $('#filterForm').serialize();
                let baseUrl = "{{ route('attendance.export.excel') }}";
                window.location.href = baseUrl + '?' + queryString;
            });

            // Print click handler
            $(document).on('click', '#printBtn', function(e) {
                e.preventDefault();
                let queryString = $('#filterForm').serialize();
                let baseUrl = "{{ route('attendance.print') }}";
                window.open(baseUrl + '?' + queryString, '_blank');
            });
        });
    </script>

    {{-- Style overrides matching standard aesthetics --}}
    <style>
        .filter-section-bg {
            background-color: var(--bs-tertiary-bg);
        }

        .attendance-row:hover {
            background-color: var(--bs-tertiary-bg);
        }

        .badge {
            font-weight: 500;
            font-size: 0.75rem;
        }

        #attendanceTable th,
        #attendanceTable td {
            white-space: nowrap;
            padding: 0.75rem 0.5rem !important;
        }

        #attendanceTable th:first-child,
        #attendanceTable td:first-child {
            padding-left: 1rem !important;
        }

        #attendanceTable th:last-child,
        #attendanceTable td:last-child {
            padding-right: 1rem !important;
        }

        /* Modal styling */
        .modal-content {
            border-radius: 1rem;
        }

        .modal-header {
            border-radius: 1rem 1rem 0 0;
        }

        .info-item {
            padding: 0.75rem;
            border-radius: 0.5rem;
            background-color: var(--bs-light-bg-subtle);
        }
    </style>
@endsection
