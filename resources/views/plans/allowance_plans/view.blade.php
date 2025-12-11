@extends('structure.master')
@section('content')
    <div class="container-fluid mt-4">
        <!-- Summary Cards -->
        <div class="row mb-4">
            <!-- Allowance Name Card -->
            <div class="col-md-4">
                <div class="card border h-100">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i style="height: 32px; width: 32px" class="text-primary" data-feather="dollar-sign"></i>
                        </div>
                        <p class="text-muted small mb-2">Allowance Name</p>
                        <h3 class="fw-semibold mb-2">{{ $plan->name }}</h3>
                        <span class="badge bg-light text-dark fw-semibold">{{ $plan->short_name ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>

            <!-- Amount Card -->
            <div class="col-md-4">
                <div class="card border h-100">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i style="height: 32px; width: 32px" class="text-success" data-feather="credit-card"></i>
                        </div>
                        <p class="text-muted small mb-2">Allowance Amount</p>
                        <h2 class="fw-semibold text-success mb-0">
                            {{ \App\HelperClass::getGeneralSetting()->currency ?? 'Tk' }}
                            {{ number_format($plan->amount, 2) }}</h2>
                        <small class="text-muted">Per Month</small>
                    </div>
                </div>
            </div>

            <!-- Status Card -->
            <div class="col-md-4">
                <div class="card border h-100">
                    <div class="card-body text-center">
                        <div class="mb-3">
                            @if ($plan->status == 'active')
                                <i style="height: 32px; width: 32px" class="text-success" data-feather="check-circle"></i>
                            @else
                                <i style="height: 32px; width: 32px" class="text-danger" data-feather="x-circle"></i>
                            @endif
                        </div>
                        <p class="text-muted small mb-2">Status</p>
                        <div class="mb-2">
                            @if ($plan->status == 'active')
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

        <!-- Description Details -->
        <div class="card border mb-4">
            <div class="card-header bg-light border-bottom">
                <h6 class="mb-0 fw-semibold">
                    <i class="mdi mdi-information-outline text-info me-2"></i>Description
                </h6>
            </div>
            <div class="card-body">
                <p class="text-muted mb-0">
                    {{ $plan->description ?? 'No description provided.' }}
                </p>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="card border">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <a href="{{ route('plans.allowance_plans.index') }}" class="btn btn-secondary">
                        <i class="mdi mdi-arrow-left me-1"></i> Back to List
                    </a>
                    <div class="d-flex gap-2">
                        <a href="{{ route('plans.allowance_plans.edit', $plan->id) }}" class="btn btn-warning">
                            <i class="mdi mdi-pencil me-1"></i> Edit
                        </a>
                        <form action="{{ route('plans.allowance_plans.delete', $plan->id) }}" method="POST"
                            class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger confirmDelete">
                                <i class="mdi mdi-delete me-1"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
