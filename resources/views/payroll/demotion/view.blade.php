@extends('structure.master')

@section('content')
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <a href="{{ route('demotion.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i style="height: 12px; width: 12px" data-feather="arrow-left"></i> Back to List
                </a>
                @if ($demotionData->status == 'pending')
                    <div class="d-flex gap-2">
                        <a href="{{ route('demotion.edit', $demotionData->id) }}" class="btn btn-primary btn-sm">
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
                                $demotionData->getEmployee->photo_path ?? null,
                                $demotionData->getEmployee->full_name ?? 'N/A',
                                120,
                                '#974063',
                                'border border-3 border-danger',
                                $demotionData->employee_id,
                            ) !!}
                        </div>

                        <div class="col-md-6 mb-2">
                            <strong>Employee Name:</strong>
                            <a href="{{ route('employee.profile.general_informations', $demotionData->employee_id) }}"
                                class="ms-2 text-decoration-none">
                                {{ $demotionData->getEmployee->full_name ?? 'N/A' }}
                            </a>
                        </div>
                        <div class="col-md-6 mb-2">
                            <strong>Employee ID:</strong>
                            <span class="ms-2">{{ $demotionData->getEmployee->applicant_id ?? 'N/A' }}</span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <strong>System ID:</strong>
                            <span class="ms-2">{{ $demotionData->getEmployee->system_id ?? 'N/A' }}</span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <strong>Department:</strong>
                            <span class="ms-2">{{ $demotionData->getEmployee->officeInfo?->getCurrentDepartment?->department_name ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Demotion Details Card --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="mdi mdi-arrow-down-bold-circle"></i> Demotion Details
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Previous Designation</label>
                            <div class="fw-semibold">
                                {{ $demotionData->getPreviousDesignation->company_designation ?? 'N/A' }}
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">New Designation</label>
                            <div class="fw-semibold text-danger">
                                <i class="mdi mdi-arrow-right text-muted"></i>
                                {{ $demotionData->getNewDesignation->company_designation ?? 'N/A' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- New Salary Details --}}
            <div class="col-md-12">
                <div class="border rounded p-3 bg-light">
                    <h6 class="fw-bold text-danger mb-3">
                        <i class="mdi mdi-currency-bdt"></i> New Salary Details
                    </h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">New Gross Salary</label>
                            <div class="fw-semibold text-danger fs-3">
                                ৳{{ number_format($demotionData->new_gross_salary, 2) }}
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Movement Type</label>
                            <div class="fw-semibold mt-1">
                                <span class="badge bg-dark fs-6">
                                    {{ $demotionData->movementType?->name ?? 'N/A' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-warning mb-0 mt-2">
                        <strong>Summary:</strong>
                        Salary decreased by {{ $demotionData->salary_decrease_amount }} on {{ ucfirst(str_replace('_', ' ', $demotionData->decrement_base)) }}.
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
                                {{ \Carbon\Carbon::parse($demotionData->effective_from)->format('d M Y') }}
                            </div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="text-muted small">Effective To</label>
                            <div class="fw-semibold">
                                {{ $demotionData->effective_to ? \Carbon\Carbon::parse($demotionData->effective_to)->format('d M Y') : 'Indefinite' }}
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
                                <span class="badge @if ($demotionData->status == 'pending') bg-warning @elseif($demotionData->status == 'approved') bg-success @else bg-danger @endif fs-6 px-3 py-2">
                                    {{ ucfirst($demotionData->status) }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="text-muted small">Record Created</label>
                            <div class="fw-semibold">
                                {{ \Carbon\Carbon::parse($demotionData->created_at)->format('d M Y, h:i A') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Attachments Card --}}
    @if($demotionData->attachments && $demotionData->attachments->count() > 0)
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i style="height: 16px; width: 16px; margin-right: 5px" data-feather="paperclip"></i> Attachments
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($demotionData->attachments as $attachment)
                            <a href="{{ \App\HelperClass::get_file_url($attachment->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                <i style="height: 12px; width: 12px" data-feather="download"></i>
                                {{ $attachment->file_name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Workflow History & Approval Form --}}
    @include('approval_engine.workflow_history', ['approvable' => $demotionData])

    <script>
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    </script>
@endsection
