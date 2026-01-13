@extends('structure.master')

@section('content')
    {{-- Back button and action buttons --}}
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <a href="{{ route('increment.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i style="height: 12px; width: 12px" data-feather="arrow-left"></i> Back to List
                </a>
                @if ($increment->status == 'pending')
                    <div class="d-flex gap-2">
                        <a href="{{ route('increment.edit', $increment->id) }}" class="btn btn-primary btn-sm">
                            <i style="height: 12px; width: 12px" data-feather="edit"></i> Edit
                        </a>
                        <form class="d-inline" action="{{ route('increment.approve', $increment->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-success btn-sm"
                                onclick="return confirm('Are you sure you want to approve this increment?')">
                                <i style="height: 12px; width: 12px" data-feather="check"></i> Approve
                            </button>
                        </form>
                        <form class="d-inline" action="{{ route('increment.reject', $increment->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-danger btn-sm"
                                onclick="return confirm('Are you sure you want to reject this increment?')">
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
                            @if (isset($increment->getEmployee->photo_path) && $increment->getEmployee->photo_path)
                                <img src="{{ asset('storage/' . $increment->getEmployee->photo_path) }}" alt="Profile"
                                    class="rounded-circle border border-3 border-primary"
                                    style="width: 120px; height: 120px; object-fit: cover;">
                            @else
                                <div class="bg-secondary rounded-circle d-inline-flex align-items-center justify-content-center border border-3 border-primary"
                                    style="width: 120px; height: 120px; font-size: 48px; font-weight: bold; color: white;">
                                    {{ strtoupper(substr($increment->getEmployee->full_name ?? 'U', 0, 1)) }}
                                </div>
                            @endif
                        </div>

                        <div class="col-md-6 mb-2">
                            <strong>Employee Name:</strong>
                            <span class="ms-2">{{ $increment->getEmployee->full_name ?? 'N/A' }}</span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <strong>Employee ID:</strong>
                            <span class="ms-2">{{ $increment->getEmployee->applicant_id ?? 'N/A' }}</span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <strong>System ID:</strong>
                            <span class="ms-2">{{ $increment->getEmployee->system_id ?? 'N/A' }}</span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <strong>Current Designation:</strong>
                            <span
                                class="ms-2">{{ $increment->getEmployee->officeInfo->current_designation ?? 'N/A' }}</span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <strong>Grade:</strong>
                            <span class="ms-2">{{ $increment->getEmployee->officeInfo->grade ?? 'N/A' }}</span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <strong>Department:</strong>
                            <span
                                class="ms-2">{{ $increment->getEmployee->officeInfo->getCurrentDepartment->name ?? 'N/A' }}</span>
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
                        <div class="col-md-4 mb-3">
                            <label class="text-muted small">Increment Base</label>
                            <div class="fw-semibold">
                                <span class="badge bg-info">
                                    {{ ucfirst(str_replace('_', ' ', $increment->increment_base)) }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="text-muted small">Increment Method</label>
                            <div class="fw-semibold">
                                <span class="badge bg-secondary">
                                    {{ ucfirst($increment->increment_method) }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="text-muted small">Increment Amount</label>
                            <div class="fw-semibold text-success fs-5">
                                @if ($increment->increment_method === 'percentage')
                                    {{ $increment->increment_amount }}%
                                @else
                                    ৳{{ number_format($increment->increment_amount, 2) }}
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-primary mb-0 mt-2">
                        <strong>Summary:</strong>
                        {{ $increment->increment_summary }}
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
                                {{ $increment->effective_from->format('d M Y') }}
                            </div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="text-muted small">Effective To</label>
                            <div class="fw-semibold">
                                {{ $increment->effective_to ? $increment->effective_to->format('d M Y') : 'Indefinite' }}
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
                                <span class="badge {{ $increment->status_badge_class }} fs-6 px-3 py-2">
                                    {{ ucfirst($increment->status) }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="text-muted small">Record Created</label>
                            <div class="fw-semibold">
                                {{ $increment->created_at->format('d M Y, h:i A') }}
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
