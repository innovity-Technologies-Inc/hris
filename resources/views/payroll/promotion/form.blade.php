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

        {{-- Promotion Details Section --}}
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Promotion Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            {{-- Hidden field for previous designation --}}
                            <input type="hidden" name="previous_designation" id="previous_designation">

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

                            {{-- New Basic Salary --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">New Basic Salary <span class="text-danger">*</span></label>
                                <input type="number" name="new_basic_salary" id="new_basic_salary"
                                    value="{{ old('new_basic_salary', $promotionData->new_basic_salary ?? '') }}"
                                    class="form-control @error('new_basic_salary') is-invalid @enderror" step="0.01"
                                    min="0" required placeholder="Enter new basic salary">
                                @error('new_basic_salary')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                                <small class="text-muted">Enter the new basic salary after promotion</small>
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

                            {{-- Status (if editing) --}}
                            @isset($promotionData)
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-select @error('status') is-invalid @enderror">
                                        <option value="pending" {{ $promotionData->status == 'pending' ? 'selected' : '' }}>
                                            Pending
                                        </option>
                                        <option value="approved" {{ $promotionData->status == 'approved' ? 'selected' : '' }}>
                                            Approved
                                        </option>
                                        <option value="rejected" {{ $promotionData->status == 'rejected' ? 'selected' : '' }}>
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

                    // Set previous designation as hidden field
                    $('#previous_designation').val(officeInfo.current_designation_id);

                    // Store salary data
                    $('#employeePromotionForm').data('basic-salary', salaryBreakdown.basic_salary || 0);
                    $('#employeePromotionForm').data('gross-salary', salaryBreakdown.gross_salary || 0);

                    // Show sections
                    $('#current-designation-section').show();
                    $('#salary-breakdown-section').show();
                } else {
                    // Hide sections if no data
                    $('#current-designation-section').hide();
                    $('#salary-breakdown-section').hide();
                    $('#previous_designation').val('');
                }
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

            // Currency formatter
            function formatCurrency(amount) {
                return parseFloat(amount).toLocaleString('en-BD', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            }

            // Trigger change on page load if editing
            @if (isset($promotionData))
                $('#employee_id').trigger('change');
            @endif
        });
    </script>
@endsection
