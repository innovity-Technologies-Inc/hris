@extends('structure.master')
@section('content')
    {{-- Search Section --}}
    <div class="row">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm rounded">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i data-feather="clipboard" class="me-2"></i>Search Vehicle Requisitions
                    </h5>
                </div>
                <div class="card-header border-bottom p-4">
                    <div class="row align-items-start">
                        {{-- Filter Section --}}
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
                                                    name="keyword"
                                                    placeholder="Search by trip type, location, vehicle type..."
                                                    aria-label="Keyword Search" value="{{ request('keyword') }}">
                                                <span class="input-group-text border-start-0 input-group-bg">
                                                    <i class="mdi mdi-magnify text-muted"></i>
                                                </span>
                                            </div>
                                        </div>

                                        {{-- Status Filter --}}
                                        <div class="col-md-4">
                                            <label for="statusFilter" class="form-label text-muted small fw-semibold mb-1">
                                                Approval Status
                                            </label>
                                            <select class="form-select" id="statusFilter" name="status">
                                                <option value="">All Status</option>
                                                <option value="Pending"
                                                    {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="Approved"
                                                    {{ request('status') == 'Approved' ? 'selected' : '' }}>Approved
                                                </option>
                                                <option value="Rejected"
                                                    {{ request('status') == 'Rejected' ? 'selected' : '' }}>Rejected
                                                </option>
                                            </select>
                                        </div>
                                    </div>

                                    {{-- Reset Button --}}
                                    <div class="row">
                                        <div class="col-12 text-end">
                                            <button type="button" id="resetFilters"
                                                class="btn btn-outline-secondary btn-sm">
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
                <div class="card-header">
                    <h5 class="card-title mb-0">Vehicle Requisition List</h5>
                </div>
                <div class="card-body">
                    {{-- Action Buttons --}}
                    <div class="d-flex justify-content-between mb-3">
                        <a type="button" class="btn btn-warning btn-sm"
                            href="{{ route('transport.vehicle_requisitions.create') }}">
                            <i style="height: 12px; width: 12px" data-feather="plus"></i> Create New Requisition
                        </a>
                    </div>

                    @if ($vehicleRequisitions->isEmpty())
                        <div class="text-center py-4 text-muted">
                            <i data-feather="inbox" style="width: 48px; height: 48px;"></i>
                            <p class="mt-2 mb-0">No vehicle requisitions found</p>
                            <a href="{{ route('transport.vehicle_requisitions.create') }}"
                                class="btn btn-sm btn-primary mt-2">
                                <i data-feather="plus" style="width: 14px; height: 14px;"></i> Create First Requisition
                            </a>
                        </div>
                    @else
                        <div class="table-responsive" id="search-result">
                            @include('transport.vehicle_requisition.search_results')
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

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
                            <i data-feather="x-circle" style="width: 14px; height: 14px;"></i> Reject
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        $(document).ready(function() {
            // Function to perform AJAX search
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

            // Trigger search on input or change
            $('#filterForm').on('input change', function(e) {
                e.preventDefault();
                fetchData();
            });

            // Reset filters
            $('#resetFilters').on('click', function() {
                $('#filterForm')[0].reset();
                fetchData();
            });

            // Handle pagination clicks
            $(document).on('click', '.pagination a', function(e) {
                e.preventDefault();
                const url = $(this).attr('href');
                if (url) {
                    fetchData(url);
                }
            });

            // Handle reject modal
            $(document).on('click', '.rejectBtn', function() {
                const requisitionId = $(this).data('id');
                const rejectUrl = "{{ url('transport/vehicle-requisitions') }}/" + requisitionId +
                "/reject";
                $('#rejectForm').attr('action', rejectUrl);
                $('#rejectModal').modal('show');
            });
        });
    </script>
@endsection
