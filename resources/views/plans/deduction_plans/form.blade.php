@extends('structure.master')

@section('content')
    @php
        // Dummy object for form - replace with actual $plan when using controller
        $plan =
            $plan ??
            (object) [
                'id' => null,
                'late_deduction' => old('late_deduction', ''),
                'early_out_deduction' => old('early_out_deduction', ''),
                'excessive_late_deduction' => old('excessive_late_deduction', ''),
                'status' => old('status', 'active'),
            ];
        $isEdit = isset($plan->id) && $plan->id !== null;
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

                    <form action="#" method="POST" enctype="multipart/form-data">
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
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="late_deduction" class="form-label fw-semibold">Late Deduction
                                            ({{ \App\HelperClass::getGeneralSetting()->currency ?? 'Tk' }}) <span
                                                class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="late_deduction" name="late_deduction"
                                            placeholder="E.g., 50.00" step="0.01" min="0"
                                            value="{{ $plan->late_deduction }}" required>
                                        <small class="text-muted">Amount deducted for late arrival</small>
                                        @error('late_deduction')
                                            <span class="text-danger d-block">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="early_out_deduction" class="form-label fw-semibold">Early Out Deduction
                                            ({{ \App\HelperClass::getGeneralSetting()->currency ?? 'Tk' }}) <span
                                                class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="early_out_deduction"
                                            name="early_out_deduction" placeholder="E.g., 40.00" step="0.01"
                                            min="0" value="{{ $plan->early_out_deduction }}" required>
                                        <small class="text-muted">Amount deducted for leaving early</small>
                                        @error('early_out_deduction')
                                            <span class="text-danger d-block">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="excessive_late_deduction" class="form-label fw-semibold">Excessive Late
                                            Deduction
                                            ({{ \App\HelperClass::getGeneralSetting()->currency ?? 'Tk' }}) <span
                                                class="text-danger">*</span></label>
                                        <input type="number" class="form-control" id="excessive_late_deduction"
                                            name="excessive_late_deduction" placeholder="E.g., 100.00" step="0.01"
                                            min="0" value="{{ $plan->excessive_late_deduction }}" required>
                                        <small class="text-muted">Amount deducted for excessive lateness (multiple late
                                            arrivals)</small>
                                        @error('excessive_late_deduction')
                                            <span class="text-danger d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="status" class="form-label fw-semibold">Status <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select" id="status" name="status" required>
                                            <option value="">Select Status</option>
                                            <option value="active" {{ $plan->status == 'active' ? 'selected' : '' }}>
                                                Active</option>
                                            <option value="inactive" {{ $plan->status == 'inactive' ? 'selected' : '' }}>
                                                Inactive</option>
                                        </select>
                                        @error('status')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-end gap-2">
                            <a href="#" class="btn btn-secondary">
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
