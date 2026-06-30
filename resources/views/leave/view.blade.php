@extends('structure.master')

@section('content')
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <a href="{{ route('leave.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i style="height: 12px; width: 12px" data-feather="arrow-left"></i> Back to List
                </a>
            </div>
        </div>
    </div>

    {{-- Employee Information Card --}}
    <div class="card border-primary mb-4">
        <div class="card-header bg-primary-subtle">
            <h6 class="mb-0 text-primary fw-semibold">
                <i class="mdi mdi-account-circle-outline me-2"></i>Employee Information
            </h6>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="border-start border-primary border-3 ps-3">
                        <small class="text-muted d-block">Employee Name</small>
                        <strong class="text-dark">{{ $leaveData->getEmployee->full_name ?? 'N/A' }}</strong>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="border-start border-info border-3 ps-3">
                        <small class="text-muted d-block">Employee ID</small>
                        <strong class="text-dark">{{ $leaveData->getEmployee->applicant_id ?? 'N/A' }}</strong>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="border-start border-success border-3 ps-3">
                        <small class="text-muted d-block">System ID</small>
                        <strong class="text-dark">{{ $leaveData->getEmployee->system_id ?? 'N/A' }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Leave Details Card --}}
    <div class="card border-success mb-4">
        <div class="card-header bg-success-subtle">
            <h6 class="mb-0 text-success fw-semibold">
                <i class="mdi mdi-calendar-clock me-2"></i>Leave Details
            </h6>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="border-start border-success border-3 ps-3">
                        <small class="text-muted d-block">Leave Plan</small>
                        <strong class="text-dark">{{ $leaveData->getPlan->name ?? 'N/A' }}</strong>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="border-start border-warning border-3 ps-3">
                        <small class="text-muted d-block">Number of Days</small>
                        <strong class="text-dark fs-5">{{ $leaveData->leave_count }}</strong>
                        <small class="text-muted">days</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="border-start border-info border-3 ps-3">
                        <small class="text-muted d-block">From Date</small>
                        <strong class="text-dark">{{ $leaveData->from }}</strong>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="border-start border-danger border-3 ps-3">
                        <small class="text-muted d-block">To Date</small>
                        <strong class="text-dark">{{ $leaveData->to }}</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Reason Card --}}
    <div class="card border-warning mb-4">
        <div class="card-header bg-warning-subtle">
            <h6 class="mb-0 text-warning fw-semibold">
                <i class="mdi mdi-text-box-outline me-2"></i>Reason for Leave
            </h6>
        </div>
        <div class="card-body">
            <p class="mb-0 text-dark">{{ $leaveData->reason }}</p>
        </div>
    </div>

    {{-- Status Card --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="mdi mdi-information"></i> Status
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-6 mb-2">
                            <label class="text-muted small">Current Status</label>
                            <div>
                                <span
                                    class="badge @if ($leaveData->status == 'pending') bg-warning @elseif($leaveData->status == 'approved') bg-success
                                     @else bg-danger @endif fs-6 px-3 py-2">
                                    {{ ucfirst($leaveData->status) }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="text-muted small">Record Created</label>
                            <div class="fw-semibold">
                                {{ \Carbon\Carbon::parse($leaveData->created_at)->format('d M Y, h:i A') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Workflow History & Approval Form --}}
    @include('approval_engine.workflow_history', ['approvable' => $leaveData])

    <script>
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    </script>
@endsection
