@extends('structure.master')
@section('content')
    @php
        $employees = [
            [
                'system_id' => 'SYS001',
                'employee_id' => 'EMP1001',
                'punch_card_no' => 'PC1001',
                'name' => 'Ayesha Rahman',
                'age' => 29,
                'gender' => 'Female',
                'marital_status' => 'Unmarried',
                'employee_type' => 'Permanent',
                'company_name' => 'Acme Corp',
                'branch_city' => 'Dhaka',
                'division' => 'North Division, 12/A Mirpur',
                'state' => 'Dhaka',
                'country' => 'Bangladesh',
                'company_division' => 'Operations',
                'department' => 'HR',
                'section' => 'Recruitment',
                'designation' => 'HR Officer',
                'joining_date' => '2020-05-14',
            ],
            [
                'system_id' => 'SYS002',
                'employee_id' => 'EMP1002',
                'punch_card_no' => 'PC1002',
                'name' => 'Md. Karim',
                'age' => 35,
                'gender' => 'Male',
                'marital_status' => 'Married',
                'employee_type' => 'Contractual',
                'company_name' => 'Beta Solutions',
                'branch_city' => 'Chittagong',
                'division' => 'East Division, 7/B Agrabad',
                'state' => 'Chattogram',
                'country' => 'Bangladesh',
                'company_division' => 'Sales',
                'department' => 'Field Sales',
                'section' => 'Retail',
                'designation' => 'Sales Executive',
                'joining_date' => '2018-01-20',
            ],
            [
                'system_id' => 'SYS003',
                'employee_id' => 'EMP1003',
                'punch_card_no' => 'PC1003',
                'name' => 'Sania Akter',
                'age' => 42,
                'gender' => 'Female',
                'marital_status' => 'Married',
                'employee_type' => 'Permanent',
                'company_name' => 'Acme Corp',
                'branch_city' => 'Sylhet',
                'division' => 'North Division, 23/C Zindabazar',
                'state' => 'Sylhet',
                'country' => 'Bangladesh',
                'company_division' => 'Finance',
                'department' => 'Accounts',
                'section' => 'Payroll',
                'designation' => 'Accountant',
                'joining_date' => '2010-09-02',
            ],
            [
                'system_id' => 'SYS004',
                'employee_id' => 'EMP1004',
                'punch_card_no' => 'PC1004',
                'name' => 'Rashid Ahmed',
                'age' => 28,
                'gender' => 'Male',
                'marital_status' => 'Unmarried',
                'employee_type' => 'Permanent',
                'company_name' => 'Gamma Industries',
                'branch_city' => 'Dhaka',
                'division' => 'Central Division, 45/D Gulshan',
                'state' => 'Dhaka',
                'country' => 'Bangladesh',
                'company_division' => 'IT',
                'department' => 'Development',
                'section' => 'Backend',
                'designation' => 'Senior Developer',
                'joining_date' => '2019-03-10',
            ],
            [
                'system_id' => 'SYS005',
                'employee_id' => 'EMP1005',
                'punch_card_no' => 'PC1005',
                'name' => 'Fatima Begum',
                'age' => 31,
                'gender' => 'Female',
                'marital_status' => 'Married',
                'employee_type' => 'Contractual',
                'company_name' => 'Beta Solutions',
                'branch_city' => 'Rajshahi',
                'division' => 'West Division, 9/E Shaheb Bazar',
                'state' => 'Rajshahi',
                'country' => 'Bangladesh',
                'company_division' => 'Operations',
                'department' => 'Logistics',
                'section' => 'Dispatch',
                'designation' => 'Logistics Coordinator',
                'joining_date' => '2021-07-15',
            ],
            [
                'system_id' => 'SYS006',
                'employee_id' => 'EMP1006',
                'punch_card_no' => 'PC1006',
                'name' => 'Jahangir Hossain',
                'age' => 45,
                'gender' => 'Male',
                'marital_status' => 'Married',
                'employee_type' => 'Permanent',
                'company_name' => 'Acme Corp',
                'branch_city' => 'Dhaka',
                'division' => 'North Division, 12/A Mirpur',
                'state' => 'Dhaka',
                'country' => 'Bangladesh',
                'company_division' => 'Operations',
                'department' => 'Production',
                'section' => 'Quality Control',
                'designation' => 'QC Manager',
                'joining_date' => '2008-11-25',
            ],
            [
                'system_id' => 'SYS007',
                'employee_id' => 'EMP1007',
                'punch_card_no' => 'PC1007',
                'name' => 'Nusrat Jahan',
                'age' => 26,
                'gender' => 'Female',
                'marital_status' => 'Unmarried',
                'employee_type' => 'Contractual',
                'company_name' => 'Gamma Industries',
                'branch_city' => 'Khulna',
                'division' => 'South Division, 18/F Sonadanga',
                'state' => 'Khulna',
                'country' => 'Bangladesh',
                'company_division' => 'Marketing',
                'department' => 'Digital Marketing',
                'section' => 'Social Media',
                'designation' => 'Content Creator',
                'joining_date' => '2022-04-01',
            ],
            [
                'system_id' => 'SYS008',
                'employee_id' => 'EMP1008',
                'punch_card_no' => 'PC1008',
                'name' => 'Tanvir Islam',
                'age' => 38,
                'gender' => 'Male',
                'marital_status' => 'Married',
                'employee_type' => 'Permanent',
                'company_name' => 'Beta Solutions',
                'branch_city' => 'Chittagong',
                'division' => 'East Division, 7/B Agrabad',
                'state' => 'Chattogram',
                'country' => 'Bangladesh',
                'company_division' => 'Finance',
                'department' => 'Audit',
                'section' => 'Internal Audit',
                'designation' => 'Audit Officer',
                'joining_date' => '2015-06-12',
            ],
            [
                'system_id' => 'SYS009',
                'employee_id' => 'EMP1009',
                'punch_card_no' => 'PC1009',
                'name' => 'Lamia Khatun',
                'age' => 33,
                'gender' => 'Female',
                'marital_status' => 'Married',
                'employee_type' => 'Permanent',
                'company_name' => 'Gamma Industries',
                'branch_city' => 'Dhaka',
                'division' => 'Central Division, 45/D Gulshan',
                'state' => 'Dhaka',
                'country' => 'Bangladesh',
                'company_division' => 'HR',
                'department' => 'Training',
                'section' => 'Development',
                'designation' => 'Training Manager',
                'joining_date' => '2017-02-28',
            ],
            [
                'system_id' => 'SYS010',
                'employee_id' => 'EMP1010',
                'punch_card_no' => 'PC1010',
                'name' => 'Imran Khan',
                'age' => 40,
                'gender' => 'Male',
                'marital_status' => 'Married',
                'employee_type' => 'Contractual',
                'company_name' => 'Acme Corp',
                'branch_city' => 'Sylhet',
                'division' => 'North Division, 23/C Zindabazar',
                'state' => 'Sylhet',
                'country' => 'Bangladesh',
                'company_division' => 'Sales',
                'department' => 'Corporate Sales',
                'section' => 'Enterprise',
                'designation' => 'Sales Manager',
                'joining_date' => '2019-08-05',
            ],
        ];
        // Build unique value lists for Select2 dropdowns (dummy data from $employees)
        $systemIds = array_unique(array_column($employees, 'system_id'));
        $employeeIds = array_unique(array_column($employees, 'employee_id'));
        $punchCardNos = array_unique(array_column($employees, 'punch_card_no'));
        $employeeNames = array_unique(array_column($employees, 'name'));
        $companyNames = array_unique(array_column($employees, 'company_name'));
        $branchCities = array_unique(array_column($employees, 'branch_city'));
        $divisions = array_unique(array_column($employees, 'division'));
        $states = array_unique(array_column($employees, 'state'));
        $countries = array_unique(array_column($employees, 'country'));
        $companyDivisions = array_unique(array_column($employees, 'company_division'));
        $departments = array_unique(array_column($employees, 'department'));
        $sections = array_unique(array_column($employees, 'section'));
        $designations = array_unique(array_column($employees, 'designation'));
    @endphp

    {{-- Removed inline CSS per request; relying on global Bootstrap/app styles. --}}

    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-12">
                {{-- Filter Card with Collapsible Content --}}
                <div class="card shadow-lg border-0 rounded-3 mb-4">
                    <div class="card-header bg-primary text-white py-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-baseline">
                                <h5 class="mb-0 fw-semibold">
                                    <i class="bi bi-funnel-fill me-2"></i>Employee Search Filters
                                </h5>
                                <span class="badge bg-success rounded-pill fs-6 px-3 py-2 mx-2">
                                    <i class="bi bi-check-circle me-1"></i>
                                    <span id="recordCount">{{ count($employees) }}</span> Records
                                </span>
                            </div>
                            <button class="btn btn-sm btn-light rounded-pill px-3" type="button" data-bs-toggle="collapse"
                                data-bs-target="#filterCollapse" aria-expanded="true">
                                <i class="bi bi-chevron-up" id="collapseIcon"></i>
                                <span class="ms-1">Toggle</span>
                            </button>
                        </div>
                    </div>

                    <div class="collapse show" id="filterCollapse">
                        <div class="card-body p-4">
                            <form id="searchForm" method="GET" action="">
                                {{-- Employee Identifiers --}}
                                <div class="mb-4">
                                    <h6 class="text-primary fw-semibold mb-3">
                                        <i class="bi bi-person-badge me-2"></i>Employee Identifiers
                                    </h6>
                                    <div class="row g-3">
                                        <div class="col-lg-3 col-md-6">
                                            <label for="employee_name" class="form-label text-muted small fw-semibold">
                                                <i class="bi bi-person-circle me-1"></i>Employee Name
                                            </label>
                                            <select class="form-select filter-input select2_list" id="employee_name"
                                                name="employee_name">
                                                <option value="">All</option>
                                                @foreach ($employeeNames as $val)
                                                    <option value="{{ $val }}">{{ $val }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-3 col-md-6">
                                            <label for="system_id" class="form-label text-muted small fw-semibold">
                                                <i class="bi bi-hdd-fill me-1"></i>System ID
                                            </label>
                                            <select class="form-select filter-input select2_list" id="system_id"
                                                name="system_id" data-placeholder="All">
                                                <option value="">All</option>
                                                @foreach ($systemIds as $val)
                                                    <option value="{{ $val }}">{{ $val }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-3 col-md-6">
                                            <label for="employee_id" class="form-label text-muted small fw-semibold">
                                                <i class="bi bi-person-vcard me-1"></i>Employee ID
                                            </label>
                                            <select class="form-select filter-input select2_list" id="employee_id"
                                                name="employee_id">
                                                <option value="">All</option>
                                                @foreach ($employeeIds as $val)
                                                    <option value="{{ $val }}">{{ $val }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-3 col-md-6">
                                            <label for="punch_card_no" class="form-label text-muted small fw-semibold">
                                                <i class="bi bi-credit-card me-1"></i>Punch Card No
                                            </label>
                                            <select class="form-select filter-input select2_list" id="punch_card_no"
                                                name="punch_card_no">
                                                <option value="">All</option>
                                                @foreach ($punchCardNos as $val)
                                                    <option value="{{ $val }}">{{ $val }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <hr class="my-4">

                                {{-- Demographics --}}
                                <div class="mb-4">
                                    <h6 class="text-success fw-semibold mb-3">
                                        <i class="bi bi-bar-chart-fill me-2"></i>Demographics
                                    </h6>
                                    <div class="row g-3">
                                        <div class="col-lg-3 col-md-6">
                                            <label for="age_from" class="form-label text-muted small fw-semibold">
                                                <i class="bi bi-calendar-range me-1"></i>Age From
                                            </label>
                                            <input type="number" class="form-control filter-input rounded-pill"
                                                id="age_from" name="age_from" placeholder="e.g. 25" min="0">
                                        </div>
                                        <div class="col-lg-3 col-md-6">
                                            <label for="age_to" class="form-label text-muted small fw-semibold">
                                                <i class="bi bi-calendar-range me-1"></i>Age To
                                            </label>
                                            <input type="number" class="form-control filter-input rounded-pill"
                                                id="age_to" name="age_to" placeholder="e.g. 40" min="0">
                                        </div>
                                        <div class="col-lg-2 col-md-6">
                                            <label for="gender" class="form-label text-muted small fw-semibold">
                                                <i class="bi bi-gender-ambiguous me-1"></i>Gender
                                            </label>
                                            <select class="form-select filter-input rounded-pill" id="gender"
                                                name="gender">
                                                <option value="">All</option>
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                                <option value="Others">Others</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-2 col-md-6">
                                            <label for="marital_status" class="form-label text-muted small fw-semibold">
                                                <i class="bi bi-heart me-1"></i>Marital Status
                                            </label>
                                            <select class="form-select filter-input rounded-pill" id="marital_status"
                                                name="marital_status">
                                                <option value="">All</option>
                                                <option value="Married">Married</option>
                                                <option value="Unmarried">Unmarried</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-2 col-md-6">
                                            <label for="employee_type" class="form-label text-muted small fw-semibold">
                                                <i class="bi bi-briefcase me-1"></i>Type
                                            </label>
                                            <select class="form-select filter-input rounded-pill" id="employee_type"
                                                name="employee_type">
                                                <option value="">All</option>
                                                <option value="Permanent">Permanent</option>
                                                <option value="Contractual">Contractual</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <hr class="my-4">

                                {{-- Company & Location --}}
                                <div class="mb-4">
                                    <h6 class="text-warning fw-semibold mb-3">
                                        <i class="bi bi-building me-2"></i>Company information
                                    </h6>
                                    <div class="row g-3">
                                        <div class="col-lg-3 col-md-6">
                                            <label for="company_name" class="form-label text-muted small fw-semibold">
                                                <i class="bi bi-building-fill me-1"></i>Company Name
                                            </label>
                                            <select class="form-select filter-input select2_list" id="company_name"
                                                name="company_name">
                                                <option value="">All</option>
                                                @foreach ($companyNames as $val)
                                                    <option value="{{ $val }}">{{ $val }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-3 col-md-6">
                                            <label for="branch_city" class="form-label text-muted small fw-semibold">
                                                <i class="bi bi-geo-alt-fill me-1"></i>Branch City
                                            </label>
                                            <select class="form-select filter-input select2_list" id="branch_city"
                                                name="branch_city">
                                                <option value="">All</option>
                                                @foreach ($branchCities as $val)
                                                    <option value="{{ $val }}">{{ $val }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-3 col-md-6">
                                            <label for="state" class="form-label text-muted small fw-semibold">
                                                <i class="bi bi-map me-1"></i>State
                                            </label>
                                            <select class="form-select filter-input select2_list" id="state"
                                                name="state">
                                                <option value="">All</option>
                                                @foreach ($states as $val)
                                                    <option value="{{ $val }}">{{ $val }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-3 col-md-6">
                                            <label for="division" class="form-label text-muted small fw-semibold">
                                                <i class="bi bi-signpost-split me-1"></i>Division (Address)
                                            </label>
                                            <select class="form-select filter-input select2_list" id="division"
                                                name="division">
                                                <option value="">All</option>
                                                @foreach ($divisions as $val)
                                                    <option value="{{ $val }}">{{ $val }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-3 col-md-6">
                                            <label for="country" class="form-label text-muted small fw-semibold">
                                                <i class="bi bi-globe me-1"></i>Country
                                            </label>
                                            <select class="form-select filter-input select2_list" id="country"
                                                name="country">
                                                <option value="">All</option>
                                                @foreach ($countries as $val)
                                                    <option value="{{ $val }}">{{ $val }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                         <div class="col-lg-3 col-md-6">
                                            <label for="company_division" class="form-label text-muted small fw-semibold">
                                                <i class="bi bi-diagram-2 me-1"></i>Company Division
                                            </label>
                                            <select class="form-select filter-input select2_list" id="company_division"
                                                name="company_division">
                                                <option value="">All</option>
                                                @foreach ($companyDivisions as $val)
                                                    <option value="{{ $val }}">{{ $val }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-3 col-md-6">
                                            <label for="department" class="form-label text-muted small fw-semibold">
                                                <i class="bi bi-collection me-1"></i>Department
                                            </label>
                                            <select class="form-select filter-input select2_list" id="department"
                                                name="department">
                                                <option value="">All</option>
                                                @foreach ($departments as $val)
                                                    <option value="{{ $val }}">{{ $val }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-3 col-md-6">
                                            <label for="section" class="form-label text-muted small fw-semibold">
                                                <i class="bi bi-folder me-1"></i>Section
                                            </label>
                                            <select class="form-select filter-input select2_list" id="section"
                                                name="section">
                                                <option value="">All</option>
                                                @foreach ($sections as $val)
                                                    <option value="{{ $val }}">{{ $val }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-lg-3 col-md-6">
                                            <label for="designation" class="form-label text-muted small fw-semibold">
                                                <i class="bi bi-award me-1"></i>Designation
                                            </label>
                                            <select class="form-select filter-input select2_list" id="designation"
                                                name="designation">
                                                <option value="">All</option>
                                                @foreach ($designations as $val)
                                                    <option value="{{ $val }}">{{ $val }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                    </div>
                                </div>

                                <hr class="my-4">

                                {{-- Date Range --}}
                                <div class="mb-4">
                                    <h6 class="text-danger fw-semibold mb-3">
                                        <i class="bi bi-calendar-event me-2"></i>Joining Date Range
                                    </h6>
                                    <div class="row g-3">
                                        <div class="col-lg-6 col-md-6">
                                            <label for="joining_date_from"
                                                class="form-label text-muted small fw-semibold">
                                                <i class="bi bi-calendar-check me-1"></i>From Date
                                            </label>
                                            <input type="date" class="form-control filter-input rounded-pill"
                                                id="joining_date_from" name="joining_date_from">
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <label for="joining_date_to" class="form-label text-muted small fw-semibold">
                                                <i class="bi bi-calendar-x me-1"></i>To Date
                                            </label>
                                            <input type="date" class="form-control filter-input rounded-pill"
                                                id="joining_date_to" name="joining_date_to">
                                        </div>
                                    </div>
                                </div>

                                {{-- Action Buttons --}}
                                <div class="d-flex gap-3 mt-4 pt-3 border-top">
                                    <button type="button" id="searchBtn"
                                        class="btn btn-primary btn-md px-3 rounded-pill shadow-sm">
                                        <i class="bi bi-search me-2"></i>Search
                                    </button>
                                    <button type="button" id="resetBtn"
                                        class="btn btn-outline-secondary btn-md px-3 rounded-pill">
                                        <i class="bi bi-arrow-counterclockwise me-2"></i>Reset All Filters
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Results Section --}}
                <div class="card shadow-lg border-0 rounded-3">
                    <div class="card-header bg-white border-bottom py-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <h5 class="mb-0 fw-semibold text-dark">
                                <i class="bi bi-table me-2 text-primary"></i>Search Results
                            </h5>
                            <span class="badge bg-success rounded-pill fs-6 px-3 py-2">
                                <i class="bi bi-check-circle me-1"></i>
                                <span id="recordCountTable">{{ count($employees) }}</span> Found
                            </span>
                        </div>
                    </div>
                    <div class="card-body p-1" id="results">
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0" id="resultsTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Profile</th>
                                        <th>System ID</th>
                                        <th>Employee ID</th>
                                        <th>Employee Name</th>
                                        <th style="width:120px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="tableBody">
                                    @php $sl = 1; @endphp
                                    @foreach ($employees as $emp)
                                        <tr class="employee-row" data-system-id="{{ $emp['system_id'] }}"
                                            data-employee-id="{{ $emp['employee_id'] }}" data-punch-card-no="{{ $emp['punch_card_no'] }}"
                                            data-name="{{ $emp['name'] }}" data-age="{{ $emp['age'] }}" data-gender="{{ $emp['gender'] }}"
                                            data-marital-status="{{ $emp['marital_status'] }}" data-employee-type="{{ $emp['employee_type'] }}"
                                            data-company-name="{{ $emp['company_name'] }}" data-branch-city="{{ $emp['branch_city'] }}"
                                            data-division="{{ $emp['division'] }}" data-state="{{ $emp['state'] }}" data-country="{{ $emp['country'] }}"
                                            data-company-division="{{ $emp['company_division'] }}" data-department="{{ $emp['department'] }}"
                                            data-section="{{ $emp['section'] }}" data-designation="{{ $emp['designation'] }}"
                                            data-joining-date="{{ $emp['joining_date'] }}">
                                            <th scope="row">{{ $sl++ }}</th>
                                            <td>
                                                <div class="rounded-circle bg-secondary d-inline-flex justify-content-center align-items-center text-white" style="width:32px; height:32px; font-size:12px;">
                                                    {{ strtoupper(substr($emp['name'],0,1)) }}
                                                </div>
                                            </td>
                                            <td>{{ $emp['system_id'] }}</td>
                                            <td>{{ $emp['employee_id'] }}</td>
                                            <td>{{ $emp['name'] }}</td>
                                            <td>
                                                <a href="#" class="btn btn-secondary btn-sm" onclick="viewEmployee(event, '{{ $emp['employee_id'] }}')">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div id="noDataMessage" class="text-center py-5" style="display:none;">
                            <div class="mb-3"><i class="bi bi-inbox display-1 text-muted"></i></div>
                            <h4 class="text-muted mb-2">No Results Found</h4>
                            <p class="text-muted mb-4">Try adjusting your filters to find what you're looking for</p>
                            <button type="button" id="resetFromNoData" class="btn btn-primary rounded-pill px-4">
                                <i class="bi bi-arrow-counterclockwise me-2"></i>Reset Filters
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Client-Side Filtering Script (Vanilla JS) --}}
    <script>
        (function() {
            'use strict';

            // Get all employee rows once on page load
            const employeeRows = document.querySelectorAll('.employee-row');
            const tableBody = document.getElementById('tableBody');
            const resultsTable = document.getElementById('resultsTable');
            const noDataMessage = document.getElementById('noDataMessage');
            const recordCount = document.getElementById('recordCount');
            const recordCountTable = document.getElementById('recordCountTable');
            const searchBtn = document.getElementById('searchBtn');
            const resetBtn = document.getElementById('resetBtn');
            const resetFromNoData = document.getElementById('resetFromNoData');
            const collapseIcon = document.getElementById('collapseIcon');
            const filterCollapse = document.getElementById('filterCollapse');

            // Toggle icon on collapse
            filterCollapse.addEventListener('show.bs.collapse', function() {
                collapseIcon.classList.remove('bi-chevron-down');
                collapseIcon.classList.add('bi-chevron-up');
            });

            filterCollapse.addEventListener('hide.bs.collapse', function() {
                collapseIcon.classList.remove('bi-chevron-up');
                collapseIcon.classList.add('bi-chevron-down');
            });

            /**
             * Main filter function: applies all filter criteria together
             * Returns true if the row should be visible, false otherwise
             */
            function matchesFilters(row) {
                // Dropdowns: exact match (case-insensitive) if a value selected
                function exact(id, dataKey) {
                    const val = document.getElementById(id).value.trim();
                    if (!val) return true;
                    return row.dataset[dataKey].toLowerCase() === val.toLowerCase();
                }

                if (!exact('system_id', 'systemId')) return false;
                if (!exact('employee_id', 'employeeId')) return false;
                if (!exact('punch_card_no', 'punchCardNo')) return false;
                if (!exact('employee_name', 'name')) return false;
                if (!exact('company_name', 'companyName')) return false;
                if (!exact('branch_city', 'branchCity')) return false;
                if (!exact('division', 'division')) return false;
                if (!exact('state', 'state')) return false;
                if (!exact('country', 'country')) return false;
                if (!exact('company_division', 'companyDivision')) return false;
                if (!exact('department', 'department')) return false;
                if (!exact('section', 'section')) return false;
                if (!exact('designation', 'designation')) return false;

                // Select filters: exact match (empty or "All" = no filter)
                const gender = document.getElementById('gender').value;
                if (gender && row.dataset.gender !== gender) return false;

                const maritalStatus = document.getElementById('marital_status').value;
                if (maritalStatus && row.dataset.maritalStatus !== maritalStatus) return false;

                const employeeType = document.getElementById('employee_type').value;
                if (employeeType && row.dataset.employeeType !== employeeType) return false;

                // Age range filter: inclusive (empty = no constraint)
                const ageFrom = document.getElementById('age_from').value.trim();
                const ageTo = document.getElementById('age_to').value.trim();
                const age = parseInt(row.dataset.age, 10);

                if (ageFrom !== '' && age < parseInt(ageFrom, 10)) return false;
                if (ageTo !== '' && age > parseInt(ageTo, 10)) return false;

                // Joining date range filter: inclusive (empty = no constraint)
                const joiningDateFrom = document.getElementById('joining_date_from').value;
                const joiningDateTo = document.getElementById('joining_date_to').value;
                const joiningDate = row.dataset.joiningDate;

                if (joiningDateFrom && joiningDate < joiningDateFrom) return false;
                if (joiningDateTo && joiningDate > joiningDateTo) return false;

                // All filters passed
                return true;
            }

            /**
             * Apply filters to all rows and update UI accordingly
             */
            function applyFilters() {
                let visibleCount = 0;

                employeeRows.forEach(function(row) {
                    if (matchesFilters(row)) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });

                // Update record counts
                recordCount.textContent = visibleCount;
                recordCountTable.textContent = visibleCount;

                // Show/hide table and no-data message
                if (visibleCount === 0) {
                    resultsTable.style.display = 'none';
                    noDataMessage.style.display = 'block';
                } else {
                    resultsTable.style.display = 'table';
                    noDataMessage.style.display = 'none';
                }
            }

            /**
             * Reset all form inputs and show all records
             */
            function resetFilters() {
                // Clear all text inputs
                document.querySelectorAll('.filter-input').forEach(function(input) {
                    if (input.tagName === 'SELECT') {
                        input.selectedIndex = 0;
                    } else {
                        input.value = '';
                    }
                });

                // Show all rows
                employeeRows.forEach(function(row) {
                    row.style.display = '';
                });

                // Update counts to total
                recordCount.textContent = employeeRows.length;
                recordCountTable.textContent = employeeRows.length;

                // Show table, hide no-data message
                resultsTable.style.display = 'table';
                noDataMessage.style.display = 'none';
            }

            // Event listeners
            searchBtn.addEventListener('click', applyFilters);
            resetBtn.addEventListener('click', resetFilters);
            resetFromNoData.addEventListener('click', resetFilters);

            // Optional: Apply filters on Enter key in text inputs
            // Apply filters on change for selects
            document.querySelectorAll('select.filter-input').forEach(function(sel) {
                sel.addEventListener('change', applyFilters);
            });

        })();

        /**
         * Handle view employee button click
         * Replace with your actual route/logic
         */
        function viewEmployee(event, employeeId) {
            event.preventDefault();

            // Option 1: Navigate to employee detail page
            window.location.href = `/employees/${employeeId}/view`;

            // Option 2: Open in new tab
            // window.open(`/employees/${employeeId}/view`, '_blank');

            // Option 3: Show modal (placeholder - implement your modal logic)
            // console.log('Viewing employee:', employeeId);
            // alert(`View details for Employee ID: ${employeeId}`);

            // TODO: Replace with your actual implementation
        }
    </script>
@endsection
