@extends('structure.master')

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Edit Approval Workflow</h5>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <form id="workflowForm">
                                @csrf
                                <input type="hidden" name="_method" value="PUT">

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="module_name" class="form-label">Module <span class="text-danger">*</span></label>
                                        <select name="module_name" id="module_name" class="form-select" required>
                                            <option value="" disabled>Select Module</option>
                                            @foreach($modules as $key => $label)
                                                <option value="{{ $key }}" {{ old('module_name', strtolower($workflow->module)) == $key ? 'selected' : '' }}>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="type" class="form-label">Workflow Type <span class="text-danger">*</span></label>
                                        <select name="type" id="type" class="form-select" required>
                                            <option value="sequential" {{ old('type', $workflow->type->value) == 'sequential' ? 'selected' : '' }}>Sequential (Step-by-Step)</option>
                                            <option value="random" {{ old('type', $workflow->type->value) == 'random' ? 'selected' : '' }}>Random (Parallel)</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-3" id="requiredApprovalsWrapper" style="display: none;">
                                    <div class="col-md-12">
                                        <label for="required_approvals" class="form-label">Required Approvals <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="number" class="form-control" name="required_approvals" id="required_approvals" value="{{ old('required_approvals', $workflow->required_approvals ?? 1) }}" min="1">
                                            <span class="input-group-text bg-light text-muted" id="totalStepsDisplay">out of X total steps</span>
                                        </div>
                                        <small class="text-muted">How many approvers must approve this for the entire workflow to pass?</small>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label for="is_active" class="form-label">Status</label>
                                        <select class="form-select" name="is_active" id="is_active">
                                            <option value="1" {{ old('is_active', $workflow->is_active ? '1' : '0') == '1' ? 'selected' : '' }}>Active</option>
                                            <option value="0" {{ old('is_active', $workflow->is_active ? '1' : '0') == '0' ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                    </div>
                                </div>

                                @php
                                    $scopeType = 'all';
                                    if (is_array($workflow->includer_user_ids) && !empty($workflow->includer_user_ids)) {
                                        $scopeType = 'specific_user';
                                    } elseif (is_array($workflow->includer_user_types) && !empty($workflow->includer_user_types) && is_array($workflow->includer_role_ids) && !empty($workflow->includer_role_ids)) {
                                        $scopeType = 'user_type_role';
                                    } elseif (is_array($workflow->includer_role_ids) && !empty($workflow->includer_role_ids)) {
                                        $scopeType = 'role';
                                    } elseif (is_array($workflow->includer_user_types) && !empty($workflow->includer_user_types)) {
                                        $scopeType = 'user_type';
                                    }

                                    $excludeScopeType = 'none';
                                    if (is_array($workflow->exclude_user_ids) && !empty($workflow->exclude_user_ids)) {
                                        $excludeScopeType = 'specific_user';
                                    } elseif (is_array($workflow->exclude_user_types) && !empty($workflow->exclude_user_types) && is_array($workflow->exclude_role_ids) && !empty($workflow->exclude_role_ids)) {
                                        $excludeScopeType = 'user_type_role';
                                    } elseif (is_array($workflow->exclude_role_ids) && !empty($workflow->exclude_role_ids)) {
                                        $excludeScopeType = 'role';
                                    } elseif (is_array($workflow->exclude_user_types) && !empty($workflow->exclude_user_types)) {
                                        $excludeScopeType = 'user_type';
                                    }
                                @endphp

                                <div class="card mb-4 border-dashed bg-light mt-4">
                                    <div class="card-header bg-transparent border-0 pb-0">
                                        <h6 class="card-title mb-0 fw-semibold text-primary">Approval Request Filters (Inclusions & Exclusions)</h6>
                                        <small class="text-muted">Configure who this workflow applies to, and who bypasses it entirely.</small>
                                    </div>
                                    <div class="card-body">
                                        <!-- Guidelines Note -->
                                        <div class="alert alert-warning-subtle border-warning-subtle text-warning mb-4" style="font-size: 0.85rem; background-color: rgba(255, 193, 7, 0.05); border: 1px solid rgba(255, 193, 7, 0.15); border-radius: 8px; padding: 12px;">
                                            <div class="d-flex gap-2">
                                                <i class="mdi mdi-information-outline fs-5 align-middle"></i>
                                                <div>
                                                    <strong class="d-block mb-1">How Filtering Works:</strong>
                                                    <ul class="mb-0 ps-3">
                                                        <li><strong>Who Needs Approval?</strong> If you add any User Type, Role, or Specific User here, requests created by them <strong>must need approval</strong>. All other users' requests will bypass approval and be auto-approved. If you leave this list empty, requests from <strong>all users</strong> will require approval by default.</li>
                                                        <li><strong>Who Bypasses Approval?</strong> If you add any User Type, Role, or Specific User here, requests created by them will <strong>bypass the approval workflow entirely (auto-approve)</strong>. These filters override the approval rules above.</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Inclusion Section -->
                                        <div class="border-bottom pb-4 mb-4">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="badge bg-success-subtle text-success fw-semibold" style="font-size: 0.9rem; padding: 6px 12px;">1. Who Needs Approval? (Inclusion Filters - Default: All Users)</span>
                                                <button type="button" class="btn btn-xs btn-outline-success" id="addIncluderBtn">
                                                    <i style="height: 12px; width: 12px" data-feather="plus"></i> Add Inclusion Rule
                                                </button>
                                            </div>
                                            <div id="includersContainer" class="mt-2">
                                                <!-- Includer criteria rows will be injected here -->
                                                @if($scopeType === 'user_type' && is_array($workflow->includer_user_types))
                                                    @foreach($workflow->includer_user_types as $val)
                                                        <div class="card mb-2 criteria-row includer-row bg-light border shadow-none">
                                                            <div class="card-body p-2">
                                                                <div class="d-flex align-items-center gap-2">
                                                                    <div style="min-width: 150px;">
                                                                        <select class="form-select criteria-type-select" required>
                                                                            <option value="user-type" selected>User Type</option>
                                                                            <option value="role">User Role</option>
                                                                            <option value="user_type_role">User Type + Role</option>
                                                                            <option value="specific-user">Specific User</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="flex-grow-1 user-type-wrapper">
                                                                        <select class="form-select user-type-select" required>
                                                                            @foreach($userTypes as $type)
                                                                                <option value="{{ $type->value }}" {{ $type->value === $val ? 'selected' : '' }}>{{ $type->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="flex-grow-1 role-wrapper" style="display: none;">
                                                                        <select class="form-select role-select">
                                                                            <option value="" disabled selected>Select Role</option>
                                                                            @foreach($roles as $role)
                                                                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="flex-grow-1 user-wrapper" style="display: none;">
                                                                        <select class="form-select user-select">
                                                                            <option value="" disabled selected>Select Specific User</option>
                                                                            @foreach($users as $user)
                                                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div>
                                                                        <button type="button" class="btn btn-sm btn-danger remove-criteria-btn">
                                                                            <i style="height: 14px; width: 14px" data-feather="x"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @elseif($scopeType === 'role' && is_array($workflow->includer_role_ids))
                                                    @foreach($workflow->includer_role_ids as $val)
                                                        <div class="card mb-2 criteria-row includer-row bg-light border shadow-none">
                                                            <div class="card-body p-2">
                                                                <div class="d-flex align-items-center gap-2">
                                                                    <div style="min-width: 150px;">
                                                                        <select class="form-select criteria-type-select" required>
                                                                            <option value="user-type">User Type</option>
                                                                            <option value="role" selected>User Role</option>
                                                                            <option value="user_type_role">User Type + Role</option>
                                                                            <option value="specific-user">Specific User</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="flex-grow-1 user-type-wrapper" style="display: none;">
                                                                        <select class="form-select user-type-select">
                                                                            <option value="" disabled selected>Select User Type</option>
                                                                            @foreach($userTypes as $type)
                                                                                <option value="{{ $type->value }}">{{ $type->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="flex-grow-1 role-wrapper">
                                                                        <select class="form-select role-select" required>
                                                                            @foreach($roles as $role)
                                                                                <option value="{{ $role->id }}" {{ $role->id == $val ? 'selected' : '' }}>{{ $role->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="flex-grow-1 user-wrapper" style="display: none;">
                                                                        <select class="form-select user-select">
                                                                            <option value="" disabled selected>Select Specific User</option>
                                                                            @foreach($users as $user)
                                                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div>
                                                                        <button type="button" class="btn btn-sm btn-danger remove-criteria-btn">
                                                                            <i style="height: 14px; width: 14px" data-feather="x"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @elseif($scopeType === 'user_type_role' && is_array($workflow->includer_user_types) && is_array($workflow->includer_role_ids))
                                                    @foreach($workflow->includer_user_types as $index => $typeVal)
                                                        @php
                                                            $roleVal = $workflow->includer_role_ids[$index] ?? null;
                                                        @endphp
                                                        <div class="card mb-2 criteria-row includer-row bg-light border shadow-none">
                                                            <div class="card-body p-2">
                                                                <div class="d-flex align-items-center gap-2">
                                                                    <div style="min-width: 150px;">
                                                                        <select class="form-select criteria-type-select" required>
                                                                            <option value="user-type">User Type</option>
                                                                            <option value="role">User Role</option>
                                                                            <option value="user_type_role" selected>User Type + Role</option>
                                                                            <option value="specific-user">Specific User</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="flex-grow-1 user-type-wrapper">
                                                                        <select class="form-select user-type-select" required>
                                                                            @foreach($userTypes as $type)
                                                                                <option value="{{ $type->value }}" {{ $type->value === $typeVal ? 'selected' : '' }}>{{ $type->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="flex-grow-1 role-wrapper">
                                                                        <select class="form-select role-select" required>
                                                                            @foreach($roles as $role)
                                                                                <option value="{{ $role->id }}" {{ $role->id == $roleVal ? 'selected' : '' }}>{{ $role->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="flex-grow-1 user-wrapper" style="display: none;">
                                                                        <select class="form-select user-select">
                                                                            <option value="" disabled selected>Select Specific User</option>
                                                                            @foreach($users as $user)
                                                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div>
                                                                        <button type="button" class="btn btn-sm btn-danger remove-criteria-btn">
                                                                            <i style="height: 14px; width: 14px" data-feather="x"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @elseif($scopeType === 'specific_user' && is_array($workflow->includer_user_ids))
                                                    @foreach($workflow->includer_user_ids as $val)
                                                        <div class="card mb-2 criteria-row includer-row bg-light border shadow-none">
                                                            <div class="card-body p-2">
                                                                <div class="d-flex align-items-center gap-2">
                                                                    <div style="min-width: 150px;">
                                                                        <select class="form-select criteria-type-select" required>
                                                                            <option value="user-type">User Type</option>
                                                                            <option value="role">User Role</option>
                                                                            <option value="user_type_role">User Type + Role</option>
                                                                            <option value="specific-user" selected>Specific User</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="flex-grow-1 user-type-wrapper" style="display: none;">
                                                                        <select class="form-select user-type-select">
                                                                            <option value="" disabled selected>Select User Type</option>
                                                                            @foreach($userTypes as $type)
                                                                                <option value="{{ $type->value }}">{{ $type->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="flex-grow-1 role-wrapper" style="display: none;">
                                                                        <select class="form-select role-select">
                                                                            <option value="" disabled selected>Select Role</option>
                                                                            @foreach($roles as $role)
                                                                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="flex-grow-1 user-wrapper">
                                                                        <select class="form-select user-select" required>
                                                                            @foreach($users as $user)
                                                                                <option value="{{ $user->id }}" {{ $user->id == $val ? 'selected' : '' }}>{{ $user->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div>
                                                                        <button type="button" class="btn btn-sm btn-danger remove-criteria-btn">
                                                                            <i style="height: 14px; width: 14px" data-feather="x"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Exclusion Section -->
                                        <div>
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <span class="badge bg-danger-subtle text-danger fw-semibold" style="font-size: 0.9rem; padding: 6px 12px;">2. Who Bypasses Approval? (Exclusion Filters - Default: None)</span>
                                                <button type="button" class="btn btn-xs btn-outline-danger" id="addExcluderBtn">
                                                    <i style="height: 12px; width: 12px" data-feather="plus"></i> Add Exclusion Rule
                                                </button>
                                            </div>
                                            <div id="excludersContainer" class="mt-2">
                                                <!-- Excluder criteria rows will be injected here -->
                                                @if($excludeScopeType === 'user_type' && is_array($workflow->exclude_user_types))
                                                    @foreach($workflow->exclude_user_types as $val)
                                                        <div class="card mb-2 criteria-row excluder-row bg-light border shadow-none">
                                                            <div class="card-body p-2">
                                                                <div class="d-flex align-items-center gap-2">
                                                                    <div style="min-width: 150px;">
                                                                        <select class="form-select criteria-type-select" required>
                                                                            <option value="user-type" selected>User Type</option>
                                                                            <option value="role">User Role</option>
                                                                            <option value="user_type_role">User Type + Role</option>
                                                                            <option value="specific-user">Specific User</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="flex-grow-1 user-type-wrapper">
                                                                        <select class="form-select user-type-select" required>
                                                                            @foreach($userTypes as $type)
                                                                                <option value="{{ $type->value }}" {{ $type->value === $val ? 'selected' : '' }}>{{ $type->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="flex-grow-1 role-wrapper" style="display: none;">
                                                                        <select class="form-select role-select">
                                                                            <option value="" disabled selected>Select Role</option>
                                                                            @foreach($roles as $role)
                                                                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="flex-grow-1 user-wrapper" style="display: none;">
                                                                        <select class="form-select user-select">
                                                                            <option value="" disabled selected>Select Specific User</option>
                                                                            @foreach($users as $user)
                                                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div>
                                                                        <button type="button" class="btn btn-sm btn-danger remove-criteria-btn">
                                                                            <i style="height: 14px; width: 14px" data-feather="x"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @elseif($excludeScopeType === 'role' && is_array($workflow->exclude_role_ids))
                                                    @foreach($workflow->exclude_role_ids as $val)
                                                        <div class="card mb-2 criteria-row excluder-row bg-light border shadow-none">
                                                            <div class="card-body p-2">
                                                                <div class="d-flex align-items-center gap-2">
                                                                    <div style="min-width: 150px;">
                                                                        <select class="form-select criteria-type-select" required>
                                                                            <option value="user-type">User Type</option>
                                                                            <option value="role" selected>User Role</option>
                                                                            <option value="user_type_role">User Type + Role</option>
                                                                            <option value="specific-user">Specific User</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="flex-grow-1 user-type-wrapper" style="display: none;">
                                                                        <select class="form-select user-type-select">
                                                                            <option value="" disabled selected>Select User Type</option>
                                                                            @foreach($userTypes as $type)
                                                                                <option value="{{ $type->value }}">{{ $type->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="flex-grow-1 role-wrapper">
                                                                        <select class="form-select role-select" required>
                                                                            @foreach($roles as $role)
                                                                                <option value="{{ $role->id }}" {{ $role->id == $val ? 'selected' : '' }}>{{ $role->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="flex-grow-1 user-wrapper" style="display: none;">
                                                                        <select class="form-select user-select">
                                                                            <option value="" disabled selected>Select Specific User</option>
                                                                            @foreach($users as $user)
                                                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div>
                                                                        <button type="button" class="btn btn-sm btn-danger remove-criteria-btn">
                                                                            <i style="height: 14px; width: 14px" data-feather="x"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @elseif($excludeScopeType === 'user_type_role' && is_array($workflow->exclude_user_types) && is_array($workflow->exclude_role_ids))
                                                    @foreach($workflow->exclude_user_types as $index => $typeVal)
                                                        @php
                                                            $roleVal = $workflow->exclude_role_ids[$index] ?? null;
                                                        @endphp
                                                        <div class="card mb-2 criteria-row excluder-row bg-light border shadow-none">
                                                            <div class="card-body p-2">
                                                                <div class="d-flex align-items-center gap-2">
                                                                    <div style="min-width: 150px;">
                                                                        <select class="form-select criteria-type-select" required>
                                                                            <option value="user-type">User Type</option>
                                                                            <option value="role">User Role</option>
                                                                            <option value="user_type_role" selected>User Type + Role</option>
                                                                            <option value="specific-user">Specific User</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="flex-grow-1 user-type-wrapper">
                                                                        <select class="form-select user-type-select" required>
                                                                            @foreach($userTypes as $type)
                                                                                <option value="{{ $type->value }}" {{ $type->value === $typeVal ? 'selected' : '' }}>{{ $type->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="flex-grow-1 role-wrapper">
                                                                        <select class="form-select role-select" required>
                                                                            @foreach($roles as $role)
                                                                                <option value="{{ $role->id }}" {{ $role->id == $roleVal ? 'selected' : '' }}>{{ $role->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="flex-grow-1 user-wrapper" style="display: none;">
                                                                        <select class="form-select user-select">
                                                                            <option value="" disabled selected>Select Specific User</option>
                                                                            @foreach($users as $user)
                                                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div>
                                                                        <button type="button" class="btn btn-sm btn-danger remove-criteria-btn">
                                                                            <i style="height: 14px; width: 14px" data-feather="x"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @elseif($excludeScopeType === 'specific_user' && is_array($workflow->exclude_user_ids))
                                                    @foreach($workflow->exclude_user_ids as $val)
                                                        <div class="card mb-2 criteria-row excluder-row bg-light border shadow-none">
                                                            <div class="card-body p-2">
                                                                <div class="d-flex align-items-center gap-2">
                                                                    <div style="min-width: 150px;">
                                                                        <select class="form-select criteria-type-select" required>
                                                                            <option value="user-type">User Type</option>
                                                                            <option value="role">User Role</option>
                                                                            <option value="user_type_role">User Type + Role</option>
                                                                            <option value="specific-user" selected>Specific User</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="flex-grow-1 user-type-wrapper" style="display: none;">
                                                                        <select class="form-select user-type-select">
                                                                            <option value="" disabled selected>Select User Type</option>
                                                                            @foreach($userTypes as $type)
                                                                                <option value="{{ $type->value }}">{{ $type->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="flex-grow-1 role-wrapper" style="display: none;">
                                                                        <select class="form-select role-select">
                                                                            <option value="" disabled selected>Select Role</option>
                                                                            @foreach($roles as $role)
                                                                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="flex-grow-1 user-wrapper">
                                                                        <select class="form-select user-select" required>
                                                                            @foreach($users as $user)
                                                                                <option value="{{ $user->id }}" {{ $user->id == $val ? 'selected' : '' }}>{{ $user->name }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div>
                                                                        <button type="button" class="btn btn-sm btn-danger remove-criteria-btn">
                                                                            <i style="height: 14px; width: 14px" data-feather="x"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <hr>

                                <div class="d-flex justify-content-between align-items-center mb-3 mt-4">
                                    <h5 class="mb-0">Workflow Steps</h5>
                                    <button type="button" class="btn btn-sm btn-primary" id="addStepBtn">
                                        <i style="height: 12px; width: 12px" data-feather="plus"></i> Add Step
                                    </button>
                                </div>

                                <div id="stepsContainer">
                                    @foreach($workflow->steps as $index => $step)
                                        <div class="card mb-2 step-row bg-light border shadow-none">
                                            <div class="card-body p-3">
                                                <div class="d-flex align-items-center gap-3">
                                                    <div class="step-handle text-muted" style="cursor: grab;">
                                                        <i data-feather="menu"></i>
                                                    </div>
                                                    <div class="step-number fw-bold bg-white border rounded px-2 py-1 text-center" style="min-width: 40px;">
                                                        {{ $index + 1 }}
                                                    </div>
                                                    
                                                    <!-- Step Type -->
                                                    <div style="min-width: 150px;">
                                                        <select class="form-select step-type-select" name="steps[{{ $index }}][type]" required>
                                                            <option value="user-type" {{ ($step->type ?? 'user-type') === 'user-type' ? 'selected' : '' }}>User Type</option>
                                                            <option value="role" {{ ($step->type ?? '') === 'role' ? 'selected' : '' }}>User Role</option>
                                                            <option value="role-user" {{ ($step->type ?? '') === 'role-user' ? 'selected' : '' }}>User Type + Role</option>
                                                            <option value="specific-user" {{ ($step->type ?? '') === 'specific-user' ? 'selected' : '' }}>Specific User</option>
                                                        </select>
                                                    </div>

                                                    <!-- User Type Selection -->
                                                    <div class="flex-grow-1 user-type-wrapper" style="display: {{ in_array(($step->type ?? 'user-type'), ['user-type', 'role-user']) ? 'block' : 'none' }};">
                                                        <select class="form-select user-type-select" name="steps[{{ $index }}][required_user_type]" {{ in_array(($step->type ?? 'user-type'), ['user-type', 'role-user']) ? 'required' : '' }}>
                                                            <option value="" disabled selected>Select User Type</option>
                                                            @foreach($userTypes as $type)
                                                                 <option value="{{ $type->value }}" {{ $step->required_user_type === $type->value ? 'selected' : '' }}>
                                                                    {{ ucfirst(str_replace('-', ' ', $type->value)) }}
                                                                 </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <!-- Spatie Role Selection -->
                                                    <div class="flex-grow-1 role-wrapper" style="display: {{ in_array(($step->type ?? ''), ['role', 'role-user']) ? 'block' : 'none' }};">
                                                        <select class="form-select role-select" name="steps[{{ $index }}][role_id]" {{ in_array(($step->type ?? ''), ['role', 'role-user']) ? 'required' : '' }}>
                                                            <option value="" disabled selected>Select Role</option>
                                                            @foreach($roles as $role)
                                                                <option value="{{ $role->id }}" {{ $step->role_id == $role->id ? 'selected' : '' }}>
                                                                    {{ $role->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <!-- Specific User Selection -->
                                                    <div class="flex-grow-1 user-wrapper" style="display: {{ ($step->type ?? '') === 'specific-user' ? 'block' : 'none' }};">
                                                        <select class="form-select user-select" name="steps[{{ $index }}][user_id]" {{ ($step->type ?? '') === 'specific-user' ? 'required' : '' }}>
                                                            <option value="" disabled selected>Select Specific User</option>
                                                            @foreach($users as $user)
                                                                <option value="{{ $user->id }}" {{ $step->user_id == $user->id ? 'selected' : '' }}>
                                                                    {{ $user->name }} ({{ $user->email }})
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <div>
                                                        <button type="button" class="btn btn-sm btn-danger remove-step-btn">
                                                            <i style="height: 14px; width: 14px" data-feather="x"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="row mt-4">
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                        <a href="{{ route('setting.approval_workflows.index') }}" class="btn btn-secondary ms-2">Cancel</a>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Template for Inclusion/Exclusion Criteria Row -->
    <template id="criteriaTemplate">
        <div class="card mb-2 criteria-row bg-light border shadow-none">
            <div class="card-body p-2">
                <div class="d-flex align-items-center gap-2">
                    <!-- Criteria Type -->
                    <div style="min-width: 150px;">
                        <select class="form-select criteria-type-select" required>
                            <option value="user-type" selected>User Type</option>
                            <option value="role">User Role</option>
                            <option value="user_type_role">User Type + Role</option>
                            <option value="specific-user">Specific User</option>
                        </select>
                    </div>
                    
                    <!-- User Type Selection -->
                    <div class="flex-grow-1 user-type-wrapper">
                        <select class="form-select user-type-select" required>
                            <option value="" disabled selected>Select User Type</option>
                            @foreach($userTypes as $type)
                                <option value="{{ $type->value }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Spatie Role Selection -->
                    <div class="flex-grow-1 role-wrapper" style="display: none;">
                        <select class="form-select role-select">
                            <option value="" disabled selected>Select Role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Specific User Selection -->
                    <div class="flex-grow-1 user-wrapper" style="display: none;">
                        <select class="form-select user-select">
                            <option value="" disabled selected>Select Specific User</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <button type="button" class="btn btn-sm btn-danger remove-criteria-btn">
                            <i style="height: 14px; width: 14px" data-feather="x"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <!-- Template for Step Row -->
    <template id="stepTemplate">
        <div class="card mb-2 step-row bg-light border shadow-none">
            <div class="card-body p-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="step-handle text-muted" style="cursor: grab;">
                        <i data-feather="menu"></i>
                    </div>
                    <div class="step-number fw-bold bg-white border rounded px-2 py-1 text-center" style="min-width: 40px;">
                        1
                    </div>
                    
                    <!-- Step Type -->
                    <div style="min-width: 150px;">
                        <select class="form-select step-type-select" required>
                            <option value="user-type" selected>User Type</option>
                            <option value="role">User Role</option>
                            <option value="role-user">User Type + Role</option>
                            <option value="specific-user">Specific User</option>
                        </select>
                    </div>

                    <!-- User Type Selection (for user-type and role-user) -->
                    <div class="flex-grow-1 user-type-wrapper">
                        <select class="form-select user-type-select" required>
                            <option value="" disabled selected>Select User Type</option>
                            @foreach($userTypes as $type)
                                <option value="{{ $type->value }}">{{ ucfirst(str_replace('-', ' ', $type->value)) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Spatie Role Selection (for role and role-user) -->
                    <div class="flex-grow-1 role-wrapper" style="display: none;">
                        <select class="form-select role-select">
                            <option value="" disabled selected>Select Role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Specific User Selection (for specific-user) -->
                    <div class="flex-grow-1 user-wrapper" style="display: none;">
                        <select class="form-select user-select">
                            <option value="" disabled selected>Select Specific User</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <button type="button" class="btn btn-sm btn-danger remove-step-btn">
                            <i style="height: 14px; width: 14px" data-feather="x"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </template>
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
        const typeSelect = $('#type');
        const requiredApprovalsWrapper = $('#requiredApprovalsWrapper');
        const requiredApprovalsInput = $('#required_approvals');
        const totalStepsDisplay = $('#totalStepsDisplay');
        const stepsContainer = $('#stepsContainer');
        const addStepBtn = $('#addStepBtn');
        const stepTemplate = document.getElementById('stepTemplate');

        function toggleTypeFields() {
            if (typeSelect.val() === 'random') {
                requiredApprovalsWrapper.show();
                requiredApprovalsInput.attr('required', true);
            } else {
                requiredApprovalsWrapper.hide();
                requiredApprovalsInput.removeAttr('required');
            }
        }

        typeSelect.on('change', toggleTypeFields);

        function updateSteps() {
            const rows = stepsContainer.find('.step-row');
            const totalSteps = rows.length;
            
            rows.each(function(index) {
                $(this).find('.step-number').text(index + 1);
                
                // Update names of input fields for correct form serialization if needed
                $(this).find('.step-type-select').attr('name', `steps[${index}][type]`);
                $(this).find('.user-type-select').attr('name', `steps[${index}][required_user_type]`);
                $(this).find('.role-select').attr('name', `steps[${index}][role_id]`);
                $(this).find('.user-select').attr('name', `steps[${index}][user_id]`);
            });

            totalStepsDisplay.text(`out of ${totalSteps} total step(s)`);
            if (typeSelect.val() === 'random') {
                requiredApprovalsInput.attr('max', totalSteps);
            }
        }

        addStepBtn.on('click', function () {
            const newStep = $(stepTemplate.content.cloneNode(true));
            stepsContainer.append(newStep);
            
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
            updateSteps();
        });

        stepsContainer.on('click', '.remove-step-btn', function () {
            if (stepsContainer.find('.step-row').length > 1) {
                $(this).closest('.step-row').remove();
                updateSteps();
            } else {
                alert('You must have at least one step in the workflow.');
            }
        });

        // Toggle row inputs based on type selection
        stepsContainer.on('change', '.step-type-select', function() {
            const row = $(this).closest('.step-row');
            const type = $(this).val();

            // Reset wrappers visibility and required attributes
            row.find('.user-type-wrapper, .role-wrapper, .user-wrapper').hide();
            row.find('.user-type-select, .role-select, .user-select').removeAttr('required');

            if (type === 'user-type') {
                row.find('.user-type-wrapper').show();
                row.find('.user-type-select').attr('required', true);
            } else if (type === 'role') {
                row.find('.role-wrapper').show();
                row.find('.role-select').attr('required', true);
            } else if (type === 'role-user') {
                row.find('.user-type-wrapper, .role-wrapper').show();
                row.find('.user-type-select, .role-select').attr('required', true);
            } else if (type === 'specific-user') {
                row.find('.user-wrapper').show();
                row.find('.user-select').attr('required', true);
            }
        });

        // Axios Form Submission
        document.getElementById('workflowForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="mdi mdi-loading mdi-spin"></i> Submitting...';

            // Clear previous errors
            $('.alert-danger').remove();
            
            // Build payload
            const formData = new FormData(this);
            const data = Object.fromEntries(formData.entries());
            
            // Inclusion multi-select arrays
            const includer_user_types = [];
            const includer_role_ids = [];
            const includer_user_ids = [];
            $('.includer-row').each(function() {
                const type = $(this).find('.criteria-type-select').val();
                if (type === 'user-type') {
                    includer_user_types.push($(this).find('.user-type-select').val());
                } else if (type === 'role') {
                    includer_role_ids.push($(this).find('.role-select').val());
                } else if (type === 'user_type_role') {
                    includer_user_types.push($(this).find('.user-type-select').val());
                    includer_role_ids.push($(this).find('.role-select').val());
                } else if (type === 'specific-user') {
                    includer_user_ids.push($(this).find('.user-select').val());
                }
            });
            data.includer_user_types = includer_user_types;
            data.includer_role_ids = includer_role_ids;
            data.includer_user_ids = includer_user_ids;

            // Exclusion multi-select arrays
            const exclude_user_types = [];
            const exclude_role_ids = [];
            const exclude_user_ids = [];
            $('.excluder-row').each(function() {
                const type = $(this).find('.criteria-type-select').val();
                if (type === 'user-type') {
                    exclude_user_types.push($(this).find('.user-type-select').val());
                } else if (type === 'role') {
                    exclude_role_ids.push($(this).find('.role-select').val());
                } else if (type === 'user_type_role') {
                    exclude_user_types.push($(this).find('.user-type-select').val());
                    exclude_role_ids.push($(this).find('.role-select').val());
                } else if (type === 'specific-user') {
                    exclude_user_ids.push($(this).find('.user-select').val());
                }
            });
            data.exclude_user_types = exclude_user_types;
            data.exclude_role_ids = exclude_role_ids;
            data.exclude_user_ids = exclude_user_ids;
            
            // Handle array of steps correctly for Axios
            const steps = [];
            $('.step-row').each(function() {
                const type = $(this).find('.step-type-select').val();
                const stepData = { type: type };

                if (type === 'user-type') {
                    stepData.required_user_type = $(this).find('.user-type-select').val();
                } else if (type === 'role') {
                    stepData.role_id = $(this).find('.role-select').val();
                } else if (type === 'role-user') {
                    stepData.required_user_type = $(this).find('.user-type-select').val();
                    stepData.role_id = $(this).find('.role-select').val();
                } else if (type === 'specific-user') {
                    stepData.user_id = $(this).find('.user-select').val();
                }

                steps.push(stepData);
            });
            data.steps = steps;

            axios.post('{{ route('setting.approval_workflows.update', $workflow->id) }}', data)
                .then(response => {
                    window.location.href = response.data.redirect;
                })
                .catch(error => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = 'Submit';
                    
                    let errorMsg = 'Something went wrong. Please try again later.';
                    if (error.response && error.response.data) {
                        if (error.response.data.errors) {
                            errorMsg = Object.values(error.response.data.errors).flat().join('<br>');
                        } else if (error.response.data.message) {
                            errorMsg = error.response.data.message;
                        }
                    }
                    
                    const alertHtml = `<div class="alert alert-danger">${errorMsg}</div>`;
                    $('#workflowForm').before(alertHtml);
                    window.scrollTo(0, 0);
                });
        });

        // Add Criteria Rows (Includer / Excluder)
        const criteriaTemplate = document.getElementById('criteriaTemplate');

        $('#addIncluderBtn').on('click', function() {
            const newRow = $(criteriaTemplate.content.cloneNode(true));
            newRow.find('.criteria-row').addClass('includer-row');
            $('#includersContainer').append(newRow);
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        });

        // Toggle row inputs based on type selection for Inclusions/Exclusions
        $(document).on('change', '.criteria-type-select', function() {
            const row = $(this).closest('.criteria-row');
            const type = $(this).val();

            row.find('.user-type-wrapper, .role-wrapper, .user-wrapper').hide();
            row.find('.user-type-select, .role-select, .user-select').removeAttr('required');

            if (type === 'user-type') {
                row.find('.user-type-wrapper').show();
                row.find('.user-type-select').attr('required', true);
            } else if (type === 'role') {
                row.find('.role-wrapper').show();
                row.find('.role-select').attr('required', true);
            } else if (type === 'user_type_role') {
                row.find('.user-type-wrapper, .role-wrapper').show();
                row.find('.user-type-select, .role-select').attr('required', true);
            } else if (type === 'specific-user') {
                row.find('.user-wrapper').show();
                row.find('.user-select').attr('required', true);
            }
        });

        $('#addExcluderBtn').on('click', function() {
            const newRow = $(criteriaTemplate.content.cloneNode(true));
            newRow.find('.criteria-row').addClass('excluder-row');
            $('#excludersContainer').append(newRow);
            if (typeof feather !== 'undefined') {
                feather.replace();
            }
        });

        $(document).on('click', '.remove-criteria-btn', function() {
            $(this).closest('.criteria-row').remove();
        });

        // Initialize
        toggleTypeFields();
        updateSteps();
    });
</script>
@endpush
