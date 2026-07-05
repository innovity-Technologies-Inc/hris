@extends('structure.master')

@section('content')
<div class="row">
    <!-- Filter Card -->
    <div class="col-lg-12">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header border-bottom p-3 bg-transparent">
                <div class="d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-2 d-inline-flex align-items-center justify-content-center">
                        <i class="mdi mdi-magnify text-primary fs-5"></i>
                    </div>
                    <h5 class="card-title mb-0 text-dark fw-bold">Search Profile Update Requests</h5>
                </div>
            </div>
            <div class="card-body p-4">
                <form id="searchForm">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-5">
                            <label class="form-label text-muted small fw-semibold mb-1">Keyword Search</label>
                            <div class="input-group input-group-md">
                                <input type="text" name="search" class="form-control border-end-0" placeholder="Search by section, status..." value="{{ request('search') }}">
                                <span class="input-group-text border-start-0 bg-transparent text-muted">
                                    <i class="mdi mdi-magnify"></i>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label text-muted small fw-semibold mb-1">Type</label>
                            <select name="type" class="form-select">
                                <option value="">All Types</option>
                                <option value="employee" {{ request('type') === 'employee' ? 'selected' : '' }}>Employee</option>
                                <option value="admin" {{ request('type') === 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-muted small fw-semibold mb-1">Status</label>
                            <select name="status" class="form-select">
                                <option value="">All Status</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="button" id="resetBtn" class="btn btn-outline-secondary w-100">
                                <i class="mdi mdi-refresh me-1"></i> Reset
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Table Card -->
    <div class="col-lg-12 mt-4">
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header border-bottom p-3 bg-transparent">
                <div class="d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-2 d-inline-flex align-items-center justify-content-center">
                        <i class="mdi mdi-format-list-bulleted text-primary fs-5"></i>
                    </div>
                    <h5 class="card-title mb-0 text-dark fw-bold">Requests List</h5>
                </div>
            </div>
            <div class="card-body p-4">
                <div id="tableContainer">
                    @include('employee.profile_update_requests.partials.table', ['requests' => $requests])
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    const searchForm = $('#searchForm');
    const tableContainer = $('#tableContainer');

    function fetchResults(url = "{{ route('profile_update_requests.index') }}") {
        const queryString = searchForm.serialize();
        $.ajax({
            url: url,
            data: queryString,
            beforeSend: function() {
                tableContainer.html('<div class="text-center py-5 text-muted"><div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>Loading update requests...</div>');
            },
            success: function(response) {
                tableContainer.html(response.html);
                if (typeof feather !== 'undefined') {
                    feather.replace();
                }
                const newUrl = '?' + queryString;
                window.history.pushState(null, '', newUrl || location.pathname);
            }
        });
    }

    searchForm.on('submit', function(e) {
        e.preventDefault();
    });

    searchForm.on('input change', 'input, select', function() {
        fetchResults();
    });

    $('#resetBtn').on('click', function() {
        searchForm[0].reset();
        window.location.href = "{{ route('profile_update_requests.index') }}";
    });
});

function deleteRequest(id) {
    if(confirm('Are you sure you want to delete this profile update request?')) {
        $.ajax({
            url: `/employees/update-requests/${id}`,
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if(response.success) {
                    location.reload();
                } else {
                    alert(response.message || 'Something went wrong.');
                }
            }
        });
    }
}
</script>
@endpush