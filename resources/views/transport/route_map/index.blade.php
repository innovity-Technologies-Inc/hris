@extends('structure.master')
@section('content')
    {{-- Search Section --}}
    <div class="row">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm rounded">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i data-feather="map" class="me-2"></i>Search Route Maps
                    </h5>
                </div>
                <div class="card-header border-bottom p-4">
                    <div class="row align-items-start">
                        <div class="col-md-12">
                            <div class="border rounded shadow-sm p-3 filter-section-bg">
                                <form id="filterForm">
                                    <div class="row mb-2">
                                        {{-- Keyword Search --}}
                                        <div class="col-md-8">
                                            <label for="keywordSearch" class="form-label text-muted small fw-semibold mb-1">
                                                Keyword Search
                                            </label>
                                            <div class="input-group input-group-md">
                                                <input type="text" class="form-control border-end-0" id="keywordSearch"
                                                    name="keyword" placeholder="Search by route name, start point, end point..."
                                                    aria-label="Keyword Search" value="{{ request('keyword') }}">
                                                <span class="input-group-text border-start-0 input-group-bg">
                                                    <i class="mdi mdi-magnify text-muted"></i>
                                                </span>
                                            </div>
                                        </div>

                                        {{-- Reset Button --}}
                                        <div class="col-md-4 d-flex align-items-end">
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
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Route Maps</h5>
                    @if(auth()->user()->can('employee-transport.create'))
                        <a href="{{ route('transport.route_maps.create') }}" class="btn btn-primary btn-sm">
                            <i class="mdi mdi-plus me-1"></i> Add Route Map
                        </a>
                    @endif
                </div>
                <div class="card-body">
                    <div id="results-container">
                        @include('transport.route_map.search_results')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Live Search Handler
            let searchTimeout;
            $('#keywordSearch').on('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(function() {
                    fetchData();
                }, 500);
            });

            // Reset Filter Handler
            $('#resetFilters').click(function() {
                $('#filterForm')[0].reset();
                fetchData();
            });

            // Fetch Data AJAX
            function fetchData(url = "{{ route('transport.route_maps.index') }}") {
                const keyword = $('#keywordSearch').val();

                $.ajax({
                    url: url,
                    type: "GET",
                    data: {
                        keyword: keyword
                    },
                    beforeSend: function() {
                        $('#results-container').html(`
                            <div class="text-center py-5">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        `);
                    },
                    success: function(response) {
                        $('#results-container').html(response);
                        feather.replace();
                    },
                    error: function(xhr) {
                        Swal.fire('Error', 'Unable to fetch route maps.', 'error');
                    }
                });
            }

            // Pagination Link Click Event
            $(document).on('click', '.pagination a', function(e) {
                e.preventDefault();
                const url = $(this).attr('href');
                fetchData(url);
            });

            // Global delete function
            window.deleteRouteMap = function(id) {
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
                        axios.delete(`/transport/route-maps/${id}`)
                            .then(response => {
                                if (response.data.success) {
                                    Swal.fire('Deleted!', response.data.message, 'success');
                                    fetchData();
                                } else {
                                    Swal.fire('Failed!', response.data.message, 'error');
                                }
                            })
                            .catch(error => {
                                const msg = error.response && error.response.data && error.response.data.message
                                    ? error.response.data.message
                                    : 'Something went wrong';
                                Swal.fire('Error', msg, 'error');
                            });
                    }
                });
            };
        });
    </script>
@endsection
