@extends('structure.master')

@section('content')
    {{-- Back button --}}
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <a href="{{ route('transfer.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i style="height: 12px; width: 12px" data-feather="arrow-left"></i> Back to List
                </a>
            </div>
        </div>
    </div>

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
                        {{-- Profile Image --}}
                        <div class="col-md-12 mb-3 text-center">
                            {!! \App\HelperClass::generateAvatar(
                                $transfer->employee->photo_path ?? null,
                                $transfer->employee->full_name ?? 'N/A',
                                120,
                                '#974063',
                                'border border-3 border-primary',
                                $transfer->employee_id,
                            ) !!}
                        </div>

                        <div class="col-md-6 mb-2">
                            <strong>Employee Name:</strong>
                            <a href="{{ route('employee.profile.general_informations', $transfer->employee_id) }}"
                                class="ms-2 text-decoration-none">
                                {{ $transfer->employee->full_name ?? 'N/A' }}
                            </a>
                        </div>
                        <div class="col-md-6 mb-2">
                            <strong>Employee ID:</strong>
                            <span class="ms-2">{{ $transfer->employee->applicant_id ?? 'N/A' }}</span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <strong>System ID:</strong>
                            <span class="ms-2">{{ $transfer->employee->system_id ?? 'N/A' }}</span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <strong>Department:</strong>
                            <span class="ms-2">{{ $transfer->employee->officeInfo?->getCurrentDepartment?->department_name ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Career Movement Details Card --}}
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="mdi mdi-swap-horizontal-bold"></i> Career Movement Details
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Company -->
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Current Company</label>
                            <div class="fw-semibold">
                                {{ $transfer->currentCompany->name ?? 'N/A' }}
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Requested Company</label>
                            <div class="fw-semibold text-success">
                                <i class="mdi mdi-arrow-right text-muted"></i>
                                {{ $transfer->requestedCompany->name ?? 'N/A' }}
                            </div>
                        </div>

                        <!-- Business Unit -->
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Current Business Unit</label>
                            <div class="fw-semibold">
                                {{ $transfer->currentBusinessUnit->name ?? 'N/A' }}
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Requested Business Unit</label>
                            <div class="fw-semibold text-success">
                                <i class="mdi mdi-arrow-right text-muted"></i>
                                {{ $transfer->requestedBusinessUnit->name ?? 'N/A' }}
                            </div>
                        </div>

                        <!-- Division -->
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Current Division</label>
                            <div class="fw-semibold">
                                {{ $transfer->currentDivision->name ?? 'N/A' }}
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Requested Division</label>
                            <div class="fw-semibold text-success">
                                <i class="mdi mdi-arrow-right text-muted"></i>
                                {{ $transfer->requestedDivision->name ?? 'N/A' }}
                            </div>
                        </div>

                        <!-- Department -->
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Current Department</label>
                            <div class="fw-semibold">
                                {{ $transfer->currentDepartment->department_name ?? 'N/A' }}
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Requested Department</label>
                            <div class="fw-semibold text-success">
                                <i class="mdi mdi-arrow-right text-muted"></i>
                                {{ $transfer->requestedDepartment->department_name ?? 'N/A' }}
                            </div>
                        </div>

                        <!-- Section -->
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Current Section</label>
                            <div class="fw-semibold">
                                {{ $transfer->currentSection->name ?? 'N/A' }}
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Requested Section</label>
                            <div class="fw-semibold text-success">
                                <i class="mdi mdi-arrow-right text-muted"></i>
                                {{ $transfer->requestedSection->name ?? 'N/A' }}
                            </div>
                        </div>

                        <!-- Movement Type -->
                        <div class="col-md-12 mt-2">
                            <div class="border rounded p-3 bg-light">
                                <label class="text-muted small">Movement Type</label>
                                <div class="fw-semibold">
                                    <span class="badge bg-dark fs-6 mt-1">
                                        {{ $transfer->movementType->name ?? 'N/A' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Effective Period Card --}}
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="mdi mdi-calendar-range"></i> Effective Period
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label class="text-muted small">Effective From</label>
                            <div class="fw-semibold">
                                {{ $transfer->effective_from ? \Carbon\Carbon::parse($transfer->effective_from)->format('d M Y') : 'N/A' }}
                            </div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="text-muted small">Effective To</label>
                            <div class="fw-semibold">
                                {{ $transfer->effective_to ? \Carbon\Carbon::parse($transfer->effective_to)->format('d M Y') : 'Indefinite' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
                            <label class="text-muted small">Current Status</label>
                            <div>
                                <span class="badge @if($transfer->status === 'pending') bg-warning text-dark @elseif($transfer->status === 'approved') bg-info @elseif($transfer->status === 'completed') bg-success @else bg-danger @endif fs-6 px-3 py-2 mt-1">
                                    {{ ucfirst($transfer->status) }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="text-muted small">Applied By</label>
                            <div class="fw-semibold text-dark">
                                <i data-feather="user" style="width: 14px;" class="me-1"></i>
                                {{ $transfer->creator->name }} on {{ $transfer->created_at->format('d M Y, h:i A') }}
                            </div>
                        </div>
                        @if($transfer->status === 'completed' && $transfer->completer)
                        <div class="col-md-4 mb-2">
                            <label class="text-success small">Finalized By</label>
                            <div class="fw-semibold text-success">
                                <i data-feather="check-circle" style="width: 14px;" class="me-1"></i>
                                {{ $transfer->completer->name }} on {{ \Carbon\Carbon::parse($transfer->completed_at)->format('d M Y, h:i A') }}
                            </div>
                        </div>
                        @endif

                        @if($transfer->remarks)
                        <div class="col-12 mt-3">
                            <div class="alert alert-primary mb-0">
                                <strong>Remarks:</strong>
                                {{ $transfer->remarks }}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Attachments Card --}}
    @if($transfer->attachments && $transfer->attachments->count() > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i style="height: 16px; width: 16px; margin-right: 5px" data-feather="paperclip"></i> Attachments
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($transfer->attachments as $attachment)
                            <a href="{{ asset('storage/' . $attachment->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                <i style="height: 12px; width: 12px" data-feather="download"></i>
                                {{ $attachment->file_name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Workflow History Timeline & Approval Action --}}
    @include('approval_engine.workflow_history', ['approvable' => $transfer])
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
$(document).ready(function() {
    // Left empty or for future enhancement, as complete action is now processed in bulk via adjustment route
});
</script>
@endpush
