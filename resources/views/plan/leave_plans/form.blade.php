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

                    <form action="{{ isset($plan) ? route('plan.leave_plans.update', $plan->id) : route('plan.leave_plans.store') }}" method="POST" id="leavePlanForm">
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
                                    <div class="col-md-6 mb-3">
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
                                    <div class="col-md-6 mb-3">
                                        <!--
                                            Leave Type Field with Interactive Autocomplete
                                            - Displays suggestions dropdown as user types
                                            - Allows custom leave types
                                            - Click to select from suggestions
                                        -->
                                        <label for="leave_type" class="form-label fw-semibold">Leave Type <span class="text-danger">*</span></label>

                                        <!-- Input field with dropdown wrapper -->
                                        <div class="position-relative">
                                            <input
                                                type="text"
                                                class="form-control"
                                                id="leave_type"
                                                name="leave_type"
                                                placeholder="Type or select leave type"
                                                value="{{ isset($plan) ? $plan->leave_type : old('leave_type') }}"
                                                required
                                                autocomplete="off">

                                            <!-- Suggestions dropdown container -->
                                            <div class="suggestions-list list-group position-absolute w-100"
                                                 id="leaveTypeSuggestionsList"
                                                 style="z-index: 1000; display: none; max-height: 200px; overflow-y: auto; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                                            </div>
                                        </div>

                                        <small class="text-muted">Select from suggestions or type a custom leave type</small>
                                        @error('leave_type')
                                        <span class="text-danger d-block">{{ $message }}</span>
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
                                        <select class="form-select" id="off_day_include" name="off_day_include">
                                            <option value="1" {{ (isset($plan) && $plan->off_day_include == 1) || old('off_day_include') == '1' ? 'selected' : '' }}>Yes</option>
                                            <option value="0" {{ (isset($plan) && $plan->off_day_include == 0) || old('off_day_include') == '0' || !isset($plan) ? 'selected' : '' }}>No</option>
                                        </select>
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
    <script>
        // ==========================================
        // 1. Define predefined leave type suggestions
        // ==========================================
        const leaveTypeSuggestions = [
            'Casual Leave',
            'Sick Leave',
            'Maternity Leave',
            'Paternity Leave',
            'Marriage Leave',
            'Compensatory Off',
            'Annual Leave',
            'Emergency Leave',
            'Bereavement Leave'
        ];

        // ==========================================
        // 2. Get DOM elements
        // ==========================================
        const leaveTypeInput = document.getElementById('leave_type');
        const suggestionsList = document.getElementById('leaveTypeSuggestionsList');

        // ==========================================
        // 3. Function: Show suggestions dropdown
        // ==========================================
        /**
         * Displays filtered leave type suggestions in a dropdown
         * @param {Array} suggestions - Array of leave types to display
         */
        function showSuggestions(suggestions) {
            // Clear any existing suggestions
            suggestionsList.innerHTML = '';

            // Create a clickable list item for each suggestion
            suggestions.forEach(suggestion => {
                const item = document.createElement('a');
                item.href = '#';
                item.className = 'list-group-item list-group-item-action';
                item.innerHTML = `<i class="mdi mdi-calendar-check text-muted me-2"></i>${suggestion}`;

                // When user clicks a suggestion, fill the input and hide dropdown
                item.addEventListener('click', (e) => {
                    e.preventDefault();
                    leaveTypeInput.value = suggestion;
                    hideSuggestions();
                });

                suggestionsList.appendChild(item);
            });

            // Show the dropdown
            suggestionsList.style.display = 'block';
        }

        // ==========================================
        // 4. Function: Hide suggestions dropdown
        // ==========================================
        function hideSuggestions() {
            suggestionsList.style.display = 'none';
        }

        // ==========================================
        // 5. Event: Filter suggestions as user types
        // ==========================================
        leaveTypeInput.addEventListener('input', (e) => {
            const value = e.target.value.trim();

            // Only show suggestions if user has typed something
            if (value.length > 0) {
                // Filter suggestions based on input (case-insensitive)
                const filtered = leaveTypeSuggestions.filter(type =>
                    type.toLowerCase().includes(value.toLowerCase())
                );

                // Display filtered results or hide if no matches
                if (filtered.length > 0) {
                    showSuggestions(filtered);
                } else {
                    hideSuggestions();
                }
            } else {
                hideSuggestions();
            }
        });

        // ==========================================
        // 6. Event: Hide dropdown when clicking outside
        // ==========================================
        document.addEventListener('click', (e) => {
            // Check if click is outside both input and suggestions list
            if (!leaveTypeInput.contains(e.target) && !suggestionsList.contains(e.target)) {
                hideSuggestions();
            }
        });

        // ==========================================
        // 7. Event: Show all suggestions on focus (if empty)
        // ==========================================
        leaveTypeInput.addEventListener('focus', (e) => {
            // If field is empty, show all available suggestions
            if (e.target.value.trim() === '') {
                showSuggestions(leaveTypeSuggestions);
            }
        });

        // ==========================================
        // 8. Event: Axios Form Submission
        // ==========================================
        const form = document.getElementById('leavePlanForm');
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const submitBtn = form.querySelector('[type="submit"]');
            submitBtn.disabled = true;

            // Clear previous errors
            form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            form.querySelectorAll('.invalid-feedback').forEach(el => el.remove());

            const formData = new FormData(form);

            // Handle PUT request spoofing via Axios correctly
            const isUpdate = "{{ isset($plan) ? 'true' : 'false' }}" === 'true';
            const url = form.getAttribute('action');

            axios({
                method: 'post',
                url: url,
                data: formData
            })
            .then(response => {
                if (response.data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.data.message,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = response.data.redirect;
                    });
                }
            })
            .catch(error => {
                submitBtn.disabled = false;
                if (error.response && error.response.status === 422) {
                    const errors = error.response.data.errors;
                    Object.keys(errors).forEach(key => {
                        const input = form.querySelector(`[name="${key}"]`);
                        if (input) {
                            input.classList.add('is-invalid');
                            const feedback = document.createElement('div');
                            feedback.className = 'invalid-feedback';
                            feedback.innerText = errors[key][0];
                            input.after(feedback);
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: error.response?.data?.message || 'Something went wrong. Please try again later.'
                    });
                }
            });
        });
    </script>
@endsection

