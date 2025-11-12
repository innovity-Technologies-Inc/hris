@extends('structure.master')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">{{ isset($plan) ? 'Edit' : 'Add' }} Leave Plan</h5>
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

                    <form action="{{ isset($plan) ? route('plans.leave_plans.update', $plan->id) : route('plans.leave_plans.store') }}" method="POST" enctype="multipart/form-data">
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
                                        <label for="name" class="form-label fw-semibold">Leave Plan Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="name" name="name" placeholder="E.g., Casual Leave, Sick Leave" value="{{ isset($plan) ? $plan->name : old('name') }}" required>
                                        @error('name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="short_name" class="form-label fw-semibold">Short Name</label>
                                        <input type="text" class="form-control" id="short_name" name="short_name" placeholder="E.g., CL, SL" value="{{ isset($plan) ? $plan->short_name : old('short_name') }}">
                                        @error('short_name')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Classification -->
                        <div class="card border mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0 fw-semibold">
                                    <i class="mdi mdi-shape-outline text-success me-2"></i>Classification
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="applicable_gender" class="form-label fw-semibold">Applicable Gender <span class="text-danger">*</span></label>
                                        <select class="form-select" id="applicable_gender" name="applicable_gender" required>
                                            <option value="">Select Gender</option>
                                            <option value="Both" {{ (isset($plan) && $plan->applicable_gender == 'Both') || old('applicable_gender') == 'Both' ? 'selected' : '' }}>Both</option>
                                            <option value="Male" {{ (isset($plan) && $plan->applicable_gender == 'Male') || old('applicable_gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                            <option value="Female" {{ (isset($plan) && $plan->applicable_gender == 'Female') || old('applicable_gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                        </select>
                                        @error('applicable_gender')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="day_type" class="form-label fw-semibold">Day Type <span class="text-danger">*</span></label>
                                        <select class="form-select" id="day_type" name="day_type" required>
                                            <option value="">Select Day Type</option>
                                            <option value="Calculative" {{ (isset($plan) && $plan->day_type == 'Calculative') || old('day_type') == 'Calculative' ? 'selected' : '' }}>Calculative</option>
                                            <option value="Fixed" {{ (isset($plan) && $plan->day_type == 'Fixed') || old('day_type') == 'Fixed' ? 'selected' : '' }}>Fixed</option>
                                        </select>
                                        @error('day_type')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="leave_type" class="form-label fw-semibold">Leave Type <span class="text-danger">*</span></label>
                                        <select class="form-select" id="leave_type" name="leave_type" required>
                                            <option value="">Select Leave Type</option>
                                            <option value="Casual Leave" {{ (isset($plan) && $plan->leave_type == 'Casual Leave') || old('leave_type') == 'Casual Leave' ? 'selected' : '' }}>Casual Leave</option>
                                            <option value="Sick Leave" {{ (isset($plan) && $plan->leave_type == 'Sick Leave') || old('leave_type') == 'Sick Leave' ? 'selected' : '' }}>Sick Leave</option>
                                            <option value="Maternal Leave" {{ (isset($plan) && $plan->leave_type == 'Maternal Leave') || old('leave_type') == 'Maternal Leave' ? 'selected' : '' }}>Maternal Leave</option>
                                            <option value="Paternal Leave" {{ (isset($plan) && $plan->leave_type == 'Paternal Leave') || old('leave_type') == 'Paternal Leave' ? 'selected' : '' }}>Paternal Leave</option>
                                            <option value="Earned Leave" {{ (isset($plan) && $plan->leave_type == 'Earned Leave') || old('leave_type') == 'Earned Leave' ? 'selected' : '' }}>Earned Leave</option>
                                            <option value="Comp Off" {{ (isset($plan) && $plan->leave_type == 'Comp Off') || old('leave_type') == 'Comp Off' ? 'selected' : '' }}>Comp Off</option>
                                        </select>
                                        @error('leave_type')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Leave Configuration -->
                        <div class="card border mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0 fw-semibold">
                                    <i class="mdi mdi-cog-outline text-info me-2"></i>Leave Configuration
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="leave_limit" class="form-label fw-semibold">Leave Limit (Days/Year)</label>
                                        <input type="number" class="form-control" id="leave_limit" name="leave_limit" placeholder="E.g., 10, 15" value="{{ isset($plan) ? $plan->leave_limit : old('leave_limit') }}" min="0" step="0.5">
                                        <small class="text-muted">Total leave days allowed per year</small>
                                        @error('leave_limit')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="max_no_of_days" class="form-label fw-semibold">Max Days Per Application</label>
                                        <input type="number" class="form-control" id="max_no_of_days" name="max_no_of_days" placeholder="E.g., 3, 5" value="{{ isset($plan) ? $plan->max_no_of_days : old('max_no_of_days') }}" min="0" step="0.5">
                                        <small class="text-muted">Maximum consecutive days per request</small>
                                        @error('max_no_of_days')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="display_serial" class="form-label fw-semibold">Display Serial</label>
                                        <input type="number" class="form-control" id="display_serial" name="display_serial" placeholder="E.g., 1, 2, 3" value="{{ isset($plan) ? $plan->display_serial : old('display_serial') }}" min="0">
                                        <small class="text-muted">Display order in lists</small>
                                        @error('display_serial')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Leave Options -->
                        <div class="card border mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0 fw-semibold">
                                    <i class="mdi mdi-tune-vertical text-warning me-2"></i>Leave Options
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="apply_limit" class="form-label fw-semibold">Apply Limit</label>
                                        <input type="number" class="form-control" id="apply_limit" name="apply_limit" placeholder="E.g., 2, 3" value="{{ isset($plan) ? $plan->apply_limit : old('apply_limit') }}" min="0">
                                        <small class="text-muted">Maximum applications allowed</small>
                                        @error('apply_limit')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="allow_fractional_leave" class="form-label fw-semibold">Allow Fractional Leave (Half day, Quarter day)</label>
                                        <select class="form-select" id="allow_fractional_leave" name="allow_fractional_leave">
                                            <option value="inactive" {{ (isset($plan) && $plan->allow_fractional_leave == 'inactive') || old('allow_fractional_leave') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                            <option value="active" {{ (isset($plan) && $plan->allow_fractional_leave == 'active') || old('allow_fractional_leave') == 'active' ? 'selected' : '' }}>Active</option>
                                        </select>
                                        @error('allow_fractional_leave')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="off_day_include" class="form-label fw-semibold">Include Off Days in Leave Count</label>
                                        <input type="number" class="form-control" id="off_day_include" name="off_day_include" placeholder="E.g., 0, 1" value="{{ isset($plan) ? $plan->off_day_include : old('off_day_include') }}" min="0">
                                        <small class="text-muted">Set to 0 for no, 1 for yes, or other number for specific logic.</small>
                                        @error('off_day_include')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="card border mb-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0 fw-semibold">
                                    <i class="mdi mdi-toggle-switch text-primary me-2"></i>Plan Status
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="active_ind" class="form-label fw-semibold">Status</label>
                                        <select class="form-select" name="active_ind" id="active_ind">
                                            <option value="active" {{ (isset($plan) && $plan->active_ind == 'active') || old('active_ind', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                            <option value="inactive" {{ (isset($plan) && $plan->active_ind == 'inactive') || old('active_ind') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                        @error('active_ind')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="card border mb-4">
                            <div class="card-body">
                                <div class="d-flex justify-content-end gap-2">
                                    <button type="reset" class="btn btn-secondary">
                                        <i class="mdi mdi-refresh me-1"></i>Reset
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="mdi mdi-content-save me-1"></i>Submit Leave Plan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection