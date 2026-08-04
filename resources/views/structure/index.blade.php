@extends('structure.master')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-users-cog fa-lg me-2"></i>
                        <h4 class="mb-0 text-white font-weight-bold">Key People</h4>
                    </div>
                    <a href="{{ route('organization-structure.create') }}" class="btn btn-warning btn-sm shadow-sm d-flex align-items-center fw-semibold">
                        <i class="fas fa-plus me-1"></i> Add Key Person
                    </a>
                </div><!-- end card header -->

                <!-- Search Filter Form -->
                <div class="px-4 pt-3 pb-2 border-bottom bg-light-subtle">
                    <form id="filterForm" onsubmit="return false;">
                        <div class="row align-items-center">
                            <div class="col-md-8 col-12 mb-2 mb-md-0">
                                <div class="input-group">
                                    <span class="input-group-text border-end-0 bg-white">
                                        <i class="fas fa-search text-muted"></i>
                                    </span>
                                    <input type="text" class="form-control border-start-0" id="keywordSearch" name="keyword"
                                        placeholder="Search by name, position, email, phone, or hierarchy level..." aria-label="Keyword Search">
                                </div>
                            </div>
                            <div class="col-md-4 col-12 text-md-end">
                                <button type="button" class="btn btn-outline-secondary w-100 w-md-auto" id="resetFilters">
                                    <i class="fas fa-undo me-1"></i> Reset Search
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="card-body p-4" id="search-result">
                    @include('structure.search_results')
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
