@extends('structure.master')

@section('content')
    <div class="container-fluid mt-4">
        <form method="POST"
            action="{{ isset($plan) ? route('plans.off_day_plans.update', $plan->id) : route('plans.off_day_plans.store') }}"
            enctype="multipart/form-data">
            @csrf
            @if (isset($plan))
                @method('PUT')
            @endif

            {{-- Basic Off-Day Plan Information --}}
            <div class="card border mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0 fw-semibold">
                        <i class="mdi mdi-calendar-remove text-primary me-2"></i> Off-Day Plan Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label fw-semibold">
                                Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="E.g., Friday Off-Day Plan"
                                value="{{ isset($plan) ? $plan->name : old('name') }}" required>
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="short_name" class="form-label fw-semibold">
                                Short Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="short_name" name="short_name"
                                placeholder="E.g., FRI-OFF"
                                value="{{ isset($plan) ? $plan->short_name : old('short_name') }}" required>
                            @error('short_name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Time Configuration --}}
            <div class="card border mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0 fw-semibold">
                        <i class="mdi mdi-clock-outline text-success me-2"></i> Time Configuration
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="start_time" class="form-label fw-semibold">
                                Start Time <span class="text-danger">*</span>
                            </label>
                            <input type="time" class="form-control" id="start_time" name="start_time"
                                value="{{ isset($plan) && $plan->start_time ? \Carbon\Carbon::parse($plan->start_time)->format('H:i') : old('start_time') }}"
                                required>
                            <small class="text-muted">{{ __('Time when off-day period begins') }}</small>
                            @error('start_time')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="end_time" class="form-label fw-semibold">
                                End Time <span class="text-danger">*</span>
                            </label>
                            <input type="time" class="form-control" id="end_time" name="end_time"
                                value="{{ isset($plan) && $plan->end_time ? \Carbon\Carbon::parse($plan->end_time)->format('H:i') : old('end_time') }}"
                                required>
                            <small class="text-muted">Time when off-day period ends</small>
                            @error('end_time')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Grace Time Configuration --}}
            <div class="card border mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0 fw-semibold">
                        <i class="mdi mdi-timer-sand text-warning me-2"></i> Grace Time Configuration
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="grace_time" class="form-label fw-semibold">
                                Grace Time (Clock In) (minutes) <span class="text-danger">*</span>
                            </label>
                            <input type="number" step="1" class="form-control" id="grace_time" name="grace_time"
                                placeholder="0" value="{{ isset($plan) ? $plan->grace_time : old('grace_time', 0) }}"
                                required>
                            <small class="text-muted">Grace period after end time (in minutes)</small>
                            @error('grace_time')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="grace_time_before" class="form-label fw-semibold">
                                Grace Time (Clock Out) (minutes) <span class="text-danger">*</span>
                            </label>
                            <input type="number" step="1" class="form-control" id="grace_time_before"
                                name="grace_time_before" placeholder="0"
                                value="{{ isset($plan) ? $plan->grace_time_before : old('grace_time_before', 0) }}"
                                required>
                            <small class="text-muted">Grace period before start time (in minutes)</small>
                            @error('grace_time_before')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Remuneration Configuration --}}
            <div class="card border mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0 fw-semibold">
                        <i class="mdi mdi-cash-multiple text-success me-2"></i> Remuneration Configuration
                    </h5>
                    <small class="text-danger"><i class="mdi mdi-information-outline me-1"></i>Note: All remuneration
                        calculations are based on hours</small>
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
                                    <input class="form-check-input @error('offday_config_type') is-invalid @enderror"
                                        type="radio" name="offday_config_type" id="offday_config_salary"
                                        value="Salary Based"
                                        {{ isset($plan) && $plan->offday_config_type == 'Salary Based' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="offday_config_salary">
                                        Based on Salary
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input @error('offday_config_type') is-invalid @enderror"
                                        type="radio" name="offday_config_type" id="offday_config_custom"
                                        value="Custom"
                                        {{ !isset($plan) || (isset($plan) && $plan->offday_config_type == 'Custom') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="offday_config_custom">
                                        Custom Rate
                                    </label>
                                </div>
                            </div>
                            @error('offday_config_type')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <!-- Remuneration Configuration (Based on Salary) -->
                    <div id="salary_based_section" class="border rounded p-3 mb-3">
                        <h6 class="fw-semibold mb-3">
                            <i class="mdi mdi-calculator text-primary me-1"></i>Remuneration Configuration (Based on
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
                                @error('salary_rate_type')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        <div class="row" id="multiplier_field_row">
                            <div class="col-md-4 mb-3">
                                <label for="offday_multiplier" class="form-label fw-semibold">
                                    Offday Rate <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="number" step="0.01" class="form-control form-control-sm"
                                        id="offday_multiplier" name="offday_multiplier" placeholder="2.0"
                                        value="{{ isset($plan) ? $plan->offday_multiplier : old('offday_multiplier', '2.0') }}"
                                        style="max-width: 120px;">
                                    <span class="input-group-text bg-light">X Base Rate</span>
                                </div>
                                <small class="text-muted">Enter fractional number (e.g., 1.5, 2.0, 2.5)</small>
                                @error('offday_multiplier')
                                    <span class="text-danger d-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Remuneration (Custom) -->
                    <div id="custom_rate_section" class="border rounded p-3 mb-3">
                        <h6 class="fw-semibold mb-3">
                            <i class="mdi mdi-cash text-success me-1"></i>Remuneration (Custom)
                        </h6>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="custom_offday_rate" class="form-label fw-semibold">
                                    Amount Per Hour <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span
                                        class="input-group-text">{{ \App\HelperClass::getGeneralSetting()->currency ?? '৳' }}</span>
                                    <input type="number" step="0.01" class="form-control" id="custom_offday_rate"
                                        name="custom_offday_rate" placeholder="Enter amount per hour"
                                        value="{{ isset($plan) ? $plan->custom_offday_rate : old('custom_offday_rate') }}">
                                </div>
                                <small class="text-muted">Fixed amount per offday hour worked</small>
                                @error('custom_offday_rate')
                                    <span class="text-danger d-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label fw-semibold">
                                Status <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="">Select Status</option>
                                <option value="active" {{ isset($plan) && $plan->status == 'active' ? 'selected' : '' }}
                                    {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive"
                                    {{ isset($plan) && $plan->status == 'inactive' ? 'selected' : '' }}
                                    {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>


            {{-- Submit Buttons --}}
            <div class="card border mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-end gap-2">
                        <button type="reset" class="btn btn-secondary">
                            <i class="mdi mdi-refresh me-1"></i>Reset
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i
                                class="mdi mdi-content-save me-1"></i>{{ isset($plan) ? 'Update Off-Day Plan' : 'Submit Off-Day Plan' }}
                        </button>
                    </div>
                </div>
            </div>

        </form>
    </div>

    <script>
        // Handle form reset - ensure status checkbox returns to default checked state
        document.querySelector('form').addEventListener('reset', function() {
            setTimeout(function() {
                document.getElementById('status').checked = true;
                document.getElementById('offday_config_custom').checked = true;
                document.getElementById('rate_type_multiplier').checked = true;
                toggleOffdayConfigSections();
            }, 0);
        });

        // Toggle between salary-based and custom offday rate configuration
        function toggleOffdayConfigSections() {
            const salaryBased = document.getElementById('offday_config_salary').checked;
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
            toggleOffdayConfigSections();
            toggleMultiplierField();

            // Listen for configuration type changes
            document.getElementById('offday_config_salary').addEventListener('change', toggleOffdayConfigSections);
            document.getElementById('offday_config_custom').addEventListener('change', toggleOffdayConfigSections);

            // Listen for rate type changes
            document.getElementById('rate_type_basic').addEventListener('change', toggleMultiplierField);
            document.getElementById('rate_type_multiplier').addEventListener('change', toggleMultiplierField);
        });
    </script>
@endsection
