@extends('structure.master')

@section('content')
    {{-- Employee List --}}
    <div class="row">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm rounded">
                   <div class="card-header">
                    <h5 class="card-title mb-0">Search Employees</h5>
                </div><!-- end card header -->
                <div class="card-header bg-white border-bottom p-4">
                    <div class="row align-items-start">

                        {{-- Filter Section --}}
                        <div class="col-md-12">
                            <div class="border rounded shadow-sm p-3 bg-light">
                                <form id="filterForm">
                                    {{-- First Row: Keyword Search --}}
                                    <div class="row mb-2">
                                        <div class="col-12">
                                            <label for="keywordSearch"
                                                class="form-label text-muted small fw-semibold mb-1">
                                                Keyword Search
                                            </label>
                                            <div class="input-group input-group-sm">
                                                <input type="text" class="form-control border-end-0"
                                                    id="keywordSearch" name="keyword" placeholder="Search employees..."
                                                    aria-label="Keyword Search" value="{{ request('keyword') }}">
                                                <span class="input-group-text bg-white border-start-0">
                                                    <i class="mdi mdi-magnify text-muted"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Second Row: Employee Name, Employee ID, and System ID --}}
                                    <div class="row mb-2">
                                        <div class="col-md-4">
                                            <label for="employeeName"
                                                class="form-label text-muted small fw-semibold mb-1">
                                                Employee Name
                                            </label>
                                            <select id="employeeName" name="employee_name"
                                                class="form-select form-select-sm select2_list"
                                                data-placeholder="Select employee name">
                                                <option value="">Choose One</option>
                                                @foreach ($employees as $employee)
                                                    <option value="{{ $employee->full_name }}"
                                                        {{ request('employee_name') == $employee->full_name ? 'selected' : '' }}>
                                                        {{ $employee->full_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <label for="employeeId"
                                                class="form-label text-muted small fw-semibold mb-1">
                                                Employee ID
                                            </label>
                                            <select id="employeeId" name="employee_id"
                                                class="form-select form-select-sm select2_list"
                                                data-placeholder="Select employee ID">
                                                <option value="">Choose One</option>
                                                @foreach ($employees as $employee)
                                                    <option value="{{ $employee->applicant_id }}"
                                                        {{ request('employee_id') == $employee->applicant_id ? 'selected' : '' }}>
                                                        {{ $employee->applicant_id }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <label for="systemId" class="form-label text-muted small fw-semibold mb-1">
                                                System ID
                                            </label>
                                            <select id="systemId" name="system_id"
                                                class="form-select form-select-sm select2_list"
                                                data-placeholder="Select system ID">
                                                <option value="">Choose One</option>
                                                @foreach ($employees as $employee)
                                                    <option value="{{ $employee->system_id }}"
                                                        {{ request('system_id') == $employee->system_id ? 'selected' : '' }}>
                                                        {{ $employee->system_id }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    {{-- Reset Button --}}
                                    <div class="row">
                                        <div class="col-12 text-end">
                                            <button type="button" id="resetFilters" class="btn btn-outline-secondary btn-sm">
                                                <i class="mdi mdi-refresh"></i> Reset
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="card-header bg-white border-bottom p-4">
                    <div id="search-result" class="card-body p-0">
                        @include('employees.partials.search_results')
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <script>
        $(document).ready(function () {
            // Function to perform AJAX search
            function fetchEmployees(url = "{{ route('employees.index') }}") {
                const queryString = $('#filterForm').serialize();

                $.ajax({
                    url: url,
                    method: "GET",
                    data: queryString,
                    beforeSend: function() {
                        $('#search-result').html('<div class="text-center py-4 text-muted">Loading...</div>');
                    },
                    success: function (response) {
                        $('#search-result').html(response);
                        // Reinitialize Feather icons if used in results
                        if (typeof feather !== 'undefined') {
                            feather.replace();
                        }
                        // Update URL without page param
                        const newUrl = '?' + queryString;
                        window.history.pushState(null, '', newUrl || location.pathname);
                    },
                    error: function (xhr) {
                        console.error('AJAX Error:', xhr.responseText);
                    }
                });
            }

            // Trigger search on input or change
            $('#filterForm').on('input change', function (e) {
                e.preventDefault();
                fetchEmployees();
            });

            // Reset filters: clear form and reload base URL
            $('#resetFilters').on('click', function () {
                // Clear all form fields
                $('#filterForm')[0].reset();

                // If using Select2, you may need to trigger change
                $('.select2_list').val(null).trigger('change');

                // Reload the page without query string
                window.location.href = "{{ route('employees.index') }}";
            });

            // Handle pagination via AJAX
            $(document).on('click', '#search-result .pagination a', function (e) {
                e.preventDefault();
                const url = $(this).attr('href');
                fetchEmployees(url);
            });
        });
    </script>
@endsection
