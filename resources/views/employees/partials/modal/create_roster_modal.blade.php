{{-- Create Roster Plan Assignment Modal --}}
<div class="modal fade" id="createRosterPlanModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="mdi mdi-calendar-multiple me-2"></i>Create Roster Plan Assignment</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="createRosterPlanForm" method="POST" action="{{route('employees.profile.plans.store', 'roster-plans')}}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="modal_roster_plan_id" class="form-label fw-semibold">
                                <i class="mdi mdi-calendar-multiple text-primary me-1"></i>
                                Select Roster Plan <span class="text-danger">*</span>
                            </label>
                            <select id="modal_roster_plan_id" name="roster_plan_id" class="form-select" required>
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
                                    <div class="col-md-4">
                                        <div class="border-start border-primary border-3 ps-3">
                                            <small class="text-muted d-block">Plan Name</small>
                                            <strong id="modal-roster-detail-name" class="text-dark">-</strong>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="border-start border-secondary border-3 ps-3">
                                            <small class="text-muted d-block">Short Name</small>
                                            <strong id="modal-roster-detail-short" class="text-secondary">-</strong>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="border-start border-info border-3 ps-3">
                                            <small class="text-muted d-block">Repetition Days</small>
                                            <strong id="modal-roster-detail-days" class="text-info">-</strong>
                                        </div>
                                    </div>
                                </div>

                                <div id="modal-roster-description" class="mb-3" style="display: none;">
                                    <small class="text-muted d-block">Description</small>
                                    <p id="modal-roster-detail-description" class="text-secondary mb-0">-</p>
                                </div>

                                {{-- Shift Schedule Section --}}
                                <div id="modal-roster-shifts" class="mt-3">
                                    {{-- Shift 1 Details --}}
                                    <div id="modal-roster-shift1" class="mb-3" style="display: none;">
                                        <div class="border rounded p-3 bg-success-subtle">
                                            <h6 class="fw-semibold mb-2 text-success">
                                                <i class="mdi mdi-clock-outline me-1"></i>Shift 1
                                            </h6>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <small class="text-muted d-block">Shift Name</small>
                                                    <strong id="modal-shift1-name">-</strong>
                                                </div>
                                                <div class="col-md-3">
                                                    <small class="text-muted d-block">Start Time</small>
                                                    <strong id="modal-shift1-start" class="text-success">-</strong>
                                                </div>
                                                <div class="col-md-3">
                                                    <small class="text-muted d-block">End Time</small>
                                                    <strong id="modal-shift1-end" class="text-danger">-</strong>
                                                </div>
                                                <div class="col-md-3">
                                                    <small class="text-muted d-block">Duration</small>
                                                    <strong id="modal-shift1-duration" class="text-secondary">-</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Shift 2 Details --}}
                                    <div id="modal-roster-shift2" class="mb-2" style="display: none;">
                                        <div class="border rounded p-3 bg-warning-subtle">
                                            <h6 class="fw-semibold mb-2 text-warning">
                                                <i class="mdi mdi-clock-outline me-1"></i>Shift 2
                                            </h6>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <small class="text-muted d-block">Shift Name</small>
                                                    <strong id="modal-shift2-name">-</strong>
                                                </div>
                                                <div class="col-md-3">
                                                    <small class="text-muted d-block">Start Time</small>
                                                    <strong id="modal-shift2-start" class="text-success">-</strong>
                                                </div>
                                                <div class="col-md-3">
                                                    <small class="text-muted d-block">End Time</small>
                                                    <strong id="modal-shift2-end" class="text-danger">-</strong>
                                                </div>
                                                <div class="col-md-3">
                                                    <small class="text-muted d-block">Duration</small>
                                                    <strong id="modal-shift2-duration" class="text-secondary">-</strong>
                                                </div>
                                            </div>
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
                <button type="button" class="btn btn-primary" onclick="submitRosterModalForm()"><i
                        class="mdi mdi-check-circle me-1"></i> Create Assignment</button>
            </div>
        </div>
    </div>
</div>
