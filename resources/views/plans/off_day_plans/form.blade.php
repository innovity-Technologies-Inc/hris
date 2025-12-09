@extends('structure.master')

@section('content')
<div class="container-fluid mt-4">
    <form method="POST" action="{{ isset($plan) ? route('plans.off_day_plans.update', $plan->id) : route('plans.off_day_plans.store')}}" enctype="multipart/form-data">
        @csrf
        @if(isset($plan))
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
                            class="form-control"
                            id="name"
                            name="name"
                            placeholder="E.g., Friday Off-Day Plan"
                            value="{{ isset($plan) ? $plan->name : old('name') }}"
                            required
                        >
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="short_name" class="form-label fw-semibold">
                            Short Name <span class="text-danger">*</span>
                        </label>
                        <input
                            type="text"
                            class="form-control"
                            id="short_name"
                            name="short_name"
                            placeholder="E.g., FRI-OFF"
                            value="{{ isset($plan) ? $plan->short_name : old('short_name') }}"
                            required
                        >
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
                        <input
                            type="time"
                            class="form-control"
                            id="start_time"
                            name="start_time"
                            value="{{ isset($plan) && $plan->start_time ? \Carbon\Carbon::parse($plan->start_time)->format('H:i') : old('start_time') }}"
                            required
                        >
                        <small class="text-muted">{{ __('Time when off-day period begins') }}</small>
                        @error('start_time')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="end_time" class="form-label fw-semibold">
                            End Time <span class="text-danger">*</span>
                        </label>
                        <input
                            type="time"
                            class="form-control"
                            id="end_time"
                            name="end_time"
                            value="{{ isset($plan) && $plan->end_time ? \Carbon\Carbon::parse($plan->end_time)->format('H:i') : old('end_time') }}"
                            required
                        >
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
                        <input
                            type="number"
                            step="1"
                            class="form-control"
                            id="grace_time"
                            name="grace_time"
                            placeholder="0"
                            value="{{ isset($plan) ? $plan->grace_time : old('grace_time', 0) }}"
                            required
                        >
                        <small class="text-muted">Grace period after end time (in minutes)</small>
                        @error('grace_time')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="grace_time_before" class="form-label fw-semibold">
                            Grace Time (Clock Out) (minutes) <span class="text-danger">*</span>
                        </label>
                        <input
                            type="number"
                            step="1"
                            class="form-control"
                            id="grace_time_before"
                            name="grace_time_before"
                            placeholder="0"
                            value="{{ isset($plan) ? $plan->grace_time_before : old('grace_time_before', 0) }}"
                            required
                        >
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
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="remuneration" class="form-label fw-semibold">
                            Remuneration Amount <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">{{ \App\HelperClass::getGeneralSetting()->currency ?? '৳' }}</span>
                            <input
                                type="number"
                                step="0.01"
                                class="form-control"
                                id="remuneration"
                                name="remuneration"
                                placeholder="Enter remuneration amount"
                                value="{{ isset($plan) ? $plan->remuneration : old('remuneration', 0.00) }}"
                                required
                            >
                        </div>
                        <small class="text-muted">Fixed amount paid for off-day work</small>
                        @error('remuneration')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="status" class="form-label fw-semibold">
                            Status <span class="text-danger">*</span>
                        </label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="">Select Status</option>
                            <option value="active" {{ isset($plan) && $plan->status == 'active' ? 'selected' : '' }} {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ isset($plan) && $plan->status == 'inactive' ? 'selected' : '' }} {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
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
                        <i class="mdi mdi-content-save me-1"></i>{{ isset($plan) ? 'Update Off-Day Plan' : 'Submit Off-Day Plan' }}
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
