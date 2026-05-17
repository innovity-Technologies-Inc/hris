@extends('structure.master')

@section('content')
<div class="row">
    <div class="col-12">
        <form action="{{ isset($role) ? route('settings.roles.update', $role->id) : route('settings.roles.store') }}" method="POST">
            @csrf
            @if(isset($role))
                @method('PUT')
            @endif

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">{{ isset($role) ? 'Edit Role' : 'Create Role' }}</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12 mb-3">
                            <label for="name" class="form-label fw-bold">Role Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" 
                                value="{{ isset($role) ? $role->name : old('name') }}" placeholder="Enter role name (e.g. Manager)">
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Menu Permissions</h5>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="selectAll">
                        <label class="form-check-label text-white" for="selectAll">Select All</label>
                    </div>
                </div>
                <div class="card-body">
                    <div class="accordion" id="menuAccordion">
                        @foreach($menus as $menu)
                        <div class="accordion-item border-0 mb-3 shadow-sm rounded">
                            <h2 class="accordion-header" id="heading{{ $menu->id }}">
                                <button class="accordion-button bg-light py-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $menu->id }}">
                                    <i class="fas fa-{{ $menu->icon ?? 'circle' }} me-2 text-primary"></i>
                                    <strong>{{ $menu->name }}</strong>
                                </button>
                            </h2>
                            <div id="collapse{{ $menu->id }}" class="accordion-collapse collapse show">
                                <div class="accordion-body p-3">
                                    {{-- Parent Menu Permissions - Only show if NO submenus --}}
                                    @if($menu->submenus->count() == 0)
                                    <div class="row align-items-center mb-2 pb-2 border-bottom">
                                        <div class="col-md-4">
                                            <span class="text-muted fw-bold">Main Menu</span>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="d-flex flex-wrap gap-3">
                                                @php
                                                    $matchingPermissions = $allPermissions->filter(function($perm) use ($menu) {
                                                        return str_starts_with($perm->name, $menu->slug . '.');
                                                    });
                                                @endphp
                                                @foreach($matchingPermissions as $permission)
                                                @php $action = str_replace($menu->slug . '.', '', $permission->name); @endphp
                                                <div class="form-check">
                                                    <input class="form-check-input perm-check" type="checkbox" name="permissions[]" 
                                                        value="{{ $permission->name }}" id="perm{{ $menu->id }}{{ $action }}"
                                                        {{ (isset($rolePermissions) && in_array($permission->name, $rolePermissions)) ? 'checked' : '' }}>
                                                    <label class="form-check-label text-capitalize" for="perm{{ $menu->id }}{{ $action }}">
                                                        {{ str_replace('-', ' ', $action) }}
                                                    </label>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    @endif

                                    {{-- Submenu Permissions --}}
                                    @foreach($menu->submenus as $submenu)
                                    <div class="row align-items-center mb-1 ms-4">
                                        <div class="col-md-4">
                                            <i class="fas fa-arrow-right me-2 text-muted small"></i>
                                            <span>{{ $submenu->name }}</span>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="d-flex flex-wrap gap-3">
                                                @php
                                                    $matchingSubPermissions = $allPermissions->filter(function($perm) use ($submenu) {
                                                        return str_starts_with($perm->name, $submenu->slug . '.');
                                                    });
                                                @endphp
                                                @foreach($matchingSubPermissions as $permission)
                                                @php $action = str_replace($submenu->slug . '.', '', $permission->name); @endphp
                                                <div class="form-check">
                                                    <input class="form-check-input perm-check" type="checkbox" name="permissions[]" 
                                                        value="{{ $permission->name }}" id="permSub{{ $submenu->id }}{{ $action }}"
                                                        {{ (isset($rolePermissions) && in_array($permission->name, $rolePermissions)) ? 'checked' : '' }}>
                                                    <label class="form-check-label text-capitalize" for="permSub{{ $submenu->id }}{{ $action }}">
                                                        {{ str_replace('-', ' ', $action) }}
                                                    </label>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="card-footer bg-light d-flex justify-content-end gap-2">
                    <a href="{{ route('settings.roles.index') }}" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fas fa-save me-1"></i> {{ isset($role) ? 'Update Role' : 'Save Role' }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        $('#selectAll').on('change', function() {
            $('.perm-check').prop('checked', $(this).prop('checked'));
        });
    });
</script>
@endpush
@endsection
