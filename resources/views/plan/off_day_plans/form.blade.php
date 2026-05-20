@extends('structure.master')

@section('content')
    <div class="container-fluid mt-4">
        <form method="POST"
            action="{{ isset($plan) ? route('plan.off_day_plans.update', $plan->id) : route('plan.off_day_plans.store') }}"
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

            {{-- Shift Selection (replaces Time Configuration) --}}
            <div class="card border mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0 fw-semibold">
                        <i class="mdi mdi-clock-outline text-success me-2"></i> Shift Configuration
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="shift_id" class="form-label fw-semibold">
                                Select Shift <span class="text-danger">*</span>
                            </label>
                            <select class="form-select shift-select @error('shift_id') is-invalid @enderror" id="shift_id"
                                name="shift_id" data-target="shift_details" required>
                                <option value="">Select a Shift</option>
                                @foreach ($shifts as $shift)
                                    <option value="{{ $shift->id }}"
                                        {{ (isset($plan) && $plan->shift_id == $shift->id) || old('shift_id') == $shift->id ? 'selected' : '' }}>
                                        {{ $shift->name }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Timing and grace periods are derived from the selected shift</small>
                            @error('shift_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    {{-- Dynamic Shift Details Container --}}
                    <div id="shift_details" class="shift-details mt-3 d-none">
                        <div class="border rounded p-3 bg-light">
                            <h6 class="fw-semibold mb-3 text-primary">
                                <i class="mdi mdi-information me-1"></i>Shift Details
                            </h6>
                            <div class="shift-info">
                                {{-- JavaScript will populate this --}}
                            </div>
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
                                        type="radio" name="offday_config_type" id="offday_config_custom" value="Custom"
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

    <script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
    <script>
        $(function() {
            // Load shift details dynamically (same pattern as Roster Plans)
            function loadShiftDetails(shiftId, targetBox) {
                if (!shiftId) {
                    $("#" + targetBox).addClass("d-none")
                        .find(".shift-info").html("");
                    return;
                }

                $.get('/get-shift-details/' + shiftId, function(response) {
                    if (!response || !response.shift) {
                        console.error("Shift not found");
                        return;
                    }

                    let shift = response.shift;

                    let html = `
                        <div class="row">
                            <div class="col-md-4 mb-2">
                                <p class="text-muted mb-1 small">Shift Name</p>
                                <p class="fw-semibold mb-0">${shift.name}</p>
                            </div>
                            <div class="col-md-4 mb-2">
                                <p class="text-muted mb-1 small">Clock In Time</p>
                                <p class="fw-semibold mb-0"><i class="mdi mdi-clock-start text-success me-1"></i>${shift.clock_in_time}</p>
                            </div>
                            <div class="col-md-4 mb-2">
                                <p class="text-muted mb-1 small">Clock Out Time</p>
                                <p class="fw-semibold mb-0"><i class="mdi mdi-clock-end text-danger me-1"></i>${shift.clock_out_time}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-2">
                                <p class="text-muted mb-1 small">Grace Time (Clock In)</p>
                                <p class="fw-semibold mb-0"><span class="badge bg-warning text-dark">${shift.grace_time || 0} minutes</span></p>
                            </div>
                            <div class="col-md-4 mb-2">
                                <p class="text-muted mb-1 small">Early Out Grace</p>
                                <p class="fw-semibold mb-0"><span class="badge bg-info">${shift.early_out_grace_minutes || 0} minutes</span></p>
                            </div>
                        </div>
                    `;

                    $("#" + targetBox)
                        .removeClass("d-none")
                        .find(".shift-info")
                        .html(html);
                });
            }

            // Handle dropdown changes
            $(".shift-select").on("change", function() {
                let shiftId = $(this).val();
                let targetBox = $(this).data("target");
                loadShiftDetails(shiftId, targetBox);
            });

            // Auto-load shift details on page load (for edit mode or old values)
            @if (isset($plan))
                let currentShiftId = "{{ old('shift_id', $plan->shift_id ?? '') }}";
                if (currentShiftId) {
                    loadShiftDetails(currentShiftId, 'shift_details');
                }
            @else
                let oldShiftId = "{{ old('shift_id') }}";
                if (oldShiftId) {
                    loadShiftDetails(oldShiftId, 'shift_details');
                }
            @endif
        });

        // Handle form reset - ensure status checkbox returns to default checked state
        document.querySelector('form').addEventListener('reset', function() {
            setTimeout(function() {
                document.getElementById('status').checked = true;
                document.getElementById('offday_config_custom').checked = true;
                document.getElementById('rate_type_multiplier').checked = true;
                document.getElementById('shift_id').value = '';
                document.getElementById('shift_details').classList.add('d-none');
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

