@php
    $plan = (object)[
        'id' => 1,
        'plan_name' => 'Weekly Day-Night Rotation',
        'short_name' => 'WDN',
        'status' => 'active', // or 'inactive'
        'repetition_days' => 7,
        'description' => 'Standard weekly rotation between day and night shifts for manufacturing floor',
    ];

    // Fetch shift 1 details
    // In production: $shift1 = Shift::find($plan->shift_1_id);
    $shift1 = (object)[
        'id' => 1,
        'name' => 'Morning Shift',
        'clock_in_time' => '06:00 AM',
        'clock_out_time' => '02:00 PM',
    ];

    // Fetch shift 2 details (optional)
    // In production: $shift2 = Shift::find($plan->shift_2_id);
    $shift2 = (object)[
        'id' => 4,
        'name' => 'Night Shift',
        'clock_in_time' => '10:00 PM',
        'clock_out_time' => '06:00 AM',
    ];
@endphp

@extends('structure.master')
@section('content')
<div class="container-fluid mt-4">
    {{-- Page Header --}}
    <div class="mb-4">
        <h4 class="fw-semibold">
            <i class="mdi mdi-calendar-clock text-primary me-2"></i>Roster Plan Details
        </h4>
        <p class="text-muted small mb-0">View roster plan configuration and assigned shifts</p>
    </div>

    {{-- Basic Roster Information --}}
    <div class="card border mb-4">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-semibold">
                <i class="mdi mdi-information-outline text-primary me-2"></i>Basic Roster Information
            </h5>
            <span class="badge bg-{{ $plan->status == 'active' ? 'success' : 'secondary' }}">
                {{ ucfirst($plan->status) }}
            </span>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="text-muted small">Plan Name</label>
                    <p class="fw-semibold mb-0">{{ $plan->plan_name }}</p>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="text-muted small">Short Name</label>
                    <p class="fw-semibold mb-0">
                        @if(isset($plan->short_name) && $plan->short_name)
                            <span class="badge bg-secondary fs-6">{{ $plan->short_name }}</span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </p>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="text-muted small">Repetition Days</label>
                    <p class="fw-semibold mb-0">
                        <span class="badge bg-secondary fs-6">{{ $plan->repetition_days }} Days</span>
                    </p>
                </div>
            </div>

            @if ($plan->description)
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="text-muted small">Description</label>
                        <p class="fw-semibold mb-0">{{ $plan->description }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Shift 1 Details --}}
    @if (isset($shift1))
        <div class="card border mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0 fw-semibold">
                    <i class="mdi mdi-clock-outline text-success me-2"></i>Shift 1 Configuration
                </h5>
            </div>
            <div class="card-body">
                <div class="border rounded p-3 bg-light">
                    <h6 class="fw-semibold mb-3 text-primary">
                        <i class="mdi mdi-account-clock me-1"></i>{{ $shift1->name }}
                    </h6>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="text-muted small">Shift Name</label>
                            <p class="fw-semibold mb-0">{{ $shift1->name }}</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="text-muted small">Clock In Time</label>
                            <p class="fw-semibold mb-0">
                                <i class="mdi mdi-clock-start text-success me-1"></i>
                                {{ $shift1->clock_in_time }}
                            </p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="text-muted small">Clock Out Time</label>
                            <p class="fw-semibold mb-0">
                                <i class="mdi mdi-clock-end text-danger me-1"></i>
                                {{ $shift1->clock_out_time }}
                            </p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="text-muted small">Duration</label>
                            <p class="fw-semibold mb-0">
                                <span class="badge bg-secondary fs-6">
                                    @php
                                        $clockIn = \Carbon\Carbon::parse($shift1->clock_in_time);
                                        $clockOut = \Carbon\Carbon::parse($shift1->clock_out_time);

                                        // Handle overnight shifts (clock out is next day)
                                        if ($clockOut->lessThan($clockIn)) {
                                            $clockOut->addDay();
                                        }

                                        $duration = $clockIn->diffInHours($clockOut);
                                    @endphp
                                    {{ $duration }} hours
                                </span>
                            </p>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12 text-end">
                            <a href="#" class="btn btn-sm btn-outline-primary">
                                <i class="mdi mdi-eye me-1"></i>View Details
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Shift 2 Details --}}
    @if (isset($shift2))
        <div class="card border mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0 fw-semibold">
                    <i class="mdi mdi-clock-outline text-warning me-2"></i>Shift 2 Configuration
                </h5>
            </div>
            <div class="card-body">
                <div class="border rounded p-3 bg-light">
                    <h6 class="fw-semibold mb-3 text-primary">
                        <i class="mdi mdi-account-clock me-1"></i>{{ $shift2->name }}
                    </h6>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="text-muted small">Shift Name</label>
                            <p class="fw-semibold mb-0">{{ $shift2->name }}</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="text-muted small">Clock In Time</label>
                            <p class="fw-semibold mb-0">
                                <i class="mdi mdi-clock-start text-success me-1"></i>
                                {{ $shift2->clock_in_time }}
                            </p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="text-muted small">Clock Out Time</label>
                            <p class="fw-semibold mb-0">
                                <i class="mdi mdi-clock-end text-danger me-1"></i>
                                {{ $shift2->clock_out_time }}
                            </p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="text-muted small">Duration</label>
                            <p class="fw-semibold mb-0">
                                <span class="badge bg-secondary fs-6">
                                    @php
                                        $clockIn = \Carbon\Carbon::parse($shift2->clock_in_time);
                                        $clockOut = \Carbon\Carbon::parse($shift2->clock_out_time);

                                        if ($clockOut->lessThan($clockIn)) {
                                            $clockOut->addDay();
                                        }

                                        $duration = $clockIn->diffInHours($clockOut);
                                    @endphp
                                    {{ $duration }} hours
                                </span>
                            </p>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-12 text-end">
                            <a href="#" class="btn btn-sm btn-outline-primary">
                                <i class="mdi mdi-eye me-1"></i>View Details
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Roster Summary --}}
    <div class="card border mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0 fw-semibold">
                <i class="mdi mdi-calendar-multiple text-info me-2"></i>Roster Summary
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="text-muted small">Total Shifts Assigned</label>
                    <p class="fw-semibold mb-0">
                        <span class="badge bg-primary fs-6">
                            {{ (isset($shift1) ? 1 : 0) + (isset($shift2) ? 1 : 0) }} Shift(s)
                        </span>
                    </p>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="text-muted small">Repetition Cycle</label>
                    <p class="fw-semibold mb-0">
                        <span class="badge bg-success fs-6">{{ $plan->repetition_days }} Days</span>
                    </p>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="text-muted small">Plan Status</label>
                    <p class="fw-semibold mb-0">
                        <span class="badge bg-{{ $plan->status == 'active' ? 'success' : 'secondary' }} fs-6">
                            {{ ucfirst($plan->status) }}
                        </span>
                    </p>
                </div>
            </div>

            @if (isset($shift1) && isset($shift2))
                <div class="row mt-2">
                    <div class="col-md-12">
                        <div class="alert alert-success mb-0">
                            <i class="mdi mdi-check-circle-outline me-2"></i>
                            This roster plan has <strong>multiple shifts</strong> configured for rotation scheduling.
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Page Actions --}}
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
