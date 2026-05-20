@extends('structure.master')
@section('content')
    <div class="container-fluid mt-4">

        <form method="POST"
            action="{{ isset($plan) ? route('plan.bonus_plans.update', $plan->id) : route('plan.bonus_plans.store') }}"
            enctype="multipart/form-data">
            @if (isset($plan))
                @method('PUT')
            @endif
            @csrf

            <!-- Basic Bonus Information -->
            <div class="card border mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0 fw-semibold">
                        <i class="mdi mdi-gift-outline text-primary me-2"></i>Basic Bonus Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Bonus Plan Name -->
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label fw-semibold">
                                Bonus Plan Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="E.g., Eid Festival Bonus 2025"
                                value="{{ isset($plan) ? $plan->name : old('name') }}"
                                required>
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Bonus Type -->
                        <div class="col-md-6 mb-3">
                            <label for="bonus_type" class="form-label fw-semibold">
                                Bonus Type <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" id="bonus_type" name="bonus_type" required>
                                <option value="">Select Bonus Type</option>
                                <option value="festival" {{ isset($plan) && $plan->bonus_type == 'festival' ? 'selected' : '' }}>
                                    Festival Bonus</option>
                                <option value="performance" {{ isset($plan) && $plan->bonus_type == 'performance' ? 'selected' : '' }}>
                                    Performance Bonus</option>
                                <option value="annual" {{ isset($plan) && $plan->bonus_type == 'annual' ? 'selected' : '' }}>
                                    Annual Bonus</option>
                                <option value="incentive" {{ isset($plan) && $plan->bonus_type == 'incentive' ? 'selected' : '' }}>
                                    Incentive</option>
                                <option value="retention" {{ isset($plan) && $plan->bonus_type == 'retention' ? 'selected' : '' }}>
                                    Retention Bonus</option>
                                <option value="other" {{ isset($plan) && $plan->bonus_type == 'other' ? 'selected' : '' }}>
                                    Other</option>
                            </select>
                            @error('bonus_type')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="description" class="form-label fw-semibold">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"
                                placeholder="Enter bonus plan details and eligibility criteria...">{{ isset($plan) ? $plan->description : old('description') }}</textarea>
                            @error('description')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bonus Calculation Configuration -->
            <div class="card border mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0 fw-semibold">
                        <i class="mdi mdi-cash-multiple text-success me-2"></i>Bonus Calculation Configuration
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Main Configuration Type Selection -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">
                                Configuration Type <span class="text-danger">*</span>
                            </label>
                            <div class="d-flex gap-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="bonus_config_type"
                                        id="config_salary_based" value="Salary Based"
                                        {{ !isset($plan) || (isset($plan) && $plan->bonus_config_type == 'Salary Based') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="config_salary_based">
                                        Based on Salary
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="bonus_config_type"
                                        id="config_custom" value="Custom"
                                        {{ isset($plan) && $plan->bonus_config_type == 'Custom' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="config_custom">
                                        Custom Amount
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Salary-Based Bonus Configuration -->
                    <div id="salary_based_section" class="border rounded p-3 mb-3">
                        <h6 class="fw-semibold mb-3">
                            <i class="mdi mdi-calculator text-primary me-1"></i>Salary-Based Calculation
                        </h6>

                        <!-- Rate Type Selection -->
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-semibold">
                                    Calculation Method <span class="text-danger">*</span>
                                </label>
                                <div class="d-flex gap-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="salary_rate_type"
                                            id="rate_type_basic" value="Basic Rate"
                                            {{ isset($plan) && $plan->salary_rate_type == 'Basic Rate' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="rate_type_basic">
                                            Basic Salary (100%)
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="salary_rate_type"
                                            id="rate_type_multiplier" value="Multiplier"
                                            {{ !isset($plan) || (isset($plan) && $plan->salary_rate_type == 'Multiplier') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="rate_type_multiplier">
                                            Percentage of Salary
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Multiplier Field (Percentage) -->
                        <div class="row" id="multiplier_field_row">
                            <div class="col-md-4 mb-3">
                                <label for="multiplier" class="form-label fw-semibold">
                                    Bonus Percentage <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="number" step="0.01" min="0" max="5" class="form-control form-control-sm"
                                        id="multiplier" name="multiplier" placeholder="1.0"
                                        value="{{ isset($plan) ? $plan->multiplier : old('multiplier', '1.0') }}"
                                        style="max-width: 120px;">
                                    <span class="input-group-text bg-light">× Basic Salary</span>
                                </div>
                                <small class="text-muted">E.g., 1.0 = 100%, 2.0 = 200%, 0.5 = 50%</small>
                                @error('multiplier')
                                    <span class="text-danger d-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Custom Fixed Amount -->
                    <div id="custom_rate_section" class="border rounded p-3">
                        <h6 class="fw-semibold mb-3">
                            <i class="mdi mdi-cash text-success me-1"></i>Fixed Bonus Amount
                        </h6>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="custom_rate" class="form-label fw-semibold">
                                    Bonus Amount <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">{{\App\HelperClass::getGeneralSetting()->currency ?? '৳'}}</span>
                                    <input type="number" step="0.01" min="0" class="form-control"
                                        id="custom_rate" name="custom_rate"
                                        placeholder="Enter fixed bonus amount"
                                        value="{{ isset($plan) ? $plan->custom_rate : old('custom_rate') }}">
                                </div>
                                <small class="text-muted">Fixed amount regardless of salary</small>
                                @error('custom_rate')
                                    <span class="text-danger d-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Plan Status -->
            <div class="card border mb-4">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-semibold">
                        <i class="mdi mdi-toggle-switch text-primary me-2"></i>Plan Status
                    </h5>
                    <div class="form-check form-switch mb-0">
                        <input type="hidden" name="status" value="inactive">
                        <input class="form-check-input" type="checkbox" name="status" id="status"
                            value="active"
                            {{ (isset($plan) && $plan->status == 'active') || old('status', 'active') == 'active' ? 'checked' : '' }}>
                        <label class="form-check-label" for="status">Active</label>
                    </div>
                    @error('status')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="card border mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-end gap-2">
                        <button type="reset" class="btn btn-secondary">
                            <i class="mdi mdi-refresh me-1"></i>Reset
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="mdi mdi-content-save me-1"></i>
                            {{ isset($plan) ? 'Update Bonus Plan' : 'Create Bonus Plan' }}
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        // ==========================================
        // FORM INITIALIZATION & RESET HANDLING
        // ==========================================
        document.querySelector('form').addEventListener('reset', function() {
            // Small delay to allow reset to complete
            setTimeout(function() {
                // Set default values after reset
                document.getElementById('status').checked = true;
                document.getElementById('config_salary_based').checked = true;
                document.getElementById('rate_type_multiplier').checked = true;

                // Refresh UI based on defaults
                toggleBonusConfigSections();
                toggleMultiplierField();
            }, 0);
        });

        // ==========================================
        // TOGGLE SALARY-BASED VS CUSTOM SECTIONS
        // ==========================================
        /**
         * Shows/hides and enables/disables form sections based on
         * whether salary-based or custom bonus calculation is selected.
         * Disabled inputs won't be submitted, preventing validation errors.
         */
        function toggleBonusConfigSections() {
            const isSalaryBased = document.getElementById('config_salary_based').checked;
            const salarySection = document.getElementById('salary_based_section');
            const customSection = document.getElementById('custom_rate_section');

            if (isSalaryBased) {
                // Enable salary-based section
                salarySection.style.opacity = '1';
                salarySection.style.pointerEvents = 'auto';
                salarySection.querySelectorAll('input').forEach(input => {
                    input.disabled = false;
                });

                // Disable custom section
                customSection.style.opacity = '0.5';
                customSection.style.pointerEvents = 'none';
                customSection.querySelectorAll('input').forEach(input => {
                    input.disabled = true;
                });
            } else {
                // Disable salary-based section
                salarySection.style.opacity = '0.5';
                salarySection.style.pointerEvents = 'none';
                salarySection.querySelectorAll('input').forEach(input => {
                    input.disabled = true;
                });

                // Enable custom section
                customSection.style.opacity = '1';
                customSection.style.pointerEvents = 'auto';
                customSection.querySelectorAll('input').forEach(input => {
                    input.disabled = false;
                });
            }
        }

        // ==========================================
        // TOGGLE MULTIPLIER FIELD VISIBILITY
        // ==========================================
        /**
         * Shows the percentage/multiplier input only when "Percentage of Salary"
         * calculation method is selected. Hidden for "Basic Salary (100%)" option.
         */
        function toggleMultiplierField() {
            const isMultiplier = document.getElementById('rate_type_multiplier').checked;
            const multiplierRow = document.getElementById('multiplier_field_row');

            multiplierRow.style.display = isMultiplier ? 'flex' : 'none';
        }

        // ==========================================
        // EVENT LISTENERS - INITIALIZE ON PAGE LOAD
        // ==========================================
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize UI state based on current form values
            toggleBonusConfigSections();
            toggleMultiplierField();

            // Listen for configuration type changes (Salary-Based vs Custom)
            document.getElementById('config_salary_based').addEventListener('change', toggleBonusConfigSections);
            document.getElementById('config_custom').addEventListener('change', toggleBonusConfigSections);

            // Listen for calculation method changes (Basic Rate vs Multiplier)
            document.getElementById('rate_type_basic').addEventListener('change', toggleMultiplierField);
            document.getElementById('rate_type_multiplier').addEventListener('change', toggleMultiplierField);
        });
    </script>
@endsection

