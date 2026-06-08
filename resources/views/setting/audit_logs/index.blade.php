@extends('structure.master')

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-journal-text me-2"></i> Audit Logs
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Search & Filter Area -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="search-box">
                                <form id="search-form">
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="search" id="search-input" placeholder="Search logs...">
                                        <button class="btn btn-outline-secondary" type="submit">
                                            <i class="bi bi-search"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Search Results -->
                    <div id="search-results">
                        @include('setting.audit_logs.search_results')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchForm = document.getElementById('search-form');
            const searchInput = document.getElementById('search-input');
            const resultsContainer = document.getElementById('search-results');

            function fetchLogs(url = null) {
                const search = searchInput.value;
                const reqUrl = url || '{{ route('audit_logs.index') }}';
                
                axios.get(reqUrl, {
                    params: { search: search },
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                }).then(response => {
                    resultsContainer.innerHTML = response.data.html;
                }).catch(error => {
                    console.error('Error fetching logs:', error);
                });
            }

            searchForm.addEventListener('submit', function(e) {
                e.preventDefault();
                fetchLogs();
            });

            searchInput.addEventListener('keyup', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    fetchLogs();
                } else {
                    // Optional debouncing can be added here
                    clearTimeout(this.searchTimeout);
                    this.searchTimeout = setTimeout(() => {
                        fetchLogs();
                    }, 500);
                }
            });

            // Pagination delegation
            resultsContainer.addEventListener('click', function(e) {
                if (e.target.tagName === 'A' && e.target.closest('.pagination')) {
                    e.preventDefault();
                    fetchLogs(e.target.href);
                }
            });
        });
    </script>
@endpush