@extends('structure.master')
@section('content')

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Leave Plan Form</h5>
                </div>

                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Basic Information Section -->
                        <div class="row">
                            <div class="col-12">
                                <div class="card border mb-4">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0 fw-semibold">Basic Information</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="leave_name" class="form-label fw-semibold">
                                                    Leave Name <span class="text-danger">*</span>
                                                </label>
                                                <input type="text" class="form-control" id="leave_name" name="leave_name"
                                                    placeholder="E.g., CASUAL LEAVE, SICK LEAVE" required>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="short_name" class="form-label fw-semibold">
                                                    Short Name <span class="text-danger">*</span>
                                                </label>
                                                <input type="text" class="form-control" id="short_name" name="short_name"
                                                    placeholder="E.g., CL, SL" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Classification Section -->
                        <div class="row">
                            <div class="col-12">
                                <div class="card border mb-4">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0 fw-semibold">Classification</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label for="applicable_gender" class="form-label fw-semibold">
                                                    Applicable Gender <span class="text-danger">*</span>
                                                </label>
                                                <select class="form-select" id="applicable_gender" name="applicable_gender" required>
                                                    <option value="" disabled selected>Select Gender</option>
                                                    <option value="Both">Both</option>
                                                    <option value="Male">Male</option>
                                                    <option value="Female">Female</option>
                                                </select>
                                            </div>

                                            <div class="col-md-4 mb-3">
                                                <label for="day_type" class="form-label fw-semibold">
                                                    Day Type <span class="text-danger">*</span>
                                                </label>
                                                <select class="form-select" id="day_type" name="day_type" required>
                                                    <option value="" disabled selected>Select Day Type</option>
                                                    <option value="Calculative">Calculative</option>
                                                    <option value="Fixed">Fixed</option>
                                                </select>
                                            </div>

                                            <div class="col-md-4 mb-3">
                                                <label for="leave_type" class="form-label fw-semibold">
                                                    Leave Type <span class="text-danger">*</span>
                                                </label>
                                                <select class="form-select" id="leave_type" name="leave_type" required>
                                                    <option value="" disabled selected>Select Leave Type</option>
                                                    <option value="Casual Leave">Casual Leave</option>
                                                    <option value="Sick Leave">Sick Leave</option>
                                                    <option value="Maternal Leave">Maternal Leave</option>
                                                    <option value="Paternal Leave">Paternal Leave</option>
                                                    <option value="Earned Leave">Earned Leave</option>
                                                    <option value="Comp Off">Comp Off</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Leave Configuration Section -->
                        <div class="row">
                            <div class="col-12">
                                <div class="card border mb-4">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0 fw-semibold">Leave Configuration</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <label for="leave_limit" class="form-label fw-semibold">Leave Limit</label>
                                                <input type="number" class="form-control" id="leave_limit" name="leave_limit"
                                                    placeholder="E.g., 10, 60">
                                            </div>

                                            <div class="col-md-4 mb-3">
                                                <label for="max_no_of_days" class="form-label fw-semibold">Max No Of Days</label>
                                                <input type="number" class="form-control" id="max_no_of_days" name="max_no_of_days"
                                                    placeholder="E.g., 5, 10">
                                            </div>

                                            <div class="col-md-4 mb-3">
                                                <label for="off_day_include" class="form-label fw-semibold">Off Day Include</label>
                                                <select class="form-select" id="off_day_include" name="off_day_include">
                                                    <option value="" disabled selected>Select Option</option>
                                                    <option value="Excluding">Excluding</option>
                                                    <option value="In Between">In Between</option>
                                                    <option value="Succeeding">Succeeding</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Status Section -->
                        <div class="row">
                            <div class="col-12">
                                <div class="card border mb-4">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0 fw-semibold">Status & Options</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4 mb-3">
                                                <div class="form-check form-switch">
                                                    <input type="hidden" name="apply_limit" value="inactive">
                                                    <input class="form-check-input" type="checkbox" name="apply_limit"
                                                        id="apply_limit" value="active">
                                                    <label class="form-check-label fw-semibold" for="apply_limit">
                                                        Apply Limit
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="col-md-4 mb-3">
                                                <div class="form-check form-switch">
                                                    <input type="hidden" name="allow_fractional_leave" value="inactive">
                                                    <input class="form-check-input" type="checkbox" name="allow_fractional_leave"
                                                        id="allow_fractional_leave" value="active">
                                                    <label class="form-check-label fw-semibold" for="allow_fractional_leave">
                                                        Allow Fractional Leave
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="col-md-4 mb-3">
                                                <div class="form-check form-switch">
                                                    <input type="hidden" name="active_ind" value="inactive">
                                                    <input class="form-check-input" type="checkbox" name="active_ind"
                                                        id="active_ind" value="active" checked>
                                                    <label class="form-check-label fw-semibold" for="active_ind">
                                                        Active
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="row">
                            <div class="col-12">
                                <div class="card border">
                                    <div class="card-body">
                                        <div class="d-flex gap-2 justify-content-end">
                                            <button type="reset" class="btn btn-secondary">
                                                <i style="height: 12px; width: 12px" data-feather="refresh-cw"></i> Reset
                                            </button>
                                            <button type="submit" class="btn btn-primary">
                                                <i style="height: 12px; width: 12px" data-feather="save"></i> Submit
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
