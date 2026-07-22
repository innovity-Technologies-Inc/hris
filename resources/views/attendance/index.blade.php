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
            // Initialize Feather icons
            if (typeof feather !== 'undefined') {
                feather.replace();
            }

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

            // Trigger search on input or change
            $('#filterForm').on('input change', function(e) {
                e.preventDefault();
                fetchData();
            });

            // Reset filters: clear form and reload base URL
            $('#resetFilters').on('click', function() {
                $('#filterForm')[0].reset();
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
