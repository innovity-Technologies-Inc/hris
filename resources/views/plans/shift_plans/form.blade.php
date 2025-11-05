@extends('structure.master')
@section('content')
    <div class="container-fluid mt-4">
        <form method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Basic Shift Information -->
            <div class="card border mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0 fw-semibold">
                        <i class="mdi mdi-clock-outline text-primary me-2"></i>Basic Shift Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="shift_name" class="form-label fw-semibold">
                                Shift Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control" id="shift_name" name="shift_name"
                                placeholder="E.g., Head Office - Check" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="clock_in_time" class="form-label fw-semibold">
                                Clock In Time <span class="text-danger">*</span>
                            </label>
                            <input type="time" class="form-control" id="clock_in_time" name="clock_in_time" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="clock_out_time" class="form-label fw-semibold">
                                Clock Out Time <span class="text-danger">*</span>
                            </label>
                            <input type="time" class="form-control" id="clock_out_time" name="clock_out_time" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="treat_as_full_day_minutes" class="form-label fw-semibold">
                                Treat as Full Day (Minutes)
                            </label>
                            <input type="number" class="form-control" id="treat_as_full_day_minutes"
                                name="treat_as_full_day_minutes" placeholder="60">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="treat_as_half_day_minutes" class="form-label fw-semibold">
                                Treat as Half Day (Minutes)
                            </label>
                            <input type="number" class="form-control" id="treat_as_half_day_minutes"
                                name="treat_as_half_day_minutes" placeholder="400">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="grace_time" class="form-label fw-semibold">Grace Time</label>
                            <input type="time" class="form-control" id="grace_time" name="grace_time">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Late and Early Out Settings -->
            <div class="card border mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0 fw-semibold">
                        <i class="mdi mdi-clock-alert-outline text-danger me-2"></i>Late and Early Out Settings
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="late_after_minutes" class="form-label fw-semibold">Late After (Minutes)</label>
                            <input type="number" class="form-control" id="late_after_minutes" name="late_after_minutes"
                                placeholder="Enter minutes">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="excessive_late_after_minutes" class="form-label fw-semibold">
                                Excessive Late After (Minutes)
                            </label>
                            <input type="number" class="form-control" id="excessive_late_after_minutes"
                                name="excessive_late_after_minutes" placeholder="Enter minutes">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="early_out_grace_minutes" class="form-label fw-semibold">
                                Early Out Grace (Minutes)
                            </label>
                            <input type="number" class="form-control" id="early_out_grace_minutes"
                                name="early_out_grace_minutes" placeholder="5">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="early_out_before" class="form-label fw-semibold">Early Out Before</label>
                            <input type="time" class="form-control" id="early_out_before" name="early_out_before">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Meal Break Settings -->
            <div class="row">
                <!-- Breakfast Plan -->
                <div class="col-md-6 mb-4">
                    <div class="card border h-100">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fw-semibold">
                                <i class="mdi mdi-coffee text-warning me-2"></i>Breakfast Break
                            </h6>
                            <div class="form-check form-switch mb-0">
                                <input type="hidden" name="breakfast_status" value="inactive">
                                <input class="form-check-input" type="checkbox" name="breakfast_status"
                                    id="breakfast_status" value="active" onchange="toggleBreakSection('breakfast')">
                                <label class="form-check-label" for="breakfast_status">Active</label>
                            </div>
                        </div>
                        <div class="card-body" id="breakfast_section" style="display: none;">
                            <div class="mb-3">
                                <label for="breakfast_start_time" class="form-label fw-semibold">Start Time</label>
                                <input type="time" class="form-control" id="breakfast_start_time"
                                    name="breakfast_start_time">
                            </div>
                            <div class="mb-0">
                                <label for="breakfast_end_time" class="form-label fw-semibold">End Time</label>
                                <input type="time" class="form-control" id="breakfast_end_time"
                                    name="breakfast_end_time">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Lunch Plan -->
                <div class="col-md-6 mb-4">
                    <div class="card border h-100">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fw-semibold">
                                <i class="mdi mdi-food text-success me-2"></i>Lunch Break
                            </h6>
                            <div class="form-check form-switch mb-0">
                                <input type="hidden" name="lunch_status" value="inactive">
                                <input class="form-check-input" type="checkbox" name="lunch_status" id="lunch_status"
                                    value="active" onchange="toggleBreakSection('lunch')">
                                <label class="form-check-label" for="lunch_status">Active</label>
                            </div>
                        </div>
                        <div class="card-body" id="lunch_section" style="display: none;">
                            <div class="mb-3">
                                <label for="lunch_start_time" class="form-label fw-semibold">Start Time</label>
                                <input type="time" class="form-control" id="lunch_start_time" name="lunch_start_time">
                            </div>
                            <div class="mb-0">
                                <label for="lunch_end_time" class="form-label fw-semibold">End Time</label>
                                <input type="time" class="form-control" id="lunch_end_time" name="lunch_end_time">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Snacks Plan -->
                <div class="col-md-6 mb-4">
                    <div class="card border h-100">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fw-semibold">
                                <i class="mdi mdi-cookie text-info me-2"></i>Snacks Break
                            </h6>
                            <div class="form-check form-switch mb-0">
                                <input type="hidden" name="snacks_status" value="inactive">
                                <input class="form-check-input" type="checkbox" name="snacks_status" id="snacks_status"
                                    value="active" onchange="toggleBreakSection('snacks')">
                                <label class="form-check-label" for="snacks_status">Active</label>
                            </div>
                        </div>
                        <div class="card-body" id="snacks_section" style="display: none;">
                            <div class="mb-3">
                                <label for="snacks_start_time" class="form-label fw-semibold">Start Time</label>
                                <input type="time" class="form-control" id="snacks_start_time" name="snacks_start_time">
                            </div>
                            <div class="mb-0">
                                <label for="snacks_end_time" class="form-label fw-semibold">End Time</label>
                                <input type="time" class="form-control" id="snacks_end_time" name="snacks_end_time">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dinner Plan -->
                <div class="col-md-6 mb-4">
                    <div class="card border h-100">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fw-semibold">
                                <i class="mdi mdi-silverware-fork-knife text-danger me-2"></i>Dinner Break
                            </h6>
                            <div class="form-check form-switch mb-0">
                                <input type="hidden" name="dinner_status" value="inactive">
                                <input class="form-check-input" type="checkbox" name="dinner_status" id="dinner_status"
                                    value="active" onchange="toggleBreakSection('dinner')">
                                <label class="form-check-label" for="dinner_status">Active</label>
                            </div>
                        </div>
                        <div class="card-body" id="dinner_section" style="display: none;">
                            <div class="mb-3">
                                <label for="dinner_start_time" class="form-label fw-semibold">Start Time</label>
                                <input type="time" class="form-control" id="dinner_start_time" name="dinner_start_time">
                            </div>
                            <div class="mb-0">
                                <label for="dinner_end_time" class="form-label fw-semibold">End Time</label>
                                <input type="time" class="form-control" id="dinner_end_time" name="dinner_end_time">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shift Status -->
            <div class="card border mb-4">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-semibold">
                        <i class="mdi mdi-toggle-switch text-primary me-2"></i>Shift Status
                    </h5>
                    <div class="form-check form-switch mb-0">
                        <input type="hidden" name="active_ind" value="inactive">
                        <input class="form-check-input" type="checkbox" name="active_ind" id="active_ind"
                            value="active" checked>
                        <label class="form-check-label" for="active_ind">Active</label>
                    </div>
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
                            <i class="mdi mdi-content-save me-1"></i>Submit Shift Plan
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        function toggleBreakSection(breakType) {
            const checkbox = document.getElementById(breakType + '_status');
            const section = document.getElementById(breakType + '_section');
            const hiddenInput = document.querySelector('input[name="' + breakType + '_status"][type="hidden"]');

            if (checkbox.checked) {
                section.style.display = 'block';
                hiddenInput.disabled = true;
            } else {
                section.style.display = 'none';
                hiddenInput.disabled = false;
                // Clear time fields
                document.getElementById(breakType + '_start_time').value = '';
                document.getElementById(breakType + '_end_time').value = '';
            }
        }

        // Handle form reset
        document.querySelector('form').addEventListener('reset', function() {
            setTimeout(function() {
                // Hide all break sections
                ['breakfast', 'lunch', 'snacks', 'dinner'].forEach(function(breakType) {
                    document.getElementById(breakType + '_section').style.display = 'none';
                    document.querySelector('input[name="' + breakType + '_status"][type="hidden"]').disabled = false;
                });

                // Keep shift active status checked
                document.getElementById('active_ind').checked = true;
            }, 0);
        });
    </script>
@endsection
