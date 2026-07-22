@extends('structure.master')

@section('content')

    {{-- Employee Increment Search --}}
    <div class="row">
        <div class="col-lg-12">
            @can('increments.view')
            <div class="card border-0 shadow-sm rounded">
                <div class="card-header">
                    <h5 class="card-title mb-0">Search Employee Increments</h5>
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
                                                    name="keyword" placeholder="Search by employee name, applicant id, system id"
                                                    aria-label="Keyword Search" value="{{ request('keyword') }}">
                                                <span class="input-group-text border-start-0 input-group-bg">
                                                    <i class="mdi mdi-magnify text-muted"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Second Row: Employee, Increment Method, Status --}}
                                    <div class="row mb-2">

                                        <div class="col-md-4">
                                            <label for="effectiveFrom" class="form-label text-muted small fw-semibold mb-1">
                                                Effective From (Start)
                                            </label>
                                            <input type="date" class="form-control form-control-sm" id="effectiveFrom"
                                                   name="effective_from_start" value="{{ request('effective_from_start') }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="effectiveTo" class="form-label text-muted small fw-semibold mb-1">
                                                Effective From (End)
                                            </label>
                                            <input type="date" class="form-control form-control-sm" id="effectiveTo"
                                                   name="effective_from_end" value="{{ request('effective_from_end') }}">
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


        <div class="col-lg-12 mt-3">
            <div class="card border-0 shadow-sm rounded">
                <div class="card-header">
                    <h5 class="card-title mb-0">Employee Increments List</h5>
                </div>
                <div class="card-body">
                    {{-- Action Buttons --}}
                    <div class="d-flex justify-content-between mb-3">
                        <div class="d-flex gap-2">
                            @can('increments.create')
                            <a type="button" class="btn btn-warning btn-sm" href="{{ route('increment.create') }}">
                                <i style="height: 12px; width: 12px" data-feather="plus"></i> Create
                            </a>
                            @endcan

                            @can('increments.hr-approve')
                            <a type="button" class="btn btn-success btn-sm" href="{{ route('increment.adjustment') }}">
                                <i style="height: 12px; width: 12px" data-feather="check"></i> Increment Adjustment
                            </a>
                            @endcan
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" id="exportExcelBtn" class="btn btn-success btn-sm no-loader">
                                <i class="bi bi-file-earmark-excel me-1"></i> Excel
                            </button>
                            <button type="button" id="printBtn" class="btn btn-secondary btn-sm no-loader">
                                <i class="bi bi-printer me-1"></i> Print
                            </button>
                        </div>
                    </div>

                    <div id="search-result">
                        @include('payroll.increment.partials.search-results')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        $(document).ready(function() {
            // Function to perform AJAX search
            function fetchData(url = "{{ route('increment.index') }}") {
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

            // Excel Export
            $('#exportExcelBtn').on('click', function(e) {
                e.preventDefault();
                window.ignoreBeforeUnload = true;
                setTimeout(() => { window.ignoreBeforeUnload = false; }, 2000);
                
                const queryString = $('#filterForm').serialize();
                window.location.href = "{{ route('increment.export.excel') }}?" + queryString;
            });

            // Print
            $('#printBtn').on('click', function(e) {
                e.preventDefault();
                const queryString = $('#filterForm').serialize();
                window.open("{{ route('increment.print') }}?" + queryString, '_blank');
            });
        });
    </script>
@endsection

