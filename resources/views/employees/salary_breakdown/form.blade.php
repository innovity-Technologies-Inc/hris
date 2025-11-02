@extends('structure.master')
@section('content')
    <div class="mt-4">
        <form class="" method="POST" enctype="multipart/form-data" action="#" autocomplete="off">
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
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-bold">Employee Name</label>
                                        <input type="text" class="form-control bg-light"
                                            value=""
                                            readonly>
                                        <input type="hidden" name="employee_id" value="">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-bold">Effective Date <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control"
                                            name="effective_date"
                                            value=""
                                            required>
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
                                            value=""
                                            placeholder="0.00" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-bold">Currency <span class="text-danger">*</span></label>
                                        <select class="form-select" name="currency" required>
                                            <option value="BDT" selected>BDT - Bangladeshi Taka</option>
                                            <option value="USD">USD - US Dollar</option>
                                            <option value="EUR">EUR - Euro</option>
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
                                            value=""
                                            placeholder="0.00">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Transport Allowance</label>
                                        <input type="number" step="0.01"
                                            class="form-control earnings-input"
                                            name="transport_allowance"
                                            value=""
                                            placeholder="0.00">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Food Allowance</label>
                                        <input type="number" step="0.01"
                                            class="form-control earnings-input"
                                            name="food_allowance"
                                            value=""
                                            placeholder="0.00">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Medical Allowance</label>
                                        <input type="number" step="0.01"
                                            class="form-control earnings-input"
                                            name="medical_allowance"
                                            value=""
                                            placeholder="0.00">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Performance Bonus</label>
                                        <input type="number" step="0.01"
                                            class="form-control earnings-input"
                                            name="performance_bonus"
                                            value=""
                                            placeholder="0.00">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Overtime Pay</label>
                                        <input type="number" step="0.01"
                                            class="form-control earnings-input"
                                            name="overtime_pay"
                                            value=""
                                            placeholder="0.00">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Other Earnings (Commissions, Incentives, etc.)</label>
                                        <input type="number" step="0.01"
                                            class="form-control earnings-input"
                                            name="other_earnings"
                                            value=""
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
