@extends('structure.master')

@section('content')
    <div class="row">
        {{-- Search & Filter Section --}}
        <div class="col-lg-12">
            @can('resignations.view')
            <div class="card border-0 shadow-lg rounded-4 my-4">
                <div class="card-header border-bottom rounded-top-4 p-4">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3 d-inline-flex align-items-center justify-content-center">
                            <i class="bi bi-search text-primary fs-5"></i>
                        </div>
                        <h5 class="card-title mb-0 text-dark fw-bold">Search Resignations</h5>
                    </div>
                </div>
                <div class="card-body p-4">
                    <form id="filterForm">
                        <div class="row">
                            <div class="col-md-10">
                                <label for="keywordSearch" class="form-label text-muted small fw-semibold mb-1">
                                    Keyword Search
                                </label>
                                <div class="input-group input-group-md">
                                    <input type="text" class="form-control border-end-0" id="keywordSearch"
                                        name="keyword" placeholder="Search by employee name, reason, status, remarks..."
                                        value="{{ request('keyword') }}">
                                    <span class="input-group-text border-start-0 input-group-bg">
                                        <i class="mdi mdi-magnify text-muted"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-2 d-flex align-items-end mt-2 mt-md-0">
                                <button type="button" id="resetFilters" class="btn btn-outline-secondary w-100">
                                    <i class="mdi mdi-refresh"></i> Reset
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            @endcan
        </div>

        {{-- Resignations List --}}
        <div class="col-lg-12">
            <div class="card border-0 shadow-lg rounded-4 mb-5">
                <div class="card-header border-bottom rounded-top-4 p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="bg-danger bg-opacity-10 rounded-circle p-2 me-3 d-inline-flex align-items-center justify-content-center">
                                <i class="mdi mdi-door-open text-danger fs-4"></i>
                            </div>
                            <h5 class="card-title mb-0 text-dark fw-bold">Employee Resignations List</h5>
                        </div>
                        <div>
                            @can('resignations.create')
                            <a class="btn btn-primary btn-md rounded-3 shadow-sm px-4" href="{{ route('resignation.create') }}">
                                <i class="mdi mdi-plus-circle me-1"></i> Submit Resignation
                            </a>
                            @endcan
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="table-responsive" id="search-result">
                        @include('resignation.search_results')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        $(document).ready(function() {
            function fetchData(url = "{{ route('resignation.index') }}") {
                const queryString = $('#filterForm').serialize();

                $.ajax({
                    url: url,
                    method: "GET",
                    data: queryString,
                    beforeSend: function() {
                        $('#search-result').html('<div class="text-center py-5 text-muted"><i class="mdi mdi-spin mdi-loading fs-2 d-block mb-2"></i> Loading data...</div>');
                    },
                    success: function(response) {
                        $('#search-result').html(response);
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
                window.location.href = "{{ route('resignation.index') }}";
            });

            $(document).on('click', '#search-result .pagination a', function(e) {
                e.preventDefault();
                const url = $(this).attr('href');
                fetchData(url);
            });

            // Handle Axios delete confirmation
            $(document).on('click', '.confirmDelete', function(e) {
                e.preventDefault();
                const btn = $(this);
                const form = btn.closest('form');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "This resignation record will be deleted!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        axios.delete(form.attr('action'))
                            .then(response => {
                                const res = response.data;
                                if (res.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Deleted!',
                                        text: res.message,
                                        timer: 1500,
                                        showConfirmButton: false
                                    }).then(() => {
                                        fetchData();
                                    });
                                }
                            })
                            .catch(error => {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: error.response?.data?.message || 'Failed to delete resignation.'
                                });
                            });
                    }
                });
            });
        });
    </script>
@endsection
