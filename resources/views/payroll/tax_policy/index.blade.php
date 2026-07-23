@extends('structure.master')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm rounded">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i data-feather="settings" class="me-2 text-primary"></i>Tax Policy Configurations
                    </h5>
                    @can('tax-policy.create')
                        <a href="{{ route('tax-policy.create') }}" class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm">
                            <i data-feather="plus" class="me-1" style="width: 16px;"></i> Create Tax Policy
                        </a>
                    @endcan
                </div>
                
                {{-- Search Filter --}}
                <div class="card-header border-bottom p-4">
                    <div class="row align-items-start">
                        <div class="col-md-12">
                            <div class="border rounded shadow-sm p-3 filter-section-bg">
                                <form id="filterForm">
                                    <div class="row mb-2">
                                        <div class="col-12">
                                            <label for="keywordSearch" class="form-label text-muted small fw-semibold mb-1">
                                                Keyword Search
                                            </label>
                                            <div class="input-group input-group-md">
                                                <input type="text" class="form-control border-end-0" id="keywordSearch"
                                                       name="keyword" placeholder="Search by zero tax limits, minimum tax amount..."
                                                       aria-label="Keyword Search" value="{{ request('keyword') }}">
                                                <span class="input-group-text border-start-0 input-group-bg">
                                                    <i data-feather="search" class="text-muted" style="width: 16px;"></i>
                                                </span>
                                            </div>
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
                <div class="card-body">
                    <div class="table-responsive" id="search-result">
                        @include('payroll.tax_policy.partials.search_results')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .filter-section-bg { background-color: rgba(0,0,0,0.02); }
        [data-bs-theme=dark] .filter-section-bg { background-color: rgba(255,255,255,0.05); }
        .input-group-bg { background-color: transparent; }
    </style>

    @push('scripts')
    <script>
        $(document).ready(function() {
            // Live Search
            function fetchData(url = "{{ route('tax-policy.index') }}") {
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

            $('#filterForm').on('input change', function(e) { 
                e.preventDefault(); 
                fetchData(); 
            });

            // Pagination Link Clicks
            $(document).on('click', '.pagination a', function(e) {
                e.preventDefault();
                const url = $(this).attr('href');
                if (url) { fetchData(url); }
            });

            // Delete Action
            $(document).on('click', '.delete-policy', function(e) {
                e.preventDefault();
                const deleteUrl = $(this).data('url');
                const row = $(this).closest('tr');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        axios.delete(deleteUrl)
                            .then(response => {
                                if (response.data.success) {
                                    Swal.fire(
                                        'Deleted!',
                                        response.data.message,
                                        'success'
                                    );
                                    fetchData(); // Reload table
                                }
                            })
                            .catch(error => {
                                Swal.fire(
                                    'Failed!',
                                    error.response?.data?.message || 'Failed to delete tax policy.',
                                    'error'
                                );
                            });
                    }
                });
            });
        });
    </script>
    @endpush
@endsection
