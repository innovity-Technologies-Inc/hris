@extends('structure.master')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ isset($plan) ? 'Edit' : 'Add' }} Allowance Plan</h5>
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
                        action="{{ isset($plan) ? route('plan.allowance_plans.update', $plan->id) : route('plan.allowance_plans.store') }}"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        @if (isset($plan))
                            @method('PUT')
                        @endif

                        <!-- Basic Information -->
                        <div class="card border mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0 fw-semibold">
                                    <i class="mdi mdi-file-document-outline text-primary me-2"></i>Basic Information
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label fw-semibold">Allowance Name <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            placeholder="E.g., House Rent, Transport, Medical"
                                            value="{{ isset($plan) ? $plan->name : old('name') }}" required>

                                        @error('name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="short_name" class="form-label fw-semibold">Short Name</label>
                                        <input type="text" class="form-control" id="short_name" name="short_name"
                                            placeholder="E.g., HRA, TA, MA"
                                            value="{{ isset($plan) ? $plan->short_name : old('short_name') }}">
                                        @error('short_name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="amount" class="form-label fw-semibold">Amount
                                            ({{ \App\HelperClass::getGeneralSetting()->currency ?? 'Tk' }}) <span
                                                class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="amount" name="amount"
                                            placeholder="Enter amount" step="0.01" min="0"
                                            value="{{ isset($plan) ? $plan->amount : old('amount') }}" required>
                                        @error('amount')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="status" class="form-label fw-semibold">Status <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select" id="status" name="status" required>
                                            <option value="">Select Status</option>
                                            <option value="active"
                                                {{ (isset($plan) && $plan->status == 'active') || old('status') == 'active' ? 'selected' : '' }}>
                                                Active</option>
                                            <option value="inactive"
                                                {{ (isset($plan) && $plan->status == 'inactive') || old('status') == 'inactive' ? 'selected' : '' }}>
                                                Inactive</option>
                                        </select>
                                        @error('status')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label for="description" class="form-label fw-semibold">Description</label>
                                        <textarea class="form-control" id="description" name="description" rows="4"
                                            placeholder="Enter allowance description">{{ isset($plan) ? $plan->description : old('description') }}</textarea>
                                        @error('description')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('plan.allowance_plans.index') }}" class="btn btn-secondary">
                                <i class="mdi mdi-arrow-left me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="mdi mdi-check me-1"></i> {{ isset($plan) ? 'Update' : 'Create' }} Allowance Plan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

