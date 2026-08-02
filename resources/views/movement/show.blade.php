@extends('structure.master')

@section('content')
    {{-- Back button and action buttons --}}
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <a href="{{ route('movement.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i style="height: 12px; width: 12px" data-feather="arrow-left"></i> Back to List
                </a>
            </div>
        </div>
    </div>

    @php
        $isEmployee = auth()->user()->user_type === \App\Enums\UserType::Employee;
    @endphp

    {{-- Employee Information Card --}}
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="mdi mdi-account-circle"></i> Employee Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 mb-3 text-center">
                            {!! \App\HelperClass::generateAvatar(
                                $movement->getEmployee->photo_path ?? null,
                                $movement->getEmployee->full_name ?? 'N/A',
                                120,
                                '#974063',
                                'border border-3 border-primary',
                                $movement->employee_id,
                            ) !!}
                        </div>

                        <div class="col-md-4 mb-2">
                            <strong>Employee Name:</strong>
                            <a href="{{ route('employee.profile.general_informations', $movement->employee_id) }}"
                                class="ms-2 text-decoration-none">
                                {{ $movement->getEmployee->full_name ?? 'N/A' }}
                            </a>
                        </div>
                        <div class="col-md-4 mb-2">
                            <strong>Employee ID:</strong>
                            <span class="ms-2">{{ $movement->getEmployee->applicant_id ?? 'N/A' }}</span>
                        </div>
                        <div class="col-md-4 mb-2">
                            <strong>System ID:</strong>
                            <span class="ms-2">{{ $movement->getEmployee->system_id ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Travel Logistics & Duration Card --}}
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="mdi mdi-map-marker-path"></i> Travel Logistics & Timeline
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small d-block">From Date & Time</label>
                            <div class="fw-semibold text-dark">
                                <i class="bi bi-calendar-check text-success me-1"></i>
                                {{ \Carbon\Carbon::parse($movement->from_date)->format('l, d F Y, h:i A') }}
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small d-block">To Date & Time</label>
                            <div class="fw-semibold text-dark">
                                <i class="bi bi-calendar-x text-danger me-1"></i>
                                {{ \Carbon\Carbon::parse($movement->to_date)->format('l, d F Y, h:i A') }}
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small d-block">Total Duration</label>
                            <div class="fw-semibold text-dark">
                                <i class="bi bi-clock-history text-info me-1"></i>
                                {{ $movement->total_days }} {{ $movement->total_days > 1 ? 'Days' : 'Day' }}
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small d-block">Overall Calculated Distance</label>
                            <div class="fw-semibold text-dark">
                                <i class="bi bi-speedometer2 text-warning me-1"></i>
                                {{ number_format($movement->distance, 2) }} KM
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Travel Routes Breakdown Card --}}
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="mdi mdi-routes"></i> Travel Routes Breakdown
                    </h5>
                </div>
                <div class="card-body">
                    <div class="timeline-route">
                        @foreach($movement->details as $index => $detail)
                            <div class="border rounded p-3 mb-3 shadow-sm bg-light-subtle" style="border-color: var(--primary-color) !important;">
                                <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-2">
                                    <span class="fw-bold text-dark"><i class="bi bi-tag-fill text-info me-1"></i>Route #{{ $index + 1 }}</span>
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
                </div>
            </div>
        </div>
    </div>

    {{-- Allowances Section --}}
    @if(!$isEmployee)
        @if($movement->status == 'pending')
            <div class="row">
                <div class="col-12">
                    <div class="card border-warning mb-4 shadow-sm bg-warning bg-opacity-10 text-warning-emphasis">
                        <div class="card-body d-flex align-items-center">
                            <i class="bi bi-exclamation-triangle-fill fs-4 me-3 text-warning"></i>
                            <div>
                                <h6 class="mb-1 fw-bold">Allowances Configuration Locked</h6>
                                <p class="mb-0 small">Please accept/approve the travel movement workflow first. Allowances can be calculated and saved immediately after approval.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            {{-- Approved/Completed Status Allowance Form --}}
            <div class="row">
                <div class="col-12">
                    <form action="{{ route('movement.save_allowances', $movement->id) }}" method="POST" id="allowanceForm">
                        @csrf
                        @method('PUT')
                        <div class="card border-success mb-4 shadow-sm">
                            <div class="card-header bg-success bg-opacity-10 d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0 fw-semibold text-success">
                                    <i class="bi bi-wallet2 me-2"></i>Allowance Setup & Calculation
                                </h5>
                                <span class="badge bg-success text-white">Workflow Approved</span>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <!-- TA Plan Section -->
                                    <div class="col-md-6 border-end">
                                        <h6 class="text-success mb-2 fw-bold text-sm"><i class="bi bi-cash-coin me-1"></i> Travel Allowance (TA)</h6>
                                        <div class="mb-3">
                                            <label class="form-label small fw-semibold text-muted mb-1">TA Plan</label>
                                            <select name="ta_plan_id" id="ta_plan_select" class="form-select form-select-sm">
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
                                            <label class="form-label small fw-semibold text-muted mb-1">Total TA Amount</label>
                                            <input type="number" step="0.01" min="0" name="total_ta" id="total_ta" class="form-control form-control-sm"
                                                   value="{{ old('total_ta', $movement->total_ta ?? '0.00') }}" placeholder="Total TA Amount">
                                        </div>
                                    </div>

                                    <!-- DA Plan Section -->
                                    <div class="col-md-6">
                                        <h6 class="text-warning mb-2 fw-bold text-sm"><i class="bi bi-wallet me-1"></i> Daily Allowance (DA)</h6>
                                        <div class="mb-3">
                                            <label class="form-label small fw-semibold text-muted mb-1">DA Plan</label>
                                            <select name="da_plan_id" id="da_plan_select" class="form-select form-select-sm">
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
                                            <label class="form-label small fw-semibold text-muted mb-1">Total DA Amount</label>
                                            <input type="number" step="0.01" min="0" name="total_da" id="total_da" class="form-control form-control-sm"
                                                   value="{{ old('total_da', $movement->total_da ?? '0.00') }}" placeholder="Total DA Amount">
                                        </div>
                                    </div>

                                    <!-- Grand Total -->
                                    <div class="col-12 mt-3">
                                        <div class="card bg-success text-white border-0 shadow-sm">
                                            <div class="card-body d-flex justify-content-between align-items-center py-2 px-3">
                                                <span class="fw-bold"><i class="bi bi-calculator me-2"></i>Grand Total Allowance (TA + DA):</span>
                                                <h3 class="mb-0 fw-bold text-white">৳<span id="grand_total_display">{{ number_format($movement->total_allowance, 2) }}</span></h3>
                                                <input type="hidden" name="total_allowance" id="total_allowance" value="{{ $movement->total_allowance }}">
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
                </div>
            </div>
        @endif
    @else
        {{-- Employee Read-only Allowances Details --}}
        @if($movement->total_allowance > 0 || $movement->ta_plan_id || $movement->da_plan_id)
            <div class="row">
                <div class="col-12">
                    <div class="card border-success mb-4 shadow-sm">
                        <div class="card-header bg-success bg-opacity-10">
                            <h5 class="card-title mb-0 fw-semibold text-success">
                                <i class="bi bi-wallet2 me-2"></i>Allowance Breakdown Details
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-4">
                                {{-- TA --}}
                                <div class="col-md-6">
                                    <div class="card bg-light border-0 h-100 shadow-none">
                                        <div class="card-body py-3">
                                            <h6 class="text-success mb-2 fw-semibold"><i class="bi bi-cash-coin me-2"></i>Travel Allowance (TA)</h6>
                                            @if($movement->ta_plan_id)
                                                <small class="text-muted d-block">Plan Name:</small>
                                                <div class="fw-semibold">{{ $movement->getTaPlan->name ?? 'N/A' }}</div>
                                                <small class="text-muted d-block mt-1">Rate per KM:</small>
                                                <div class="fw-semibold">৳{{ number_format($movement->getTaPlan->remuneration ?? 0, 2) }}</div>
                                            @else
                                                <div class="text-muted small">Direct Input (No Plan Selected)</div>
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
                                            @if($movement->da_plan_id)
                                                <small class="text-muted d-block">Plan Name:</small>
                                                <div class="fw-semibold">{{ $movement->getDaPlan->name ?? 'N/A' }}</div>
                                                <small class="text-muted d-block mt-1">Rate per Day:</small>
                                                <div class="fw-semibold">৳{{ number_format($movement->getDaPlan->remuneration ?? 0, 2) }}</div>
                                            @else
                                                <div class="text-muted small">Direct Input (No Plan Selected)</div>
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
                </div>
            </div>
        @else
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-secondary d-flex align-items-center border-0 py-2 shadow-sm mb-4" role="alert">
                        <i class="bi bi-info-circle me-2 fs-5 text-secondary"></i>
                        <div class="small">
                            <strong>Allowances Breakdown:</strong> Pending calculations by HR.
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endif

    {{-- Status Card --}}
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="mdi mdi-information"></i> Status
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-4 mb-2">
                            <label class="text-muted small">Approval Status</label>
                            <div>
                                @if ($movement->status == 'pending')
                                    <span class="badge bg-warning text-dark fs-6 px-3 py-2">Pending Approval</span>
                                @elseif($movement->status == 'approved')
                                    <span class="badge bg-success fs-6 px-3 py-2">Approved</span>
                                @elseif($movement->status == 'rejected')
                                    <span class="badge bg-danger fs-6 px-3 py-2">Rejected</span>
                                @else
                                    <span class="badge bg-info fs-6 px-3 py-2">{{ ucfirst($movement->status) }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="text-muted small">Payment Status</label>
                            <div>
                                @if ($movement->payment_status == 'paid')
                                    <span class="badge bg-success fs-6 px-3 py-2">Paid</span>
                                @else
                                    <span class="badge bg-secondary fs-6 px-3 py-2">Unpaid</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="text-muted small">Assigned Date</label>
                            <div class="fw-semibold">
                                {{ \Carbon\Carbon::parse($movement->created_at)->format('d M Y') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Workflow History & Approval Form --}}
    @include('approval_engine.workflow_history', ['approvable' => $movement])
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            const distance = parseFloat("{{ $movement->distance }}") || 0;
            const days = parseFloat("{{ $movement->total_days }}") || 0;

            // TA Plan Rate Calculation
            $('#ta_plan_select').on('change', function () {
                const taRate = parseFloat($(this).find('option:selected').data('rate')) || 0;
                const calculatedTa = distance * taRate;
                $('#total_ta').val(calculatedTa.toFixed(2)).trigger('input');
            });

            // DA Plan Rate Calculation
            $('#da_plan_select').on('change', function () {
                const daRate = parseFloat($(this).find('option:selected').data('rate')) || 0;
                const calculatedDa = days * daRate;
                $('#total_da').val(calculatedDa.toFixed(2)).trigger('input');
            });

            // Summation
            $('#total_ta, #total_da').on('input change', function () {
                const totalTa = parseFloat($('#total_ta').val()) || 0;
                const totalDa = parseFloat($('#total_da').val()) || 0;
                const totalAllowance = totalTa + totalDa;

                $('#grand_total_display').text(totalAllowance.toFixed(2));
                $('#total_allowance').val(totalAllowance.toFixed(2));
            });

            // Submit allowances via Axios
            $('#allowanceForm').on('submit', function (e) {
                e.preventDefault();
                const form = $(this);
                const submitBtn = form.find('button[type="submit"]');
                const originalHtml = submitBtn.html();

                submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Saving...');

                axios.post(form.attr('action'), form.serialize())
                .then(response => {
                    if (response.data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.data.message,
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({ icon: 'error', title: 'Error', text: response.data.message });
                        submitBtn.prop('disabled', false).html(originalHtml);
                    }
                })
                .catch(error => {
                    submitBtn.prop('disabled', false).html(originalHtml);
                    const errMsg = error.response && error.response.data && error.response.data.message
                        ? error.response.data.message
                        : 'An unexpected error occurred.';
                    Swal.fire({ icon: 'error', title: 'Error', text: errMsg });
                });
            });

            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        });
    </script>
@endpush
