@extends('structure.master')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card border-0 shadow-sm rounded">
            <div class="card-header">
                <h5 class="card-title mb-0">Profile Update Requests</h5>
            </div><!-- end card header -->
            <div class="card-header border-bottom p-4">
                <div class="row align-items-start">
                    {{-- Filter Section --}}
                    <div class="col-md-12">
                        <div class="border rounded shadow-sm p-3 filter-section-bg">
                            <form id="searchForm">
                                <div class="row g-3">
                                    <div class="col-md-7">
                                        <label class="form-label text-muted small fw-semibold mb-1">Keyword Search</label>
                                        <div class="input-group input-group-md">
                                            <input type="text" name="search" class="form-control border-end-0" placeholder="Search by section, status..." value="{{ request('search') }}">
                                            <span class="input-group-text border-start-0 input-group-bg">
                                                <i class="mdi mdi-magnify text-muted"></i>
                                            </span>
                                        </div>
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
                                    <div class="col-md-2 d-flex align-items-end">
                                        <button type="button" id="resetBtn" class="btn btn-outline-secondary btn-md w-100">
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
    </div>

    <div class="col-lg-12 mt-3">
        <div class="card border-0 shadow-sm rounded">
            <div class="card-header">
                <h5 class="card-title mb-0">Requests List</h5>
            </div>
            <div class="card-body">
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
                tableContainer.html('<div class="text-center py-4 text-muted">Loading...</div>');
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

    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        fetchResults($(this).attr('href'));
    });

    window.deleteRequest = function(id) {
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                axios.delete('{{ url('employees/update-requests') }}/' + id)
                    .then(response => {
                        if (response.data.success) {
                            Swal.fire("Deleted!", response.data.message, "success").then(() => location.reload());
                        }
                    })
                    .catch(() => {
                        Swal.fire("Error!", "Something went wrong.", "error");
                    });
            }
        });
    };
});
</script>
@endpush