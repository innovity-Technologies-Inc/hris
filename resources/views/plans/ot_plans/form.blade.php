@extends('structure.master')
@section('content')
    <div class="container-fluid mt-4">



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
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="overtime_rate_type" class="form-label fw-semibold">
                                Rate Type <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" id="overtime_rate_type" name="overtime_rate_type" required>
                                <option value="">Select Rate Type</option>
                                <option value="multiplier"
                                    {{ isset($plan) && $plan->overtime_rate_type == 'multiplier' ? 'selected' : '' }}>
                                    Multiplier (e.g., 1.5x base rate)</option>
                                <option value="per_hour"
                                    {{ isset($plan) && $plan->overtime_rate_type == 'per_hour' ? 'selected' : '' }}>Per
                                    Hour (e.g., $10/hour)</option>
                            </select>
                            @error('overtime_rate_type')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="overtime_rate" class="form-label fw-semibold">
                                Overtime Rate <span class="text-danger">*</span>
                            </label>
                            <input type="number" step="0.01" class="form-control" id="overtime_rate"
                                name="overtime_rate" placeholder="1.50"
                                value="{{ isset($plan) ? $plan->overtime_rate : old('overtime_rate', '1.50') }}" required>
                            <small class="text-muted">For multiplier: use 1.5 for 1.5x | For per hour: use actual
                                amount</small>
                            @error('overtime_rate')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
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

            <!-- OT Limits -->
            <div class="card border mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0 fw-semibold">
                        <i class="mdi mdi-alert-circle-outline text-danger me-2"></i>OT Limits
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="max_ot_limit" class="form-label fw-semibold">
                                Maximum OT Limit (Hours)
                            </label>
                            <input type="number" step="0.01" class="form-control" id="max_ot_limit"
                                name="max_ot_limit" placeholder="Leave empty for no limit"
                                value="{{ isset($plan) ? $plan->max_ot_limit : old('max_ot_limit') }}">
                            <small class="text-muted">Maximum OT hours per period (optional)</small>
                            @error('max_ot_limit')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="max_ot_period" class="form-label fw-semibold">
                                Limit Period
                            </label>
                            <select class="form-select" id="max_ot_period" name="max_ot_period">
                                <option value="">Select Period</option>
                                <option value="daily"
                                    {{ isset($plan) && $plan->max_ot_period == 'daily' ? 'selected' : '' }}>Daily</option>
                                <option value="weekly"
                                    {{ isset($plan) && $plan->max_ot_period == 'weekly' ? 'selected' : '' }}>Weekly
                                </option>
                                <option value="monthly"
                                    {{ isset($plan) && $plan->max_ot_period == 'monthly' ? 'selected' : '' }}>Monthly
                                </option>
                                <option value="yearly"
                                    {{ isset($plan) && $plan->max_ot_period == 'yearly' ? 'selected' : '' }}>Yearly
                                </option>
                            </select>
                            <small class="text-muted">Period for maximum OT limit</small>
                            @error('max_ot_period')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Notes -->
            <div class="card border mb-4">
                <div class="card-header bg-light">
                    <h5 class="mb-0 fw-semibold">
                        <i class="mdi mdi-note-text-outline text-secondary me-2"></i>Additional Notes
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="notes" class="form-label fw-semibold">Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="4"
                                placeholder="Any additional information about this OT plan...">{{ isset($plan) ? $plan->notes : old('notes') }}</textarea>
                            @error('notes')
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
                            value="active" {{ (isset($plan) && $plan->active_ind == 'active') || old('active_ind', 'active') == 'active' ? 'checked' : '' }}>
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
            }, 0);
        });
    </script>

@endsection
