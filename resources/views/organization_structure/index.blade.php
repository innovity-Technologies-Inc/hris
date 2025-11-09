@extends('structure.master')

@section('content')
    {{-- List of Organization Structure Key Members --}}
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <a type="button" class="btn btn-warning btn-sm" href="{{ route('organization-structure.create') }}">
                        <i style="height: 12px; width: 12px" data-feather="plus"></i> Create
                    </a>
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
                                    <th scope="col">Designation</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Contact</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php($i = 1)
                                @foreach ($organizationStructures as $member)
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
                                                @elseif($member->type_form == 'location') bg-danger
                                                @elseif($member->type_form == 'division') bg-warning text-dark
                                                @elseif($member->type_form == 'department') bg-info
                                                @else bg-secondary @endif">
                                                {{ $member->type }}
                                            </span>
                                        </td>
                                        <td>{{ $member->name }}</td>
                                        <td>{{ $member->designation }}</td>
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
                            </tbody>
                        </table>
                    </div>
                </div>
            </div><!-- end card -->
        </div>
    </div><!-- end row -->
@endsection
