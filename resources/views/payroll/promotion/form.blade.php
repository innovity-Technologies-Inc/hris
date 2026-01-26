@extends('structure.master')

@section('content')
    {{-- Add back button following project pattern --}}
    <div class="row mb-3">
        <div class="col-12">
            <a href="{{ route('promotion.index') }}" class="btn btn-outline-secondary btn-sm">
                <i style="height: 12px; width: 12px" data-feather="arrow-left"></i> Back to Promotions
            </a>
        </div>
    </div>

    <form id="employeePromotionForm" method="POST"
        action="{{ isset($promotionData) ? route('promotion.update', $promotionData->id) : route('promotion.store') }}">

        @csrf
        @isset($promotionData)
            @method('PUT')
        @endisset

        {{-- Employee Selection Section --}}
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Employee Selection</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Employee <span class="text-danger">*</span></label>
                                <select name="employee_id" id="employee_id"
                                    class="form-select select2_list @error('employee_id') is-invalid @enderror" required>
                                    <option value="">Select Employee</option>
                                    @foreach ($employees as $employee)
                                        <option value="{{ $employee->id }}"
                                            data-office-info="{{ json_encode($employee->officeInfo ?? null) }}"
                                            data-salary-breakdown="{{ json_encode($employee->salaryBreakdown ?? null) }}"
                                            {{ old('employee_id', $promotionData->employee_id ?? '') == $employee->id ? 'selected' : '' }}>
                                            {{ $employee->full_name }} ({{ $employee->applicant_id }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('employee_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                                <small class="text-muted">Select an employee to load current designation and salary
                                    details</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Current Designation Display (Auto-populated) --}}
        <div class="row" id="current-designation-section" style="display: none;">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="alert alert-info mb-0">
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Current Designation:</strong>
                                    <span id="current-designation-display">-</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Current Salary Breakdown Display (Auto-populated) --}}
        <div class="row" id="salary-breakdown-section" style="display: none;">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Current Salary Breakdown</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm mb-0" id="salary-breakdown-table">
                                <thead class="table-light">
                                    <tr>
                                        <th>Component</th>
                                        <th class="text-end">Amount (৳)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Populated via JavaScript -->
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <th>Gross Salary</th>
                                        <th class="text-end" id="gross-salary-display">0.00</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Promotion Details Section --}}
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Promotion Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            {{-- Hidden fields for previous salary information --}}
                            <input type="hidden" name="previous_designation" id="previous_designation"
                                value="{{ old('previous_designation', $promotionData->previous_designation ?? '') }}">
                            <input type="hidden" name="previous_basic_salary" id="previous_basic_salary"
                                value="{{ old('previous_basic_salary', $promotionData->previous_basic_salary ?? '') }}">
                            <input type="hidden" name="previous_gross_salary" id="previous_gross_salary"
                                value="{{ old('previous_gross_salary', $promotionData->previous_gross_salary ?? '') }}">

                            {{-- New Designation --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">New Designation <span class="text-danger">*</span></label>
                                <select name="new_designation" id="new_designation"
                                    class="form-select select2_list @error('new_designation') is-invalid @enderror"
                                    required>
                                    <option value="">Select Designation</option>
                                    @foreach ($designations as $designation)
                                        <option value="{{ $designation->id }}"
                                            {{ old('new_designation', $promotionData->new_designation ?? '') == $designation->id ? 'selected' : '' }}>
                                            {{ $designation->company_designation }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('new_designation')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Increment Base --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Promotional Increment Base <span class="text-danger">*</span></label>
                                <select name="increment_base" id="increment_base"
                                    class="form-select @error('increment_base') is-invalid @enderror" required>
                                    <option value="">Select Base</option>
                                    <option value="basic_salary"
                                        {{ old('increment_base', $promotionData->increment_base ?? '') == 'basic_salary' ? 'selected' : '' }}>
                                        Basic Salary
                                    </option>
                                    <option value="gross_salary"
                                        {{ old('increment_base', $promotionData->increment_base ?? '') == 'gross_salary' ? 'selected' : '' }}>
                                        Gross Salary
                                    </option>
                                </select>
                                @error('increment_base')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                                <small class="text-muted">Choose whether increment applies to basic or gross salary</small>
                            </div>

                            {{-- Increment Method --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Promotional Increment Method <span class="text-danger">*</span></label>
                                <select name="increment_method" id="increment_method"
                                    class="form-select @error('increment_method') is-invalid @enderror" required>
                                    <option value="">Select Method</option>
                                    <option value="fixed"
                                        {{ old('increment_method', $promotionData->increment_method ?? '') == 'fixed' ? 'selected' : '' }}>
                                        Fixed Amount
                                    </option>
                                    <option value="percentage"
                                        {{ old('increment_method', $promotionData->increment_method ?? '') == 'percentage' ? 'selected' : '' }}>
                                        Percentage
                                    </option>
                                </select>
                                @error('increment_method')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Salary Increase Amount --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Promotional Increment Increase Amount <span
                                        class="text-danger">*</span></label>
                                <input type="number" name="salary_increase_amount" id="salary_increase_amount"
                                    value="{{ old('salary_increase_amount', $promotionData->salary_increase_amount ?? '') }}"
                                    class="form-control @error('salary_increase_amount') is-invalid @enderror"
                                    step="0.01" min="0" required placeholder="Enter amount or percentage">
                                @error('salary_increase_amount')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                                <small class="text-muted" id="increment-hint">Enter fixed amount in BDT or percentage
                                    value</small>
                            </div>

                            {{-- Effective From --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Effective From <span class="text-danger">*</span></label>
                                <input type="date" name="effective_from" id="effective_from"
                                    value="{{ old('effective_from', isset($promotionData) ? $promotionData->effective_from : '') }}"
                                    class="form-control @error('effective_from') is-invalid @enderror" required>
                                @error('effective_from')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Effective To --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Effective To <span class="text-muted">(Optional)</span></label>
                                <input type="date" name="effective_to" id="effective_to"
                                    value="{{ old('effective_to', isset($promotionData) && $promotionData->effective_to ? $promotionData->effective_to : '') }}"
                                    class="form-control @error('effective_to') is-invalid @enderror">
                                @error('effective_to')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                                <small class="text-muted">Leave empty for indefinite period</small>
                            </div>

                            {{-- Submit Buttons --}}
                            <div class="col-md-12">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('promotion.index') }}" class="btn btn-secondary">
                                        <i style="height: 12px; width: 12px" data-feather="x"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i style="height: 12px; width: 12px" data-feather="save"></i>
                                        {{ isset($promotionData) ? 'Update' : 'Create' }} Promotion
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        $(document).ready(function() {

            /**
             * 1. EMPLOYEE SELECTION HANDLER
             * Fetches data via AJAX to display current state, but performs no calculations.
             */
            $('#employee_id').on('change', function() {
                const employeeId = $(this).val();

                if (!employeeId) {
                    $('#current-designation-section, #salary-breakdown-section').hide();
                    return;
                }

                // A. Fetch Current Designation via AJAX
                $.ajax({
                    url: `/get-current-designation/${employeeId}`,
                    type: 'GET',
                    success: function(response) {
                        const officeInfo = response.employee;
                        if (officeInfo) {
                            displayCurrentDesignation(officeInfo);

                            // Pass current designation ID to the backend for reference
                            $('#previous_designation').val(officeInfo.current_designation_id || '');
                            $('#current-designation-section').show();
                        }
                    }
                });

                // B. Fetch Salary Breakdown via AJAX
                $.ajax({
                    url: `/get-employee-salary/${employeeId}`,
                    type: 'GET',
                    success: function(response) {
                        const salary = response.employee;
                        if (salary) {
                            displaySalaryBreakdown(salary);

                            // Hidden fields only store the existing values to inform the backend
                            $('#previous_basic_salary').val(salary.basic_salary || 0);
                            $('#previous_gross_salary').val(salary.gross_salary || 0);

                            $('#salary-breakdown-section').show();
                        }
                    }
                });
            });

            /**
             * 2. UI DISPLAY FUNCTIONS
             * Renders the current database records into the view.
             */
            function displayCurrentDesignation(officeInfo) {
                // Path: employee -> get_current_designation -> company_designation
                const title = (officeInfo.get_current_designation)
                    ? officeInfo.get_current_designation.company_designation
                    : 'Not Assigned';

                $('#current-designation-display').text(title);
            }

            function displaySalaryBreakdown(salary) {
                const tableBody = $('#salary-breakdown-table tbody');
                tableBody.empty();

                const components = [
                    { label: 'Basic Salary', value: salary.basic_salary },
                    { label: 'House Allowance', value: salary.house_allowance },
                    { label: 'Transport Allowance', value: salary.transport_allowance },
                    { label: 'Food Allowance', value: salary.food_allowance },
                    { label: 'Medical Allowance', value: salary.medical_allowance },
                    { label: 'Other Earnings', value: salary.other_earnings }
                ];

                components.forEach(comp => {
                    const val = parseFloat(comp.value) || 0;
                    if (val > 0) {
                        tableBody.append(`
                        <tr>
                            <td>${comp.label}</td>
                            <td class="text-end">${formatCurrency(val)}</td>
                        </tr>
                    `);
                    }
                });

                $('#gross-salary-display').text(formatCurrency(salary.gross_salary || 0));
            }

            /**
             * 3. UTILITIES & INITIALIZATION
             */
            function formatCurrency(amount) {
                return parseFloat(amount).toLocaleString('en-BD', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            }

            // Change hints for the user, but does not trigger calculation
            $('#increment_method').on('change', function() {
                const method = $(this).val();
                const hint = (method === 'percentage') ? 'Enter % (e.g. 10)' : 'Enter Fixed Amount';
                $('#increment-hint').text(hint);
            });

            // Initialize data if an employee is already selected (e.g., on page reload or edit)
            if ($('#employee_id').val()) {
                $('#employee_id').trigger('change');
            }
        });
    </script>
@endsection
