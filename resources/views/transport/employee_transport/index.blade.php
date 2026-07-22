@extends('structure.master')

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <a type="button" class="btn btn-warning btn-sm" href="{{ route('transport.employee_transports.create') }}">
                        <i style="height: 12px; width: 12px" data-feather="plus"></i> Create
                    </a>
                    <div class="d-flex gap-2">
                        <button type="button" id="exportExcelBtn" class="btn btn-success btn-sm no-loader">
                            <i class="bi bi-file-earmark-excel me-1"></i> Excel
                        </button>
                        <button type="button" id="printBtn" class="btn btn-secondary btn-sm no-loader">
                            <i class="bi bi-printer me-1"></i> Print
                        </button>
                    </div>
                </div><!-- end card header -->

                {{-- Search Filter Form --}}
                <form id="filterForm">
                    <div class="row mb-1 mt-2 mx-4">
                        <div class="col-md-6 mb-2">
                            <div class="input-group input-group-md">
                                <input type="text" class="form-control border-end-0" id="keywordSearch" name="keyword"
                                    placeholder="Search by service name, purpose, status..." aria-label="Keyword Search" value="{{ request('keyword') }}">
                                <span class="input-group-text border-start-0 input-group-bg">
                                    <i class="mdi mdi-magnify text-muted"></i>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <select class="form-select" id="statusFilter" name="status">
                                <option value="">All Status</option>
                                <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                                <option value="Approved" {{ request('status') == 'Approved' ? 'selected' : '' }}>Approved</option>
                                <option value="Rejected" {{ request('status') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                                <option value="Cancelled" {{ request('status') == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-2">
                            <select class="form-select" id="typeFilter" name="transport_type">
                                <option value="">All Types</option>
                                <option value="Daily Commute" {{ request('transport_type') == 'Daily Commute' ? 'selected' : '' }}>Daily Commute</option>
                                <option value="Shuttle Service" {{ request('transport_type') == 'Shuttle Service' ? 'selected' : '' }}>Shuttle Service</option>
                                <option value="Special Transport" {{ request('transport_type') == 'Special Transport' ? 'selected' : '' }}>Special Transport</option>
                                <option value="Field Work" {{ request('transport_type') == 'Field Work' ? 'selected' : '' }}>Field Work</option>
                            </select>
                        </div>
                    </div>
                </form>

                <div class="card-body">
                    <div class="table-responsive" id="search-result">
                        @include('transport.employee_transport.search_results')
                    </div>
                </div>
            </div><!-- end card -->
        </div>
    </div><!-- end row -->

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

            $(document).on('click', '#search-result .pagination a', function(e) {
                e.preventDefault();
                const url = $(this).attr('href');
                fetchData(url);
            });

            // Global reject function
            window.rejectService = function(id) {
                Swal.fire({
                    title: 'Reject Service Request',
                    input: 'textarea',
                    inputLabel: 'Rejection Remarks',
                    inputPlaceholder: 'Type your remarks here...',
                    inputAttributes: {
                        'aria-label': 'Type your remarks here'
                    },
                    showCancelButton: true,
                    confirmButtonText: 'Submit Rejection',
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    preConfirm: (remarks) => {
                        if (!remarks) {
                            Swal.showValidationMessage('Rejection remarks are required');
                        }
                        return remarks;
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        axios.post(`/transport/employee-transports/${id}/reject`, {
                            _token: '{{ csrf_token() }}',
                            _method: 'PATCH',
                            approval_remarks: result.value
                        })
                        .then(response => {
                            if (response.data.success) {
                                Swal.fire('Rejected!', response.data.message, 'success');
                                fetchData();
                            } else {
                                Swal.fire('Failed!', response.data.message, 'error');
                            }
                        })
                        .catch(error => {
                            Swal.fire(
                                'Error!',
                                error.response?.data?.message || 'Something went wrong.',
                                'error'
                            );
                        });
                    }
                });
            };

            // Excel Export click handler
            $(document).on('click', '#exportExcelBtn', function(e) {
                e.preventDefault();
                window.ignoreBeforeUnload = true;
                setTimeout(() => {
                    window.ignoreBeforeUnload = false;
                }, 2000);
                let queryString = $('#filterForm').serialize();
                let baseUrl = "{{ route('transport.employee_transports.export.excel') }}";
                window.location.href = baseUrl + '?' + queryString;
            });

            // Print click handler
            $(document).on('click', '#printBtn', function(e) {
                e.preventDefault();
                let queryString = $('#filterForm').serialize();
                let baseUrl = "{{ route('transport.employee_transports.print') }}";
                window.open(baseUrl + '?' + queryString, '_blank');
            });
        });
    </script>
@endsection
