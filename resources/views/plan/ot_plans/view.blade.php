@extends('structure.master')
@section('content')
    <div class="container-fluid mt-4">
        <!-- Basic OT Information -->
        <div class="card border mb-4">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-semibold">
                    <i class="mdi mdi-clock-plus-outline text-primary me-2"></i>Basic OT Information
                </h5>
                <span class="badge bg-{{ $plan->status == 'active' ? 'success' : 'secondary' }}">
                    {{ ucfirst($plan->status) }}
                </span>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="text-muted small">OT Plan Name</label>
                        <p class="fw-semibold mb-0">{{ $plan->name }}</p>
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

        <!-- Overtime Rate Configuration -->
        <div class="card border mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0 fw-semibold">
                    <i class="mdi mdi-cash-multiple text-success me-2"></i>Overtime Rate Configuration
                </h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label class="text-muted small">Configuration Type</label>
                        <p class="fw-semibold mb-0">
                            <span class="badge bg-primary">
                                {{ $plan->ot_config_type == 'Salary Based' ? 'Based on Salary' : 'Custom Rate' }}
                            </span>
                        </p>
                    </div>
                </div>

                @if ($plan->ot_config_type == 'Salary Based')
                    <!-- Salary Based Configuration -->
                    <div class="border rounded p-3 bg-light">
                        <h6 class="fw-semibold mb-3 text-primary">
                            <i class="mdi mdi-calculator me-1"></i>Overtime Rate Configuration (Based on Salary)
                        </h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">Rate Type</label>
                                <p class="fw-semibold mb-0">
                                    <span class="badge bg-info">
                                        {{ $plan->salary_rate_type == 'Basic Rate' ? 'Basic Rate' : 'Multiplier' }}
                                    </span>
                                </p>
                            </div>
                            @if ($plan->salary_rate_type == 'Multiplier')
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small">Overtime Multiplier</label>
                                    <p class="fw-semibold mb-0">
                                        <span
                                            class="badge bg-success fs-6">{{ number_format($plan->overtime_multiplier, 2) }}
                                            X Base Rate</span>
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                @else
                    <!-- Custom Rate Configuration -->
                    <div class="border rounded p-3 bg-light">
                        <h6 class="fw-semibold mb-3 text-success">
                            <i class="mdi mdi-cash me-1"></i>Overtime Rate (Custom)
                        </h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">Amount Per Hour</label>
                                <p class="fw-semibold mb-0">
                                    <span class="badge bg-success fs-6">
                                        {{ \App\HelperClass::getGeneralSetting()->currency ?? '৳' }}{{ number_format($plan->custom_overtime_rate, 2) }}/hour</span>
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
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
                    <div class="col-md-12 mb-3">
                        <label class="text-muted small">Maximum Overtime</label>
                        <p class="fw-semibold mb-0">
                            @if ($plan->maximum_overtime)
                                {{ number_format($plan->maximum_overtime, 2) }} hours
                            @else
                                <span class="text-muted">Unlimited</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Page Actions -->
        <div class="d-flex float-end justify-content-right align-items-center mb-4">
            <div>
                <a href="{{ route('plan.ot_plans.edit', $plan->id) }}" class="btn btn-primary">
                    <i class="mdi mdi-pencil me-1"></i>Edit
                </a>
            </div>
        </div>
    </div>
@endsection

