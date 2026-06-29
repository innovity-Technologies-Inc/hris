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
                    <h5 class="card-title mb-0">Edit Approval Workflow</h5>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form id="workflowForm">
                                @csrf
                                <input type="hidden" name="_method" value="PUT">

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="module_name" class="form-label">Module <span class="text-danger">*</span></label>
                                        <select name="module_name" id="module_name" class="form-select" required>
                                            <option value="" disabled>Select Module</option>
                                            @foreach($modules as $key => $label)
                                                <option value="{{ $key }}" {{ old('module_name', $workflow->module_name) == $key ? 'selected' : '' }}>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="type" class="form-label">Workflow Type <span class="text-danger">*</span></label>
                                        <select name="type" id="type" class="form-select" required>
                                            <option value="sequential" {{ old('type', $workflow->type->value) == 'sequential' ? 'selected' : '' }}>Sequential (Step-by-Step)</option>
                                            <option value="random" {{ old('type', $workflow->type->value) == 'random' ? 'selected' : '' }}>Random (Parallel)</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-3" id="requiredApprovalsWrapper" style="display: none;">
                                    <div class="col-md-12">
                                        <label for="required_approvals" class="form-label">Required Approvals <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" name="required_approvals" id="required_approvals" value="{{ old('required_approvals', $workflow->required_approvals ?? 1) }}" min="1">
                                            <span class="input-group-text bg-light text-muted" id="totalStepsDisplay">out of X total steps</span>
                                        </div>
                                        <small class="text-muted">How many approvers must approve this for the entire workflow to pass?</small>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label for="is_active" class="form-label">Status</label>
                                        <select class="form-select" name="is_active" id="is_active">
                                            <option value="1" {{ old('is_active', $workflow->is_active ? '1' : '0') == '1' ? 'selected' : '' }}>Active</option>
                                            <option value="0" {{ old('is_active', $workflow->is_active ? '1' : '0') == '0' ? 'selected' : '' }}>Inactive</option>
                                        </select>
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
                                    @foreach($workflow->steps as $index => $step)
                                        <div class="card mb-2 step-row bg-light border shadow-none">
                                            <div class="card-body p-3 d-flex align-items-center gap-3">
                                                <div class="step-handle text-muted" style="cursor: grab;">
                                                    <i data-feather="menu"></i>
                                                </div>
                                                <div class="step-number fw-bold bg-white border rounded px-2 py-1 text-center" style="min-width: 40px;">
                                                    {{ $index + 1 }}
                                                </div>
                                                <div class="flex-grow-1">
                                                    <select class="form-select user-type-select" name="steps[{{ $index }}][required_user_type]" required>
                                                        <option value="" disabled>Select Approver Type</option>
                                                        @foreach($userTypes as $type)
                                                            <option value="{{ $type->value }}" {{ $step->required_user_type == $type->value ? 'selected' : '' }}>
                                                                {{ ucfirst(str_replace('-', ' ', $type->value)) }}
                                                            </option>
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
                                    @endforeach
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
            <div class="card-body p-3 d-flex align-items-center gap-3">
                <div class="step-handle text-muted" style="cursor: grab;">
                    <i data-feather="menu"></i>
                </div>
                <div class="step-number fw-bold bg-white border rounded px-2 py-1 text-center" style="min-width: 40px;">
                    1
                </div>
                <div class="flex-grow-1">
                    <select class="form-select user-type-select" required>
                        <option value="" disabled selected>Select Approver Type</option>
                        @foreach($userTypes as $type)
                            <option value="{{ $type->value }}">{{ ucfirst(str_replace('-', ' ', $type->value)) }}</option>
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
                $(this).find('.user-type-select').attr('name', `steps[${index}][required_user_type]`);
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
            
            // Handle array of steps correctly for Axios
            const steps = [];
            $('.step-row').each(function(index) {
                const userType = $(this).find('.user-type-select').val();
                if(userType) {
                    steps.push({ required_user_type: userType });
                }
            });
            data.steps = steps;

            axios.post('{{ route('setting.approval_workflows.update', $workflow->id) }}', data)
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

        // Initialize
        toggleTypeFields();
        updateSteps();
    });
</script>
@endpush
