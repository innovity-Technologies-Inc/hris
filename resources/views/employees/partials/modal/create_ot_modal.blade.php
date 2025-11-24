{{-- Create OT Plan Assignment Modal --}}
<div class="modal fade" id="createOTPlanModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="mdi mdi-clock-plus-outline me-2"></i>Create OT Plan Assignment</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="createOTPlanForm" method="POST" action="{{route('employees.profile.plans.store', 'ot-plans')}}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="modal_ot_plan_id" class="form-label fw-semibold">
                                <i class="mdi mdi-clock-plus-outline text-primary me-1"></i>
                                Select OT Plan <span class="text-danger">*</span>
                            </label>
                            <select id="modal_ot_plan_id" name="ot_plan_id" class="form-select" required>
                                <option value="">-- Choose Plan --</option>
                                @foreach ($otPlans as $plan)
                                    <option value="{{ $plan->id }}">
                                        {{ $plan->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="modal_ot_effective_from" class="form-label fw-semibold">
                                <i class="mdi mdi-calendar-start text-success me-1"></i>
                                Effective From <span class="text-danger">*</span>
                            </label>
                            <input type="date" id="modal_ot_effective_from" name="from"
                                class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="modal_ot_effective_to" class="form-label fw-semibold">
                                <i class="mdi mdi-calendar-end text-danger me-1"></i>
                                Effective To <span class="text-danger">*</span>
                            </label>
                            <input type="date" id="modal_ot_effective_to" name="to" class="form-control"
                                required>
                        </div>
                    </div>
                    <div id="modal-ot-plan-details" class="mt-4" style="display: none;">
                        <hr class="my-3">
                        <div class="card border-primary">
                            <div class="card-header bg-primary-subtle">
                                <h6 class="mb-0 text-primary fw-semibold"><i
                                        class="mdi mdi-information-outline me-2"></i>Selected Plan Details</h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <div class="border-start border-primary border-3 ps-3">
                                            <small class="text-muted d-block">OT Plan</small>
                                            <strong id="modal-ot-detail-name" class="text-dark">-</strong>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="border-start border-info border-3 ps-3">
                                            <small class="text-muted d-block">OT Type</small>
                                            <strong id="modal-ot-detail-type" class="text-info">-</strong>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="border-start border-success border-3 ps-3">
                                            <small class="text-muted d-block">Config Type</small>
                                            <strong id="modal-ot-detail-config" class="text-success">-</strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-3 mt-2" id="modal-ot-rate-section">
                                    <div class="col-md-12">
                                        <div class="border-start border-warning border-3 ps-3">
                                            <small class="text-muted d-block">Rate</small>
                                            <strong id="modal-ot-detail-rate" class="text-warning fs-5">-</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i
                        class="mdi mdi-close me-1"></i> Cancel</button>
                <button type="button" class="btn btn-primary" onclick="submitOTModalForm()"><i
                        class="mdi mdi-check-circle me-1"></i> Create Assignment</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modalPlanSelector = document.getElementById('modal_ot_plan_id');
        const modalPlanDetails = document.getElementById('modal-ot-plan-details');
        const modal = document.getElementById('createOTPlanModal');

        if (modalPlanSelector) {
            modalPlanSelector.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                if (this.value) {
                    document.getElementById('modal-ot-detail-name').textContent = selectedOption
                        .getAttribute('data-plan-name');

                    // OT Type
                    const otType = selectedOption.getAttribute('data-ot-type');
                    document.getElementById('modal-ot-detail-type').textContent = otType.replace('_',
                        ' ').toUpperCase();

                    // Config Type
                    const configType = selectedOption.getAttribute('data-config-type');
                    document.getElementById('modal-ot-detail-config').textContent =
                        configType === 'salary_based' ? 'Salary Based' : 'Custom';

                    // Rate Information
                    let rateText = '-';
                    if (configType === 'salary_based') {
                        const salaryRateType = selectedOption.getAttribute('data-salary-rate-type');
                        const multiplier = selectedOption.getAttribute('data-multiplier');
                        if (salaryRateType === 'multiplier' && multiplier) {
                            rateText = multiplier + 'x Base Rate';
                        } else if (salaryRateType === 'basic_rate') {
                            rateText = 'Basic Rate';
                        }
                    } else {
                        const customRate = selectedOption.getAttribute('data-custom-rate');
                        if (customRate) {
                            rateText = '৳' + parseFloat(customRate).toFixed(2) + '/hr';
                        }
                    }
                    document.getElementById('modal-ot-detail-rate').textContent = rateText;

                    modalPlanDetails.style.display = 'block';
                } else {
                    modalPlanDetails.style.display = 'none';
                }
            });
        }

        if (modal) {
            modal.addEventListener('hidden.bs.modal', function() {
                document.getElementById('createOTPlanForm').reset();
                if (modalPlanDetails) modalPlanDetails.style.display = 'none';
            });
        }
    });

    function submitOTModalForm() {
        const form = document.getElementById('createOTPlanForm');
        if (form.checkValidity()) {
            alert('OT assignment created successfully!');
            bootstrap.Modal.getInstance(document.getElementById('createOTPlanModal')).hide();
        } else {
            form.reportValidity();
        }
    }
</script>
