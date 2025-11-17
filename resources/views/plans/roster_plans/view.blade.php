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
                    <p class="fw-semibold mb-0">{{ $plan->name }}</p>
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
                        <span class="badge bg-secondary fs-6">{{ $plan->swapping }} Days</span>
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
        <div class="card border mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0 fw-semibold">
                    <i class="mdi mdi-clock-outline text-success me-2"></i>Shift 1 Configuration
                </h5>
            </div>
            <div class="card-body">
                <div class="border rounded p-3 bg-light">
                    <h6 class="fw-semibold mb-3 text-primary">
                        <i class="mdi mdi-account-clock me-1"></i>{{ $plan->getFirstShift->name }}
                    </h6>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="text-muted small">Shift Name</label>
                            <p class="fw-semibold mb-0">{{ $plan->getFirstShift->name }}</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="text-muted small">Clock In Time</label>
                            <p class="fw-semibold mb-0">
                                <i class="mdi mdi-clock-start text-success me-1"></i>
                                {{ $plan->getFirstShift->clock_in_time }}
                            </p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="text-muted small">Clock Out Time</label>
                            <p class="fw-semibold mb-0">
                                <i class="mdi mdi-clock-end text-danger me-1"></i>
                                {{ $plan->getFirstShift->clock_out_time }}
                            </p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="text-muted small">Duration</label>
                            <p class="fw-semibold mb-0">
                                <span class="badge bg-secondary fs-6">
                                    @php
                                        $clockIn = \Carbon\Carbon::parse($plan->getFirstShift->clock_in_time);
                                        $clockOut = \Carbon\Carbon::parse($plan->getFirstShift->clock_out_time);

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
                            <a href="{{route('plans.shift_plans.show', $plan->first_shift_id)}}" class="btn btn-sm btn-outline-primary">
                                <i class="mdi mdi-eye me-1"></i>View Details
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    {{-- Shift 2 Details --}}
        <div class="card border mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0 fw-semibold">
                    <i class="mdi mdi-clock-outline text-warning me-2"></i>Shift 2 Configuration
                </h5>
            </div>
            <div class="card-body">
                <div class="border rounded p-3 bg-light">
                    <h6 class="fw-semibold mb-3 text-primary">
                        <i class="mdi mdi-account-clock me-1"></i>{{ $plan->getSecondShift->name }}
                    </h6>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="text-muted small">Shift Name</label>
                            <p class="fw-semibold mb-0">{{ $plan->getSecondShift->name }}</p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="text-muted small">Clock In Time</label>
                            <p class="fw-semibold mb-0">
                                <i class="mdi mdi-clock-start text-success me-1"></i>
                                {{ $plan->getSecondShift->clock_in_time }}
                            </p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="text-muted small">Clock Out Time</label>
                            <p class="fw-semibold mb-0">
                                <i class="mdi mdi-clock-end text-danger me-1"></i>
                                {{ $plan->getSecondShift->clock_out_time }}
                            </p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="text-muted small">Duration</label>
                            <p class="fw-semibold mb-0">
                                <span class="badge bg-secondary fs-6">
                                    @php
                                        $clockIn = \Carbon\Carbon::parse($plan->getSecondShift->clock_in_time);
                                        $clockOut = \Carbon\Carbon::parse($plan->getSecondShift->clock_out_time);

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
                            <a href="{{route('plans.shift_plans.show', $plan->second_shift_id)}}" class="btn btn-sm btn-outline-primary">
                                <i class="mdi mdi-eye me-1"></i>View Details
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    {{-- Page Actions --}}
    <div class="d-flex float-end justify-content-right align-items-center mb-4">
        <div>
            <a href="{{route('plans.roster_plans.edit', $plan->id)}}" class="btn btn-primary me-2">
                <i class="mdi mdi-pencil me-1"></i>Edit
            </a>
            <a href="{{route('plans.roster_plans.index')}}" class="btn btn-secondary">
                <i class="mdi mdi-arrow-left me-1"></i>Back to List
            </a>
        </div>
    </div>
</div>
@endsection
