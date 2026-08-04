@php
    $canManageRoles = auth()->user()->can('role-management.edit');
    $mode = $mode ?? 'edit'; // 'edit' or 'create'
    $currentUserType = $employee?->user?->user_type ?? \App\Enums\UserType::Employee;
@endphp

@if($mode === 'edit' && !$canManageRoles)
    <div class="alert alert-soft-info border-0 mb-4 d-flex align-items-center" role="alert">
        <i class="fas fa-info-circle me-2"></i>
        <div>Only administrators can modify User Types and Roles. You can update your password below.</div>
    </div>
    <input type="hidden" name="user_type" value="{{ $currentUserType }}">
@endif

<div class="row g-3">
    @if($mode === 'create')
        <!-- Detailed Creation Fields -->
        <div class="col-12 mb-3">
            <label for="{{ $mode }}_full_name" class="form-label fw-bold" style="color: #974063;">Full Name <span class="text-danger">*</span></label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="fas fa-user" style="color: #974063;"></i></span>
                <input type="text" class="form-control border-start-0" id="{{ $mode }}_full_name" name="full_name" 
                       value="{{ old('full_name') }}" required>
            </div>
        </div>



        <div class="col-md-6 mb-3">
            <label for="{{ $mode }}_applicant_id" class="form-label fw-bold" style="color: #974063;">Applicant ID</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="fas fa-id-badge" style="color: #974063;"></i></span>
                <input type="text" class="form-control border-start-0" id="{{ $mode }}_applicant_id" name="applicant_id"
                       value="{{ old('applicant_id') }}" placeholder="Auto-generated if left blank">
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <label for="{{ $mode }}_system_id" class="form-label fw-bold" style="color: #974063;">System ID</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="fas fa-laptop-code" style="color: #974063;"></i></span>
                <input type="text" class="form-control border-start-0" id="{{ $mode }}_system_id" name="system_id"
                       value="{{ old('system_id') }}" placeholder="Auto-generated if left blank">
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <label for="{{ $mode }}_punch_card_no" class="form-label fw-bold" style="color: #974063;">Punch Card No <span class="text-danger">*</span></label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="fas fa-clock" style="color: #974063;"></i></span>
                <input type="text" class="form-control border-start-0" id="{{ $mode }}_punch_card_no" name="punch_card_no" 
                       value="{{ old('punch_card_no') }}" required>
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <label for="{{ $mode }}_work_email" class="form-label fw-bold" style="color: #974063;">Work Email <span class="text-danger">*</span></label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="fas fa-envelope" style="color: #974063;"></i></span>
                <input type="email" class="form-control border-start-0" id="{{ $mode }}_work_email" name="work_email" 
                       value="{{ old('work_email') }}" required>
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <label for="{{ $mode }}_user_type" class="form-label fw-bold" style="color: #974063;">User Type <span class="text-danger">*</span></label>
            <select class="form-select user-type-select" id="{{ $mode }}_user_type" name="user_type" required>
                <option value="">Select User Type</option>
                @foreach(\App\Enums\UserType::cases() as $type)
                    <option value="{{ $type->value }}" {{ (old('user_type', $currentUserType instanceof \App\Enums\UserType ? $currentUserType->value : $currentUserType) == $type->value) ? 'selected' : '' }}>
                        {{ $type->name }}
                    </option>
                @endforeach
            </select>
        </div>



        <div class="col-md-6 mb-3">
            <label for="{{ $mode }}_role" class="form-label fw-bold" style="color: #974063;">Assign Role</label>
            <select class="form-select" id="{{ $mode }}_role" name="role">
                <option value="">Select Role</option>
                @foreach($roles as $role)
                    <option value="{{ $role->name }}">{{ $role->name }}</option>
                @endforeach
            </select>
        </div>
    @else
        <!-- Simplified Edit Fields -->
        <div class="col-md-6 mb-3">
            <label for="{{ $mode }}_work_email" class="form-label fw-bold" style="color: #974063;">Work Email <span class="text-danger">*</span></label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="fas fa-envelope" style="color: #974063;"></i></span>
                <input type="email" class="form-control border-start-0" id="{{ $mode }}_work_email" name="work_email" 
                       value="{{ old('work_email', $employee?->user?->email ?? $employee?->work_email ?? '') }}" required @if(!$canManageRoles) readonly @endif>
            </div>
        </div>

        @if($canManageRoles)
            <div class="col-md-6 mb-3">
                <label for="{{ $mode }}_user_type" class="form-label fw-bold" style="color: #974063;">User Type <span class="text-danger">*</span></label>
                <select class="form-select" id="{{ $mode }}_user_type" name="user_type" required>
                    @foreach(\App\Enums\UserType::cases() as $type)
                        <option value="{{ $type->value }}" {{ (old('user_type', $currentUserType instanceof \App\Enums\UserType ? $currentUserType->value : $currentUserType) == $type->value) ? 'selected' : '' }}>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label for="{{ $mode }}_role" class="form-label fw-bold" style="color: #974063;">Assign Role</label>
                <select class="form-select" id="{{ $mode }}_role" name="role">
                    <option value="">Select Role</option>
                    @foreach($roles as $role)
                        @php
                            $hasRole = isset($employee?->user) && $employee?->user?->hasRole($role->name);
                        @endphp
                        <option value="{{ $role->name }}" {{ $hasRole ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        @endif
    @endif

    <div class="col-md-6 mb-3">
        <label for="{{ $mode }}_password" class="form-label fw-bold" style="color: #974063;">
            {{ $mode === 'create' ? 'Password' : 'New Password' }} 
            @if($mode === 'create') <span class="text-danger">*</span> @endif
        </label>
        <div class="input-group">
            <span class="input-group-text bg-light border-end-0"><i class="fas fa-lock" style="color: #974063;"></i></span>
            <input type="password" class="form-control border-start-0" id="{{ $mode }}_password" name="password" 
                   placeholder="••••••••" {{ $mode === 'create' ? 'required' : '' }}>
            <span class="input-group-text border-start-0 cursor-pointer"><i class="fas fa-eye password-toggle"></i></span>
        </div>
    </div>

    <div class="col-md-6 mb-3">
        <label for="{{ $mode }}_password_confirmation" class="form-label fw-bold" style="color: #974063;">Confirm Password @if($mode === 'create') <span class="text-danger">*</span> @endif</label>
        <div class="input-group">
            <span class="input-group-text bg-light border-end-0"><i class="fas fa-check-circle" style="color: #974063;"></i></span>
            <input type="password" class="form-control border-start-0" id="{{ $mode }}_password_confirmation" name="password_confirmation" 
                   placeholder="••••••••" {{ $mode === 'create' ? 'required' : '' }}>
            <span class="input-group-text border-start-0 cursor-pointer"><i class="fas fa-eye password-toggle"></i></span>
        </div>
    </div>
</div>


