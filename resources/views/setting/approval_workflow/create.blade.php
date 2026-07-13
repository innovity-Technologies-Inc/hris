@extends('structure.master')

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Add Approval Workflow</h5>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form id="workflowForm">
                                @csrf

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="module_name" class="form-label">Module <span class="text-danger">*</span></label>
                                        <select name="module_name" id="module_name" class="form-select" required>
                                            <option value="" selected disabled>Select Module</option>
                                            @foreach($modules as $key => $label)
                                                <option value="{{ $key }}" {{ old('module_name') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="type" class="form-label">Workflow Type <span class="text-danger">*</span></label>
                                        <select name="type" id="type" class="form-select" required>
                                            <option value="sequential" {{ old('type') == 'sequential' ? 'selected' : '' }}>Sequential (Step-by-Step)</option>
                                            <option value="random" {{ old('type') == 'random' ? 'selected' : '' }}>Random (Parallel)</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-3" id="requiredApprovalsWrapper" style="display: none;">
                                    <div class="col-md-12">
                                        <label for="required_approvals" class="form-label">Required Approvals <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" name="required_approvals" id="required_approvals" value="{{ old('required_approvals', 1) }}" min="1">
                                            <span class="input-group-text bg-light text-muted" id="totalStepsDisplay">out of X total steps</span>
                                        </div>
                                        <small class="text-muted">How many approvers must approve this for the entire workflow to pass?</small>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label for="is_active" class="form-label">Status</label>
                                        <select class="form-select" name="is_active" id="is_active">
                                            <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Active</option>
                                            <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="card mb-4 border-dashed bg-light mt-4">
                                    <div class="card-header bg-transparent border-0 pb-0">
                                        <h6 class="card-title mb-0 fw-semibold text-primary">Creator/Requester Inclusion & Exclusion Scopes</h6>
                                        <small class="text-muted">Define who this workflow applies to, and who bypasses it (auto-approves).</small>
                                    </div>
                                    <div class="card-body">
                                        <!-- Inclusion Section -->
                                        <div class="border-bottom pb-3 mb-3">
                                            <span class="badge bg-success-subtle text-success mb-2 fw-semibold">1. Inclusion Scope (Needs Approval)</span>
                                            <div class="row">
                                                <div class="col-md-3 mb-3">
                                                    <label class="form-label fw-semibold">Target Inclusion Scope</label>
                                                    <select name="scope_type" id="scope_type" class="form-select">
                                                        <option value="all">Apply to All (Default)</option>
                                                        <option value="user_type">User Type</option>
                                                        <option value="role">User Role</option>
                                                        <option value="user_type_role">User Type + Role</option>
                                                        <option value="specific_user">Specific User</option>
                                                    </select>
                                                </div>

                                                <!-- User Type Dropdown -->
                                                <div class="col-md-3 mb-3 scope-field d-none" id="scope_user_type_div">
                                                    <label class="form-label fw-semibold">Included User Types <span class="text-muted">(Hold Ctrl to select multiple)</span></label>
                                                    <select name="requester_user_types[]" id="requester_user_types" class="form-select" multiple>
                                                        @foreach($userTypes as $type)
                                                            <option value="{{ $type->value }}">{{ $type->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <!-- Role Dropdown -->
                                                <div class="col-md-3 mb-3 scope-field d-none" id="scope_role_div">
                                                    <label class="form-label fw-semibold">Included User Roles <span class="text-muted">(Hold Ctrl to select multiple)</span></label>
                                                    <select name="requester_role_ids[]" id="requester_role_ids" class="form-select" multiple>
                                                        @foreach($roles as $role)
                                                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <!-- Specific User Dropdown -->
                                                <div class="col-md-3 mb-3 scope-field d-none" id="scope_user_div">
                                                    <label class="form-label fw-semibold">Included Specific Users <span class="text-muted">(Hold Ctrl to select multiple)</span></label>
                                                    <select name="requester_user_ids[]" id="requester_user_ids" class="form-select" multiple>
                                                        @foreach($users as $user)
                                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Exclusion Section -->
                                        <div>
                                            <span class="badge bg-danger-subtle text-danger mb-2 fw-semibold">2. Exclusion Scope (Bypasses/Auto-Approves)</span>
                                            <div class="row">
                                                <div class="col-md-3 mb-3">
                                                    <label class="form-label fw-semibold">Target Exclusion Scope</label>
                                                    <select name="exclude_scope_type" id="exclude_scope_type" class="form-select">
                                                        <option value="none">None (Default)</option>
                                                        <option value="user_type">User Type</option>
                                                        <option value="role">User Role</option>
                                                        <option value="user_type_role">User Type + Role</option>
                                                        <option value="specific_user">Specific User</option>
                                                    </select>
                                                </div>

                                                <!-- Exclude User Type Dropdown -->
                                                <div class="col-md-3 mb-3 exclude-scope-field d-none" id="exclude_user_type_div">
                                                    <label class="form-label fw-semibold">Excluded User Types <span class="text-muted">(Hold Ctrl to select multiple)</span></label>
                                                    <select name="exclude_user_types[]" id="exclude_user_types" class="form-select" multiple>
                                                        @foreach($userTypes as $type)
                                                            <option value="{{ $type->value }}">{{ $type->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <!-- Exclude Role Dropdown -->
                                                <div class="col-md-3 mb-3 exclude-scope-field d-none" id="exclude_role_div">
                                                    <label class="form-label fw-semibold">Excluded User Roles <span class="text-muted">(Hold Ctrl to select multiple)</span></label>
                                                    <select name="exclude_role_ids[]" id="exclude_role_ids" class="form-select" multiple>
                                                        @foreach($roles as $role)
                                                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <!-- Exclude Specific User Dropdown -->
                                                <div class="col-md-3 mb-3 exclude-scope-field d-none" id="exclude_user_div">
                                                    <label class="form-label fw-semibold">Excluded Specific Users <span class="text-muted">(Hold Ctrl to select multiple)</span></label>
                                                    <select name="exclude_user_ids[]" id="exclude_user_ids" class="form-select" multiple>
                                                        @foreach($users as $user)
                                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <hr>

                                <div class="d-flex justify-content-between align-items-center mb-3 mt-4">
                                    <h5 class="mb-0">Workflow Steps</h5>
                                    <button type="button" class="btn btn-sm btn-primary" id="addStepBtn">
                                        <i style="height: 12px; width: 12px" data-feather="plus"></i> Add Step
                                    </button>
                                </div>

                                <div id="stepsContainer">
                                    <!-- Steps will be injected here -->
                                </div>

                                <div class="row mt-4">
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                        <a href="{{ route('setting.approval_workflows.index') }}" class="btn btn-secondary ms-2">Cancel</a>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Template for Step Row -->
    <template id="stepTemplate">
        <div class="card mb-2 step-row bg-light border shadow-none">
            <div class="card-body p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="step-handle text-muted" style="cursor: grab;">
                        <i data-feather="menu"></i>
                    </div>
                    <div class="step-number fw-bold bg-white border rounded px-2 py-1 text-center" style="min-width: 40px;">
                        1
                    </div>
                    
                    <!-- Step Type -->
                    <div style="min-width: 150px;">
                        <select class="form-select step-type-select" required>
                            <option value="user-type" selected>User Type</option>
                            <option value="role-user">User Type + Role</option>
                            <option value="specific-user">Specific User</option>
                        </select>
                    </div>

                    <!-- User Type Selection (for user-type and role-user) -->
                    <div class="flex-grow-1 user-type-wrapper">
                        <select class="form-select user-type-select" required>
                            <option value="" disabled selected>Select User Type</option>
                            @foreach($userTypes as $type)
                                <option value="{{ $type->value }}">{{ ucfirst(str_replace('-', ' ', $type->value)) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Spatie Role Selection (for role-user) -->
                    <div class="flex-grow-1 role-wrapper" style="display: none;">
                        <select class="form-select role-select">
                            <option value="" disabled selected>Select Role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Specific User Selection (for specific-user) -->
                    <div class="flex-grow-1 user-wrapper" style="display: none;">
                        <select class="form-select user-select">
                            <option value="" disabled selected>Select Specific User</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <button type="button" class="btn btn-sm btn-danger remove-step-btn">
                            <i style="height: 14px; width: 14px" data-feather="x"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </template>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        const typeSelect = $('#type');
        const requiredApprovalsWrapper = $('#requiredApprovalsWrapper');
        const requiredApprovalsInput = $('#required_approvals');
        const totalStepsDisplay = $('#totalStepsDisplay');
        const stepsContainer = $('#stepsContainer');
        const addStepBtn = $('#addStepBtn');
        const stepTemplate = document.getElementById('stepTemplate');

        function toggleTypeFields() {
            if (typeSelect.val() === 'random') {
                requiredApprovalsWrapper.show();
                requiredApprovalsInput.attr('required', true);
            } else {
                requiredApprovalsWrapper.hide();
                requiredApprovalsInput.removeAttr('required');
            }
        }

        typeSelect.on('change', toggleTypeFields);

        function updateSteps() {
            const rows = stepsContainer.find('.step-row');
            const totalSteps = rows.length;
            
            rows.each(function(index) {
                $(this).find('.step-number').text(index + 1);
                
                // Update names of input fields for correct form serialization if needed
                $(this).find('.step-type-select').attr('name', `steps[${index}][type]`);
                $(this).find('.user-type-select').attr('name', `steps[${index}][required_user_type]`);
                $(this).find('.role-select').attr('name', `steps[${index}][role_id]`);
                $(this).find('.user-select').attr('name', `steps[${index}][user_id]`);
            });

            totalStepsDisplay.text(`out of ${totalSteps} total step(s)`);
            if (typeSelect.val() === 'random') {
                requiredApprovalsInput.attr('max', totalSteps);
            }
        }

        addStepBtn.on('click', function () {
            const newStep = $(stepTemplate.content.cloneNode(true));
            stepsContainer.append(newStep);
            
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
            updateSteps();
        });

        stepsContainer.on('click', '.remove-step-btn', function () {
            if (stepsContainer.find('.step-row').length > 1) {
                $(this).closest('.step-row').remove();
                updateSteps();
            } else {
                alert('You must have at least one step in the workflow.');
            }
        });

        // Toggle row inputs based on type selection
        stepsContainer.on('change', '.step-type-select', function() {
            const row = $(this).closest('.step-row');
            const type = $(this).val();

            // Reset wrappers visibility and required attributes
            row.find('.user-type-wrapper, .role-wrapper, .user-wrapper').hide();
            row.find('.user-type-select, .role-select, .user-select').removeAttr('required');

            if (type === 'user-type') {
                row.find('.user-type-wrapper').show();
                row.find('.user-type-select').attr('required', true);
            } else if (type === 'role-user') {
                row.find('.user-type-wrapper, .role-wrapper').show();
                row.find('.user-type-select, .role-select').attr('required', true);
            } else if (type === 'specific-user') {
                row.find('.user-wrapper').show();
                row.find('.user-select').attr('required', true);
            }
        });

        // Axios Form Submission
        document.getElementById('workflowForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="mdi mdi-loading mdi-spin"></i> Submitting...';

            // Clear previous errors
            $('.alert-danger').remove();
            
            // Build payload
            const formData = new FormData(this);
            const data = Object.fromEntries(formData.entries());
            
            // Inclusion multi-select arrays
            data.requester_user_types = $('#requester_user_types').val() || [];
            data.requester_role_ids = $('#requester_role_ids').val() || [];
            data.requester_user_ids = $('#requester_user_ids').val() || [];

            // Exclusion multi-select arrays
            data.exclude_user_types = $('#exclude_user_types').val() || [];
            data.exclude_role_ids = $('#exclude_role_ids').val() || [];
            data.exclude_user_ids = $('#exclude_user_ids').val() || [];
            
            // Handle array of steps correctly for Axios
            const steps = [];
            $('.step-row').each(function() {
                const type = $(this).find('.step-type-select').val();
                const stepData = { type: type };

                if (type === 'user-type') {
                    stepData.required_user_type = $(this).find('.user-type-select').val();
                } else if (type === 'role-user') {
                    stepData.required_user_type = $(this).find('.user-type-select').val();
                    stepData.role_id = $(this).find('.role-select').val();
                } else if (type === 'specific-user') {
                    stepData.user_id = $(this).find('.user-select').val();
                }

                steps.push(stepData);
            });
            data.steps = steps;

            axios.post('{{ route('setting.approval_workflows.store') }}', data)
                .then(response => {
                    window.location.href = response.data.redirect;
                })
                .catch(error => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = 'Submit';
                    
                    let errorMsg = 'Something went wrong. Please try again later.';
                    if (error.response && error.response.data) {
                        if (error.response.data.errors) {
                            errorMsg = Object.values(error.response.data.errors).flat().join('<br>');
                        } else if (error.response.data.message) {
                            errorMsg = error.response.data.message;
                        }
                    }
                    
                    const alertHtml = `<div class="alert alert-danger">${errorMsg}</div>`;
                    $('#workflowForm').before(alertHtml);
                    window.scrollTo(0, 0);
                });
        });

        // Inclusion Scope type toggle logic
        $('#scope_type').on('change', function() {
            $('.scope-field').addClass('d-none').find('select').val([]);
            const val = $(this).val();
            if (val === 'user_type') {
                $('#scope_user_type_div').removeClass('d-none');
            } else if (val === 'role') {
                $('#scope_role_div').removeClass('d-none');
            } else if (val === 'user_type_role') {
                $('#scope_user_type_div, #scope_role_div').removeClass('d-none');
            } else if (val === 'specific_user') {
                $('#scope_user_div').removeClass('d-none');
            }
        });

        // Exclusion Scope type toggle logic
        $('#exclude_scope_type').on('change', function() {
            $('.exclude-scope-field').addClass('d-none').find('select').val([]);
            const val = $(this).val();
            if (val === 'user_type') {
                $('#exclude_user_type_div').removeClass('d-none');
            } else if (val === 'role') {
                $('#exclude_role_div').removeClass('d-none');
            } else if (val === 'user_type_role') {
                $('#exclude_user_type_div, #exclude_role_div').removeClass('d-none');
            } else if (val === 'specific_user') {
                $('#exclude_user_div').removeClass('d-none');
            }
        });

        // Initialize
        toggleTypeFields();
        if (stepsContainer.children().length === 0) {
            addStepBtn.click();
        }
    });
</script>
@endpush
