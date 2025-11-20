{{-- Create Meal Plan Assignment Modal --}}
<div class="modal fade" id="createMealPlanModal" tabindex="-1" aria-labelledby="createMealPlanModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            {{-- Modal Header --}}
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="createMealPlanModalLabel">
                    <i class="mdi mdi-food-apple me-2"></i>Create Meal Plan Assignment
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            {{-- Modal Body --}}
            <div class="modal-body">
                <form id="createMealPlanForm" method="POST" action="#">
                    @csrf

                    <div class="row g-3">
                        {{-- Meal Type --}}
                        <div class="col-md-6">
                            <label for="modal_meal_type" class="form-label fw-semibold">
                                <i class="mdi mdi-silverware-fork-knife text-primary me-1"></i>
                                Meal Type <span class="text-danger">*</span>
                            </label>
                            <select id="modal_meal_type" name="meal_type" class="form-select" required>
                                <option value="">-- Select Meal Type --</option>
                                    <option value="breakfast">Breakfast</option>
                                    <option value="lunch">Lunch</option>
                                    <option value="snacks">Snacks</option>
                                    <option value="dinner">Dinner</option>
                            </select>
                        </div>

                        {{-- Select Meal Plan --}}
                        <div class="col-md-6">
                            <label for="modal_meal_plan_id" class="form-label fw-semibold">
                                <i class="mdi mdi-food text-success me-1"></i>
                                Select Meal Plan <span class="text-danger">*</span>
                            </label>
                            <select id="modal_meal_plan_id" name="meal_plan_id" class="form-select" required>
                                <option value="">-- Choose Plan --</option>

                            </select>
                        </div>

                        {{-- Effective From --}}
                        <div class="col-md-6">
                            <label for="modal_effective_from" class="form-label fw-semibold">
                                <i class="mdi mdi-calendar-start text-success me-1"></i>
                                Effective From <span class="text-danger">*</span>
                            </label>
                            <input type="date" id="modal_effective_from" name="effective_from" class="form-control"
                                value="{{ date('Y-m-d') }}" required>
                        </div>

                        {{-- Effective To --}}
                        <div class="col-md-6">
                            <label for="modal_effective_to" class="form-label fw-semibold">
                                <i class="mdi mdi-calendar-end text-danger me-1"></i>
                                Effective To <span class="text-danger">*</span>
                            </label>
                            <input type="date" id="modal_effective_to" name="effective_to" class="form-control"
                                required>
                        </div>
                    </div>

                    {{-- Plan Details Display Area (Hidden by default) --}}
                    <div id="modal-plan-details" class="mt-4" style="display: none;">
                        <hr class="my-3">

                        <div class="card border-primary">
                            {{-- Card Header --}}
                            <div class="card-header bg-primary-subtle">
                                <h6 class="mb-0 text-primary fw-semibold">
                                    <i class="mdi mdi-information-outline me-2"></i>Selected Plan Details
                                </h6>
                            </div>

                            {{-- Card Body - Simplified to show only essential fields --}}
                            <div class="card-body">
                                <div class="row g-3">

                                    {{-- Plan Type (Breakfast/Lunch/Snacks/Dinner) --}}
                                    <div class="col-md-4">
                                        <div class="border-start border-primary border-3 ps-3">
                                            <small class="text-muted d-block">Plan Type</small>
                                            <strong id="modal-detail-type" class="text-dark">-</strong>
                                        </div>
                                    </div>

                                    {{-- Plan Name --}}
                                    <div class="col-md-4">
                                        <div class="border-start border-info border-3 ps-3">
                                            <small class="text-muted d-block">Plan Name</small>
                                            <strong id="modal-detail-name" class="text-dark">-</strong>
                                        </div>
                                    </div>

                                    {{-- Daily Cost --}}
                                    <div class="col-md-4">
                                        <div class="border-start border-success border-3 ps-3">
                                            <small class="text-muted d-block">Daily Rate</small>
                                            <strong id="modal-detail-price" class="text-success fs-5">৳0</strong>
                                            <small class="text-muted">/day</small>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                </form>
            </div>

            {{-- Modal Footer --}}
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="mdi mdi-close me-1"></i> Cancel
                </button>
                <button type="button" class="btn btn-primary" onclick="submitModalForm()">
                    <i class="mdi mdi-check-circle me-1"></i> Create Assignment
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Modal JavaScript --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modalPlanSelector = document.getElementById('modal_meal_plan_id');
        const modalMealType = document.getElementById('modal_meal_type');
        const modalPlanDetails = document.getElementById('modal-plan-details');
        const modal = document.getElementById('createMealPlanModal');

        // Detail elements
        const detailType = document.getElementById('modal-detail-type');
        const detailName = document.getElementById('modal-detail-name');
        const detailPrice = document.getElementById('modal-detail-price');

        // Add transition styles
        if (modalPlanDetails) {
            modalPlanDetails.style.transition = 'all 0.3s ease';
            modalPlanDetails.style.opacity = '0';
        }

        // Update plan type when meal type changes
        if (modalMealType) {
            modalMealType.addEventListener('change', function() {
                if (detailType && this.value) {
                    detailType.textContent = this.value;
                }
            });
        }

        // Handle plan selection
        if (modalPlanSelector) {
            modalPlanSelector.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];

                if (this.value) {
                    // Extract plan data
                    const planName = selectedOption.getAttribute('data-plan-name');
                    const planPrice = parseInt(selectedOption.getAttribute('data-plan-price'));

                    // Get selected meal type
                    const mealTypeSelect = document.getElementById('modal_meal_type');
                    const mealType = mealTypeSelect ? mealTypeSelect.value : '-';

                    // Update details
                    if (detailType) detailType.textContent = mealType;
                    if (detailName) detailName.textContent = planName;
                    if (detailPrice) detailPrice.textContent = '৳' + planPrice.toLocaleString();

                    // Show details with smooth animation
                    modalPlanDetails.style.display = 'block';
                    setTimeout(() => {
                        modalPlanDetails.style.opacity = '1';
                    }, 10);
                } else {
                    // Hide details
                    modalPlanDetails.style.opacity = '0';
                    setTimeout(() => {
                        modalPlanDetails.style.display = 'none';
                    }, 300);
                }
            });
        }

        // Reset modal when closed
        if (modal) {
            modal.addEventListener('hidden.bs.modal', function() {
                document.getElementById('createMealPlanForm').reset();
                if (modalPlanDetails) {
                    modalPlanDetails.style.display = 'none';
                    modalPlanDetails.style.opacity = '0';
                }
            });
        }
    });

    // Form submission handler
    function submitModalForm() {
        const form = document.getElementById('createMealPlanForm');

        if (form.checkValidity()) {
            // In production, submit the form via AJAX or normal submission
            const formData = new FormData(form);

            console.log('Form Data:');
            for (let [key, value] of formData.entries()) {
                console.log(key + ': ' + value);
            }

            alert('Assignment created successfully!\n(In production, this would submit to your Laravel route)');

            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('createMealPlanModal'));
            modal.hide();

            // Optionally reload page or update table
            // location.reload();
        } else {
            form.reportValidity();
        }
    }
</script>
