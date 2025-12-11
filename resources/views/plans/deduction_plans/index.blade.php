@extends('structure.master')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Deduction Plan</h5>
                    @if (!$plan)
                        <a href="{{ route('plans.deduction_plans.create') }}" class="btn btn-warning btn-sm">
                            <i class="mdi mdi-plus me-1"></i> Create Deduction Plan
                        </a>
                    @else
                        <a href="{{ route('plans.deduction_plans.edit') }}" class="btn btn-primary btn-sm">
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
                                        <div class="row">
                                            <div class="col-md-3 mb-3">
                                                <label class="form-label fw-semibold text-muted">Late Deduction</label>
                                                <div class="fs-5 fw-bold text-primary">
                                                    {{ \App\HelperClass::getGeneralSetting()->currency ?? 'Tk' }}
                                                    {{ number_format($plan->late_deduction, 2) }}
                                                </div>
                                                <small class="text-muted">Amount deducted for late arrival</small>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label class="form-label fw-semibold text-muted">Early Out Deduction</label>
                                                <div class="fs-5 fw-bold text-warning">
                                                    {{ \App\HelperClass::getGeneralSetting()->currency ?? 'Tk' }}
                                                    {{ number_format($plan->early_out_deduction, 2) }}
                                                </div>
                                                <small class="text-muted">Amount deducted for leaving early</small>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label class="form-label fw-semibold text-muted">Excessive Late
                                                    Deduction</label>
                                                <div class="fs-5 fw-bold text-danger">
                                                    {{ \App\HelperClass::getGeneralSetting()->currency ?? 'Tk' }}
                                                    {{ number_format($plan->excessive_late_deduction, 2) }}
                                                </div>
                                                <small class="text-muted">Amount for excessive lateness</small>
                                            </div>
                                            <div class="col-md-3 mb-3">
                                                <label class="form-label fw-semibold text-muted">Status</label>
                                                <div>
                                                    <span
                                                        class="badge bg-{{ $plan->status == 'active' ? 'success' : 'danger' }} fs-6">
                                                        {{ ucfirst($plan->status) }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold text-muted">Created At</label>
                                                <div class="text-secondary">
                                                    {{ $plan->created_at->format('d M Y, h:i A') }}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold text-muted">Last Updated</label>
                                                <div class="text-secondary">
                                                    {{ $plan->updated_at->format('d M Y, h:i A') }}
                                                </div>
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
                            <a href="{{ route('plans.deduction_plans.create') }}" class="btn btn-warning mt-3">
                                <i class="mdi mdi-plus me-1"></i> Create Deduction Plan
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
