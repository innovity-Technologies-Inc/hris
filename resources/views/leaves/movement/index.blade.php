@extends('structure.master')

@section('content')
    @php
        // ========== SAMPLE EMPLOYEE MOVEMENT DATA ==========
        $movements = collect([
            (object) [
                'id' => 1,
                'employee_id' => 1,
                'employee_name' => 'Mohammad Rahman',
                'employee_code' => 'EMP-2024-001',
                'designation' => 'Senior Software Engineer',
                'from_date' => '2024-12-10 09:00:00',
                'to_date' => '2024-12-12 18:00:00',
                'source_address' => 'Dhaka Office, Gulshan-1',
                'destination_address' => 'Chittagong Regional Office',
                'covered_distance' => 264.5,
                'ta_plan' => 'Executive TA Plan',
                'ta_rate' => 15.0,
                'ta_amount' => 3967.5,
                'da_plan' => 'Executive DA Plan',
                'da_rate' => 1000.0,
                'da_amount' => 3000.0,
                'total_days' => 3,
                'total_allowance' => 6967.5,
                'reason' => 'Client meeting and project deployment supervision at Chittagong branch',
                'status' => 'approved',
                'created_at' => '2024-12-08 10:30:00',
            ],
            (object) [
                'id' => 2,
                'employee_id' => 3,
                'employee_name' => 'Karim Hassan',
                'employee_code' => 'EMP-2024-003',
                'designation' => 'Financial Analyst',
                'from_date' => '2024-12-15 08:00:00',
                'to_date' => '2024-12-15 20:00:00',
                'source_address' => 'Head Office, Motijheel',
                'destination_address' => 'Bank Asia, Karwan Bazar',
                'covered_distance' => 12.0,
                'ta_plan' => 'Basic TA Plan',
                'ta_rate' => 10.0,
                'ta_amount' => 120.0,
                'da_plan' => 'Standard DA Plan',
                'da_rate' => 500.0,
                'da_amount' => 500.0,
                'total_days' => 1,
                'total_allowance' => 620.0,
                'reason' => 'Loan documentation and financial audit meeting',
                'status' => 'pending',
                'created_at' => '2024-12-14 11:20:00',
            ],
            (object) [
                'id' => 3,
                'employee_id' => 5,
                'employee_name' => 'Abdullah Islam',
                'employee_code' => 'EMP-2024-005',
                'designation' => 'Sales Manager',
                'from_date' => '2024-12-05 07:00:00',
                'to_date' => '2024-12-09 22:00:00',
                'source_address' => 'Dhaka Sales Center',
                'destination_address' => 'Sylhet Regional Sales Office',
                'covered_distance' => 242.0,
                'ta_plan' => 'Field Staff TA Plan',
                'ta_rate' => 12.5,
                'ta_amount' => 3025.0,
                'da_plan' => 'Field Staff DA Plan',
                'da_rate' => 750.0,
                'da_amount' => 3750.0,
                'total_days' => 5,
                'total_allowance' => 6775.0,
                'reason' => 'Regional sales team training and quarterly review meeting',
                'status' => 'completed',
                'created_at' => '2024-12-02 09:15:00',
            ],
            (object) [
                'id' => 4,
                'employee_id' => 2,
                'employee_name' => 'Fatima Ahmed',
                'employee_code' => 'EMP-2024-002',
                'designation' => 'HR Manager',
                'from_date' => '2024-12-18 10:00:00',
                'to_date' => '2024-12-19 17:00:00',
                'source_address' => 'Corporate Office, Banani',
                'destination_address' => 'Brac University, Mohakhali',
                'covered_distance' => 8.5,
                'ta_plan' => 'Executive TA Plan',
                'ta_rate' => 15.0,
                'ta_amount' => 127.5,
                'da_plan' => 'Executive DA Plan',
                'da_rate' => 1000.0,
                'da_amount' => 2000.0,
                'total_days' => 2,
                'total_allowance' => 2127.5,
                'reason' => 'Campus recruitment drive and HR seminar attendance',
                'status' => 'pending',
                'created_at' => '2024-12-16 14:45:00',
            ],
            (object) [
                'id' => 5,
                'employee_id' => 6,
                'employee_name' => 'Nadia Sultana',
                'employee_code' => 'EMP-2024-006',
                'designation' => 'Project Manager',
                'from_date' => '2024-12-01 06:00:00',
                'to_date' => '2024-12-04 23:00:00',
                'source_address' => 'IT Tower, Kawran Bazar',
                'destination_address' => 'Khulna Tech Park',
                'covered_distance' => 334.0,
                'ta_plan' => 'Premium TA Plan',
                'ta_rate' => 20.0,
                'ta_amount' => 6680.0,
                'da_plan' => 'Premium DA Plan',
                'da_rate' => 1500.0,
                'da_amount' => 6000.0,
                'total_days' => 4,
                'total_allowance' => 12680.0,
                'reason' => 'Software implementation and team coordination at new branch',
                'status' => 'approved',
                'created_at' => '2024-11-28 08:00:00',
            ],
            (object) [
                'id' => 6,
                'employee_id' => 4,
                'employee_name' => 'Ayesha Khan',
                'employee_code' => 'EMP-2024-004',
                'designation' => 'Marketing Executive',
                'from_date' => '2024-12-20 09:30:00',
                'to_date' => '2024-12-20 19:30:00',
                'source_address' => 'Marketing HQ, Dhanmondi',
                'destination_address' => 'Bashundhara City Convention Hall',
                'covered_distance' => 15.0,
                'ta_plan' => 'Basic TA Plan',
                'ta_rate' => 10.0,
                'ta_amount' => 150.0,
                'da_plan' => 'Standard DA Plan',
                'da_rate' => 500.0,
                'da_amount' => 500.0,
                'total_days' => 1,
                'total_allowance' => 650.0,
                'reason' => 'Product launch event and marketing campaign presentation',
                'status' => 'rejected',
                'created_at' => '2024-12-18 16:30:00',
            ],
            (object) [
                'id' => 7,
                'employee_id' => 7,
                'employee_name' => 'Mahmudul Hasan',
                'employee_code' => 'EMP-2024-007',
                'designation' => 'Business Analyst',
                'from_date' => '2024-12-22 08:00:00',
                'to_date' => '2024-12-25 20:00:00',
                'source_address' => 'Business Center, Gulshan',
                'destination_address' => 'Rajshahi Business Park',
                'covered_distance' => 256.0,
                'ta_plan' => 'Executive TA Plan',
                'ta_rate' => 15.0,
                'ta_amount' => 3840.0,
                'da_plan' => 'Executive DA Plan',
                'da_rate' => 1000.0,
                'da_amount' => 4000.0,
                'total_days' => 4,
                'total_allowance' => 7840.0,
                'reason' => 'Business process analysis and system requirement gathering',
                'status' => 'pending',
                'created_at' => '2024-12-19 10:00:00',
            ],
            (object) [
                'id' => 8,
                'employee_id' => 8,
                'employee_name' => 'Rukhsana Begum',
                'employee_code' => 'EMP-2024-008',
                'designation' => 'Quality Assurance',
                'from_date' => '2024-11-25 07:30:00',
                'to_date' => '2024-11-27 21:00:00',
                'source_address' => 'QA Lab, Mirpur',
                'destination_address' => 'Gazipur Manufacturing Unit',
                'covered_distance' => 45.0,
                'ta_plan' => 'Field Staff TA Plan',
                'ta_rate' => 12.5,
                'ta_amount' => 562.5,
                'da_plan' => 'Field Staff DA Plan',
                'da_rate' => 750.0,
                'da_amount' => 2250.0,
                'total_days' => 3,
                'total_allowance' => 2812.5,
                'reason' => 'Quality inspection and production process audit',
                'status' => 'completed',
                'created_at' => '2024-11-22 13:45:00',
            ],
        ]);
    @endphp

    {{-- Employee Movement List --}}
    <div class="row">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm rounded">
                <div class="card-header">
                    <h5 class="card-title mb-0">Search Employee Movement</h5>
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
                                                    name="keyword"
                                                    placeholder="Search by employee name, code, designation, source, destination"
                                                    aria-label="Keyword Search" value="{{ request('keyword') }}">
                                                <span class="input-group-text border-start-0 input-group-bg">
                                                    <i class="mdi mdi-magnify text-muted"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Second Row: Date Range & Status --}}
                                    <div class="row mb-2">
                                        <div class="col-md-4">
                                            <label for="fromDate" class="form-label text-muted small fw-semibold mb-1">
                                                From Date
                                            </label>
                                            <input type="date" class="form-control" id="fromDate" name="from"
                                                value="{{ request('from') }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="toDate" class="form-label text-muted small fw-semibold mb-1">
                                                To Date
                                            </label>
                                            <input type="date" class="form-control" id="toDate" name="to"
                                                value="{{ request('to') }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="statusFilter" class="form-label text-muted small fw-semibold mb-1">
                                                Status
                                            </label>
                                            <select class="form-select" id="statusFilter" name="status">
                                                <option value="">All Status</option>
                                                <option value="pending">Pending</option>
                                                <option value="approved">Approved</option>
                                                <option value="rejected">Rejected</option>
                                                <option value="completed">Completed</option>
                                            </select>
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
                    <h5 class="card-title mb-0">Employee Movement Records</h5>
                </div>
                <div class="card-body">
                    {{-- Action Buttons --}}
                    <div class="d-flex justify-content-between mb-3">
                        <a type="button" class="btn btn-warning btn-sm" href="{{ route('leaves.movement.create') }}">
                            <i style="height: 12px; width: 12px" data-feather="plus"></i> Create
                        </a>
                        {{-- <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal"
                            data-bs-target="#bulkUploadModal">
                            <i style="height: 12px; width: 12px" data-feather="upload"></i> Upload Bulk
                        </button> --}}
                    </div>

                    @if ($movements->isEmpty())
                        <div class="text-center py-4 text-muted">No movement records found.</div>
                    @else
                        <div class="table-responsive" id="search-result">
                            @include('leaves.movement.partials.search_results', [
                                'movements' => $movements,
                            ])
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        $(document).ready(function() {
            // Function to perform AJAX search
            function fetchData(url = "{{ route('leaves.movement.index') }}") {
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
                // Clear all form fields
                $('#filterForm')[0].reset();

                // If using Select2, you may need to trigger change
                $('.select2_list').val(null).trigger('change');

                // Reload the page without query string
                window.location.href = "{{ route('leaves.movement.index') }}";
            });

            // Handle pagination via AJAX
            $(document).on('click', '#search-result .pagination a', function(e) {
                e.preventDefault();
                const url = $(this).attr('href');
                fetchData(url);
            });
        });
    </script>

    {{-- Include Import Modal --}}
    @include('leaves.movement.partials.import_modal')
@endsection
