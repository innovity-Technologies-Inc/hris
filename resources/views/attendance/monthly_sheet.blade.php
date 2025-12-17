@extends('structure.master')

@section('content')
    @php
        // ========== MONTHLY ATTENDANCE SUMMARY DATA ==========

        $branches = [
            (object) ['id' => 1, 'name' => 'Head Office'],
            (object) ['id' => 2, 'name' => 'Dhaka Branch'],
            (object) ['id' => 3, 'name' => 'Chittagong Branch'],
        ];

        $departments = [
            (object) ['id' => 1, 'name' => 'Engineering'],
            (object) ['id' => 2, 'name' => 'Human Resources'],
            (object) ['id' => 3, 'name' => 'Sales & Marketing'],
            (object) ['id' => 4, 'name' => 'Finance'],
        ];

        $sections = [
            (object) ['id' => 1, 'name' => 'Backend Development'],
            (object) ['id' => 2, 'name' => 'Frontend Development'],
            (object) ['id' => 3, 'name' => 'Recruitment'],
            (object) ['id' => 4, 'name' => 'Payroll'],
        ];

        // Monthly attendance aggregated data for each employee
        $monthlyAttendance = [
            (object) [
                'system_id' => 1001,
                'employee_id' => 'EMP-2024-001',
                'employee_name' => 'Mohammad Rahman',
                'shift' => 'Morning (9:00 AM - 5:00 PM)',
                'total_present_days' => 22,
                'total_absent_days' => 2,
                'total_working_days' => 24,
                'avg_working_hours' => '08:12',
            ],
            (object) [
                'system_id' => 1002,
                'employee_id' => 'EMP-2024-002',
                'employee_name' => 'Fatima Khatun',
                'shift' => 'Morning (9:00 AM - 5:00 PM)',
                'total_present_days' => 20,
                'total_absent_days' => 4,
                'total_working_days' => 24,
                'avg_working_hours' => '07:58',
            ],
            (object) [
                'system_id' => 1003,
                'employee_id' => 'EMP-2024-003',
                'employee_name' => 'Ahmed Hassan',
                'shift' => 'Morning (9:00 AM - 5:00 PM)',
                'total_present_days' => 21,
                'total_absent_days' => 3,
                'total_working_days' => 24,
                'avg_working_hours' => '07:45',
            ],
            (object) [
                'system_id' => 1004,
                'employee_id' => 'EMP-2024-004',
                'employee_name' => 'Nusrat Jahan',
                'shift' => 'Morning (9:00 AM - 5:00 PM)',
                'total_present_days' => 18,
                'total_absent_days' => 6,
                'total_working_days' => 24,
                'avg_working_hours' => '07:30',
            ],
            (object) [
                'system_id' => 1005,
                'employee_id' => 'EMP-2024-005',
                'employee_name' => 'Karim Abdullah',
                'shift' => 'Evening (2:00 PM - 10:00 PM)',
                'total_present_days' => 23,
                'total_absent_days' => 1,
                'total_working_days' => 24,
                'avg_working_hours' => '08:18',
            ],
            (object) [
                'system_id' => 1006,
                'employee_id' => 'EMP-2024-006',
                'employee_name' => 'Sabrina Akter',
                'shift' => 'Morning (9:00 AM - 5:00 PM)',
                'total_present_days' => 22,
                'total_absent_days' => 2,
                'total_working_days' => 24,
                'avg_working_hours' => '08:05',
            ],
            (object) [
                'system_id' => 1007,
                'employee_id' => 'EMP-2024-007',
                'employee_name' => 'Rakib Hossain',
                'shift' => 'Morning (9:00 AM - 5:00 PM)',
                'total_present_days' => 24,
                'total_absent_days' => 0,
                'total_working_days' => 24,
                'avg_working_hours' => '08:15',
            ],
            (object) [
                'system_id' => 1008,
                'employee_id' => 'EMP-2024-008',
                'employee_name' => 'Ayesha Siddiqua',
                'shift' => 'Morning (9:00 AM - 5:00 PM)',
                'total_present_days' => 21,
                'total_absent_days' => 3,
                'total_working_days' => 24,
                'avg_working_hours' => '07:52',
            ],
        ];

        $currentMonth = date('F Y');
        $showResults = true;
        $hasResults = count($monthlyAttendance) > 0;
    @endphp

    {{-- Page Header --}}
    <div class="border-bottom mb-4" style="background-color: var(--bs-secondary-bg);">
        <div class="container-fluid px-4 py-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-1 fw-bold">Monthly Attendance Summary</h3>
                    <p class="mb-0 text-muted small">View aggregated employee attendance data by month</p>
                </div>
                <div class="text-end">
                    <div class="text-muted small mb-1">Current Month</div>
                    <div class="fw-semibold">{{ $currentMonth }}</div>
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
                        <small class="opacity-75">Select criteria to view monthly attendance summary</small>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">
                <form id="monthlyFilterForm" class="needs-validation" novalidate>
                    <div class="row g-4">
                        {{-- Branch Select --}}
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

                        {{-- Department Select --}}
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

                        {{-- Section Select --}}
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
                        </div>

                        {{-- Month/Year Picker --}}
                        <div class="col-md-6 col-xl-3">
                            <label for="month_year" class="form-label fw-semibold mb-2">
                                <svg class="me-1" width="14" height="14" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" />
                                </svg>
                                Month/Year
                            </label>
                            <input type="month" id="month_year" name="month_year" class="form-control"
                                value="{{ date('Y-m') }}" required>
                            <div class="form-text">Required field</div>
                        </div>

                    </div>

                    {{-- Action Buttons --}}
                    <div class="d-flex flex-wrap gap-3 mt-4 pt-3 border-top">
                        <button type="submit" class="btn btn-primary btn-lg px-4 shadow-sm" id="getMonthlyBtn">
                            <svg class="me-2" width="18" height="18" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z" />
                            </svg>
                            Get Monthly Summary
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
                                    <path
                                        d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9zM4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" />
                                </svg>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold">Monthly Attendance Summary</h5>
                                @if ($showResults && $hasResults)
                                    <small class="text-muted">
                                        <span
                                            class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-1 mt-1">
                                            {{ count($monthlyAttendance) }} employees found
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
                                    <path
                                        d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9zM4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm9 4a1 1 0 10-2 0v6a1 1 0 102 0V9zm-3 2a1 1 0 10-2 0v4a1 1 0 102 0v-4zm-3 3a1 1 0 10-2 0v1a1 1 0 102 0v-1z" />
                                </svg>
                            </div>
                        </div>
                        <h4 class="fw-bold mb-3">Ready to View Monthly Summary</h4>
                        <p class="text-muted mb-4 lead">Select a branch and month from the filters above, then click
                            <strong>"Get Monthly Summary"</strong> to display employee attendance summary data.
                        </p>
                        <div class="d-flex justify-content-center gap-2 flex-wrap">
                            <span class="badge border px-3 py-2"
                                style="background-color: var(--bs-secondary-bg); color: var(--bs-body-color);">
                                <svg class="me-1" width="14" height="14" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" />
                                </svg>
                                Aggregate data by month
                            </span>
                            <span class="badge border px-3 py-2"
                                style="background-color: var(--bs-secondary-bg); color: var(--bs-body-color);">
                                <svg class="me-1" width="14" height="14" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" />
                                </svg>
                                Track attendance patterns
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
                    {{-- Monthly Summary Table --}}
                    <div class="table-responsive">
                        <table class="table table-hover table-borderless align-middle mb-0">
                            <thead class="border-bottom" style="background-color: var(--bs-tertiary-bg);">
                                <tr class="text-uppercase small fw-semibold text-muted">
                                    <th scope="col" class="ps-4 py-3">System ID</th>
                                    <th scope="col" class="py-3">Employee ID</th>
                                    <th scope="col" class="py-3">Employee Name</th>
                                    <th scope="col" class="py-3" style="width: 160px;">Shift</th>
                                    <th scope="col" class="py-3 text-center">
                                        <span class="text-success">Present Days</span>
                                    </th>
                                    <th scope="col" class="py-3 text-center">
                                        <span class="text-danger">Absent Days</span>
                                    </th>
                                    <th scope="col" class="py-3 text-center">
                                        <span class="text-primary">Working Days</span>
                                    </th>
                                    <th scope="col" class="py-3 pe-4 text-center">
                                        <span class="text-info">Avg Hrs/Day</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($monthlyAttendance as $record)
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

                                        {{-- Shift --}}
                                        <td class="py-3" style="width: 160px;">
                                            @php
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

                                        {{-- Total Present Days --}}
                                        <td class="py-3 text-center">
                                            <span class="badge bg-success-subtle text-success border border-success px-3 py-2 fw-semibold">
                                                {{ $record->total_present_days }}
                                            </span>
                                        </td>

                                        {{-- Total Absent Days --}}
                                        <td class="py-3 text-center">
                                            <span class="badge bg-danger-subtle text-danger border border-danger px-3 py-2 fw-semibold">
                                                {{ $record->total_absent_days }}
                                            </span>
                                        </td>

                                        {{-- Total Working Days --}}
                                        <td class="py-3 text-center">
                                            <span class="badge bg-primary-subtle text-primary border border-primary px-3 py-2 fw-semibold">
                                                {{ $record->total_working_days }}
                                            </span>
                                        </td>

                                        {{-- Average Working Hours --}}
                                        <td class="py-3 pe-4 text-center">
                                            <span class="fw-semibold text-info" style="font-size: 1.1rem;">
                                                {{ $record->avg_working_hours }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Summary Statistics Footer --}}
                    <div class="border-top p-4" style="background-color: var(--bs-tertiary-bg);">
                        <div class="row g-4">
                            <div class="col-md-6 col-lg-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-success bg-opacity-10 rounded-3 p-3 me-3">
                                        <svg width="28" height="28" class="text-success" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-muted small mb-1">Total Present</div>
                                        <div class="fw-bold" style="font-size: 1.4rem;">
                                            {{ array_sum(array_map(fn($r) => $r->total_present_days, $monthlyAttendance)) }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-danger bg-opacity-10 rounded-3 p-3 me-3">
                                        <svg width="28" height="28" class="text-danger" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-muted small mb-1">Total Absent</div>
                                        <div class="fw-bold" style="font-size: 1.4rem;">
                                            {{ array_sum(array_map(fn($r) => $r->total_absent_days, $monthlyAttendance)) }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 rounded-3 p-3 me-3">
                                        <svg width="28" height="28" class="text-primary" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM4 9a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zm0 4a1 1 0 011-1h6a1 1 0 110 2H5a1 1 0 01-1-1z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-muted small mb-1">Total Employees</div>
                                        <div class="fw-bold" style="font-size: 1.4rem;">
                                            {{ count($monthlyAttendance) }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-lg-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-info bg-opacity-10 rounded-3 p-3 me-3">
                                        <svg width="28" height="28" class="text-info" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M11 3a1 1 0 10-2 0v1a1 1 0 102 0V3zM15.657 5.343a1 1 0 00-1.414-1.414l-.707.707a1 1 0 001.414 1.414l.707-.707zM18 10a1 1 0 01-1 1h-1a1 1 0 110-2h1a1 1 0 011 1zm-1-9h-1a1 1 0 110 2h1V1zM5.343 15.657a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414l.707.707zM5 10a1 1 0 01-1 1H3a1 1 0 110-2h1a1 1 0 011 1zM8 16v-1a1 1 0 10-2 0v1h2z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-muted small mb-1">Attendance Rate</div>
                                        <div class="fw-bold" style="font-size: 1.4rem;">
                                            @php
                                                $totalDays = array_sum(array_map(fn($r) => $r->total_working_days, $monthlyAttendance));
                                                $totalPresent = array_sum(array_map(fn($r) => $r->total_present_days, $monthlyAttendance));
                                                $rate = $totalDays > 0 ? round(($totalPresent / $totalDays) * 100) : 0;
                                            @endphp
                                            {{ $rate }}%
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Pagination --}}
                    <div class="border-top p-4" style="background-color: var(--bs-tertiary-bg);">
                        <div class="row align-items-center g-3">
                            <div class="col-md-6">
                                <div class="text-muted small">
                                    Showing <strong>1</strong> to <strong>{{ count($monthlyAttendance) }}</strong> of
                                    <strong>{{ count($monthlyAttendance) }}</strong> total employees
                                </div>
                            </div>
                            <div class="col-md-6">
                                <nav aria-label="Monthly summary pagination">
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
            const form = document.getElementById('monthlyFilterForm');
            const getMonthlyBtn = document.getElementById('getMonthlyBtn');
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
                const originalContent = getMonthlyBtn.innerHTML;
                getMonthlyBtn.disabled = true;
                getMonthlyBtn.innerHTML = `
                    <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                    Loading Summary...
                `;

                // Simulate AJAX delay (Replace with actual AJAX call in production)
                setTimeout(function() {
                    getMonthlyBtn.disabled = false;
                    getMonthlyBtn.innerHTML = originalContent;

                    console.log('Form submitted with:', {
                        branch_id: document.getElementById('branch_id').value,
                        department_id: document.getElementById('department_id').value,
                        section_id: document.getElementById('section_id').value,
                        month_year: document.getElementById('month_year').value
                    });
                }, 1500);
            });

            // Clear filters
            clearFiltersBtn.addEventListener('click', function() {
                form.reset();
                form.classList.remove('was-validated');
                document.getElementById('month_year').value = '{{ date('Y-m') }}';

                // Reset Select2 dropdowns
                if (typeof $.fn.select2 !== 'undefined') {
                    $('.select2').val(null).trigger('change');
                }
            });

            // Clear results
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

    {{-- Additional Styles --}}
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

        /* Badge styling */
        .badge {
            font-weight: 500;
            letter-spacing: 0.3px;
        }

        /* Shift column specific styling */
        td[style*="width: 160px"] {
            vertical-align: middle;
        }
    </style>

@endsection
