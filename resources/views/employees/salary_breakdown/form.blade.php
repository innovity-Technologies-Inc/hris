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
                            <!-- Basic Salary & Currency -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-bold">Basic Salary <span class="text-danger">*</span></label>
                                        <input type="number" step="0.01"
                                            class="form-control"
                                            name="basic_salary" id="basic_salary"
                                            value="{{isset($employeeData) ? $employeeData->basic_salary : old('basic_salary')}}"
                                            placeholder="0.00" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-bold">Currency <span class="text-danger">*</span></label>
                                        <select class="form-select" name="currency" required>
                                            <option value="BDT"  {{isset($employeeData) && $employeeData->currency == 'BDT' ? 'selected' : '' }} {{ old('currency') == 'BDT' ? 'selected' : '' }}>BDT - Bangladeshi Taka</option>
                                            <option value="USD" {{isset($employeeData) && $employeeData->currency == 'USD' ? 'selected' : '' }} {{ old('currency') == 'USD' ? 'selected' : '' }}>USD - US Dollar</option>
                                            <option value="EUR" {{isset($employeeData) && $employeeData->currency == 'EUR' ? 'selected' : '' }} {{ old('currency') == 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
                                            <option value="LB" {{isset($employeeData) && $employeeData->currency == 'LB' ? 'selected' : '' }} {{ old('currency') == 'LB' ? 'selected' : '' }}>LB - Pound</option>

                                        </select>
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
                                        <label class="form-label">House Allowance</label>
                                        <input type="number" step="0.01"
                                            class="form-control earnings-input"
                                            name="house_allowance"
                                            value="{{isset($employeeData) ? $employeeData->house_allowance : old('house_allowance')}}"
                                            placeholder="0.00">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Transport Allowance</label>
                                        <input type="number" step="0.01"
                                            class="form-control earnings-input"
                                            name="transport_allowance"
                                            value="{{isset($employeeData) ? $employeeData->transport_allowance : old('transport_allowance')}}"
                                            placeholder="0.00">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Food Allowance</label>
                                        <input type="number" step="0.01"
                                            class="form-control earnings-input"
                                            name="food_allowance"
                                            value="{{isset($employeeData) ? $employeeData->food_allowance : old('food_allowance')}}"
                                            placeholder="0.00">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Medical Allowance</label>
                                        <input type="number" step="0.01"
                                            class="form-control earnings-input"
                                            name="medical_allowance"
                                            value="{{isset($employeeData) ? $employeeData->medical_allowance : old('medical_allowance')}}"
                                            placeholder="0.00">
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Other Earnings</label>
                                        <input type="number" step="0.01"
                                            class="form-control earnings-input"
                                            name="other_earnings"
                                            value="{{isset($employeeData) ? $employeeData->other_earnings : old('other_earnings')}}"
                                            placeholder="0.00">
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
                                            <input type="hidden" name="gross_salary" id="gross_salary" value="0">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
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
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Save Salary Breakdown
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get all input fields
            const basicSalary = document.getElementById('basic_salary');
            const earningsInputs = document.querySelectorAll('.earnings-input');

            // Function to format number with commas
            function formatNumber(num) {
                return num.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            }

            // Function to calculate and update salary totals
            function calculateSalary() {
                // Get basic salary
                let basic = parseFloat(basicSalary.value) || 0;

                // Calculate total benefits (all allowances + bonuses)
                let totalBenefits = 0;
                earningsInputs.forEach(input => {
                    totalBenefits += parseFloat(input.value) || 0;
                });

                // Calculate gross salary (basic + benefits)
                let grossSalary = basic + totalBenefits;

                // Update display with formatted numbers
                document.getElementById('basic_salary_display').textContent = formatNumber(basic);
                document.getElementById('total_benefits_display').textContent = formatNumber(totalBenefits);
                document.getElementById('gross_salary_display').textContent = formatNumber(grossSalary);

                // Update hidden input
                document.getElementById('gross_salary').value = grossSalary.toFixed(2);
            }

            // Add event listeners
            basicSalary.addEventListener('input', calculateSalary);
            earningsInputs.forEach(input => {
                input.addEventListener('input', calculateSalary);
            });

            // Initial calculation on page load
            calculateSalary();
        });
    </script>
@endsection
