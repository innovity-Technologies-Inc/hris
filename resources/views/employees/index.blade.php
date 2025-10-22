@extends('structure.master')

@section('content')
    {{-- Employee List --}}
    <div class="row">
        <div class="col-xl-12">
            <div class="card border-0 shadow-sm rounded">
                <div class="card-header bg-white border-bottom p-4">
                    <div class="row align-items-start">

                            {{-- Right side: Search and Filter Section --}}
                            <div class="col-md-12">
                                <div class="border rounded shadow-sm p-3 bg-light">
                                    <form id="filterForm">
                                        {{-- First Row: Keyword Search --}}
                                        <div class="row mb-2">
                                            <div class="col-12">
                                                <label for="keywordSearch"
                                                    class="form-label text-muted small fw-semibold mb-1">
                                                    Keyword Search
                                                </label>
                                                <div class="input-group input-group-sm">
                                                    <input type="text" class="form-control border-end-0"
                                                        id="keywordSearch" name="keyword" placeholder="Search employees..."
                                                        aria-label="Keyword Search">
                                                    <span class="input-group-text bg-white border-start-0">
                                                        <i class="mdi mdi-magnify text-muted"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Second Row: Employee Name, Employee ID, and System ID --}}
                                        <div class="row mb-2">
                                            <div class="col-md-4">
                                                <label for="employeeName"
                                                    class="form-label text-muted small fw-semibold mb-1">
                                                    Employee Name
                                                </label>
                                                <select id="employeeName" name="employee_name"
                                                    class="form-select form-select-sm select2_list"
                                                    data-placeholder="Select employee name" aria-label="Employee Name">
                                                    <option value="">Choose One</option>
                                                @foreach ($employees as $employee)
                                                    <option value="{{$employee->full_name}}">{{$employee->full_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-4">
                                                <label for="employeeId"
                                                    class="form-label text-muted small fw-semibold mb-1">
                                                    Employee ID
                                                </label>
                                                <select id="employeeId" name="employee_id"
                                                    class="form-select form-select-sm select2_list"
                                                    data-placeholder="Select employee ID" aria-label="Employee ID">
                                                    <option value="">Choose One</option>
                                                @foreach ($employees as $employee)
                                                        <option value="{{$employee->applicant_id}}">{{$employee->applicant_id}}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-4">
                                                <label for="systemId" class="form-label text-muted small fw-semibold mb-1">
                                                    System ID
                                                </label>
                                                <select id="systemId" name="system_id"
                                                    class="form-select form-select-sm select2_list"
                                                    data-placeholder="Select system ID" aria-label="System ID">
                                                    <option value="">Choose One</option>
                                                @foreach ($employees as $employee)
                                                        <option value="{{$employee->system_id}}">{{$employee->system_id}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                    </form>
                                </div>
                            </div>

                    </div>
                </div>


                <section id="search-result">
                    @include('employees.partials.search_results')
                </section>

            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <script>
        $(document).ready(function () {

            // Function to perform AJAX search
            function fetchEmployees(url = "{{ route('employees.index') }}") {
                const queryString = $('#filterForm').serialize();

                $.ajax({
                    url: url, // Can be pagination or base URL
                    method: "GET",
                    data: queryString,
                    beforeSend: function() {
                        $('#search-result').html('<div class="text-center py-4 text-muted">Loading...</div>');
                    },
                    success: function (response) {
                        $('#search-result').html(response);
                        window.history.pushState(null, '', '?' + queryString);
                    },
                    error: function (xhr) {
                        console.error('AJAX Error:', xhr.responseText);
                    }
                });
            }

            // Trigger search on typing or select changes
            $('#filterForm').on('input change', function (e) {
                e.preventDefault();
                fetchEmployees();
            });

            // Handle pagination links (Ajax pagination)
            $(document).on('click', '#search-result .pagination a', function (e) {
                e.preventDefault();
                const url = $(this).attr('href');
                fetchEmployees(url);
            });

        });
    </script>

@endsection
