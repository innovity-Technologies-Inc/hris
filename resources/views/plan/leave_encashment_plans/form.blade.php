@extends('structure.master')

@section('content')
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0 fw-bold" style="color: #974063;">
                    <i class="mdi mdi-cash-multiple me-2"></i>{{ isset($plan) ? 'Edit Leave Encashment Plan' : 'Create Leave Encashment Plan' }}
                </h5>
                <a href="{{ route('plan.leave_encashment_plans.index') }}" class="btn btn-secondary btn-sm shadow-sm">
                    <i class="mdi mdi-arrow-left me-1"></i> Back to Plan
                </a>
            </div>
            
            <div class="card-body">
                <form action="{{ isset($plan) ? route('plan.leave_encashment_plans.update') : route('plan.leave_encashment_plans.store') }}" method="POST">
                    @csrf
                    @if(isset($plan))
                        @method('PUT')
                    @endif

                    <div class="row">
                        <!-- Left Column -->
                        <div class="col-md-6 mb-4">
                            <h6 class="fw-bold text-primary mb-3">General Information</h6>
                            
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                       name="title" value="{{ old('title', $plan->title ?? '') }}" 
                                       placeholder="e.g. Standard Encashment Policy" required>
                                @error('title') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          name="description" rows="3" 
                                          placeholder="Enter description">{{ old('description', $plan->description ?? '') }}</textarea>
                                @error('description') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" name="status" required>
                                    <option value="active" {{ old('status', $plan->status ?? '') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status', $plan->status ?? '') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="col-md-6 mb-4">
                            <h6 class="fw-bold text-primary mb-3">Configuration Rules</h6>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Encashment Basis <span class="text-danger">*</span></label>
                                <select class="form-select @error('encashment_basis') is-invalid @enderror" name="encashment_basis" required>
                                    <option value="basic" {{ old('encashment_basis', $plan->encashment_basis ?? '') == 'basic' ? 'selected' : '' }}>Basic Salary</option>
                                    <option value="gross" {{ old('encashment_basis', $plan->encashment_basis ?? '') == 'gross' ? 'selected' : '' }}>Gross Salary</option>
                                </select>
                                <small class="text-muted">Salary component used for calculation</small>
                                @error('encashment_basis') <span class="text-danger small d-block">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Min Balance to Maintain <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" class="form-control @error('min_balance_to_maintain') is-invalid @enderror" 
                                           name="min_balance_to_maintain" value="{{ old('min_balance_to_maintain', $plan->min_balance_to_maintain ?? '0') }}" required>
                                    <span class="input-group-text">Days</span>
                                </div>
                                <small class="text-muted">Minimum leave balance that must remain</small>
                                @error('min_balance_to_maintain') <span class="text-danger small d-block">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Encashment Rate <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="number" step="0.01" class="form-control @error('encashment_rate') is-invalid @enderror" 
                                           name="encashment_rate" value="{{ old('encashment_rate', $plan->encashment_rate ?? '1.00') }}" required>
                                    <span class="input-group-text">x Day Salary</span>
                                </div>
                                <small class="text-muted">1.00 = Full Day, 0.50 = Half Day</small>
                                @error('encashment_rate') <span class="text-danger small d-block">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Max Encashable Days (Yearly)</label>
                                <div class="input-group">
                                    <input type="number" class="form-control @error('max_encashable_days_per_year') is-invalid @enderror" 
                                           name="max_encashable_days_per_year" value="{{ old('max_encashable_days_per_year', $plan->max_encashable_days_per_year ?? '') }}" placeholder="Optional">
                                    <span class="input-group-text">Days</span>
                                </div>
                                <small class="text-muted">Maximum days that can be encashed in a year</small>
                                @error('max_encashable_days_per_year') <span class="text-danger small d-block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <hr>
                    <div class="d-flex justify-content-end mt-3">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="mdi mdi-content-save me-1"></i> {{ isset($plan) ? 'Update Plan' : 'Save Plan' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection