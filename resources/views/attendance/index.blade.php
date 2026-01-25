@extends('structure.master')

@section('content')
    {{-- Page Header --}}
    {{--<div class="border-bottom mb-4" style="background-color: var(--bs-secondary-bg);">
        <div class="container-fluid px-4 py-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-1 fw-bold">Daily Attendance Sheet</h3>
                    <p class="mb-0 text-muted small">Track and monitor employee attendance records</p>
                </div>
                <div class="text-end">
                    <div class="text-muted small mb-1">Today's Date</div>
                    <div class="fw-semibold">{{ date('l, F j, Y') }}</div>
                </div>
            </div>
        </div>
    </div>--}}

    <div class="container-fluid px-4 pb-5">

        {{-- Filter Card --}}
        <div class="card border-0 shadow-sm mb-4 overflow-hidden">
            <div class="card-header bg-gradient bg-primary text-white border-0 py-3">
                <div class="d-flex align-items-center">
                    <div class="bg-white bg-opacity-25 rounded-3 p-2 me-3">
                        <svg width="24" height="24" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" />
                        </svg>
                    </div>
                    <div>
                        <h5 class="mb-0 fw-semibold">Search Filters</h5>
                        <small class="opacity-75">Select criteria to view attendance records</small>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">
                <form id="filterForm">
                    <div class="row g-4">

                        {{-- Branch Select --}}{{--
                        <div class="col-md-6 col-xl-3">
                            <label for="branch_id" class="form-label fw-semibold mb-2">
                                <svg class="me-1" width="14" height="14" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" />
                                </svg>
                                Branch
                            </label>
                            <select id="branch_id" name="branch_id" class="form-select select2" required>
                                <option value="">Choose branch...</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                @endforeach
                            </select>
                            <div class="form-text">Required field</div>
                        </div>

                        --}}{{-- Department Select --}}{{--
                        <div class="col-md-6 col-xl-3">
                            <label for="department_id" class="form-label fw-semibold mb-2">
                                <svg class="me-1" width="14" height="14" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" />
                                </svg>
                                Department
                            </label>
                            <select id="department_id" name="department_id" class="form-select select2">
                                <option value="">All departments</option>
                                @foreach ($departments as $dept)
                                    <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                                @endforeach
                            </select>
                            <div class="form-text">Optional filter</div>
                        </div>

                        --}}{{-- Section Select --}}{{--
                        <div class="col-md-6 col-xl-3">
                            <label for="section_id" class="form-label fw-semibold mb-2">
                                <svg class="me-1" width="14" height="14" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z" />
                                </svg>
                                Section
                            </label>
                            <select id="section_id" name="section_id" class="form-select select2">
                                <option value="">All sections</option>
                                @foreach ($sections as $section)
                                    <option value="{{ $section->id }}">{{ $section->name }}</option>
                                @endforeach
                            </select>
                            <div class="form-text">Optional filter</div>
                        </div> --}}

                        {{-- Date Picker --}}
                        <div class="col-md-6 col-xl-3">
                            <label for="from" class="form-label fw-semibold mb-2">
                                <svg class="me-1" width="14" height="14" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" />
                                </svg>
                                From
                            </label>
                            <input type="date" id="from" name="from" class="form-control"
                                value="{{request('from')}}">
                        </div>

                        <div class="col-md-6 col-xl-3">
                            <label for="to" class="form-label fw-semibold mb-2">
                                <svg class="me-1" width="14" height="14" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" />
                                </svg>
                                To
                            </label>
                            <input type="date" id="to" name="to" class="form-control"
                                   value="{{request('to')}}">
                        </div>

                    </div>

                    {{-- Action Buttons --}}
                    <div class="d-flex flex-wrap gap-3 mt-4 pt-3 border-top">
                        <button type="button" class="btn btn-outline-secondary btn-lg px-4" id="resetFilters">
                            <svg class="me-2" width="18" height="18" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" />
                            </svg>
                            Clear All Filters
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Results Card --}}
        <div class="card border-0 shadow-sm overflow-hidden">
            <div class="card-header border-bottom py-3">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <div class="btn-group shadow-sm" role="group">
                            <button type="button" class="btn btn-outline-primary" title="Export to Excel">
                                <svg width="18" height="18" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M3 17a1 1 0 001 1h12a1 1 0 001-1v-5h-2v4H5v-4H3v5zM10 3l-4 4h3v6h2V7h3l-4-4z" />
                                </svg>
                                <span class="d-none d-md-inline ms-2">Export</span>
                            </button>
                            <button type="button" class="btn btn-outline-primary"
                                onclick="window.open('{{ route('attendance.print') }}', '_blank')" title="Print">
                                <svg width="18" height="18" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M5 4v2h10V4a1 1 0 00-1-1H6a1 1 0 00-1 1zm13 3H2a1 1 0 00-1 1v4a1 1 0 001 1h1v3a1 1 0 001 1h12a1 1 0 001-1v-3h1a1 1 0 001-1V8a1 1 0 00-1-1zm-3 9H5v-4h10v4z" />
                                </svg>
                                <span class="d-none d-md-inline ms-2">Print</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body p-0" id="search-result">
                @include('attendance.partials.search_results')
            </div>
        </div>

    </div>


    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <script>
        $(document).ready(function() {
            // Function to perform AJAX search
            function fetchData(url = "{{ route('attendance.index') }}") {
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
                window.location.href = "{{ route('attendance.index') }}";
            });

            // Handle pagination via AJAX
            $(document).on('click', '#search-result .pagination a', function(e) {
                e.preventDefault();
                const url = $(this).attr('href');
                fetchData(url);
            });
        });
    </script>

    {{-- Additional Dark Theme Support --}}
    <style>
        /* Table Row Hover Effect */
        .attendance-row:hover {
            background-color: var(--bs-tertiary-bg);
        }

        /* Badge Enhancements */
        .badge {
            font-weight: 500;
            font-size: 0.75rem;
        }

        /* Compact table layout */
        #attendanceTable th,
        #attendanceTable td {
            white-space: nowrap;
            padding: 0.75rem 0.5rem !important;
        }

        #attendanceTable th:first-child,
        #attendanceTable td:first-child {
            padding-left: 1rem !important;
        }

        #attendanceTable th:last-child,
        #attendanceTable td:last-child {
            padding-right: 1rem !important;
        }

        /* Ensure Select2 matches form control height */
        .select2-container--bootstrap-5 .select2-selection--single {
            height: calc(1.5em + 0.75rem + 2px) !important;
            display: flex !important;
            align-items: center !important;
        }

        /* Fix Select2 placeholder and selected text positioning */
        .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
            padding-left: 0 !important;
            padding-right: 0 !important;
            line-height: normal !important;
        }

        /* Ensure Select2 arrow is properly positioned */
        .select2-container--bootstrap-5 .select2-selection--single .select2-selection__arrow {
            height: 100% !important;
            top: 0 !important;
            transform: none !important;
        }

        /* Fix Select2 clear button positioning */
        .select2-container--bootstrap-5 .select2-selection--single .select2-selection__clear {
            margin-right: 10px;
        }

        /* Dark theme table row hover */
        .table-hover tbody tr:hover {
            background-color: var(--bs-tertiary-bg);
        }

        /* Remove extra spacing that may cause overlap */
        .select2-container--bootstrap-5 .select2-selection--single .select2-selection__placeholder {
            color: var(--bs-secondary-color);
        }

        /* Modal Enhancements */
        .modal-content {
            border-radius: 1rem;
        }

        .modal-header {
            border-radius: 1rem 1rem 0 0;
        }

        /* Info Item Styling in Modal */
        .info-item {
            padding: 0.75rem;
            border-radius: 0.5rem;
            background-color: var(--bs-light-bg-subtle);
        }

        /* Print Styles for A4 Paper - Professional Report */
        @media print {

            /* A4 Paper Setup */
            @page {
                size: A4;
                margin: 15mm 10mm;
            }

            /* Hide unnecessary elements */
            .btn,
            .pagination,
            .border-top.p-4,
            .card-header .col-auto,
            .modal-footer,
            button,
            .form-control,
            .form-select,
            nav,
            .sidebar,
            .navbar,
            header,
            footer,
            .no-print,
            .btn-group,
            .shadow,
            .shadow-sm {
                display: none !important;
            }

            /* Reset body and page styles */
            body {
                background: white !important;
                color: black !important;
                font-size: 10pt;
                line-height: 1.3;
            }

            /* Container adjustments */
            .container-fluid {
                padding: 0 !important;
                max-width: 100% !important;
            }

            /* Remove all colors - black, white, gray only */
            * {
                background-color: white !important;
                color: black !important;
                border-color: #666 !important;
                box-shadow: none !important;
            }

            /* Card styling for print */
            .card {
                border: 1px solid #ccc !important;
                page-break-inside: avoid;
                margin-bottom: 10px !important;
            }

            .card-header {
                background-color: #f5f5f5 !important;
                border-bottom: 2px solid #333 !important;
                padding: 8px !important;
                font-weight: bold;
            }

            .card-body {
                padding: 8px !important;
            }

            /* Page header for report */
            .border-bottom.mb-4 {
                border-bottom: 3px solid #333 !important;
                margin-bottom: 15px !important;
                padding-bottom: 10px !important;
                background-color: white !important;
            }

            .border-bottom.mb-4 h3 {
                font-size: 18pt !important;
                font-weight: bold !important;
                color: black !important;
            }

            .border-bottom.mb-4 p {
                font-size: 9pt !important;
                color: #666 !important;
            }

            /* Table styling */
            .table-responsive {
                overflow: visible !important;
            }

            #attendanceTable {
                width: 100% !important;
                border-collapse: collapse !important;
                font-size: 8pt !important;
            }

            #attendanceTable thead {
                background-color: #e0e0e0 !important;
                border-bottom: 2px solid #333 !important;
            }

            #attendanceTable th {
                padding: 6px 4px !important;
                font-weight: bold !important;
                text-align: center !important;
                border: 1px solid #999 !important;
                background-color: #e0e0e0 !important;
                color: black !important;
                font-size: 8pt !important;
            }

            #attendanceTable td {
                padding: 5px 4px !important;
                border: 1px solid #ccc !important;
                background-color: white !important;
                color: black !important;
                font-size: 8pt !important;
                vertical-align: middle !important;
            }

            #attendanceTable tbody tr {
                page-break-inside: avoid;
            }

            #attendanceTable tbody tr:nth-child(even) {
                background-color: #f9f9f9 !important;
            }

            /* Badge styling for print */
            .badge {
                border: 1px solid #666 !important;
                background-color: white !important;
                color: black !important;
                padding: 2px 6px !important;
                font-size: 7pt !important;
                font-weight: normal !important;
            }

            /* Status badges */
            .bg-success,
            .badge.bg-success {
                background-color: #e8e8e8 !important;
                border-color: #333 !important;
            }

            .bg-danger,
            .badge.bg-danger {
                background-color: #d0d0d0 !important;
                border-color: #333 !important;
            }

            .bg-warning,
            .badge.bg-warning {
                background-color: #f0f0f0 !important;
                border-color: #666 !important;
            }

            .bg-primary,
            .badge.bg-primary,
            .bg-info,
            .badge.bg-info {
                background-color: #e8e8e8 !important;
                border-color: #666 !important;
            }

            /* Icons - hide in print */
            .bi,
            svg,
            i[class*="bi-"] {
                display: none !important;
            }

            /* Text adjustments */
            .text-muted {
                color: #666 !important;
            }

            .fw-semibold,
            .fw-bold {
                font-weight: bold !important;
            }

            /* Filter card - hide */
            .card-header.bg-gradient,
            .card:first-of-type {
                display: none !important;
            }
        }
    </style>
@endsection
