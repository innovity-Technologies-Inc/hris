@extends('structure.master')

@section('content')
    @php
        // ========== EMPLOYEE DETAILS DATA ==========
        $employee = (object) [
            'system_id' => 1001,
            'employee_id' => 'EMP-2024-001',
            'name' => 'Mohammad Rahman',
            'designation' => 'Senior Software Engineer',
            'department' => 'Engineering',
            'branch' => 'Head Office',
            'image' =>
                'https://ui-avatars.com/api/?name=Mohammad+Rahman&size=200&background=4F46E5&color=fff&bold=true',
            'email' => 'mohammad.rahman@company.com',
            'phone' => '+880 1712-345678',
        ];

        // ========== MONTHLY ATTENDANCE RECORDS ==========
        $attendanceRecords = [
            (object) [
                'date' => '2024-11-01',
                'day' => 'Friday',
                'shift' => 'Morning',
                'shift_time' => '9:00 AM - 5:00 PM',
                'clock_in' => '08:55 AM',
                'clock_out' => '05:10 PM',
                'clock_in_status' => 'on-time',
                'clock_out_status' => 'on-time',
                'working_hours' => '08:15',
                'status' => 'full day',
            ],
            (object) [
                'date' => '2024-11-02',
                'day' => 'Saturday',
                'shift' => 'Morning',
                'shift_time' => '9:00 AM - 5:00 PM',
                'clock_in' => '09:05 AM',
                'clock_out' => '05:00 PM',
                'clock_in_status' => 'late',
                'clock_out_status' => 'on-time',
                'working_hours' => '07:55',
                'status' => 'full day',
            ],
            (object) [
                'date' => '2024-11-03',
                'day' => 'Sunday',
                'shift' => 'Morning',
                'shift_time' => '9:00 AM - 5:00 PM',
                'clock_in' => '08:58 AM',
                'clock_out' => '05:05 PM',
                'clock_in_status' => 'on-time',
                'clock_out_status' => 'on-time',
                'working_hours' => '08:07',
                'status' => 'full day',
            ],
            (object) [
                'date' => '2024-11-04',
                'day' => 'Monday',
                'shift' => 'Morning',
                'shift_time' => '9:00 AM - 5:00 PM',
                'clock_in' => null,
                'clock_out' => null,
                'clock_in_status' => 'missing',
                'clock_out_status' => 'missing',
                'working_hours' => '00:00',
                'status' => 'absent',
            ],
            (object) [
                'date' => '2024-11-05',
                'day' => 'Tuesday',
                'shift' => 'Morning',
                'shift_time' => '9:00 AM - 5:00 PM',
                'clock_in' => '08:50 AM',
                'clock_out' => '05:02 PM',
                'clock_in_status' => 'on-time',
                'clock_out_status' => 'on-time',
                'working_hours' => '08:12',
                'status' => 'full day',
            ],
            (object) [
                'date' => '2024-11-06',
                'day' => 'Wednesday',
                'shift' => 'Morning',
                'shift_time' => '9:00 AM - 5:00 PM',
                'clock_in' => '09:00 AM',
                'clock_out' => '01:00 PM',
                'clock_in_status' => 'on-time',
                'clock_out_status' => 'early',
                'working_hours' => '04:00',
                'status' => 'half day',
            ],
            (object) [
                'date' => '2024-11-07',
                'day' => 'Thursday',
                'shift' => 'Morning',
                'shift_time' => '9:00 AM - 5:00 PM',
                'clock_in' => '08:55 AM',
                'clock_out' => '05:15 PM',
                'clock_in_status' => 'on-time',
                'clock_out_status' => 'on-time',
                'working_hours' => '08:20',
                'status' => 'full day',
            ],
            (object) [
                'date' => '2024-11-08',
                'day' => 'Friday',
                'shift' => 'Morning',
                'shift_time' => '9:00 AM - 5:00 PM',
                'clock_in' => '09:15 AM',
                'clock_out' => '05:00 PM',
                'clock_in_status' => 'late',
                'clock_out_status' => 'on-time',
                'working_hours' => '07:45',
                'status' => 'full day',
            ],
            (object) [
                'date' => '2024-11-09',
                'day' => 'Saturday',
                'shift' => 'Morning',
                'shift_time' => '9:00 AM - 5:00 PM',
                'clock_in' => '08:58 AM',
                'clock_out' => '05:10 PM',
                'clock_in_status' => 'on-time',
                'clock_out_status' => 'on-time',
                'working_hours' => '08:12',
                'status' => 'full day',
            ],
            (object) [
                'date' => '2024-11-10',
                'day' => 'Sunday',
                'shift' => 'Morning',
                'shift_time' => '9:00 AM - 5:00 PM',
                'clock_in' => '09:00 AM',
                'clock_out' => '05:00 PM',
                'clock_in_status' => 'on-time',
                'clock_out_status' => 'on-time',
                'working_hours' => '08:00',
                'status' => 'full day',
            ],
            (object) [
                'date' => '2024-11-11',
                'day' => 'Monday',
                'shift' => 'Morning',
                'shift_time' => '9:00 AM - 5:00 PM',
                'clock_in' => '08:52 AM',
                'clock_out' => '05:08 PM',
                'clock_in_status' => 'on-time',
                'clock_out_status' => 'on-time',
                'working_hours' => '08:16',
                'status' => 'full day',
            ],
            (object) [
                'date' => '2024-11-12',
                'day' => 'Tuesday',
                'shift' => 'Morning',
                'shift_time' => '9:00 AM - 5:00 PM',
                'clock_in' => '09:20 AM',
                'clock_out' => '05:00 PM',
                'clock_in_status' => 'late',
                'clock_out_status' => 'on-time',
                'working_hours' => '07:40',
                'status' => 'full day',
            ],
            (object) [
                'date' => '2024-11-13',
                'day' => 'Wednesday',
                'shift' => 'Morning',
                'shift_time' => '9:00 AM - 5:00 PM',
                'clock_in' => '08:55 AM',
                'clock_out' => '05:05 PM',
                'clock_in_status' => 'on-time',
                'clock_out_status' => 'on-time',
                'working_hours' => '08:10',
                'status' => 'full day',
            ],
            (object) [
                'date' => '2024-11-14',
                'day' => 'Thursday',
                'shift' => 'Morning',
                'shift_time' => '9:00 AM - 5:00 PM',
                'clock_in' => '09:00 AM',
                'clock_out' => '05:00 PM',
                'clock_in_status' => 'on-time',
                'clock_out_status' => 'on-time',
                'working_hours' => '08:00',
                'status' => 'full day',
            ],
            (object) [
                'date' => '2024-11-15',
                'day' => 'Friday',
                'shift' => 'Morning',
                'shift_time' => '9:00 AM - 5:00 PM',
                'clock_in' => '08:48 AM',
                'clock_out' => '05:12 PM',
                'clock_in_status' => 'on-time',
                'clock_out_status' => 'on-time',
                'working_hours' => '08:24',
                'status' => 'full day',
            ],
        ];

        // ========== FILTER OPTIONS ==========
        $companies = [
            (object) ['id' => 1, 'name' => 'ABC Corporation Ltd.'],
            (object) ['id' => 2, 'name' => 'XYZ Industries'],
            (object) ['id' => 3, 'name' => 'Tech Solutions Inc.'],
        ];

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

        $employees = [
            (object) ['id' => 1001, 'name' => 'Mohammad Rahman', 'employee_id' => 'EMP-2024-001', 'system_id' => 1001],
            (object) ['id' => 1002, 'name' => 'Fatima Khatun', 'employee_id' => 'EMP-2024-002', 'system_id' => 1002],
            (object) ['id' => 1003, 'name' => 'Ahmed Hassan', 'employee_id' => 'EMP-2024-003', 'system_id' => 1003],
            (object) ['id' => 1004, 'name' => 'Nusrat Jahan', 'employee_id' => 'EMP-2024-004', 'system_id' => 1004],
            (object) ['id' => 1005, 'name' => 'Karim Abdullah', 'employee_id' => 'EMP-2024-005', 'system_id' => 1005],
        ];

        $currentMonth = date('F Y');
        $showResults = true;
        $hasResults = count($attendanceRecords) > 0;

        // Calculate summary statistics
        $totalPresent = count(array_filter($attendanceRecords, fn($r) => $r->status !== 'absent'));
        $totalAbsent = count(array_filter($attendanceRecords, fn($r) => $r->status === 'absent'));
        $totalLate = count(array_filter($attendanceRecords, fn($r) => $r->clock_in_status === 'late'));
        $totalWorkingDays = count($attendanceRecords);
    @endphp

    {{-- Page Header --}}
    <div class="border-bottom mb-4" style="background-color: var(--bs-secondary-bg);">
        <div class="container-fluid px-4 py-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-1 fw-bold">Employee Attendance Sheet</h3>
                    <p class="mb-0 text-muted small">View detailed monthly attendance records for individual employees</p>
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
                        <small class="opacity-75">Select employee and month to view attendance records</small>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">
                <form id="employeeFilterForm" class="needs-validation" novalidate>
                    <div class="row g-4">

                        {{-- Company Select --}}
                        <div class="col-md-6 col-xl-3">
                            <label for="company_id" class="form-label fw-semibold mb-2">
                                <svg class="me-1" width="14" height="14" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" />
                                </svg>
                                Company
                            </label>
                            <select id="company_id" name="company_id" class="form-select select2">
                                <option value="">All companies</option>
                                @foreach ($companies as $company)
                                    <option value="{{ $company->id }}">{{ $company->name }}</option>
                                @endforeach
                            </select>
                            <div class="form-text">Optional filter</div>
                        </div>

                        {{-- Branch Select --}}
                        <div class="col-md-6 col-xl-3">
                            <label for="branch_id" class="form-label fw-semibold mb-2">
                                <svg class="me-1" width="14" height="14" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" />
                                </svg>
                                Branch
                            </label>
                            <select id="branch_id" name="branch_id" class="form-select select2">
                                <option value="">All branches</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                @endforeach
                            </select>
                            <div class="form-text">Optional filter</div>
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

                        {{-- System ID Input --}}
                        <div class="col-md-6 col-xl-3">
                            <label for="system_id" class="form-label fw-semibold mb-2">
                                <svg class="me-1" width="14" height="14" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 2a1 1 0 011 1v1.323l3.954 1.582 1.599-.8a1 1 0 01.894 1.79l-1.233.616 1.738 5.42a1 1 0 01-.285 1.05A3.989 3.989 0 0115 15a3.989 3.989 0 01-2.667-1.019 1 1 0 01-.285-1.05l1.715-5.349L11 6.477V16h2a1 1 0 110 2H7a1 1 0 110-2h2V6.477L6.237 7.582l1.715 5.349a1 1 0 01-.285 1.05A3.989 3.989 0 015 15a3.989 3.989 0 01-2.667-1.019 1 1 0 01-.285-1.05l1.738-5.42-1.233-.617a1 1 0 01.894-1.788l1.599.799L9 4.323V3a1 1 0 011-1z" />
                                </svg>
                                System ID
                            </label>
                            <input type="text" id="system_id" name="system_id" class="form-control"
                                placeholder="Enter system ID">
                            <div class="form-text">Optional filter</div>
                        </div>

                        {{-- Employee ID Input --}}
                        <div class="col-md-6 col-xl-3">
                            <label for="emp_id" class="form-label fw-semibold mb-2">
                                <svg class="me-1" width="14" height="14" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path
                                        d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9zM4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" />
                                </svg>
                                Employee ID
                            </label>
                            <input type="text" id="emp_id" name="emp_id" class="form-control"
                                placeholder="Enter employee ID">
                            <div class="form-text">Optional filter</div>
                        </div>

                        {{-- Employee Name Select --}}
                        <div class="col-md-6 col-xl-3">
                            <label for="employee_id" class="form-label fw-semibold mb-2">
                                <svg class="me-1" width="14" height="14" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" />
                                </svg>
                                Employee Name
                            </label>
                            <select id="employee_id" name="employee_id" class="form-select select2">
                                <option value="">Choose employee...</option>
                                @foreach ($employees as $emp)
                                    <option value="{{ $emp->id }}" {{ $emp->id == 1001 ? 'selected' : '' }}>
                                        {{ $emp->name }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text">Optional filter</div>
                        </div>

                        {{-- Month/Year Picker --}}
                        <div class="col-md-6 col-xl-3">
                            <label for="month_year" class="form-label fw-semibold mb-2">
                                <svg class="me-1" width="14" height="14" fill="currentColor"
                                    viewBox="0 0 20 20">
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
                        <button type="submit" class="btn btn-primary btn-lg px-4 shadow-sm" id="getRecordsBtn">
                            <svg class="me-2" width="18" height="18" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9zM4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5z" />
                            </svg>
                            Get Attendance Records
                        </button>
                        <button type="button" class="btn btn-outline-secondary btn-lg px-4" id="clearFiltersBtn">
                            <svg class="me-2" width="18" height="18" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" />
                            </svg>
                            Clear Filters
                        </button>
                    </div>
                </form>
            </div>
        </div>

        @if ($showResults && $hasResults)
            {{-- Employee Information Card --}}
            <div class="card border-0 shadow-sm mb-4 overflow-hidden">
                <div class="card-body p-0">
                    <div class="row g-0 align-items-stretch">
                        {{-- Left Side: Employee Image --}}
                        <div class="col-lg-3 col-md-4 d-flex align-items-center justify-content-center p-4 p-lg-5"
                            style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 320px;">
                            <div class="text-center w-100">
                                <div class="mb-3">
                                    <img src="{{ $employee->image }}" alt="{{ $employee->name }}"
                                        class="rounded-circle shadow-lg border-4 border-white"
                                        style="width: 130px; height: 130px; object-fit: cover;">
                                </div>
                                <h5 class="text-white fw-bold mb-2">{{ $employee->name }}</h5>
                                <p class="text-white opacity-75 mb-0 small">{{ $employee->designation }}</p>
                            </div>
                        </div>

                        {{-- Right Side: Employee Details --}}
                        <div class="col-lg-9 col-md-8 p-4">
                            <div class="row g-3">
                                {{-- System ID --}}
                                <div class="col-sm-6 col-lg-6 col-xl-3">
                                    <div class="d-flex align-items-start">
                                        <div class="bg-primary bg-opacity-10 rounded-3 p-2 me-2 shrink-0">
                                            <svg width="20" height="20" class="text-primary" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M10 2a1 1 0 011 1v1.323l3.954 1.582 1.599-.8a1 1 0 01.894 1.79l-1.233.616 1.738 5.42a1 1 0 01-.285 1.05A3.989 3.989 0 0115 15a3.989 3.989 0 01-2.667-1.019 1 1 0 01-.285-1.05l1.715-5.349L11 6.477V16h2a1 1 0 110 2H7a1 1 0 110-2h2V6.477L6.237 7.582l1.715 5.349a1 1 0 01-.285 1.05A3.989 3.989 0 015 15a3.989 3.989 0 01-2.667-1.019 1 1 0 01-.285-1.05l1.738-5.42-1.233-.617a1 1 0 01.894-1.788l1.599.799L9 4.323V3a1 1 0 011-1z" />
                                            </svg>
                                        </div>
                                        <div style="min-width: 0; flex: 1;">
                                            <div class="text-muted small mb-1">System ID</div>
                                            <div class="fw-bold fs-6">{{ $employee->system_id }}</div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Employee ID --}}
                                <div class="col-sm-6 col-lg-6 col-xl-3">
                                    <div class="d-flex align-items-start">
                                        <div class="bg-success bg-opacity-10 rounded-3 p-2 me-2 shrink-0">
                                            <svg width="20" height="20" class="text-success" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path
                                                    d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9zM4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" />
                                            </svg>
                                        </div>
                                        <div style="min-width: 0; flex: 1;">
                                            <div class="text-muted small mb-1">Employee ID</div>
                                            <div class="fw-bold fs-6">{{ $employee->employee_id }}</div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Department --}}
                                <div class="col-sm-6 col-lg-6 col-xl-3">
                                    <div class="d-flex align-items-start">
                                        <div class="bg-warning bg-opacity-10 rounded-3 p-2 me-2 shrink-0">
                                            <svg width="20" height="20" class="text-warning" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path
                                                    d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" />
                                            </svg>
                                        </div>
                                        <div style="min-width: 0; flex: 1;">
                                            <div class="text-muted small mb-1">Department</div>
                                            <div class="fw-semibold text-truncate">{{ $employee->department }}</div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Branch --}}
                                <div class="col-sm-6 col-lg-6 col-xl-3">
                                    <div class="d-flex align-items-start">
                                        <div class="bg-info bg-opacity-10 rounded-3 p-2 me-2 shrink-0">
                                            <svg width="20" height="20" class="text-info" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" />
                                            </svg>
                                        </div>
                                        <div style="min-width: 0; flex: 1;">
                                            <div class="text-muted small mb-1">Branch</div>
                                            <div class="fw-semibold text-truncate">{{ $employee->branch }}</div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Email --}}
                                <div class="col-sm-6 col-lg-6 col-xl-6">
                                    <div class="d-flex align-items-start">
                                        <div class="bg-danger bg-opacity-10 rounded-3 p-2 me-2 shrink-0">
                                            <svg width="20" height="20" class="text-danger" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path
                                                    d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                            </svg>
                                        </div>
                                        <div style="min-width: 0; flex: 1;">
                                            <div class="text-muted small mb-1">Email Address</div>
                                            <div class="fw-semibold text-truncate" title="{{ $employee->email }}">
                                                {{ $employee->email }}</div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Phone --}}
                                <div class="col-sm-6 col-lg-6 col-xl-6">
                                    <div class="d-flex align-items-start">
                                        <div class="bg-secondary bg-opacity-10 rounded-3 p-2 me-2 shrink-0">
                                            <svg width="20" height="20" class="text-secondary"
                                                fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                                            </svg>
                                        </div>
                                        <div style="min-width: 0; flex: 1;">
                                            <div class="text-muted small mb-1">Phone Number</div>
                                            <div class="fw-semibold">{{ $employee->phone }}</div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            {{-- Monthly Summary Stats --}}
                            <div class="row g-2 mt-2 pt-3 border-top">
                                <div class="col-6 col-md-3">
                                    <div class="text-center p-2 rounded-3 bg-success bg-opacity-10">
                                        <div class="text-success fw-bold fs-4">{{ $totalPresent }}</div>
                                        <div class="text-muted" style="font-size: 0.75rem;">Present Days</div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="text-center p-2 rounded-3 bg-danger bg-opacity-10">
                                        <div class="text-danger fw-bold fs-4">{{ $totalAbsent }}</div>
                                        <div class="text-muted" style="font-size: 0.75rem;">Absent Days</div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="text-center p-2 rounded-3 bg-warning bg-opacity-10">
                                        <div class="text-warning fw-bold fs-4">{{ $totalLate }}</div>
                                        <div class="text-muted" style="font-size: 0.75rem;">Late Arrivals</div>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="text-center p-2 rounded-3 bg-primary bg-opacity-10">
                                        <div class="text-primary fw-bold fs-4">
                                            {{ $totalWorkingDays > 0 ? round(($totalPresent / $totalWorkingDays) * 100) : 0 }}%
                                        </div>
                                        <div class="text-muted" style="font-size: 0.75rem;">Attendance Rate</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Attendance Records Table --}}
            <div class="card border-0 shadow-sm overflow-hidden">
                <div class="card-header border-bottom py-3" style="background-color: var(--bs-tertiary-bg);">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary bg-opacity-10 rounded-3 p-2 me-3">
                                <svg width="24" height="24" class="text-primary" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" />
                                </svg>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold">Monthly Attendance Records</h5>
                                <small class="text-muted">
                                    <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-1 mt-1">
                                        {{ count($attendanceRecords) }} records found
                                    </span>
                                </small>
                            </div>
                        </div>
                        <div class="btn-group shadow-sm" role="group">
                            <button type="button" class="btn btn-outline-primary btn-sm" title="Export to Excel">
                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M3 17a1 1 0 001 1h12a1 1 0 001-1v-5h-2v4H5v-4H3v5zM10 3l-4 4h3v6h2V7h3l-4-4z" />
                                </svg>
                                <span class="d-none d-md-inline ms-1">Export</span>
                            </button>
                            <button type="button" class="btn btn-outline-primary btn-sm" onclick="window.print()"
                                title="Print">
                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M5 4v2h10V4a1 1 0 00-1-1H6a1 1 0 00-1 1zm13 3H2a1 1 0 00-1 1v4a1 1 0 001 1h1v3a1 1 0 001 1h12a1 1 0 001-1v-3h1a1 1 0 001-1V8a1 1 0 00-1-1zm-3 9H5v-4h10v4z" />
                                </svg>
                                <span class="d-none d-md-inline ms-1">Print</span>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-borderless align-middle mb-0">
                            <thead class="border-bottom" style="background-color: var(--bs-secondary-bg);">
                                <tr class="text-uppercase small fw-semibold text-muted">
                                    <th scope="col" class="ps-4 py-3" style="width: 50px;">SL</th>
                                    <th scope="col" class="py-3" style="width: 120px;">Date</th>
                                    <th scope="col" class="py-3" style="width: 100px;">Day</th>
                                    <th scope="col" class="py-3" style="width: 180px;">Shift</th>
                                    <th scope="col" class="py-3 text-center">In</th>
                                    <th scope="col" class="py-3 text-center">Out</th>
                                    <th scope="col" class="py-3 text-center">Clock In Status</th>
                                    <th scope="col" class="py-3 text-center">Clock Out Status</th>
                                    <th scope="col" class="py-3 text-center">Working Hours</th>
                                    <th scope="col" class="py-3 pe-4 text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($attendanceRecords as $index => $record)
                                    <tr class="border-bottom">
                                        {{-- Serial Number --}}
                                        <td class="ps-4 py-3">
                                            <span class="badge text-muted border fw-normal"
                                                style="background-color: var(--bs-secondary-bg);">{{ $index + 1 }}</span>
                                        </td>

                                        {{-- Date --}}
                                        <td class="py-3">
                                            <div class="fw-semibold">
                                                {{ date('d M, Y', strtotime($record->date)) }}
                                            </div>
                                        </td>

                                        {{-- Day --}}
                                        <td class="py-3">
                                            <span
                                                class="badge bg-primary bg-opacity-10 text-primary px-3 py-2">{{ $record->day }}</span>
                                        </td>

                                        {{-- Shift --}}
                                        <td class="py-3" style="width: 180px;">
                                            <div class="d-flex align-items-start">
                                                <svg class="me-2 mt-1 flex-shrink-0 text-primary" width="14"
                                                    height="14" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" />
                                                </svg>
                                                <div style="line-height: 1.3;">
                                                    <div class="fw-medium" style="font-size: 0.813rem;">
                                                        {{ $record->shift }}</div>
                                                    <div class="text-muted"
                                                        style="font-size: 0.688rem; white-space: nowrap;">
                                                        {{ $record->shift_time }}</div>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- Clock In --}}
                                        <td class="py-3 text-center">
                                            <span class="fw-medium">{{ $record->clock_in ?? '—' }}</span>
                                        </td>

                                        {{-- Clock Out --}}
                                        <td class="py-3 text-center">
                                            <span class="fw-medium">{{ $record->clock_out ?? '—' }}</span>
                                        </td>

                                        {{-- Clock In Status --}}
                                        <td class="py-3 text-center">
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
                                                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" />
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
                                            @endif
                                        </td>

                                        {{-- Clock Out Status --}}
                                        <td class="py-3 text-center">
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
                                                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" />
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
                                            @endif
                                        </td>

                                        {{-- Working Hours --}}
                                        <td class="py-3 text-center">
                                            <span class="fw-bold text-primary fs-6">
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
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination Footer --}}
                    <div class="border-top p-4" style="background-color: var(--bs-tertiary-bg);">
                        <div class="row align-items-center g-3">
                            <div class="col-md-6">
                                <div class="text-muted small">
                                    Showing <strong>1</strong> to <strong>{{ count($attendanceRecords) }}</strong> of
                                    <strong>{{ count($attendanceRecords) }}</strong> total records
                                </div>
                            </div>
                            <div class="col-md-6">
                                <nav aria-label="Attendance records pagination">
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
        @endif

    </div>

    {{-- Enhanced JavaScript --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Select2
            if (typeof $.fn.select2 !== 'undefined') {
                $('#company_id').select2({
                    theme: 'bootstrap-5',
                    width: '100%',
                    placeholder: 'All companies',
                    allowClear: true
                });

                $('#branch_id').select2({
                    theme: 'bootstrap-5',
                    width: '100%',
                    placeholder: 'All branches',
                    allowClear: true
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

                $('#employee_id').select2({
                    theme: 'bootstrap-5',
                    width: '100%',
                    placeholder: 'Choose employee...',
                    allowClear: true
                });
            }

            // Form elements
            const form = document.getElementById('employeeFilterForm');
            const getRecordsBtn = document.getElementById('getRecordsBtn');
            const clearFiltersBtn = document.getElementById('clearFiltersBtn');

            // Form submission with loading state
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                if (!form.checkValidity()) {
                    e.stopPropagation();
                    form.classList.add('was-validated');
                    return;
                }

                // Show loading state
                const originalContent = getRecordsBtn.innerHTML;
                getRecordsBtn.disabled = true;
                getRecordsBtn.innerHTML = `
                    <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                    Loading Records...
                `;

                // Simulate AJAX delay (Replace with actual AJAX call in production)
                setTimeout(function() {
                    getRecordsBtn.disabled = false;
                    getRecordsBtn.innerHTML = originalContent;

                    console.log('Form submitted with:', {
                        company_id: document.getElementById('company_id').value,
                        branch_id: document.getElementById('branch_id').value,
                        department_id: document.getElementById('department_id').value,
                        section_id: document.getElementById('section_id').value,
                        system_id: document.getElementById('system_id').value,
                        emp_id: document.getElementById('emp_id').value,
                        employee_id: document.getElementById('employee_id').value,
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
                    $('#company_id, #branch_id, #department_id, #section_id, #employee_id').val(null)
                        .trigger('change');
                }
            });

            // Add smooth scroll animation for form submission
            form.addEventListener('submit', function() {
                setTimeout(() => {
                    const employeeCard = document.querySelector('.card.shadow-sm:nth-of-type(2)');
                    if (employeeCard) {
                        employeeCard.scrollIntoView({
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
        .card-header .btn-outline-primary {
            display: none !important;
        }

        /* Adjust card styling for print */
        .card {
            box-shadow: none !important;
            border: 1px solid #dee2e6 !important;
            page-break-inside: avoid;
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

        /* Employee info card adjustments */
        .col-lg-3,
        .col-lg-9 {
            flex: 0 0 auto;
        }

        /* Ensure employee photo prints well */
        img {
            print-color-adjust: exact;
            -webkit-print-color-adjust: exact;
        }

        /* Hide filter form when printing */
        #employeeFilterForm {
            display: none !important;
        }

        /* Page breaks */
        .card.border-0.shadow-sm {
            page-break-after: avoid;
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

        /* Employee info gradient - responsive */
        @media (max-width: 991.98px) {
            .col-lg-3[style*="gradient"] {
                padding: 2rem 1.5rem !important;
                min-height: 250px !important;
            }
        }

        /* Responsive employee image */
        @media (max-width: 767.98px) {
            .col-lg-3[style*="gradient"] img {
                width: 100px !important;
                height: 100px !important;
            }

            .col-lg-3 h5 {
                font-size: 1rem !important;
            }

            .col-lg-3[style*="gradient"] {
                min-height: 200px !important;
            }
        }

        /* Prevent text overflow */
        .text-truncate {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        /* Table responsiveness */
        @media (max-width: 1199.98px) {
            .table {
                font-size: 0.875rem;
            }

            .badge {
                font-size: 0.75rem;
                padding: 0.35rem 0.65rem !important;
            }
        }

        /* Smooth transitions */
        .card,
        .btn,
        .badge {
            transition: all 0.2s ease-in-out;
        }

        /* Hover effects */
        .table tbody tr:hover {
            transform: translateX(2px);
        }
    </style>

@endsection

