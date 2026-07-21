@extends('structure.master')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm rounded">
                <div class="card-header">
                    <h5 class="card-title mb-0">Search Employee</h5>
                </div>
                <div class="card-header border-bottom p-4">
                    <div class="row align-items-start">
                        <div class="col-md-12">
                            <div class="border rounded shadow-sm p-3 filter-section-bg">
                                <form id="employeeSearchForm">

                                    {{-- Keyword Search Section --}}
                                    <div class="mb-4">
                                        <h6 class="text-primary fw-semibold mb-3">
                                            <i class="mdi mdi-magnify"></i> Keyword Search
                                        </h6>
                                        <div class="row">
                                            <div class="col-12">
                                                <label for="keywordSearch"
                                                    class="form-label text-muted small fw-semibold mb-1">
                                                    Quick Search
                                                </label>
                                                <div class="input-group input-group-md">
                                                    <input type="text" class="form-control border-end-0"
                                                        id="keywordSearch" name="keyword"
                                                        placeholder="Search by name, system ID, or employee ID"
                                                        aria-label="Keyword Search">
                                                    <span class="input-group-text border-start-0 input-group-bg">
                                                        <i class="mdi mdi-magnify text-muted"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Basic Search Section --}}
                                    <div class="mb-4">
                                        <h6 class="text-primary fw-semibold mb-3">
                                            <i class="mdi mdi-account-search"></i> Basic Search
                                        </h6>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="employeeName"
                                                    class="form-label text-muted small fw-semibold mb-1">
                                                    Employee Name
                                                </label>
                                                <select id="employeeName" name="employee_name"
                                                    class="form-select form-select-sm select2_list"
                                                    data-placeholder="Select employee name">
                                                    <option value="">Choose One</option>
                                                    @foreach ($filterOptions['employee_names'] as $name)
                                                        <option value="{{ $name }}">{{ $name }}</option>
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
                                                    data-placeholder="Select employee ID">
                                                    <option value="">Choose One</option>
                                                    @foreach ($filterOptions['employee_ids'] as $id)
                                                        <option value="{{ $id }}">{{ $id }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-4">
                                                <label for="systemId" class="form-label text-muted small fw-semibold mb-1">
                                                    System ID
                                                </label>
                                                <select id="systemId" name="system_id"
                                                    class="form-select form-select-sm select2_list"
                                                    data-placeholder="Select system ID">
                                                    <option value="">Choose One</option>
                                                    @foreach ($filterOptions['system_ids'] as $sysId)
                                                        <option value="{{ $sysId }}">{{ $sysId }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Organizational Filters --}}
                                    <div class="mb-4">
                                        <h6 class="text-primary fw-semibold mb-3">
                                            <i class="mdi mdi-office-building"></i> Organizational Filters
                                        </h6>
                                        <div class="row mb-2">
                                            <div class="col-md-4">
                                                <label for="search_company_id"
                                                    class="form-label text-muted small fw-semibold mb-1">
                                                    Company
                                                </label>
                                                <select id="search_company_id" name="company"
                                                    class="form-select form-select-sm select2_list"
                                                    data-placeholder="Select company">
                                                    <option value="">Choose One</option>
                                                    @foreach ($filterOptions['companies'] as $company)
                                                        <option value="{{ $company->id }}">{{ $company->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            @if (App\HelperClass::getGeneralSetting()->branch_status == 1)
                                                <div class="col-md-4">
                                                    <label for="search_business_unit_id"
                                                        class="form-label text-muted small fw-semibold mb-1">
                                                        Branch
                                                    </label>
                                                    <select id="search_business_unit_id" name="business_unit"
                                                        class="form-select form-select-sm select2_list"
                                                        data-placeholder="Select branch">
                                                        <option value="">Select Branch</option>
                                                    </select>
                                                </div>
                                            @endif

                                            @if (App\HelperClass::getGeneralSetting()->division_status == 1)
                                                <div class="col-md-4">
                                                    <label for="search_division_id"
                                                        class="form-label text-muted small fw-semibold mb-1">
                                                        Division
                                                    </label>
                                                    <select id="search_division_id" name="division"
                                                        class="form-select form-select-sm select2_list"
                                                        data-placeholder="Select division">
                                                        <option value="">Select Division</option>
                                                    </select>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="row">
                                            @if (App\HelperClass::getGeneralSetting()->department_status == 1)
                                                <div class="col-md-6">
                                                    <label for="search_department_id"
                                                        class="form-label text-muted small fw-semibold mb-1">
                                                        Department
                                                    </label>
                                                    <select id="search_department_id" name="department"
                                                        class="form-select form-select-sm select2_list"
                                                        data-placeholder="Select department">
                                                        <option value="">Select Department</option>
                                                    </select>
                                                </div>
                                            @endif

                                            @if (App\HelperClass::getGeneralSetting()->section_status == 1)
                                                <div class="col-md-6">
                                                    <label for="search_section_id"
                                                        class="form-label text-muted small fw-semibold mb-1">
                                                        Section
                                                    </label>
                                                    <select id="search_section_id" name="section"
                                                        class="form-select form-select-sm select2_list"
                                                        data-placeholder="Select section">
                                                        <option value="">Select Section</option>
                                                    </select>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Employment Details --}}
                                    <div class="mb-4">
                                        <h6 class="text-primary fw-semibold mb-3">
                                            <i class="mdi mdi-briefcase"></i> Employment Details
                                        </h6>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label for="empType"
                                                    class="form-label text-muted small fw-semibold mb-1">
                                                    Employee Type
                                                </label>
                                                <select id="empType" name="emp_type"
                                                    class="form-select form-select-sm select2_list"
                                                    data-placeholder="Select employee type">
                                                    <option value="">Choose One</option>
                                                    @foreach ($filterOptions['employee_types'] as $type)
                                                        <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-4">
                                                <label for="gender"
                                                    class="form-label text-muted small fw-semibold mb-1">
                                                    Gender
                                                </label>
                                                <select id="gender" name="gender"
                                                    class="form-select form-select-sm select2_list"
                                                    data-placeholder="Select gender">
                                                    <option value="">Choose One</option>
                                                    <option value="Male">Male</option>
                                                    <option value="Female">Female</option>
                                                </select>
                                            </div>

                                            <div class="col-md-4">
                                                <label for="maritalStatus"
                                                    class="form-label text-muted small fw-semibold mb-1">
                                                    Marital Status
                                                </label>
                                                <select id="maritalStatus" name="marital_status"
                                                    class="form-select form-select-sm select2_list"
                                                    data-placeholder="Select status">
                                                    <option value="">Choose One</option>
                                                    <option value="Married">Married</option>
                                                    <option value="Unmarried">Unmarried</option>
                                                    <option value="Divorced">Divorced</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Personal Attributes --}}
                                    <div class="mb-4">
                                        <h6 class="text-primary fw-semibold mb-3">
                                            <i class="mdi mdi-account-details"></i> Personal Attributes
                                        </h6>
                                        <div class="row mb-2">
                                            <div class="col-md-3">
                                                <label for="ageFrom"
                                                    class="form-label text-muted small fw-semibold mb-1">
                                                    Age From
                                                </label>
                                                <input type="number" class="form-control form-control-sm" id="ageFrom"
                                                    name="age_from" placeholder="Min Age" min="18" max="100">
                                            </div>

                                            <div class="col-md-3">
                                                <label for="ageTo"
                                                    class="form-label text-muted small fw-semibold mb-1">
                                                    Age To
                                                </label>
                                                <input type="number" class="form-control form-control-sm" id="ageTo"
                                                    name="age_to" placeholder="Max Age" min="18" max="100">
                                            </div>

                                            <div class="col-md-3">
                                                <label for="religion"
                                                    class="form-label text-muted small fw-semibold mb-1">
                                                    Religion
                                                </label>
                                                <select id="religion" name="religion"
                                                    class="form-select form-select-sm select2_list"
                                                    data-placeholder="Select religion">
                                                    <option value="">Choose One</option>
                                                    @foreach ($filterOptions['religions'] as $religion)
                                                        <option value="{{ $religion }}">{{ $religion }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-3">
                                                <label for="bloodGroup"
                                                    class="form-label text-muted small fw-semibold mb-1">
                                                    Blood Group
                                                </label>
                                                <select id="bloodGroup" name="blood_group"
                                                    class="form-select form-select-sm select2_list"
                                                    data-placeholder="Select blood group">
                                                    <option value="">Choose One</option>
                                                    @foreach ($filterOptions['blood_groups'] as $bloodGroup)
                                                        <option value="{{ $bloodGroup }}">{{ $bloodGroup }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="nationality"
                                                    class="form-label text-muted small fw-semibold mb-1">
                                                    Nationality
                                                </label>
                                                <select id="nationality" name="nationality"
                                                    class="form-select form-select-sm select2_list"
                                                    data-placeholder="Select nationality">
                                                    <option value="">Choose One</option>
                                                    @foreach ($filterOptions['nationalities'] as $nationality)
                                                        <option value="{{ $nationality }}">{{ $nationality }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-6">
                                                <label for="country"
                                                    class="form-label text-muted small fw-semibold mb-1">
                                                    Country
                                                </label>
                                                <select id="country" name="country"
                                                    class="form-select form-select-sm select2_list"
                                                    data-placeholder="Select country (from permanent address)">
                                                    <option value="">Choose One</option>
                                                    @foreach ($filterOptions['countries'] as $country)
                                                        <option value="{{ $country }}">{{ $country }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Action Buttons --}}
                                    <div class="row">
                                        <div class="col-12 text-end">
                                            <button type="button" id="resetFilters"
                                                class="btn btn-outline-secondary btn-sm me-2">
                                                <i class="mdi mdi-refresh"></i> Reset
                                            </button>
                                            <button type="button" id="searchButton" class="btn btn-primary btn-sm">
                                                <i class="mdi mdi-magnify"></i> Search
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Search Results Section - Statistical Charts --}}
        <div class="col-lg-12 mt-3" id="statisticalResults" style="display:none;">
            <div class="card border-0 shadow-sm rounded">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="mdi mdi-chart-pie text-primary"></i> Search Results Statistics
                    </h5>
                    <div>
                        <span class="badge bg-success me-2" id="totalEmployeesFound">0 Employees Found</span>
                        <button type="button" class="btn btn-success btn-sm me-2" id="btnExportExcel">
                            <i class="bi bi-file-earmark-excel me-1"></i> Export Excel
                        </button>
                        <button type="button" class="btn btn-primary btn-sm me-2" onclick="viewDetailedResults()">
                            <i class="mdi mdi-table-eye"></i> View Details
                        </button>
                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="printResults()">
                            <i class="mdi mdi-printer"></i> Print
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card border shadow-sm">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0 text-center">
                                        <i class="mdi mdi-gender-male-female text-info"></i> Gender Distribution
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <canvas id="genderChart" height="250"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card border shadow-sm">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0 text-center">
                                        <i class="mdi mdi-briefcase text-warning"></i> Employee Type
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <canvas id="employeeTypeChart" height="250"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card border shadow-sm">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0 text-center">
                                        <i class="mdi mdi-office-building text-success"></i> Company Distribution
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <canvas id="companyChart" height="300"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card border shadow-sm">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0 text-center">
                                        <i class="mdi mdi-account-group text-primary"></i> Department Distribution
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <canvas id="departmentChart" height="300"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card border shadow-sm">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0 text-center">
                                        <i class="mdi mdi-calendar-range text-danger"></i> Age Group Distribution
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <canvas id="ageChart" height="250"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 mb-4" id="branchChartContainer">
                            <div class="card border shadow-sm">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0 text-center">
                                        <i class="mdi mdi-map-marker text-info"></i> Branch Distribution
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <canvas id="branchChart" height="300"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card border shadow-sm">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0 text-center">
                                        <i class="mdi mdi-domain text-secondary"></i> Division Distribution
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <canvas id="divisionChart" height="300"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card border shadow-sm">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0 text-center">
                                        <i class="mdi mdi-sitemap text-primary"></i> Section Distribution
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <canvas id="sectionChart" height="300"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card border shadow-sm">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0 text-center">
                                        <i class="mdi mdi-heart text-danger"></i> Marital Status Distribution
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <canvas id="maritalChart" height="250"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card border shadow-sm">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0 text-center">
                                        <i class="mdi mdi-water text-danger"></i> Blood Group Distribution
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <canvas id="bloodGroupChart" height="250"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card border shadow-sm">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0 text-center">
                                        <i class="mdi mdi-book-cross text-info"></i> Religion Distribution
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <canvas id="religionChart" height="250"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card border shadow-sm">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0 text-center">
                                        <i class="mdi mdi-account-tie text-success"></i> Nationality Distribution
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <canvas id="nationalityChart" height="250"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card border shadow-sm">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0 text-center">
                                        <i class="mdi mdi-earth text-primary"></i> Country Distribution
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <canvas id="countryChart" height="250"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- No Data Message --}}
        <div class="col-lg-12 mt-3" id="noDataMessage" style="display:none;">
            <div class="card border-0 shadow-sm rounded">
                <div class="card-body text-center py-5">
                    <i class="mdi mdi-database-remove text-muted" style="font-size: 64px;"></i>
                    <h4 class="text-muted mt-3">No Employees Found</h4>
                    <p class="text-muted">Try adjusting your search filters</p>
                    <button type="button" class="btn btn-outline-primary" onclick="resetAllFilters()">
                        <i class="mdi mdi-refresh"></i> Reset Filters
                    </button>
                </div>
            </div>
        </div>

        {{-- Hidden employee data for processing --}}
        <div style="display:none;">
            <table id="hiddenEmployeeTable">
                <tbody id="employeeTableBody">
                    @foreach ($filterOptions['employees'] as $index => $employee)
                        @php
                            $age = $employee->date_of_birth
                                ? \Carbon\Carbon::parse($employee->date_of_birth)->age
                                : null;
                            $country = $employee->permanent_address['country'] ?? '';

                            // Get office info
                            $officeInfo = $employee->officeInfo;

                            // Company
                            $companyId = optional($officeInfo)->current_company_id ?? '';
                            $companyName = optional(optional($officeInfo)->getCurrentCompany)->name ?? '';

                            // Branch (Business Unit) - uses name field from company_locations table
                            $businessUnitId = optional($officeInfo)->current_business_unit_id ?? '';
                            $businessUnitObj = optional($officeInfo)->getCurrentBusinessUnit;
                            $businessUnitName = $businessUnitObj ? $businessUnitObj->name : '';

                            // Division
                            $divisionId = optional($officeInfo)->current_division_id ?? '';
                            $divisionName = optional(optional($officeInfo)->getCurrentDivision)->name ?? '';

                            // Department
                            $departmentId = optional($officeInfo)->current_department_id ?? '';
                            $departmentName =
                                optional(optional($officeInfo)->getCurrentDepartment)->department_name ?? '';

                            // Section
                            $sectionId = optional($officeInfo)->current_section_id ?? '';
                            $sectionName = optional(optional($officeInfo)->getCurrentSection)->name ?? '';

                            // Employee Type
                            $empType = optional($officeInfo)->emp_type ?? '';
                        @endphp
                        <tr class="employee-row" data-system-id="{{ $employee->system_id }}"
                            data-employee-id="{{ $employee->applicant_id }}"
                            data-name="{{ strtolower($employee->full_name) }}" data-age="{{ $age }}"
                            data-gender="{{ $employee->gender }}" data-marital-status="{{ $employee->marital_status }}"
                            data-emp-type="{{ $empType }}" data-blood-group="{{ $employee->blood_group }}"
                            data-religion="{{ $employee->religion }}" data-nationality="{{ $employee->nationality }}"
                            data-country="{{ $country }}" data-company-id="{{ $companyId }}"
                            data-company="{{ $companyName }}" data-business-unit-id="{{ $businessUnitId }}"
                            data-branch="{{ $businessUnitName }}" data-division-id="{{ $divisionId }}"
                            data-division="{{ $divisionName }}" data-department-id="{{ $departmentId }}"
                            data-department="{{ $departmentName }}" data-section-id="{{ $sectionId }}"
                            data-section="{{ $sectionName }}"
                            data-email="{{ $employee->work_email ?? $employee->personal_email }}"
                            data-phone="{{ $employee->personal_mobile }}" data-full-name="{{ $employee->full_name }}">
                            <td>{{ $index + 1 }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @include('employee.search_results_modal')

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <script>
        let filteredEmployees = [];
        let chartInstances = {};

        $(document).ready(function() {
            // Initialize Select2 for all select2_list elements
            $('.select2_list').select2({
                placeholder: function() {
                    return $(this).data('placeholder');
                },
                allowClear: true,
                width: '100%'
            });

            // -------------------------
            // Organizational Filter Dependencies
            // -------------------------
            function loading($el, text = 'Loading...') {
                $el.prop('disabled', true).html(`<option value="">${text}</option>`);
            }

            function reset($el, text) {
                $el.prop('disabled', false).html(`<option value="">${text}</option>`);
            }

            // -------------------------
            // Load Divisions + Chain (Department + Section)
            // -------------------------
            function loadDivisions() {
                const companyId = $('#search_company_id').val();
                if (!companyId) return;

                const locationId = $('#search_business_unit_id').val() || 'null';

                loading($('#search_division_id'));
                reset($('#search_department_id'), 'Select Department');
                reset($('#search_section_id'), 'Select Section');

                $.get(`/get-divisions/${companyId}/${locationId}`, function(data) {
                    reset($('#search_division_id'), 'Select Division');
                    if (!data.length) {
                        $('#search_division_id').html('<option value="">No division found</option>');
                    } else {
                        $.each(data, function(_, item) {
                            $('#search_division_id').append(
                                `<option value="${item.id}">${item.name}</option>`);
                        });
                    }
                    // Chain: Load departments after divisions
                    loadDepartments();
                });
            }

            // -------------------------
            // Load Departments + Chain (Section)
            // -------------------------
            function loadDepartments() {
                const companyId = $('#search_company_id').val();
                if (!companyId) return;

                const locationId = $('#search_business_unit_id').val() || 'null';
                const divisionId = $('#search_division_id').val() || 'null';

                loading($('#search_department_id'));
                reset($('#search_section_id'), 'Select Section');

                $.get(`/get-departments/${companyId}/${locationId}/${divisionId}`, function(data) {
                    reset($('#search_department_id'), 'Select Department');
                    if (!data.length) {
                        $('#search_department_id').html('<option value="">No department found</option>');
                    } else {
                        $.each(data, function(_, item) {
                            $('#search_department_id').append(
                                `<option value="${item.id}">${item.department_name}</option>`);
                        });
                    }
                    // Chain: Load sections after departments
                    loadSections();
                });
            }

            // -------------------------
            // Load Sections
            // -------------------------
            function loadSections() {
                const companyId = $('#search_company_id').val();
                if (!companyId) return;

                const locationId = $('#search_business_unit_id').val() || 'null';
                const divisionId = $('#search_division_id').val() || 'null';
                const departmentId = $('#search_department_id').val() || 'null';

                loading($('#search_section_id'));

                $.get(`/get-sections/${companyId}/${locationId}/${divisionId}/${departmentId}`, function(data) {
                    reset($('#search_section_id'), 'Select Section');
                    if (!data.length) {
                        $('#search_section_id').html('<option value="">No section found</option>');
                    } else {
                        $.each(data, function(_, item) {
                            $('#search_section_id').append(
                                `<option value="${item.id}">${item.name}</option>`);
                        });
                    }
                });
            }

            // -------------------------
            // Company Change → Load Branch + Full Chain
            // -------------------------
            $('#search_company_id').on('change', function() {
                const companyId = $(this).val();
                if (!companyId) return;

                reset($('#search_division_id'), 'Select Division');
                reset($('#search_department_id'), 'Select Department');
                reset($('#search_section_id'), 'Select Section');

                @if (\App\HelperClass::getGeneralSetting()->branch_status == '1')
                    loading($('#search_business_unit_id'));

                    $.get(`/get-units/${companyId}`, function(data) {
                        reset($('#search_business_unit_id'), 'Select Branch');
                        if (!data.length) {
                            $('#search_business_unit_id').html(
                                '<option value="">No branch found</option>');
                        } else {
                            $.each(data, function(_, item) {
                                $('#search_business_unit_id').append(
                                    `<option value="${item.id}">${item.name}</option>`);
                            });
                        }
                        // Immediately load the full chain after branches
                        loadDivisions();
                    });
                @else
                    // No branch → directly load divisions + chain
                    loadDivisions();
                @endif
            });

            // -------------------------
            // Branch Change → Reload Full Chain
            // -------------------------
            $('#search_business_unit_id').on('change', function() {
                loadDivisions(); // This will chain to department → section
            });

            // -------------------------
            // Division Change → Reload Department + Section
            // -------------------------
            $('#search_division_id').on('change', function() {
                loadDepartments(); // This will chain to section
            });

            // -------------------------
            // Department Change → Reload Section
            // -------------------------
            $('#search_department_id').on('change', function() {
                loadSections();
            });

            // Client-side filtering function
            function filterEmployees() {
                const keyword = $('#keywordSearch').val().toLowerCase();
                const employeeName = $('#employeeName').val();
                const employeeId = $('#employeeId').val();
                const systemId = $('#systemId').val();
                const company = $('#search_company_id').val();
                const businessUnit = $('#search_business_unit_id').val();
                const division = $('#search_division_id').val();
                const department = $('#search_department_id').val();
                const section = $('#search_section_id').val();
                const empType = $('#empType').val();
                const gender = $('#gender').val();
                const maritalStatus = $('#maritalStatus').val();
                const ageFrom = parseInt($('#ageFrom').val()) || 0;
                const ageTo = parseInt($('#ageTo').val()) || 999;
                const religion = $('#religion').val();
                const bloodGroup = $('#bloodGroup').val();
                const nationality = $('#nationality').val();
                const country = $('#country').val();

                filteredEmployees = [];

                $('.employee-row').each(function() {
                    const row = $(this);
                    const rowData = {
                        systemId: row.data('system-id'),
                        employeeId: row.data('employee-id'),
                        name: row.data('name'),
                        fullName: row.data('full-name'),
                        age: parseInt(row.data('age')),
                        gender: row.data('gender'),
                        companyId: row.data('company-id'),
                        company: row.data('company'),
                        businessUnitId: row.data('business-unit-id'),
                        branch: row.data('branch'),
                        divisionId: row.data('division-id'),
                        division: row.data('division'),
                        departmentId: row.data('department-id'),
                        department: row.data('department'),
                        sectionId: row.data('section-id'),
                        section: row.data('section'),
                        employeeType: row.data('emp-type'),
                        maritalStatus: row.data('marital-status'),
                        bloodGroup: row.data('blood-group'),
                        religion: row.data('religion'),
                        nationality: row.data('nationality'),
                        country: row.data('country'),
                        email: row.data('email'),
                        phone: row.data('phone')
                    };

                    let matches = true;

                    // Keyword search (checks name, system ID, employee ID)
                    if (keyword &&
                        !rowData.name.includes(keyword) &&
                        !rowData.systemId.toLowerCase().includes(keyword) &&
                        !rowData.employeeId.toLowerCase().includes(keyword)) {
                        matches = false;
                    }

                    // Individual field filters
                    if (employeeName && rowData.fullName !== employeeName) matches = false;
                    if (employeeId && rowData.employeeId !== employeeId) matches = false;
                    if (systemId && rowData.systemId !== systemId) matches = false;
                    if (company && rowData.companyId != company) matches = false;
                    if (businessUnit && rowData.businessUnitId != businessUnit) matches = false;
                    if (division && rowData.divisionId != division) matches = false;
                    if (department && rowData.departmentId != department) matches = false;
                    if (section && rowData.sectionId != section) matches = false;
                    if (empType && rowData.employeeType !== empType) matches = false;
                    if (gender && rowData.gender !== gender) matches = false;
                    if (maritalStatus && rowData.maritalStatus !== maritalStatus) matches = false;
                    if (rowData.age < ageFrom || rowData.age > ageTo) matches = false;
                    if (religion && rowData.religion !== religion) matches = false;
                    if (bloodGroup && rowData.bloodGroup !== bloodGroup) matches = false;
                    if (nationality && rowData.nationality !== nationality) matches = false;
                    if (country && rowData.country !== country) matches = false;

                    if (matches) {
                        filteredEmployees.push(rowData);
                    }
                });

                // Show statistical results or no data message
                if (filteredEmployees.length === 0) {
                    $('#statisticalResults').hide();
                    $('#noDataMessage').show();
                } else {
                    $('#noDataMessage').hide();
                    $('#statisticalResults').show();
                    $('#totalEmployeesFound').text(filteredEmployees.length + ' Employee' + (filteredEmployees
                        .length > 1 ? 's' : '') + ' Found');
                    generateCharts();
                }
            }

            // Generate pie charts
            function generateCharts() {
                // Destroy existing charts
                Object.values(chartInstances).forEach(chart => chart.destroy());
                chartInstances = {};

                // Gender Distribution
                const genderData = countByProperty(filteredEmployees, 'gender');
                chartInstances.gender = createPieChart('genderChart', 'Gender Distribution',
                    Object.keys(genderData), Object.values(genderData),
                    ['#4BC0C0', '#FF6384', '#FFCE56']);

                // Employee Type Distribution
                const typeData = countByProperty(filteredEmployees, 'employeeType');
                chartInstances.type = createPieChart('employeeTypeChart', 'Employee Type',
                    Object.keys(typeData), Object.values(typeData),
                    ['#36A2EB', '#FF9F40']);

                // Company Distribution
                const companyData = countByProperty(filteredEmployees, 'company');
                chartInstances.company = createPieChart('companyChart', 'Company',
                    Object.keys(companyData), Object.values(companyData),
                    ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF']);

                // Department Distribution
                const deptData = countByProperty(filteredEmployees, 'department');
                chartInstances.dept = createPieChart('departmentChart', 'Department',
                    Object.keys(deptData), Object.values(deptData),
                    ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40']);

                // Age Group Distribution
                const ageGroups = categorizeByAge(filteredEmployees);
                chartInstances.age = createPieChart('ageChart', 'Age Groups',
                    Object.keys(ageGroups), Object.values(ageGroups),
                    ['#36A2EB', '#FF6384', '#FFCE56', '#4BC0C0']);

                // Branch Distribution
                const branchData = countByProperty(filteredEmployees, 'branch');
                
                if (Object.keys(branchData).length === 0) {
                    // No branch data available, hide the container
                    $('#branchChartContainer').hide();
                } else {
                    // Show the container and render the chart
                    $('#branchChartContainer').show();
                    chartInstances.branch = createPieChart('branchChart', 'Branch',
                        Object.keys(branchData), Object.values(branchData),
                        ['#9966FF', '#FF9F40', '#4BC0C0', '#FF6384', '#36A2EB']);
                }

                // Division Distribution
                const divisionData = countByProperty(filteredEmployees, 'division');
                chartInstances.division = createPieChart('divisionChart', 'Division',
                    Object.keys(divisionData), Object.values(divisionData),
                    ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40']);

                // Section Distribution
                const sectionData = countByProperty(filteredEmployees, 'section');
                chartInstances.section = createPieChart('sectionChart', 'Section',
                    Object.keys(sectionData), Object.values(sectionData),
                    ['#36A2EB', '#FF6384', '#4BC0C0', '#FFCE56', '#9966FF', '#FF9F40', '#C9CBCF']);

                // Marital Status Distribution
                const maritalData = countByProperty(filteredEmployees, 'maritalStatus');
                chartInstances.marital = createPieChart('maritalChart', 'Marital Status',
                    Object.keys(maritalData), Object.values(maritalData),
                    ['#FF6384', '#36A2EB', '#FFCE56']);

                // Blood Group Distribution
                const bloodGroupData = countByProperty(filteredEmployees, 'bloodGroup');
                chartInstances.bloodGroup = createPieChart('bloodGroupChart', 'Blood Group',
                    Object.keys(bloodGroupData), Object.values(bloodGroupData),
                    ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40', '#C9CBCF', '#FF4560']);

                // Religion Distribution
                const religionData = countByProperty(filteredEmployees, 'religion');
                chartInstances.religion = createPieChart('religionChart', 'Religion',
                    Object.keys(religionData), Object.values(religionData),
                    ['#36A2EB', '#FF6384', '#FFCE56', '#4BC0C0']);

                // Nationality Distribution
                const nationalityData = countByProperty(filteredEmployees, 'nationality');
                chartInstances.nationality = createPieChart('nationalityChart', 'Nationality',
                    Object.keys(nationalityData), Object.values(nationalityData),
                    ['#4BC0C0', '#FF6384', '#36A2EB', '#FFCE56', '#9966FF', '#FF9F40']);

                // Country Distribution
                const countryData = countByProperty(filteredEmployees, 'country');
                chartInstances.country = createPieChart('countryChart', 'Country',
                    Object.keys(countryData), Object.values(countryData),
                    ['#9966FF', '#FF9F40', '#4BC0C0', '#FF6384', '#36A2EB', '#FFCE56']);
            }

            function countByProperty(data, property) {
                return data.reduce((acc, item) => {
                    const value = item[property];
                    // Skip undefined, null, empty strings, and 'N/A' values
                    if (value && value.toString().trim() !== '' && value !== 'N/A' && value !==
                        'undefined' && value !== 'null') {
                        acc[value] = (acc[value] || 0) + 1;
                    }
                    return acc;
                }, {});
            }

            function categorizeByAge(data) {
                return data.reduce((acc, item) => {
                    if (item.age < 25) acc['18-24'] = (acc['18-24'] || 0) + 1;
                    else if (item.age < 35) acc['25-34'] = (acc['25-34'] || 0) + 1;
                    else if (item.age < 45) acc['35-44'] = (acc['35-44'] || 0) + 1;
                    else acc['45+'] = (acc['45+'] || 0) + 1;
                    return acc;
                }, {});
            }

            function createPieChart(canvasId, title, labels, data, colors) {
                const ctx = document.getElementById(canvasId).getContext('2d');

                // Handle empty data - show N/A message
                if (labels.length === 0 || data.length === 0) {
                    ctx.clearRect(0, 0, ctx.canvas.width, ctx.canvas.height);
                    ctx.font = '16px Arial';
                    ctx.fillStyle = '#999';
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'middle';
                    ctx.fillText('No Data Available', ctx.canvas.width / 2, ctx.canvas.height / 2);
                    return null;
                }

                // Generate more colors if needed
                const colorPalette = [
                    '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40',
                    '#FF6384', '#C9CBCF', '#4BC0C0', '#FF4560', '#00D9E9', '#775DD0',
                    '#FEB019', '#FF4560', '#00E396', '#FF9F40', '#546E7A', '#26A69A'
                ];
                const finalColors = labels.length > colors.length ? colorPalette : colors;

                return new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: labels,
                        datasets: [{
                            data: data,
                            backgroundColor: finalColors,
                            borderWidth: 2,
                            borderColor: '#fff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    padding: 8,
                                    font: {
                                        size: 10
                                    },
                                    boxWidth: 12,
                                    boxHeight: 12,
                                    usePointStyle: true
                                },
                                maxHeight: 120
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.label || '';
                                        let value = context.parsed || 0;
                                        let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        let percentage = ((value / total) * 100).toFixed(1);
                                        return label + ': ' + value + ' (' + percentage + '%)';
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // Search button click
            $('#searchButton').on('click', function(e) {
                e.preventDefault();
                filterEmployees();
            });

            // Reset filters
            $('#resetFilters').on('click', function() {
                resetAllFilters();
            });

            // Trigger search on Enter key
            $('#employeeSearchForm').on('keypress', function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    filterEmployees();
                }
            });

            // Live search on keyword input
            $('#keywordSearch').on('input', function() {
                if ($(this).val().length > 2 || $(this).val().length === 0) {
                    filterEmployees();
                }
            });

            // Export search results to Excel
            $('#btnExportExcel, #btnExportExcelModal').on('click', function(e) {
                e.preventDefault();
                const query = $('#employeeSearchForm').serialize();
                window.location.href = "{{ route('employee.employee.export') }}?" + query;
            });

            // Make functions globally accessible
            window.filterEmployees = filterEmployees;
        });

        // Reset function
        function resetAllFilters() {
            $('#employeeSearchForm')[0].reset();
            $('.select2_list').val(null).trigger('change');

            // Reset dependent dropdowns to default state
            $('#search_business_unit_id').html('<option value="">Select Branch</option>');
            $('#search_division_id').html('<option value="">Select Division</option>');
            $('#search_department_id').html('<option value="">Select Department</option>');
            $('#search_section_id').html('<option value="">Select Section</option>');

            filteredEmployees = [];
            $('#statisticalResults').hide();
            $('#noDataMessage').hide();
            Object.values(chartInstances).forEach(chart => chart.destroy());
            chartInstances = {};
        }

        // View detailed results in modal
        function viewDetailedResults() {
            if (filteredEmployees.length === 0) return;

            // Determine which columns to display - show all organizational hierarchy
            const showCompany = true; // Always show company
            const showBranch = true; // Always show branch
            const showDivision = true; // Always show division
            const showDepartment = true; // Always show department
            const showSection = true; // Always show section

            // Show/hide table headers
            $('#headerCompany').toggle(showCompany);
            $('#headerBranch').toggle(showBranch);
            $('#headerDivision').toggle(showDivision);
            $('#headerDepartment').toggle(showDepartment);
            $('#headerSection').toggle(showSection);

            let tableHtml = '';
            filteredEmployees.forEach((emp, index) => {
                tableHtml += `
                    <tr>
                        <td>${index + 1}</td>
                        <td><span class="badge bg-primary">${emp.systemId}</span></td>
                        <td><span class="badge bg-info">${emp.employeeId}</span></td>
                        <td><strong>${emp.fullName}</strong></td>
                        <td>${emp.age}</td>
                        <td>${emp.gender}</td>
                        <td><span class="badge ${emp.employeeType === 'permanent' ? 'bg-success' : 'bg-warning'}">${emp.employeeType}</span></td>
                        ${showCompany ? `<td>${emp.company}</td>` : ''}
                        ${showBranch ? `<td>${emp.branch}</td>` : ''}
                        ${showDivision ? `<td>${emp.division}</td>` : ''}
                        ${showDepartment ? `<td>${emp.department}</td>` : ''}
                        ${showSection ? `<td>${emp.section}</td>` : ''}
                        <td><small><i class="mdi mdi-email"></i> ${emp.email}<br><i class="mdi mdi-phone"></i> ${emp.phone}</small></td>
                    </tr>
                `;
            });

            $('#resultsModalTable tbody').html(tableHtml);
            $('#resultsModalTitle').text('Search Results (' + filteredEmployees.length + ' Employees)');

            const modal = new bootstrap.Modal(document.getElementById('resultsModal'));
            modal.show();
        }

        // Print results
        function printResults() {
            if (filteredEmployees.length === 0) return;

            // Determine which columns to display - show all organizational hierarchy
            const showCompany = true;
            const showBranch = true;
            const showDivision = true;
            const showDepartment = true;
            const showSection = true;

            // Build table headers dynamically
            let headers = `
                <th style="width: 40px;">#</th>
                <th>System ID</th>
                <th>Employee ID</th>
                <th>Name</th>
                <th>Age</th>
                <th>Gender</th>
                <th>Type</th>
            `;
            if (showCompany) headers += `<th>Company</th>`;
            if (showBranch) headers += `<th>Branch</th>`;
            if (showDivision) headers += `<th>Division</th>`;
            if (showDepartment) headers += `<th>Department</th>`;
            if (showSection) headers += `<th>Section</th>`;
            headers += `<th>Contact</th>`;

            let printContent = `
                <html>
                <head>
                    <title>Employee Search Results</title>
                    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
                    <style>
                        @page {
                            size: A4 landscape;
                            margin: 10mm;
                        }
                        body { 
                            font-family: Arial, sans-serif; 
                            padding: 10px;
                            font-size: 10px;
                        }
                        .header { 
                            text-align: center; 
                            margin-bottom: 15px; 
                            border-bottom: 2px solid #333; 
                            padding-bottom: 8px;
                            page-break-after: avoid;
                        }
                        .header h2 {
                            font-size: 18px;
                            margin-bottom: 5px;
                        }
                        .header p {
                            font-size: 11px;
                            margin: 0;
                        }
                        table { 
                            width: 100%; 
                            font-size: 9px;
                            border-collapse: collapse;
                        }
                        th { 
                            background-color: #f8f9fa;
                            font-weight: bold;
                            padding: 6px 4px;
                            border: 1px solid #dee2e6;
                            page-break-inside: avoid;
                            page-break-after: avoid;
                        }
                        td {
                            padding: 5px 4px;
                            border: 1px solid #dee2e6;
                            page-break-inside: avoid;
                        }
                        tr {
                            page-break-inside: avoid;
                            page-break-after: auto;
                        }
                        thead {
                            display: table-header-group;
                        }
                        tbody {
                            display: table-row-group;
                        }
                        .badge {
                            display: inline-block;
                            padding: 2px 6px;
                            font-size: 9px;
                            border-radius: 3px;
                        }
                        .badge.bg-primary { background-color: #0d6efd; color: white; }
                        .badge.bg-info { background-color: #0dcaf0; color: black; }
                        .badge.bg-success { background-color: #198754; color: white; }
                        .badge.bg-warning { background-color: #ffc107; color: black; }
                        @media print {
                            .no-print { display: none; }
                            body { print-color-adjust: exact; -webkit-print-color-adjust: exact; }
                            table { page-break-inside: auto; }
                            tr { page-break-inside: avoid; page-break-after: auto; }
                            td { page-break-inside: avoid; page-break-after: auto; }
                            thead { display: table-header-group; }
                        }
                    </style>
                </head>
                <body>
                    <div class="header">
                        <h2>Employee Search Results</h2>
                        <p>Total Employees: ${filteredEmployees.length} | Date: ${new Date().toLocaleDateString()} ${new Date().toLocaleTimeString()}</p>
                    </div>
                    <table class="table table-bordered">
                        <thead>
                            <tr>${headers}</tr>
                        </thead>
                        <tbody>`;

            filteredEmployees.forEach((emp, index) => {
                let row = `
                    <tr>
                        <td>${index + 1}</td>
                        <td><span class="badge bg-primary">${emp.systemId}</span></td>
                        <td><span class="badge bg-info">${emp.employeeId}</span></td>
                        <td><strong>${emp.fullName}</strong></td>
                        <td>${emp.age}</td>
                        <td>${emp.gender}</td>
                        <td><span class="badge ${emp.employeeType === 'permanent' ? 'bg-success' : 'bg-warning'}">${emp.employeeType}</span></td>
                `;
                if (showCompany) row += `<td>${emp.company}</td>`;
                if (showBranch) row += `<td>${emp.branch}</td>`;
                if (showDivision) row += `<td>${emp.division}</td>`;
                if (showDepartment) row += `<td>${emp.department}</td>`;
                if (showSection) row += `<td>${emp.section}</td>`;
                row += `<td style="font-size: 8px;">${emp.email}<br>${emp.phone}</td>`;
                row += `</tr>`;
                printContent += row;
            });

            printContent += `
                        </tbody>
                    </table>
                </body>
                </html>
            `;

            const printWindow = window.open('', '_blank', 'width=1200,height=800');
            printWindow.document.write(printContent);
            printWindow.document.close();
            printWindow.focus();
            setTimeout(() => {
                printWindow.print();
            }, 500);
        }
    </script>
@endsection

