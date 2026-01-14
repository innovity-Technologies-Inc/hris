@extends('structure.master')

@section('content')
    {{--
    ================================================
    DUMMY DATA FOR TESTING (Controller Integration)
    ================================================
    Use this object-style dummy data in your controller:

    $employees = collect([
        (object)['id' => 1, 'full_name' => 'Ahmed Rahman', 'applicant_id' => 'EMP-2024-001'],
        (object)['id' => 2, 'full_name' => 'Fatima Khatun', 'applicant_id' => 'EMP-2024-002'],
        (object)['id' => 3, 'full_name' => 'Mohammad Karim', 'applicant_id' => 'EMP-2024-003'],
    ]);

    $designations = collect([
        (object)['id' => 1, 'company_designation' => 'Junior Software Engineer'],
        (object)['id' => 2, 'company_designation' => 'Software Engineer'],
        (object)['id' => 3, 'company_designation' => 'Senior Software Engineer'],
    ]);

    $promotions = new \Illuminate\Pagination\LengthAwarePaginator(
        collect([
            (object)[
                'id' => 1,
                'employee_id' => 1,
                'previous_designation' => 2,
                'new_designation' => 3,
                'new_basic_salary' => '50000.00',
                'effective_from' => now()->subMonths(2),
                'effective_to' => null,
                'status' => 'approved',
                'getEmployee' => (object)['full_name' => 'Ahmed Rahman', 'applicant_id' => 'EMP-2024-001'],
                'getPreviousDesignation' => (object)['company_designation' => 'Software Engineer'],
                'getNewDesignation' => (object)['company_designation' => 'Senior Software Engineer'],
                'getStatusBadgeClass' => fn() => 'bg-success',
            ],
        ]),
        1, 15, 1
    );
    --}}

    {{-- Employee Promotion Search --}}
    <div class="row">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm rounded">
                <div class="card-header">
                    <h5 class="card-title mb-0">Search Employee Promotions</h5>
                </div><!-- end card header -->
                <div class="card-header border-bottom p-4">
                    <div class="row align-items-start">

                        {{-- Filter Section --}}
                        <div class="col-md-12">
                            <div class="border rounded shadow-sm p-3 filter-section-bg">
                                <form id="filterForm">
                                    {{-- First Row: Keyword Search --}}
                                    <div class="row mb-2">
                                        <div class="col-12">
                                            <label for="keywordSearch" class="form-label text-muted small fw-semibold mb-1">
                                                Keyword Search
                                            </label>
                                            <div class="input-group input-group-md">
                                                <input type="text" class="form-control border-end-0" id="keywordSearch"
                                                    name="keyword" placeholder="Search by employee name or designation"
                                                    aria-label="Keyword Search" value="{{ request('keyword') }}">
                                                <span class="input-group-text border-start-0 input-group-bg">
                                                    <i class="mdi mdi-magnify text-muted"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Second Row: Employee, Designation, Status --}}
                                    <div class="row mb-2">
                                        <div class="col-md-4">
                                            <label for="employeeName" class="form-label text-muted small fw-semibold mb-1">
                                                Employee Name
                                            </label>
                                            <select id="employeeName" name="employee_id"
                                                class="form-select form-select-sm select2_list"
                                                data-placeholder="Select employee">
                                                <option value="">All Employees</option>
                                                @foreach ($employees as $employee)
                                                    <option value="{{ $employee->id }}"
                                                        {{ request('employee_id') == $employee->id ? 'selected' : '' }}>
                                                        {{ $employee->full_name }} ({{ $employee->applicant_id }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <label for="designationFilter"
                                                class="form-label text-muted small fw-semibold mb-1">
                                                New Designation
                                            </label>
                                            <select id="designationFilter" name="new_designation"
                                                class="form-select form-select-sm select2_list"
                                                data-placeholder="Select designation">
                                                <option value="">All Designations</option>
                                                @foreach ($designations as $designation)
                                                    <option value="{{ $designation->id }}"
                                                        {{ request('new_designation') == $designation->id ? 'selected' : '' }}>
                                                        {{ $designation->company_designation }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <label for="statusFilter" class="form-label text-muted small fw-semibold mb-1">
                                                Status
                                            </label>
                                            <select class="form-select form-select-sm" id="statusFilter" name="status">
                                                <option value="">All Status</option>
                                                <option value="pending"
                                                    {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="approved"
                                                    {{ request('status') == 'approved' ? 'selected' : '' }}>Approved
                                                </option>
                                                <option value="rejected"
                                                    {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected
                                                </option>
                                            </select>
                                        </div>
                                    </div>

                                    {{-- Third Row: Date Range --}}
                                    <div class="row mb-2">
                                        <div class="col-md-6">
                                            <label for="effectiveFrom" class="form-label text-muted small fw-semibold mb-1">
                                                Effective From (Start)
                                            </label>
                                            <input type="date" class="form-control form-control-sm" id="effectiveFrom"
                                                name="effective_from_start" value="{{ request('effective_from_start') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="effectiveTo" class="form-label text-muted small fw-semibold mb-1">
                                                Effective From (End)
                                            </label>
                                            <input type="date" class="form-control form-control-sm" id="effectiveTo"
                                                name="effective_from_end" value="{{ request('effective_from_end') }}">
                                        </div>
                                    </div>

                                    {{-- Reset Button --}}
                                    <div class="row">
                                        <div class="col-12 text-end">
                                            <button type="button" id="resetFilters"
                                                class="btn btn-outline-secondary btn-sm">
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
        </div>


        <div class="col-lg-12 mt-3">
            <div class="card border-0 shadow-sm rounded">
                <div class="card-header">
                    <h5 class="card-title mb-0">Employee Promotions List</h5>
                </div>
                <div class="card-body">
                    <div id="search-result">
                        @include('payroll.promotion.partials.search-results')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        $(document).ready(function() {
            // Function to perform AJAX search
            function fetchData(url = "{{ route('promotion.index') }}") {
                const queryString = $('#filterForm').serialize();

                $.ajax({
                    url: url,
                    method: "GET",
                    data: queryString,
                    beforeSend: function() {
                        $('#search-result').html(
                            '<div class="text-center py-4 text-muted">Loading Data...</div>');
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

            // Trigger search on input or change
            $('#filterForm').on('input change', function(e) {
                e.preventDefault();
                fetchData();
            });

            // Reset filters: clear form and reload base URL
            $('#resetFilters').on('click', function() {
                $('#filterForm')[0].reset();
                $('.select2_list').val(null).trigger('change');
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
        });
    </script>
@endsection
