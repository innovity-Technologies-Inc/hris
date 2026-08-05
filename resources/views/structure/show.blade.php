@extends('structure.master')

@push('styles')
    <style>
        .btn-primary-theme {
            background-color: var(--primary-color, #974063) !important;
            border-color: var(--primary-color, #974063) !important;
            color: #fff !important;
        }
        .btn-primary-theme:hover, .btn-primary-theme:focus {
            background-color: #7b3150 !important;
            border-color: #7b3150 !important;
        }
        .bg-primary-theme {
            background-color: var(--primary-color, #974063) !important;
        }
        .text-primary-theme {
            color: var(--primary-color, #974063) !important;
        }
    </style>
@endpush

@section('content')
    {{-- Back button and action buttons --}}
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <a href="{{ route('organization-structure.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Back to List
                </a>
            </div>
        </div>
    </div>

    @php($displayName = $member->getEmployee ? $member->getEmployee->full_name : $member->name)
    @php($photoPath = $member->photo_path ?? ($member->getEmployee ? $member->getEmployee->photo_path : null))
    @php($employeeId = $member->getEmployee ? $member->getEmployee->id : null)

    {{-- Key Person Information Card --}}
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user-circle me-1 text-primary-theme"></i> Key Person Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-2 text-center mb-3 mb-md-0">
                            {!! \App\HelperClass::generateAvatar(
                                $photoPath,
                                $displayName,
                                100,
                                '#974063',
                                'border border-3 border-primary shadow-sm rounded-circle',
                                $employeeId,
                            ) !!}
                        </div>
                        <div class="col-md-10">
                            <h3 class="mb-1 text-dark fw-bold">{{ $displayName }}</h3>
                            <p class="text-primary-theme fw-semibold mb-2"><i class="fas fa-briefcase me-1"></i>{{ $member->position }}</p>
                            @if ($member->getEmployee)
                                <div class="row text-muted small">
                                    <div class="col-sm-6 mb-1">
                                        <i class="fas fa-id-badge me-1"></i><strong>Employee ID:</strong> {{ $member->getEmployee->applicant_id ?? 'N/A' }}
                                    </div>
                                    <div class="col-sm-6 mb-1">
                                        <i class="fas fa-fingerprint me-1"></i><strong>System ID:</strong> {{ $member->getEmployee->system_id }}
                                    </div>
                                </div>
                            @else
                                <span class="badge bg-secondary"><i class="fas fa-user-tag me-1"></i>External / Custom Person</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Hierarchy & Organization Context Card --}}
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-sitemap me-1 text-primary-theme"></i> Hierarchy & Organization Context
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6 col-lg-3">
                            <span class="text-muted d-block small fw-semibold">Level / Type</span>
                            <span class="badge bg-primary mt-1">{{ $member->type }}</span>
                        </div>

                        <div class="col-md-6 col-lg-3">
                            <span class="text-muted d-block small fw-semibold">Status</span>
                            @if ($member->status_form == 'active')
                                <span class="badge bg-success mt-1">Active</span>
                            @else
                                <span class="badge bg-danger mt-1">Inactive</span>
                            @endif
                        </div>

                        @if ($member->getGroup)
                            <div class="col-md-6 col-lg-3">
                                <span class="text-muted d-block small fw-semibold">Group Scope</span>
                                <span class="text-dark fw-medium">{{ $member->getGroup->name }}</span>
                            </div>
                        @endif

                        @if ($member->getCompany)
                            <div class="col-md-6 col-lg-3">
                                <span class="text-muted d-block small fw-semibold">Company Scope</span>
                                <span class="text-dark fw-medium">{{ $member->getCompany->name }}</span>
                            </div>
                        @endif

                        @if ($member->getBranchUnit)
                            <div class="col-md-6 col-lg-3">
                                <span class="text-muted d-block small fw-semibold">Branch / Location Scope</span>
                                <span class="text-dark fw-medium">{{ $member->getBranchUnit->name }}</span>
                            </div>
                        @endif

                        @if ($member->getDivision)
                            <div class="col-md-6 col-lg-3">
                                <span class="text-muted d-block small fw-semibold">Division Scope</span>
                                <span class="text-dark fw-medium">{{ $member->getDivision->name }}</span>
                            </div>
                        @endif

                        @if ($member->getDepartment)
                            <div class="col-md-6 col-lg-3">
                                <span class="text-muted d-block small fw-semibold">Department Scope</span>
                                <span class="text-dark fw-medium">{{ $member->getDepartment->department_name }}</span>
                            </div>
                        @endif

                        @if ($member->getSection)
                            <div class="col-md-6 col-lg-3">
                                <span class="text-muted d-block small fw-semibold">Section Scope</span>
                                <span class="text-dark fw-medium">{{ $member->getSection->name }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Contact Details Card --}}
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-address-book me-1 text-primary-theme"></i> Contact Details
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <span class="text-muted d-block small fw-semibold mb-1">Email Address</span>
                            <span class="text-dark">
                                @php($email = $member->getEmployee ? ($member->getEmployee->work_email ?? $member->getEmployee->personal_email) : $member->email)
                                @if (!empty($email))
                                    <a href="mailto:{{ $email }}" class="text-decoration-none">
                                        <i class="fas fa-envelope text-primary-theme me-1"></i>{{ $email }}
                                    </a>
                                @else
                                    <span class="text-muted-light">No Email Provided</span>
                                @endif
                            </span>
                        </div>

                        <div class="col-md-6">
                            <span class="text-muted d-block small fw-semibold mb-1">Phone Number</span>
                            <span class="text-dark">
                                @php($phone = $member->getEmployee ? ($member->getEmployee->work_mobile ?? $member->getEmployee->personal_mobile) : $member->contact_no)
                                @if (!empty($phone))
                                    <a href="tel:{{ $phone }}" class="text-decoration-none">
                                        <i class="fas fa-phone text-success me-1"></i>{{ $phone }}
                                    </a>
                                @else
                                    <span class="text-muted-light">No Phone Number Provided</span>
                                @endif
                            </span>
                        </div>

                        @if ($member->address)
                            <div class="col-md-12">
                                <span class="text-muted d-block small fw-semibold mb-1">Address</span>
                                <span class="text-dark"><i class="fas fa-map-marker-alt text-danger me-1"></i>{{ $member->address }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Bottom Action Buttons --}}
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-end gap-2 mb-4">
                <a href="{{ route('organization-structure.index') }}" class="btn btn-secondary px-4">
                    <i class="fas fa-times me-1"></i> Cancel
                </a>
                <a href="{{ route('organization-structure.edit', $member->id) }}" class="btn btn-primary-theme px-5">
                    <i class="fas fa-edit me-1"></i> Edit Profile
                </a>
            </div>
        </div>
    </div>
@endsection
