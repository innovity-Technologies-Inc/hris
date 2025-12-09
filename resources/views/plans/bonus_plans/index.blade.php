@extends('structure.master')
@section('content')
    {{-- Bonus Plans List --}}

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <a type="button" class="btn btn-warning btn-sm" href="{{ route('plans.bonus_plans.create') }}">
                        <i style="height: 12px; width: 12px" data-feather="plus"></i> Create
                    </a>
                </div><!-- end card header -->

                {{-- Search Filter Form --}}
                <form id="filterForm">
                    <div class="row mb-1 mt-2 mx-4">
                        <div class="col-12">
                            <div class="input-group input-group-md">
                                <input type="text" class="form-control border-end-0" id="keywordSearch" name="keyword"
                                    placeholder="Search bonus plans by name or type..." aria-label="Keyword Search">
                                <span class="input-group-text border-start-0 input-group-bg">
                                    <i class="mdi mdi-magnify text-muted"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="card-body" id="search-result">
                    @include('plans.bonus_plans.search_results')
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // ==========================================
            // AJAX SEARCH FUNCTION
            // ==========================================
            /**
             * Performs AJAX request to fetch filtered bonus plans.
             * Updates the search results container without page reload.
             *
             * @param {string} url - The target URL (defaults to index route)
             */
            function fetchData(url = "{{ route('plans.bonus_plans.index') }}") {
                const queryString = $('#filterForm').serialize();

                $.ajax({
                    url: url,
                    method: "GET",
                    data: queryString,
                    beforeSend: function() {
                        // Show loading indicator while fetching
                        $('#search-result').html(
                            '<div class="text-center py-4 text-muted">' +
                            '<i class="mdi mdi-loading mdi-spin mdi-24px"></i>' +
                            '<p class="mt-2">Loading bonus plans...</p>' +
                            '</div>'
                        );
                    },
                    success: function(response) {
                        // Inject response HTML into results container
                        $('#search-result').html(response);

                        // Reinitialize Feather icons if used in results
                        if (typeof feather !== 'undefined') {
                            feather.replace();
                        }

                        // Update browser URL without reloading page (for bookmarking/sharing)
                        const newUrl = queryString ? '?' + queryString : location.pathname;
                        window.history.pushState(null, '', newUrl);
                    },
                    error: function(xhr) {
                        // Show user-friendly error message
                        $('#search-result').html(
                            '<div class="alert alert-danger m-3" role="alert">' +
                            '<i class="mdi mdi-alert-circle-outline me-2"></i>' +
                            'Failed to load bonus plans. Please try again.' +
                            '</div>'
                        );
                        console.error('AJAX Error:', xhr.responseText);
                    }
                });
            }

            // ==========================================
            // REAL-TIME SEARCH TRIGGER
            // ==========================================
            /**
             * Triggers search on every keystroke in the search input.
             * Uses 'input' event for better UX (instant feedback).
             */
            $('#filterForm').on('input change', function(e) {
                e.preventDefault();
                fetchData();
            });

            // ==========================================
            // RESET FILTERS HANDLER
            // ==========================================
            /**
             * Clears all search filters and reloads the unfiltered list.
             * Note: Requires a reset button with id="resetFilters" in your UI.
             */
            $('#resetFilters').on('click', function() {
                // Clear all form fields
                $('#filterForm')[0].reset();

                // If using Select2 dropdowns, trigger their reset
                $('.select2_list').val(null).trigger('change');

                // Reload the page without query string
                window.location.href = "{{ route('plans.bonus_plans.index') }}";
            });

            // ==========================================
            // AJAX PAGINATION HANDLER
            // ==========================================
            /**
             * Intercepts pagination link clicks to load next page via AJAX
             * instead of full page reload. Maintains current search filters.
             */
            $(document).on('click', '#search-result .pagination a', function(e) {
                e.preventDefault();
                const url = $(this).attr('href');
                fetchData(url);
            });
        });
    </script>
@endsection
