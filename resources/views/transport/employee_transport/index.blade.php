@extends('structure.master')
@section('content')
    {{-- Search Section --}}
    <div class="row">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm rounded">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i data-feather="users" class="me-2"></i>Search Employee Transport Applications
                    </h5>
                </div>
                <div class="card-header border-bottom p-4">
                    <div class="row align-items-start">
                        {{-- Filter Section --}}
                        <div class="col-md-12">
                            <div class="border rounded shadow-sm p-3 filter-section-bg">
                                <form id="filterForm">
                                    <div class="row mb-2">
                                        {{-- Keyword Search --}}
                                        <div class="col-md-4">
                                            <label for="keywordSearch" class="form-label text-muted small fw-semibold mb-1">
                                                Keyword Search
                                            </label>
                                            <div class="input-group input-group-md">
                                                <input type="text" class="form-control border-end-0" id="keywordSearch"
                                                    name="keyword" placeholder="Search by purpose, location..."
                                                    aria-label="Keyword Search" value="{{ request('keyword') }}">
                                                <span class="input-group-text border-start-0 input-group-bg">
                                                    <i class="mdi mdi-magnify text-muted"></i>
                                                </span>
                                            </div>
                                        </div>

                                        {{-- Status Filter --}}
                                        <div class="col-md-3">
                                            <label for="statusFilter" class="form-label text-muted small fw-semibold mb-1">
                                                Status
                                            </label>
                                            <select class="form-select" id="statusFilter" name="status">
                                                <option value="">All Status</option>
                                                <option value="Pending"
                                                    {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="Approved"
                                                    {{ request('status') == 'Approved' ? 'selected' : '' }}>Approved
                                                </option>
                                                <option value="Rejected"
                                                    {{ request('status') == 'Rejected' ? 'selected' : '' }}>Rejected
                                                </option>
                                                <option value="Cancelled"
                                                    {{ request('status') == 'Cancelled' ? 'selected' : '' }}>Cancelled
                                                </option>
                                            </select>
                                        </div>

                                        {{-- Transport Type Filter --}}
                                        <div class="col-md-3">
                                            <label for="typeFilter" class="form-label text-muted small fw-semibold mb-1">
                                                Transport Type
                                            </label>
                                            <select class="form-select" id="typeFilter" name="transport_type">
                                                <option value="">All Types</option>
                                                <option value="Daily Commute"
                                                    {{ request('transport_type') == 'Daily Commute' ? 'selected' : '' }}>
                                                    Daily Commute</option>
                                                <option value="Shuttle Service"
                                                    {{ request('transport_type') == 'Shuttle Service' ? 'selected' : '' }}>
                                                    Shuttle Service</option>
                                                <option value="Special Transport"
                                                    {{ request('transport_type') == 'Special Transport' ? 'selected' : '' }}>
                                                    Special Transport</option>
                                                <option value="Field Work"
                                                    {{ request('transport_type') == 'Field Work' ? 'selected' : '' }}>Field
                                                    Work</option>
                                            </select>
                                        </div>

                                        {{-- Reset Button --}}
                                        <div class="col-md-2 d-flex align-items-end">
                                            <button type="button" id="resetFilters"
                                                class="btn btn-outline-secondary btn-sm w-100">
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

        {{-- List Section --}}
        <div class="col-lg-12 mt-3">
            <div class="card border-0 shadow-sm rounded">
                <div class="card-header">
                    <h5 class="card-title mb-0">Employee Transport Applications</h5>
                </div>
                <div class="card-body">
                    {{-- Action Buttons --}}
                    <div class="d-flex justify-content-between mb-3">
                        <a type="button" class="btn btn-warning btn-sm"
                            href="{{ route('transport.employee_transports.create') }}">
                            <i style="height: 12px; width: 12px" data-feather="plus"></i> New Application
                        </a>
                    </div>

                    @if ($employeeTransports->isEmpty())
                        <div class="text-center py-4 text-muted">
                            <i data-feather="inbox" style="width: 48px; height: 48px;"></i>
                            <p class="mt-2 mb-0">No employee transport applications found</p>
                            <a href="{{ route('transport.employee_transports.create') }}"
                                class="btn btn-sm btn-primary mt-2">
                                <i data-feather="plus" style="width: 14px; height: 14px;"></i> Create First Application
                            </a>
                        </div>
                    @else
                        <div class="table-responsive" id="search-result">
                            @include('transport.employee_transport.search_results')
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        $(document).ready(function() {
            function fetchData(url = "{{ route('transport.employee_transports.index') }}") {
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

            $('#filterForm').on('input change', function(e) {
                e.preventDefault();
                fetchData();
            });

            $('#resetFilters').on('click', function() {
                $('#filterForm')[0].reset();
                fetchData();
            });

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
