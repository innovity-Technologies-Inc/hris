@extends('structure.master')
@section('content')
    <div class="mt-4">
        @if(!isset($employeeData))
            @include('employees.partials.creation_button')
        @endif
            <form class="" method="POST" enctype="multipart/form-data"
                  action="{{isset($employeeData) ? route('employees.salary_breakdown.update', $employeeData->id) : route('employees.salary_breakdown.store') }}">
                @if(isset($employeeData))
                    @method('PUT')
                @endif
                @csrf

            <!-- Employee Information Section -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="card-title mb-0 text-white">
                                <i class="bi bi-person-badge"></i> Employee Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label for="employee_id" class="form-label">Employee Name <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" readonly
                                               value="{{ $employee->full_name }}">

                                        <input type="hidden" name="employee_id" value="{{ $employee->id }}">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Salary Breakdown Section -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-success text-white">
                            <h5 class="card-title mb-0 text-white">
                                <i class="bi bi-cash-stack"></i> Salary Breakdown Details
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-bold">Gross Salary<span class="text-danger">*</span></label>
                                        <input type="number" step="1"
                                               class="form-control"
                                               name="gross_salary" id="gross_salary"
                                               value="{{isset($employeeData) ? $employeeData->gross_salary : old('gross_salary')}}"
                                               placeholder="30000" required>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <!-- Basic Salary & Currency -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-bold">Basic Salary(%) <span class="text-danger">*</span></label>
                                        <input type="number" step="1"
                                            class="form-control"
                                            name="basic_salary_percentage" id="basic_salary_percentage"
                                            value="{{isset($employeeData) ? $employeeData->basic_salary_percentage : old('basic_salary_percentage')}}"
                                            placeholder="70" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-bold">Basic Salary <span class="text-danger">*</span></label>
                                        <input type="number" step="0.01"
                                               class="form-control"
                                               name="basic_salary" id="basic_salary"
                                               value="{{isset($employeeData) ? $employeeData->basic_salary : old('basic_salary')}}"
                                               placeholder="0.00" readonly>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <!-- Allowances Section -->
                            <h6 class="fw-bold text-success mb-3">
                                <i class="bi bi-plus-circle"></i> Allowances & Benefits
                            </h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label class="form-label">House Allowance(%)</label>
                                        <input type="number" step="1"
                                            class="form-control earnings-input"
                                            name="house_allowance_percentage"
                                            value="{{isset($employeeData) ? $employeeData->house_allowance_percentage : old('house_allowance_percentage')}}"
                                            placeholder="5">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label class="form-label">House Allowance</label>
                                        <input type="number"
                                            class="form-control earnings-input"
                                            name="house_allowance"
                                            value="{{isset($employeeData) ? $employeeData->house_allowance : old('house_allowance')}}"
                                            placeholder="0.00" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Transport Allowance(%)</label>
                                        <input type="number" step="1"
                                            class="form-control earnings-input"
                                            name="transport_allowance_percentage"
                                            value="{{isset($employeeData) ? $employeeData->transport_allowance_percentage : old('transport_allowance_percentage')}}"
                                            placeholder="3">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Transport Allowance</label>
                                        <input type="number"
                                            class="form-control earnings-input"
                                            name="transport_allowance"
                                            value="{{isset($employeeData) ? $employeeData->transport_allowance : old('transport_allowance')}}"
                                            placeholder="0.00" readonly>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Food Allowance(%)</label>
                                        <input type="number" step="1"
                                            class="form-control earnings-input"
                                            name="food_allowance_percentage"
                                            value="{{isset($employeeData) ? $employeeData->food_allowance_percentage : old('food_allowance_percentage')}}"
                                            placeholder="3">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Food Allowance</label>
                                        <input type="number"
                                            class="form-control earnings-input"
                                            name="food_allowance"
                                            value="{{isset($employeeData) ? $employeeData->food_allowance : old('food_allowance')}}"
                                            placeholder="0.00" readonly>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Medical Allowance(%)</label>
                                        <input type="number" step="1"
                                            class="form-control earnings-input"
                                            name="medical_allowance_percentage"
                                            value="{{isset($employeeData) ? $employeeData->medical_allowance_percentage : old('medical_allowance_percentage')}}"
                                            placeholder="3">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Medical Allowance</label>
                                        <input type="number"
                                            class="form-control earnings-input"
                                            name="medical_allowance"
                                            value="{{isset($employeeData) ? $employeeData->medical_allowance : old('medical_allowance')}}"
                                            placeholder="0.00" readonly>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Other Earnings(%)</label>
                                        <input type="number" step="1"
                                            class="form-control earnings-input"
                                            name="other_earnings_percentage"
                                            value="{{isset($employeeData) ? $employeeData->other_earnings_percentage : old('other_earnings_percentage')}}"
                                            placeholder="1">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Other Earnings</label>
                                        <input type="number"
                                            class="form-control earnings-input"
                                            name="other_earnings"
                                            value="{{isset($employeeData) ? $employeeData->other_earnings : old('other_earnings')}}"
                                            placeholder="0.00" readonly>
                                    </div>
                                </div>

                            </div>

                            <hr class="my-4">

                            <!-- Summary Section -->
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="card bg-light">
                                        <div class="card-body text-center">
                                            <h6 class="text-muted mb-2">Basic Salary</h6>
                                            <h4 class="text-success fw-bold mb-0" id="basic_salary_display">0.00</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card bg-light">
                                        <div class="card-body text-center">
                                            <h6 class="text-muted mb-2">Total Benefits</h6>
                                            <h4 class="text-success fw-bold mb-0" id="total_benefits_display">0.00</h4>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card bg-primary text-white">
                                        <div class="card-body text-center">
                                            <h6 class="text-white mb-2">Gross Salary</h6>
                                            <h4 class="text-white fw-bold mb-0" id="gross_salary_display">0.00</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="alert {{ isset($employeeData) ? 'alert-light' : 'alert-warning' }} border d-flex justify-content-between align-items-center">
                                <span><i class="bi bi-info-circle"></i> Note: Total allocation must equal 100%.</span>
                                <span class="fs-5">Total: <span id="total_pct_display">0%</span></span>
                            </div>
                        </div>
                    </div>

            <!-- Form Actions -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Cancel
                        </button>
                        <button type="button" id="previewBtn" class="btn btn-info text-white">
                            <i class="mdi mdi-eye me-1"></i> Preview
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Save Salary Breakdown
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    @include('employees.partials.preview_modal')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const grossInput = document.getElementById('gross_salary');
            const percentageInputs = document.querySelectorAll('input[name$="_percentage"]');
            const form = document.querySelector('form');

            function validateAndCalculate(event) {
                const grossSalary = parseFloat(grossInput.value) || 0;
                let totalAllocated = 0;

                // 1. Calculate sum of other fields
                percentageInputs.forEach(input => {
                    if (event && event.target === input) return;
                    totalAllocated += parseFloat(input.value) || 0;
                });

                // 2. Validate the active input with SweetAlert
                if (event && event.target.name.includes('_percentage')) {
                    let newVal = parseFloat(event.target.value) || 0;

                    if (totalAllocated + newVal > 100) {
                        const maxAllowed = 100 - totalAllocated;
                        event.target.value = maxAllowed > 0 ? maxAllowed : 0;

                        Swal.fire({
                            icon: 'warning',
                            title: 'Limit Exceeded',
                            text: `Total percentage cannot exceed 100%. This field has been capped at ${event.target.value}%`,
                            confirmButtonColor: '#3085d6'
                        });
                    }
                }

                // 3. Update all dollar amounts
                let runningTotalPercent = 0;
                let totalBenefits = 0;
                let basicAmount = 0;

                percentageInputs.forEach(pInput => {
                    const percentage = parseFloat(pInput.value) || 0;
                    runningTotalPercent += percentage;

                    const amount = (grossSalary * percentage) / 100;
                    const valueInputName = pInput.name.replace('_percentage', '');
                    const valueInput = document.getElementsByName(valueInputName)[0];

                    if (valueInput) {
                        valueInput.value = amount.toFixed(2);
                    }

                    if (valueInputName === 'basic_salary') {
                        basicAmount = amount;
                    } else {
                        totalBenefits += amount;
                    }
                });

                updateSummary(basicAmount, totalBenefits, grossSalary, runningTotalPercent);
            }

            function updateSummary(basic, benefits, gross, totalPct) {
                const format = (num) => num.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });

                document.getElementById('basic_salary_display').textContent = format(basic);
                document.getElementById('total_benefits_display').textContent = format(benefits);
                document.getElementById('gross_salary_display').textContent = format(gross);

                const pctDisplay = document.getElementById('total_pct_display');
                if(pctDisplay) {
                    pctDisplay.textContent = totalPct + "%";
                    pctDisplay.className = (totalPct === 100) ? "fw-bold text-success" : "fw-bold text-danger";
                }
            }

            // Event Listeners
            grossInput.addEventListener('input', validateAndCalculate);
            percentageInputs.forEach(input => {
                input.addEventListener('input', validateAndCalculate);
            });

            // Final Submission Check with SweetAlert
            form.addEventListener('submit', function(e) {
                let total = 0;
                percentageInputs.forEach(i => total += parseFloat(i.value) || 0);

                if (total !== 100) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Incomplete Breakdown',
                        text: `The total must be exactly 100%. Currently at ${total}%`,
                        footer: 'Please adjust your percentages before saving.'
                    });
                }
            });

            validateAndCalculate();
        });
    </script>
@endsection
