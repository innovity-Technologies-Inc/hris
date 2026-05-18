<!-- Edit Login Information Modal -->
<div class="modal fade" id="editLoginInfoModal" tabindex="-1" aria-labelledby="editLoginInfoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <!-- Using the card directly as the modal content -->
        <div class="modal-content card shadow-sm border-0 mb-0" style="border-radius: 15px; overflow: hidden;">
            <form action="{{ route('employees.update_login_info', $employee->id) }}" method="POST">
                @csrf
                <div class="card-header bg-primary text-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 text-white"><i class="fas fa-user-shield me-2"></i>Edit Login Information</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="card-body p-4">
                    @php
                        $currentUserType = $employee->user->user_type ?? 'Employee';
                        $canEditFullProfile = auth()->user()->can('employee-management.edit');
                    @endphp

                    @if(!$canEditFullProfile)
                        <!-- READ-ONLY DATA for Email, Type, Role - User can only change password -->
                        <div class="alert alert-soft-info border-0 mb-4 d-flex align-items-center" role="alert">
                            <i class="fas fa-info-circle me-2"></i>
                            <div>You can only update your account password. Profile management is restricted to administrators.</div>
                        </div>

                        <input type="hidden" name="work_email" value="{{ $employee->user->email ?? $employee->work_email }}">
                        <input type="hidden" name="user_type" value="{{ $currentUserType }}">
                        <input type="hidden" name="role" value="{{ isset($employee->user) ? $employee->user->getRoleNames()->first() : '' }}">
                        
                        <div class="row mb-3">
                            <div class="col-12">
                                <p class="mb-1 text-muted small fw-bold text-uppercase">Associated Email</p>
                                <p class="mb-0 fw-medium">{{ $employee->user->email ?? $employee->work_email }}</p>
                            </div>
                        </div>
                    @else
                        <!-- FULL EDITABLE MODE for users with employee-management.edit permission -->
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label for="work_email" class="form-label text-primary fw-bold">Work Email <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-envelope text-primary"></i></span>
                                    <input type="email" class="form-control border-start-0" id="work_email" name="work_email" 
                                           value="{{ old('work_email', $employee->user->email ?? $employee->work_email) }}" required>
                                </div>
                            </div>

                            <div class="col-12 mb-3">
                                <label for="user_type" class="form-label text-primary fw-bold">User Type <span class="text-danger">*</span></label>
                                <select class="form-select" id="emp_user_type" name="user_type" required>
                                    <option value="">Select User Type</option>
                                    @foreach(['Group', 'Company', 'Business Unit', 'Division', 'Department', 'Section', 'Employee'] as $type)
                                        <option value="{{ $type }}" {{ (old('user_type', $currentUserType) == $type) ? 'selected' : '' }}>
                                            {{ $type }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12 mb-3">
                                <label for="role" class="form-label text-primary fw-bold">Assign Role</label>
                                <select class="form-select" id="role" name="role">
                                    <option value="">Select Role</option>
                                    @foreach($roles as $role)
                                        @php
                                            $hasRole = isset($employee->user) && $employee->user->hasRole($role->name);
                                        @endphp
                                        <option value="{{ $role->name }}" {{ $hasRole ? 'selected' : '' }}>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @endif

                    <!-- Password Section (Common to all or required for password changes) -->
                    <div id="password_section">
                        <div class="hr-divider mb-4 mt-2">
                            <span class="bg-white px-3 text-muted small fw-bold">PASSWORD SECURITY</span>
                        </div>

                        <div class="row">
                            <div class="col-12 mb-3">
                                <label for="password" class="form-label text-primary fw-bold">New Password</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-lock text-primary"></i></span>
                                    <input type="password" class="form-control border-start-0" id="emp_password" name="password" placeholder="••••••••">
                                    <span class="input-group-text bg-white border-start-0"><i class="fas fa-eye password-toggle"></i></span>
                                </div>
                                <small class="text-muted mt-1 d-block"><i class="fas fa-info-circle me-1"></i>Leave blank to keep current password</small>
                            </div>

                            <div class="col-12 mb-3">
                                <label for="password_confirmation" class="form-label text-primary fw-bold">Confirm Password</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-check-circle text-primary"></i></span>
                                    <input type="password" class="form-control border-start-0" id="emp_password_confirmation" name="password_confirmation" placeholder="••••••••">
                                    <span class="input-group-text bg-white border-start-0"><i class="fas fa-eye password-toggle"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light py-3">
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary px-4 fw-bold">
                            <i class="fas fa-save me-1"></i> Update Account Credentials
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .hr-divider {
        display: flex;
        align-items: center;
        text-align: center;
    }
    .hr-divider::before, .hr-divider::after {
        content: '';
        flex: 1;
        border-bottom: 1px solid #e9ecef;
    }
    .hr-divider:not(:empty)::before {
        margin-right: .5em;
    }
    .hr-divider:not(:empty)::after {
        margin-left: .5em;
    }
</style>
