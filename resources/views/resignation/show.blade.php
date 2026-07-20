@extends('structure.master')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card border-0 shadow-lg rounded-4 my-4">
            <div class="card-header border-bottom rounded-top-4 p-4 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <div class="bg-info bg-opacity-10 rounded-circle p-2 me-3 d-inline-flex align-items-center justify-content-center">
                        <i class="mdi mdi-text-box-search text-info fs-4"></i>
                    </div>
                    <div>
                        <h5 class="card-title mb-0 text-dark fw-bold">Resignation Details #{{ $resignation->id }}</h5>
                        <small class="text-muted">Submitted on {{ \Carbon\Carbon::parse($resignation->created_at)->format('M d, Y h:i A') }}</small>
                    </div>
                </div>
                <div>
                    @can('resignations.edit')
                    <a href="{{ route('resignation.edit', $resignation->id) }}" class="btn btn-warning btn-sm rounded-3 me-2">
                        <i class="mdi mdi-pencil me-1"></i> Edit
                    </a>
                    @endcan
                    <a href="{{ route('resignation.index') }}" class="btn btn-outline-secondary btn-sm rounded-3">
                        <i class="mdi mdi-arrow-left me-1"></i> Back
                    </a>
                </div>
            </div>

            <div class="card-body p-4">
                <div class="row g-4">
                    {{-- Employee Information Card --}}
                    <div class="col-md-6">
                        <div class="card border h-100 rounded-3">
                            <div class="card-header bg-light">
                                <h6 class="mb-0 fw-semibold text-primary"><i class="mdi mdi-account-circle me-2"></i>Employee Profile</h6>
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless table-sm mb-0">
                                    <tr>
                                        <th class="text-muted" style="width: 40%;">Full Name:</th>
                                        <td class="fw-semibold text-dark">{{ $resignation->employee?->full_name ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">Applicant ID:</th>
                                        <td>{{ $resignation->employee?->applicant_id ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">Company:</th>
                                        <td>{{ $resignation->employee->officeInfo?->company?->name ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">Designation:</th>
                                        <td>{{ $resignation->employee->officeInfo?->designation?->name ?? '-' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- Resignation Parameters Card --}}
                    <div class="col-md-6">
                        <div class="card border h-100 rounded-3">
                            <div class="card-header bg-light">
                                <h6 class="mb-0 fw-semibold text-primary"><i class="mdi mdi-door-open me-2"></i>Resignation Status & Dates</h6>
                            </div>
                            <div class="card-body">
                                <table class="table table-borderless table-sm mb-0">
                                    <tr>
                                        <th class="text-muted" style="width: 40%;">Status:</th>
                                        <td>
                                            @if ($resignation->status == 'pending')
                                                <span class="badge bg-warning text-dark">Pending</span>
                                            @elseif($resignation->status == 'approved')
                                                <span class="badge bg-success">Approved</span>
                                            @elseif($resignation->status == 'rejected')
                                                <span class="badge bg-danger">Rejected</span>
                                            @else
                                                <span class="badge bg-secondary">Cancelled</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">Resignation Date:</th>
                                        <td>{{ \Carbon\Carbon::parse($resignation->resignation_date)->format('M d, Y') }}</td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">Notice Period:</th>
                                        <td><span class="badge bg-info text-dark">{{ $resignation->notice_period_days }} Days</span></td>
                                    </tr>
                                    <tr>
                                        <th class="text-muted">Last Working Day:</th>
                                        <td class="fw-bold text-danger">{{ \Carbon\Carbon::parse($resignation->last_working_day)->format('M d, Y') }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- Reason & Remarks Card --}}
                    <div class="col-md-12">
                        <div class="card border rounded-3">
                            <div class="card-header bg-light">
                                <h6 class="mb-0 fw-semibold text-primary"><i class="mdi mdi-text-subject me-2"></i>Reason & Additional Remarks</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <h6 class="fw-semibold text-dark">Reason:</h6>
                                    <p class="text-secondary mb-0 bg-light p-3 rounded-3 border">{{ $resignation->reason }}</p>
                                </div>
                                @if($resignation->remarks)
                                <div>
                                    <h6 class="fw-semibold text-dark">Remarks:</h6>
                                    <p class="text-secondary mb-0 bg-light p-3 rounded-3 border">{{ $resignation->remarks }}</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Approval Workflow History --}}
                    @if($resignation->approvalRequests && $resignation->approvalRequests->isNotEmpty())
                    <div class="col-md-12">
                        <div class="card border rounded-3">
                            <div class="card-header bg-light">
                                <h6 class="mb-0 fw-semibold text-primary"><i class="mdi mdi-timeline-text-outline me-2"></i>Approval Workflow History</h6>
                            </div>
                            <div class="card-body">
                                @foreach($resignation->approvalRequests as $approvalRequest)
                                    <div class="mb-3">
                                        <div class="fw-bold text-dark mb-2">Workflow Request #{{ $approvalRequest->id }} - Status: <span class="badge bg-secondary">{{ ucfirst($approvalRequest->status) }}</span></div>
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-sm align-middle">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Step</th>
                                                        <th>Approver</th>
                                                        <th>Status</th>
                                                        <th>Action Date</th>
                                                        <th>Comments</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($approvalRequest->stepRequests as $step)
                                                        <tr>
                                                            <td>Step {{ $step->step_order ?? '-' }}</td>
                                                            <td>{{ $step->approver?->name ?? 'Pending Assigned Approver' }}</td>
                                                            <td>
                                                                @if($step->status == 'approved')
                                                                    <span class="badge bg-success">Approved</span>
                                                                @elseif($step->status == 'rejected')
                                                                    <span class="badge bg-danger">Rejected</span>
                                                                @else
                                                                    <span class="badge bg-warning text-dark">Pending</span>
                                                                @endif
                                                            </td>
                                                            <td>{{ $step->action_at ? \Carbon\Carbon::parse($step->action_at)->format('M d, Y h:i A') : '-' }}</td>
                                                            <td>{{ $step->comments ?? '-' }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
