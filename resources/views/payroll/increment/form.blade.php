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

    <form id="employeeIncrementForm" method="POST"
        action="{{ isset($increment) ? route('increment.update', $increment->id) : route('increment.store') }}">

        @csrf
        @isset($increment)
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
                                            {{ old('employee_id', $increment->employee_id ?? '') == $employee->id ? 'selected' : '' }}>
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
                                <div class="col-md-6">
                                    <strong>Current Grade:</strong>
                                    <span id="current-grade-display">-</span>
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
                                        {{ old('increment_base', $increment->increment_base ?? '') == 'basic_salary' ? 'selected' : '' }}>
                                        Basic Salary
                                    </option>
                                    <option value="gross_salary"
                                        {{ old('increment_base', $increment->increment_base ?? '') == 'gross_salary' ? 'selected' : '' }}>
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
                                        {{ old('increment_method', $increment->increment_method ?? '') == 'fixed' ? 'selected' : '' }}>
                                        Fixed Amount
                                    </option>
                                    <option value="percentage"
                                        {{ old('increment_method', $increment->increment_method ?? '') == 'percentage' ? 'selected' : '' }}>
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
                                <input type="number" name="increment_amount" id="increment_amount"
                                    value="{{ old('increment_amount', $increment->increment_amount ?? '') }}"
                                    class="form-control @error('increment_amount') is-invalid @enderror" step="0.01"
                                    min="0" required placeholder="Enter amount or percentage">
                                @error('increment_amount')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                                <small class="text-muted" id="increment-hint">Enter fixed amount in BDT or percentage
                                    value</small>
                            </div>

                            {{-- Effective From --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Effective From <span class="text-danger">*</span></label>
                                <input type="date" name="effective_from" id="effective_from"
                                    value="{{ old('effective_from', isset($increment) ? $increment->effective_from : '') }}"
                                    class="form-control @error('effective_from') is-invalid @enderror" required>
                                @error('effective_from')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Effective To --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Effective To <span class="text-muted">(Optional)</span></label>
                                <input type="date" name="effective_to" id="effective_to"
                                    value="{{ old('effective_to', isset($increment) && $increment->effective_to ? $increment->effective_to : '') }}"
                                    class="form-control @error('effective_to') is-invalid @enderror">
                                @error('effective_to')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                                <small class="text-muted">Leave empty for indefinite period</small>
                            </div>

                            {{-- Status (if editing) --}}
                            @isset($increment)
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-select @error('status') is-invalid @enderror">
                                        <option value="pending" {{ $increment->status == 'pending' ? 'selected' : '' }}>
                                            Pending
                                        </option>
                                        <option value="approved" {{ $increment->status == 'approved' ? 'selected' : '' }}>
                                            Approved
                                        </option>
                                        <option value="rejected" {{ $increment->status == 'rejected' ? 'selected' : '' }}>
                                            Rejected
                                        </option>
                                    </select>
                                    @error('status')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            @endisset

                            {{-- Submit Buttons --}}
                            <div class="col-md-12">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('increment.index') }}" class="btn btn-secondary">
                                        <i style="height: 12px; width: 12px" data-feather="x"></i> Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i style="height: 12px; width: 12px" data-feather="save"></i>
                                        {{ isset($increment) ? 'Update' : 'Create' }} Increment
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

            // Employee selection change handler
            $('#employee_id').on('change', function() {
                const selectedOption = $(this).find('option:selected');
                const officeInfo = selectedOption.data('office-info');
                const salaryBreakdown = selectedOption.data('salary-breakdown');

                if (officeInfo && salaryBreakdown) {
                    // Display current designation
                    displayCurrentDesignation(officeInfo);

                    // Display salary breakdown
                    displaySalaryBreakdown(salaryBreakdown);

                    // Store salary data for calculations
                    $('#employeeIncrementForm').data('basic-salary', salaryBreakdown.basic_salary || 0);
                    $('#employeeIncrementForm').data('gross-salary', salaryBreakdown.gross_salary || 0);

                    // Show sections
                    $('#current-designation-section').show();
                    $('#salary-breakdown-section').show();
                } else {
                    // Hide sections if no data
                    $('#current-designation-section').hide();
                    $('#salary-breakdown-section').hide();
                }
            });

            // Update increment hint based on method
            $('#increment_method').on('change', function() {
                const method = $(this).val();
                if (method === 'percentage') {
                    $('#increment-hint').text('Enter percentage value (e.g., 10 for 10%)');
                    $('#increment_amount').attr('placeholder', 'Enter percentage (e.g., 10)');
                } else if (method === 'fixed') {
                    $('#increment-hint').text('Enter fixed amount in BDT');
                    $('#increment_amount').attr('placeholder', 'Enter amount in BDT');
                } else {
                    $('#increment-hint').text('Enter fixed amount in BDT or percentage value');
                    $('#increment_amount').attr('placeholder', 'Enter amount or percentage');
                }
            });

            // Real-time calculation on increment changes
            $('#increment_amount, #increment_base, #increment_method').on('change input', function() {
                calculateProjectedIncrement();
            });

            // Function to display current designation
            function displayCurrentDesignation(officeInfo) {
                const designation = officeInfo.current_designation || '-';
                const grade = officeInfo.grade || '-';

                $('#current-designation-display').text(designation);
                $('#current-grade-display').text(grade);
            }

            // Function to display salary breakdown
            function displaySalaryBreakdown(salaryBreakdown) {
                const tableBody = $('#salary-breakdown-table tbody');
                tableBody.empty();

                // Build table rows
                const components = [{
                        label: 'Basic Salary',
                        value: salaryBreakdown.basic_salary || 0
                    },
                    {
                        label: 'House Allowance',
                        value: salaryBreakdown.house_allowance || 0
                    },
                    {
                        label: 'Transport Allowance',
                        value: salaryBreakdown.transport_allowance || 0
                    },
                    {
                        label: 'Food Allowance',
                        value: salaryBreakdown.food_allowance || 0
                    },
                    {
                        label: 'Medical Allowance',
                        value: salaryBreakdown.medical_allowance || 0
                    },
                    {
                        label: 'Other Earnings',
                        value: salaryBreakdown.other_earnings || 0
                    }
                ];

                components.forEach(component => {
                    if (parseFloat(component.value) > 0) {
                        tableBody.append(`
                            <tr>
                                <td>${component.label}</td>
                                <td class="text-end">${formatCurrency(component.value)}</td>
                            </tr>
                        `);
                    }
                });

                // Display gross salary
                const grossSalary = salaryBreakdown.gross_salary || 0;
                $('#gross-salary-display').text(formatCurrency(grossSalary));
            }

            // Calculate projected increment
            function calculateProjectedIncrement() {
                const form = $('#employeeIncrementForm');
                const incrementMethod = $('#increment_method').val();
                const incrementBase = $('#increment_base').val();
                const incrementAmount = parseFloat($('#increment_amount').val() || 0);

                if (!incrementMethod || !incrementBase || incrementAmount <= 0) {
                    return;
                }

                const basicSalary = parseFloat(form.data('basic-salary') || 0);
                const grossSalary = parseFloat(form.data('gross-salary') || 0);

                if (basicSalary === 0 && grossSalary === 0) {
                    return;
                }

                let baseAmount = incrementBase === 'basic_salary' ? basicSalary : grossSalary;
                let incrementValue = 0;

                if (incrementMethod === 'fixed') {
                    incrementValue = incrementAmount;
                } else if (incrementMethod === 'percentage') {
                    incrementValue = (baseAmount * incrementAmount) / 100;
                }

                // Optional: Display projected increment value
                console.log('Projected Increment: ৳' + formatCurrency(incrementValue));
            }

            // Currency formatter
            function formatCurrency(amount) {
                return parseFloat(amount).toLocaleString('en-BD', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            }

            // Trigger change on page load if editing
            @if (isset($increment))
                $('#employee_id').trigger('change');
            @endif
        });
    </script>
@endsection
