@extends('structure.master')
@section('content')
    {{--    Form --}}

    <div class="row">
        <div class="col-12">
            <div class="card">

                <div class="card-header">
                    <h5 class="card-title mb-0">Add Company Type</h5>
                </div><!-- end card header -->

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form action="{{ route('company_types.save') }}" method="post">
                                @csrf
                                <div class="mb-3 row">
                                    <div class="col-lg-4">
                                        <label for="simpleinput" class="form-label">Company Type Name<span
                                                class="text-danger">*</span></label>
                                        <input type="text" id="simpleinput" class="form-control" name="name"
                                            placeholder="Enter Company Type Name" value="{{ old('name') }}">

                                        @error('name')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror

                                    </div>

                                    <div class="col-lg-4">
                                        <label for="simpleinput" class="form-label">Short Name<span
                                                class="text-danger">*</span></label>
                                        <input type="text" id="simpleinput" class="form-control" name="short_name"
                                            placeholder="Enter Short Name" value="{{ old('short_name') }}">
                                        @error('short_name')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    <div class="col-lg-4">
                                        <label for="example-select" class="form-label">Status</label>
                                        <select class="form-select" id="example-select" name="status">
                                            <option value="active">Active</option>
                                            <option value="inactive">Inactive</option>
                                        </select>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary">Submit</button>


                            </form>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

    {{--    list --}}

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Company Type List</h5>
                </div><!-- end card header -->

                <div class="card-body">
                    <form id="filterForm">
                        {{-- Keyword Search --}}
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="input-group input-group-md">
                                    <input type="text" class="form-control border-end-0" id="keywordSearch"
                                        name="keyword" placeholder="Search company types by name, short name"
                                        aria-label="Keyword Search" value="{{ request('keyword') }}">
                                    <span class="input-group-text border-start-0 input-group-bg">
                                        <i class="mdi mdi-magnify text-muted"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive" id="search-result">
                        @include('company_setup.company_type_search_results')
                        <div class="mt-3">
                            {{ $company_types->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <script>
        $(document).ready(function() {
            // Function to perform AJAX search
            function fetchData(url = "{{ route('company_types.index') }}") {
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
                        // Reinitialize Feather icons if used in results
                        if (typeof feather !== 'undefined') {
                            feather.replace();
                        }
                        // Update URL without page param
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

            // Handle pagination via AJAX
            $(document).on('click', '#search-result .pagination a', function(e) {
                e.preventDefault();
                const url = $(this).attr('href');
                fetchData(url);
            });
        });
    </script>
@endsection
