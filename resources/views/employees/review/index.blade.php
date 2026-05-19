@extends('structure.master')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h2 class="fw-bold text-dark mb-1">Profile Review</h2>
                    <p class="text-muted">Review and approve pending employee profiles.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <form id="searchForm" class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label text-muted small fw-bold text-uppercase">Search</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-search"></i></span>
                                <input type="text" name="search" class="form-control bg-light border-start-0" placeholder="Name, ID, Email...">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-muted small fw-bold text-uppercase">Department</label>
                            <select name="department" class="form-select bg-light">
                                <option value="">All Departments</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}">{{ $dept->department_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100 py-2">Filter Results</button>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="button" id="resetBtn" class="btn btn-outline-secondary w-100 py-2">Reset</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <div id="tableContainer">
                        @include('employees.review.partials.table', ['employees' => $employees])
                    </div>
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

    function fetchResults(url = window.location.href) {
        const formData = searchForm.serialize();
        $.ajax({
            url: url,
            data: formData,
            success: function(response) {
                tableContainer.html(response.html);
                // Handle pagination if needed via response.pagination
            }
        });
    }

    searchForm.on('submit', function(e) {
        e.preventDefault();
        fetchResults();
    });

    $('#resetBtn').on('click', function() {
        searchForm[0].reset();
        fetchResults();
    });

    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        fetchResults($(this).attr('href'));
    });
});
</script>
@endpush
