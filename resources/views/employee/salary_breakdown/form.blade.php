@extends('structure.master')
@section('content')
    <div class="mt-4">
            <form class="" method="POST" enctype="multipart/form-data"
                  action="{{isset($employeeData) ? route('employee.salary_breakdown.update', $employeeData->id) : route('employee.salary_breakdown.store') }}">
                @if(isset($employeeData))
                    @method('PUT')
                @endif
                @csrf
                <input type="hidden" name="employee_id" value="{{ $employee->id }}">

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
                                        <label class="form-label fw-bold">Pay Scale <span class="text-danger">*</span></label>
                                        <select class="form-select" name="pay_scale_id" id="pay_scale_id" required>
                                            <option value="">Select Pay Scale</option>
                                            @foreach($payScales as $scale)
                                                <option value="{{ $scale->id }}" {{ (isset($employeeData) && $employeeData->pay_scale_id == $scale->id) || old('pay_scale_id') == $scale->id ? 'selected' : '' }}>
                                                    {{ $scale->title }} ({{ \App\HelperClass::getCurrency() }} {{ number_format($scale->min_salary, 0) }} - {{ number_format($scale->max_salary, 0) }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-bold">Gross Salary<span class="text-danger">*</span></label>
                                        <input type="number" step="1"
                                               class="form-control"
                                               name="gross_salary" id="gross_salary"
                                               value="{{isset($employeeData) ? $employeeData->gross_salary : old('gross_salary')}}"
                                               placeholder="30000" required>
                                        <div id="payscale_hint" class="small mt-1 text-muted d-none">
                                            Pay Scale Range: <span id="min_val">0</span> - <span id="max_val">0</span>
                                        </div>
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
    @include('employee.partials.preview_modal')

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const grossInput = document.getElementById('gross_salary');
            const payscaleSelect = document.getElementById('pay_scale_id');
            const payscaleHint = document.getElementById('payscale_hint');
            const minValSpan = document.getElementById('min_val');
            const maxValSpan = document.getElementById('max_val');
            const percentageInputs = document.querySelectorAll('input[name$="_percentage"]');
            const form = document.querySelector('form');

            let currentMin = 0;
            let currentMax = 0;

            // --- Pay Scale Details Fetching ---
            payscaleSelect.addEventListener('change', function() {
                const id = this.value;
                if (!id) {
                    payscaleHint.classList.add('d-none');
                    currentMin = 0;
                    currentMax = 0;
                    return;
                }

                axios.get(`/get-pay-scale-details/${id}`)
                    .then(res => {
                        const scale = res.data.data;
                        currentMin = parseFloat(scale.min_salary);
                        currentMax = parseFloat(scale.max_salary);
                        
                        minValSpan.innerText = currentMin.toLocaleString();
                        maxValSpan.innerText = currentMax.toLocaleString();
                        payscaleHint.classList.remove('d-none');
                        
                        validateGrossRange();
                    });
            });

            // Trigger on load if editing
            if (payscaleSelect.value) {
                payscaleSelect.dispatchEvent(new Event('change'));
            }

            function validateGrossRange() {
                const gross = parseFloat(grossInput.value) || 0;
                if (currentMin > 0 && (gross < currentMin || gross > currentMax)) {
                    grossInput.classList.add('is-invalid');
                    return false;
                } else {
                    grossInput.classList.remove('is-invalid');
                    return true;
                }
            }

            function validateAndCalculate(event) {
                validateGrossRange();
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
                const gross = parseFloat(grossInput.value) || 0;
                
                if (currentMin > 0 && (gross < currentMin || gross > currentMax)) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Salary Range Violation',
                        text: `The Gross Salary (${gross}) must be between ${currentMin} and ${currentMax} for the selected Pay Scale.`,
                    });
                    return;
                }

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
