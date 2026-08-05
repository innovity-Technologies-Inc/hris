<div class="table-responsive">
    <table class="table table-hover table-centered align-middle table-nowrap mb-0">
        <thead class="table-light">
            <tr>
                <th scope="col" style="width: 50px;">#</th>
                <th scope="col">Profile</th>
                <th scope="col">Type & Scope</th>
                <th scope="col">Name / Employee</th>
                <th scope="col">Position</th>
                <th scope="col">Contact</th>
                <th scope="col">Status</th>
                <th scope="col" style="width: 120px;">Action</th>
            </tr>
        </thead>
        <tbody>
            @php($i = ($keyPeople->currentPage() - 1) * $keyPeople->perPage() + 1)
            @foreach ($keyPeople as $member)
                <tr>
                    <th scope="row" class="align-middle fw-medium">{{ $i++ }}</th>
                    <td class="align-middle">
                        @php($displayName = $member->getEmployee ? $member->getEmployee->full_name : $member->name)
                        @php($photoPath = $member->photo_path ?? ($member->getEmployee ? $member->getEmployee->photo_path : null))
                        @php($employeeId = $member->getEmployee ? $member->getEmployee->id : null)
                        {!! \App\HelperClass::generateAvatar($photoPath, $displayName, 38, '#974063', 'rounded-circle border border-2 border-primary-subtle shadow-sm', $employeeId) !!}
                    </td>
                    <td class="align-middle">
                        <span class="badge rounded-pill
                            @if ($member->type_form == 'group') bg-primary
                            @elseif($member->type_form == 'company') bg-success
                            @elseif($member->type_form == 'location') bg-danger
                            @elseif($member->type_form == 'division') bg-warning text-dark
                            @elseif($member->type_form == 'department') bg-info
                            @else bg-secondary @endif">
                            {{ $member->type }}
                        </span>
                        <br>
                        <small class="text-muted fw-semibold d-inline-block mt-1">
                            @if($member->type == 'Group')
                                {{ $member->getGroup?->name }}
                            @elseif($member->type == 'Company')
                                {{ $member->getCompany?->name }}
                            @elseif($member->type == 'Branch Unit')
                                {{ $member->getBranchUnit?->name }}
                            @elseif($member->type == 'Division')
                                {{ $member->getDivision?->name }}
                            @elseif($member->type == 'Department')
                                {{ $member->getDepartment?->name }}
                            @elseif($member->type == 'Section')
                                {{ $member->getSection?->name }}
                            @endif
                        </small>
                    </td>
                    <td class="align-middle">
                        @if ($member->getEmployee)
                            <a href="{{ route('employee.profile.general_informations', $member->getEmployee->id) }}"
                                class="text-decoration-none fw-semibold text-primary d-inline-block mb-1">
                                {{ $displayName }}
                            </a>
                            <br><small class="text-muted"><i class="fas fa-id-badge me-1"></i>ID: {{ $member->getEmployee->system_id }}</small>
                        @else
                            <span class="fw-semibold text-dark">{{ $displayName }}</span>
                            <br><small class="text-muted"><i class="fas fa-user-tag me-1"></i>External / Custom</small>
                        @endif
                    </td>
                    <td class="align-middle fw-medium">{{ $member->position }}</td>
                    <td class="align-middle">
                        @php($email = $member->getEmployee ? ($member->getEmployee->work_email ?? $member->getEmployee->personal_email) : $member->email)
                        @php($phone = $member->getEmployee ? ($member->getEmployee->work_mobile ?? $member->getEmployee->personal_mobile) : $member->contact_no)
                        <span class="d-block mb-1">
                            <i class="fas fa-envelope text-muted me-1"></i>{{ $email ?? 'N/A' }}
                        </span>
                        <span class="d-block">
                            <i class="fas fa-phone text-muted me-1"></i>{{ $phone ?? 'N/A' }}
                        </span>
                    </td>
                    <td class="align-middle">
                        @if ($member->status_form == 'active')
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">Active</span>
                        @else
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1">Inactive</span>
                        @endif
                    </td>
                    <td class="align-middle">
                        <div class="d-flex gap-2">
                            <a href="{{ route('organization-structure.show', $member->id) }}"
                                class="btn btn-soft-info btn-sm rounded-circle d-flex align-items-center justify-content-center" 
                                style="width: 32px; height: 32px;" title="View">
                                <i class="fas fa-eye text-info"></i>
                            </a>

                            <a href="{{ route('organization-structure.edit', $member->id) }}"
                                class="btn btn-soft-primary btn-sm rounded-circle d-flex align-items-center justify-content-center" 
                                style="width: 32px; height: 32px;" title="Edit">
                                <i class="fas fa-edit text-primary"></i>
                            </a>

                            <button class="btn btn-soft-danger btn-sm rounded-circle d-flex align-items-center justify-content-center delete-person"
                                data-id="{{ $member->id }}"
                                data-url="{{ route('organization-structure.destroy', $member->id) }}"
                                style="width: 32px; height: 32px;" title="Delete">
                                <i class="fas fa-trash text-danger"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            @endforeach
            @if ($keyPeople->isEmpty())
                <tr>
                    <td colspan="8" class="text-center text-muted py-5">
                        <div class="mb-3">
                            <i class="fas fa-users-slash fa-3x text-muted-light"></i>
                        </div>
                        <h5>No Key People Found</h5>
                        <p class="text-muted-light mb-0">Try adjusting your search criteria or add new key people.</p>
                    </td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="mt-4 d-flex justify-content-between align-items-center">
        <small class="text-muted">Showing {{ $keyPeople->firstItem() ?? 0 }} to {{ $keyPeople->lastItem() ?? 0 }} of {{ $keyPeople->total() }} records</small>
        <div>
            {{ $keyPeople->links() }}
        </div>
    </div>
</div>
