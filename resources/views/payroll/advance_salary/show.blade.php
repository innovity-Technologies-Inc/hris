@extends('structure.master')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i data-feather="info" class="me-2 text-primary"></i>
                    Advance Salary Batch: <span class="text-primary">{{ $process->batch_id }}</span>
                </h5>
                <a href="{{ route('advance-salary.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i data-feather="arrow-left" class="me-1"></i> Back to List
                </a>
            </div>
            <div class="card-body p-4">
                {{-- Summary Header --}}
                <div class="row g-4 mb-4">
                    <div class="col-md-3">
                        <label class="text-muted small fw-semibold">Company</label>
                        <p class="fw-bold mb-0 text-dark">{{ $process->getCompany->name ?? 'N/A' }}</p>
                    </div>
                    <div class="col-md-3">
                        <label class="text-muted small fw-semibold">Month / Period</label>
                        <p class="fw-bold mb-0 text-dark">
                            {{ $process->salary_month ? \Carbon\Carbon::parse($process->salary_month)->format('F Y') : 'Custom Period' }}
                        </p>
                    </div>
                    <div class="col-md-3">
                        <label class="text-muted small fw-semibold">Approval Status</label>
                        <div>
                            @if ($process->approval_status == 'Approved')
                                <span class="badge text-bg-success">Approved</span>
                            @elseif($process->approval_status == 'Pending')
                                <span class="badge text-bg-warning">Pending</span>
                            @else
                                <span class="badge text-bg-danger">{{ $process->approval_status }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3 text-md-end">
                        <label class="text-muted small fw-semibold">Total Amount</label>
                        <p class="fw-bold mb-0 fs-4 text-success">{{ number_format($process->total_amount, 2) }}</p>
                    </div>
                </div>

                {{-- Individual Employees Table --}}
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" style="width: 50px;">#</th>
                                <th>Employee ID</th>
                                <th>Employee Name</th>
                                <th>Reason</th>
                                <th class="text-center">Deduction Month</th>
                                <th class="text-end">Advance Amount</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($process->advanceSalaries as $index => $item)
                            <tr>
                                <td class="text-center text-muted">{{ $index + 1 }}</td>
                                <td><span class="fw-semibold text-secondary">{{ $item->employee->system_id }}</span></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="ms-1">
                                            <div class="fw-bold">
                                                <a href="{{ route('employee.profile.general_informations', $item->employee->id) }}" class="text-dark hover-primary">
                                                    {{ $item->employee->full_name }}
                                                </a>
                                            </div>
                                            <div class="text-muted small">
                                                {{ $item->employee->officeInfo->getCurrentDesignation->name ?? 'N/A' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="text-muted small">{{ $item->reason ?? 'No reason provided' }}</span></td>
                                <td class="text-center">
                                    <span class="badge bg-light text-dark border">
                                        {{ \Carbon\Carbon::parse($item->deduction_month)->format('M Y') }}
                                    </span>
                                </td>
                                <td class="text-end fw-bold text-primary">
                                    {{ number_format($item->amount, 2) }}
                                </td>
                                <td class="text-center">
                                    @if ($item->status == 'deducted')
                                        <span class="badge bg-info-subtle text-info border border-info">Deducted</span>
                                    @elseif($item->status == 'approved')
                                        <span class="badge bg-success-subtle text-success border border-success">Approved</span>
                                    @elseif($item->status == 'pending')
                                        <span class="badge bg-warning-subtle text-warning border border-warning">Pending</span>
                                    @else
                                        <span class="badge bg-danger-subtle text-danger border border-danger">{{ ucfirst($item->status) }}</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-outline-info btn-sm view-item-details"
                                            data-name="{{ $item->employee->full_name }}"
                                            data-id="{{ $item->employee->system_id }}"
                                            data-amount="{{ number_format($item->amount, 2) }}"
                                            data-month="{{ \Carbon\Carbon::parse($item->deduction_month)->format('F Y') }}"
                                            data-reason="{{ $item->reason ?? 'N/A' }}"
                                            data-status="{{ ucfirst($item->status) }}"
                                            data-department="{{ $item->employee->officeInfo->getCurrentDepartment->name ?? 'N/A' }}"
                                            data-designation="{{ $item->employee->officeInfo->getCurrentDesignation->name ?? 'N/A' }}">
                                        <i data-feather="eye" style="width: 14px; height: 14px;"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Footer Info --}}
                <div class="mt-4 pt-3 border-top d-flex justify-content-between text-muted small">
                    <div>
                        Generated by: <strong>{{ $process->generatedBy->name ?? 'System' }}</strong>
                    </div>
                    <div>
                        Processed at: <strong>{{ \Carbon\Carbon::parse($process->created_at)->format('d M, Y H:i A') }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Individual Item Modal --}}
<div class="modal fade" id="itemDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title text-white">Employee Advance Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="text-center mb-4">
                    <h5 class="mb-1" id="modal-emp-name">-</h5>
                    <span class="badge bg-light text-dark border" id="modal-emp-id">-</span>
                </div>
                <div class="row g-3">
                    <div class="col-6">
                        <label class="text-muted small d-block">Department</label>
                        <span class="fw-bold" id="modal-emp-dept">-</span>
                    </div>
                    <div class="col-6">
                        <label class="text-muted small d-block">Designation</label>
                        <span class="fw-bold" id="modal-emp-desig">-</span>
                    </div>
                    <div class="col-6">
                        <label class="text-muted small d-block">Advance Amount</label>
                        <span class="fw-bold text-primary fs-5" id="modal-advance-amount">-</span>
                    </div>
                    <div class="col-6">
                        <label class="text-muted small d-block">Deduction Month</label>
                        <span class="fw-bold" id="modal-deduction-month">-</span>
                    </div>
                    <div class="col-12 border-top pt-3">
                        <label class="text-muted small d-block">Reason</label>
                        <p class="text-dark mb-0" id="modal-reason">-</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<style>
    .hover-primary:hover { color: var(--bs-primary) !important; text-decoration: underline; }
</style>

@push('scripts')
<script>
    $(document).ready(function() {
        $('.view-item-details').on('click', function() {
            const data = $(this).data();
            $('#modal-emp-name').text(data.name);
            $('#modal-emp-id').text(data.id);
            $('#modal-emp-dept').text(data.department);
            $('#modal-emp-desig').text(data.designation);
            $('#modal-advance-amount').text(data.amount);
            $('#modal-deduction-month').text(data.month);
            $('#modal-reason').text(data.reason);
            
            const modal = new bootstrap.Modal(document.getElementById('itemDetailsModal'));
            modal.show();
        });
    });
</script>
@endpush
@endsection
