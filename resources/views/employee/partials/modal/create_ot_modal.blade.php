{{-- Create OT Plan Assignment Modal --}}
<div class="modal fade" id="createOTPlanModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="mdi mdi-clock-plus-outline me-2"></i>Create OT Plan Assignment</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('employee.profile.plans.store', 'ot-plans') }}">
                @csrf
                <input type="hidden" name="employee_id" value="{{ $employee->id }}">

                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="modal_ot_plan_id" class="form-label fw-semibold">
                                <i class="mdi mdi-clock-plus-outline text-primary me-1"></i>
                                Select OT Plan <span class="text-danger">*</span>
                            </label>
                            <select id="modal_ot_plan_id" name="plan_id" class="form-select" required>
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
                            <input type="date" id="modal_ot_effective_from" name="from" class="form-control"
                                value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="modal_ot_effective_to" class="form-label fw-semibold">
                                <i class="mdi mdi-calendar-end text-danger me-1"></i>
                                Effective To <span class="text-danger">*</span>
                            </label>
                            <input type="date" id="modal_ot_effective_to" name="to" class="form-control"
                                value="{{ date('Y-m-d', strtotime('+1 year')) }}" required>
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
                                    <div class="col-md-6">
                                        <div class="border-start border-primary border-3 ps-3">
                                            <small class="text-muted d-block">OT Plan</small>
                                            <strong id="modal-ot-detail-name" class="text-dark">-</strong>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="border-start border-success border-3 ps-3">
                                            <small class="text-muted d-block">Config Type</small>
                                            <strong id="modal-ot-detail-config" class="text-success">-</strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-3 mt-2" id="modal-ot-rate-section">
                                    <div class="col-md-4">
                                        <div class="border-start border-warning border-3 ps-3">
                                            <small class="text-muted d-block">Remuneration Type</small>
                                            <strong id="modal-ot-detail-salary-type"
                                                class="text-warning fs-5">-</strong>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="border-start border-warning border-3 ps-3">
                                            <small class="text-muted d-block">Multiplier</small>
                                            <strong id="modal-ot-detail-multiplier" class="text-warning fs-5">-</strong>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="border-start border-warning border-3 ps-3">
                                            <small class="text-muted d-block">Custom Rate</small>
                                            <strong id="modal-ot-detail-rate" class="text-warning fs-5">-</strong>
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
                    <button type="submit" class="btn btn-primary"><i class="mdi mdi-check-circle me-1"></i>
                        Add</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(function() {

        // ============================
        // 🚀 Set minimum date for "to" field
        // ============================
        $(document).on('change', '#modal_ot_effective_from', function() {
            let fromDate = $(this).val();
            if (fromDate) {
                $('#modal_ot_effective_to').attr('min', fromDate);
                // If to date is empty or less than from date, set it to from date
                let toDate = $('#modal_ot_effective_to').val();
                if (!toDate || toDate < fromDate) {
                    // Set to date to 1 year from the from date
                    let toDateObj = new Date(fromDate);
                    toDateObj.setFullYear(toDateObj.getFullYear() + 1);
                    let toDateString = toDateObj.toISOString().split('T')[0];
                    $('#modal_ot_effective_to').val(toDateString);
                }
            }
        });

        // ============================
        // 🚀 Show OT Plan Details
        // ============================
        $(document).on('change', '#modal_ot_plan_id', function() {
            let planId = $(this).val();

            if (!planId) {
                $('#modal-ot-plan-details').hide();
                return;
            }

            $.ajax({
                url: "/get-ot-plan-details/" + planId,
                type: "GET",
                success: function(data) {
                    console.log(data); // see what is returned
                    $('#modal-ot-plan-details').show();
                    $('#modal-ot-detail-name').text(data.name ?? '-');
                    $('#modal-ot-detail-config').text(data.config ?? '-');
                    $('#modal-ot-detail-rate').text(data.rate ?? '-');
                    $('#modal-ot-detail-multiplier').text(data.multiplier ?? '-');
                }
            });
        });

    });
</script>

