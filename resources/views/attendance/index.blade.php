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
                            <label for="attendance_date" class="form-label fw-semibold mb-2">
                                <svg class="me-1" width="14" height="14" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" />
                                </svg>
                                From
                            </label>
                            <input type="date" id="attendance_date" name="attendance_date" class="form-control"
                                value="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}" required>
                        </div>

                    </div>

                    {{-- Action Buttons --}}
                    <div class="d-flex flex-wrap gap-3 mt-4 pt-3 border-top">
                        <button type="submit" class="btn btn-primary btn-lg px-4 shadow-sm" id="getEmployeesBtn">
                            <svg class="me-2" width="18" height="18" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                            </svg>
                            Get Attendance Records
                        </button>
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
                    <div class="col">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary bg-opacity-10 rounded-3 p-2 me-3">
                                <svg width="24" height="24" class="text-primary" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                                    <path fill-rule="evenodd"
                                        d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" />
                                </svg>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold">Attendance Records</h5>
                                @if ($showResults && $hasResults)
                                    <small class="text-muted">
                                        <span
                                            class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-1 mt-1">
                                            {{ count($attendanceRecords) }} employees found
                                        </span>
                                    </small>
                                @endif
                            </div>
                        </div>
                    </div>
                    @if ($showResults && $hasResults)
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
                    @endif
                </div>
            </div>

            <div class="card-body p-0">
                @if (!$showResults)
                    {{-- Empty State (Initial) --}}
                    <div class="text-center py-5 px-4" id="emptyState">
                        <div class="mb-4">
                            <div class="bg-primary bg-opacity-10 d-inline-flex rounded-circle p-4">
                                <svg width="64" height="64" class="text-primary" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                                    <path fill-rule="evenodd"
                                        d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm9 4a1 1 0 10-2 0v6a1 1 0 102 0V9zm-3 2a1 1 0 10-2 0v4a1 1 0 102 0v-4zm-3 3a1 1 0 10-2 0v1a1 1 0 102 0v-1z" />
                                </svg>
                            </div>
                        </div>
                        <h4 class="fw-bold mb-3">Ready to View Attendance</h4>
                        <p class="text-muted mb-4 lead">Select a branch and date from the filters above, then click
                            <strong>"Get Attendance Records"</strong> to display employee attendance data.
                        </p>
                        <div class="d-flex justify-content-center gap-2 flex-wrap">
                            <span class="badge border px-3 py-2"
                                style="background-color: var(--bs-secondary-bg); color: var(--bs-body-color);">
                                <svg class="me-1" width="14" height="14" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" />
                                </svg>
                                Filter by location
                            </span>
                            <span class="badge border px-3 py-2"
                                style="background-color: var(--bs-secondary-bg); color: var(--bs-body-color);">
                                <svg class="me-1" width="14" height="14" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" />
                                </svg>
                                Real-time updates
                            </span>
                            <span class="badge border px-3 py-2"
                                style="background-color: var(--bs-secondary-bg); color: var(--bs-body-color);">
                                <svg class="me-1" width="14" height="14" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" />
                                </svg>
                                Export & print ready
                            </span>
                        </div>
                    </div>
                @elseif(!$hasResults)
                    {{-- No Results State --}}
                    <div class="text-center py-5 px-4" id="noResultsState">
                        <div class="mb-4">
                            <div class="bg-warning bg-opacity-10 d-inline-flex rounded-circle p-4">
                                <svg width="64" height="64" class="text-warning" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" />
                                </svg>
                            </div>
                        </div>
                        <h4 class="fw-bold mb-3">No Attendance Records Found</h4>
                        <p class="text-muted mb-4">We couldn't find any attendance records matching your selected filters.
                            Try adjusting your search criteria.</p>
                        <button type="button" class="btn btn-primary px-4" id="clearResultsBtn">
                            <svg class="me-2" width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" />
                            </svg>
                            Reset Filters & Try Again
                        </button>
                    </div>
                @else
                    {{-- Attendance Table --}}
                    <div class="table-responsive">
                        <table class="table table-hover table-borderless align-middle mb-0">
                            <thead class="border-bottom" style="background-color: var(--bs-tertiary-bg);">
                                <tr class="text-uppercase small fw-semibold text-muted">
                                    <th scope="col" class="ps-4 py-3">System ID</th>
                                    <th scope="col" class="py-3">Employee ID</th>
                                    <th scope="col" class="py-3">Employee Name</th>
                                    <th scope="col" class="py-3" style="width: 160px;">Shift</th>
                                    <th scope="col" class="py-3 d-none d-md-table-cell">Clock In</th>
                                    <th scope="col" class="py-3 d-none d-md-table-cell text-center">In Status</th>
                                    <th scope="col" class="py-3 d-none d-md-table-cell">Clock Out</th>
                                    <th scope="col" class="py-3 d-none d-md-table-cell text-center">Out Status</th>
                                    <th scope="col" class="py-3 d-none d-lg-table-cell text-center">Working Hours</th>
                                    <th scope="col" class="py-3 pe-4 text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($attendanceRecords as $record)
                                    <tr class="border-bottom">
                                        {{-- System ID --}}
                                        <td class="ps-4 py-3">
                                            <span class="badge text-muted border fw-normal"
                                                style="background-color: var(--bs-secondary-bg);">{{ $record->system_id }}</span>
                                        </td>

                                        {{-- Employee ID --}}
                                        <td class="py-3">
                                            <code class="px-2 py-1 rounded small"
                                                style="background-color: var(--bs-secondary-bg); color: var(--bs-body-color);">{{ $record->employee_id }}</code>
                                        </td>

                                        {{-- Employee Name --}}
                                        <td class="py-3">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3"
                                                    style="width: 40px; height: 40px;">
                                                    <span
                                                        class="fw-bold text-primary">{{ substr($record->employee_name, 0, 1) }}</span>
                                                </div>
                                                <div>
                                                    <div class="fw-semibold">{{ $record->employee_name }}</div>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- Shift (Fixed Width, Two-Line Display) --}}
                                        <td class="py-3" style="width: 160px;">
                                            @php
                                                // Extract shift name and time from format "Morning (9:00 AM - 5:00 PM)"
                                                preg_match('/^(.+?)\s*\((.+?)\)$/', $record->shift, $matches);
                                                $shiftName = $matches[1] ?? $record->shift;
                                                $shiftTime = $matches[2] ?? '';
                                            @endphp
                                            <div class="d-flex align-items-start">
                                                <svg class="me-2 mt-1 flex-shrink-0 text-primary" width="14"
                                                    height="14" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" />
                                                </svg>
                                                <div style="line-height: 1.3;">
                                                    <div class="fw-medium" style="font-size: 0.813rem;">
                                                        {{ $shiftName }}</div>
                                                    @if ($shiftTime)
                                                        <div class="text-muted"
                                                            style="font-size: 0.688rem; white-space: nowrap;">
                                                            {{ $shiftTime }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>

                                        {{-- Clock In --}}
                                        <td class="py-3 d-none d-md-table-cell">
                                            <span class="fw-medium">{{ $record->clock_in ?? '—' }}</span>
                                        </td>

                                        {{-- Clock In Status --}}
                                        <td class="py-3 d-none d-md-table-cell text-center">
                                            @if ($record->clock_in_status === 'on-time')
                                                <span
                                                    class="badge rounded-pill bg-success-subtle text-success border border-success px-3 py-2">
                                                    <svg class="me-1" width="12" height="12"
                                                        fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" />
                                                    </svg>
                                                    On Time
                                                </span>
                                            @elseif($record->clock_in_status === 'late')
                                                <span
                                                    class="badge rounded-pill bg-warning-subtle text-warning border border-warning px-3 py-2">
                                                    <svg class="me-1" width="12" height="12"
                                                        fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" />
                                                    </svg>
                                                    Late
                                                </span>
                                            @elseif($record->clock_in_status === 'missing')
                                                <span
                                                    class="badge rounded-pill bg-danger-subtle text-danger border border-danger px-3 py-2">
                                                    <svg class="me-1" width="12" height="12"
                                                        fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" />
                                                    </svg>
                                                    Missing
                                                </span>
                                            @else
                                                <span
                                                    class="badge rounded-pill bg-secondary-subtle text-secondary border border-secondary px-3 py-2">—</span>
                                            @endif
                                        </td>

                                        {{-- Clock Out --}}
                                        <td class="py-3 d-none d-md-table-cell">
                                            <span class="fw-medium">{{ $record->clock_out ?? '—' }}</span>
                                        </td>

                                        {{-- Clock Out Status --}}
                                        <td class="py-3 d-none d-md-table-cell text-center">
                                            @if ($record->clock_out_status === 'on-time')
                                                <span
                                                    class="badge rounded-pill bg-success-subtle text-success border border-success px-3 py-2">
                                                    <svg class="me-1" width="12" height="12"
                                                        fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" />
                                                    </svg>
                                                    On Time
                                                </span>
                                            @elseif($record->clock_out_status === 'early')
                                                <span
                                                    class="badge rounded-pill bg-warning-subtle text-warning border border-warning px-3 py-2">
                                                    <svg class="me-1" width="12" height="12"
                                                        fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" />
                                                    </svg>
                                                    Early
                                                </span>
                                            @elseif($record->clock_out_status === 'missing')
                                                <span
                                                    class="badge rounded-pill bg-danger-subtle text-danger border border-danger px-3 py-2">
                                                    <svg class="me-1" width="12" height="12"
                                                        fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" />
                                                    </svg>
                                                    Missing
                                                </span>
                                            @else
                                                <span
                                                    class="badge rounded-pill bg-secondary-subtle text-secondary border border-secondary px-3 py-2">—</span>
                                            @endif
                                        </td>

                                        {{-- Working Hours --}}
                                        <td class="py-3 d-none d-lg-table-cell text-center">
                                            <span class="fw-semibold text-primary">
                                                {{ $record->working_hours }}
                                            </span>
                                        </td>

                                        {{-- Status --}}
                                        <td class="py-3 pe-4 text-center">
                                            @if ($record->status === 'full day')
                                                <span
                                                    class="badge rounded-pill bg-success text-white px-3 py-2 fw-semibold">
                                                    <svg class="me-1" width="14" height="14"
                                                        fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" />
                                                    </svg>
                                                    Full Day
                                                </span>
                                            @elseif($record->status === 'half day')
                                                <span
                                                    class="badge rounded-pill bg-primary text-white px-3 py-2 fw-semibold">
                                                    <svg class="me-1" width="14" height="14"
                                                        fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586L7.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 10.586V7z" />
                                                    </svg>
                                                    Half Day
                                                </span>
                                            @elseif($record->status === 'absent')
                                                <span
                                                    class="badge rounded-pill bg-danger text-white px-3 py-2 fw-semibold">
                                                    <svg class="me-1" width="14" height="14"
                                                        fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" />
                                                    </svg>
                                                    Absent
                                                </span>
                                            @else
                                                <span
                                                    class="badge rounded-pill bg-secondary text-white px-3 py-2 fw-semibold">—</span>
                                            @endif
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
                @endif
            </div>
        </div>

    </div>

    {{-- Enhanced JavaScript --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Select2
            if (typeof $.fn.select2 !== 'undefined') {
                // Initialize each select2 individually with proper configuration
                $('#branch_id').select2({
                    theme: 'bootstrap-5',
                    width: '100%',
                    placeholder: 'Choose branch...',
                    allowClear: false
                });

                $('#department_id').select2({
                    theme: 'bootstrap-5',
                    width: '100%',
                    placeholder: 'All departments',
                    allowClear: true
                });

                $('#section_id').select2({
                    theme: 'bootstrap-5',
                    width: '100%',
                    placeholder: 'All sections',
                    allowClear: true
                });
            }

            // Form elements
            const form = document.getElementById('attendanceFilterForm');
            const getEmployeesBtn = document.getElementById('getEmployeesBtn');
            const clearFiltersBtn = document.getElementById('clearFiltersBtn');
            const clearResultsBtn = document.getElementById('clearResultsBtn');

            // Form submission with loading state
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                if (!form.checkValidity()) {
                    e.stopPropagation();
                    form.classList.add('was-validated');
                    return;
                }

                // Show loading state
                const originalContent = getEmployeesBtn.innerHTML;
                getEmployeesBtn.disabled = true;
                getEmployeesBtn.innerHTML = `
            <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
            Loading Records...
        `;

                // Simulate AJAX delay (Replace with actual AJAX call in production)
                setTimeout(function() {
                    getEmployeesBtn.disabled = false;
                    getEmployeesBtn.innerHTML = originalContent;

                    // In production, redirect or update DOM with results
                    console.log('Form submitted with:', {
                        branch_id: document.getElementById('branch_id').value,
                        department_id: document.getElementById('department_id').value,
                        section_id: document.getElementById('section_id').value,
                        attendance_date: document.getElementById('attendance_date').value
                    });
                }, 1500);
            });

            // Clear filters
            clearFiltersBtn.addEventListener('click', function() {
                form.reset();
                form.classList.remove('was-validated');
                document.getElementById('attendance_date').value = '{{ date('Y-m-d') }}';

                // Reset Select2 dropdowns
                if (typeof $.fn.select2 !== 'undefined') {
                    $('.select2').val(null).trigger('change');
                }
            });

            // Clear results (if button exists)
            if (clearResultsBtn) {
                clearResultsBtn.addEventListener('click', function() {
                    clearFiltersBtn.click();
                });
            }

            // Add smooth scroll animation for form submission
            form.addEventListener('submit', function() {
                setTimeout(() => {
                    const resultsCard = document.querySelector('.card.shadow-sm:last-of-type');
                    if (resultsCard) {
                        resultsCard.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                }, 100);
            });
        });
    </script>

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
</style>@endsection
