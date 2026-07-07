@extends('structure.master')

@section('content')
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <a href="{{ route('decrement.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i style="height: 12px; width: 12px" data-feather="arrow-left"></i> Back to List
                </a>
                @if ($decrementData->status == 'pending')
                    <div class="d-flex gap-2">
                        <a href="{{ route('decrement.edit', $decrementData->id) }}" class="btn btn-primary btn-sm">
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
                        <div class="col-md-12 mb-3 text-center">
                            {!! \App\HelperClass::generateAvatar(
                                $decrementData->getEmployee->photo_path ?? null,
                                $decrementData->getEmployee->full_name ?? 'N/A',
                                120,
                                '#974063',
                                'border border-3 border-primary',
                                $decrementData->employee_id,
                            ) !!}
                        </div>

                        <div class="col-md-6 mb-2">
                            <strong>Employee Name:</strong>
                            <a href="{{ route('employee.profile.general_informations', $decrementData->employee_id) }}"
                                class="ms-2 text-decoration-none">
                                {{ $decrementData->getEmployee->full_name ?? 'N/A' }}
                            </a>
                        </div>
                        <div class="col-md-6 mb-2">
                            <strong>Employee ID:</strong>
                            <span class="ms-2">{{ $decrementData->getEmployee->applicant_id ?? 'N/A' }}</span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <strong>System ID:</strong>
                            <span class="ms-2">{{ $decrementData->getEmployee->system_id ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Decrement Details Card --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="mdi mdi-currency-bdt"></i> Salary Decrement Details
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="text-muted small">Decrement Base</label>
                            <div class="fw-semibold">
                                <span class="badge bg-info">
                                    {{ ucfirst(str_replace('_', ' ', $decrementData->decrement_base)) }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="text-muted small">Decrement Method</label>
                            <div class="fw-semibold">
                                <span class="badge bg-secondary">
                                    {{ ucfirst($decrementData->decrement_method) }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="text-muted small">Decrement Amount</label>
                            <div class="fw-semibold text-danger fs-5">
                                @if ($decrementData->decrement_method === 'percentage')
                                    {{ $decrementData->salary_decrease_amount }}%
                                @else
                                    ৳{{ number_format($decrementData->salary_decrease_amount, 2) }}
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-warning mb-0 mt-2">
                        <strong>Summary:</strong>
                        Decrement amount is {{ $decrementData->salary_decrease_amount }} on {{ ucfirst(str_replace('_', ' ', $decrementData->decrement_base)) }}.
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
                                {{ \Carbon\Carbon::parse($decrementData->effective_from)->format('d M Y') }}
                            </div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="text-muted small">Effective To</label>
                            <div class="fw-semibold">
                                {{ $decrementData->effective_to ? \Carbon\Carbon::parse($decrementData->effective_to)->format('d M Y') : 'Indefinite' }}
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
                                <span class="badge @if ($decrementData->status == 'pending') bg-warning @elseif($decrementData->status == 'approved') bg-success @else bg-danger @endif fs-6 px-3 py-2">
                                    {{ ucfirst($decrementData->status) }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="text-muted small">Record Created</label>
                            <div class="fw-semibold">
                                {{ \Carbon\Carbon::parse($decrementData->created_at)->format('d M Y') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Workflow History & Approval Form --}}
    @include('approval_engine.workflow_history', ['approvable' => $decrementData])

    <script>
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    </script>
@endsection
