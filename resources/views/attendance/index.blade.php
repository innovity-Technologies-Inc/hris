@extends('structure.master')

@section('content')

    {{-- Page Header --}}
    <div class="border-bottom mb-4" style="background-color: var(--bs-secondary-bg);">
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
    </div>

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
                <form id="attendanceFilterForm">
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
                        </div>--}}

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
                                value="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}">
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
                                   value="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}">
                        </div>

                    </div>

                    {{-- Action Buttons --}}
                    <div class="d-flex flex-wrap gap-3 mt-4 pt-3 border-top">
                        <button type="button" class="btn btn-outline-secondary btn-lg px-4" id="clearFiltersBtn">
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
                                <button type="button" class="btn btn-outline-primary" onclick="window.print()"
                                    title="Print">
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

            <div class="card-body p-0">

                    {{-- Attendance Table --}}
                    <div class="table-responsive">
                        <table class="table table-hover table-borderless align-middle mb-0">
                            <thead class="border-bottom" style="background-color: var(--bs-tertiary-bg);">
                                <tr class="text-uppercase small fw-semibold text-muted">
                                    <th scope="col" class="py-3">Employee ID</th>
                                    <th scope="col" class="py-3">Employee Name</th>
                                    <th scope="col" class="py-3" style="width: 160px;">Shift</th>
                                    <th scope="col" class="py-3 d-none d-md-table-cell">Clock In</th>
                                    <th scope="col" class="py-3 d-none d-md-table-cell text-center">In Status</th>
                                    <th scope="col" class="py-3 d-none d-md-table-cell">Clock Out</th>
                                    <th scope="col" class="py-3 d-none d-md-table-cell text-center">Out Status</th>
                                    <th scope="col" class="py-3 d-none d-lg-table-cell text-center">Working Time</th>
                                    <th scope="col" class="py-3 d-none d-lg-table-cell text-center">Late Count</th>
                                    <th scope="col" class="py-3 d-none d-lg-table-cell text-center">Early Out Count</th>
                                    <th scope="col" class="py-3 d-none d-lg-table-cell text-center">Overtime</th>
                                    <th scope="col" class="py-3 d-none d-lg-table-cell text-center">Work Type</th>
                                    <th scope="col" class="py-3 pe-4 text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($attendanceRecords as $record)
                                    <tr class="border-bottom">
                                        <td class="ps-4 py-3">
                                            <span class="badge text-muted border fw-normal"
                                                style="background-color: var(--bs-secondary-bg);">{{ $record->getEmployee->applicant_id }}</span>
                                        </td>

                                        <td class="py-3">
                                            <code class="px-2 py-1 rounded small"
                                                style="background-color: var(--bs-secondary-bg); color: var(--bs-body-color);">{{ $record->getEmployee->full_name }}</code>
                                        </td>

                                        {{-- Shift (Fixed Width, Two-Line Display) --}}
                                        {{--<td class="py-3" style="width: 160px;">
                                            <div class="d-flex align-items-start">
                                                <svg class="me-2 mt-1 flex-shrink-0 text-primary" width="14"
                                                    height="14" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" />
                                                </svg>
                                                <div style="line-height: 1.3;">
                                                    <div class="fw-medium" style="font-size: 0.813rem;">
                                                        {{ $record->getShift->name }}</div>
                                                        <div class="text-muted"
                                                            style="font-size: 0.688rem; white-space: nowrap;">
                                                            {{ \Carbon\Carbon::parse($record->getShift->clock_in_time)->format('h:i A') }} -
                                                            {{ \Carbon\Carbon::parse($record->getShift->clock_out_time)->format('h:i A') }}</div>
                                                </div>
                                            </div>
                                        </td>--}}

                                        {{-- Clock In --}}
                                        <td class="py-3 d-none d-md-table-cell">
                                            <span class="fw-medium">{{ $record->in_time ?? '—' }}</span>
                                        </td>

                                        {{-- Clock In Status --}}
                                        <td class="py-3 pe-4 text-center">
                                                <span
                                                    class="badge rounded-pill @if($record->in_status == 'On-Time') bg-success @elseif($record->in_status == 'Excessive-Late') bg-danger @elseif($record->in_status == 'Late') bg-warning @endif  text-white px-3 py-2 fw-semibold">
                                                    {{($record->in_status)}}
                                                </span>
                                        </td>
                                        {{-- Clock Out --}}
                                        <td class="py-3 d-none d-md-table-cell">
                                            <span class="fw-medium">{{ $record->out_time ?? '—' }}</span>
                                        </td>

                                        {{-- Clock Out Status --}}
                                        <td class="py-3 pe-4 text-center">
                                                <span
                                                    class="badge rounded-pill @if($record->out_status == 'On-Time') bg-success @elseif($record->out_status == 'Early-Exit') bg-danger @endif  text-white px-3 py-2 fw-semibold">
                                                    {{($record->out_status)}}
                                                </span>
                                        </td>
                                        {{-- Working Hours --}}
                                        <td class="py-3 d-none d-lg-table-cell text-center">
                                            <span class="fw-semibold text-primary">
                                                {{ $record->working_time }}
                                            </span>
                                        </td>

                                        <td class="py-3 d-none d-lg-table-cell text-center">
                                            <span class="fw-semibold text-primary">
                                                {{ $record->late_count }}
                                            </span>
                                        </td>

                                        <td class="py-3 d-none d-lg-table-cell text-center">
                                            <span class="fw-semibold text-primary">
                                                {{ $record->early_out_count }}
                                            </span>
                                        </td>

                                        <td class="py-3 d-none d-lg-table-cell text-center">
                                            <span class="fw-semibold text-primary">
                                                {{ $record->overtime }}
                                            </span>
                                        </td>

                                        <td class="py-3 d-none d-lg-table-cell text-center">
                                            <span class="fw-semibold text-primary">
                                                {{ $record->work_type }}
                                            </span>
                                        </td>


                                        {{-- Status --}}
                                        <td class="py-3 pe-4 text-center">
                                                <span
                                                    class="badge rounded-pill @if($record->attendance_status == 'Present') bg-success @elseif($record->attendance_status == 'Absent') bg-danger @endif  text-white px-3 py-2 fw-semibold">
                                                    {{($record->attendance_status)}}
                                                </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Table Footer with Pagination --}}
                    <div class="border-top p-4" style="background-color: var(--bs-tertiary-bg);">
                        <div class="row align-items-center g-3">
                            <div class="col-md-6">
                                <div class="text-muted small">
                                    Showing <strong>1</strong> to <strong>{{ count($attendanceRecords) }}</strong> of
                                    <strong>{{ count($attendanceRecords) }}</strong> total records
                                </div>
                            </div>
                            <div class="col-md-6">
                                <nav aria-label="Attendance pagination">
                                    <ul class="pagination pagination-sm justify-content-md-end mb-0">
                                        <li class="page-item disabled">
                                            <a class="page-link" href="#" tabindex="-1">
                                                <svg width="14" height="14" fill="currentColor"
                                                    viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" />
                                                </svg>
                                            </a>
                                        </li>
                                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                                        <li class="page-item">
                                            <a class="page-link" href="#">
                                                <svg width="14" height="14" fill="currentColor"
                                                    viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" />
                                                </svg>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
            </div>
        </div>

    </div>

    {{-- Print Styles --}}
    <style media="print">
        /* Hide interactive elements when printing */
        .btn,
        .pagination,
        .border-top.p-4,
        .card-header .col-auto {
            display: none !important;
        }

        /* Adjust card styling for print */
        .card {
            box-shadow: none !important;
            border: 1px solid #dee2e6 !important;
        }

        /* Optimize table font size */
        .table {
            font-size: 9pt;
        }

        /* Show all columns in print */
        .d-none.d-md-table-cell,
        .d-none.d-lg-table-cell {
            display: table-cell !important;
        }

        /* Remove page header background */
        .bg-light {
            background-color: #fff !important;
        }
    </style>

    {{-- Additional Dark Theme Support --}}
    <style>
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
</style>

@endsection
