<!-- Attendance View Modal -->
<div class="modal fade" id="viewAttendanceModal{{ $record->id }}" tabindex="-1"
    aria-labelledby="viewAttendanceModalLabel{{ $record->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg">

            {{-- Modal Header --}}
            <div class="modal-header bg-gradient bg-primary text-white border-0 py-2">
                <h5 class="modal-title d-flex align-items-center" id="viewAttendanceModalLabel{{ $record->id }}">
                    <i class="bi bi-clock-history me-2"></i>
                    <span>Attendance Details</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            {{-- Modal Body --}}
            <div class="modal-body p-3" style="background-color: var(--bs-light-bg-subtle);">

                {{-- Employee Info Section --}}
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body p-3">
                        <div class="row align-items-center">
                            {{-- Employee Photo --}}
                            <div class="col-md-3 text-center mb-2 mb-md-0">
                                @if ($record->getEmployee && $record->getEmployee->photo_path)
                                    <img src="{{ asset('storage/' . $record->getEmployee->photo_path) }}"
                                        class="rounded-circle border border-primary border-2 shadow-sm"
                                        style="width: 100px; height: 100px; object-fit: cover;"
                                        alt="{{ $record->getEmployee->full_name }}">
                                @else
                                    <div class="rounded-circle bg-primary bg-gradient d-inline-flex align-items-center justify-content-center text-white shadow"
                                        style="width: 100px; height: 100px; font-size: 2rem; font-weight: bold;">
                                        {{ strtoupper(substr($record->getEmployee->full_name, 0, 2)) }}
                                    </div>
                                @endif
                                <div class="mt-2">
                                    <span class="badge bg-primary px-2 py-1 small">
                                        <i class="bi bi-person-badge me-1"></i>
                                        ID: {{ $record->getEmployee->applicant_id }}
                                    </span>
                                </div>
                            </div>

                            {{-- Employee Details --}}
                            <div class="col-md-9">
                                <h5 class="mb-2 fw-bold text-primary">
                                    <i class="bi bi-person-circle me-2"></i>{{ $record->getEmployee->full_name }}
                                </h5>
                                <div class="row g-2">
                                    <div class="col-sm-6">
                                        <div class="d-flex align-items-start">
                                            <i class="bi bi-envelope text-primary me-2 mt-1 small"></i>
                                            <div>
                                                <small class="text-muted d-block"
                                                    style="font-size: 0.7rem;">Email</small>
                                                <span class="fw-medium small">
                                                    {{ $record->getEmployee->work_email ?? ($record->getEmployee->personal_email ?? 'N/A') }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="d-flex align-items-start">
                                            <i class="bi bi-telephone text-primary me-2 mt-1 small"></i>
                                            <div>
                                                <small class="text-muted d-block"
                                                    style="font-size: 0.7rem;">Phone</small>
                                                <span class="fw-medium small">
                                                    {{ $record->getEmployee->work_mobile ?? ($record->getEmployee->personal_mobile ?? 'N/A') }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Attendance Information Grid --}}
                <div class="row g-2">

                    {{-- Clock In Section --}}
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-header bg-success bg-opacity-10 border-0 py-2">
                                <h6 class="mb-0 text-success fw-semibold small">
                                    <i class="bi bi-arrow-down-circle me-1"></i>Clock In Information
                                </h6>
                            </div>
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="bg-success bg-opacity-10 rounded-circle p-2 me-2">
                                        <i class="bi bi-clock-history text-success" style="font-size: 1.2rem;"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        @if ($record->in_time)
                                            <div class="mb-1">
                                                <small class="text-muted d-block"
                                                    style="font-size: 0.65rem;">Date</small>
                                                <div class="fw-semibold">
                                                    {{ \Carbon\Carbon::parse($record->in_time)->format('d M, Y') }}
                                                </div>
                                            </div>
                                            <div>
                                                <small class="text-muted d-block"
                                                    style="font-size: 0.65rem;">Time</small>
                                                <h6 class="mb-0 fw-bold text-success">
                                                    {{ \Carbon\Carbon::parse($record->in_time)->format('h:i A') }}</h6>
                                            </div>
                                        @else
                                            <small class="text-muted d-block" style="font-size: 0.7rem;">Clock
                                                In</small>
                                            <h6 class="mb-0 fw-bold">Not Clocked In</h6>
                                        @endif
                                    </div>
                                </div>
                                <div class="border-top pt-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">Status</small>
                                        <span
                                            class="badge rounded-pill
                                            @if ($record->in_status == 'On-Time') bg-success
                                            @elseif($record->in_status == 'Excessive-Late') bg-danger
                                            @elseif($record->in_status == 'Late') bg-warning text-dark
                                            @else bg-secondary @endif px-2 py-1 small">
                                            {{ $record->in_status ?? 'N/A' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Clock Out Section --}}
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-header bg-danger bg-opacity-10 border-0 py-2">
                                <h6 class="mb-0 text-danger fw-semibold small">
                                    <i class="bi bi-arrow-up-circle me-1"></i>Clock Out Information
                                </h6>
                            </div>
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="bg-danger bg-opacity-10 rounded-circle p-2 me-2">
                                        <i class="bi bi-clock text-danger" style="font-size: 1.2rem;"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        @if ($record->out_time)
                                            <div class="mb-1">
                                                <small class="text-muted d-block"
                                                    style="font-size: 0.65rem;">Date</small>
                                                <div class="fw-semibold">
                                                    {{ \Carbon\Carbon::parse($record->out_time)->format('d M, Y') }}
                                                </div>
                                            </div>
                                            <div>
                                                <small class="text-muted d-block"
                                                    style="font-size: 0.65rem;">Time</small>
                                                <h6 class="mb-0 fw-bold text-danger">
                                                    {{ \Carbon\Carbon::parse($record->out_time)->format('h:i A') }}
                                                </h6>
                                            </div>
                                        @else
                                            <small class="text-muted d-block" style="font-size: 0.7rem;">Clock
                                                Out</small>
                                            <h6 class="mb-0 fw-bold">Not Clocked Out</h6>
                                        @endif
                                    </div>
                                </div>
                                <div class="border-top pt-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">Status</small>
                                        <span
                                            class="badge rounded-pill
                                            @if ($record->out_status == 'On-Time') bg-success
                                            @elseif($record->out_status == 'Early-Exit') bg-danger
                                            @else bg-secondary @endif px-2 py-1 small">
                                            {{ $record->out_status ?? 'N/A' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Additional Details --}}
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-primary bg-opacity-10 border-0 py-2">
                                <h6 class="mb-0 text-primary fw-semibold small">
                                    <i class="bi bi-info-circle me-1"></i>Additional Information
                                </h6>
                            </div>
                            <div class="card-body p-3">
                                <div class="row g-2">

                                    {{-- Shift Type --}}
                                    <div class="col-md-4 col-lg-3">
                                        <div class="info-item">
                                            <div class="d-flex align-items-center mb-1">
                                                <i class="bi bi-calendar-check text-primary me-1 small"></i>
                                                <small class="text-muted text-uppercase fw-semibold"
                                                    style="font-size: 0.65rem;">Shift Type</small>
                                            </div>
                                            <p class="mb-0 fw-medium ps-3 small">{{ $record->shift_type }}</p>
                                        </div>
                                    </div>

                                    {{-- Working Time --}}
                                    <div class="col-md-4 col-lg-3">
                                        <div class="info-item">
                                            <div class="d-flex align-items-center mb-1">
                                                <i class="bi bi-hourglass-split text-primary me-1 small"></i>
                                                <small class="text-muted text-uppercase fw-semibold"
                                                    style="font-size: 0.65rem;">Working Time</small>
                                            </div>
                                            <p class="mb-0 fw-medium ps-3 small">
                                                {{ $record->working_time ? \App\HelperClass::getHoursByMinutes($record->working_time) : '0:00' }}
                                            </p>
                                        </div>
                                    </div>

                                    {{-- Overtime --}}
                                    <div class="col-md-4 col-lg-3">
                                        <div class="info-item">
                                            <div class="d-flex align-items-center mb-1">
                                                <i class="bi bi-clock-fill text-primary me-1 small"></i>
                                                <small class="text-muted text-uppercase fw-semibold"
                                                    style="font-size: 0.65rem;">Overtime</small>
                                            </div>
                                            <p class="mb-0 fw-medium ps-3 small">
                                                {{ $record->overtime ? \App\HelperClass::getHoursByMinutes($record->overtime) : '0:00' }}
                                            </p>
                                        </div>
                                    </div>

                                    {{-- Late Count --}}
                                    <div class="col-md-4 col-lg-3">
                                        <div class="info-item">
                                            <div class="d-flex align-items-center mb-1">
                                                <i class="bi bi-exclamation-triangle text-warning me-1 small"></i>
                                                <small class="text-muted text-uppercase fw-semibold"
                                                    style="font-size: 0.65rem;">Late Count</small>
                                            </div>
                                            <p class="mb-0 fw-medium ps-3 small">
                                                {{ $record->late_count ? \App\HelperClass::getHoursByMinutes($record->late_count) : '0:00' }}
                                            </p>
                                        </div>
                                    </div>

                                    {{-- Early Out Count --}}
                                    <div class="col-md-4 col-lg-3">
                                        <div class="info-item">
                                            <div class="d-flex align-items-center mb-1">
                                                <i class="bi bi-dash-circle text-danger me-1 small"></i>
                                                <small class="text-muted text-uppercase fw-semibold"
                                                    style="font-size: 0.65rem;">Early Out Count</small>
                                            </div>
                                            <p class="mb-0 fw-medium ps-3 small">
                                                {{ $record->early_out_count ? \App\HelperClass::getHoursByMinutes($record->early_out_count) : '0:00' }}
                                            </p>
                                        </div>
                                    </div>

                                    {{-- Workstation --}}
                                    <div class="col-md-4 col-lg-3">
                                        <div class="info-item">
                                            <div class="d-flex align-items-center mb-1">
                                                <i class="bi bi-geo-alt text-primary me-1 small"></i>
                                                <small class="text-muted text-uppercase fw-semibold"
                                                    style="font-size: 0.65rem;">Workstation</small>
                                            </div>
                                            <p class="mb-0 fw-medium ps-3 small">{{ $record->workstation ?? 'N/A' }}
                                            </p>
                                        </div>
                                    </div>

                                    {{-- Attendance Status --}}
                                    <div class="col-md-4 col-lg-3">
                                        <div class="info-item">
                                            <div class="d-flex align-items-center mb-1">
                                                <i class="bi bi-check-circle text-success me-1 small"></i>
                                                <small class="text-muted text-uppercase fw-semibold"
                                                    style="font-size: 0.65rem;">Status</small>
                                            </div>
                                            <p class="mb-0 ps-3">
                                                <span
                                                    class="badge rounded-pill
                                                    @if ($record->attendance_status == 'Present') bg-success
                                                    @elseif($record->attendance_status == 'Absent') bg-danger
                                                    @else bg-secondary @endif px-2 py-1 small">
                                                    <i
                                                        class="bi bi-{{ $record->attendance_status == 'Present' ? 'check-circle' : 'x-circle' }} me-1"></i>
                                                    {{ $record->attendance_status }}
                                                </span>
                                            </p>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Shift Details (if available) --}}
                    @if ($record->getShift)
                        <div class="col-12">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-info bg-opacity-10 border-0 py-2">
                                    <h6 class="mb-0 text-info fw-semibold small">
                                        <i class="bi bi-calendar2-week me-1"></i>Shift Details
                                    </h6>
                                </div>
                                <div class="card-body p-3">
                                    <div class="row g-2">
                                        <div class="col-md-4">
                                            <small class="text-muted d-block" style="font-size: 0.7rem;">Shift
                                                Name</small>
                                            <p class="mb-0 fw-medium small">{{ $record->getShift->name ?? 'N/A' }}</p>
                                        </div>
                                        <div class="col-md-4">
                                            <small class="text-muted d-block" style="font-size: 0.7rem;">Clock In
                                                Time</small>
                                            <p class="mb-0 fw-medium small">
                                                {{ $record->getShift->clock_in_time ? \Carbon\Carbon::parse($record->getShift->clock_in_time)->format('h:i A') : 'N/A' }}
                                            </p>
                                        </div>
                                        <div class="col-md-4">
                                            <small class="text-muted d-block" style="font-size: 0.7rem;">Clock Out
                                                Time</small>
                                            <p class="mb-0 fw-medium small">
                                                {{ $record->getShift->clock_out_time ? \Carbon\Carbon::parse($record->getShift->clock_out_time)->format('h:i A') : 'N/A' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
            </div>

            {{-- Modal Footer --}}
            <div class="modal-footer border-0 bg-light py-2">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-2"></i>Close
                </button>
                <button type="button" class="btn btn-primary"
                    onclick="window.open('{{ route('attendance.print-detail', $record->id) }}', '_blank')">
                    <i class="bi bi-printer me-2"></i>Print
                </button>
            </div>

        </div>
    </div>
</div>

{{-- Print Styles for Modal - A4 Professional Report --}}
<style>
    @media print {

        /* A4 Paper Setup for Modal Print */
        @page {
            size: A4;
            margin: 15mm 10mm;
        }

        /* Hide everything except the modal being printed */
        body * {
            visibility: hidden;
        }

        /* Show only the modal content */
        #viewAttendanceModal{{ $record->id }},
        #viewAttendanceModal{{ $record->id }} * {
            visibility: visible;
        }

        /* Position modal for print */
        #viewAttendanceModal{{ $record->id }} {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }

        /* Hide modal overlay and backdrop */
        .modal-backdrop,
        .modal.fade {
            display: none !important;
        }

        .modal-dialog {
            max-width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        .modal-content {
            border: none !important;
            box-shadow: none !important;
        }

        /* Hide buttons and interactive elements */
        .btn,
        .btn-close,
        button,
        .modal-footer {
            display: none !important;
        }

        /* Reset all colors to black, white, gray */
        * {
            background-color: white !important;
            color: black !important;
            border-color: #666 !important;
            box-shadow: none !important;
        }

        /* Modal header */
        .modal-header {
            background-color: #f0f0f0 !important;
            border-bottom: 3px solid #333 !important;
            padding: 10px !important;
        }

        .modal-title {
            font-size: 16pt !important;
            font-weight: bold !important;
            color: black !important;
        }

        /* Modal body */
        .modal-body {
            padding: 10px !important;
            background-color: white !important;
        }

        /* Cards in modal */
        .card {
            border: 1px solid #ccc !important;
            page-break-inside: avoid;
            margin-bottom: 8px !important;
            background-color: white !important;
        }

        .card-header {
            background-color: #e8e8e8 !important;
            border-bottom: 2px solid #666 !important;
            padding: 6px 8px !important;
            font-size: 10pt !important;
            font-weight: bold !important;
        }

        .card-body {
            padding: 8px !important;
            font-size: 9pt !important;
        }

        /* Employee photo */
        img,
        .rounded-circle {
            border: 2px solid #333 !important;
            background-color: white !important;
        }

        /* Employee info section */
        h5,
        h6 {
            font-size: 11pt !important;
            font-weight: bold !important;
            color: black !important;
        }

        /* Clock in/out sections */
        .bg-success,
        .text-success {
            background-color: #e8e8e8 !important;
            color: black !important;
        }

        .bg-danger,
        .text-danger {
            background-color: #d0d0d0 !important;
            color: black !important;
        }

        .bg-primary,
        .text-primary {
            background-color: #e8e8e8 !important;
            color: black !important;
        }

        .bg-info,
        .text-info {
            background-color: #e8e8e8 !important;
            color: black !important;
        }

        .bg-warning,
        .text-warning {
            background-color: #f0f0f0 !important;
            color: #333 !important;
        }

        /* Badges */
        .badge {
            border: 1px solid #666 !important;
            background-color: white !important;
            color: black !important;
            padding: 3px 8px !important;
            font-size: 8pt !important;
        }

        /* Info items */
        .info-item {
            background-color: #f9f9f9 !important;
            border: 1px solid #ddd !important;
            padding: 6px !important;
            margin-bottom: 4px !important;
        }

        /* Text styles */
        .text-muted {
            color: #666 !important;
        }

        small {
            font-size: 8pt !important;
            color: #666 !important;
        }

        .fw-bold,
        .fw-semibold {
            font-weight: bold !important;
            color: black !important;
        }

        /* Hide icons */
        .bi,
        i[class*="bi-"],
        svg {
            display: none !important;
        }

        /* Grid adjustments */
        .row {
            margin: 0 !important;
        }

        .col-md-3,
        .col-md-4,
        .col-md-6,
        .col-md-9,
        .col-sm-6,
        .col-lg-3 {
            padding: 4px !important;
        }

        /* Opacity resets */
        .bg-opacity-10 {
            opacity: 1 !important;
        }

        /* Border adjustments */
        .border-top {
            border-top: 1px solid #ccc !important;
        }
    }
</style>
