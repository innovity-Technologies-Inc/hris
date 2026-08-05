@extends('structure.master')

@push('styles')
    <style>
        .bg-primary-theme {
            background-color: var(--primary-color, #974063) !important;
        }
        .text-primary-theme {
            color: var(--primary-color, #974063) !important;
        }
        .spinner-border.text-primary {
            color: var(--primary-color, #974063) !important;
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded">
                <div class="card-header">
                    <h5 class="card-title mb-0">Search Key People</h5>
                </div>
                <div class="card-header border-bottom p-4">
                    <div class="row align-items-start">
                        <div class="col-md-12">
                            <div class="border rounded shadow-sm p-3 filter-section-bg bg-light-subtle">
                                <form id="filterForm" onsubmit="return false;">
                                    <div class="row mb-2">
                                        <div class="col-md-8 col-12 mb-2 mb-md-0">
                                            <label for="keywordSearch" class="form-label text-muted small fw-semibold mb-1">Keyword Search</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control border-end-0" id="keywordSearch" name="keyword"
                                                    placeholder="Search by name, position, email, phone, or hierarchy level..." aria-label="Keyword Search">
                                                <span class="input-group-text border-start-0 bg-white">
                                                    <i class="fas fa-search text-muted"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-12 text-md-end align-self-end">
                                            <button type="button" class="btn btn-outline-secondary btn-md w-100 w-md-auto" id="resetFilters">
                                                <i class="fas fa-undo me-1"></i> Reset
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded mt-3">
                <div class="card-header">
                    <h5 class="card-title mb-0">Key People Records</h5>
                </div>
                <div class="card-body">
                    {{-- Action Buttons --}}
                    <div class="d-flex justify-content-between mb-3">
                        <a class="btn btn-warning btn-sm shadow-sm d-flex align-items-center fw-semibold" href="{{ route('organization-structure.create') }}">
                            <i class="fas fa-plus me-1"></i> Create
                        </a>
                    </div>

                    <div id="search-result">
                        <div class="table-responsive">
                            @include('structure.search_results')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Function to perform AJAX search
            function fetchData(url = "{{ route('organization-structure.index') }}") {
                const queryString = $('#filterForm').serialize();

                $.ajax({
                    url: url,
                    method: "GET",
                    data: queryString,
                    beforeSend: function() {
                        $('#search-result').html(
                            '<div class="text-center py-5 text-muted">' +
                            '<div class="spinner-border text-primary mb-2" role="status"></div>' +
                            '<div>Searching records, please wait...</div>' +
                            '</div>'
                        );
                    },
                    success: function(response) {
                        $('#search-result').html(response);
                        // Reinitialize Feather icons if used in results
                        if (typeof feather !== 'undefined') {
                            feather.replace();
                        }
                    },
                    error: function(xhr) {
                        console.error('AJAX Error:', xhr.responseText);
                        toastr.error('Failed to load search results.');
                    }
                });
            }

            // Trigger search on input or change
            let searchTimeout;
            $('#keywordSearch').on('input', function(e) {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(function() {
                    fetchData();
                }, 300); // debounce search to avoid overwhelming backend
            });

            // Reset filters
            $('#resetFilters').on('click', function() {
                $('#keywordSearch').val('');
                fetchData();
            });

            // Handle pagination via AJAX
            $(document).on('click', '#search-result .pagination a', function(e) {
                e.preventDefault();
                const url = $(this).attr('href');
                fetchData(url);
            });

            // Handle Delete with SweetAlert2 & Axios
            $(document).on('click', '.delete-person', function(e) {
                e.preventDefault();
                const button = $(this);
                const url = button.data('url');

                Swal.fire({
                    title: 'Delete Key Person?',
                    text: "Are you sure you want to remove this key person? This action cannot be undone.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        axios.delete(url)
                            .then(response => {
                                if (response.data.success) {
                                    Swal.fire({
                                        title: 'Deleted!',
                                        text: response.data.message,
                                        icon: 'success',
                                        timer: 1500,
                                        showConfirmButton: false
                                    }).then(() => {
                                        fetchData(); // Reload the results table
                                    });
                                }
                            })
                            .catch(error => {
                                Swal.fire(
                                    'Error!',
                                    error.response?.data?.message || 'Something went wrong. Please try again later.',
                                    'error'
                                );
                            });
                    }
                });
            });
        });
    </script>
@endpush
