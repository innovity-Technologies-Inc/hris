@extends('structure.master')
@section('content')
    {{-- Search Section --}}
    <div class="row">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm rounded">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i data-feather="dollar-sign" class="me-2"></i>Payroll Disbursement
                    </h5>
                </div>
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
                                                       name="keyword" placeholder="Search by batch id"
                                                       aria-label="Keyword Search" value="{{ request('keyword') }}">
                                                <span class="input-group-text border-start-0 input-group-bg">
                                                    <i class="mdi mdi-magnify text-muted"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Second Row --}}
                                    <div class="row mb-2">
                                        <div class="col-md-4">
                                            <label for="salaryMonth" class="form-label text-muted small fw-semibold mb-1">
                                                Salary/Bonus Month
                                            </label>
                                            <input type="month" class="form-control form-control-sm" id="salaryMonth"
                                                   name="salary_month" value="{{ request('salary_month') }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label for="typeFilter" class="form-label text-muted small fw-semibold mb-1">
                                                Process Type
                                            </label>
                                            <select class="form-select form-select-sm" id="typeFilter" name="type">
                                                <option value="">All Types</option>
                                                <option value="salary" {{ request('type') == 'salary' ? 'selected' : '' }}>Salary</option>
                                                <option value="bonus" {{ request('type') == 'bonus' ? 'selected' : '' }}>Bonus</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4 text-end mt-4">
                                            <button type="button" id="resetFilters" class="btn btn-outline-secondary btn-sm w-100">
                                                <i class="mdi mdi-refresh"></i> Reset Filters
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
                    <h5 class="card-title mb-0">Pending Disbursements List</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive" id="search-result">
                        @include('payroll.disbursement.partials.search_results')
                    </div>

                    <div class="mt-2">
                        {{ $data->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .filter-section-bg { background-color: #f8f9fa; }
        .input-group-bg { background-color: #fff; }
    </style>

    @push('scripts')
    <script>
        $(document).ready(function() {
            function fetchData(url = "{{ route('disbursement.index') }}") {
                const queryString = $('#filterForm').serialize();
                const fetchUrl = url + (url.includes('?') ? '&' : '?') + queryString;

                axios.get(fetchUrl, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                })
                .then(function (response) {
                    $('#search-result').html(response.data);
                    if (typeof feather !== 'undefined') { feather.replace(); }
                    const newUrl = '?' + queryString;
                    window.history.pushState(null, '', newUrl || location.pathname);
                })
                .catch(function (error) {
                    console.error('Error fetching data:', error);
                    $('#search-result').html('<div class="text-center py-4 text-danger">Error loading data. Please try again.</div>');
                });
            }

            $('#filterForm').on('input change', function(e) { e.preventDefault(); fetchData(); });
            $('#resetFilters').on('click', function() {
                $('#filterForm')[0].reset();
                fetchData();
            });
            $(document).on('click', '.pagination a', function(e) {
                e.preventDefault();
                const url = $(this).attr('href');
                if (url) { fetchData(url); }
            });
        });
    </script>
    @endpush
@endsection
