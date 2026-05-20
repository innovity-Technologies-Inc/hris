{{-- Create Roster Plan Assignment Modal --}}
<div class="modal fade " id="createRosterPlanModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="mdi mdi-calendar-multiple me-2"></i>Create Roster Plan</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="createRosterPlanForm" method="POST" action="{{route('employee.profile.plans.store', 'roster-plans')}}">
                @csrf
                <input type="hidden" name="employee_id" value="{{ $employee->id }}">
            <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="modal_roster_plan_id" class="form-label fw-semibold">
                                <i class="mdi mdi-calendar-multiple text-primary me-1"></i>
                                Select Roster Plan <span class="text-danger">*</span>
                            </label>
                            <select id="modal_roster_plan_id" name="plan_id" class="form-select" required>
                                <option value="">-- Choose Plan --</option>
                                @foreach ($rosterPlans as $plan)
                                    <option value="{{ $plan->id }}">
                                        {{ $plan->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="modal_roster_effective_from" class="form-label fw-semibold">
                                <i class="mdi mdi-calendar-start text-success me-1"></i>
                                Effective From <span class="text-danger">*</span>
                            </label>
                            <input type="date" id="modal_roster_effective_from" name="from"
                                class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="modal_roster_effective_to" class="form-label fw-semibold">
                                <i class="mdi mdi-calendar-end text-danger me-1"></i>
                                Effective To <span class="text-danger">*</span>
                            </label>
                            <input type="date" id="modal_roster_effective_to" name="to"
                                class="form-control" required>
                        </div>
                    </div>
                    <div id="modal-roster-plan-details" class="mt-4" style="display: none;">
                        <hr class="my-3">
                        <div class="card border-primary">
                            <div class="card-header bg-primary-subtle">
                                <h6 class="mb-0 text-primary fw-semibold"><i
                                        class="mdi mdi-information-outline me-2"></i>Selected Plan Details</h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3 mb-3">
                                    <div class="col-md-6">
                                        <div class="border-start border-primary border-3 ps-3">
                                            <small class="text-muted d-block">Plan Name</small>
                                            <strong id="modal-roster-detail-name" class="text-dark">-</strong>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="border-start border-info border-3 ps-3">
                                            <small class="text-muted d-block">Repetition Days</small>
                                            <strong id="modal-roster-detail-days" class="text-info">-</strong>
                                        </div>
                                    </div>
                                </div>

                                {{-- Shift Schedule Section --}}
                                <div id="modal-roster-rosters" class="mt-3">
                                    {{-- Shift 1 Details --}}
                                    <div id="modal-roster-roster1" class="mb-3" style="display: none;">
                                        <div class="border rounded p-3 bg-success-subtle">
                                            <h6 class="fw-semibold mb-2 text-success">
                                                <i class="mdi mdi-clock-outline me-1"></i>Shift 1
                                            </h6>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <small class="text-muted d-block">Shift Name</small>
                                                    <strong id="modal-roster1-name">-</strong>
                                                </div>
                                                <div class="col-md-4">
                                                    <small class="text-muted d-block">Start Time</small>
                                                    <strong id="modal-roster1-start" class="text-success">-</strong>
                                                </div>
                                                <div class="col-md-4">
                                                    <small class="text-muted d-block">End Time</small>
                                                    <strong id="modal-roster1-end" class="text-danger">-</strong>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                    {{-- Shift 2 Details --}}
                                    <div id="modal-roster-roster2" class="mb-2" style="display: none;">
                                        <div class="border rounded p-3 bg-warning-subtle">
                                            <h6 class="fw-semibold mb-2 text-warning">
                                                <i class="mdi mdi-clock-outline me-1"></i>Shift 2
                                            </h6>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <small class="text-muted d-block">Shift Name</small>
                                                    <strong id="modal-roster2-name">-</strong>
                                                </div>
                                                <div class="col-md-4">
                                                    <small class="text-muted d-block">Start Time</small>
                                                    <strong id="modal-roster2-start" class="text-success">-</strong>
                                                </div>
                                                <div class="col-md-4">
                                                    <small class="text-muted d-block">End Time</small>
                                                    <strong id="modal-roster2-end" class="text-danger">-</strong>
                                                </div>
                                            </div>
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
    $(document).on('change', '#modal_roster_plan_id', function () {
        let planId = $(this).val();

        if (!planId) {
            $('#modal-roster-plan-details').hide();
            return;
        }

        $.ajax({
            url: "/get-roster-plan-details/" + planId,
            type: "GET",
            success: function (data) {

                console.log(data);

                $('#modal-roster-plan-details').show();
                $('#modal-roster-detail-name').text(data.name ?? '-');
                $('#modal-roster-detail-days').text(data.swapping ?? '-');

                // -------------------------
                // SHIFT 1 (Show if exists)
                // -------------------------
                if (data.first_shift_name || data.first_shift_start || data.first_shift_end) {

                    $('#modal-roster-roster1').show();

                    $('#modal-roster1-name').text(
                        data.first_shift_name ?? data.shift1?.name ?? '-'
                    );

                    $('#modal-roster1-start').text(
                        data.first_shift_start ?? data.shift1?.start_time ?? data.shift1?.clock_in ?? '-'
                    );

                    $('#modal-roster1-end').text(
                        data.first_shift_end ?? data.shift1?.end_time ?? data.shift1?.clock_out ?? '-'
                    );

                } else {
                    $('#modal-roster-roster1').hide();
                }

                // -------------------------
                // SHIFT 2 (Show if exists)
                // -------------------------
                if (data.second_shift_name || data.second_shift_start || data.second_shift_end) {

                    $('#modal-roster-roster2').show();

                    $('#modal-roster2-name').text(
                        data.second_shift_name ?? data.shift2?.name ?? '-'
                    );

                    $('#modal-roster2-start').text(
                        data.second_shift_start ?? data.shift2?.start_time ?? data.shift2?.clock_in ?? '-'
                    );

                    $('#modal-roster2-end').text(
                        data.second_shift_end ?? data.shift2?.end_time ?? data.shift2?.clock_out ?? '-'
                    );

                } else {
                    $('#modal-roster-roster2').hide();
                }
            }
        });
    });
    });

</script>

