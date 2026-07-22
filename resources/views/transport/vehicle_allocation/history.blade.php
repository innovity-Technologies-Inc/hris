@extends('structure.master')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-history me-2"></i>
                            <h5 class="mb-0">Vehicle Allocation History</h5>
                        </div>
                        <div>
                            <a href="{{ route('transport.vehicle_allocations.dashboard') }}" class="btn btn-light btn-sm">
                                <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                            </a>
                            <a href="{{ route('transport.vehicle_allocations.create') }}" class="btn btn-success btn-sm">
                                <i class="fas fa-plus me-1"></i>New Allocation
                            </a>
                            <button type="button" id="exportExcelBtn" class="btn btn-light btn-sm no-loader ms-1">
                                <i class="fas fa-file-excel text-success me-1"></i>Excel
                            </button>
                            <button type="button" id="printBtn" class="btn btn-light btn-sm no-loader">
                                <i class="fas fa-print text-primary me-1"></i>Print
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Filters -->
                    <form action="{{ route('transport.vehicle_allocations.history') }}" method="GET" class="mb-4" id="filterForm">
                        <div class="row g-3 align-items-end mb-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Search Keyword</label>
                                <div class="input-group">
                                    <input type="text" name="keyword" id="keywordSearch" class="form-control"
                                        placeholder="Search by allocation name, type, status..."
                                        value="{{ request('keyword') }}">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-search text-muted"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Vehicle</label>
                                <select name="vehicle_id" class="form-select select2_list">
                                    <option value="">All Vehicles</option>
                                    @foreach ($vehicles ?? [] as $vehicle)
                                        <option value="{{ $vehicle->id }}"
                                            {{ request('vehicle_id') == $vehicle->id ? 'selected' : '' }}>
                                            {{ $vehicle->license_number }} ({{ $vehicle->vehicle_category }} - {{ $vehicle->model_number }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row g-3 align-items-end">
                            <div class="col-md-4">
                                <label class="form-label small fw-semibold">Allocation Type</label>
                                <select name="allocation_type" class="form-select">
                                    <option value="">All Types</option>
                                    <option value="employee_transport"
                                        {{ request('allocation_type') == 'employee_transport' ? 'selected' : '' }}>
                                        Employee Transport
                                    </option>
                                    <option value="trip_based"
                                        {{ request('allocation_type') == 'trip_based' ? 'selected' : '' }}>
                                        Trip Based
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-semibold">Status</label>
                                <select name="status" class="form-select">
                                    <option value="">All Status</option>
                                    <option value="Active" {{ request('status') == 'Active' ? 'selected' : '' }}>Active</option>
                                    <option value="Inactive" {{ request('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                    <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small fw-semibold">From Date</label>
                                <input type="date" name="from_date" class="form-control"
                                    value="{{ request('from_date') }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label small fw-semibold">To Date</label>
                                <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                            </div>
                        </div>
                    </form>

                    <!-- Results Table Wrapper -->
                    <div id="search-result">
                        @include('transport.vehicle_allocation.history_results')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Release Vehicle Modal -->
    <div class="modal fade" id="releaseModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Release Vehicle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="releaseForm" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body">
                        <p>Are you sure you want to release this vehicle allocation?</p>
                        <div class="mb-3">
                            <label for="release_remarks" class="form-label">Remarks (Optional)</label>
                            <textarea name="release_remarks" id="release_remarks" class="form-control" rows="3"
                                placeholder="Enter any remarks for releasing this vehicle..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-unlock me-1"></i>Release Vehicle
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function releaseVehicle(allocationId) {
            const form = document.getElementById('releaseForm');
            form.action = `/transport/vehicle-allocations/${allocationId}/release`;
            const modal = new bootstrap.Modal(document.getElementById('releaseModal'));
            modal.show();
        }

        $(document).ready(function() {
            function fetchData(url = "{{ route('transport.vehicle_allocations.history') }}") {
                const queryString = $('#filterForm').serialize();
                $.ajax({
                    url: url,
                    method: "GET",
                    data: queryString + '&_ajax=1',
                    beforeSend: function() {
                        $('#search-result').html(
                            '<div class="text-center py-4 text-muted">Loading Data...</div>');
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

            let timer;
            $(document).on('input change change.select2', '#filterForm input, #filterForm select', function(e) {
                clearTimeout(timer);
                timer = setTimeout(function() {
                    fetchData();
                }, 300);
            });

            $('#filterForm').on('submit', function(e) {
                e.preventDefault();
                fetchData();
            });

            $(document).on('click', '#search-result .pagination a', function(e) {
                e.preventDefault();
                const url = $(this).attr('href');
                if (url) {
                    fetchData(url);
                }
            });

            // Excel Export click handler
            $(document).on('click', '#exportExcelBtn', function(e) {
                e.preventDefault();
                window.ignoreBeforeUnload = true;
                setTimeout(() => {
                    window.ignoreBeforeUnload = false;
                }, 2000);
                let queryString = $('#filterForm').serialize();
                let baseUrl = "{{ route('transport.vehicle_allocations.export.excel') }}";
                window.location.href = baseUrl + '?' + queryString;
            });

            // Print click handler
            $(document).on('click', '#printBtn', function(e) {
                e.preventDefault();
                let queryString = $('#filterForm').serialize();
                let baseUrl = "{{ route('transport.vehicle_allocations.print') }}";
                window.open(baseUrl + '?' + queryString, '_blank');
            });
        });
    </script>
@endpush

