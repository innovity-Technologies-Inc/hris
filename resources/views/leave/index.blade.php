@extends('structure.master')

@section('content')

    {{-- Leave Applications List --}}
    <div class="row">
        <div class="col-lg-12">
            @can('leave.view')
            <div class="card border-0 shadow-sm rounded">
                <div class="card-header">
                    <h5 class="card-title mb-0">Search Leave Applications</h5>
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
                                                    name="keyword" placeholder="Search by employee name, leave plan, plan type and days"
                                                    aria-label="Keyword Search" value="{{ request('keyword') }}">
                                                <span class="input-group-text border-start-0 input-group-bg">
                                                    <i class="mdi mdi-magnify text-muted"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Second Row: Date Range --}}
                                    <div class="row mb-2">
                                        <div class="col-md-6">
                                            <label for="fromDate" class="form-label text-muted small fw-semibold mb-1">
                                                From Date
                                            </label>
                                            <input type="date" class="form-control" id="fromDate" name="from"
                                                value="{{ request('from') }}">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="toDate" class="form-label text-muted small fw-semibold mb-1">
                                                To Date
                                            </label>
                                            <input type="date" class="form-control" id="toDate" name="to"
                                                value="{{ request('to') }}">
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
                    <h5 class="card-title mb-0">Leave Applications List</h5>
                </div>
                <div class="card-body">
                    {{-- Action Buttons --}}
                    <div class="d-flex justify-content-between mb-3">
                        @can('leave.create')
                        <a type="button" class="btn btn-warning btn-sm" href="{{ route('leave.create') }}">
                            <i style="height: 12px; width: 12px" data-feather="plus"></i> Create
                        </a>
                        @endcan
                        
                        @can('leave.import')
                        <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal"
                            data-bs-target="#bulkUploadModal">
                            <i style="height: 12px; width: 12px" data-feather="upload"></i> Upload Bulk
                        </button>
                        @endcan
                    </div>

                    @if ($leaves->isEmpty())
                        <div class="text-center py-4 text-muted">No leave applications found.</div>
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
            // Function to perform AJAX search
            function fetchData(url = "{{ route('leave.index') }}") {
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
                window.location.href = "{{ route('leave.index') }}";
            });

            // Handle pagination via AJAX
            $(document).on('click', '#search-result .pagination a', function(e) {
                e.preventDefault();
                const url = $(this).attr('href');
                fetchData(url);
            });
        });
    </script>
@endsection

