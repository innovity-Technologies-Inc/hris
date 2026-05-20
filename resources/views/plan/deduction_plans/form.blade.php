@extends('structure.master')

@section('content')
    @php
        $isEdit = isset($plan) && $plan !== null;
    @endphp

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ $isEdit ? 'Edit' : 'Add' }} Deduction Plan</h5>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <h6 class="mb-2">Please fix the following errors:</h6>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form
                        action="{{ $isEdit ? route('plan.deduction_plans.update') : route('plan.deduction_plans.store') }}"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        @if ($isEdit)
                            @method('PUT')
                        @endif

                        <!-- Deduction Configuration -->
                        <div class="card border mb-4">
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
                                        <label for="late_deduction_days" class="form-label fw-semibold">Late Deduction Days
                                            <span class="text-danger">*</span></label>
                                        <input type="number"
                                            class="form-control @error('late_deduction_days') is-invalid @enderror"
                                            id="late_deduction_days" name="late_deduction_days" placeholder="E.g., 1"
                                            min="0"
                                            value="{{ old('late_deduction_days', $plan->late_deduction_days ?? '') }}"
                                            required>
                                        <small class="text-muted">Number of days to deduct for late arrival</small>
                                        @error('late_deduction_days')
                                            <span class="text-danger d-block">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="late_salary_deduction_rate" class="form-label fw-semibold">Late Salary
                                            Deduction Rate <span class="text-danger">*</span></label>

                                        <div class="input-group w-50">
                                            <input type="number"
                                                class="form-control @error('late_salary_deduction_rate') is-invalid @enderror"
                                                id="late_salary_deduction_rate" name="late_salary_deduction_rate"
                                                step="0.01" min="0" max="100"
                                                value="{{ old('late_salary_deduction_rate', $plan->late_salary_deduction_rate ?? '') }}"
                                                required>
                                            <span class="input-group-text">Day(s) Salary </span>
                                        </div>

                                        @error('late_salary_deduction_rate')
                                            <span class="text-danger d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Early Out Deduction -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <h6 class="fw-bold text-warning mb-3">Early Out Deduction</h6>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="early_out_deduction_days" class="form-label fw-semibold">Early Out
                                            Deduction Days <span class="text-danger">*</span></label>
                                        <input type="number"
                                            class="form-control @error('early_out_deduction_days') is-invalid @enderror"
                                            id="early_out_deduction_days" name="early_out_deduction_days"
                                            placeholder="E.g., 1" min="0"
                                            value="{{ old('early_out_deduction_days', $plan->early_out_deduction_days ?? '') }}"
                                            required>
                                        <small class="text-muted">Number of days to deduct for leaving early</small>
                                        @error('early_out_deduction_days')
                                            <span class="text-danger d-block">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="early_out_salary_deduction_rate" class="form-label fw-semibold">Early
                                            Out Salary Deduction Rate <span class="text-danger">*</span></label>

                                        <div class="input-group w-50">
                                            <input type="number"
                                                class="form-control @error('early_out_salary_deduction_rate') is-invalid @enderror"
                                                id="early_out_salary_deduction_rate" name="early_out_salary_deduction_rate"
                                                step="0.01" min="0" max="100"
                                                value="{{ old('early_out_salary_deduction_rate', $plan->early_out_salary_deduction_rate ?? '') }}"
                                                required>
                                            <span class="input-group-text">Day(s) Salary </span>
                                        </div>

                                        @error('early_out_salary_deduction_rate')
                                            <span class="text-danger d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Excessive Late Deduction -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <h6 class="fw-bold text-danger mb-3">Excessive Late Deduction</h6>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="excessive_late_deduction_days" class="form-label fw-semibold">Excessive
                                            Late Deduction Days <span class="text-danger">*</span></label>
                                        <input type="number"
                                            class="form-control @error('excessive_late_deduction_days') is-invalid @enderror"
                                            id="excessive_late_deduction_days" name="excessive_late_deduction_days"
                                            placeholder="E.g., 2" min="0"
                                            value="{{ old('excessive_late_deduction_days', $plan->excessive_late_deduction_days ?? '') }}"
                                            required>
                                        <small class="text-muted">Number of days to deduct for excessive lateness</small>
                                        @error('excessive_late_deduction_days')
                                            <span class="text-danger d-block">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="excessive_late_salary_deduction_rate"
                                            class="form-label fw-semibold">Excessive Late Salary Deduction Rate <span
                                                class="text-danger">*</span></label>

                                        <div class="input-group w-50">
                                            <input type="number"
                                                class="form-control @error('excessive_late_salary_deduction_rate') is-invalid @enderror"
                                                id="excessive_late_salary_deduction_rate"
                                                name="excessive_late_salary_deduction_rate" step="0.01" min="0"
                                                max="100"
                                                value="{{ old('excessive_late_salary_deduction_rate', $plan->excessive_late_salary_deduction_rate ?? '') }}"
                                                required>
                                            <span class="input-group-text">Day(s) Salary </span>
                                        </div>

                                        @error('excessive_late_salary_deduction_rate')
                                            <span class="text-danger d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Absent Deduction -->
                                <div class="row mb-4">
                                    <div class="col-12">
                                        <h6 class="fw-bold text-secondary mb-3">Absent Deduction</h6>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="absent_deduction_days" class="form-label fw-semibold">Absent
                                            Deduction Days <span class="text-danger">*</span></label>
                                        <input type="number"
                                            class="form-control @error('absent_deduction_days') is-invalid @enderror"
                                            id="absent_deduction_days" name="absent_deduction_days" placeholder="E.g., 1"
                                            min="0"
                                            value="{{ old('absent_deduction_days', $plan->absent_deduction_days ?? '') }}"
                                            required>
                                        <small class="text-muted">Number of days to deduct for absence</small>
                                        @error('absent_deduction_days')
                                            <span class="text-danger d-block">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="absent_salary_deduction_rate" class="form-label fw-semibold">Absent
                                            Salary Deduction Rate <span class="text-danger">*</span></label>

                                        <div class="input-group w-50">
                                            <input type="number"
                                                class="form-control @error('absent_salary_deduction_rate') is-invalid @enderror"
                                                id="absent_salary_deduction_rate" name="absent_salary_deduction_rate"
                                                step="0.01" min="0" max="100"
                                                value="{{ old('absent_salary_deduction_rate', $plan->absent_salary_deduction_rate ?? '') }}"
                                                required>
                                            <span class="input-group-text">Day(s) Salary </span>
                                        </div>

                                        @error('absent_salary_deduction_rate')
                                            <span class="text-danger d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Calculation Type -->
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="calculation_type" class="form-label fw-semibold">Calculation Type
                                            <span class="text-danger">*</span></label>
                                        <select class="form-select @error('calculation_type') is-invalid @enderror"
                                            id="calculation_type" name="calculation_type" required>
                                            <option value="">Select Calculation Type</option>
                                            <option value="gross_salary"
                                                {{ old('calculation_type', $plan->calculation_type ?? 'gross_salary') == 'gross_salary' ? 'selected' : '' }}>
                                                Gross Salary</option>
                                            <option value="basic_salary"
                                                {{ old('calculation_type', $plan->calculation_type ?? '') == 'basic_salary' ? 'selected' : '' }}>
                                                Basic Salary</option>
                                        </select>
                                        <small class="text-muted">Base salary type for deduction calculations</small>
                                        @error('calculation_type')
                                            <span class="text-danger d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('plan.deduction_plans.index') }}" class="btn btn-secondary">
                                <i class="mdi mdi-arrow-left me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="mdi mdi-check me-1"></i> {{ $isEdit ? 'Update' : 'Create' }} Deduction Plan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

