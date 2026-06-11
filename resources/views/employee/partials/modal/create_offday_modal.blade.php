{{-- Create Off Day Plan Assignment Modal --}}
<div class="modal fade" id="createOffDayPlanModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="mdi mdi-calendar-blank me-2"></i>Create Off Day Plan Assignment</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('employee.profile.plans.store', 'offday-plans') }}">
                @csrf

                <div class="modal-body">
                    <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="modal_offday_plan_id" class="form-label fw-semibold">
                                <i class="mdi mdi-calendar-blank text-primary me-1"></i>
                                Select Off Day Plan <span class="text-danger">*</span>
                            </label>
                            <select id="modal_offday_plan_id" name="plan_id" class="form-select" required>
                                <option value="">-- Choose Plan --</option>
                                @foreach ($offDayPlans as $plan)
                                    <option value="{{ $plan->id }}">
                                        {{ $plan->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="modal_offday_effective_from" class="form-label fw-semibold">
                                <i class="mdi mdi-calendar-start text-success me-1"></i>
                                Effective From <span class="text-danger">*</span>
                            </label>
                            <input type="date" id="modal_offday_effective_from" name="from" class="form-control"
                                value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="modal_offday_effective_to" class="form-label fw-semibold">
                                <i class="mdi mdi-calendar-end text-danger me-1"></i>
                                Effective To
                            </label>
                            <input type="date" id="modal_offday_effective_to" name="to" class="form-control">
                        </div>
                    </div>
                    <div id="modal-offday-plan-details" class="mt-4" style="display: none;">
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
                                            <small class="text-muted d-block">Plan Name</small>
                                            <strong id="modal-offday-detail-name" class="text-dark">-</strong>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="border-start border-secondary border-3 ps-3">
                                            <small class="text-muted d-block">Short Name</small>
                                            <strong id="modal-offday-detail-short" class="text-secondary">-</strong>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="border-start border-success border-3 ps-3">
                                            <small class="text-muted d-block">Assigned Shift</small>
                                            <strong id="modal-offday-detail-shift" class="text-success">-</strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-3 mt-2">
                                    <div class="col-md-4">
                                        <div class="border-start border-info border-3 ps-3">
                                            <small class="text-muted d-block">Clock In Time</small>
                                            <strong id="modal-offday-detail-start" class="text-info">-</strong>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="border-start border-warning border-3 ps-3">
                                            <small class="text-muted d-block">Clock Out Time</small>
                                            <strong id="modal-offday-detail-end" class="text-warning">-</strong>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="border-start border-danger border-3 ps-3">
                                            <small class="text-muted d-block">Grace Time</small>
                                            <strong id="modal-offday-detail-grace" class="text-danger">-</strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="row g-3 mt-2">
                                    <div class="col-md-12">
                                        <div class="border-start border-purple border-3 ps-3">
                                            <small class="text-muted d-block">Remuneration Details</small>
                                            <strong id="modal-offday-detail-description" class="text-dark">-</strong>
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
                    <button type="submit" class="btn btn-primary" onclick="submitOffDayModalForm()"><i
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
        $(document).on('change', '#modal_offday_plan_id', function() {
            let planId = $(this).val();

            if (!planId) {
                $('#modal-offday-plan-details').hide();
                return;
            }

            $.ajax({
                url: "/get-offday-plan-details/" + planId,
                type: "GET",
                success: function(data) {

                    // Show the details box
                    $('#modal-offday-plan-details').show();

                    // Fill the details
                    $('#modal-offday-detail-name').text(data.name ?? '-');
                    $('#modal-offday-detail-short').text(data.short_name ?? '-');
                    $('#modal-offday-detail-shift').text(data.shift_name ?? '-');
                    $('#modal-offday-detail-description').text(data
                        .configuration_description ?? '-');

                    $('#modal-offday-detail-start').text(data.start_time ?? '-');
                    $('#modal-offday-detail-end').text(data.end_time ?? '-');
                    $('#modal-offday-detail-grace').text((data.grace_time ?? '0') + ' min');
                }
            });
        });

    });
</script>

