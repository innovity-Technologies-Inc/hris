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
                <form id="createMealPlanForm" method="POST" action="{{route('employees.profile.plans.store', 'meal-plans')}}">
                    @csrf

                    <div class="row g-3">
                        <input type="hidden" name="employee_id" value="{{ $employee->id }}">
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
                            <select id="modal_meal_plan_id" name="plan_id" class="form-select" required>
                                <option value="">-- Choose Plan --</option>

                            </select>
                        </div>

                        {{-- Effective From --}}
                        <div class="col-md-6">
                            <label for="modal_effective_from" class="form-label fw-semibold">
                                <i class="mdi mdi-calendar-start text-success me-1"></i>
                                Effective From <span class="text-danger">*</span>
                            </label>
                            <input type="date" id="modal_effective_from" name="from" class="form-control"
                                value="{{ date('Y-m-d') }}" required>
                        </div>

                        {{-- Effective To --}}
                        <div class="col-md-6">
                            <label for="modal_effective_to" class="form-label fw-semibold">
                                <i class="mdi mdi-calendar-end text-danger me-1"></i>
                                Effective To <span class="text-danger">*</span>
                            </label>
                            <input type="date" id="modal_effective_to" name="to" class="form-control"
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
                                            <small class="text-muted d-block">Cost ({{\App\HelperClass::getCurrency()}})</small>
                                            <strong id="modal-detail-price" class="text-success fs-5"></strong>
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
                <button type="submit" class="btn btn-primary" onclick="submitModalForm()">
                    <i class="mdi mdi-check-circle me-1"></i> Add
                </button>
            </div>
        </div>
    </div>
</div>


<script src="{{asset('assets/libs/jquery/jquery.min.js')}}"></script>


<script>
    $(function() {

        function loadMeals(mealType, selectedMeal = null) {
            if (mealType) {
                $.get('/get-meal-plans/' + mealType, function(data) {
                    let $mealSelect = $('#modal_meal_plan_id');
                    $mealSelect.html('<option value="">-- Select --</option>');
                    $.each(data, function(key, value) {
                        let selected = (selectedMeal == value.id) ? 'selected' : '';
                        $mealSelect.append('<option value="'+ value.id +'" '+selected+'>'+ value.name +'</option>');
                    });
                });
            }
        }

        // --- Change Event ---
        $('#modal_meal_type').on('change', function() {
            loadMeals($(this).val());
        });

        // --- Auto-load existing values from DB when editing ---
        @if(isset($employee_meal_info))
        let mealType = "{{ old('modal_meal_type', $employee_meal_info->modal_meal_type ?? '') }}";
        let meals  = "{{ old('modal_meal_plan_id', $employee_meal_info->modal_meal_plan_id ?? '') }}";

        if (mealType) {
            loadMeals(mealType, meals);
        }
        @endif

    });
</script>

<script>
    $(function() {

        function loadMeals(mealType, selectedMeal = null) {
            if (mealType) {
                $.get('/get-meal-plans/' + mealType, function(data) {
                    let $select = $('#modal_meal_plan_id');
                    $select.html('<option value="">-- Select --</option>');

                    $.each(data, function(key, value) {
                        let selected = (selectedMeal == value.id) ? 'selected' : '';
                        $select.append('<option value="'+ value.id +'" '+selected+'>'+ value.name +'</option>');
                    });
                });
            }
        }

        // ============================
        // 🚀 Show Plan Details
        // ============================
        $('#modal_meal_plan_id').on('change', function() {
            let planId = $(this).val();

            if (planId) {
                $.ajax({
                    url: "/get-meal-plan-details/" + planId,
                    type: "GET",
                    success: function(data) {

                        // Show details block
                        $('#modal-plan-details').show();

                        // Update fields
                        $('#modal-detail-type').text(data.type ?? '-');
                        $('#modal-detail-name').text(data.name ?? '-');
                        $('#modal-detail-price').text(data.cost ?? '0');
                    }
                });
            } else {
                $('#modal-plan-details').hide();
            }
        });

        // Load meals if editing
        @if(isset($employee_meal_info))
        let mealType = "{{ old('modal_meal_type', $employee_meal_info->modal_meal_type ?? '') }}";
        let meals  = "{{ old('modal_meal_plan_id', $employee_meal_info->modal_meal_plan_id ?? '') }}";

        if (mealType) {
            loadMeals(mealType, meals);
        }
        @endif

    });

</script>
