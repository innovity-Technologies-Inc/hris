@php
    $offDayPlan = (object)[
        'id' => 1,
        'name' => 'Friday Off-Day Coverage',
        'short_name' => 'FRI-OFF',
        'status' => 'active', // or 'inactive'
        'start_time' => '12:00 AM',
        'end_time' => '11:59 PM',
        'grace_time_before' => 30,
        'grace_time_after' => 30,
        'remuneration_amount' => 1500.00,
        'description' => 'Special compensation plan for employees working on designated off-days (Fridays). Includes full-day coverage with grace periods for attendance flexibility.',
    ];
@endphp

@extends('structure.master')
@section('content')
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
            <span class="badge bg-{{ $offDayPlan->status == 'active' ? 'success' : 'secondary' }}">
                {{ ucfirst($offDayPlan->status) }}
            </span>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="text-muted small">Plan Name</label>
                    <p class="fw-semibold mb-0">{{ $offDayPlan->name }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="text-muted small">Short Name</label>
                    <p class="fw-semibold mb-0">
                        @if(isset($offDayPlan->short_name) && $offDayPlan->short_name)
                            <span class="badge bg-secondary fs-6">{{ $offDayPlan->short_name }}</span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </p>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="text-muted small">Remuneration Amount</label>
                    <p class="fw-semibold mb-0">
                        <span class="badge bg-success fs-6">
                            {{ \App\HelperClass::getGeneralSetting()->currency ?? '৳' }}
                            {{ number_format($offDayPlan->remuneration_amount, 2) }}
                        </span>
                    </p>
                </div>
            </div>

            @if ($offDayPlan->description)
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="text-muted small">Description</label>
                        <p class="fw-semibold mb-0">{{ $offDayPlan->description }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Time Configuration --}}
    <div class="card border mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0 fw-semibold">
                <i class="mdi mdi-clock-outline text-success me-2"></i>Time Configuration
            </h5>
        </div>
        <div class="card-body">
            <div class="border rounded p-3 bg-light">
                <h6 class="fw-semibold mb-3 text-primary">
                    <i class="mdi mdi-calendar-clock me-1"></i>Off-Day Time Period
                </h6>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="text-muted small">Start Time</label>
                        <p class="fw-semibold mb-0">
                            <i class="mdi mdi-clock-start text-success me-1"></i>
                            {{ $offDayPlan->start_time }}
                        </p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="text-muted small">End Time</label>
                        <p class="fw-semibold mb-0">
                            <i class="mdi mdi-clock-end text-danger me-1"></i>
                            {{ $offDayPlan->end_time }}
                        </p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="text-muted small">Duration</label>
                        <p class="fw-semibold mb-0">
                            <span class="badge bg-secondary fs-6">
                                @php
                                    $startTime = \Carbon\Carbon::parse($offDayPlan->start_time);
                                    $endTime = \Carbon\Carbon::parse($offDayPlan->end_time);

                                    // Handle overnight periods (end time is next day)
                                    if ($endTime->lessThan($startTime)) {
                                        $endTime->addDay();
                                    }

                                    // Round to nearest hour
                                    $durationHours = round($startTime->diffInMinutes($endTime) / 60);
                                @endphp
                                {{ $durationHours }} hours
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Grace Time Configuration --}}
    <div class="card border mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0 fw-semibold">
                <i class="mdi mdi-timer-sand text-warning me-2"></i>Grace Time Configuration
            </h5>
        </div>
        <div class="card-body">
            <div class="border rounded p-3 bg-light">
                <h6 class="fw-semibold mb-3 text-primary">
                    <i class="mdi mdi-clock-alert me-1"></i>Attendance Grace Periods
                </h6>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Grace Time Before (Clock In)</label>
                        <p class="fw-semibold mb-2">
                            <span class="badge bg-warning text-dark fs-6">
                                <i class="mdi mdi-clock-fast me-1"></i>
                                {{ $offDayPlan->grace_time_before }} minutes
                            </span>
                        </p>
                        <small class="text-muted">
                            Employees can clock in from:
                            <strong>
                                @php
                                    $effectiveStart = \Carbon\Carbon::parse($offDayPlan->start_time)->subMinutes($offDayPlan->grace_time_before);
                                @endphp
                                {{ $effectiveStart->format('h:i A') }}
                            </strong>
                        </small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Grace Time After (Clock Out)</label>
                        <p class="fw-semibold mb-2">
                            <span class="badge bg-info fs-6">
                                <i class="mdi mdi-clock-plus me-1"></i>
                                {{ $offDayPlan->grace_time_after }} minutes
                            </span>
                        </p>
                        <small class="text-muted">
                            Employees can clock out until:
                            <strong>
                                @php
                                    $effectiveEnd = \Carbon\Carbon::parse($offDayPlan->end_time)->addMinutes($offDayPlan->grace_time_after);
                                @endphp
                                {{ $effectiveEnd->format('h:i A') }}
                            </strong>
                        </small>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-md-12">
                        <div class="alert alert-info mb-0">
                            <i class="mdi mdi-information-outline me-2"></i>
                            Grace periods allow attendance flexibility without penalty. Total grace time:
                            <strong>{{ $offDayPlan->grace_time_before + $offDayPlan->grace_time_after }} minutes</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Remuneration Details --}}
    <div class="card border mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0 fw-semibold">
                <i class="mdi mdi-cash-multiple text-success me-2"></i>Remuneration Details
            </h5>
        </div>
        <div class="card-body">
            <div class="border rounded p-4 bg-light text-center">
                <h6 class="fw-semibold mb-3 text-primary">
                    <i class="mdi mdi-currency-usd me-1"></i>Off-Day Compensation
                </h6>
                <div class="mb-3">
                    <label class="text-muted small d-block mb-2">Fixed Remuneration Amount</label>
                    <h2 class="fw-bold text-success mb-1">
                        {{ \App\HelperClass::getGeneralSetting()->currency ?? '৳' }}
                        {{ number_format($offDayPlan->remuneration_amount, 2) }}
                    </h2>
                    <small class="text-muted">Per off-day worked</small>
                </div>

                <div class="alert alert-success mb-0">
                    <i class="mdi mdi-check-circle-outline me-2"></i>
                    Employees working during this off-day will receive a fixed payment of
                    <strong>{{ \App\HelperClass::getGeneralSetting()->currency ?? '৳' }}{{ number_format($offDayPlan->remuneration_amount, 2) }}</strong>
                </div>
            </div>
        </div>
    </div>

    {{-- Off-Day Plan Summary --}}
    <div class="card border mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0 fw-semibold">
                <i class="mdi mdi-chart-box text-info me-2"></i>Off-Day Plan Summary
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="text-muted small">Total Duration</label>
                    <p class="fw-semibold mb-0">
                        <span class="badge bg-primary fs-6">
                            {{ $durationHours }} hours
                        </span>
                    </p>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="text-muted small">Total Grace Time</label>
                    <p class="fw-semibold mb-0">
                        <span class="badge bg-warning text-dark fs-6">
                            {{ $offDayPlan->grace_time_before + $offDayPlan->grace_time_after }} minutes
                        </span>
                    </p>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="text-muted small">Plan Status</label>
                    <p class="fw-semibold mb-0">
                        <span class="badge bg-{{ $offDayPlan->status == 'active' ? 'success' : 'secondary' }} fs-6">
                            {{ ucfirst($offDayPlan->status) }}
                        </span>
                    </p>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-md-12">
                    <div class="alert alert-{{ $offDayPlan->status == 'active' ? 'success' : 'secondary' }} mb-0">
                        <i class="mdi mdi-{{ $offDayPlan->status == 'active' ? 'check-circle' : 'information' }}-outline me-2"></i>
                        This off-day plan is currently <strong>{{ $offDayPlan->status }}</strong>
                        @if($offDayPlan->status == 'active')
                            and can be assigned to employees for off-day work scheduling.
                        @else
                            and cannot be used for off-day assignments until activated.
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Page Actions - Fixed at Bottom Right --}}
    <div class="d-flex float-end justify-content-right align-items-center mb-4">
        <div>
            <a href="#" class="btn btn-primary me-2">
                <i class="mdi mdi-pencil me-1"></i>Edit
            </a>
            <a href="#" class="btn btn-secondary">
                <i class="mdi mdi-arrow-left me-1"></i>Back to List
            </a>
        </div>
    </div>
</div>
@endsection
