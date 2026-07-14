@extends('structure.master')

@section('content')
    {{-- Back button --}}
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <a href="{{ route('claim_expenses.index') }}" class="btn btn-outline-secondary btn-sm">
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
                        <div class="col-md-12 mb-3 text-center">
                            {!! \App\HelperClass::generateAvatar(
                                $application->employee->photo_path ?? null,
                                $application->employee->full_name ?? 'N/A',
                                120,
                                '#974063',
                                'border border-3 border-primary',
                                $application->employee_id,
                            ) !!}
                        </div>

                        <div class="col-md-6 mb-2">
                            <strong>Employee Name:</strong>
                            <a href="{{ route('employee.profile.general_informations', $application->employee_id) }}"
                                class="ms-2 text-decoration-none">
                                {{ $application->employee->full_name ?? 'N/A' }}
                            </a>
                        </div>
                        <div class="col-md-6 mb-2">
                            <strong>Employee ID:</strong>
                            <span class="ms-2">{{ $application->employee->applicant_id ?? 'N/A' }}</span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <strong>System ID:</strong>
                            <span class="ms-2">{{ $application->employee->system_id ?? 'N/A' }}</span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <strong>Department:</strong>
                            <span class="ms-2">{{ $application->employee->officeInfo?->getCurrentDepartment?->department_name ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Claim Expense Details Card --}}
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="mdi mdi-cash-multiple"></i> Expense Claim Details
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Expense Type</label>
                            <div class="fw-semibold">
                                {{ $application->expenseType->name ?? 'N/A' }}
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Amount</label>
                            <div class="fw-semibold text-warning fs-5">
                                {{ number_format($application->amount, 2) }}
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Payment Method</label>
                            <div class="fw-semibold">
                                {{ ucfirst(str_replace('_', ' ', $application->payment_method)) }}
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Purpose</label>
                            <div class="fw-semibold">
                                {{ $application->purpose ?? 'N/A' }}
                            </div>
                        </div>

                        @if($application->receipt_path)
                            <div class="col-12 mb-3">
                                <label class="text-muted small d-block">Receipt / Bill Attachment</label>
                                <a href="{{ asset($application->receipt_path) }}" target="_blank" class="btn btn-sm btn-outline-warning rounded-pill px-3 mt-1">
                                    <i style="height: 12px; width: 12px" data-feather="eye"></i> View Attachment
                                </a>
                            </div>
                        @endif
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
                                <span class="badge @if($application->status === 'pending') bg-warning text-dark @elseif($application->status === 'approved') bg-success @else bg-danger @endif fs-6 px-3 py-2 mt-1">
                                    {{ ucfirst($application->status) }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="text-muted small">Applied By</label>
                            <div class="fw-semibold text-dark">
                                <i data-feather="user" style="width: 14px;" class="me-1"></i>
                                {{ $application->creator->name ?? 'System' }} on {{ $application->created_at->format('d M Y, h:i A') }}
                            </div>
                        </div>

                        @if($application->remarks)
                        <div class="col-12 mt-3">
                            <div class="alert alert-primary mb-0">
                                <strong>Remarks:</strong>
                                {{ $application->remarks }}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Workflow History Timeline & Approval Action --}}
    @include('approval_engine.workflow_history', ['approvable' => $application])
@endsection
