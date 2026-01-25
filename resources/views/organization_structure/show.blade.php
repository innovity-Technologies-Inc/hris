@extends('structure.master')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-user me-2"></i>
                            {{ $member->member_type === 'Board Member' ? 'Board Member Details' : 'Key Member Details' }}
                        </h5>
                        <a href="{{ route('organization-structure.index') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>Back to List
                        </a>
                    </div>
                </div>

                <div class="card-body p-4">
                    <div class="row">
                        <!-- Profile Image Section -->
                        <div class="col-md-12 mb-4 text-center">
                            @php($isKey = ($member->member_type ?? '') === 'Key Member')
                            @php($displayName = $isKey && $member->getEmployee ? $member->getEmployee->full_name : $member->name)
                            @php($photoPath = $isKey && $member->getEmployee ? $member->getEmployee->photo_path : $member->photo_path)
                            {!! \App\HelperClass::generateAvatar(
                                $photoPath ?? null,
                                $displayName,
                                150,
                                '#974063',
                                'border border-3 border-primary shadow-sm',
                            ) !!}
                        </div>

                        <!-- Organization Information Section -->
                        <div class="col-md-12 mb-4">
                            <h6 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-briefcase me-2"></i>Organization Information
                            </h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="fw-semibold text-muted mb-1">Type:</label>
                                    <div>
                                        <span
                                            class="badge
                                            @if ($member->type_form == 'group') bg-primary
                                            @elseif($member->type_form == 'company') bg-success
                                            @elseif($member->type_form == 'location') bg-danger
                                            @elseif($member->type_form == 'division') bg-warning text-dark
                                            @elseif($member->type_form == 'department') bg-info
                                            @else bg-secondary @endif">
                                            {{ $member->type }}
                                        </span>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="fw-semibold text-muted mb-1">Status:</label>
                                    <div>
                                        @if ($member->status_form == 'active')
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-danger">Inactive</span>
                                        @endif
                                    </div>
                                </div>

                                @if ($member->getGroup)
                                    <div class="col-md-6 mb-3">
                                        <label class="fw-semibold text-muted mb-1">Group:</label>
                                        <p class="mb-0">{{ $member->getGroup->name }}</p>
                                    </div>
                                @endif

                                @if ($member->getCompany)
                                    <div class="col-md-6 mb-3">
                                        <label class="fw-semibold text-muted mb-1">Company:</label>
                                        <p class="mb-0">{{ $member->getCompany->name }}</p>
                                    </div>
                                @endif

                                @if ($member->getBranchUnit)
                                    <div class="col-md-6 mb-3">
                                        <label class="fw-semibold text-muted mb-1">Location:</label>
                                        <p class="mb-0">{{ $member->getBranchUnit->unit_name }}</p>
                                    </div>
                                @endif

                                @if ($member->getDivision)
                                    <div class="col-md-6 mb-3">
                                        <label class="fw-semibold text-muted mb-1">Division:</label>
                                        <p class="mb-0">{{ $member->getDivision->division_name }}</p>
                                    </div>
                                @endif

                                @if ($member->getDepartment)
                                    <div class="col-md-6 mb-3">
                                        <label class="fw-semibold text-muted mb-1">Department:</label>
                                        <p class="mb-0">{{ $member->getDepartment->department_name }}</p>
                                    </div>
                                @endif

                                @if ($member->getSection)
                                    <div class="col-md-6 mb-3">
                                        <label class="fw-semibold text-muted mb-1">Section:</label>
                                        <p class="mb-0">{{ $member->getSection->section_name }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Personal Information Section -->
                        <div class="col-md-12 mb-4">
                            <h6 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-user me-2"></i>Personal Information
                            </h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="fw-semibold text-muted mb-1">Name:</label>
                                    <p class="mb-0">{{ $member->name }}</p>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="fw-semibold text-muted mb-1">Position:</label>
                                    <p class="mb-0">{{ $member->position }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information Section -->
                        <div class="col-md-12 mb-4">
                            <h6 class="text-primary border-bottom pb-2 mb-3">
                                <i class="fas fa-phone me-2"></i>Contact Information
                            </h6>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="fw-semibold text-muted mb-1">Email:</label>
                                    <p class="mb-0">
                                        @if ($isKey && $member->getEmployee && $member->getEmployee->work_email)
                                            <a href="mailto:{{ $member->getEmployee->work_email }}">
                                                <i class="fas fa-envelope me-1"></i>{{ $member->getEmployee->work_email }}
                                            </a>
                                        @elseif ($isKey && $member->getEmployee && $member->getEmployee->personal_email)
                                            <a href="mailto:{{ $member->getEmployee->personal_email }}">
                                                <i
                                                    class="fas fa-envelope me-1"></i>{{ $member->getEmployee->personal_email }}
                                            </a>
                                        @elseif (!empty($member->email))
                                            <a href="mailto:{{ $member->email }}">
                                                <i class="fas fa-envelope me-1"></i>{{ $member->email }}
                                            </a>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </p>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="fw-semibold text-muted mb-1">Phone:</label>
                                    <p class="mb-0">
                                        @if ($isKey && $member->getEmployee && $member->getEmployee->work_mobile)
                                            <a href="tel:{{ $member->getEmployee->work_mobile }}">
                                                <i class="fas fa-phone me-1"></i>{{ $member->getEmployee->work_mobile }}
                                            </a>
                                        @elseif ($isKey && $member->getEmployee && $member->getEmployee->personal_mobile)
                                            <a href="tel:{{ $member->getEmployee->personal_mobile }}">
                                                <i
                                                    class="fas fa-phone me-1"></i>{{ $member->getEmployee->personal_mobile }}
                                            </a>
                                        @elseif (!empty($member->contact_no))
                                            <a href="tel:{{ $member->contact_no }}">
                                                <i class="fas fa-phone me-1"></i>{{ $member->contact_no }}
                                            </a>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </p>
                                </div>

                                @if ($member->address)
                                    <div class="col-md-12 mb-3">
                                        <label class="fw-semibold text-muted mb-1">Address:</label>
                                        <p class="mb-0">{{ $member->address }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-end gap-2 border-top pt-3">
                        <a href="{{ route('organization-structure.index') }}" class="btn btn-secondary px-4">
                            <i class="fas fa-arrow-left me-1"></i>Back
                        </a>
                        <a href="{{ route('organization-structure.edit', $member->id) }}" class="btn btn-primary px-4">
                            <i class="fas fa-edit me-1"></i>Edit
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
