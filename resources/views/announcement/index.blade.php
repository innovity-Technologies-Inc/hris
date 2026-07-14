@extends('structure.master')

@section('content')
    @php
        $generalSettings = \App\HelperClass::getGeneralSetting();
    @endphp

    <div class="row">
        {{-- Search Card --}}
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm rounded">
                <div class="card-header">
                    <h5 class="card-title mb-0">Search Announcements</h5>
                </div>
                <div class="card-body">
                    <div class="border rounded shadow-sm p-3 filter-section-bg">
                        <form id="filterForm">
                            {{-- Keyword Search --}}
                            <div class="row mb-3">
                                <div class="col-12">
                                    <label for="keywordSearch" class="form-label text-muted small fw-semibold mb-1">
                                        Keyword Search
                                    </label>
                                    <div class="input-group input-group-md">
                                        <input type="text" class="form-control border-end-0" id="keywordSearch"
                                            name="keyword"
                                            placeholder="Search announcements by title or content"
                                            value="{{ request('keyword') }}">
                                        <span class="input-group-text border-start-0 input-group-bg">
                                            <i class="mdi mdi-magnify text-muted"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            {{-- Company Selector --}}
                            <div class="row mb-3">
                                <div class="col-12">
                                    <label for="company_id" class="form-label text-muted small fw-semibold mb-1">
                                        Company
                                    </label>
                                    <select name="company_id" id="company_id" class="form-select form-select-sm">
                                        <option value="">Global (All Companies)</option>
                                        @foreach($companies as $company)
                                            <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>
                                                {{ $company->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            {{-- Reset Button --}}
                            <div class="row">
                                <div class="col-12 text-end">
                                    <button type="button" id="resetFilters" class="btn btn-outline-secondary btn-sm">
                                        <i class="mdi mdi-refresh"></i> Reset
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Table Card --}}
        <div class="col-lg-12 mt-3">
            <div class="card border-0 shadow-sm rounded">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Announcements List</h5>
                    @can('announcements.create')
                        <a class="btn btn-warning btn-sm" href="{{ route('announcements.create') }}">
                            <i style="height: 12px; width: 12px" data-feather="plus"></i> Post Announcement
                        </a>
                    @endcan
                </div>
                <div class="card-body">
                    <div id="search-result">
                        @include('announcement.partials.table')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Change Event for Company Selector
        $('#company_id').on('change', function () {
            fetchAnnouncements();
        });

        // --- Live Search ---
        let debounceTimer;

        function fetchAnnouncements(url = '{{ route('announcements.index') }}') {
            const queryString = $('#filterForm').serialize();

            $.ajax({
                url: url,
                method: "GET",
                data: queryString,
                beforeSend: function() {
                    $('#search-result').html('<div class="text-center py-4 text-muted">Loading...</div>');
                },
                success: function(response) {
                    $('#search-result').html(response);
                    if (typeof feather !== 'undefined') {
                        feather.replace();
                    }
                    attachDeleteHandlers();
                },
                error: function(xhr) {
                    console.error('AJAX Error:', xhr.responseText);
                }
            });
        }

        function debouncedSearch() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                fetchAnnouncements();
            }, 300);
        }

        $('#keywordSearch').on('input', debouncedSearch);

        // Reset Filters Button
        $('#resetFilters').on('click', function() {
            $('#keywordSearch').val('');
            $('#company_id').val('');
            fetchAnnouncements();
        });

        // Intercept Pagination Clicks for AJAX Search
        $(document).on('click', '#search-result .pagination a', function(e) {
            e.preventDefault();
            const url = $(this).attr('href');
            if (url) {
                fetchAnnouncements(url);
            }
        });

        // Delete Handler Setup
        function attachDeleteHandlers() {
            const deleteBtns = document.querySelectorAll('.delete-btn');
            deleteBtns.forEach(btn => {
                // Clear any existing handler
                $(btn).off('click').on('click', function(event) {
                    event.preventDefault();
                    const url = this.getAttribute('data-url');
                    
                    Swal.fire({
                        title: 'Are you sure you want to delete?',
                        text: 'This action cannot be reverted!',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Confirm'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            axios.delete(url, {
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                }
                            })
                            .then(response => {
                                Swal.fire({
                                    title: 'Deleted!',
                                    text: response.data.message || 'Announcement has been deleted.',
                                    icon: 'success'
                                }).then(() => {
                                    fetchAnnouncements();
                                });
                            })
                            .catch(error => {
                                let errorMsg = 'Something went wrong. Please try again later.';
                                if (error.response && error.response.data && error.response.data.message) {
                                    errorMsg = error.response.data.message;
                                }
                                Swal.fire({
                                    title: 'Error!',
                                    text: errorMsg,
                                    icon: 'error'
                                });
                            });
                        }
                    });
                });
            });
        }

        // Initialize Delete Handlers on first page load
        attachDeleteHandlers();
    });
</script>
@endpush
