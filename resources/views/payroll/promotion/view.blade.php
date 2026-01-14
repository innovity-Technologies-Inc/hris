@extends('structure.master')

@section('content')
    {{--
    ================================================
    DUMMY DATA FOR TESTING (Controller Integration)
    ================================================
    Use this object-style dummy data in your controller:

    $promotion = (object)[
        'id' => 1,
        'employee_id' => 1,
        'previous_designation' => 2,
        'new_designation' => 3,
        'new_basic_salary' => '50000.00',
        'effective_from' => \Carbon\Carbon::parse('2024-01-01'),
        'effective_to' => null,
        'status' => 'approved',
        'created_at' => \Carbon\Carbon::parse('2023-12-15 14:30:00'),
        'getEmployee' => (object)[
            'id' => 1,
            'full_name' => 'Ahmed Rahman',
            'applicant_id' => 'EMP-2024-001',
            'system_id' => 'SYS-001',
            'officeInfo' => (object)[
                'getCurrentDepartment' => (object)['name' => 'IT Department'],
            ],
        ],
        'getPreviousDesignation' => (object)[
            'id' => 2,
            'company_designation' => 'Software Engineer',
        ],
        'getNewDesignation' => (object)[
            'id' => 3,
            'company_designation' => 'Senior Software Engineer',
        ],
        'getStatusBadgeClass' => fn() => 'bg-success',
        'getIncrementSummary' => fn() => '15% (Percentage on Basic Salary)',
    ];
    --}}

    {{-- Back button and action buttons --}}
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <a href="{{ route('promotion.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i style="height: 12px; width: 12px" data-feather="arrow-left"></i> Back to List
                </a>
                @if ($promotion->status == 'pending')
                    <div class="d-flex gap-2">
                        <a href="{{ route('promotion.edit', $promotion->id) }}" class="btn btn-primary btn-sm">
                            <i style="height: 12px; width: 12px" data-feather="edit"></i> Edit
                        </a>
                        <form class="d-inline" action="{{ route('promotion.approve', $promotion->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-success btn-sm"
                                onclick="return confirm('Are you sure you want to approve this promotion?')">
                                <i style="height: 12px; width: 12px" data-feather="check"></i> Approve
                            </button>
                        </form>
                        <form class="d-inline" action="{{ route('promotion.reject', $promotion->id) }}" method="POST">
                            @csrf
                            @method('PUT')
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
                            @if (isset($promotion->getEmployee->photo_path) && $promotion->getEmployee->photo_path)
                                <img src="{{ asset('storage/' . $promotion->getEmployee->photo_path) }}" alt="Profile"
                                    class="rounded-circle border border-3 border-primary"
                                    style="width: 120px; height: 120px; object-fit: cover;">
                            @else
                                <div class="bg-secondary rounded-circle d-inline-flex align-items-center justify-content-center border border-3 border-primary"
                                    style="width: 120px; height: 120px; font-size: 48px; font-weight: bold; color: white;">
                                    {{ strtoupper(substr($promotion->getEmployee->full_name ?? 'U', 0, 1)) }}
                                </div>
                            @endif
                        </div>

                        <div class="col-md-6 mb-2">
                            <strong>Employee Name:</strong>
                            <span class="ms-2">{{ $promotion->getEmployee->full_name ?? 'N/A' }}</span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <strong>Employee ID:</strong>
                            <span class="ms-2">{{ $promotion->getEmployee->applicant_id ?? 'N/A' }}</span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <strong>System ID:</strong>
                            <span class="ms-2">{{ $promotion->getEmployee->system_id ?? 'N/A' }}</span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <strong>Department:</strong>
                            <span
                                class="ms-2">{{ $promotion->getEmployee->officeInfo->getCurrentDepartment->name ?? 'N/A' }}</span>
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
                                {{ $promotion->getPreviousDesignation->company_designation ?? 'N/A' }}
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">New Designation</label>
                            <div class="fw-semibold text-success">
                                <i class="mdi mdi-arrow-right text-muted"></i>
                                {{ $promotion->getNewDesignation->company_designation ?? 'N/A' }}
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
                                ৳{{ number_format($promotion->new_basic_salary, 2) }}
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-primary mb-0 mt-2">
                        <strong>Summary:</strong>
                        {{ $promotion->promotion_summary }}
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
                                {{ $promotion->effective_from->format('d M Y') }}
                            </div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="text-muted small">Effective To</label>
                            <div class="fw-semibold">
                                {{ $promotion->effective_to ? $promotion->effective_to->format('d M Y') : 'Indefinite' }}
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
                                <span class="badge {{ $promotion->status_badge_class }} fs-6 px-3 py-2">
                                    {{ ucfirst($promotion->status) }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="text-muted small">Record Created</label>
                            <div class="fw-semibold">
                                {{ $promotion->created_at->format('d M Y, h:i A') }}
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
