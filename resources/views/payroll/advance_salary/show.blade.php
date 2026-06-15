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
                                        <div class="avatar-sm me-2">
                                            @if($item->employee->photo_path)
                                                <img src="{{ asset('storage/' . $item->employee->photo_path) }}" 
                                                     alt="user-img" class="rounded-circle img-thumbnail shadow-sm" style="width: 32px; height: 32px; object-fit: cover;">
                                            @else
                                                <div class="avatar-title rounded-circle bg-soft-primary text-primary d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 12px; border: 1px solid #dee2e6;">
                                                    {{ strtoupper(substr($item->employee->full_name, 0, 1)) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ms-1">
                                            <div class="fw-bold">
                                                <a href="{{ route('employee.profile.general_informations', $item->employee->id) }}" class="text-dark hover-primary">
                                                    {{ $item->employee->full_name }}
                                                </a>
                                            </div>
                                            <div class="text-muted small">
                                                {{ $item->employee->officeInfo->getCurrentDesignation->company_designation ?? 'N/A' }}
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
                                    <button type="button" class="btn btn-outline-primary btn-sm view-item-details"
                                            data-name="{{ $item->employee->full_name }}"
                                            data-id="{{ $item->employee->system_id }}"
                                            data-photo="{{ $item->employee->photo_path ? asset('storage/' . $item->employee->photo_path) : '' }}"
                                            data-amount="{{ number_format($item->amount, 2) }}"
                                            data-month="{{ \Carbon\Carbon::parse($item->deduction_month)->format('F Y') }}"
                                            data-reason="{{ $item->reason ?? 'N/A' }}"
                                            data-status="{{ ucfirst($item->status) }}"
                                            data-company="{{ $item->employee->officeInfo->getCurrentCompany->name ?? 'N/A' }}"
                                            data-branch="{{ $item->employee->officeInfo->getCurrentBusinessUnit->name ?? 'N/A' }}"
                                            data-division="{{ $item->employee->officeInfo->getCurrentDivision->name ?? 'N/A' }}"
                                            data-department="{{ $item->employee->officeInfo->getCurrentDepartment->department_name ?? 'N/A' }}"
                                            data-section="{{ $item->employee->officeInfo->getCurrentSection->name ?? 'N/A' }}"
                                            data-designation="{{ $item->employee->officeInfo->getCurrentDesignation->company_designation ?? 'N/A' }}">
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
        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px; overflow: hidden;">
            <div class="modal-header bg-primary text-white py-3">
                <h5 class="modal-title text-white">
                    <i data-feather="user" class="me-2" style="width: 18px;"></i>
                    Employee Advance Profile
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                {{-- Profile Background & Avatar --}}
                <div class="position-relative" style="height: 100px; background: linear-gradient(45deg, #108dff, #6366f1); opacity: 0.1;"></div>
                <div class="text-center position-relative" style="margin-top: -50px;">
                    <img id="modal-emp-photo" src="" class="rounded-circle img-thumbnail shadow-sm d-none" 
                         style="width: 100px; height: 100px; object-fit: cover; border: 4px solid #fff;">
                    <div id="modal-emp-avatar-placeholder" class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto shadow-sm" 
                         style="width: 100px; height: 100px; border: 4px solid #fff; font-size: 40px; font-weight: bold;">
                    </div>
                    <h4 class="mt-3 mb-1 text-dark fw-bold" id="modal-emp-name">-</h4>
                    <p class="text-muted mb-3" id="modal-emp-desig-sub">-</p>
                </div>

                {{-- Organizational Info Section --}}
                <div class="px-4 py-3 bg-light mx-3 rounded-4 mb-4">
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="text-muted small d-block mb-1">System ID</label>
                            <span class="badge bg-white text-dark border px-2 py-1" id="modal-emp-id">-</span>
                        </div>
                        <div class="col-6">
                            <label class="text-muted small d-block mb-1">Current Status</label>
                            <span class="badge" id="modal-emp-status">-</span>
                        </div>
                        <div class="col-12">
                            <hr class="my-1 opacity-25">
                        </div>
                        <div class="col-6">
                            <label class="text-muted small d-block">Company</label>
                            <span class="fw-bold small text-dark d-block" id="modal-emp-company">-</span>
                        </div>
                        <div class="col-6">
                            <label class="text-muted small d-block">Branch</label>
                            <span class="fw-bold small text-dark d-block" id="modal-emp-branch">-</span>
                        </div>
                        <div class="col-6">
                            <label class="text-muted small d-block">Division</label>
                            <span class="fw-bold small text-dark d-block" id="modal-emp-division">-</span>
                        </div>
                        <div class="col-6">
                            <label class="text-muted small d-block">Department</label>
                            <span class="fw-bold small text-dark d-block" id="modal-emp-dept">-</span>
                        </div>
                        <div class="col-6">
                            <label class="text-muted small d-block">Section</label>
                            <span class="fw-bold small text-dark d-block" id="modal-emp-section">-</span>
                        </div>
                    </div>
                </div>

                {{-- Financial Info Section --}}
                <div class="px-4 mb-4">
                    <h6 class="text-primary fw-bold mb-3 border-bottom pb-2">Advance Details</h6>
                    <div class="row align-items-center">
                        <div class="col-6">
                            <div class="p-2 border rounded-3 bg-soft-primary">
                                <label class="text-muted small d-block mb-0">Requested Amount</label>
                                <span class="fw-bold text-primary fs-5" id="modal-advance-amount">-</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="p-2 border rounded-3">
                                <label class="text-muted small d-block mb-0">Deduction Cycle</label>
                                <span class="fw-bold text-dark" id="modal-deduction-month">-</span>
                            </div>
                        </div>
                        <div class="col-12 mt-3">
                            <label class="text-muted small d-block mb-1">Reason for Advance</label>
                            <div class="p-3 bg-light rounded-3 italic text-muted small" style="border-left: 4px solid #dee2e6;">
                                <i data-feather="quote" class="me-1 opacity-25" style="width: 14px;"></i>
                                <span id="modal-reason">-</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top-0 pt-0 pb-4 justify-content-center">
                <button type="button" class="btn btn-secondary px-5 rounded-pill shadow-sm" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-soft-primary { background-color: rgba(16, 141, 255, 0.05); }
    .img-thumbnail { padding: .25rem; background-color: #fff; border: 1px solid #dee2e6; border-radius: 50%; }
    .hover-primary:hover { color: var(--bs-primary) !important; text-decoration: underline; }
</style>

@push('scripts')
<script>
    $(document).ready(function() {
        $('.view-item-details').on('click', function() {
            const data = $(this).data();
            
            // Set basic info
            $('#modal-emp-name').text(data.name);
            $('#modal-emp-desig-sub').text(data.designation);
            $('#modal-emp-id').text(data.id);
            $('#modal-emp-company').text(data.company);
            $('#modal-emp-branch').text(data.branch);
            $('#modal-emp-division').text(data.division);
            $('#modal-emp-dept').text(data.department);
            $('#modal-emp-section').text(data.section);
            $('#modal-advance-amount').text(data.amount);
            $('#modal-deduction-month').text(data.month);
            $('#modal-reason').text(data.reason);

            // Handle Photo vs Initial Avatar
            if (data.photo) {
                $('#modal-emp-photo').attr('src', data.photo).removeClass('d-none');
                $('#modal-emp-avatar-placeholder').addClass('d-none');
            } else {
                $('#modal-emp-photo').addClass('d-none');
                $('#modal-emp-avatar-placeholder').text(data.name.charAt(0).toUpperCase()).removeClass('d-none');
            }

            // Handle Status Badge styling
            const statusEl = $('#modal-emp-status');
            statusEl.text(data.status).removeClass().addClass('badge px-2 py-1 border');
            if (data.status === 'Approved') statusEl.addClass('bg-success-subtle text-success border-success');
            else if (data.status === 'Pending') statusEl.addClass('bg-warning-subtle text-warning border-warning');
            else if (data.status === 'Deducted') statusEl.addClass('bg-info-subtle text-info border-info');
            else statusEl.addClass('bg-danger-subtle text-danger border-danger');

            const modal = new bootstrap.Modal(document.getElementById('itemDetailsModal'));
            modal.show();
        });
    });
</script>
@endpush
@endsection
