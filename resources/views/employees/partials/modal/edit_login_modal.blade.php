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
                    <div class="row">
                        <!-- User Type always visible to allow switching back -->
                        <div class="col-12 mb-3">
                            <label for="user_type" class="form-label text-primary fw-bold">User Type <span class="text-danger">*</span></label>
                            <select class="form-select" id="emp_user_type" name="user_type" required>
                                <option value="">Select User Type</option>
                                @foreach(['Group', 'Company', 'Business Unit', 'Division', 'Department', 'Section', 'Employee'] as $type)
                                    <option value="{{ $type }}" {{ (old('user_type', $employee->user->user_type ?? '') == $type) ? 'selected' : '' }}>
                                        {{ $type }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Work Email and Role (Hidden for 'Employee' type) -->
                    <div id="extended_fields_section">
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
                                <label for="role" class="form-label text-primary fw-bold">Assign Role</label>
                                <select class="form-select" id="role" name="role">
                                    <option value="">Select Role</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->name }}" {{ (isset($employee->user) && $employee->user->hasRole($role->name)) ? 'selected' : '' }}>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Password Section (Always visible or conditional based on your previous rule, 
                         but the user says 'only have the password field' for employees) -->
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
                                </div>
                                <small class="text-muted mt-1 d-block"><i class="fas fa-info-circle me-1"></i>Leave blank to keep current password</small>
                            </div>

                            <div class="col-12 mb-3">
                                <label for="password_confirmation" class="form-label text-primary fw-bold">Confirm Password</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-check-circle text-primary"></i></span>
                                    <input type="password" class="form-control border-start-0" id="emp_password_confirmation" name="password_confirmation" placeholder="••••••••">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 mb-3">
                                <div class="form-check custom-checkbox">
                                    <input class="form-check-input" type="checkbox" id="show_emp_password">
                                    <label class="form-check-label text-muted fw-bold" for="show_emp_password">
                                        Show Password
                                    </label>
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

<script>
    function togglePasswordSection() {
        const userTypeSelect = document.getElementById('emp_user_type');
        const passwordSection = document.getElementById('password_section');
        const extendedFields = document.getElementById('extended_fields_section');
        
        if (userTypeSelect.value === 'Employee') {
            passwordSection.style.display = 'block';
            extendedFields.style.display = 'none';
        } else {
            passwordSection.style.display = 'block';
            extendedFields.style.display = 'block';
        }
    }

    // Listener for User Type change
    document.getElementById('emp_user_type').addEventListener('change', togglePasswordSection);

    // Initial check on load (for when modal opens or page reloads with input)
    document.addEventListener('DOMContentLoaded', togglePasswordSection);
    
    // Also trigger when Bootstrap modal is shown
    document.getElementById('editLoginInfoModal').addEventListener('shown.bs.modal', togglePasswordSection);

    document.getElementById('show_emp_password').addEventListener('change', function() {
        const passwordInput = document.getElementById('emp_password');
        const passwordConfirmInput = document.getElementById('emp_password_confirmation');
        const type = this.checked ? 'text' : 'password';
        passwordInput.type = type;
        passwordConfirmInput.type = type;
    });
</script>

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
