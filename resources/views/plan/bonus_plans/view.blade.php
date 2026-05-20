@extends('structure.master')
@section('content')
    <div class="container-fluid mt-4">
        <!-- Basic Bonus Information -->
        <div class="card border mb-4">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-semibold">
                    <i class="mdi mdi-gift-outline text-primary me-2"></i>Basic Bonus Information
                </h5>
                <span class="badge bg-{{ $plan->status == 'active' ? 'success' : 'secondary' }}">
                    {{ ucfirst($plan->status) }}
                </span>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Bonus Plan Name -->
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Bonus Plan Name</label>
                        <p class="fw-semibold mb-0">{{ $plan->name }}</p>
                    </div>

                    <!-- Bonus Type -->
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Bonus Type</label>
                        <p class="fw-semibold mb-0">
                            <span class="badge bg-info">{{ ucwords(str_replace('_', ' ', $plan->bonus_type)) }}</span>
                        </p>
                    </div>
                </div>

                <!-- Description (if exists) -->
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

        <!-- Bonus Calculation Configuration -->
        <div class="card border mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0 fw-semibold">
                    <i class="mdi mdi-cash-multiple text-success me-2"></i>Bonus Calculation Configuration
                </h5>
            </div>
            <div class="card-body">
                <!-- Configuration Type Display -->
                <div class="row mb-3">
                    <div class="col-md-12">
                        <label class="text-muted small">Configuration Type</label>
                        <p class="fw-semibold mb-0">
                            <span class="badge bg-primary">
                                {{ $plan->bonus_config_type == 'Salary Based' ? 'Based on Salary' : 'Custom Amount' }}
                            </span>
                        </p>
                    </div>
                </div>

                @if ($plan->bonus_config_type == 'Salary Based')
                    <!-- Salary Based Configuration -->
                    <div class="border rounded p-3 bg-light">
                        <h6 class="fw-semibold mb-3 text-primary">
                            <i class="mdi mdi-calculator me-1"></i>Salary-Based Calculation
                        </h6>
                        <div class="row">
                            <!-- Calculation Method -->
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">Calculation Method</label>
                                <p class="fw-semibold mb-0">
                                    <span class="badge bg-info">
                                        {{ $plan->salary_rate_type == 'Basic Rate' ? 'Basic Salary (100%)' : 'Percentage of Salary' }}
                                    </span>
                                </p>
                            </div>

                            <!-- Multiplier (only if percentage method) -->
                            @if ($plan->salary_rate_type == 'Multiplier')
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small">Bonus Percentage</label>
                                    <p class="fw-semibold mb-0">
                                        <span class="badge bg-success fs-6">
                                            {{ number_format($plan->multiplier, 2) }}× Base Salary
                                        </span>
                                    </p>
                                </div>
                            @endif
                        </div>

                        <!-- Calculation Example -->
                        @if ($plan->salary_rate_type == 'Multiplier')
                            <div class="alert alert-info mb-0 mt-2">
                                <i class="mdi mdi-information-outline me-2"></i>
                                <strong>Example:</strong>
                                If base salary is {{ \App\HelperClass::getGeneralSetting()->currency ?? '৳' }}10,000,
                                bonus will be
                                <strong>{{ \App\HelperClass::getGeneralSetting()->currency ?? '৳' }}{{ number_format(10000 * $plan->multiplier, 2) }}</strong>
                            </div>
                        @elseif ($plan->salary_rate_type == 'Basic Rate')
                            <div class="alert alert-info mb-0 mt-2">
                                <i class="mdi mdi-information-outline me-2"></i>
                                Bonus amount equals 100% of employee's basic salary
                            </div>
                        @endif
                    </div>
                @else
                    <!-- Custom Fixed Amount Configuration -->
                    <div class="border rounded p-3 bg-light">
                        <h6 class="fw-semibold mb-3 text-success">
                            <i class="mdi mdi-cash me-1"></i>Fixed Bonus Amount
                        </h6>
                        <div class="row">
                            <!-- Fixed Amount Display -->
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small">Bonus Amount</label>
                                <p class="fw-semibold mb-0">
                                    <span class="badge bg-success fs-6">
                                        {{ \App\HelperClass::getGeneralSetting()->currency ?? '৳' }}{{ number_format($plan->custom_rate, 2) }}
                                    </span>
                                </p>
                            </div>
                        </div>

                        <!-- Fixed Amount Info -->
                        <div class="alert alert-info mb-0 mt-2">
                            <i class="mdi mdi-information-outline me-2"></i>
                            This is a fixed amount paid to all eligible employees, regardless of their salary
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Page Actions -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <!-- Back Button -->
            <div>
                <a href="{{ route('plan.bonus_plans.index') }}" class="btn btn-secondary">
                    <i class="mdi mdi-arrow-left me-1"></i>Back to List
                </a>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex gap-2">
                <!-- Edit Button -->
                <a href="{{ route('plan.bonus_plans.edit', $plan->id) }}" class="btn btn-primary">
                    <i class="mdi mdi-pencil me-1"></i>Edit
                </a>

                <!-- Delete Button -->
                <form action="{{ route('plan.bonus_plans.delete', $plan->id) }}" method="POST"
                      onsubmit="return confirm('Are you sure you want to delete this bonus plan? This action cannot be undone.');"
                      class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="mdi mdi-trash-can me-1"></i>Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

