@extends('structure.master')

@section('content')
    {{-- Back button and action buttons --}}
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <a href="{{ route('payroll.penalty.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i style="height: 12px; width: 12px" data-feather="arrow-left"></i> Back to List
                </a>
            </div>
        </div>
    </div>

    {{-- Employee Information Card --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="mdi mdi-account-circle"></i> Employee Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 mb-3 text-center">
                            {!! \App\HelperClass::generateAvatar(
                                $penalty->employee->photo_path ?? null,
                                $penalty->employee->full_name ?? 'N/A',
                                120,
                                '#974063',
                                'border border-3 border-primary',
                                $penalty->employee_id,
                            ) !!}
                        </div>

                        <div class="col-md-6 mb-2">
                            <strong>Employee Name:</strong>
                            <a href="{{ route('employee.profile.general_informations', $penalty->employee_id) }}"
                                class="ms-2 text-decoration-none">
                                {{ $penalty->employee->full_name ?? 'N/A' }}
                            </a>
                        </div>
                        <div class="col-md-6 mb-2">
                            <strong>Employee ID:</strong>
                            <span class="ms-2">{{ $penalty->employee->applicant_id ?? 'N/A' }}</span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <strong>System ID:</strong>
                            <span class="ms-2">{{ $penalty->employee->system_id ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Penalty Details Card --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="mdi mdi-alert-circle-outline"></i> Penalty Details
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="text-muted small">Penalty Plan</label>
                            <div class="fw-semibold">
                                <span class="badge bg-dark">
                                    {{ $penalty->penaltyPlan->title ?? 'Custom' }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="text-muted small">Penalty Amount</label>
                            <div class="fw-semibold text-danger fs-5">
                                ৳{{ number_format($penalty->penalty_amount, 2) }}
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="text-muted small">Occurrence Date</label>
                            <div class="fw-semibold">
                                {{ \Carbon\Carbon::parse($penalty->occurrence_date)->format('d M Y') }}
                            </div>
                        </div>
                    </div>

                    @if($penalty->cause)
                        <div class="alert alert-warning mb-0 mt-2">
                            <strong>Cause/Reason:</strong> {{ $penalty->cause }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Status Card --}}
    <div class="row">
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
                                <span class="badge @if ($penalty->status == 'pending') bg-warning @elseif($penalty->status == 'approved') bg-success @elseif($penalty->status == 'rejected') bg-danger @else bg-info @endif fs-6 px-3 py-2">
                                    {{ ucfirst($penalty->status) }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="text-muted small">Assigned Date</label>
                            <div class="fw-semibold">
                                {{ \Carbon\Carbon::parse($penalty->created_at)->format('d M Y') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Workflow History & Approval Form --}}
    @include('approval_engine.workflow_history', ['approvable' => $penalty])

    <script>
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    </script>
@endsection
