@extends('structure.master')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Leave Encashment Plan</h5>
                    @if (!$plan)
                        @can('leave-encashment-plans.create')
                        <a href="{{ route('plan.leave_encashment_plans.create') }}" class="btn btn-warning btn-sm">
                            <i class="mdi mdi-plus me-1"></i> Create Encashment Plan
                        </a>
                        @endcan
                    @else
                        @can('leave-encashment-plans.edit')
                        <a href="{{ route('plan.leave_encashment_plans.edit') }}" class="btn btn-primary btn-sm">
                            <i class="mdi mdi-pencil me-1"></i> Edit Encashment Plan
                        </a>
                        @endcan
                    @endif
                </div>
                <div class="card-body">
                    @if ($plan)
                        <!-- Display Leave Encashment Plan Details -->
                        <div class="row">
                            <div class="col-12">
                                <div class="card border">
                                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0 fw-semibold">
                                            <i class="mdi mdi-cash-multiple text-success me-2"></i>Encashment Configuration
                                        </h5>
                                        <span class="badge {{ $plan->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                                            {{ ucfirst($plan->status) }}
                                        </span>
                                    </div>
                                    <div class="card-body">
                                        <div class="row mb-4">
                                            <div class="col-12 mb-3">
                                                <h6 class="fw-bold text-primary mb-1">{{ $plan->title }}</h6>
                                                <p class="text-muted small">{{ $plan->description ?: 'No description provided.' }}</p>
                                            </div>
                                            
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-semibold text-muted">Encashment Basis</label>
                                                <div class="fs-5 fw-bold text-dark">
                                                    {{ ucfirst($plan->encashment_basis) }} Salary
                                                </div>
                                                <small class="text-muted">Salary component used for calculation</small>
                                            </div>
                                            
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-semibold text-muted">Encashment Rate</label>
                                                <div class="fs-5 fw-bold text-primary">
                                                    {{ number_format($plan->encashment_rate, 2) }}x
                                                </div>
                                                <small class="text-muted">Multiplier per day (1.00 = full day)</small>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-semibold text-muted">Min Balance to Maintain</label>
                                                <div class="fs-5 fw-bold text-warning">
                                                    {{ $plan->min_balance_to_maintain }} day(s)
                                                </div>
                                                <small class="text-muted">Required leave balance after encashment</small>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-semibold text-muted">Max Encashable Days (Yearly)</label>
                                                <div class="fs-5 fw-bold text-info">
                                                    {{ $plan->max_encashable_days_per_year ?: 'No Limit' }} {{ $plan->max_encashable_days_per_year ? 'day(s)' : '' }}
                                                </div>
                                                <small class="text-muted">Annual cap on encashable days</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- No Plan Exists -->
                        <div class="alert alert-info alert-dismissible fade show border-0" role="alert">
                            <h5 class="alert-heading text-info">
                                <i class="mdi mdi-information me-2"></i>No Leave Encashment Plan Found
                            </h5>
                            <p class="mb-0 text-muted mt-2">
                                You haven't created a leave encashment plan yet. Click the "Create Encashment Plan" button above to
                                set up policies for encashment basis, minimum balance requirements, and yearly limits.
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
