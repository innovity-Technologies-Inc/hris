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
    <div class="row">
        <div class="col-12 col-lg-10 mx-auto">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary-theme text-white d-flex justify-content-between align-items-center py-3">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-id-card fa-lg me-2"></i>
                        <h4 class="mb-0 text-white font-weight-bold">Key Person Profile</h4>
                    </div>
                    <a href="{{ route('organization-structure.index') }}" class="btn btn-light btn-sm fw-semibold shadow-sm">
                        <i class="fas fa-arrow-left me-1"></i> Back to List
                    </a>
                </div>

                <div class="card-body p-4">
                    <div class="row">
                        <!-- Profile Image Section -->
                        <div class="col-md-12 mb-4 text-center">
                            @php($displayName = $member->getEmployee ? $member->getEmployee->full_name : $member->name)
                            @php($photoPath = $member->photo_path ?? ($member->getEmployee ? $member->getEmployee->photo_path : null))
                            @php($employeeId = $member->getEmployee ? $member->getEmployee->id : null)
                            {!! \App\HelperClass::generateAvatar(
                                $photoPath,
                                $displayName,
                                120,
                                '#974063',
                                'border border-3 border-primary shadow-sm rounded-circle',
                                $employeeId,
                            ) !!}
                            
                            <h3 class="mt-3 mb-1 text-dark fw-bold">{{ $displayName }}</h3>
                            <p class="text-primary fw-semibold mb-0"><i class="fas fa-briefcase me-1"></i>{{ $member->position }}</p>
                            @if ($member->getEmployee)
                                <small class="text-muted d-block mt-1">
                                    <i class="fas fa-id-card me-1"></i>Employee ID: {{ $member->getEmployee->system_id }}
                                </small>
                            @endif
                        </div>

                        <!-- Organization Information Section -->
                        <div class="col-md-12 mb-4">
                            <h5 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-sitemap me-2"></i>Organization Context
                            </h5>
                            <div class="row bg-light rounded p-3 g-3">
                                <div class="col-md-6 col-lg-4">
                                    <span class="text-muted d-block small fw-semibold">Level / Type</span>
                                    <span class="badge bg-primary mt-1">{{ $member->type }}</span>
                                </div>

                                <div class="col-md-6 col-lg-4">
                                    <span class="text-muted d-block small fw-semibold">Status</span>
                                    @if ($member->status_form == 'active')
                                        <span class="badge bg-success mt-1">Active</span>
                                    @else
                                        <span class="badge bg-danger mt-1">Inactive</span>
                                    @endif
                                </div>

                                @if ($member->getGroup)
                                    <div class="col-md-6 col-lg-4">
                                        <span class="text-muted d-block small fw-semibold">Group Scope</span>
                                        <span class="text-dark fw-medium">{{ $member->getGroup->name }}</span>
                                    </div>
                                @endif

                                @if ($member->getCompany)
                                    <div class="col-md-6 col-lg-4">
                                        <span class="text-muted d-block small fw-semibold">Company Scope</span>
                                        <span class="text-dark fw-medium">{{ $member->getCompany->name }}</span>
                                    </div>
                                @endif

                                @if ($member->getBranchUnit)
                                    <div class="col-md-6 col-lg-4">
                                        <span class="text-muted d-block small fw-semibold">Branch / Location</span>
                                        <span class="text-dark fw-medium">{{ $member->getBranchUnit->name }}</span>
                                    </div>
                                @endif

                                @if ($member->getDivision)
                                    <div class="col-md-6 col-lg-4">
                                        <span class="text-muted d-block small fw-semibold">Division Scope</span>
                                        <span class="text-dark fw-medium">{{ $member->getDivision->name }}</span>
                                    </div>
                                @endif

                                @if ($member->getDepartment)
                                    <div class="col-md-6 col-lg-4">
                                        <span class="text-muted d-block small fw-semibold">Department Scope</span>
                                        <span class="text-dark fw-medium">{{ $member->getDepartment->department_name }}</span>
                                    </div>
                                @endif

                                @if ($member->getSection)
                                    <div class="col-md-6 col-lg-4">
                                        <span class="text-muted d-block small fw-semibold">Section Scope</span>
                                        <span class="text-dark fw-medium">{{ $member->getSection->name }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Contact Information Section -->
                        <div class="col-md-12 mb-4">
                            <h5 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-address-book me-2"></i>Contact Details
                            </h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <span class="text-muted d-block small fw-semibold">Email Address</span>
                                    <span class="text-dark">
                                        @if (!empty($member->email))
                                            <a href="mailto:{{ $member->email }}" class="text-decoration-none">
                                                <i class="fas fa-envelope text-primary me-1"></i>{{ $member->email }}
                                            </a>
                                        @else
                                            <span class="text-muted-light">No Email Provided</span>
                                        @endif
                                    </span>
                                </div>

                                <div class="col-md-6">
                                    <span class="text-muted d-block small fw-semibold">Phone Number</span>
                                    <span class="text-dark">
                                        @if (!empty($member->contact_no))
                                            <a href="tel:{{ $member->contact_no }}" class="text-decoration-none">
                                                <i class="fas fa-phone text-success me-1"></i>{{ $member->contact_no }}
                                            </a>
                                        @else
                                            <span class="text-muted-light">No Phone Number Provided</span>
                                        @endif
                                    </span>
                                </div>

                                @if ($member->address)
                                    <div class="col-md-12">
                                        <span class="text-muted d-block small fw-semibold">Address</span>
                                        <span class="text-dark"><i class="fas fa-map-marker-alt text-danger me-1"></i>{{ $member->address }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-end gap-2 border-top pt-3 mt-4">
                        <a href="{{ route('organization-structure.index') }}" class="btn btn-secondary px-4">
                            <i class="fas fa-times me-1"></i> Cancel
                        </a>
                        <a href="{{ route('organization-structure.edit', $member->id) }}" class="btn btn-primary-theme px-5">
                            <i class="fas fa-edit me-1"></i> Edit Profile
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
