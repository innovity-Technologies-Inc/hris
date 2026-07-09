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

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded">
                <div class="card-header">
                    <h5 class="card-title mb-0">Career Movement Request Details: {{ $transfer->employee->full_name }}</h5>
                </div>
                <div class="card-body">
                <div class="row">
                    <!-- Current Office Info -->
                    <div class="col-md-6 border-end">
                        <h6 class="text-muted border-bottom pb-2 mb-3">Current Office Info</h6>
                        <div class="info-item mb-2">
                            <label class="text-muted small d-block">Company</label>
                            <span class="fw-medium">{{ $transfer->currentCompany->name ?? 'N/A' }}</span>
                        </div>
                        <div class="info-item mb-2">
                            <label class="text-muted small d-block">Business Unit</label>
                            <span class="fw-medium">{{ $transfer->currentBusinessUnit->name ?? 'N/A' }}</span>
                        </div>
                        <div class="info-item mb-2">
                            <label class="text-muted small d-block">Division / Department</label>
                            <span class="fw-medium">{{ $transfer->currentDivision->name ?? 'N/A' }} / {{ $transfer->currentDepartment->department_name ?? 'N/A' }}</span>
                        </div>
                        <div class="info-item mb-2">
                            <label class="text-muted small d-block">Section</label>
                            <span class="fw-medium">{{ $transfer->currentSection->name ?? 'N/A' }}</span>
                        </div>
                    </div>

                    <!-- Requested Office Info -->
                    <div class="col-md-6 ps-md-4">
                        <h6 class="text-primary border-bottom border-primary-20 pb-2 mb-3">Requested Office Info</h6>
                        <div class="info-item mb-2">
                            <label class="text-muted small d-block">Company</label>
                            <span class="fw-medium">{{ $transfer->requestedCompany->name ?? 'N/A' }}</span>
                        </div>
                        <div class="info-item mb-2">
                            <label class="text-muted small d-block">Business Unit</label>
                            <span class="fw-medium">{{ $transfer->requestedBusinessUnit->name ?? 'N/A' }}</span>
                        </div>
                        <div class="info-item mb-2">
                            <label class="text-muted small d-block">Division / Department</label>
                            <span class="fw-medium">{{ $transfer->requestedDivision->name ?? 'N/A' }} / {{ $transfer->requestedDepartment->department_name ?? 'N/A' }}</span>
                        </div>
                        <div class="info-item mb-2">
                            <label class="text-muted small d-block">Section</label>
                            <span class="fw-medium">{{ $transfer->requestedSection->name ?? 'N/A' }}</span>
                        </div>
                        <div class="info-item mb-2">
                            <label class="text-muted small d-block">Movement Type</label>
                            <span class="badge bg-dark fs-6">{{ $transfer->movementType->name ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>

                @if($transfer->remarks)
                <div class="mt-4 p-3 bg-light rounded">
                    <label class="text-muted small d-block">Employee Remarks</label>
                    <p class="mb-0">{{ $transfer->remarks }}</p>
                </div>
                @endif

                @if($transfer->attachments && $transfer->attachments->count() > 0)
                <div class="mt-4 p-3 bg-light rounded">
                    <label class="text-muted small d-block mb-2">Attachments</label>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($transfer->attachments as $attachment)
                            <a href="{{ asset('storage/' . $attachment->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                <i class="bi bi-file-earmark-arrow-down me-1"></i>
                                {{ $attachment->file_name }}
                            </a>
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="mt-4 row g-3">
                    <div class="col-md-6">
                        <div class="p-2 border rounded bg-light">
                            <label class="text-muted small d-block mb-1">Applied By</label>
                            <span class="">
                                <i data-feather="user" class="me-1" style="width: 14px;"></i>
                                {{ $transfer->creator->name }} on {{ $transfer->created_at->format('d M Y, h:i A') }}
                            </span>
                        </div>
                    </div>
                    @if($transfer->status === 'completed' && $transfer->completer)
                    <div class="col-md-6">
                        <div class="p-2 border border-success-subtle rounded bg-success-light">
                            <label class="text-success small d-block mb-1">Finalized By</label>
                            <span class="">
                                <i data-feather="check-circle" class="me-1" style="width: 14px;"></i>
                                {{ $transfer->completer->name }} on {{ \Carbon\Carbon::parse($transfer->completed_at)->format('d M Y, h:i A') }}
                            </span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

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
