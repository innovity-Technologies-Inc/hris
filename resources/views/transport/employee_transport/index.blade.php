@extends('structure.master')

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <a type="button" class="btn btn-warning btn-sm" href="{{ route('transport.employee_transports.create') }}">
                        <i style="height: 12px; width: 12px" data-feather="plus"></i> Create
                    </a>
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
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = `/transport/employee-transports/${id}/reject`;
                        
                        const csrfInput = document.createElement('input');
                        csrfInput.type = 'hidden';
                        csrfInput.name = '_token';
                        csrfInput.value = '{{ csrf_token() }}';
                        form.appendChild(csrfInput);

                        const methodInput = document.createElement('input');
                        methodInput.type = 'hidden';
                        methodInput.name = '_method';
                        methodInput.value = 'PATCH';
                        form.appendChild(methodInput);

                        const remarksInput = document.createElement('input');
                        remarksInput.type = 'hidden';
                        remarksInput.name = 'approval_remarks';
                        remarksInput.value = result.value;
                        form.appendChild(remarksInput);

                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            };
        });
    </script>
@endsection
