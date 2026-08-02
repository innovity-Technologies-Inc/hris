{{-- View Employee Travel Movement Details Modal --}}
<div class="modal fade" id="viewTravelMovementModal{{ $movement->id }}" tabindex="-1"
    aria-labelledby="viewTravelMovementModalLabel{{ $movement->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg">
            {{-- Modal Header --}}
            <div class="modal-header bg-primary text-white border-0">
                <h5 class="modal-title fw-semibold" id="viewTravelMovementModalLabel{{ $movement->id }}">
                    <i class="bi bi-geo-alt-fill me-2"></i>Employee Travel Movement Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            {{-- Modal Body --}}
            <div class="modal-body p-4">
                @php $isEmployee = auth()->user()->user_type === \App\Enums\UserType::Employee; @endphp

                <span class="distance-value d-none" data-distance="{{ $movement->distance }}"></span>
                <span class="days-value d-none" data-days="{{ $movement->total_days }}"></span>

                {{-- Status Badges --}}
                @if(!$isEmployee)
                <div class="text-center mb-4 d-flex justify-content-center gap-3">
                    @if ($movement->status == 'pending')
                        <span class="badge bg-warning text-dark fs-6 px-4 py-2">Pending Approval</span>
                    @elseif($movement->status == 'approved')
                        <span class="badge bg-success fs-6 px-4 py-2">Approved</span>
                    @elseif($movement->status == 'rejected')
                        <span class="badge bg-danger fs-6 px-4 py-2">Rejected</span>
                    @else
                        <span class="badge bg-info fs-6 px-4 py-2">Completed</span>
                    @endif

                    @if ($movement->payment_status == 'paid')
                        <span class="badge bg-success fs-6 px-4 py-2">Paid</span>
                    @else
                        <span class="badge bg-secondary fs-6 px-4 py-2">Unpaid</span>
                    @endif
                </div>
                @endif

                {{-- Employee Information Card --}}
                <div class="card border-primary mb-4 shadow-sm">
                    <div class="card-header bg-primary bg-opacity-10">
                        <h6 class="mb-0 text-primary fw-semibold">
                            <i class="bi bi-person-badge me-2"></i>Employee Information
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="border-start border-primary border-3 ps-3">
                                    <small class="text-muted d-block text-uppercase small fw-bold">Employee Name</small>
                                    <strong class="text-dark">{{ $movement->getEmployee->full_name }}</strong>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="border-start border-info border-3 ps-3">
                                    <small class="text-muted d-block text-uppercase small fw-bold">Emp ID</small>
                                    <strong class="text-dark">{{ $movement->getEmployee->applicant_id }}</strong>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="border-start border-success border-3 ps-3">
                                    <small class="text-muted d-block text-uppercase small fw-bold">System ID</small>
                                    <strong class="text-dark">{{ $movement->getEmployee->system_id }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Travel Movement Timeline & Details --}}
                <div class="card border-info mb-4 shadow-sm">
                    <div class="card-header bg-info bg-opacity-10">
                        <h6 class="mb-0 text-info fw-semibold">
                            <i class="bi bi-calendar-event me-2"></i>Timeline & Duration
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
                                        <div class="text-muted text-sm">
                                            {{ \Carbon\Carbon::parse($movement->from_date)->format('h:i A') }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="bi bi-calendar-x text-danger me-2 fs-5"></i>
                                        <strong class="text-muted">To Date & Time</strong>
                                    </div>
                                    <div class="ps-4 border-start border-danger border-2 ms-2">
                                        <div class="fw-semibold text-dark">
                                            {{ \Carbon\Carbon::parse($movement->to_date)->format('l, d F Y') }}
                                        </div>
                                        <div class="text-muted text-sm">
                                            {{ \Carbon\Carbon::parse($movement->to_date)->format('h:i A') }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="alert alert-info d-flex align-items-center border-0 py-2 mb-0" role="alert">
                                    <i class="bi bi-clock-history me-2 fs-5"></i>
                                    <div>
                                        <strong>Total Duration:</strong> {{ $movement->total_days }}
                                        {{ $movement->total_days > 1 ? 'Days' : 'Day' }}
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="alert alert-warning d-flex align-items-center border-0 py-2 mb-0" role="alert">
                                    <i class="bi bi-speedometer2 me-2 fs-5"></i>
                                    <div>
                                        <strong>Overall Calculated Distance:</strong>
                                        {{ number_format($movement->distance, 2) }} KM
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Travel Route Legs Details --}}
                <div class="card border-secondary mb-4 shadow-sm">
                    <div class="card-header bg-secondary bg-opacity-10">
                        <h6 class="mb-0 text-secondary fw-semibold">
                            <i class="bi bi-pin-map-fill me-2"></i>Route Legs/Destinations Breakdown
                        </h6>
                    </div>
                    <div class="card-body">
                        @if($movement->details->isEmpty())
                            {{-- Legacy movements support --}}
                            <div class="border rounded p-3 bg-light">
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <small class="text-muted d-block fw-bold text-uppercase">Source</small>
                                        <span class="text-dark text-sm">{{ $movement->source_address }}</span>
                                    </div>
                                    <div class="col-md-6">
                                        <small class="text-muted d-block fw-bold text-uppercase">Destination</small>
                                        <span class="text-dark text-sm">{{ $movement->destination_address }}</span>
                                    </div>
                                    <div class="col-md-8 mt-2">
                                        <small class="text-muted d-block fw-bold text-uppercase">Reason</small>
                                        <span class="text-dark text-sm">{{ $movement->reason }}</span>
                                    </div>
                                    <div class="col-md-4 mt-2 text-end">
                                        <span class="badge bg-secondary">{{ number_format($movement->distance, 2) }} KM</span>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="timeline-route">
                                @foreach($movement->details as $index => $detail)
                                    <div class="border border-info rounded p-3 mb-3 shadow-sm bg-light-subtle">
                                        <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-2">
                                            <span class="fw-bold text-dark"><i class="bi bi-tag-fill text-info me-1"></i>Leg #{{ $index + 1 }}</span>
                                            <span class="badge bg-primary fs-7">{{ number_format($detail->distance, 2) }} KM</span>
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <small class="text-muted d-block fw-semibold text-uppercase" style="font-size: 0.75rem;">Source</small>
                                                <span class="text-dark text-sm">{{ $detail->source_address }}</span>
                                            </div>
                                            <div class="col-md-6">
                                                <small class="text-muted d-block fw-semibold text-uppercase" style="font-size: 0.75rem;">Destination</small>
                                                <span class="text-dark text-sm">{{ $detail->destination_address }}</span>
                                            </div>
                                            <div class="col-md-8">
                                                <small class="text-muted d-block fw-semibold text-uppercase" style="font-size: 0.75rem;">Reason</small>
                                                <span class="text-dark text-sm">{{ $detail->reason }}</span>
                                            </div>
                                            <div class="col-md-4 text-md-end d-flex align-items-end justify-content-md-end">
                                                @if($detail->attachment_path)
                                                    <a href="{{ \Illuminate\Support\Facades\Storage::url($detail->attachment_path) }}" target="_blank" class="btn btn-outline-secondary btn-sm py-1 px-2">
                                                        <i class="bi bi-file-earmark-arrow-down-fill me-1"></i>View Attachment
                                                    </a>
                                                @else
                                                    <span class="text-muted small">No attachment</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Allowances Section --}}
                @if(!$isEmployee)
                    @if($movement->status == 'pending')
                        {{-- Pending Status Allowance message --}}
                        <div class="card border-warning mb-4 shadow-sm bg-warning bg-opacity-10 text-warning-emphasis">
                            <div class="card-body d-flex align-items-center">
                                <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>
                                <div>
                                    <h6 class="mb-1 fw-bold">Allowances Configuration Locked</h6>
                                    <p class="mb-0 small">Please accept/approve the travel movement workflow first. Allowances can be calculated and saved immediately after approval.</p>
                                </div>
                            </div>
                        </div>
                    @else
                        {{-- Approved/Completed Status Allowance Form --}}
                        <form action="{{ route('movement.save_allowances', $movement->id) }}" method="POST" id="allowanceForm{{ $movement->id }}">
                            @csrf
                            @method('PUT')
                            <div class="card border-success mb-4 shadow-sm">
                                <div class="card-header bg-success bg-opacity-10 d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0 text-success fw-semibold">
                                        <i class="bi bi-wallet2 me-2"></i>Allowance Setup & Calculation
                                    </h6>
                                    <span class="badge bg-success text-white">Workflow Accepted</span>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <!-- TA Plan Section -->
                                        <div class="col-md-6 border-end">
                                            <h6 class="text-success mb-2 fw-bold text-sm"><i class="bi bi-cash-coin me-1"></i> Travel Allowance (TA)</h6>
                                            <div class="mb-3">
                                                <label class="form-label small fw-semibold text-muted mb-1">TA Plan</label>
                                                <select name="ta_plan_id" class="form-select form-select-sm ta-plan-select" data-movement-id="{{ $movement->id }}">
                                                    <option value="" data-rate="0">Select TA Plan</option>
                                                    @foreach($taPlans as $plan)
                                                        <option value="{{ $plan->id }}" data-rate="{{ $plan->remuneration }}"
                                                            {{ old('ta_plan_id', $movement->ta_plan_id) == $plan->id ? 'selected' : '' }}>
                                                            {{ $plan->name }} (৳{{ $plan->remuneration }}/KM)
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label small fw-semibold text-muted mb-1">Custom TA Amount (Overrides Plan)</label>
                                                <input type="number" step="0.01" min="0" name="custom_ta" class="form-control form-control-sm custom-ta-input" data-movement-id="{{ $movement->id }}"
                                                       value="{{ old('custom_ta', $movement->custom_ta) }}" placeholder="Enter custom TA amount">
                                            </div>
                                            <div class="bg-light p-2 rounded d-flex justify-content-between align-items-center">
                                                <span class="text-muted small">Total TA Amount:</span>
                                                <strong class="text-success" id="calc_ta_display{{ $movement->id }}">৳{{ number_format($movement->total_ta, 2) }}</strong>
                                                <input type="hidden" name="total_ta" id="total_ta{{ $movement->id }}" value="{{ $movement->total_ta }}">
                                            </div>
                                        </div>

                                        <!-- DA Plan Section -->
                                        <div class="col-md-6">
                                            <h6 class="text-warning mb-2 fw-bold text-sm"><i class="bi bi-wallet me-1"></i> Daily Allowance (DA)</h6>
                                            <div class="mb-3">
                                                <label class="form-label small fw-semibold text-muted mb-1">DA Plan</label>
                                                <select name="da_plan_id" class="form-select form-select-sm da-plan-select" data-movement-id="{{ $movement->id }}">
                                                    <option value="" data-rate="0">Select DA Plan</option>
                                                    @foreach($daPlans as $plan)
                                                        <option value="{{ $plan->id }}" data-rate="{{ $plan->remuneration }}"
                                                            {{ old('da_plan_id', $movement->da_plan_id) == $plan->id ? 'selected' : '' }}>
                                                            {{ $plan->name }} (৳{{ $plan->remuneration }}/Day)
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label small fw-semibold text-muted mb-1">Custom DA Amount (Overrides Plan)</label>
                                                <input type="number" step="0.01" min="0" name="custom_da" class="form-control form-control-sm custom-da-input" data-movement-id="{{ $movement->id }}"
                                                       value="{{ old('custom_da', $movement->custom_da) }}" placeholder="Enter custom DA amount">
                                            </div>
                                            <div class="bg-light p-2 rounded d-flex justify-content-between align-items-center">
                                                <span class="text-muted small">Total DA Amount:</span>
                                                <strong class="text-warning" id="calc_da_display{{ $movement->id }}">৳{{ number_format($movement->total_da, 2) }}</strong>
                                                <input type="hidden" name="total_da" id="total_da{{ $movement->id }}" value="{{ $movement->total_da }}">
                                            </div>
                                        </div>

                                        <!-- Grand Total -->
                                        <div class="col-12 mt-3">
                                            <div class="card bg-success text-white border-0 shadow-sm">
                                                <div class="card-body d-flex justify-content-between align-items-center py-2">
                                                    <span class="fw-bold"><i class="bi bi-calculator me-2"></i>Grand Total Allowance Value (TA + DA):</span>
                                                    <h3 class="mb-0 fw-bold text-white" id="grand_total_display{{ $movement->id }}">৳{{ number_format($movement->total_allowance, 2) }}</h3>
                                                    <input type="hidden" name="total_allowance" id="total_allowance{{ $movement->id }}" value="{{ $movement->total_allowance }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12 text-end mt-2">
                                            <button type="submit" class="btn btn-success btn-sm px-4">
                                                <i class="bi bi-check-circle me-1"></i> Save/Update Allowances
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    @endif
                @else
                    {{-- Employee Read-only Allowances Details --}}
                    @if($movement->total_allowance > 0 || $movement->ta_plan_id || $movement->da_plan_id || $movement->custom_ta || $movement->custom_da)
                        <div class="card border-success mb-4 shadow-sm">
                            <div class="card-header bg-success bg-opacity-10">
                                <h6 class="mb-0 text-success fw-semibold">
                                    <i class="bi bi-wallet2 me-2"></i>Allowance Breakdown Details
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-4">
                                    {{-- TA --}}
                                    <div class="col-md-6">
                                        <div class="card bg-light border-0 h-100 shadow-none">
                                            <div class="card-body py-3">
                                                <h6 class="text-success mb-2 fw-semibold"><i class="bi bi-cash-coin me-2"></i>Travel Allowance (TA)</h6>
                                                @if($movement->custom_ta)
                                                    <small class="text-muted d-block">Custom Amount (Overridden):</small>
                                                    <div class="fw-bold text-dark">৳{{ number_format($movement->custom_ta, 2) }}</div>
                                                @else
                                                    <small class="text-muted d-block">Plan Name:</small>
                                                    <div class="fw-semibold">{{ $movement->getTaPlan->name ?? 'N/A' }}</div>
                                                    <small class="text-muted d-block mt-1">Rate per KM:</small>
                                                    <div class="fw-semibold">৳{{ number_format($movement->getTaPlan->remuneration ?? 0, 2) }}</div>
                                                    <small class="text-muted d-block mt-1">Calculated for {{ number_format($movement->distance, 2) }} KM:</small>
                                                @endif
                                                <hr class="my-2">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <strong class="text-muted">Total TA:</strong>
                                                    <strong class="text-success">৳{{ number_format($movement->total_ta, 2) }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- DA --}}
                                    <div class="col-md-6">
                                        <div class="card bg-light border-0 h-100 shadow-none">
                                            <div class="card-body py-3">
                                                <h6 class="text-warning mb-2 fw-semibold"><i class="bi bi-wallet me-2"></i>Daily Allowance (DA)</h6>
                                                @if($movement->custom_da)
                                                    <small class="text-muted d-block">Custom Amount (Overridden):</small>
                                                    <div class="fw-bold text-dark">৳{{ number_format($movement->custom_da, 2) }}</div>
                                                @else
                                                    <small class="text-muted d-block">Plan Name:</small>
                                                    <div class="fw-semibold">{{ $movement->getDaPlan->name ?? 'N/A' }}</div>
                                                    <small class="text-muted d-block mt-1">Rate per Day:</small>
                                                    <div class="fw-semibold">৳{{ number_format($movement->getDaPlan->remuneration ?? 0, 2) }}</div>
                                                    <small class="text-muted d-block mt-1">Calculated for {{ $movement->total_days }} days:</small>
                                                @endif
                                                <hr class="my-2">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <strong class="text-muted">Total DA:</strong>
                                                    <strong class="text-warning">৳{{ number_format($movement->total_da, 2) }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Grand Total --}}
                                    <div class="col-12">
                                        <div class="card bg-success text-white border-0 shadow-sm mb-0">
                                            <div class="card-body d-flex justify-content-between align-items-center py-2 px-3">
                                                <span class="fw-semibold"><i class="bi bi-calculator me-2"></i>Grand Total Allowance Approved:</span>
                                                <h4 class="mb-0 fw-bold text-white">৳{{ number_format($movement->total_allowance, 2) }}</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-secondary d-flex align-items-center border-0 py-2 shadow-sm mb-4" role="alert">
                            <i class="bi bi-info-circle me-2 fs-5 text-secondary"></i>
                            <div class="small">
                                <strong>Allowances Breakdown:</strong> Pending calculations by HR.
                            </div>
                        </div>
                    @endif
                @endif

                {{-- Submission Info --}}
                @if(!$isEmployee)
                <div class="row">
                    <div class="col-12">
                        <div class="text-muted small bg-light p-2 rounded d-inline-block">
                            <i class="bi bi-clock me-1"></i>
                            <strong>Submitted On:</strong>
                            {{ \Carbon\Carbon::parse($movement->created_at)->format('d F Y, h:i A') }}
                        </div>
                    </div>
                </div>
                @endif
            </div>

            {{-- Modal Footer --}}
            <div class="modal-footer border-top bg-light p-3">
                <button type="button" class="btn btn-outline-secondary px-4 rounded-3 text-sm py-1.5" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-2"></i>Close
                </button>
                
                <div class="ms-auto d-flex gap-2">
                    {{-- Edit Button --}}
                    @can('movement.edit')
                        @if($movement->status == 'pending' || !$isEmployee)
                        <a href="{{ route('movement.edit', $movement->id) }}" class="btn btn-primary px-4 rounded-3 shadow-sm text-sm py-1.5">
                            <i class="bi bi-pencil-square me-2"></i>Edit
                        </a>
                        @endif
                    @endcan

                    @if(!$isEmployee)
                        {{-- Change Status Dropdown --}}
                        @can('movement.hr-approve')
                        <div class="dropdown">
                            <button class="btn btn-info dropdown-toggle px-4 rounded-3 shadow-sm text-white text-sm py-1.5" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-arrow-left-right me-2"></i>Change Status
                            </button>
                            <ul class="dropdown-menu shadow border-0">
                                <li>
                                    <form action="{{ route('movement.change_status') }}" method="POST">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="id" value="{{ $movement->id }}">
                                        <input type="hidden" name="status" value="approved">
                                        <button type="submit" class="dropdown-item py-2"><i class="bi bi-check-circle text-success me-2"></i>Approve</button>
                                    </form>
                                </li>
                                <li>
                                    <form action="{{ route('movement.change_status') }}" method="POST">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="id" value="{{ $movement->id }}">
                                        <input type="hidden" name="status" value="rejected">
                                        <button type="submit" class="dropdown-item py-2"><i class="bi bi-x-circle text-danger me-2"></i>Reject</button>
                                    </form>
                                </li>
                                <li>
                                    <form action="{{ route('movement.change_status') }}" method="POST">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="id" value="{{ $movement->id }}">
                                        <input type="hidden" name="status" value="completed">
                                        <button type="submit" class="dropdown-item py-2"><i class="bi bi-flag text-info me-2"></i>Complete</button>
                                    </form>
                                </li>
                            </ul>
                        </div>

                        {{-- Change Payment Status Dropdown --}}
                        <div class="dropdown">
                            <button class="btn btn-success dropdown-toggle px-4 rounded-3 shadow-sm text-sm py-1.5" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-cash-coin me-2"></i>Payment Status
                            </button>
                            <ul class="dropdown-menu shadow border-0">
                                <li>
                                    <form action="{{ route('movement.change_payment_status') }}" method="POST">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="id" value="{{ $movement->id }}">
                                        <input type="hidden" name="payment_status" value="paid">
                                        <button type="submit" class="dropdown-item py-2"><i class="bi bi-credit-card text-success me-2"></i>Mark as Paid</button>
                                    </form>
                                </li>
                                <li>
                                    <form action="{{ route('movement.change_payment_status') }}" method="POST">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="id" value="{{ $movement->id }}">
                                        <input type="hidden" name="payment_status" value="unpaid">
                                        <button type="submit" class="dropdown-item py-2"><i class="bi bi-dash-circle text-secondary me-2"></i>Mark as Unpaid</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                        @endcan
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
