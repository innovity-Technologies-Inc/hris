@extends('structure.master')

@section('content')
    {{-- Add back button following project pattern --}}


    <div class="row mb-3">
        <div class="col-12">
            <a href="{{ route('increment.index') }}" class="btn btn-outline-secondary btn-sm">
                <i style="height: 12px; width: 12px" data-feather="arrow-left"></i> Back to Increments
            </a>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <form id="employeeIncrementForm" method="POST"
        action="{{ isset($incrementData) ? route('increment.update', $incrementData->id) : route('increment.store') }}">

        @csrf
        @isset($incrementData)
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
                                            {{ old('employee_id', $incrementData->employee_id ?? '') == $employee->id ? 'selected' : '' }}>
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
                                <div class="col-md-12">
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

        {{-- Increment Details Section --}}
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Increment Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            {{-- Increment Base --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Increment Base <span class="text-danger">*</span></label>
                                <select name="increment_base" id="increment_base"
                                    class="form-select @error('increment_base') is-invalid @enderror" required>
                                    <option value="">Select Base</option>
                                    <option value="basic_salary"
                                        {{ old('increment_base', $incrementData->increment_base ?? '') == 'basic_salary' ? 'selected' : '' }}>
                                        Basic Salary
                                    </option>
                                    <option value="gross_salary"
                                        {{ old('increment_base', $incrementData->increment_base ?? '') == 'gross_salary' ? 'selected' : '' }}>
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
                                <label class="form-label">Increment Method <span class="text-danger">*</span></label>
                                <select name="increment_method" id="increment_method"
                                    class="form-select @error('increment_method') is-invalid @enderror" required>
                                    <option value="">Select Method</option>
                                    <option value="fixed"
                                        {{ old('increment_method', $incrementData->increment_method ?? '') == 'fixed' ? 'selected' : '' }}>
                                        Fixed Amount
                                    </option>
                                    <option value="percentage"
                                        {{ old('increment_method', $incrementData->increment_method ?? '') == 'percentage' ? 'selected' : '' }}>
                                        Percentage
                                    </option>
                                </select>
                                @error('increment_method')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Increment Amount --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Increment Amount <span class="text-danger">*</span></label>
                                <input type="number" name="salary_increase_amount" id="salary_increase_amount"
                                    value="{{ old('salary_increase_amount', $incrementData->salary_increase_amount ?? '') }}"
                                    class="form-control @error('salary_increase_amount') is-invalid @enderror" step="0.01"
                                    min="0" required placeholder="Enter amount or percentage">
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
                                    value="{{ old('effective_from', isset($incrementData) ? $incrementData->effective_from : '') }}"
                                    class="form-control @error('effective_from') is-invalid @enderror" required>
                                @error('effective_from')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Effective To --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Effective To <span class="text-muted">(Optional)</span></label>
                                <input type="date" name="effective_to" id="effective_to"
                                    value="{{ old('effective_to', isset($incrementData) && $incrementData->effective_to ? $incrementData->effective_to : '') }}"
                                    class="form-control @error('effective_to') is-invalid @enderror">
                                @error('effective_to')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                                <small class="text-muted">Leave empty for indefinite period</small>
                            </div>

                            {{-- Submit Buttons --}}
                            <div class="col-md-12">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('increment.index') }}" class="btn btn-secondary">
                                        <i style="height: 12px; width: 12px" data-feather="x"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i style="height: 12px; width: 12px" data-feather="save"></i>
                                        {{ isset($incrementData) ? 'Update' : 'Create' }} Increment
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

            // 1. Employee Selection Handler (AJAX)
            $('#employee_id').on('change', function() {
                const employeeId = $(this).val();

                if (!employeeId) {
                    $('#current-designation-section, #salary-breakdown-section').hide();
                    return;
                }

                // A. Fetch Designation Data
                $.ajax({
                    url: `/get-current-designation/${employeeId}`,
                    type: 'GET',
                    success: function(response) {
                        const officeInfo = response.employee;
                        if (officeInfo) {
                            displayCurrentDesignation(officeInfo);
                            // Store current designation ID for the backend
                            $('#previous_designation').val(officeInfo.current_designation_id || '');
                            $('#current-designation-section').show();
                        }
                    }
                });

                // B. Fetch Salary Data
                $.ajax({
                    url: `/get-employee-salary/${employeeId}`,
                    type: 'GET',
                    success: function(response) {
                        const salary = response.employee;
                        if (salary) {
                            displaySalaryBreakdown(salary);
                            // Set hidden fields so the backend knows the starting point
                            $('#previous_basic_salary').val(salary.basic_salary || 0);
                            $('#previous_gross_salary').val(salary.gross_salary || 0);
                            $('#salary-breakdown-section').show();
                        }
                    }
                });
            });

            // 2. UI Display Functions
            function displayCurrentDesignation(officeInfo) {
                // Path: employee -> get_current_designation -> company_designation
                const title = (officeInfo.get_current_designation)
                    ? officeInfo.get_current_designation.company_designation
                    : 'Not Assigned';

                // If you have a grade relationship, access it here
                const grade = officeInfo.grade_id || '-';

                $('#current-designation-display').text(title);
                $('#current-grade-display').text(grade);
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

            // 3. Increment Hint Logic (No Calculation)
            $('#increment_method').on('change', function() {
                const method = $(this).val();
                const hint = (method === 'percentage') ? 'Enter % (e.g. 10)' : 'Enter Fixed Amount (৳)';
                $('#increment-hint').text(hint);
                $('#salary_increase_amount').attr('placeholder', hint);
            });

            // 4. Utility: Currency Formatter
            function formatCurrency(amount) {
                return parseFloat(amount).toLocaleString('en-BD', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            }

            // 5. Page Load Trigger (For Edit Mode)
            if ($('#employee_id').val()) {
                $('#employee_id').trigger('change');
            }
        });
    </script>@endsection
