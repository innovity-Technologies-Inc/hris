@extends('structure.master')
@section('content')
    <div class="container-fluid mt-4">
        <!-- Summary Cards -->
        <div class="row mb-4">
            <!-- Leave Name Card -->
            <div class="col-md-4">
                <div class="card border h-100">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i style="height: 32px; width: 32px" class="text-primary" data-feather="file-text"></i>
                        </div>
                        <p class="text-muted small mb-2">Leave Name</p>
                        <h3 class="fw-semibold mb-2">{{ $plan->name }}</h3>
                        <span class="badge bg-light text-dark fw-semibold">{{ $plan->short_name ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>

            <!-- Leave Limit Card -->
            <div class="col-md-4">
                <div class="card border h-100">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i style="height: 32px; width: 32px" class="text-warning" data-feather="calendar"></i>
                        </div>
                        <p class="text-muted small mb-2">Annual Leave Limit</p>
                        <h2 class="fw-semibold text-primary mb-0">{{ $plan->leave_limit ?? 0 }}</h2>
                        <small class="text-muted">Days Per Year</small>
                    </div>
                </div>
            </div>

            <!-- Status Card -->
            <div class="col-md-4">
                <div class="card border h-100">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            @if ($plan->active_ind == 'active')
                                <i style="height: 32px; width: 32px" class="text-success" data-feather="check-circle"></i>
                            @else
                                <i style="height: 32px; width: 32px" class="text-danger" data-feather="x-circle"></i>
                            @endif
                        </div>
                        <p class="text-muted small mb-2">Status</p>
                        <div class="mb-2">
                            @if ($plan->active_ind == 'active')
                                <span class="badge bg-success text-white"
                                    style="font-size: 0.875rem; padding: 0.5rem 1rem;">
                                    <i style="height: 10px; width: 10px" data-feather="check-circle"></i> Active
                                </span>
                            @else
                                <span class="badge bg-danger text-white" style="font-size: 0.875rem; padding: 0.5rem 1rem;">
                                    <i style="height: 10px; width: 10px" data-feather="x-circle"></i> Inactive
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Classification Details -->
        <div class="card border mb-4">
            <div class="card-header bg-light border-bottom">
                <h6 class="mb-0 fw-semibold">Classification Details</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-light p-3 rounded"
                                style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                                <i style="height: 24px; width: 24px;" class="text-info" data-feather="users"></i>
                            </div>
                            <div>
                                <p class="text-muted small mb-1">Applicable For</p>
                                <h6 class="fw-semibold mb-0">{{ ucfirst($plan->applicable_gender) }}</h6>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 mb-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-light p-3 rounded"
                                style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                                <i style="height: 24px; width: 24px;" class="text-primary" data-feather="calendar"></i>
                            </div>
                            <div>
                                <p class="text-muted small mb-1">Day Type</p>
                                <h6 class="fw-semibold mb-0">{{ str_replace('_', ' ', ucfirst($plan->day_type)) }}</h6>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 mb-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-light p-3 rounded"
                                style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                                <i style="height: 24px; width: 24px;" class="text-success" data-feather="tag"></i>
                            </div>
                            <div>
                                <p class="text-muted small mb-1">Leave Type</p>
                                <h6 class="fw-semibold mb-0">{{ ucfirst($plan->leave_type) }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Leave Configuration -->
        <div class="card border mb-4">
            <div class="card-header bg-light border-bottom">
                <h6 class="mb-0 fw-semibold">Configuration Rules</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-light p-3 rounded"
                                style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                                <i style="height: 24px; width: 24px;" class="text-warning" data-feather="layers"></i>
                            </div>
                            <div>
                                <p class="text-muted small mb-1">Max Days Per Application</p>
                                <h6 class="fw-semibold mb-0">{{ $plan->max_no_of_days }} Days</h6>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 mb-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-light p-3 rounded"
                                style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                                <i style="height: 24px; width: 24px;" class="text-danger" data-feather="skip-back"></i>
                            </div>
                            <div>
                                <p class="text-muted small mb-1">Off Days Treatment</p>
                                <h6 class="fw-semibold mb-0">{{ $plan->off_day_include }}</h6>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 mb-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-light p-3 rounded"
                                style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                                <i style="height: 24px; width: 24px;" class="text-secondary" data-feather="lock"></i>
                            </div>
                            <div>
                                <p class="text-muted small mb-1">Limit Application</p>
                                @if ($plan->apply_limit == 'yes')
                                    <span class="badge bg-success text-white">Applied</span>
                                @else
                                    <span class="badge bg-secondary">Not Applied</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Leave Options -->
        <div class="card border">
            <div class="card-header bg-light border-bottom">
                <h6 class="mb-0 fw-semibold">Leave Options</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
                            <div>
                                <div class="mb-2">
                                    <i style="height: 20px; width: 20px;" class="text-info"
                                        data-feather="help-circle"></i>
                                </div>
                                <p class="text-muted small mb-1">Fractional Leave Allowed</p>
                                <p class="fw-semibold mb-0">Allow employees to take partial days</p>
                            </div>
                            @if ($plan->allow_fractional_leave == 'active')
                                <span class="badge bg-success text-white">
                                    <i style="height: 12px; width: 12px" data-feather="check"></i> Yes
                                </span>
                            @else
                                <span class="badge bg-light text-dark">
                                    <i style="height: 12px; width: 12px" data-feather="x"></i> No
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="d-flex justify-content-between align-items-center p-3">
                            <div>
                                <div class="mb-2">
                                    <i style="height: 20px; width: 20px;" class="text-warning"
                                        data-feather="alert-circle"></i>
                                </div>
                                <p class="text-muted small mb-1">Leave Limit Applied</p>
                                <p class="fw-semibold mb-0">Maximum applications allowed: {{ $plan->apply_limit ?? 'N/A' }}
                                </p>
                            </div>
                            <span class="badge bg-info text-white">
                                {{ $plan->apply_limit ?? 'N/A' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-white border-top">
                <div class="d-flex justify-content-end">
                    <a type="button" class="btn btn-primary btn-sm"
                        href="{{ route('plans.leave_plans.edit', $plan->id) }}" title="Edit">
                        <i style="height: 12px; width: 12px" data-feather="edit"></i> Edit
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
