@extends('structure.master')

@section('content')
<div class="row g-4">
    <!-- Comparison Section -->
    <div class="col-md-12">
        <div class="card glass-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0 text-white">Career Movement Request Details: {{ $transfer->employee->full_name }}</h5>
                <div>
                    @if($transfer->status === 'approved' && auth()->user()->can('transfers.edit'))
                    <button class="btn btn-sm btn-success" id="btnComplete">
                        <i data-feather="check-circle" class="me-1"></i> Mark as Complete
                    </button>
                    @endif
                    <a href="{{ route('transfer.index') }}" class="btn btn-sm btn-light">Back to Logs</a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Current Office Info -->
                    <div class="col-md-6 border-end border-white-10">
                        <h6 class="text-white-50 border-bottom border-white-10 pb-2 mb-3">Current Office Info</h6>
                        <div class="info-item mb-2">
                            <label class="text-white-50 small d-block">Company</label>
                            <span class="text-white">{{ $transfer->currentCompany->name ?? 'N/A' }}</span>
                        </div>
                        <div class="info-item mb-2">
                            <label class="text-white-50 small d-block">Business Unit</label>
                            <span class="text-white">{{ $transfer->currentBusinessUnit->name ?? 'N/A' }}</span>
                        </div>
                        <div class="info-item mb-2">
                            <label class="text-white-50 small d-block">Division / Department</label>
                            <span class="text-white">{{ $transfer->currentDivision->name ?? 'N/A' }} / {{ $transfer->currentDepartment->department_name ?? 'N/A' }}</span>
                        </div>
                        <div class="info-item mb-2">
                            <label class="text-white-50 small d-block">Section</label>
                            <span class="text-white">{{ $transfer->currentSection->name ?? 'N/A' }}</span>
                        </div>
                    </div>

                    <!-- Requested Office Info -->
                    <div class="col-md-6 ps-md-4">
                        <h6 class="text-primary border-bottom border-primary-20 pb-2 mb-3">Requested Office Info</h6>
                        <div class="info-item mb-2">
                            <label class="text-white-50 small d-block">Company</label>
                            <span class="text-white">{{ $transfer->requestedCompany->name ?? 'N/A' }}</span>
                        </div>
                        <div class="info-item mb-2">
                            <label class="text-white-50 small d-block">Business Unit</label>
                            <span class="text-white">{{ $transfer->requestedBusinessUnit->name ?? 'N/A' }}</span>
                        </div>
                        <div class="info-item mb-2">
                            <label class="text-white-50 small d-block">Division / Department</label>
                            <span class="text-white">{{ $transfer->requestedDivision->name ?? 'N/A' }} / {{ $transfer->requestedDepartment->department_name ?? 'N/A' }}</span>
                        </div>
                        <div class="info-item mb-2">
                            <label class="text-white-50 small d-block">Section</label>
                            <span class="text-white">{{ $transfer->requestedSection->name ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>

                @if($transfer->remarks)
                <div class="mt-4 p-3 bg-white-5 rounded">
                    <label class="text-white-50 small d-block">Employee Remarks</label>
                    <p class="text-white mb-0">{{ $transfer->remarks }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Approval Workflow Section -->
    <div class="col-md-12">
        <div class="card glass-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0 text-white">Approval Workflow</h5>
                @if($transfer->status === 'pending' && $transfer->approval_count_required === 0 && auth()->user()->can('transfers.approve'))
                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#setupApproversModal">
                    <i data-feather="users" class="me-1"></i> Setup Approvers
                </button>
                @endif
            </div>
            <div class="card-body">
                @if($transfer->approval_count_required > 0)
                <div class="table-responsive">
                    <table class="table table-sm text-white">
                        <thead>
                            <tr>
                                <th>Approver</th>
                                <th>Status</th>
                                <th>Remarks</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($transfer->approvals as $approval)
                            <tr>
                                <td>{{ $approval->approver->name }}</td>
                                <td>
                                    @if($approval->status === 'approved')
                                        <span class="text-success"><i data-feather="check"></i> Approved</span>
                                    @elseif($approval->status === 'rejected')
                                        <span class="text-danger"><i data-feather="x"></i> Rejected</span>
                                    @else
                                        <span class="text-warning">Pending</span>
                                    @endif
                                </td>
                                <td>{{ $approval->remarks ?? '-' }}</td>
                                <td>{{ $approval->approved_at ? \Carbon\Carbon::parse($approval->approved_at)->format('d M Y') : '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- My Approval Action -->
                @php
                    $myApproval = $transfer->approvals->where('approver_id', auth()->id())->where('status', 'pending')->first();
                @endphp
                @if($myApproval)
                <div class="mt-4 p-3 border border-primary-20 rounded bg-primary-10">
                    <h6 class="text-white mb-3">Your Approval Required</h6>
                    <button class="btn btn-success" onclick="approveCareer Movement()">Approve Request</button>
                </div>
                @endif
                @else
                <div class="text-center py-4 text-white-50">
                    <p>No approval workflow configured yet.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Setup Approvers Modal -->
<div class="modal fade" id="setupApproversModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content glass-card">
            <div class="modal-header">
                <h5 class="modal-title text-white">Setup Career Movement Approvers</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="text-white small mb-1">Search & Select Authorities</label>
                        <div class="input-group">
                            <input type="text" id="authoritySearch" class="form-control" placeholder="Search by name or office...">
                            <button class="btn btn-primary" id="btnSearchAuthorities">Search</button>
                        </div>
                    </div>
                    
                    <div class="col-md-12">
                        <div id="authoritiesList" class="list-group mt-2" style="max-height: 300px; overflow-y: auto;">
                            <!-- Dynamically populated -->
                        </div>
                    </div>

                    <div class="col-md-12 mt-4">
                        <h6 class="text-white small">Selected Approvers</h6>
                        <div id="selectedApprovers" class="d-flex flex-wrap gap-2 py-2">
                            <!-- Chips -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="btnSaveApprovers">Assign & Notify</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const transferId = '{{ $transfer->id }}';
    const btnSearch = document.getElementById('btnSearchAuthorities');
    const inputSearch = document.getElementById('authoritySearch');
    const listContainer = document.getElementById('authoritiesList');
    const selectedContainer = document.getElementById('selectedApprovers');
    const btnSaveApprovers = document.getElementById('btnSaveApprovers');
    const btnComplete = document.getElementById('btnComplete');

    let selectedApproverIds = [];

    // Search Authorities
    btnSearch.addEventListener('click', function() {
        const query = inputSearch.value;
        if (!query) return;

        axios.get(`{{ route('transfer.api.search_authorities') }}?name=${query}`)
            .then(res => renderAuthorities(res.data.data))
            .catch(err => console.error(err));
    });

    function renderAuthorities(users) {
        listContainer.innerHTML = '';
        users.forEach(user => {
            const item = `
                <button class="list-group-item list-group-item-action bg-white-5 text-white border-white-10 d-flex justify-content-between" onclick="window.selectApprover(${user.id}, '${user.name}')">
                    <span>${user.name} <small class="text-white-50">(${user.user_type})</small></span>
                    <i data-feather="plus" style="width: 14px;"></i>
                </button>
            `;
            listContainer.insertAdjacentHTML('beforeend', item);
        });
        feather.replace();
    }

    window.selectApprover = function(id, name) {
        if (selectedApproverIds.includes(id)) return;
        selectedApproverIds.push(id);
        renderSelected();
    };

    function renderSelected() {
        selectedContainer.innerHTML = '';
        selectedApproverIds.forEach(id => {
            const chip = `
                <span class="badge bg-info px-3 py-2 d-flex align-items-center gap-2">
                    Approver ID: ${id}
                    <i data-feather="x" style="width: 14px; cursor: pointer;" onclick="window.removeApprover(${id})"></i>
                </span>
            `;
            selectedContainer.insertAdjacentHTML('beforeend', chip);
        });
        feather.replace();
    }

    window.removeApprover = function(id) {
        selectedApproverIds = selectedApproverIds.filter(sid => sid !== id);
        renderSelected();
    };

    // Save Approvers
    btnSaveApprovers.addEventListener('click', function() {
        if (selectedApproverIds.length === 0) {
            Swal.fire('Error', 'Please select at least one approver.', 'error');
            return;
        }

        axios.post(`{{ url('transfer/api/set-approvers') }}/${transferId}`, { approver_ids: selectedApproverIds })
            .then(res => {
                Swal.fire('Success', res.data.message, 'success').then(() => location.reload());
            })
            .catch(err => Swal.fire('Error', 'Failed to save approvers.', 'error'));
    });

    // Approve Action
    window.approveCareer Movement = function() {
        Swal.fire({
            title: 'Approve Career Movement?',
            text: "Are you sure you want to approve this request?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, Approve'
        }).then((result) => {
            if (result.isConfirmed) {
                axios.post(`{{ url('transfer/api/approve') }}/${transferId}`)
                    .then(res => {
                        Swal.fire('Approved!', res.data.message, 'success').then(() => location.reload());
                    })
                    .catch(err => Swal.fire('Error', 'Failed to approve.', 'error'));
            }
        });
    };

    // Completion Action
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
