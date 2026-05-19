@extends('structure.master')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card border-0 shadow-sm rounded">
            <div class="card-header">
                <h5 class="card-title mb-0">Profile Review</h5>
            </div><!-- end card header -->
            <div class="card-header border-bottom p-4">
                <div class="row align-items-start">
                    {{-- Filter Section --}}
                    <div class="col-md-12">
                        <div class="border rounded shadow-sm p-3 filter-section-bg">
                            <form id="searchForm">
                                <div class="row g-3">
                                    <div class="col-md-8">
                                        <label class="form-label text-muted small fw-semibold mb-1">Keyword Search</label>
                                        <div class="input-group input-group-md">
                                            <input type="text" name="search" class="form-control border-end-0" placeholder="Search by Name, Email, ID, Punch Card, System ID..." value="{{ request('search') }}">
                                            <span class="input-group-text border-start-0 input-group-bg">
                                                <i class="mdi mdi-magnify text-muted"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        <button type="submit" class="btn btn-primary btn-md w-100">
                                            <i class="mdi mdi-filter-variant me-1"></i> Filter
                                        </button>
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
                <h5 class="card-title mb-0">Pending Profiles</h5>
            </div>
            <div class="card-body">
                <div id="tableContainer">
                    @include('employees.review.partials.table', ['employees' => $employees])
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

    function fetchResults(url = "{{ route('employees.review.index') }}") {
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
        fetchResults();
    });

    searchForm.on('input change', 'input, select', function() {
        fetchResults();
    });

    $('#resetBtn').on('click', function() {
        searchForm[0].reset();
        window.location.href = "{{ route('employees.review.index') }}";
    });

    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        fetchResults($(this).attr('href'));
    });
});
</script>
@endpush
