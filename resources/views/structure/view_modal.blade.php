<!-- View Modal -->
<div class="modal fade" id="viewModal{{ $member->id }}" tabindex="-1" aria-labelledby="viewModalLabel{{ $member->id }}"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="viewModalLabel{{ $member->id }}">
                    <i data-feather="user" style="height: 16px; width: 16px"></i>
                    {{ $member->member_type === 'Board Member' ? 'Board Member Details' : 'Key Member Details' }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Profile Image Section -->
                    <div class="col-md-12 mb-4 text-center">
                        @php($isKey = ($member->member_type ?? '') === 'Key Member')
                        @if ($isKey && $member->getEmployee && $member->getEmployee->photo_path)
                            <img src="{{ asset('storage/' . $member->getEmployee->photo_path) }}"
                                class="rounded-circle border-3 border-primary shadow-sm"
                                style="width: 120px; height: 120px; object-fit: cover; border: 3px solid;"
                                alt="Profile Image">
                        @elseif ($member->photo_path)
                            <img src="{{ asset('storage/' . $member->photo_path) }}"
                                class="rounded-circle border-3 border-primary shadow-sm"
                                style="width: 120px; height: 120px; object-fit: cover; border: 3px solid;"
                                alt="Profile Image">
                        @else
                            <div class="rounded-circle bg-secondary d-inline-flex align-items-center justify-content-center text-white shadow"
                                style="width: 120px; height: 120px; font-size: 48px; font-weight: bold;">
                                {{ strtoupper(substr($member->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>

                    <div class="col-md-12 mb-3">
                        <h6 class="text-primary border-bottom pb-2">
                            <i data-feather="briefcase" style="height: 14px; width: 14px"></i>
                            Organization Information
                        </h6>
                    </div>

                    <div class="col-md-6 mb-3">
                        <strong>Type:</strong>
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

                    <div class="col-md-6 mb-3">
                        <strong>Status:</strong>
                        @if ($member->status_form == 'active')
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-danger">Inactive</span>
                        @endif
                    </div>

                    @if ($member->getGroup)
                        <div class="col-md-6 mb-3">
                            <strong>Group:</strong>
                            <p class="mb-0">{{ $member->getGroup->name }}</p>
                        </div>
                    @endif

                    @if ($member->getCompany)
                        <div class="col-md-6 mb-3">
                            <strong>Company:</strong>
                            <p class="mb-0">{{ $member->getCompany->name }}</p>
                        </div>
                    @endif

                    @if ($member->getBranchUnit)
                        <div class="col-md-6 mb-3">
                            <strong>Location:</strong>
                            <p class="mb-0">{{ $member->getBranchUnit->unit_name }}</p>
                        </div>
                    @endif

                    @if ($member->getDivision)
                        <div class="col-md-6 mb-3">
                            <strong>Division:</strong>
                            <p class="mb-0">{{ $member->getDivision->division_name }}</p>
                        </div>
                    @endif

                    @if ($member->getDepartment)
                        <div class="col-md-6 mb-3">
                            <strong>Department:</strong>
                            <p class="mb-0">{{ $member->getDepartment->department_name }}</p>
                        </div>
                    @endif

                    @if ($member->getSection)
                        <div class="col-md-6 mb-3">
                            <strong>Section:</strong>
                            <p class="mb-0">{{ $member->getSection->section_name }}</p>
                        </div>
                    @endif

                    <div class="col-md-12 mb-3 mt-3">
                        <h6 class="text-primary border-bottom pb-2">
                            <i data-feather="user" style="height: 14px; width: 14px"></i>
                            Personal Information
                        </h6>
                    </div>

                    <div class="col-md-6 mb-3">
                        <strong>Name:</strong>
                        <p class="mb-0">{{ $member->name }}</p>
                    </div>

                    <div class="col-md-6 mb-3">
                        <strong>Position:</strong>
                        <p class="mb-0">{{ $member->position }}</p>
                    </div>

                    <div class="col-md-12 mb-3 mt-3">
                        <h6 class="text-primary border-bottom pb-2">
                            <i data-feather="phone" style="height: 14px; width: 14px"></i>
                            Contact Information
                        </h6>
                    </div>

                    <div class="col-md-6 mb-3">
                        <strong>Email:</strong>
                        <p class="mb-0">
                            @if ($isKey && $member->getEmployee && $member->getEmployee->work_email)
                                <a href="mailto:{{ $member->getEmployee->work_email }}">{{ $member->getEmployee->work_email }}</a>
                            @elseif ($isKey && $member->getEmployee && $member->getEmployee->personal_email)
                                <a href="mailto:{{ $member->getEmployee->personal_email }}">{{ $member->getEmployee->personal_email }}</a>
                            @elseif (!empty($member->email))
                                <a href="mailto:{{ $member->email }}">{{ $member->email }}</a>
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </p>
                    </div>

                    <div class="col-md-6 mb-3">
                        <strong>Phone:</strong>
                        <p class="mb-0">
                            @if ($isKey && $member->getEmployee && $member->getEmployee->work_mobile)
                                <a href="tel:{{ $member->getEmployee->work_mobile }}">{{ $member->getEmployee->work_mobile }}</a>
                            @elseif ($isKey && $member->getEmployee && $member->getEmployee->personal_mobile)
                                <a href="tel:{{ $member->getEmployee->personal_mobile }}">{{ $member->getEmployee->personal_mobile }}</a>
                            @elseif (!empty($member->contact_no))
                                <a href="tel:{{ $member->contact_no }}">{{ $member->contact_no }}</a>
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </p>
                    </div>

                    @if ($member->address)
                        <div class="col-md-12 mb-3">
                            <strong>Address:</strong>
                            <p class="mb-0">{{ $member->address }}</p>
                        </div>
                    @endif
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                    <i data-feather="x" style="height: 12px; width: 12px"></i> Close
                </button>
                <a href="{{ route('organization-structure.edit', $member->id) }}" class="btn btn-primary btn-sm">
                    <i data-feather="edit" style="height: 12px; width: 12px"></i> Edit
                </a>
            </div>
        </div>
    </div>
</div>

