@extends('structure.master')

@section('content')
    {{-- Tax Calculate Search & Batch Execution Page --}}
    <div class="row">
        <div class="col-lg-12">
            @can('tax-policy.view')
            <div class="card border-0 shadow-sm rounded">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0 fw-bold text-dark d-flex align-items-center">
                        <i data-feather="search" class="me-2 text-primary" style="width: 20px; height: 20px;"></i>
                        Search Tax Calculations
                    </h5>
                </div>
                <div class="card-header border-bottom p-4">
                    <div class="row align-items-start">
                        {{-- Filter Section --}}
                        <div class="col-md-12">
                            <div class="border rounded shadow-sm p-3 filter-section-bg">
                                <form id="filterForm">
                                    {{-- Row: Keyword Search --}}
                                    <div class="row align-items-end">
                                        <div class="col-md-10">
                                            <label for="keywordSearch" class="form-label text-muted small fw-semibold mb-1">
                                                Keyword Search
                                            </label>
                                            <div class="input-group input-group-md">
                                                <input type="text" class="form-control border-end-0" id="keywordSearch"
                                                       name="keyword" placeholder="Search by employee name, applicant id, system id"
                                                       aria-label="Keyword Search" value="{{ request('keyword') }}">
                                                <span class="input-group-text border-start-0 input-group-bg bg-white">
                                                    <i class="mdi mdi-magnify text-muted"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-md-2 text-end">
                                            <button type="button" id="resetFilters" class="btn btn-outline-secondary btn-md w-100">
                                                <i class="mdi mdi-refresh me-1"></i> Reset
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
                <div class="card-header bg-white py-3 border-bottom border-light d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-bold text-dark d-flex align-items-center">
                        <i data-feather="list" class="me-2 text-primary" style="width: 20px; height: 20px;"></i>
                        Tax Calculations List
                    </h5>
                    @can('tax-policy.edit')
                    <button type="button" class="btn btn-primary rounded-pill px-4 shadow-sm fw-bold no-loader" id="calculateTaxBtn">
                        <i data-feather="cpu" class="me-1" style="width: 16px; height: 16px;"></i> Calculate Tax
                    </button>
                    @endcan
                </div>
                <div class="card-body">
                    <div id="search-result">
                        @include('payroll.tax_calculate.partials.search_results')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        $(document).ready(function() {
            // Function to perform AJAX search
            function fetchData(url = "{{ route('tax-calculate.index') }}") {
                const queryString = $('#filterForm').serialize();

                $.ajax({
                    url: url,
                    method: "GET",
                    data: queryString,
                    beforeSend: function() {
                        $('#search-result').html(
                            '<div class="text-center py-5 text-muted"><span class="spinner-border spinner-border-sm me-2"></span> Loading Data...</div>'
                        );
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

            // Trigger search on input
            $('#keywordSearch').on('input', function(e) {
                e.preventDefault();
                fetchData();
            });

            // Reset filters
            $('#resetFilters').on('click', function() {
                $('#filterForm')[0].reset();
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

            // Trigger Tax Calculation batch processing
            $('#calculateTaxBtn').on('click', function(e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Are you sure?',
                    text: "This will run the tax calculation formula for all active employees. Existing tax logs will be updated.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, calculate now!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Calculating...',
                            html: 'Processing employee tax brackets. Please wait...',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        axios.post("{{ route('tax-calculate.calculate') }}")
                            .then(response => {
                                Swal.close();
                                if (response.data.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Calculated!',
                                        text: response.data.message,
                                        timer: 2000,
                                        showConfirmButton: false
                                    }).then(() => {
                                        fetchData();
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Calculation Failed',
                                        text: response.data.message
                                    });
                                }
                            })
                            .catch(error => {
                                Swal.close();
                                const msg = error.response?.data?.message || 'Failed to trigger tax calculation process.';
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: msg
                                });
                            });
                    }
                });
            });
        });
    </script>
@endsection
