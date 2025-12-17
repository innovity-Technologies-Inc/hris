{{-- View Leave Application Details Modal --}}
<div class="modal fade" id="viewLeaveModal" tabindex="-1" aria-labelledby="viewLeaveModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            {{-- Modal Header --}}
            <div class="modal-header bg-primary text-white border-0">
                <h5 class="modal-title fw-semibold" id="viewLeaveModalLabel">
                    <i class="mdi mdi-file-document-outline me-2"></i>Leave Application Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            {{-- Modal Body --}}
            <div class="modal-body p-4">
                {{-- Status Badge --}}
                <div class="text-center mb-4">
                    <span id="modalStatusBadge" class="badge fs-6 px-4 py-2"></span>
                </div>

                {{-- Employee Information Card --}}
                <div class="card border-primary mb-4">
                    <div class="card-header bg-primary-subtle">
                        <h6 class="mb-0 text-primary fw-semibold">
                            <i class="mdi mdi-account-circle-outline me-2"></i>Employee Information
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="border-start border-primary border-3 ps-3">
                                    <small class="text-muted d-block">Employee Name</small>
                                    <strong id="modalEmployeeName" class="text-dark">{{$application->getEmployee->full_name}}</strong>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="border-start border-info border-3 ps-3">
                                    <small class="text-muted d-block">Employee ID</small>
                                    <strong id="modalEmployeeId" class="text-dark">{{$application->getEmployee->applicant_id}}</strong>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="border-start border-success border-3 ps-3">
                                    <small class="text-muted d-block">System ID</small>
                                    <strong id="modalSystemId" class="text-dark">{{$application->getEmployee->system_id}}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Leave Details Card --}}
                <div class="card border-success mb-4">
                    <div class="card-header bg-success-subtle">
                        <h6 class="mb-0 text-success fw-semibold">
                            <i class="mdi mdi-calendar-clock me-2"></i>Leave Details
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="border-start border-success border-3 ps-3">
                                    <small class="text-muted d-block">Leave Plan</small>
                                    <strong id="modalLeavePlan" class="text-dark">{{$application->getPlan->name}}</strong>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="border-start border-warning border-3 ps-3">
                                    <small class="text-muted d-block">Number of Days</small>
                                    <strong id="modalDays" class="text-dark fs-5">{{$application->leave_count}}</strong>
                                    <small class="text-muted">days</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="border-start border-info border-3 ps-3">
                                    <small class="text-muted d-block">From Date</small>
                                    <strong id="modalFromDate" class="text-dark">{{$application->from}}</strong>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="border-start border-danger border-3 ps-3">
                                    <small class="text-muted d-block">To Date</small>
                                    <strong id="modalToDate" class="text-dark">{{$application->to}}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Reason Card --}}
                <div class="card border-warning mb-4">
                    <div class="card-header bg-warning-subtle">
                        <h6 class="mb-0 text-warning fw-semibold">
                            <i class="mdi mdi-text-box-outline me-2"></i>Reason for Leave
                        </h6>
                    </div>
                    <div class="card-body">
                        <p id="modalReason" class="mb-0 text-dark">{{$application->reason}}</p>
                    </div>
                </div>

                {{-- Application Info Card --}}
                <div class="card border-secondary">
                    <div class="card-header bg-secondary-subtle">
                        <h6 class="mb-0 text-secondary fw-semibold">
                            <i class="mdi mdi-information-outline me-2"></i>Application Information
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="border-start border-secondary border-3 ps-3">
                                    <small class="text-muted d-block">Application Date</small>
                                    <strong id="modalCreatedAt" class="text-dark">
                                        {{ \Carbon\Carbon::parse($application->created_at)->format('d-m-Y, h:i A') }}                                    </strong>
                                </div>
                            </div>
                            {{--<div class="col-md-6">
                                <div class="border-start border-primary border-3 ps-3">
                                    <small class="text-muted d-block">Application ID</small>
                                    <strong id="modalApplicationId" class="text-dark">-</strong>
                                </div>
                            </div>--}}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Modal Footer --}}
            <div class="modal-footer border-0 bg-light">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="mdi mdi-close-circle-outline me-1"></i>Close
                </button>
            </div>
        </div>
    </div>
</div>
