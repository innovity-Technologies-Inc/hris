<!-- Edit Login Information Modal -->
<div class="modal fade" id="editLoginInfoModal" tabindex="-1" aria-labelledby="editLoginInfoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white py-3">
                <h5 class="modal-title fw-bold" id="editLoginInfoModalLabel">
                    <i class="mdi mdi-account-key me-2"></i>Edit Login Information
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('employees.update_login_info', $employee->id) }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <!-- Login Credentials Section -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="work_email" class="form-label fw-semibold text-primary">Work Email <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-envelope"></i></span>
                                <input type="email" class="form-control border-start-0" id="work_email" name="work_email" 
                                       value="{{ old('work_email', $employee->user->email ?? $employee->work_email) }}" required>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="user_type" class="form-label fw-semibold text-primary">User Type <span class="text-danger">*</span></label>
                            <select class="form-select" id="user_type" name="user_type" required>
                                <option value="">Select User Type</option>
                                @foreach(['Group', 'Company', 'Business Unit', 'Division', 'Department', 'Section', 'Employee'] as $type)
                                    <option value="{{ $type }}" {{ (old('user_type', $employee->user->user_type ?? '') == $type) ? 'selected' : '' }}>
                                        {{ $type }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Roles Section -->
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="roles" class="form-label fw-semibold text-primary">Assign Roles</label>
                            <select class="form-select select2_list" id="roles" name="roles[]" multiple>
                                @foreach($roles as $role)
                                    <option value="{{ $role->name }}" {{ (isset($employee->user) && $employee->user->hasRole($role->name)) ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted mt-1 d-block"><i class="fas fa-info-circle me-1"></i>You can select multiple roles for this user.</small>
                        </div>
                    </div>

                    <div class="hr-text text-primary fw-bold mb-3 mt-4">
                        <span>SECURITY UPDATE</span>
                    </div>

                    <!-- Password Section -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label fw-semibold text-primary">New Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-lock"></i></span>
                                <input type="password" class="form-control border-start-0" id="password" name="password" 
                                       placeholder="Leave blank to keep current">
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label fw-semibold text-primary">Confirm Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-check-circle"></i></span>
                                <input type="password" class="form-control border-start-0" id="password_confirmation" name="password_confirmation">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0 py-3">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4 fw-bold">
                        <i class="fas fa-save me-1"></i> Update Login Info
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .hr-text {
        display: flex;
        align-items: center;
        text-align: center;
    }
    .hr-text::before, .hr-text::after {
        content: '';
        flex: 1;
        border-bottom: 1px solid rgba(151, 64, 99, 0.2);
    }
    .hr-text:not(:empty)::before {
        margin-right: .5em;
    }
    .hr-text:not(:empty)::after {
        margin-left: .5em;
    }
</style>
