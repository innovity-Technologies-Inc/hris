@extends('structure.master')

@section('content')

    {{-- Employee Travel Movement List --}}
    <div class="row">
        <div class="col-lg-12">
            @can('movement.view')
            <div class="card border-0 shadow-lg rounded-4 my-4">
                <div class="card-header border-bottom rounded-top-4 p-4">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3 d-inline-flex align-items-center justify-content-center">
                            <i class="bi bi-search text-primary fs-5"></i>
                        </div>
                        <h5 class="card-title mb-0 text-dark fw-bold">Search Employee Travel Movement</h5>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row align-items-start">
                        {{-- Filter Section --}}
                        <div class="col-md-12">
                            <div class="border-0 rounded-3 p-0">
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
                                                    placeholder="Search by employee name"
                                                    aria-label="Keyword Search" value="{{ request('keyword') }}">
                                                <span class="input-group-text border-start-0 input-group-bg">
                                                    <i class="mdi mdi-magnify text-muted"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Second Row: Date Range, Status & Payment --}}
                                    <div class="row mb-2">
                                        <div class="col-md-3">
                                            <label for="fromDate" class="form-label text-muted small fw-semibold mb-1">
                                                From Date
                                            </label>
                                            <input type="date" class="form-control" id="fromDate" name="from"
                                                value="{{ request('from') }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="toDate" class="form-label text-muted small fw-semibold mb-1">
                                                To Date
                                            </label>
                                            <input type="date" class="form-control" id="toDate" name="to"
                                                value="{{ request('to') }}">
                                        </div>
                                        @if(auth()->user()->user_type !== \App\Enums\UserType::Employee)
                                        <div class="col-md-3">
                                            <label for="statusFilter" class="form-label text-muted small fw-semibold mb-1">
                                                Status
                                            </label>
                                            <select class="form-select" id="statusFilter" name="status">
                                                <option value="">All Status</option>
                                                <option value="pending">Pending</option>
                                                <option value="approved">Approved</option>
                                                <option value="rejected">Rejected</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="paymentStatusFilter" class="form-label text-muted small fw-semibold mb-1">
                                                Payment Status
                                            </label>
                                            <select class="form-select" id="paymentStatusFilter" name="payment_status">
                                                <option value="">All Status</option>
                                                <option value="paid">Paid</option>
                                                <option value="unpaid">Unpaid</option>
                                            </select>
                                        </div>
                                        @endif
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
            @endcan
        </div>


        <div class="col-lg-12">
            <div class="card border-0 shadow-lg rounded-4 mb-5">
                <div class="card-header border-bottom rounded-top-4 p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="bg-info bg-opacity-10 rounded-circle p-2 me-3 d-inline-flex align-items-center justify-content-center">
                                <i class="bi bi-truck text-info fs-5"></i>
                            </div>
                            <h5 class="card-title mb-0 text-dark fw-bold">Employee Travel Movement Records</h5>
                        </div>
                        <div>
                            @can('movement.create')
                            <a class="btn btn-dark btn-sm rounded-3 shadow px-3" href="{{ route('movement.create') }}">
                                <i class="bi bi-plus-circle me-1"></i> Create
                            </a>
                            @endcan
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    @if ($movements->isEmpty())
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-3 opacity-25"></i>
                            No movement records found.
                        </div>
                    @else
                        <div class="table-responsive" id="search-result">
                            @include('movement.partials.search_results')
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
            function fetchData(url = "{{ route('movement.index') }}") {
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
                window.location.href = "{{ route('movement.index') }}";
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
    @include('movement.partials.import_modal')
@endsection

