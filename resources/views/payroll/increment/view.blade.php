@extends('structure.master')

@section('content')
    {{-- Back button and action buttons --}}
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <a href="{{ route('increment.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i style="height: 12px; width: 12px" data-feather="arrow-left"></i> Back to List
                </a>
                @if ($incrementData->status == 'pending')
                    <div class="d-flex gap-2">
                        <a href="{{ route('increment.edit', $incrementData->id) }}" class="btn btn-primary btn-sm">
                            <i style="height: 12px; width: 12px" data-feather="edit"></i> Edit
                        </a>
                    </div>
                @endif
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
                        {{-- Profile Image --}}
                        <div class="col-md-12 mb-3 text-center">
                            {!! \App\HelperClass::generateAvatar(
                                $incrementData->getEmployee->photo_path ?? null,
                                $incrementData->getEmployee->full_name ?? 'N/A',
                                120,
                                '#974063',
                                'border border-3 border-primary',
                                $incrementData->employee_id,
                            ) !!}
                        </div>

                        <div class="col-md-6 mb-2">
                            <strong>Employee Name:</strong>
                            <a href="{{ route('employee.profile.general_informations', $incrementData->employee_id) }}"
                                class="ms-2 text-decoration-none">
                                {{ $incrementData->getEmployee->full_name ?? 'N/A' }}
                            </a>
                        </div>
                        <div class="col-md-6 mb-2">
                            <strong>Employee ID:</strong>
                            <span class="ms-2">{{ $incrementData->getEmployee->applicant_id ?? 'N/A' }}</span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <strong>System ID:</strong>
                            <span class="ms-2">{{ $incrementData->getEmployee->system_id ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Increment Details Card --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="mdi mdi-currency-bdt"></i> Salary Increment Details
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="text-muted small">Increment Base</label>
                            <div class="fw-semibold">
                                <span class="badge bg-info">
                                    {{ ucfirst(str_replace('_', ' ', $incrementData->increment_base)) }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="text-muted small">Increment Method</label>
                            <div class="fw-semibold">
                                <span class="badge bg-secondary">
                                    {{ ucfirst($incrementData->increment_method) }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="text-muted small">Increment Amount</label>
                            <div class="fw-semibold text-success fs-5">
                                @if ($incrementData->increment_method === 'percentage')
                                    {{ $incrementData->salary_increase_amount }}%
                                @else
                                    ৳{{ number_format($incrementData->salary_increase_amount, 2) }}
                                @endif
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="text-muted small">Movement Type</label>
                            <div class="fw-semibold">
                                <span class="badge bg-dark">
                                    {{ $incrementData->movementType?->name ?? 'N/A' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-primary mb-0 mt-2">
                        <strong>Summary:</strong>
                        {{ $incrementData->salary_increase_amount }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Effective Period Card --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="mdi mdi-calendar-range"></i> Effective Period
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label class="text-muted small">Effective From</label>
                            <div class="fw-semibold">
                                {{ \Carbon\Carbon::parse($incrementData->effective_from)->format('d M Y') }} </div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="text-muted small">Effective To</label>
                            <div class="fw-semibold">
                                {{ $incrementData->effective_to ? \Carbon\Carbon::parse($increment->effective_to)->format('d M Y') : 'Indefinite' }}
                            </div>
                        </div>
                    </div>
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
                                <span
                                    class="badge @if ($incrementData->status == 'pending') bg-warning @elseif($incrementData->status == 'approved') bg-warning
                                     @else bg-danger @endif fs-6 px-3 py-2">
                                    {{ ucfirst($incrementData->status) }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="text-muted small">Record Created</label>
                            <div class="fw-semibold">
                                {{ \Carbon\Carbon::parse($incrementData->created_at)->format('d M Y') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Workflow History & Approval Form --}}
    @include('approval_engine.workflow_history', ['approvable' => $incrementData])

    <script>
        // Initialize Feather icons
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    </script>
@endsection

