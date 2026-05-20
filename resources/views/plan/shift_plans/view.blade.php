@extends('structure.master')
@section('content')
    <div class="container-fluid mt-4">
        <!-- Basic Shift Information -->
        <div class="card border mb-4">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-semibold">
                    <i class="mdi mdi-clock-outline text-primary me-2"></i>Basic Shift Information
                </h5>
                <span class="badge bg-{{ $plan->active_ind == 'active' ? 'success' : 'secondary' }}">
                    {{ ucfirst($plan->active_ind) }}
                </span>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="text-muted small">Shift Name</label>
                        <p class="fw-semibold mb-0">{{ $plan->name }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="text-muted small">Clock In Time</label>
                        <p class="fw-semibold mb-0">
                            <i class="mdi mdi-clock-in text-success me-1"></i>
                            {{ date('h:i A', strtotime($plan->clock_in_time)) }}
                        </p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="text-muted small">Clock Out Time</label>
                        <p class="fw-semibold mb-0">
                            <i class="mdi mdi-clock-out text-danger me-1"></i>
                            {{ date('h:i A', strtotime($plan->clock_out_time)) }}
                        </p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="text-muted small">Full Day Duration</label>
                        <p class="fw-semibold mb-0">{{ $plan->treat_as_full_day_minutes }} Minutes</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="text-muted small">Half Day Duration</label>
                        <p class="fw-semibold mb-0">{{ $plan->treat_as_half_day_minutes }} Minutes</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="text-muted small">Grace Time</label>
                        <p class="fw-semibold mb-0">{{ $plan->grace_time }} Minutes</p>
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
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Excessive Late After</label>
                        <p class="fw-semibold mb-0">
                            <span class="badge bg-danger">{{ $plan->excessive_late_after_minutes }} Minutes</span>
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Early Out Grace</label>
                        <p class="fw-semibold mb-0">
                            <span class="badge bg-info">{{ $plan->early_out_grace_minutes }} Minutes</span>
                        </p>
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
                        <span class="badge bg-{{ $plan->breakfast_status == 'active' ? 'success' : 'secondary' }}">
                            {{ ucfirst($plan->breakfast_status) }}
                        </span>
                    </div>
                    <div class="card-body">
                        @if ($plan->breakfast_status == 'active')
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span class="text-muted">Start Time</span>
                                    <span
                                        class="fw-semibold">{{ date('h:i A', strtotime($plan->breakfast_start_time)) }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span class="text-muted">End Time</span>
                                    <span
                                        class="fw-semibold">{{ date('h:i A', strtotime($plan->breakfast_end_time)) }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span class="text-muted">Duration</span>
                                    <span class="badge bg-primary">
                                        {{ (strtotime($plan->breakfast_end_time) - strtotime($plan->breakfast_start_time)) / 60 }}
                                        Min
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
                        <span class="badge bg-{{ $plan->lunch_status == 'active' ? 'success' : 'secondary' }}">
                            {{ ucfirst($plan->lunch_status) }}
                        </span>
                    </div>
                    <div class="card-body">
                        @if ($plan->lunch_status == 'active')
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span class="text-muted">Start Time</span>
                                    <span
                                        class="fw-semibold">{{ date('h:i A', strtotime($plan->lunch_start_time)) }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span class="text-muted">End Time</span>
                                    <span class="fw-semibold">{{ date('h:i A', strtotime($plan->lunch_end_time)) }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span class="text-muted">Duration</span>
                                    <span class="badge bg-primary">
                                        {{ (strtotime($plan->lunch_end_time) - strtotime($plan->lunch_start_time)) / 60 }}
                                        Min
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
                        <span class="badge bg-{{ $plan->snacks_status == 'active' ? 'success' : 'secondary' }}">
                            {{ ucfirst($plan->snacks_status) }}
                        </span>
                    </div>
                    <div class="card-body">
                        @if ($plan->snacks_status == 'active')
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span class="text-muted">Start Time</span>
                                    <span
                                        class="fw-semibold">{{ date('h:i A', strtotime($plan->snacks_start_time)) }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span class="text-muted">End Time</span>
                                    <span class="fw-semibold">{{ date('h:i A', strtotime($plan->snacks_end_time)) }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span class="text-muted">Duration</span>
                                    <span class="badge bg-primary">
                                        {{ (strtotime($plan->snacks_end_time) - strtotime($plan->snacks_start_time)) / 60 }}
                                        Min
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
                        <span class="badge bg-{{ $plan->dinner_status == 'active' ? 'success' : 'secondary' }}">
                            {{ ucfirst($plan->dinner_status) }}
                        </span>
                    </div>
                    <div class="card-body">
                        @if ($plan->dinner_status == 'active')
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span class="text-muted">Start Time</span>
                                    <span
                                        class="fw-semibold">{{ date('h:i A', strtotime($plan->dinner_start_time)) }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span class="text-muted">End Time</span>
                                    <span
                                        class="fw-semibold">{{ date('h:i A', strtotime($plan->dinner_end_time)) }}</span>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span class="text-muted">Duration</span>
                                    <span class="badge bg-primary">
                                        {{ (strtotime($plan->dinner_end_time) - strtotime($plan->dinner_start_time)) / 60 }}
                                        Min
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
                <a href="{{ route('plan.shift_plans.edit', $plan->id) }}" class="btn btn-primary">
                    <i class="mdi mdi-pencil me-1"></i>Edit
                </a>
            </div>
        </div>
    </div>
@endsection

