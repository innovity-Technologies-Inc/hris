@extends('structure.master')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0 rounded">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-user-shield me-2"></i>Role Management</h5>
                <a href="{{ route('setting.roles.create') }}" class="btn btn-light btn-sm">
                    <i class="fas fa-plus me-1"></i>Add New Role
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" style="width: 50px;">#</th>
                                <th>Role Name</th>
                                <th>Permissions Count</th>
                                <th class="text-center" style="width: 150px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($roles as $index => $role)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>
                                    <span class="fw-bold text-primary">{{ $role->name }}</span>
                                    @if($role->name === 'Super Admin')
                                        <span class="badge bg-danger ms-2">System Reserved</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $role->permissions->count() }} Permissions</span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="{{ route('setting.roles.edit', $role->id) }}" class="btn btn-info btn-sm" title="Edit Permissions">
                                            <i style="height: 12px; width: 12px" data-feather="edit-2"></i>
                                        </a>
                                        @if($role->name !== 'Super Admin')
                                        <form action="{{ route('setting.roles.destroy', $role->id) }}" method="POST" class="d-inline confirmDelete">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm ms-1" title="Delete Role">
                                                <i style="height: 12px; width: 12px" data-feather="trash-2"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">No roles found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

