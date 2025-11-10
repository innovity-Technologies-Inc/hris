@extends('structure.master')

@section('content')
    <a href="{{ route('organization-structure.create') }}" class="btn btn-warning btn-sm mb-3">
        <i style="height: 12px; width: 12px" data-feather="plus"></i> Create
    </a>

    <ul class="nav nav-tabs mb-3" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="tab-board-tab" data-bs-toggle="tab" data-bs-target="#tab-board" type="button" role="tab" aria-controls="tab-board" aria-selected="true">
                <i class="fas fa-users-cog me-2"></i>Board Members
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="tab-key-tab" data-bs-toggle="tab" data-bs-target="#tab-key" type="button" role="tab" aria-controls="tab-key" aria-selected="false">
                <i class="fas fa-user-tie me-2"></i>Key Peoples
            </button>
        </li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane fade show active" id="tab-board" role="tabpanel" aria-labelledby="tab-board-tab">
    {{-- Board Members Section --}}
    <div class="row mb-4">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-users-cog me-2"></i>Board Members</h5>
                </div><!-- end card header -->

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Profile</th>
                                    <th scope="col">Type</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Position</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Contact</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php($i = 1)
                                @foreach ($organizationStructures->where('member_type', 'Board Member') as $member)
                                    <tr>
                                        <th scope="row">{{ $i++ }}</th>
                                        <td>
                                            @if ($member->photo_path)
                                                <img src="{{ asset('storage/' . $member->photo_path) }}"
                                                    class="rounded-circle"
                                                    style="width: 32px; height: 32px; object-fit: cover;" alt="Profile">
                                            @else
                                                <div class="rounded-circle bg-secondary d-inline-flex align-items-center justify-content-center text-white"
                                                    style="width: 32px; height: 32px; font-size: 14px;">
                                                    {{ strtoupper(substr($member->name, 0, 1)) }}
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <span
                                                class="badge
                                                @if ($member->type_form == 'group') bg-primary
                                                @elseif($member->type_form == 'company') bg-success
                                                @else bg-secondary @endif">
                                                {{ $member->type }}
                                            </span>
                                        </td>
                                        <td>{{ $member->name }}</td>
                                        <td>{{ $member->position }}</td>
                                        <td>{{ $member->email }}</td>
                                        <td>{{ $member->contact_no }}</td>
                                        <td>
                                            @if ($member->status_form == 'active')
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#viewModal{{ $member->id }}" title="View">
                                                <i style="height: 12px; width: 12px" data-feather="eye"></i>
                                            </button>

                                            <a href="{{ route('organization-structure.edit', $member->id) }}"
                                                class="btn btn-primary btn-sm" title="Edit">
                                                <i style="height: 12px; width: 12px" data-feather="edit"></i>
                                            </a>

                                            <form action="{{ route('organization-structure.destroy', $member->id) }}"
                                                method="POST" style="display: inline-block">
                                                @csrf
                                                @method('DELETE')

                                                <button class="btn btn-sm btn-danger confirmDelete" title="Delete">
                                                    <i style="height: 12px; width: 12px" data-feather="trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>

                                    @include('organization_structure.view_modal', ['member' => $member])
                                @endforeach
                                @if ($organizationStructures->where('member_type', 'Board Member')->isEmpty())
                                    <tr>
                                        <td colspan="9" class="text-center text-muted">No Board Members found</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div><!-- end card -->
        </div>
    </div><!-- end row -->

        </div> <!-- end tab-board -->

        <div class="tab-pane fade" id="tab-key" role="tabpanel" aria-labelledby="tab-key-tab">
    {{-- Key Members Section --}}
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-user-tie me-2"></i>Key Members</h5>
                </div><!-- end card header -->

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Profile</th>
                                    <th scope="col">Type</th>
                                    <th scope="col">Employee</th>
                                    <th scope="col">Position</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Contact</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php($i = 1)
                                @foreach ($organizationStructures->where('member_type', 'Key Member') as $member)
                                    <tr>
                                        <th scope="row">{{ $i++ }}</th>
                                        <td>
                                            @if ($member->getEmployee && $member->getEmployee->photo_path)
                                                <img src="{{ asset('storage/' . $member->getEmployee->photo_path) }}"
                                                    class="rounded-circle"
                                                    style="width: 32px; height: 32px; object-fit: cover;" alt="Profile">
                                            @elseif ($member->photo_path)
                                                <img src="{{ asset('storage/' . $member->photo_path) }}"
                                                    class="rounded-circle"
                                                    style="width: 32px; height: 32px; object-fit: cover;" alt="Profile">
                                            @else
                                                <div class="rounded-circle bg-secondary d-inline-flex align-items-center justify-content-center text-white"
                                                    style="width: 32px; height: 32px; font-size: 14px;">
                                                    {{ strtoupper(substr($member->name, 0, 1)) }}
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <span
                                                class="badge
                                                @if ($member->type_form == 'location') bg-danger
                                                @elseif($member->type_form == 'division') bg-warning text-dark
                                                @elseif($member->type_form == 'department') bg-info
                                                @else bg-secondary @endif">
                                                {{ $member->type }}
                                            </span>
                                        </td>
                                        <td>
                                            {{ $member->name }}
                                            @if ($member->getEmployee)
                                                <br><small class="text-muted">ID:
                                                    {{ $member->getEmployee->system_id }}</small>
                                            @endif
                                        </td>
                                        <td>{{ $member->position }}</td>
                                        <td>
                                            @if ($member->getEmployee && $member->getEmployee->work_email)
                                                {{ $member->getEmployee->work_email }}
                                            @elseif ($member->getEmployee && $member->getEmployee->personal_email)
                                                {{ $member->getEmployee->personal_email }}
                                            @else
                                                {{ $member->email ?? 'N/A' }}
                                            @endif
                                        </td>
                                        <td>
                                            @if ($member->getEmployee && $member->getEmployee->work_mobile)
                                                {{ $member->getEmployee->work_mobile }}
                                            @elseif ($member->getEmployee && $member->getEmployee->personal_mobile)
                                                {{ $member->getEmployee->personal_mobile }}
                                            @else
                                                {{ $member->contact_no ?? 'N/A' }}
                                            @endif
                                        </td>
                                        <td>
                                            @if ($member->status_form == 'active')
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#viewModal{{ $member->id }}" title="View">
                                                <i style="height: 12px; width: 12px" data-feather="eye"></i>
                                            </button>

                                            <a href="{{ route('organization-structure.edit', $member->id) }}"
                                                class="btn btn-primary btn-sm" title="Edit">
                                                <i style="height: 12px; width: 12px" data-feather="edit"></i>
                                            </a>

                                            <form action="{{ route('organization-structure.destroy', $member->id) }}"
                                                method="POST" style="display: inline-block">
                                                @csrf
                                                @method('DELETE')

                                                <button class="btn btn-sm btn-danger confirmDelete" title="Delete">
                                                    <i style="height: 12px; width: 12px" data-feather="trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>

                                    @include('organization_structure.view_modal', ['member' => $member])
                                @endforeach
                                @if ($organizationStructures->where('member_type', 'Key Member')->isEmpty())
                                    <tr>
                                        <td colspan="9" class="text-center text-muted">No Key Members found</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div><!-- end card -->
        </div>
    </div><!-- end row -->
        </div> <!-- end tab-key -->
    </div> <!-- end tab-content -->
@endsection
