@php
    $canEditFullProfile = auth()->user()->can('employee-management.edit');
    $mode = $mode ?? 'edit'; // 'edit' or 'create'
    $currentUserType = $employee?->user?->user_type ?? 'Employee';
@endphp

@if($mode === 'edit' && !$canEditFullProfile)
    <!-- READ-ONLY DATA for Email, Type, Role - User can only change password -->
    <div class="alert alert-soft-info border-0 mb-4 d-flex align-items-center" role="alert">
        <i class="fas fa-info-circle me-2"></i>
        <div>You can only update your account password. Profile management is restricted to administrators.</div>
    </div>

    <input type="hidden" name="work_email" value="{{ $employee?->user?->email ?? $employee?->work_email }}">
    <input type="hidden" name="user_type" value="{{ $currentUserType }}">
    <input type="hidden" name="role" value="{{ isset($employee?->user) ? $employee?->user?->getRoleNames()?->first() : '' }}">
    
    <div class="row mb-3">
        <div class="col-12">
            <p class="mb-1 text-muted small fw-bold text-uppercase">Associated Email</p>
            <p class="mb-0 fw-medium">{{ $employee?->user?->email ?? $employee?->work_email }}</p>
        </div>
    </div>
@else
    <!-- FULL EDITABLE MODE -->
    <div class="row">
        @if($mode === 'create')
            <div class="col-12 mb-3">
                <label for="{{ $mode }}_full_name" class="form-label fw-bold" style="color: #974063;">Full Name <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-user" style="color: #974063;"></i></span>
                    <input type="text" class="form-control border-start-0" id="{{ $mode }}_full_name" name="full_name" 
                           value="{{ old('full_name') }}" required>
                </div>
            </div>

            <div class="col-md-6 mb-3">
                <label for="{{ $mode }}_applicant_id" class="form-label fw-bold" style="color: #974063;">Applicant ID <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-id-badge" style="color: #974063;"></i></span>
                    <input type="text" class="form-control border-start-0" id="{{ $mode }}_applicant_id" name="applicant_id" 
                           value="{{ old('applicant_id') }}" required>
                </div>
            </div>

            <div class="col-md-6 mb-3">
                <label for="{{ $mode }}_system_id" class="form-label fw-bold" style="color: #974063;">System ID <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-laptop-code" style="color: #974063;"></i></span>
                    <input type="text" class="form-control border-start-0" id="{{ $mode }}_system_id" name="system_id" 
                           value="{{ old('system_id') }}" required>
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
                <select class="form-select" id="{{ $mode }}_user_type" name="user_type" required>
                    <option value="">Select User Type</option>
                    @foreach(['Group', 'Company', 'Business Unit', 'Division', 'Department', 'Section', 'Employee'] as $type)
                        <option value="{{ $type }}" {{ (old('user_type', $currentUserType) == $type) ? 'selected' : '' }}>
                            {{ $type }}
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
            <div class="col-12 mb-3">
                <label for="{{ $mode }}_work_email" class="form-label fw-bold" style="color: #974063;">Work Email <span class="text-danger">*</span></label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-envelope" style="color: #974063;"></i></span>
                    <input type="email" class="form-control border-start-0" id="{{ $mode }}_work_email" name="work_email" 
                           value="{{ old('work_email', $employee?->user?->email ?? $employee?->work_email ?? '') }}" required>
                </div>
            </div>

            <div class="col-12 mb-3">
                <label for="{{ $mode }}_user_type" class="form-label fw-bold" style="color: #974063;">User Type <span class="text-danger">*</span></label>
                <select class="form-select" id="{{ $mode }}_user_type" name="user_type" required>
                    <option value="">Select User Type</option>
                    @foreach(['Group', 'Company', 'Business Unit', 'Division', 'Department', 'Section', 'Employee'] as $type)
                        <option value="{{ $type }}" {{ (old('user_type', $currentUserType) == $type) ? 'selected' : '' }}>
                            {{ $type }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-12 mb-3">
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
    </div>
@endif

<!-- Password Section -->
<div id="{{ $mode }}_password_section">
    <div class="hr-divider mb-4 mt-2">
        <span class="bg-white px-3 text-muted small fw-bold">PASSWORD SECURITY</span>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="{{ $mode }}_password" class="form-label fw-bold" style="color: #974063;">
                {{ $mode === 'create' ? 'Password' : 'New Password' }} 
                @if($mode === 'create') <span class="text-danger">*</span> @endif
            </label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="fas fa-lock" style="color: #974063;"></i></span>
                <input type="password" class="form-control border-start-0" id="{{ $mode }}_password" name="password" 
                       placeholder="••••••••" {{ $mode === 'create' ? 'required' : '' }}>
                <span class="input-group-text bg-white border-start-0"><i class="fas fa-eye password-toggle"></i></span>
            </div>
            @if($mode === 'edit')
                <small class="text-muted mt-1 d-block"><i class="fas fa-info-circle me-1"></i>Leave blank to keep current password</small>
            @endif
        </div>

        <div class="col-md-6 mb-3">
            <label for="{{ $mode }}_password_confirmation" class="form-label fw-bold" style="color: #974063;">Confirm Password @if($mode === 'create') <span class="text-danger">*</span> @endif</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="fas fa-check-circle" style="color: #974063;"></i></span>
                <input type="password" class="form-control border-start-0" id="{{ $mode }}_password_confirmation" name="password_confirmation" 
                       placeholder="••••••••" {{ $mode === 'create' ? 'required' : '' }}>
                <span class="input-group-text bg-white border-start-0"><i class="fas fa-eye password-toggle"></i></span>
            </div>
        </div>
    </div>
</div>
