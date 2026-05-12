<!-- Edit Login Information Modal -->
<div class="modal fade" id="editLoginInfoModal" tabindex="-1" aria-labelledby="editLoginInfoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px; overflow: hidden;">
            <form action="{{ route('employees.update_login_info', $employee->id) }}" method="POST">
                @csrf
                <div class="modal-body p-0">
                    <div class="card shadow-sm border-0 mb-0">
                        <div class="card-header bg-primary text-white py-3 d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0 text-white"><i class="fas fa-user-shield me-2"></i>Edit Login Information</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="card-body p-4">
                            <!-- Section 1: Credentials -->
                            <div class="row">
                                <div class="col-lg-12 mb-4">
                                    <label for="work_email" class="form-label text-primary fw-bold">Work Email <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i class="fas fa-envelope text-primary"></i></span>
                                        <input type="email" class="form-control border-start-0" id="work_email" name="work_email" 
                                               value="{{ old('work_email', $employee->user->email ?? $employee->work_email) }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4 mb-3">
                                    <label for="user_type" class="form-label text-primary fw-bold">User Type <span class="text-danger">*</span></label>
                                    <select class="form-select" id="user_type" name="user_type" required>
                                        <option value="">Select User Type</option>
                                        @foreach(['Group', 'Company', 'Business Unit', 'Division', 'Department', 'Section', 'Employee'] as $type)
                                            <option value="{{ $type }}" {{ (old('user_type', $employee->user->user_type ?? '') == $type) ? 'selected' : '' }}>
                                                {{ $type }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-lg-8 mb-3">
                                    <label for="roles" class="form-label text-primary fw-bold">Assign Roles</label>
                                    <select class="form-select select2_list" id="roles" name="roles[]" multiple>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->name }}" {{ (isset($employee->user) && $employee->user->hasRole($role->name)) ? 'selected' : '' }}>
                                                {{ $role->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="hr-divider mb-4 mt-2">
                                <span class="bg-white px-3 text-muted small fw-bold">PASSWORD SECURITY</span>
                            </div>

                            <div class="row">
                                <div class="col-lg-6 mb-3">
                                    <label for="password" class="form-label text-primary fw-bold">New Password (Leave blank to keep current)</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i class="fas fa-lock text-primary"></i></span>
                                        <input type="password" class="form-control border-start-0" id="password" name="password">
                                    </div>
                                </div>

                                <div class="col-lg-6 mb-3">
                                    <label for="password_confirmation" class="form-label text-primary fw-bold">Confirm Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0"><i class="fas fa-check-circle text-primary"></i></span>
                                        <input type="password" class="form-control border-start-0" id="password_confirmation" name="password_confirmation">
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
    #editLoginInfoModal .select2-container {
        width: 100% !important;
    }
</style>
