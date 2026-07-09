@extends('structure.master')

@section('content')
    {{-- Back button and action buttons --}}
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <a href="{{ route('transfer.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i style="height: 12px; width: 12px" data-feather="arrow-left"></i> Back to List
                </a>
                @if($transfer->status === 'approved' && auth()->user()->can('transfers.edit'))
                <button class="btn btn-success btn-sm" id="btnComplete">
                    <i style="height: 12px; width: 12px" data-feather="check-circle"></i> Mark as Complete
                </button>
                @endif
            </div>
        </div>
    </div>

    {{-- Employee Information Card --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-person-circle me-1"></i> Employee Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        {{-- Profile Image --}}
                        <div class="col-md-2 mb-3 mb-md-0 text-center">
                            {!! \App\HelperClass::generateAvatar(
                                $transfer->employee->photo_path ?? null,
                                $transfer->employee->full_name ?? 'N/A',
                                100,
                                '#974063',
                                'border border-3 border-primary',
                                $transfer->employee_id,
                            ) !!}
                        </div>

                        <div class="col-md-10">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <strong>Employee Name:</strong>
                                    <a href="{{ route('employee.profile.general_informations', $transfer->employee_id) }}"
                                        class="ms-2 text-decoration-none fw-semibold">
                                        {{ $transfer->employee->full_name ?? 'N/A' }}
                                    </a>
                                </div>
                                <div class="col-md-4">
                                    <strong>Employee ID:</strong>
                                    <span class="ms-2 text-muted fw-semibold">{{ $transfer->employee->applicant_id ?? 'N/A' }}</span>
                                </div>
                                <div class="col-md-4">
                                    <strong>System ID:</strong>
                                    <span class="ms-2 text-muted fw-semibold">{{ $transfer->employee->system_id ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Placement Details Card --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-arrow-left-right me-1"></i> Placement Details
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-4">
                        <!-- Current Placement -->
                        <div class="col-md-6 border-end">
                            <h6 class="fw-bold text-muted border-bottom pb-2 mb-3">Current Placement</h6>
                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <label class="text-muted small d-block">Company</label>
                                    <span class="fw-semibold text-dark">{{ $transfer->currentCompany->name ?? 'N/A' }}</span>
                                </div>
                                <div class="col-sm-6">
                                    <label class="text-muted small d-block">Business Unit / Branch</label>
                                    <span class="fw-semibold text-dark">{{ $transfer->currentBusinessUnit->name ?? 'N/A' }}</span>
                                </div>
                                <div class="col-sm-6">
                                    <label class="text-muted small d-block">Division</label>
                                    <span class="fw-semibold text-dark">{{ $transfer->currentDivision->name ?? 'N/A' }}</span>
                                </div>
                                <div class="col-sm-6">
                                    <label class="text-muted small d-block">Department</label>
                                    <span class="fw-semibold text-dark">{{ $transfer->currentDepartment->department_name ?? 'N/A' }}</span>
                                </div>
                                <div class="col-sm-6">
                                    <label class="text-muted small d-block">Section</label>
                                    <span class="fw-semibold text-dark">{{ $transfer->currentSection->name ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Requested Placement -->
                        <div class="col-md-6 ps-md-4">
                            <h6 class="fw-bold text-primary border-bottom pb-2 mb-3">Requested Placement</h6>
                            <div class="row g-3">
                                <div class="col-sm-6">
                                    <label class="text-muted small d-block">Company</label>
                                    <span class="fw-semibold text-dark">{{ $transfer->requestedCompany->name ?? 'N/A' }}</span>
                                </div>
                                <div class="col-sm-6">
                                    <label class="text-muted small d-block">Business Unit / Branch</label>
                                    <span class="fw-semibold text-dark">{{ $transfer->requestedBusinessUnit->name ?? 'N/A' }}</span>
                                </div>
                                <div class="col-sm-6">
                                    <label class="text-muted small d-block">Division</label>
                                    <span class="fw-semibold text-dark">{{ $transfer->requestedDivision->name ?? 'N/A' }}</span>
                                </div>
                                <div class="col-sm-6">
                                    <label class="text-muted small d-block">Department</label>
                                    <span class="fw-semibold text-dark">{{ $transfer->requestedDepartment->department_name ?? 'N/A' }}</span>
                                </div>
                                <div class="col-sm-6">
                                    <label class="text-muted small d-block">Section</label>
                                    <span class="fw-semibold text-dark">{{ $transfer->requestedSection->name ?? 'N/A' }}</span>
                                </div>
                                <div class="col-sm-6">
                                    <label class="text-muted small d-block">Movement Type</label>
                                    <div>
                                        <span class="badge bg-dark px-3 py-2 mt-1">
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
    </div>

    {{-- Application Details & Remarks Card --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-info-circle me-1"></i> Application Details
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="text-muted small d-block">Status</label>
                            <div>
                                <span class="badge @if($transfer->status === 'pending') bg-warning text-dark @elseif($transfer->status === 'approved') bg-info @elseif($transfer->status === 'completed') bg-success @else bg-danger @endif px-3 py-2 fs-6 mt-1">
                                    {{ ucfirst($transfer->status) }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="text-muted small d-block">Applied By</label>
                            <span class="fw-semibold text-dark">
                                <i data-feather="user" style="width: 14px;" class="me-1"></i>
                                {{ $transfer->creator->name }} on {{ $transfer->created_at->format('d M Y, h:i A') }}
                            </span>
                        </div>
                        @if($transfer->status === 'completed' && $transfer->completer)
                        <div class="col-md-4">
                            <label class="text-success small d-block">Finalized By</label>
                            <span class="fw-semibold text-success">
                                <i data-feather="check-circle" style="width: 14px;" class="me-1"></i>
                                {{ $transfer->completer->name }} on {{ \Carbon\Carbon::parse($transfer->completed_at)->format('d M Y, h:i A') }}
                            </span>
                        </div>
                        @endif

                        @if($transfer->remarks)
                        <div class="col-12 mt-3">
                            <div class="p-3 bg-light rounded border-start border-4 border-warning">
                                <label class="text-muted small d-block fw-bold mb-1">Reason / Remarks</label>
                                <p class="mb-0 text-dark">{{ $transfer->remarks }}</p>
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
            <div class="card border-0 shadow-sm rounded">
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
    const transferId = '{{ $transfer->id }}';

    const btnComplete = document.getElementById('btnComplete');
    if (btnComplete) {
        btnComplete.addEventListener('click', function() {
            Swal.fire({
                title: 'Finalize Career Movement?',
                text: "This will update the employee's office info and mark the transfer as complete.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Finalize'
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.post(`{{ url('transfer/api/complete') }}/${transferId}`)
                        .then(res => {
                            Swal.fire('Completed!', res.data.message, 'success').then(() => location.reload());
                        })
                        .catch(err => Swal.fire('Error', 'Failed to complete transfer.', 'error'));
                }
            });
        });
    }
});
</script>
@endpush
