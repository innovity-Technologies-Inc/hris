@extends('structure.master')

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <a type="button" class="btn btn-warning btn-sm" href="{{ route('transport.vehicle_requisitions.create') }}">
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
                        <div class="col-md-8 mb-2">
                            <div class="input-group input-group-md">
                                <input type="text" class="form-control border-end-0" id="keywordSearch" name="keyword"
                                    placeholder="Search by trip type, location, vehicle type..." aria-label="Keyword Search" value="{{ request('keyword') }}">
                                <span class="input-group-text border-start-0 input-group-bg">
                                    <i class="mdi mdi-magnify text-muted"></i>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <select class="form-select" id="statusFilter" name="status">
                                <option value="">All Status</option>
                                <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                                <option value="Approved" {{ request('status') == 'Approved' ? 'selected' : '' }}>Approved</option>
                                <option value="Rejected" {{ request('status') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>
                    </div>
                </form>

                <div class="card-body">
                    <div class="table-responsive" id="search-result">
                        @include('transport.vehicle_requisition.search_results')
                    </div>
                </div>
            </div><!-- end card -->
        </div>
    </div><!-- end row -->

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        $(document).ready(function() {
            function fetchData(url = "{{ route('transport.vehicle_requisitions.index') }}") {
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

            // Handle reject modal show
            $(document).on('click', '.rejectBtn', function() {
                const requisitionId = $(this).data('id');
                const rejectUrl = "{{ url('transport/vehicle-requisitions') }}/" + requisitionId + "/reject";
                $('#rejectForm').attr('action', rejectUrl);
                $('#rejectModal').modal('show');
            });

            // Handle reject form submit via Axios
            $('#rejectForm').on('submit', function(e) {
                e.preventDefault();
                const form = this;
                const submitBtn = $(form).find('[type="submit"]');
                submitBtn.prop('disabled', true);

                const formData = new FormData(form);

                axios.post(form.action, formData)
                    .then(response => {
                        if (response.data.success) {
                            $('#rejectModal').modal('hide');
                            form.reset();
                            submitBtn.prop('disabled', false);
                            Swal.fire({
                                icon: 'success',
                                title: 'Rejected!',
                                text: response.data.message,
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                fetchData();
                            });
                        }
                    })
                    .catch(error => {
                        submitBtn.prop('disabled', false);
                        Swal.fire(
                            'Error!',
                            error.response?.data?.message || 'Something went wrong.',
                            'error'
                        );
                    });
            });

            // Excel Export click handler
            $(document).on('click', '#exportExcelBtn', function(e) {
                e.preventDefault();
                window.ignoreBeforeUnload = true;
                setTimeout(() => {
                    window.ignoreBeforeUnload = false;
                }, 2000);
                let queryString = $('#filterForm').serialize();
                let baseUrl = "{{ route('transport.vehicle_requisitions.export.excel') }}";
                window.location.href = baseUrl + '?' + queryString;
            });

            // Print click handler
            $(document).on('click', '#printBtn', function(e) {
                e.preventDefault();
                let queryString = $('#filterForm').serialize();
                let baseUrl = "{{ route('transport.vehicle_requisitions.print') }}";
                window.open(baseUrl + '?' + queryString, '_blank');
            });
        });
    </script>

    {{-- Reject Modal --}}
    <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="rejectModalLabel">
                        <i data-feather="x-circle" class="me-2"></i>Reject Requisition
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form id="rejectForm" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p class="text-muted mb-3">Are you sure you want to reject this vehicle requisition?</p>
                        <div class="mb-3">
                            <label for="rejection_reason" class="form-label">Rejection Reason (Optional)</label>
                            <textarea class="form-control" id="rejection_reason" name="rejection_reason" rows="3"
                                placeholder="Enter reason for rejection..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">
                            Reject
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
