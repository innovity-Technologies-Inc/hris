@php
    // Fetch the latest approval request (even if completed or rejected, to show history)
    $approvalRequest = $approvable->approvalRequests()->latest()->first();
    
    $canApprove = false;
    $pendingStep = null;
    $historySteps = [];

    if ($approvalRequest) {
        // Get all step requests for history timeline
        $historySteps = $approvalRequest->stepRequests()
            ->with(['workflowStep', 'approver'])
            ->orderBy('created_at', 'asc')
            ->get();

        if ($approvalRequest->status->value === 'pending') {
            $pendingSteps = $historySteps->where('status.value', 'pending');
            $resolver = app(\Innovity\ApprovalEngine\Contracts\ApproverResolverInterface::class);
            
            foreach ($pendingSteps as $step) {
                $authorizedUserIds = $resolver->resolve($step->workflowStep->required_user_type, $approvable);
                if (in_array(auth()->id(), $authorizedUserIds)) {
                    $canApprove = true;
                    $pendingStep = $step;
                    break;
                }
            }
        }
    }
@endphp

@if($approvalRequest)
<div class="row mt-4">
    <div class="col-12">
        <div class="card border-primary shadow-sm">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0 text-white">
                    <i class="mdi mdi-timeline-check me-2"></i> Approval Workflow
                </h5>
                <span class="badge bg-light text-dark fs-6">{{ ucfirst($approvalRequest->status->value) }}</span>
            </div>
            <div class="card-body">
                
                {{-- Approval Timeline / History --}}
                <h6 class="fw-bold mb-3">Workflow History</h6>
                <div class="timeline" style="border-left: 2px solid #e9ecef; padding-left: 20px; margin-left: 10px;">
                    @foreach($historySteps as $step)
                        <div class="timeline-item mb-4 position-relative">
                            <span class="position-absolute" style="left: -29px; top: 0; background: white; border-radius: 50%;">
                                @if($step->status->value === 'approved')
                                    <i class="mdi mdi-check-circle text-success fs-4"></i>
                                @elseif($step->status->value === 'rejected')
                                    <i class="mdi mdi-close-circle text-danger fs-4"></i>
                                @else
                                    <i class="mdi mdi-clock-outline text-warning fs-4"></i>
                                @endif
                            </span>
                            
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <strong class="text-dark">Step {{ $step->workflowStep->step_order ?? $loop->iteration }} - {{ ucfirst(str_replace('_', ' ', $step->workflowStep->required_user_type)) }}</strong>
                                <small class="text-muted">{{ $step->action_taken_at ? $step->action_taken_at->format('d M Y, h:i A') : 'Pending' }}</small>
                            </div>
                            
                            <div class="bg-light p-3 rounded mt-2 border">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Status:</strong> 
                                            <span class="badge {{ $step->status->value === 'approved' ? 'bg-success' : ($step->status->value === 'rejected' ? 'bg-danger' : 'bg-warning') }}">
                                                {{ ucfirst($step->status->value) }}
                                            </span>
                                        </p>
                                        @if($step->approver)
                                            <p class="mb-0"><strong>Resolved By:</strong> {{ $step->approver->full_name ?? $step->approver->name }}</p>
                                        @endif
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Remarks:</strong>
                                        <p class="mb-0 text-muted fst-italic">
                                            {{ $step->comments ?: 'No remarks provided.' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Approval Action Form --}}
                @if($canApprove && $pendingStep)
                    <hr class="my-4">
                    <div class="bg-primary bg-opacity-10 border border-primary border-opacity-25 rounded p-4">
                        <h5 class="fw-bold text-primary mb-3"><i class="mdi mdi-gavel"></i> Your Action Required</h5>
                        <p class="text-muted mb-3">You are authorized to approve or reject this request as a <strong>{{ ucfirst(str_replace('_', ' ', $pendingStep->workflowStep->required_user_type)) }} Authority</strong>.</p>
                        
                        <form action="{{ route('approval.action', $pendingStep->id) }}" method="POST" id="approvalForm">
                            @csrf
                            <input type="hidden" name="action" id="actionInput" value="">
                            <div class="mb-3">
                                <label for="comments" class="form-label fw-bold">Remarks / Comments <span class="text-danger">*</span></label>
                                <textarea name="comments" id="comments" rows="3" class="form-control" placeholder="Please provide your remarks..." required></textarea>
                            </div>
                            <div class="d-flex gap-3">
                                <button type="button" id="btnApprove" class="btn btn-success px-4">
                                    <i class="mdi mdi-check-circle"></i> Approve Request
                                </button>
                                <button type="button" id="btnReject" class="btn btn-danger px-4">
                                    <i class="mdi mdi-close-circle"></i> Reject Request
                                </button>
                            </div>
                        </form>
                        
                        <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                const form = document.getElementById('approvalForm');
                                const actionInput = document.getElementById('actionInput');

                                function submitApprovalAction(actionType, confirmMessage) {
                                    if (!form.checkValidity()) {
                                        form.reportValidity();
                                        return;
                                    }
                                    
                                    Swal.fire({
                                        title: confirmMessage,
                                        text: 'You won\'t be able to revert!',
                                        icon: 'warning',
                                        showCancelButton: true,
                                        confirmButtonColor: '#3085d6',
                                        cancelButtonColor: '#d33',
                                        confirmButtonText: 'Confirm',
                                        showLoaderOnConfirm: true,
                                        preConfirm: () => {
                                            actionInput.value = actionType;
                                            return axios.post(form.action, new FormData(form))
                                                .then(response => {
                                                    return response.data;
                                                })
                                                .catch(error => {
                                                    Swal.showValidationMessage(
                                                        `Request failed: ${error.response?.data?.message || error.message}`
                                                    );
                                                });
                                        },
                                        allowOutsideClick: () => !Swal.isLoading()
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            Swal.fire({
                                                title: 'Success!',
                                                text: result.value.message,
                                                icon: 'success'
                                            }).then(() => {
                                                window.location.reload();
                                            });
                                        }
                                    });
                                }

                                document.getElementById('btnApprove').addEventListener('click', function () {
                                    submitApprovalAction('approve', 'Are you sure you want to approve this request?');
                                });

                                document.getElementById('btnReject').addEventListener('click', function () {
                                    submitApprovalAction('reject', 'Are you sure you want to reject this request?');
                                });
                            });
                        </script>
                    </div>
                @endif
                
            </div>
        </div>
    </div>
</div>
@endif
