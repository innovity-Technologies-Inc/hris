@extends('structure.master')

@section('content')
    {{-- Tax Calculate Search & Batch Execution Page --}}
    <div class="row">
        <div class="col-lg-12">
            @can('tax-policy.view')
            <div class="card border-0 shadow-sm rounded">
                <div class="card-header bg-white py-3 border-bottom border-light">
                    <h5 class="card-title mb-0 fw-bold text-dark d-flex align-items-center">
                        <i data-feather="search" class="me-2 text-primary" style="width: 20px; height: 20px;"></i>
                        Search Employee Tax
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="border rounded shadow-sm p-3 filter-section-bg">
                        <form id="filterForm">
                            {{-- Row 1: Keyword & Company --}}
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="keywordSearch" class="form-label text-muted small fw-semibold mb-1">
                                        Keyword Search
                                    </label>
                                    <div class="input-group input-group-md">
                                        <input type="text" class="form-control border-end-0" id="keywordSearch"
                                               name="keyword" placeholder="Search by employee name, applicant id, system id"
                                               aria-label="Keyword Search" value="{{ request('keyword') }}">
                                        <span class="input-group-text border-start-0 input-group-bg bg-white">
                                            <i class="mdi mdi-magnify text-muted"></i>
                                        </span>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="search_company_id" class="form-label text-muted small fw-semibold mb-1">
                                        Company <span class="text-danger">*</span>
                                    </label>
                                    <select id="search_company_id" name="company" class="form-select select2_list" data-placeholder="Select Company">
                                        <option value="">Choose One</option>
                                        @foreach ($companies as $company)
                                            <option value="{{ $company->id }}" {{ request('company') == $company->id ? 'selected' : '' }}>
                                                {{ $company->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            {{-- Row 2: Branch, Division, Department, Section (Cascading status-based) --}}
                            <div class="row g-3 mt-1">
                                {{-- Branch --}}
                                @if (App\HelperClass::getGeneralSetting()?->branch_status == 1)
                                    <div class="col-md-3">
                                        <label for="search_business_unit_id" class="form-label text-muted small fw-semibold mb-1">
                                            Branch
                                        </label>
                                        <select id="search_business_unit_id" name="business_unit" class="form-select select2_list" data-placeholder="Select Branch">
                                            <option value="">Select Branch</option>
                                            @if ($selectedBranch)
                                                <option value="{{ $selectedBranch->id }}" selected>{{ $selectedBranch->name }}</option>
                                            @endif
                                        </select>
                                    </div>
                                @endif

                                {{-- Division --}}
                                @if (App\HelperClass::getGeneralSetting()?->division_status == 1)
                                    <div class="col-md-3">
                                        <label for="search_division_id" class="form-label text-muted small fw-semibold mb-1">
                                            Division
                                        </label>
                                        <select id="search_division_id" name="division" class="form-select select2_list" data-placeholder="Select Division">
                                            <option value="">Select Division</option>
                                            @if ($selectedDivision)
                                                <option value="{{ $selectedDivision->id }}" selected>{{ $selectedDivision->name }}</option>
                                            @endif
                                        </select>
                                    </div>
                                @endif

                                {{-- Department --}}
                                @if (App\HelperClass::getGeneralSetting()?->department_status == 1)
                                    <div class="col-md-3">
                                        <label for="search_department_id" class="form-label text-muted small fw-semibold mb-1">
                                            Department
                                        </label>
                                        <select id="search_department_id" name="department" class="form-select select2_list" data-placeholder="Select Department">
                                            <option value="">Select Department</option>
                                            @if ($selectedDepartment)
                                                <option value="{{ $selectedDepartment->id }}" selected>{{ $selectedDepartment->department_name }}</option>
                                            @endif
                                        </select>
                                    </div>
                                @endif

                                {{-- Section --}}
                                @if (App\HelperClass::getGeneralSetting()?->section_status == 1)
                                    <div class="col-md-3">
                                        <label for="search_section_id" class="form-label text-muted small fw-semibold mb-1">
                                            Section
                                        </label>
                                        <select id="search_section_id" name="section" class="form-select select2_list" data-placeholder="Select Section">
                                            <option value="">Select Section</option>
                                            @if ($selectedSection)
                                                <option value="{{ $selectedSection->id }}" selected>{{ $selectedSection->name }}</option>
                                            @endif
                                        </select>
                                    </div>
                                @endif
                            </div>

                            {{-- Row 3: Action buttons --}}
                            <div class="row mt-3">
                                <div class="col-12 text-end">
                                    <button type="button" id="resetFilters" class="btn btn-outline-secondary btn-md px-4 me-2">
                                        <i class="mdi mdi-refresh me-1"></i> Reset
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endcan
        </div>

        <div class="col-lg-12 mt-3">
            <div class="card border-0 shadow-sm rounded">
                <div class="card-header bg-white py-3 border-bottom border-light d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-bold text-dark d-flex align-items-center">
                        <i data-feather="list" class="me-2 text-primary" style="width: 20px; height: 20px;"></i>
                        Employee Tax List
                    </h5>
                    <div class="d-flex gap-2">
                        <button type="button" id="exportExcelBtn" class="btn btn-success rounded-pill px-4 shadow-sm fw-bold no-loader">
                            <i class="bi bi-file-earmark-excel me-1"></i> Export
                        </button>
                        @can('tax-policy.edit')
                        <button type="button" class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold no-loader" id="calculateTaxBtn">
                            <i data-feather="cpu" class="me-1" style="width: 16px; height: 16px;"></i> Calculate Tax
                        </button>
                        @endcan
                    </div>
                </div>
                <div class="card-body">
                    <div id="search-result">
                        @include('payroll.tax_calculate.partials.search_results')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        $(document).ready(function() {
            let silenceChangeEvents = false;

            // Function to perform AJAX search
            function fetchData(url = "{{ route('tax-calculate.index') }}") {
                const queryString = $('#filterForm').serialize();

                $.ajax({
                    url: url,
                    method: "GET",
                    data: queryString,
                    beforeSend: function() {
                        $('#search-result').html(
                            '<div class="text-center py-5 text-muted"><span class="spinner-border spinner-border-sm me-2"></span> Loading Data...</div>'
                        );
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
                if (!$select.length) return;
                $select.html(`<option value="">${placeholder}</option>`);
                $select.val('').trigger('change.select2');
            }

            // On page load, initialize organizational selects if company query is preset
            const initialCompany = $('#search_company_id').val();
            if (initialCompany) {
                silenceChangeEvents = true;
                const urlParams = new URLSearchParams(window.location.search);
                loadHierarchy(
                    initialCompany,
                    urlParams.get('business_unit'),
                    urlParams.get('division'),
                    urlParams.get('department'),
                    urlParams.get('section')
                ).then(function() {
                    silenceChangeEvents = false;
                });
            }

            // Bind selectors change cascading chains
            $('#search_company_id').on('change', function () {
                if (silenceChangeEvents) return;
                const companyId = $(this).val();
                loadHierarchy(companyId).then(fetchData);
            });

            $('#search_business_unit_id').on('change', function () {
                if (silenceChangeEvents) return;
                const companyId = $('#search_company_id').val();
                const branchId = $(this).val() || 'null';
                ajaxLoad(`/get-divisions/${companyId}/${branchId}`, $('#search_division_id'), 'Select Division')
                    .then(() => resetSelect($('#search_department_id'), 'Select Department'))
                    .then(() => resetSelect($('#search_section_id'), 'Select Section'))
                    .then(fetchData);
            });

            $('#search_division_id').on('change', function () {
                if (silenceChangeEvents) return;
                const companyId = $('#search_company_id').val();
                const branchId = $('#search_business_unit_id').val() || 'null';
                const divisionId = $(this).val() || 'null';
                ajaxLoad(`/get-departments/${companyId}/${branchId}/${divisionId}`, $('#search_department_id'), 'Select Department')
                    .then(() => resetSelect($('#search_section_id'), 'Select Section'))
                    .then(fetchData);
            });

            $('#search_department_id').on('change', function () {
                if (silenceChangeEvents) return;
                const companyId = $('#search_company_id').val();
                const branchId = $('#search_business_unit_id').val() || 'null';
                const divisionId = $('#search_division_id').val() || 'null';
                const departmentId = $(this).val() || 'null';
                ajaxLoad(`/get-sections/${companyId}/${branchId}/${divisionId}/${departmentId}`, $('#search_section_id'), 'Select Section')
                    .then(fetchData);
            });

            $('#search_section_id').on('change', function () {
                if (silenceChangeEvents) return;
                fetchData();
            });

            // Trigger search on keyword input
            $('#keywordSearch').on('input', function(e) {
                e.preventDefault();
                fetchData();
            });

            // Reset filters
            $('#resetFilters').on('click', function() {
                silenceChangeEvents = true;
                $('#filterForm')[0].reset();
                $('.select2_list').val('').trigger('change.select2');
                resetSelect($('#search_business_unit_id'), 'Select Branch');
                resetSelect($('#search_division_id'), 'Select Division');
                resetSelect($('#search_department_id'), 'Select Department');
                resetSelect($('#search_section_id'), 'Select Section');
                silenceChangeEvents = false;
                fetchData();
            });

            // Handle pagination links via AJAX
            $(document).on('click', '.pagination a', function(e) {
                e.preventDefault();
                const url = $(this).attr('href');
                if (url) {
                    fetchData(url);
                }
            });

            // Trigger Tax Calculation batch processing
            $('#calculateTaxBtn').on('click', function(e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Are you sure?',
                    text: "This will run the tax calculation formula for all active employees. Existing tax logs will be updated.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, calculate now!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        let progressInterval = null;

                        Swal.fire({
                            title: 'Calculating...',
                            html: `
                                <div class="text-center">
                                    <p id="taxProgressMessage" class="mb-2 text-muted">Starting employee tax bracket processing...</p>
                                    <div class="progress mt-3" style="height: 25px; border-radius: 12px; overflow: hidden; background-color: rgba(0,0,0,0.05);">
                                        <div id="taxProgressBar" class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar" style="width: 0%; font-weight: bold; line-height: 25px;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                                    </div>
                                    <small class="text-muted mt-2 d-block" id="taxProgressCounter">Preparing calculation parameters...</small>
                                </div>
                            `,
                            allowOutsideClick: false,
                            showConfirmButton: false
                        });

                        // Start polling progress
                        progressInterval = setInterval(() => {
                            axios.get("{{ route('tax-calculate.progress') }}")
                                .then(res => {
                                    const data = res.data;
                                    if (data && data.total > 0) {
                                        const percentage = Math.round((data.processed / data.total) * 100);
                                        $('#taxProgressBar').css('width', percentage + '%').attr('aria-valuenow', percentage).text(percentage + '%');
                                        $('#taxProgressCounter').text(`Processed ${data.processed} of ${data.total} employees`);
                                        if (data.status === 'completed') {
                                            clearInterval(progressInterval);
                                        } else if (data.status === 'failed') {
                                            clearInterval(progressInterval);
                                            Swal.fire({
                                                icon: 'error',
                                                title: 'Calculation Failed',
                                                text: data.error || 'Failed to process calculations.'
                                            });
                                        }
                                    }
                                })
                                .catch(err => {
                                    console.error('Error fetching progress:', err);
                                });
                        }, 800);

                        axios.post("{{ route('tax-calculate.calculate') }}")
                            .then(response => {
                                clearInterval(progressInterval);
                                Swal.close();
                                if (response.data.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Calculated!',
                                        text: response.data.message,
                                        timer: 2000,
                                        showConfirmButton: false
                                    }).then(() => {
                                        fetchData();
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Calculation Failed',
                                        text: response.data.message
                                    });
                                }
                            })
                            .catch(error => {
                                clearInterval(progressInterval);
                                Swal.close();
                                const msg = error.response?.data?.message || 'Failed to trigger tax calculation process.';
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: msg
                                });
                            });
                    }
                });
            });

            // Excel Export click handler
            $(document).on('click', '#exportExcelBtn', function(e) {
                e.preventDefault();
                let queryString = $('#filterForm').serialize();
                let baseUrl = "{{ route('tax-calculate.export') }}";
                window.location.href = baseUrl + '?' + queryString;
            });
        });
    </script>
@endsection
