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
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($process->advanceSalaries as $index => $item)
                            <tr>
                                <td class="text-center text-muted">{{ $index + 1 }}</td>
                                <td><span class="fw-semibold text-secondary">{{ $item->employee->employee_id }}</span></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="ms-1">
                                            <div class="fw-bold text-dark">{{ $item->employee->full_name }}</div>
                                            <div class="text-muted small">{{ $item->employee->officeInfo->designation->name ?? 'N/A' }}</div>
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
@endsection
