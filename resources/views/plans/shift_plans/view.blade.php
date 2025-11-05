@extends('structure.master')
@section('content')
    @php
        // Dummy Data Object
        $shiftPlan = (object)[
            'id' => 1,
            'shift_name' => 'Morning Shift - Head Office',
            'clock_in_time' => '09:00:00',
            'clock_out_time' => '18:00:00',
            'treat_as_full_day_minutes' => 480,
            'treat_as_half_day_minutes' => 240,
            'grace_time' => '00:15:00',
            'late_after_minutes' => 15,
            'excessive_late_after_minutes' => 30,
            'early_out_grace_minutes' => 5,
            'early_out_before' => '17:55:00',
            'breakfast_status' => 'active',
            'breakfast_start_time' => '10:00:00',
            'breakfast_end_time' => '10:15:00',
            'lunch_status' => 'active',
            'lunch_start_time' => '13:00:00',
            'lunch_end_time' => '14:00:00',
            'snacks_status' => 'active',
            'snacks_start_time' => '16:00:00',
            'snacks_end_time' => '16:15:00',
            'dinner_status' => 'inactive',
            'dinner_start_time' => null,
            'dinner_end_time' => null,
            'active_ind' => 'active',
            'created_at' => '2025-11-01 10:30:00',
            'updated_at' => '2025-11-04 12:45:00'
        ];
    @endphp

    <div class="container-fluid mt-4">
       <!-- Basic Shift Information -->
        <div class="card border mb-4">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-semibold">
                    <i class="mdi mdi-clock-outline text-primary me-2"></i>Basic Shift Information
                </h5>
                <span class="badge bg-{{ $shiftPlan->active_ind == 'active' ? 'success' : 'secondary' }}">
                    {{ ucfirst($shiftPlan->active_ind) }}
                </span>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="text-muted small">Shift Name</label>
                        <p class="fw-semibold mb-0">{{ $shiftPlan->shift_name }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="text-muted small">Clock In Time</label>
                        <p class="fw-semibold mb-0">
                            <i class="mdi mdi-clock-in text-success me-1"></i>
                            {{ date('h:i A', strtotime($shiftPlan->clock_in_time)) }}
                        </p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="text-muted small">Clock Out Time</label>
                        <p class="fw-semibold mb-0">
                            <i class="mdi mdi-clock-out text-danger me-1"></i>
                            {{ date('h:i A', strtotime($shiftPlan->clock_out_time)) }}
                        </p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="text-muted small">Full Day Duration</label>
                        <p class="fw-semibold mb-0">{{ $shiftPlan->treat_as_full_day_minutes }} Minutes</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="text-muted small">Half Day Duration</label>
                        <p class="fw-semibold mb-0">{{ $shiftPlan->treat_as_half_day_minutes }} Minutes</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="text-muted small">Grace Time</label>
                        <p class="fw-semibold mb-0">{{ date('H:i', strtotime($shiftPlan->grace_time)) }}</p>
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
                        <label class="text-muted small">Late After</label>
                        <p class="fw-semibold mb-0">
                            <span class="badge bg-warning text-dark">{{ $shiftPlan->late_after_minutes }} Minutes</span>
                        </p>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="text-muted small">Excessive Late After</label>
                        <p class="fw-semibold mb-0">
                            <span class="badge bg-danger">{{ $shiftPlan->excessive_late_after_minutes }} Minutes</span>
                        </p>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="text-muted small">Early Out Grace</label>
                        <p class="fw-semibold mb-0">
                            <span class="badge bg-info">{{ $shiftPlan->early_out_grace_minutes }} Minutes</span>
                        </p>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="text-muted small">Early Out Before</label>
                        <p class="fw-semibold mb-0">{{ date('h:i A', strtotime($shiftPlan->early_out_before)) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Meal Break Settings -->
        <div class="row">
            <!-- Breakfast Break -->
            <div class="col-md-6 mb-4">
                <div class="card border h-100">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-semibold">
                            <i class="mdi mdi-coffee text-warning me-2"></i>Breakfast Break
                        </h6>
                        <span class="badge bg-{{ $shiftPlan->breakfast_status == 'active' ? 'success' : 'secondary' }}">
                            {{ ucfirst($shiftPlan->breakfast_status) }}
                        </span>
                    </div>
                    <div class="card-body">
                        @if($shiftPlan->breakfast_status == 'active')
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span class="text-muted">Start Time</span>
                                    <span class="fw-semibold">{{ date('h:i A', strtotime($shiftPlan->breakfast_start_time)) }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span class="text-muted">End Time</span>
                                    <span class="fw-semibold">{{ date('h:i A', strtotime($shiftPlan->breakfast_end_time)) }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span class="text-muted">Duration</span>
                                    <span class="badge bg-primary">
                                        {{ (strtotime($shiftPlan->breakfast_end_time) - strtotime($shiftPlan->breakfast_start_time)) / 60 }} Min
                                    </span>
                                </li>
                            </ul>
                        @else
                            <p class="text-muted mb-0">Not configured</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Lunch Break -->
            <div class="col-md-6 mb-4">
                <div class="card border h-100">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-semibold">
                            <i class="mdi mdi-food text-success me-2"></i>Lunch Break
                        </h6>
                        <span class="badge bg-{{ $shiftPlan->lunch_status == 'active' ? 'success' : 'secondary' }}">
                            {{ ucfirst($shiftPlan->lunch_status) }}
                        </span>
                    </div>
                    <div class="card-body">
                        @if($shiftPlan->lunch_status == 'active')
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span class="text-muted">Start Time</span>
                                    <span class="fw-semibold">{{ date('h:i A', strtotime($shiftPlan->lunch_start_time)) }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span class="text-muted">End Time</span>
                                    <span class="fw-semibold">{{ date('h:i A', strtotime($shiftPlan->lunch_end_time)) }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span class="text-muted">Duration</span>
                                    <span class="badge bg-primary">
                                        {{ (strtotime($shiftPlan->lunch_end_time) - strtotime($shiftPlan->lunch_start_time)) / 60 }} Min
                                    </span>
                                </li>
                            </ul>
                        @else
                            <p class="text-muted mb-0">Not configured</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Snacks Break -->
            <div class="col-md-6 mb-4">
                <div class="card border h-100">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-semibold">
                            <i class="mdi mdi-cookie text-info me-2"></i>Snacks Break
                        </h6>
                        <span class="badge bg-{{ $shiftPlan->snacks_status == 'active' ? 'success' : 'secondary' }}">
                            {{ ucfirst($shiftPlan->snacks_status) }}
                        </span>
                    </div>
                    <div class="card-body">
                        @if($shiftPlan->snacks_status == 'active')
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span class="text-muted">Start Time</span>
                                    <span class="fw-semibold">{{ date('h:i A', strtotime($shiftPlan->snacks_start_time)) }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span class="text-muted">End Time</span>
                                    <span class="fw-semibold">{{ date('h:i A', strtotime($shiftPlan->snacks_end_time)) }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span class="text-muted">Duration</span>
                                    <span class="badge bg-primary">
                                        {{ (strtotime($shiftPlan->snacks_end_time) - strtotime($shiftPlan->snacks_start_time)) / 60 }} Min
                                    </span>
                                </li>
                            </ul>
                        @else
                            <p class="text-muted mb-0">Not configured</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Dinner Break -->
            <div class="col-md-6 mb-4">
                <div class="card border h-100">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 fw-semibold">
                            <i class="mdi mdi-silverware-fork-knife text-danger me-2"></i>Dinner Break
                        </h6>
                        <span class="badge bg-{{ $shiftPlan->dinner_status == 'active' ? 'success' : 'secondary' }}">
                            {{ ucfirst($shiftPlan->dinner_status) }}
                        </span>
                    </div>
                    <div class="card-body">
                        @if($shiftPlan->dinner_status == 'active')
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span class="text-muted">Start Time</span>
                                    <span class="fw-semibold">{{ date('h:i A', strtotime($shiftPlan->dinner_start_time)) }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span class="text-muted">End Time</span>
                                    <span class="fw-semibold">{{ date('h:i A', strtotime($shiftPlan->dinner_end_time)) }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span class="text-muted">Duration</span>
                                    <span class="badge bg-primary">
                                        {{ (strtotime($shiftPlan->dinner_end_time) - strtotime($shiftPlan->dinner_start_time)) / 60 }} Min
                                    </span>
                                </li>
                            </ul>
                        @else
                            <p class="text-muted mb-0">Not configured</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
         <!-- Page Header -->
        <div class="d-flex float-end justify-content-right align-items-center mb-4">
            <div>
                <a href="#" class="btn btn-primary">
                    <i class="mdi mdi-pencil me-1"></i>Edit
                </a>
            </div>
        </div>
    </div>
@endsection
