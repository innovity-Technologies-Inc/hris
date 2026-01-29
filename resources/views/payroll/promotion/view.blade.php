@extends('structure.master')

@section('content')
    {{-- Back button and action buttons --}}
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <a href="{{ route('promotion.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i style="height: 12px; width: 12px" data-feather="arrow-left"></i> Back to List
                </a>
                @if ($promotionData->status == 'pending')
                    <div class="d-flex gap-2">
                        <a href="{{ route('promotion.edit', $promotionData->id) }}" class="btn btn-primary btn-sm">
                            <i style="height: 12px; width: 12px" data-feather="edit"></i> Edit
                        </a>
                        <form class="d-inline" action="{{ route('promotion.status.update', $promotionData->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="approved">
                            <button type="submit" class="btn btn-success btn-sm"
                                onclick="return confirm('Are you sure you want to approve this promotion?')">
                                <i style="height: 12px; width: 12px" data-feather="check"></i> Approve
                            </button>
                        </form>
                        <form class="d-inline" action="{{ route('promotion.status.update', $promotionData->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="rejected">
                            <button type="submit" class="btn btn-danger btn-sm"
                                onclick="return confirm('Are you sure you want to reject this promotion?')">
                                <i style="height: 12px; width: 12px" data-feather="x"></i> Reject
                            </button>
                        </form>
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
                                $promotionData->getEmployee->photo_path ?? null,
                                $promotionData->getEmployee->full_name ?? 'N/A',
                                120,
                                '#974063',
                                'border border-3 border-primary',
                                $promotionData->employee_id,
                            ) !!}
                        </div>

                        <div class="col-md-6 mb-2">
                            <strong>Employee Name:</strong>
                            <a href="{{ route('employees.profile.general_informations', $promotionData->employee_id) }}"
                                class="ms-2 text-decoration-none">
                                {{ $promotionData->getEmployee->full_name ?? 'N/A' }}
                            </a>
                        </div>
                        <div class="col-md-6 mb-2">
                            <strong>Employee ID:</strong>
                            <span class="ms-2">{{ $promotionData->getEmployee->applicant_id ?? 'N/A' }}</span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <strong>System ID:</strong>
                            <span class="ms-2">{{ $promotionData->getEmployee->system_id ?? 'N/A' }}</span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <strong>Department:</strong>
                            <span
                                class="ms-2">{{ $promotionData->getEmployee->officeInfo->getCurrentDepartment->name ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Promotion Details Card --}}
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="mdi mdi-arrow-up-bold-circle"></i> Promotion Details
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Previous Designation</label>
                            <div class="fw-semibold">
                                {{ $promotionData->getPreviousDesignation->company_designation ?? 'N/A' }}
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">New Designation</label>
                            <div class="fw-semibold text-success">
                                <i class="mdi mdi-arrow-right text-muted"></i>
                                {{ $promotionData->getNewDesignation->company_designation ?? 'N/A' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- New Salary Details --}}
            <div class="col-md-12">
                <div class="border rounded p-3 bg-light">
                    <h6 class="fw-bold text-primary mb-3">
                        <i class="mdi mdi-currency-bdt"></i> New Salary Details
                    </h6>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="text-muted small">New Basic Salary</label>
                            <div class="fw-semibold text-success fs-3">
                                ৳{{ number_format($promotionData->new_gross_salary, 2) }}
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-primary mb-0 mt-2">
                        <strong>Summary:</strong>
                        {{ $promotionData->salary_increase_amount }}
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
                                {{ \Carbon\Carbon::parse($promotionData->effective_from)->format('d M Y') }}
                            </div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="text-muted small">Effective To</label>
                            <div class="fw-semibold">
                                {{ $promotionData->effective_to ? \Carbon\Carbon::parse($promotionData->effective_to)->format('d M Y') : 'Indefinite' }}
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
                                    class="badge @if ($promotionData->status == 'pending') bg-warning @elseif($promotionData->status == 'approved') bg-warning
                                     @else bg-danger @endif fs-6 px-3 py-2">
                                    {{ ucfirst($promotionData->status) }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="text-muted small">Record Created</label>
                            <div class="fw-semibold">
                                {{ \Carbon\Carbon::parse($promotionData->created_at)->format('d M Y, h:i A') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Initialize Feather icons
        if (typeof feather !== 'undefined') {
            feather.replace();
        }
    </script>
@endsection
