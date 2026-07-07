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

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


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

                            {{-- Pay Scale --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Pay Scale <span class="text-danger">*</span></label>
                                <select name="pay_scale_id" id="pay_scale_id"
                                    class="form-select @error('pay_scale_id') is-invalid @enderror" required>
                                    <option value="">Select Pay Scale</option>
                                    @foreach ($payScales as $scale)
                                        <option value="{{ $scale->id }}"
                                            data-min="{{ $scale->min_salary }}"
                                            data-max="{{ $scale->max_salary }}"
                                            {{ old('pay_scale_id', $promotionData->pay_scale_id ?? '') == $scale->id ? 'selected' : '' }}>
                                            {{ $scale->title }} ({{ \App\HelperClass::getCurrency() }} {{ number_format($scale->min_salary, 0) }} - {{ number_format($scale->max_salary, 0) }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('pay_scale_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                                <small class="text-muted" id="payscale-range-hint" style="display: none;">
                                    Selected Range: <span id="payscale-min-display">0.00</span> - <span id="payscale-max-display">0.00</span>
                                </small>
                            </div>

                            {{-- Movement Type --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Movement Type</label>
                                <select name="movement_type_id" id="movement_type_id"
                                    class="form-select @error('movement_type_id') is-invalid @enderror">
                                    <option value="">Select Movement Type</option>
                                    @foreach ($movementTypes as $type)
                                        <option value="{{ $type->id }}"
                                            {{ old('movement_type_id', $promotionData->movement_type_id ?? '') == $type->id ? 'selected' : '' }}>
                                            {{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('movement_type_id')
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
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        $(document).ready(function() {

            // Submit form via Axios
            $('#employeePromotionForm').on('submit', function(e) {
                e.preventDefault();

                // Clear prior validation errors
                $('.text-danger.error-msg').remove();
                $('.is-invalid').removeClass('is-invalid');

                const form = $(this);
                const actionUrl = form.attr('action');
                const method = form.find('input[name="_method"]').val() || 'POST';
                const formData = form.serialize();

                axios({
                    method: method,
                    url: actionUrl,
                    data: formData
                })
                .then(res => {
                    if (res.data.success) {
                        if (window.Swal) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: res.data.message,
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.href = res.data.redirect_url;
                            });
                        } else {
                            window.location.href = res.data.redirect_url;
                        }
                    } else {
                        if (window.Swal) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: res.data.message || 'Something went wrong'
                            });
                        } else {
                            alert(res.data.message || 'Something went wrong');
                        }
                    }
                })
                .catch(err => {
                    if (err.response && err.response.status === 422) {
                        const errors = err.response.data.errors;
                        Object.keys(errors).forEach(key => {
                            const input = $(`[name="${key}"]`);
                            input.addClass('is-invalid');
                            input.after(`<small class="text-danger error-msg">${errors[key][0]}</small>`);
                        });
                        if (window.Swal) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Validation Error',
                                text: 'Please correct the highlighted fields.'
                            });
                        }
                    } else {
                        const errMsg = err.response?.data?.message || 'Something went wrong.';
                        if (window.Swal) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: errMsg
                            });
                        } else {
                            alert(errMsg);
                        }
                    }
                });
            });

            /**
             * 1. EMPLOYEE SELECTION HANDLER
             */
            $('#employee_id').on('change', function() {
                const employeeId = $(this).val();

                if (!employeeId) {
                    $('#current-designation-section, #salary-breakdown-section').hide();
                    return;
                }

                $.ajax({
                    url: `/get-current-designation/${employeeId}`,
                    type: 'GET',
                    success: function(response) {
                        const officeInfo = response.employee;
                        if (officeInfo) {
                            displayCurrentDesignation(officeInfo);
                            $('#previous_designation').val(officeInfo.current_designation_id || '');
                            $('#current-designation-section').show();
                        }
                    }
                });

                $.ajax({
                    url: `/get-employee-salary/${employeeId}`,
                    type: 'GET',
                    success: function(response) {
                        const salary = response.employee;
                        if (salary) {
                            displaySalaryBreakdown(salary);
                            $('#previous_basic_salary').val(salary.basic_salary || 0);
                            $('#previous_gross_salary').val(salary.gross_salary || 0);
                            $('#salary-breakdown-section').show();

                            // Pre-select employee's current pay scale if not in edit mode
                            @if(!isset($promotionData))
                                if (salary.pay_scale_id) {
                                    $('#pay_scale_id').val(salary.pay_scale_id).trigger('change');
                                } else {
                                    $('#pay_scale_id').val('').trigger('change');
                                }
                            @endif

                            verifyPayScale();
                        }
                    }
                });
            });

            /**
             * 2. UI DISPLAY FUNCTIONS
             */
            function displayCurrentDesignation(officeInfo) {
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

            // 3. Pay Scale Change Handler & Verification
            $('#pay_scale_id').on('change', function() {
                const selectedOption = $(this).find('option:selected');
                const min = parseFloat(selectedOption.data('min')) || 0;
                const max = parseFloat(selectedOption.data('max')) || 0;

                if (min > 0 || max > 0) {
                    $('#payscale-min-display').text(formatCurrency(min));
                    $('#payscale-max-display').text(formatCurrency(max));
                    $('#payscale-range-hint').show();
                } else {
                    $('#payscale-range-hint').hide();
                }

                verifyPayScale();
            });

            function verifyPayScale() {
                const currentBasicSalary = parseFloat($('#previous_basic_salary').val()) || 0;
                const currentGrossSalary = parseFloat($('#previous_gross_salary').val()) || 0;
                const base = $('#increment_base').val();
                const method = $('#increment_method').val();
                const amount = parseFloat($('#salary_increase_amount').val()) || 0;

                let incrementValue = 0;
                if (base && method && amount > 0) {
                    if (method === 'percentage') {
                        if (base === 'basic_salary') {
                            incrementValue = currentBasicSalary * (amount / 100);
                        } else {
                            incrementValue = currentGrossSalary * (amount / 100);
                        }
                    } else {
                        incrementValue = amount;
                    }
                }

                const newGrossSalary = currentGrossSalary + incrementValue;

                // Remove existing warning
                $('#payscale-warning').remove();

                const selectedOption = $('#pay_scale_id option:selected');
                if (selectedOption.val()) {
                    const min = parseFloat(selectedOption.data('min')) || 0;
                    const max = parseFloat(selectedOption.data('max')) || 0;

                    if (max > 0 && newGrossSalary > max) {
                        const warningMsg = `Warning: The incremented salary (${formatCurrency(newGrossSalary)}) surpasses the selected pay scale maximum limit of (${formatCurrency(max)}).`;
                        $('#pay_scale_id').after(`<div id="payscale-warning" class="alert alert-warning py-1 px-2 mt-2 mb-0 small text-danger"><i class="bi bi-exclamation-triangle"></i> ${warningMsg}</div>`);
                    } else if (min > 0 && newGrossSalary < min) {
                        const warningMsg = `Warning: The incremented salary (${formatCurrency(newGrossSalary)}) is below the selected pay scale minimum limit of (${formatCurrency(min)}).`;
                        $('#pay_scale_id').after(`<div id="payscale-warning" class="alert alert-warning py-1 px-2 mt-2 mb-0 small text-danger"><i class="bi bi-exclamation-triangle"></i> ${warningMsg}</div>`);
                    }
                }
            }

            $('#increment_base, #increment_method').on('change', verifyPayScale);
            $('#salary_increase_amount').on('input', verifyPayScale);

            /**
             * 4. UTILITIES & INITIALIZATION
             */
            function formatCurrency(amount) {
                return parseFloat(amount).toLocaleString('en-BD', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            }

            $('#increment_method').on('change', function() {
                const method = $(this).val();
                const hint = (method === 'percentage') ? 'Enter % (e.g. 10)' : 'Enter Fixed Amount';
                $('#increment-hint').text(hint);
            });

            if ($('#employee_id').val()) {
                $('#employee_id').trigger('change');
            }
        });
    </script>
@endsection
