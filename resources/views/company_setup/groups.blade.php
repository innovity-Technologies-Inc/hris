@extends('structure.master')
@section('content')
    {{--    Form --}}

    {{--@if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif--}}


    @canany(['groups.create', 'groups.edit'])
    <div class="row">
        <div class="col-12">
            <div class="card">

                <div class="card-header">
                    <h5 class="card-title mb-0">Add Group Name</h5>
                </div><!-- end card header -->

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form action="{{ route('groups.save') }}" method="post">
                                @csrf
                                <div class="mb-3 row">
                                    <div class="col-lg-12">
                                        <label for="simpleinput" class="form-label">Group Name<span
                                                class="text-danger">*</span></label>
                                        <input type="hidden" name="type" value="{{ isset($group) ? 'edit' : 'create' }}">
                                        <input type="hidden" name="id" value="{{ isset($group) ? $group->id : '' }}">

                                        <input type="text" id="simpleinput" class="form-control" name="name"
                                            placeholder="Enter Group Name" value="{{ isset($group) ? $group->name : old('name') }}">
                                        @error('name')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>

                                    {{--<div class="col-lg-4">
                                        <label for="example-select" class="form-label">Status</label>
                                        <select class="form-select" id="example-select" name="status">
                                            <option value="active">Active</option>
                                            <option value="inactive">Inactive</option>
                                        </select>
                                    </div>--}}

                                </div>

                                <button type="submit" class="btn btn-primary">Submit</button>


                            </form>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
    @endcanany



    {{--    list --}}
    {{--
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Group List</h5>
                    <form id="filterForm">
                        --}}
    {{-- First Row: Keyword Search --}}{{--

                        <div class="row mb-1 mt-2">
                            <div class="col-12">
                                <div class="input-group input-group-md">
                                    <input type="text" class="form-control border-end-0" id="keywordSearch"
                                           name="keyword" placeholder="Search groups by name"
                                           aria-label="Keyword Search" value="{{ request('keyword') }}">
                                    <span class="input-group-text border-start-0 input-group-bg">
                                                    <i class="mdi mdi-magnify text-muted"></i>
                                                </span>
                                </div>
                            </div>
                        </div>
                    </form>
                </div><!-- end card header -->

                <div class="card-body" id="search-result">
                    @include('company_setup.group_search_results')
                </div>
            </div>
        </div>

    </div>
--}}

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <script>
        $(document).ready(function() {
            // Function to perform AJAX search
            function fetchData(url = "{{ route('groups.index') }}") {
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

            // Reset filters: clear form and reload base URL
            $('#resetFilters').on('click', function() {
                // Clear all form fields
                $('#filterForm')[0].reset();

                // If using Select2, you may need to trigger change
                $('.select2_list').val(null).trigger('change');

                // Reload the page without query string
                window.location.href = "{{ route('groups.index') }}";
            });

            // Handle pagination via AJAX
            $(document).on('click', '#search-result .pagination a', function(e) {
                e.preventDefault();
                const url = $(this).attr('href');
                fetchData(url);
            });
        });
    </script>

    <script>
        document.getElementById('resetFilters').addEventListener('click', function() {
            document.getElementById('keywordSearch').value = '';
            document.getElementById('filterForm').submit();
        });
    </script>
@endsection
