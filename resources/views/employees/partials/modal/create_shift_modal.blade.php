{{-- Create Shift Plan Assignment Modal --}}
<div class="modal fade" id="createShiftPlanModal" tabindex="-1" aria-labelledby="createShiftPlanModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            {{-- Modal Header --}}
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="createShiftPlanModalLabel">
                    <i class="mdi mdi-clock-outline me-2"></i>Create Shift Plan Assignment
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            {{-- Modal Body --}}
            <div class="modal-body">
                <form id="createShiftPlanForm" method="POST" action="#">
                    @csrf

                    <div class="row g-3">
                        {{-- Select Shift Plan --}}
                        <div class="col-md-6">
                            <label for="modal_shift_plan_id" class="form-label fw-semibold">
                                <i class="mdi mdi-clock-outline text-primary me-1"></i>
                                Select Shift Plan <span class="text-danger">*</span>
                            </label>
                            <select id="modal_shift_plan_id" name="shift_plan_id" class="form-select" required>
                                <option value="">-- Choose Plan --</option>
                                @foreach ($shiftPlans as $plan)
                                    <option value="{{$plan->id}}">{{$plan->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Effective From --}}
                        <div class="col-md-6">
                            <label for="modal_shift_effective_from" class="form-label fw-semibold">
                                <i class="mdi mdi-calendar-start text-success me-1"></i>
                                Effective From <span class="text-danger">*</span>
                            </label>
                            <input type="date" id="modal_shift_effective_from" name="effective_from"
                                class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>

                        {{-- Effective To --}}
                        <div class="col-md-6">
                            <label for="modal_shift_effective_to" class="form-label fw-semibold">
                                <i class="mdi mdi-calendar-end text-danger me-1"></i>
                                Effective To <span class="text-danger">*</span>
                            </label>
                            <input type="date" id="modal_shift_effective_to" name="effective_to" class="form-control"
                                required>
                        </div>
                    </div>

                    {{-- Plan Details Display Area --}}
                    <div id="modal-shift-plan-details" class="mt-4" style="display: none;">
                        <hr class="my-3">
                        <div class="card border-primary">
                            <div class="card-header bg-primary-subtle">
                                <h6 class="mb-0 text-primary fw-semibold">
                                    <i class="mdi mdi-information-outline me-2"></i>Selected Plan Details
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <div class="border-start border-primary border-3 ps-3">
                                            <small class="text-muted d-block">Shift Name</small>
                                            <strong id="modal-shift-detail-name" class="text-dark">-</strong>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="border-start border-info border-3 ps-3">
                                            <small class="text-muted d-block">Clock In/Out</small>
                                            <strong id="modal-shift-detail-time" class="text-info">-</strong>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="border-start border-success border-3 ps-3">
                                            <small class="text-muted d-block">Work Hours</small>
                                            <strong id="modal-shift-detail-hours" class="text-success fs-5">-</strong>
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
                <button type="button" class="btn btn-primary" onclick="submitShiftModalForm()">
                    <i class="mdi mdi-check-circle me-1"></i> Create Assignment
                </button>
            </div>
        </div>
    </div>
</div>
