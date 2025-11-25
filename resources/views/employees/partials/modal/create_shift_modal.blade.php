<div class="modal fade" id="createShiftPlanModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="mdi mdi-clock-plus-outline me-2"></i>Create Shift Plan Assignment</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{route('employees.profile.plans.store', 'shift-plans')}}">
                @csrf
                <input type="hidden" name="employee_id" value="{{ $employee->id }}">

                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="modal_shift_plan_id" class="form-label fw-semibold">
                                <i class="mdi mdi-clock-plus-outline text-primary me-1"></i>
                                Select Shift Plan <span class="text-danger">*</span>
                            </label>
                            <select id="modal_shift_plan_id" name="plan_id" class="form-select" required>
                                <option value="">-- Choose Plan --</option>
                                @foreach ($shiftPlans as $plan)
                                    <option value="{{ $plan->id }}">
                                        {{ $plan->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="modal_shift_effective_from" class="form-label fw-semibold">
                                <i class="mdi mdi-calendar-start text-success me-1"></i>
                                Effective From <span class="text-danger">*</span>
                            </label>
                            <input type="date" id="modal_shift_effective_from" name="from"
                                   class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="modal_shift_effective_to" class="form-label fw-semibold">
                                <i class="mdi mdi-calendar-end text-danger me-1"></i>
                                Effective To <span class="text-danger">*</span>
                            </label>
                            <input type="date" id="modal_shift_effective_to" name="to" class="form-control"
                                   required>
                        </div>
                    </div>
                    <div id="modal-shift-plan-details" class="mt-4" style="display: none;">
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
                                            <small class="text-muted d-block">Shift Plan</small>
                                            <strong id="modal-shift-detail-name" class="text-dark">-</strong>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="border-start border-info border-3 ps-3">
                                            <small class="text-muted d-block">Clock In</small>
                                            <strong id="modal-shift-detail-start" class="text-info">-</strong>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="border-start border-success border-3 ps-3">
                                            <small class="text-muted d-block">Clock Out</small>
                                            <strong id="modal-shift-detail-end" class="text-success">-</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i
                            class="mdi mdi-close me-1"></i> Cancel</button>
                    <button type="submit" class="btn btn-primary"><i
                            class="mdi mdi-check-circle me-1"></i> Add</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(function() {

        // ============================
        // 🚀 Show Off Day Plan Details
        // ============================
        $('#modal_shift_plan_id').on('change', function () {
            let planId = $(this).val();

            if (!planId) {
                $('#modal-shift-plan-details').hide();
                return;
            }

            $.ajax({
                url: "/get-shift-plan-details/" + planId,
                type: "GET",
                success: function (data) {
                    console.log(data); // see what is returned
                    $('#modal-shift-plan-details').show();
                    $('#modal-shift-detail-name').text(data.name ?? '-');
                    $('#modal-shift-detail-start').text(data.start_time ?? '-');
                    $('#modal-shift-detail-end').text(data.end_time ?? '-');
                }
            });
        });

    });
</script>

