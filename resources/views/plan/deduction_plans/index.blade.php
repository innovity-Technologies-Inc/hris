@extends('structure.master')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Deduction Plan</h5>
                    @if (!$plan)
                        <a href="{{ route('plan.deduction_plans.create') }}" class="btn btn-warning btn-sm">
                            <i class="mdi mdi-plus me-1"></i> Create Deduction Plan
                        </a>
                    @else
                        <a href="{{ route('plan.deduction_plans.edit') }}" class="btn btn-primary btn-sm">
                            <i class="mdi mdi-pencil me-1"></i> Edit Deduction Plan
                        </a>
                    @endif
                </div>
                <div class="card-body">
                    @if ($plan)
                        <!-- Display Deduction Plan Details -->
                        <div class="row">
                            <div class="col-12">
                                <div class="card border">
                                    <div class="card-header bg-light">
                                        <h5 class="mb-0 fw-semibold">
                                            <i class="mdi mdi-calculator text-success me-2"></i>Deduction Configuration
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <!-- Late Deduction -->
                                        <div class="row mb-4">
                                            <div class="col-12">
                                                <h6 class="fw-bold text-primary mb-3">Late Deduction</h6>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-semibold text-muted">Late Deduction Days</label>
                                                <div class="fs-5 fw-bold text-primary">
                                                    {{ $plan->late_deduction_days }} day(s)
                                                </div>
                                                <small class="text-muted">Number of days to deduct for late arrival</small>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-semibold text-muted">Late Salary Deduction
                                                    Rate</label>
                                                <div class="fs-5 fw-bold text-primary">
                                                    {{ number_format($plan->late_salary_deduction_rate, 2) }} Day(s) Salary
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Early Out Deduction -->
                                        <div class="row mb-4">
                                            <div class="col-12">
                                                <h6 class="fw-bold text-warning mb-3">Early Out Deduction</h6>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-semibold text-muted">Early Out Deduction
                                                    Days</label>
                                                <div class="fs-5 fw-bold text-warning">
                                                    {{ $plan->early_out_deduction_days }} day(s)
                                                </div>
                                                <small class="text-muted">Number of days to deduct for leaving early</small>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-semibold text-muted">Early Out Salary Deduction
                                                    Rate</label>
                                                <div class="fs-5 fw-bold text-warning">
                                                    {{ number_format($plan->early_out_salary_deduction_rate, 2) }} Day(s)
                                                    Salary
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Excessive Late Deduction -->
                                        <div class="row mb-4">
                                            <div class="col-12">
                                                <h6 class="fw-bold text-danger mb-3">Excessive Late Deduction</h6>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-semibold text-muted">Excessive Late Deduction
                                                    Days</label>
                                                <div class="fs-5 fw-bold text-danger">
                                                    {{ $plan->excessive_late_deduction_days }} day(s)
                                                </div>
                                                <small class="text-muted">Number of days to deduct for excessive
                                                    lateness</small>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-semibold text-muted">Excessive Late Salary
                                                    Deduction Rate</label>
                                                <div class="fs-5 fw-bold text-danger">
                                                    {{ number_format($plan->excessive_late_salary_deduction_rate, 2) }}
                                                    Day(s) Salary
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Absent Deduction -->
                                        <div class="row mb-4">
                                            <div class="col-12">
                                                <h6 class="fw-bold text-secondary mb-3">Absent Deduction</h6>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-semibold text-muted">Absent Deduction
                                                    Days</label>
                                                <div class="fs-5 fw-bold text-secondary">
                                                    {{ $plan->absent_deduction_days }} day(s)
                                                </div>
                                                <small class="text-muted">Number of days to deduct for absence</small>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-semibold text-muted">Absent Salary
                                                    Deduction Rate</label>
                                                <div class="fs-5 fw-bold text-secondary">
                                                    {{ number_format($plan->absent_salary_deduction_rate, 2) }} Day(s)
                                                    Salary
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Calculation Type -->
                                        <div class="row mb-4">
                                            <div class="col-12">
                                                <h6 class="fw-bold text-info mb-3">Calculation Settings</h6>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-semibold text-muted">Calculation Type</label>
                                                <div>
                                                    <span class="badge bg-info fs-6">
                                                        {{ ucwords(str_replace('_', ' ', $plan->calculation_type)) }}
                                                    </span>
                                                </div>
                                                <small class="text-muted">Base salary type for deduction
                                                    calculations</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- No Plan Exists -->
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <h5 class="alert-heading">
                                <i class="mdi mdi-information me-2"></i>No Deduction Plan Found
                            </h5>
                            <p class="mb-0">
                                You haven't created a deduction plan yet. Click the "Create Deduction Plan" button above to
                                set up deduction rates for late arrivals, early departures, and excessive lateness.
                            </p>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>

                        <div class="text-center py-5">
                            <i class="mdi mdi-calculator-variant-outline text-muted" style="font-size: 80px;"></i>
                            <h4 class="text-muted mt-3">Create Your Deduction Plan</h4>
                            <p class="text-muted">Set up deduction amounts for attendance violations</p>
                            <a href="{{ route('plan.deduction_plans.create') }}" class="btn btn-warning mt-3">
                                <i class="mdi mdi-plus me-1"></i> Create Deduction Plan
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

