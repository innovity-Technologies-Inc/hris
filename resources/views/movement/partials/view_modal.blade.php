{{-- View Employee Movement Details Modal --}}
<div class="modal fade" id="viewMovementModal{{ $movement->id }}" tabindex="-1"
    aria-labelledby="viewMovementModalLabel{{ $movement->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg">
            {{-- Modal Header --}}
            <div class="modal-header bg-primary text-white border-0">
                <h5 class="modal-title fw-semibold" id="viewMovementModalLabel{{ $movement->id }}">
                    <i class="bi bi-geo-alt-fill me-2"></i>Employee Movement Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            {{-- Modal Body --}}
            <div class="modal-body p-4">
                {{-- Status Badge --}}
                <div class="text-center mb-4">
                    @if ($movement->status == 'pending')
                        <span class="badge bg-warning text-light fs-6 px-4 py-2">Pending Approval</span>
                    @elseif($movement->status == 'approved')
                        <span class="badge bg-success fs-6 px-4 py-2">Approved</span>
                    @elseif($movement->status == 'rejected')
                        <span class="badge bg-danger fs-6 px-4 py-2">Rejected</span>
                    @else
                        <span class="badge bg-info fs-6 px-4 py-2">Completed</span>
                    @endif
                </div>

                {{-- Employee Information Card --}}
                <div class="card border-primary mb-4">
                    <div class="card-header bg-primary-subtle">
                        <h6 class="mb-0 text-primary fw-semibold">
                            <i class="bi bi-person-badge me-2"></i>Employee Information
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="border-start border-primary border-3 ps-3">
                                    <small class="text-muted d-block">Employee Name</small>
                                    <strong class="text-dark">{{ $movement->getEmployee->full_name }}</strong>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="border-start border-info border-3 ps-3">
                                    <small class="text-muted d-block">Emp ID</small>
                                    <strong class="text-dark">{{ $movement->getEmployee->applicant_id }}</strong>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="border-start border-success border-3 ps-3">
                                    <small class="text-muted d-block">System ID</small>
                                    <strong class="text-dark">{{ $movement->getEmployee->system_id }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Movement Details Card --}}
                <div class="card border-info mb-4">
                    <div class="card-header bg-info-subtle">
                        <h6 class="mb-0 text-info fw-semibold">
                            <i class="bi bi-calendar-event me-2"></i>Movement Timeline & Location
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            {{-- Timeline --}}
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="bi bi-calendar-check text-success me-2 fs-5"></i>
                                        <strong class="text-muted">From Date & Time</strong>
                                    </div>
                                    <div class="ps-4 border-start border-success border-2 ms-2">
                                        <div class="fw-semibold text-dark">
                                            {{ \Carbon\Carbon::parse($movement->from_date)->format('l, d F Y') }}
                                        </div>
                                        <div class="text-muted">
                                            {{ \Carbon\Carbon::parse($movement->from_date)->format('h:i A') }}
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="bi bi-calendar-x text-danger me-2 fs-5"></i>
                                        <strong class="text-muted">To Date & Time</strong>
                                    </div>
                                    <div class="ps-4 border-start border-danger border-2 ms-2">
                                        <div class="fw-semibold text-dark">
                                            {{ \Carbon\Carbon::parse($movement->to_date)->format('l, d F Y') }}
                                        </div>
                                        <div class="text-muted">
                                            {{ \Carbon\Carbon::parse($movement->to_date)->format('h:i A') }}
                                        </div>
                                    </div>
                                </div>

                                <div class="alert alert-info d-flex align-items-center" role="alert">
                                    <i class="bi bi-clock-history me-2 fs-5"></i>
                                    <div>
                                        <strong>Total Duration:</strong> {{ $movement->total_days }}
                                        {{ $movement->total_days > 1 ? 'Days' : 'Day' }}
                                    </div>
                                </div>
                            </div>

                            {{-- Locations --}}
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="bi bi-pin-map text-info me-2 fs-5"></i>
                                        <strong class="text-muted">Source Address</strong>
                                    </div>
                                    <div class="ps-4 border-start border-info border-2 ms-2">
                                        <div class="text-dark">{{ $movement->source_address }}</div>
                                    </div>
                                </div>

                                <div class="text-center my-2">
                                    <i class="bi bi-arrow-down-circle text-primary fs-4"></i>
                                </div>

                                <div class="mb-3">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="bi bi-geo-alt text-warning me-2 fs-5"></i>
                                        <strong class="text-muted">Destination Address</strong>
                                    </div>
                                    <div class="ps-4 border-start border-warning border-2 ms-2">
                                        <div class="text-dark">{{ $movement->destination_address }}</div>
                                    </div>
                                </div>

                                <div class="alert alert-warning d-flex align-items-center" role="alert">
                                    <i class="bi bi-speedometer2 me-2 fs-5"></i>
                                    <div>
                                        <strong>Covered Distance:</strong>
                                        {{ number_format($movement->distance, 2) }} KM
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Allowance Details Card --}}
                <div class="card border-success mb-4">
                    <div class="card-header bg-success-subtle">
                        <h6 class="mb-0 text-success fw-semibold">
                            <i class="bi bi-wallet2 me-2"></i>Allowance Breakdown
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            {{-- TA Plan --}}
                            <div class="col-md-6">
                                <div class="card bg-light h-100">
                                    <div class="card-body">
                                        <h6 class="text-success mb-3">
                                            <i class="bi bi-cash-coin me-2"></i>Travel Allowance (TA)
                                        </h6>
                                        <div class="mb-2">
                                            <small class="text-muted">Plan Name:</small>
                                            <div class="fw-semibold">{{ $movement->getTaPlan->name }}</div>
                                        </div>
                                        <div class="mb-2">
                                            <small class="text-muted">Rate per KM:</small>
                                            <div class="fw-semibold">৳{{ number_format($movement->getTaPlan->remuneration, 2) }}</div>
                                        </div>
                                        <div class="mb-2">
                                            <small class="text-muted">Distance:</small>
                                            <div class="fw-semibold">
                                                {{ number_format($movement->distance, 2) }} KM</div>
                                        </div>
                                        <hr>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <strong class="text-muted">Total TA Amount:</strong>
                                            <h5 class="mb-0 text-success">
                                                ৳{{ number_format($movement->total_ta, 2) }}</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- DA Plan --}}
                            <div class="col-md-6">
                                <div class="card bg-light h-100">
                                    <div class="card-body">
                                        <h6 class="text-warning mb-3">
                                            <i class="bi bi-wallet me-2"></i>Daily Allowance (DA)
                                        </h6>
                                        <div class="mb-2">
                                            <small class="text-muted">Plan Name:</small>
                                            <div class="fw-semibold">{{ $movement->getDaPlan->name }}</div>
                                        </div>
                                        <div class="mb-2">
                                            <small class="text-muted">Rate per Day:</small>
                                            <div class="fw-semibold">৳{{ number_format($movement->getDaPlan->remuneration, 2) }}</div>
                                        </div>
                                        <div class="mb-2">
                                            <small class="text-muted">Total Days:</small>
                                            <div class="fw-semibold">{{ $movement->total_days }}
                                                {{ $movement->total_days > 1 ? 'Days' : 'Day' }}</div>
                                        </div>
                                        <hr>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <strong class="text-muted">DA Amount:</strong>
                                            <h5 class="mb-0 text-warning">
                                                ৳{{ number_format($movement->total_da, 2) }}</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Total Allowance --}}
                            <div class="col-12">
                                <div class="alert alert-success d-flex justify-content-between align-items-center mb-0"
                                    role="alert">
                                    <div>
                                        <i class="bi bi-calculator me-2 fs-5"></i>
                                        <strong class="fs-5">Total Allowance:</strong>
                                    </div>
                                    <h4 class="mb-0 fw-bold">৳{{ number_format($movement->total_allowance, 2) }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Reason Card --}}
                <div class="card border-secondary mb-4">
                    <div class="card-header bg-secondary-subtle">
                        <h6 class="mb-0 text-secondary fw-semibold">
                            <i class="bi bi-chat-left-text me-2"></i>Reason / Purpose of Movement
                        </h6>
                    </div>
                    <div class="card-body">
                        <p class="mb-0 text-dark">{{ $movement->reason }}</p>
                    </div>
                </div>

                {{-- Submission Info --}}
                <div class="row">
                    <div class="col-12">
                        <div class="text-muted small">
                            <i class="bi bi-clock me-1"></i>
                            <strong>Submitted On:</strong>
                            {{ \Carbon\Carbon::parse($movement->created_at)->format('d F Y, h:i A') }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Modal Footer --}}
            <div class="modal-footer border-top">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-2"></i>Close
                </button>
                <a href="{{ route('movement.edit', $movement->id) }}" class="btn btn-primary">
                    <i class="bi bi-pencil-square me-2"></i>Edit Movement
                </a>
            </div>
        </div>
    </div>
</div>

