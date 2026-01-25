@extends('structure.master')
@section('content')

    @php
        // Get timing from the related shift
        $shift = $plan->getShift;
        $start = $shift ? Carbon\Carbon::parse($shift->clock_in_time) : null;
        $end = $shift ? Carbon\Carbon::parse($shift->clock_out_time) : null;

        $formatted_diff = '-';
        $start_time = '-';
        $end_time = '-';

        if ($start && $end) {
            $diffMinutes = $start->diffInMinutes($end);
            $hours = floor($diffMinutes / 60);
            $minutes = $diffMinutes % 60;
            $formatted_diff = $hours . ' : ' . $minutes;
            $start_time = $start->format('h:i A');
            $end_time = $end->format('h:i A');
        }

        $grace_time = $shift->grace_time ?? 0;
        $grace_time_before = $shift->early_out_grace_minutes ?? 0;
    @endphp
    <div class="container-fluid mt-4">
        {{-- Page Header --}}
        <div class="mb-4">
            <h4 class="fw-semibold">
                <i class="mdi mdi-calendar-remove text-primary me-2"></i>Off-Day Plan Details
            </h4>
            <p class="text-muted small mb-0">View off-day plan configuration and remuneration settings</p>
        </div>

        {{-- Basic Off-Day Plan Information --}}
        <div class="card border mb-4">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-semibold">
                    <i class="mdi mdi-information-outline text-primary me-2"></i>Basic Off-Day Plan Information
                </h5>
                <span class="badge bg-{{ $plan->status == 'active' ? 'success' : 'secondary' }}">
                    {{ ucfirst($plan->status) }}
                </span>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="text-muted small">Plan Name</label>
                        <p class="fw-semibold mb-0">{{ $plan->name }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="text-muted small">Short Name</label>
                        <p class="fw-semibold mb-0">
                            @if (isset($plan->short_name) && $plan->short_name)
                                <span class="badge bg-secondary fs-6">{{ $plan->short_name }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="text-muted small">Configuration Type</label>
                        <p class="fw-semibold mb-0">
                            @if (isset($plan->offday_config_type))
                                <span class="badge bg-info fs-6">{{ $plan->offday_config_type }}</span>
                            @else
                                <span class="badge bg-secondary fs-6">Custom</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Shift Configuration (replaces Time Configuration) --}}
        <div class="card border mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0 fw-semibold">
                    <i class="mdi mdi-clock-outline text-success me-2"></i>Shift Configuration
                </h5>
            </div>
            <div class="card-body">
                @if ($shift)
                    <div class="border rounded p-3 bg-light">
                        <h6 class="fw-semibold mb-3 text-primary">
                            <i class="mdi mdi-calendar-clock me-1"></i>{{ $shift->name }}
                            <a href="{{ route('plans.shift_plans.show', $shift->id) }}"
                                class="btn btn-sm btn-outline-primary ms-2">
                                <i class="mdi mdi-eye me-1"></i>View Shift
                            </a>
                        </h6>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="text-muted small">Clock In Time</label>
                                <p class="fw-semibold mb-0">
                                    <i class="mdi mdi-clock-start text-success me-1"></i>
                                    {{ $start_time }}
                                </p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="text-muted small">Clock Out Time</label>
                                <p class="fw-semibold mb-0">
                                    <i class="mdi mdi-clock-end text-danger me-1"></i>
                                    {{ $end_time }}
                                </p>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="text-muted small">Duration</label>
                                <p class="fw-semibold mb-0">
                                    <span class="badge bg-secondary fs-6">
                                        {{ $formatted_diff }} hr
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="alert alert-warning mb-0">
                        <i class="mdi mdi-alert-outline me-2"></i>No shift assigned to this off-day plan.
                    </div>
                @endif
            </div>
        </div>

        {{-- Grace Time Configuration (derived from Shift) --}}
        <div class="card border mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0 fw-semibold">
                    <i class="mdi mdi-timer-sand text-warning me-2"></i>Grace Time Configuration
                    <small class="text-muted ms-2">(from Shift)</small>
                </h5>
            </div>
            <div class="card-body">
                @if ($shift)
                    <div class="border rounded p-3 bg-light">
                        <h6 class="fw-semibold mb-3 text-primary">
                            <i class="mdi mdi-clock-alert me-1"></i>Attendance Grace Periods
                        </h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">Grace Time (Clock In)</label>
                                <p class="fw-semibold mb-2">
                                    <span class="badge bg-warning text-dark fs-6">
                                        <i class="mdi mdi-clock-fast me-1"></i>
                                        {{ $grace_time }} minutes
                                    </span>
                                </p>
                                @if ($start)
                                    <small class="text-muted">
                                        Employees can clock in until:
                                        <strong>
                                            {{ $start->copy()->addMinutes($grace_time)->format('h:i A') }}
                                        </strong>
                                    </small>
                                @endif
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">Early Out Grace</label>
                                <p class="fw-semibold mb-2">
                                    <span class="badge bg-info fs-6">
                                        <i class="mdi mdi-clock-plus me-1"></i>
                                        {{ $grace_time_before }} minutes
                                    </span>
                                </p>
                                @if ($end)
                                    <small class="text-muted">
                                        Employees can clock out after:
                                        <strong>
                                            {{ $end->copy()->subMinutes($grace_time_before)->format('h:i A') }}
                                        </strong>
                                    </small>
                                @endif
                            </div>
                        </div>

                        <div class="row mt-2">
                            <div class="col-md-12">
                                <div class="alert alert-info mb-0">
                                    <i class="mdi mdi-information-outline me-2"></i>
                                    Grace periods are inherited from the associated shift configuration.
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="alert alert-warning mb-0">
                        <i class="mdi mdi-alert-outline me-2"></i>No shift assigned - grace times unavailable.
                    </div>
                @endif
            </div>
        </div>

        {{-- Remuneration Configuration Details --}}
        <div class="card border mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0 fw-semibold">
                    <i class="mdi mdi-cash-multiple text-success me-2"></i>Remuneration Configuration
                </h5>
            </div>
            <div class="card-body">
                <div class="border rounded p-4 bg-light">
                    <h6 class="fw-semibold mb-3 text-primary">
                        <i class="mdi mdi-currency-usd me-1"></i>Off-Day Compensation Details
                    </h6>

                    @if (isset($plan->offday_config_type) && $plan->offday_config_type === 'Salary Based')
                        {{-- Salary Based Configuration --}}
                        <div class="alert alert-info mb-3">
                            <i class="mdi mdi-calculator me-2"></i>
                            <strong>Configuration Type:</strong> Salary Based
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">Rate Type</label>
                                <p class="fw-semibold mb-0">
                                    <span class="badge bg-primary fs-6">
                                        {{ $plan->salary_rate_type ?? 'Multiplier' }}
                                    </span>
                                </p>
                            </div>

                            @if ($plan->salary_rate_type === 'Multiplier')
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small">Multiplier Rate</label>
                                    <p class="fw-semibold mb-0">
                                        <span class="badge bg-warning text-dark fs-6">
                                            {{ number_format($plan->offday_multiplier, 2) }}x Base Rate
                                        </span>
                                    </p>
                                </div>
                            @endif
                        </div>

                        <div class="alert alert-success mb-0">
                            <i class="mdi mdi-information-outline me-2"></i>
                            Remuneration is calculated based on employee's basic salary
                            @if ($plan->salary_rate_type === 'Multiplier')
                                multiplied by <strong>{{ number_format($plan->offday_multiplier, 2) }}x</strong>
                            @else
                                using the <strong>basic hourly rate</strong>
                            @endif
                        </div>
                    @else
                        {{-- Custom Rate Configuration --}}
                        <div class="alert alert-info mb-3">
                            <i class="mdi mdi-cash me-2"></i>
                            <strong>Configuration Type:</strong> Custom Rate
                        </div>

                        <div class="mb-3">
                            <label class="text-muted small d-block mb-2">Fixed Rate Per Hour</label>
                            <h2 class="fw-bold text-success mb-1">
                                {{ \App\HelperClass::getGeneralSetting()->currency ?? '৳' }}
                                {{ number_format($plan->custom_offday_rate ?? 0, 2) }}
                            </h2>
                            <small class="text-muted">Per hour worked on off-day</small>
                        </div>

                        <div class="alert alert-success mb-0">
                            <i class="mdi mdi-check-circle-outline me-2"></i>
                            Employees working during this off-day will receive a fixed rate of
                            <strong>{{ \App\HelperClass::getGeneralSetting()->currency ?? '৳' }}{{ number_format($plan->custom_offday_rate ?? 0, 2) }}</strong>
                            per hour worked
                        </div>
                    @endif
                </div>
            </div>
        </div>


        {{-- Page Actions - Fixed at Bottom Right --}}
        <div class="d-flex float-end justify-content-right align-items-center mb-4">
            <div>
                <a href="{{ route('plans.off_day_plans.edit', $plan->id) }}" class="btn btn-primary me-2">
                    <i class="mdi mdi-pencil me-1"></i>Edit
                </a>
                <a href="{{ route('plans.off_day_plans.index') }}" class="btn btn-secondary">
                    <i class="mdi mdi-arrow-left me-1"></i>Back to List
                </a>
            </div>
        </div>
    </div>
@endsection
