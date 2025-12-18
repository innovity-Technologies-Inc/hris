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
                            @if (isset($plan->short_name) && $plan->short_name)
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

        {{-- Shift Configuration --}}
        <div class="card border mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0 fw-semibold">
                    <i class="mdi mdi-clock-outline text-success me-2"></i>Shift Configuration
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    {{-- Shift 1 --}}
                    <div class="col-md-4">
                        <div class="border rounded p-3 bg-light h-100">
                            <h6 class="fw-semibold mb-3 text-primary">
                                <i class="mdi mdi-clock-outline text-success me-1"></i>Shift 1
                            </h6>
                            <h6 class="fw-semibold mb-3">{{ $plan->getFirstShift->name }}</h6>
                            <div class="mb-2">
                                <label class="text-muted small">Clock In Time</label>
                                <p class="fw-semibold mb-0">
                                    <i class="mdi mdi-clock-start text-success me-1"></i>
                                    {{ \Carbon\Carbon::parse($plan->getFirstShift->clock_in_time)->format('H:i:s') }}
                                </p>
                            </div>
                            <div class="mb-2">
                                <label class="text-muted small">Clock Out Time</label>
                                <p class="fw-semibold mb-0">
                                    <i class="mdi mdi-clock-end text-danger me-1"></i>
                                    {{ \Carbon\Carbon::parse($plan->getFirstShift->clock_out_time)->format('H:i:s') }}
                                </p>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted small">Duration</label>
                                <p class="fw-semibold mb-0">
                                    <span class="badge bg-secondary fs-6">
                                        @php
                                            $clockIn = \Carbon\Carbon::parse($plan->getFirstShift->clock_in_time);
                                            $clockOut = \Carbon\Carbon::parse($plan->getFirstShift->clock_out_time);
                                            if ($clockOut->lessThan($clockIn)) {
                                                $clockOut->addDay();
                                            }
                                            $duration = $clockIn->diffInHours($clockOut);
                                        @endphp
                                        {{ $duration }} hours
                                    </span>
                                </p>
                            </div>
                            <div class="text-end">
                                <a href="{{ route('plans.shift_plans.show', $plan->first_shift_id) }}"
                                    class="btn btn-sm btn-outline-primary">
                                    <i class="mdi mdi-eye me-1"></i>View
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Shift 2 --}}
                    <div class="col-md-4">
                        <div class="border rounded p-3 bg-light h-100">
                            <h6 class="fw-semibold mb-3 text-primary">
                                <i class="mdi mdi-clock-outline text-warning me-1"></i>Shift 2
                            </h6>
                            <h6 class="fw-semibold mb-3">{{ $plan->getSecondShift->name }}</h6>
                            <div class="mb-2">
                                <label class="text-muted small">Clock In Time</label>
                                <p class="fw-semibold mb-0">
                                    <i class="mdi mdi-clock-start text-success me-1"></i>
                                    {{ \Carbon\Carbon::parse($plan->getSecondShift->clock_in_time)->format('H:i:s') }}
                                </p>
                            </div>
                            <div class="mb-2">
                                <label class="text-muted small">Clock Out Time</label>
                                <p class="fw-semibold mb-0">
                                    <i class="mdi mdi-clock-end text-danger me-1"></i>
                                    {{ \Carbon\Carbon::parse($plan->getSecondShift->clock_out_time)->format('H:i:s') }}
                                </p>
                            </div>
                            <div class="mb-3">
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
                            <div class="text-end">
                                <a href="{{ route('plans.shift_plans.show', $plan->second_shift_id) }}"
                                    class="btn btn-sm btn-outline-primary">
                                    <i class="mdi mdi-eye me-1"></i>View
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Shift 3 (if exists) --}}
                    @if ($plan->third_shift_id && $plan->getThirdShift)
                        <div class="col-md-4">
                            <div class="border rounded p-3 bg-light h-100">
                                <h6 class="fw-semibold mb-3 text-primary">
                                    <i class="mdi mdi-clock-outline text-info me-1"></i>Shift 3
                                </h6>
                                <h6 class="fw-semibold mb-3">{{ $plan->getThirdShift->name }}</h6>
                                <div class="mb-2">
                                    <label class="text-muted small">Clock In Time</label>
                                    <p class="fw-semibold mb-0">
                                        <i class="mdi mdi-clock-start text-success me-1"></i>
                                        {{ \Carbon\Carbon::parse($plan->getThirdShift->clock_in_time)->format('H:i:s') }}
                                    </p>
                                </div>
                                <div class="mb-2">
                                    <label class="text-muted small">Clock Out Time</label>
                                    <p class="fw-semibold mb-0">
                                        <i class="mdi mdi-clock-end text-danger me-1"></i>
                                        {{ \Carbon\Carbon::parse($plan->getThirdShift->clock_out_time)->format('H:i:s') }}
                                    </p>
                                </div>
                                <div class="mb-3">
                                    <label class="text-muted small">Duration</label>
                                    <p class="fw-semibold mb-0">
                                        <span class="badge bg-secondary fs-6">
                                            @php
                                                $clockIn = \Carbon\Carbon::parse($plan->getThirdShift->clock_in_time);
                                                $clockOut = \Carbon\Carbon::parse($plan->getThirdShift->clock_out_time);
                                                if ($clockOut->lessThan($clockIn)) {
                                                    $clockOut->addDay();
                                                }
                                                $duration = $clockIn->diffInHours($clockOut);
                                            @endphp
                                            {{ $duration }} hours
                                        </span>
                                    </p>
                                </div>
                                <div class="text-end">
                                    <a href="{{ route('plans.shift_plans.show', $plan->third_shift_id) }}"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="mdi mdi-eye me-1"></i>View
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>


        {{-- Page Actions --}}
        <div class="d-flex float-end justify-content-right align-items-center mb-4">
            <div>
                <a href="{{ route('plans.roster_plans.edit', $plan->id) }}" class="btn btn-primary me-2">
                    <i class="mdi mdi-pencil me-1"></i>Edit
                </a>
                <a href="{{ route('plans.roster_plans.index') }}" class="btn btn-secondary">
                    <i class="mdi mdi-arrow-left me-1"></i>Back to List
                </a>
            </div>
        </div>
    </div>
@endsection
