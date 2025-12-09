@extends('structure.master')
@section('content')
    <div class="container-fluid mt-4">

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        <form method="POST"
            action="{{ isset($plan) ? route('plans.ot_plans.update', $plan->id) : route('plans.ot_plans.store') }}"
            enctype="multipart/form-data">
            @if (isset($plan))
                @method('PUT')
            @endif
            @csrf

            <!-- Basic OT Information -->
            <div class="card border mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0 fw-semibold">
                        <i class="mdi mdi-clock-plus-outline text-primary me-2"></i>Basic OT Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label fw-semibold">
                                OT Plan Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="E.g., Regular OT - 1.5x" value="{{ isset($plan) ? $plan->name : old('name') }}"
                                required>
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="ot_type" class="form-label fw-semibold">
                                Overtime Type <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" id="ot_type" name="ot_type" required>
                                <option value="">Select OT Type</option>
                                <option value="regular" {{ isset($plan) && $plan->ot_type == 'regular' ? 'selected' : '' }}>
                                    Regular</option>
                                <option value="holiday" {{ isset($plan) && $plan->ot_type == 'holiday' ? 'selected' : '' }}>
                                    Holiday</option>
                                <option value="night_shift"
                                    {{ isset($plan) && $plan->ot_type == 'night_shift' ? 'selected' : '' }}>Night Shift
                                </option>
                                <option value="weekend" {{ isset($plan) && $plan->ot_type == 'weekend' ? 'selected' : '' }}>
                                    Weekend</option>
                                <option value="other" {{ isset($plan) && $plan->ot_type == 'other' ? 'selected' : '' }}>
                                    Other</option>
                            </select>
                            @error('ot_type')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="description" class="form-label fw-semibold">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"
                                placeholder="Enter plan description...">{{ isset($plan) ? $plan->description : old('description') }}</textarea>
                            @error('description')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Overtime Rate Configuration -->
            <div class="card border mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0 fw-semibold">
                        <i class="mdi mdi-cash-multiple text-success me-2"></i>Overtime Rate Configuration
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
                                    <input class="form-check-input" type="radio" name="ot_config_type"
                                        id="ot_config_salary" value="Salary Based"
                                        {{ !isset($plan) || (isset($plan) && $plan->ot_config_type != 'Salary Based') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="ot_config_salary">
                                        Based on Salary
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="ot_config_type"
                                        id="ot_config_custom" value="Custom"
                                        {{ isset($plan) && $plan->ot_config_type == 'Custom' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="ot_config_custom">
                                        Custom Rate
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Overtime Rate Configuration (Based on Salary) -->
                    <div id="salary_based_section" class="border rounded p-3 mb-3">
                        <h6 class="fw-semibold mb-3">
                            <i class="mdi mdi-calculator text-primary me-1"></i>Overtime Rate Configuration (Based on
                            Salary)
                        </h6>

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-semibold">
                                    Rate Type <span class="text-danger">*</span>
                                </label>
                                <div class="d-flex gap-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="salary_rate_type"
                                            id="rate_type_basic" value="Basic Rate"
                                            {{ isset($plan) && $plan->salary_rate_type == 'Basic Rate' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="rate_type_basic">
                                            Basic Rate
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="salary_rate_type"
                                            id="rate_type_multiplier" value="Multiplier"
                                            {{ !isset($plan) || (isset($plan) && $plan->salary_rate_type == 'Multiplier') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="rate_type_multiplier">
                                            Multiplier
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row" id="multiplier_field_row">
                            <div class="col-md-4 mb-3">
                                <label for="overtime_multiplier" class="form-label fw-semibold">
                                    Overtime Rate <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="number" step="0.01" class="form-control form-control-sm"
                                        id="overtime_multiplier" name="overtime_multiplier" placeholder="1.5"
                                        value="{{ isset($plan) ? $plan->overtime_multiplier : old('overtime_multiplier', '1.5') }}"
                                        style="max-width: 120px;">
                                    <span class="input-group-text bg-light">X Base Rate</span>
                                </div>
                                <small class="text-muted">Enter fractional number (e.g., 1.5, 2.0, 2.5)</small>
                                @error('overtime_multiplier')
                                    <span class="text-danger d-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Overtime Rate (Custom) -->
                    <div id="custom_rate_section" class="border rounded p-3">
                        <h6 class="fw-semibold mb-3">
                            <i class="mdi mdi-cash text-success me-1"></i>Overtime Rate (Custom)
                        </h6>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="custom_overtime_rate" class="form-label fw-semibold">
                                    Amount Per Hour <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">{{\App\HelperClass::getGeneralSetting()->currency ?? '৳'}}</span>
                                    <input type="number" step="0.01" class="form-control" id="custom_overtime_rate"
                                        name="custom_overtime_rate" placeholder="Enter amount per hour"
                                        value="{{ isset($plan) ? $plan->custom_overtime_rate : old('custom_overtime_rate') }}">
                                </div>
                                <small class="text-muted">Fixed amount per overtime hour</small>
                                @error('custom_overtime_rate')
                                    <span class="text-danger d-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- OT Hours Configuration -->
            <div class="card border mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0 fw-semibold">
                        <i class="mdi mdi-clock-time-eight-outline text-info me-2"></i>OT Hours Configuration
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="minimum_overtime_hours" class="form-label fw-semibold">
                                Minimum Overtime Hours
                            </label>
                            <input type="number" step="0.01" class="form-control" id="minimum_overtime_hours"
                                name="minimum_overtime_hours" placeholder="0.00"
                                value="{{ isset($plan) ? $plan->minimum_overtime_hours : old('minimum_overtime_hours', '0.00') }}">
                            <small class="text-muted">Minimum hours required to qualify for OT</small>
                            @error('minimum_overtime_hours')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="maximum_overtime_hours" class="form-label fw-semibold">
                                Maximum Overtime Hours
                            </label>
                            <input type="number" step="0.01" class="form-control" id="maximum_overtime_hours"
                                name="maximum_overtime_hours" placeholder="Leave empty for unlimited"
                                value="{{ isset($plan) ? $plan->maximum_overtime_hours : old('maximum_overtime_hours') }}">
                            <small class="text-muted">Maximum OT hours allowed (optional)</small>
                            @error('maximum_overtime_hours')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Applicable Time Range -->
            <div class="card border mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0 fw-semibold">
                        <i class="mdi mdi-calendar-clock text-warning me-2"></i>Applicable Time Range
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="overtime_start_time" class="form-label fw-semibold">
                                OT Start Time
                            </label>
                            <input type="time" class="form-control" id="overtime_start_time"
                                name="overtime_start_time"
                                value="{{ isset($plan) && $plan->overtime_start_time ? Carbon\Carbon::parse($plan->overtime_start_time)->format('H:i') : old('overtime_start_time') }}">
                            <small class="text-muted">Time when OT period begins (optional)</small>
                            @error('overtime_start_time')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="overtime_end_time" class="form-label fw-semibold">
                                OT End Time
                            </label>
                            <input type="time" class="form-control" id="overtime_end_time" name="overtime_end_time"
                                value="{{ isset($plan) && $plan->overtime_end_time ? Carbon\Carbon::parse($plan->overtime_end_time)->format('H:i') : old('overtime_end_time') }}">
                            <small class="text-muted">Time when OT period ends (optional)</small>
                            @error('overtime_end_time')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
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
                        <input type="hidden" name="active_ind" value="inactive">
                        <input class="form-check-input" type="checkbox" name="active_ind" id="active_ind"
                            value="active"
                            {{ (isset($plan) && $plan->active_ind == 'active') || old('active_ind', 'active') == 'active' ? 'checked' : '' }}>
                        <label class="form-check-label" for="active_ind">Active</label>
                    </div>
                    @error('active_ind')
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
                            <i class="mdi mdi-content-save me-1"></i>Submit OT Plan
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        // Handle form reset
        document.querySelector('form').addEventListener('reset', function() {
            setTimeout(function() {
                document.getElementById('active_ind').checked = true;
                document.getElementById('ot_config_salary').checked = true;
                document.getElementById('rate_type_multiplier').checked = true;
                toggleOTConfigSections();
            }, 0);
        });

        // Toggle between salary-based and custom OT rate configuration
        function toggleOTConfigSections() {
            const salaryBased = document.getElementById('ot_config_salary').checked;
            const salarySection = document.getElementById('salary_based_section');
            const customSection = document.getElementById('custom_rate_section');

            if (salaryBased) {
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

        // Toggle multiplier field visibility based on rate type
        function toggleMultiplierField() {
            const isMultiplier = document.getElementById('rate_type_multiplier').checked;
            const multiplierRow = document.getElementById('multiplier_field_row');

            if (isMultiplier) {
                multiplierRow.style.display = 'flex';
            } else {
                multiplierRow.style.display = 'none';
            }
        }

        // Event listeners
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize on page load
            toggleOTConfigSections();
            toggleMultiplierField();

            // Listen for configuration type changes
            document.getElementById('ot_config_salary').addEventListener('change', toggleOTConfigSections);
            document.getElementById('ot_config_custom').addEventListener('change', toggleOTConfigSections);

            // Listen for rate type changes
            document.getElementById('rate_type_basic').addEventListener('change', toggleMultiplierField);
            document.getElementById('rate_type_multiplier').addEventListener('change', toggleMultiplierField);
        });
    </script>
@endsection
