@extends('structure.master')

@section('content')
<div class="container-fluid mt-4">
    <form method="POST" action="#" enctype="multipart/form-data">
        @csrf
        @if(isset($offDayPlan))
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
                        <input
                            type="text"
                            class="form-control @error('name') is-invalid @enderror"
                            id="name"
                            name="name"
                            placeholder="E.g., Friday Off-Day Plan"
                            value="{{ isset($offDayPlan) ? $offDayPlan->name : old('name') }}"
                            required
                        >
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="short_name" class="form-label fw-semibold">
                            Short Name <span class="text-danger">*</span>
                        </label>
                        <input
                            type="text"
                            class="form-control @error('short_name') is-invalid @enderror"
                            id="short_name"
                            name="short_name"
                            placeholder="E.g., FRI-OFF"
                            value="{{ isset($offDayPlan) ? $offDayPlan->short_name : old('short_name') }}"
                            required
                        >
                        @error('short_name')
                            <span class="text-danger">{{ $message }}</span>
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
                        <input
                            type="time"
                            class="form-control @error('start_time') is-invalid @enderror"
                            id="start_time"
                            name="start_time"
                            value="{{ isset($offDayPlan) && $offDayPlan->start_time ? \Carbon\Carbon::parse($offDayPlan->start_time)->format('H:i') : old('start_time') }}"
                            required
                        >
                        <small class="text-muted">{{ __('Time when off-day period begins') }}</small>
                        @error('start_time')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="end_time" class="form-label fw-semibold">
                            End Time <span class="text-danger">*</span>
                        </label>
                        <input
                            type="time"
                            class="form-control @error('end_time') is-invalid @enderror"
                            id="end_time"
                            name="end_time"
                            value="{{ isset($offDayPlan) && $offDayPlan->end_time ? \Carbon\Carbon::parse($offDayPlan->end_time)->format('H:i') : old('end_time') }}"
                            required
                        >
                        <small class="text-muted">Time when off-day period ends</small>
                        @error('end_time')
                            <span class="text-danger">{{ $message }}</span>
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
                        <label for="grace_time_before" class="form-label fw-semibold">
                            Grace Time Before (minutes) <span class="text-danger">*</span>
                        </label>
                        <input
                            type="number"
                            step="1"
                            class="form-control @error('grace_time_before') is-invalid @enderror"
                            id="grace_time_before"
                            name="grace_time_before"
                            placeholder="0"
                            value="{{ isset($offDayPlan) ? $offDayPlan->grace_time_before : old('grace_time_before', 0) }}"
                            required
                        >
                        <small class="text-muted">Grace period before start time (in minutes)</small>
                        @error('grace_time_before')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="grace_time_after" class="form-label fw-semibold">
                            Grace Time After (minutes) <span class="text-danger">*</span>
                        </label>
                        <input
                            type="number"
                            step="1"
                            class="form-control @error('grace_time_after') is-invalid @enderror"
                            id="grace_time_after"
                            name="grace_time_after"
                            placeholder="0"
                            value="{{ isset($offDayPlan) ? $offDayPlan->grace_time_after : old('grace_time_after', 0) }}"
                            required
                        >
                        <small class="text-muted">Grace period after end time (in minutes)</small>
                        @error('grace_time_after')
                            <span class="text-danger">{{ $message }}</span>
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
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="remuneration_amount" class="form-label fw-semibold">
                            Remuneration Amount <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">{{ \App\HelperClass::getGeneralSetting()->currency ?? '৳' }}</span>
                            <input
                                type="number"
                                step="0.01"
                                class="form-control @error('remuneration_amount') is-invalid @enderror"
                                id="remuneration_amount"
                                name="remuneration_amount"
                                placeholder="Enter remuneration amount"
                                value="{{ isset($offDayPlan) ? $offDayPlan->remuneration_amount : old('remuneration_amount', 0.00) }}"
                                required
                            >
                        </div>
                        <small class="text-muted">Fixed amount paid for off-day work</small>
                        @error('remuneration_amount')
                            <span class="text-danger d-block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Plan Status --}}
        <div class="card border mb-4">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-semibold">
                    <i class="mdi mdi-toggle-switch text-primary me-2"></i> Plan Status
                </h5>
                <div class="form-check form-switch mb-0">
                    <input type="hidden" name="status" value="inactive">
                    <input
                        class="form-check-input"
                        type="checkbox"
                        name="status"
                        id="status"
                        value="active"
                        {{ (isset($offDayPlan) && $offDayPlan->status === 'active') || old('status', 'active') === 'active' ? 'checked' : '' }}
                    >
                    <label class="form-check-label" for="status">Active</label>
                </div>
                @error('status')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
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
                        <i class="mdi mdi-content-save me-1"></i>{{ isset($offDayPlan) ? 'Update Off-Day Plan' : 'Submit Off-Day Plan' }}
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
        }, 0);
    });
</script>
@endsection
