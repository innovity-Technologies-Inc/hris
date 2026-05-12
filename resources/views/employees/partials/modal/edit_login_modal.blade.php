<!-- Edit Login Information Modal -->
<div class="modal fade" id="editLoginInfoModal" tabindex="-1" aria-labelledby="editLoginInfoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="editLoginInfoModalLabel">Edit Login Information</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('employees.update_login_info', $employee->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <!-- Work Email -->
                        <div class="col-md-6">
                            <label for="work_email" class="form-label fw-bold">Work Email</label>
                            <input type="email" class="form-control" id="work_email" name="work_email" 
                                   value="{{ old('work_email', $employee->user->email ?? $employee->work_email) }}" required>
                        </div>

                        <!-- User Type -->
                        <div class="col-md-6">
                            <label for="user_type" class="form-label fw-bold">User Type</label>
                            <select class="form-select" id="user_type" name="user_type" required>
                                <option value="">Select User Type</option>
                                @foreach(['Group', 'Company', 'Business Unit', 'Division', 'Department', 'Section', 'Employee'] as $type)
                                    <option value="{{ $type }}" {{ (old('user_type', $employee->user->user_type ?? '') == $type) ? 'selected' : '' }}>
                                        {{ $type }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Roles -->
                        <div class="col-md-12">
                            <label for="roles" class="form-label fw-bold">Roles</label>
                            <div class="row">
                                @foreach($roles as $role)
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="roles[]" 
                                                   value="{{ $role->name }}" id="role_{{ $role->id }}"
                                                   {{ (isset($employee->user) && $employee->user->hasRole($role->name)) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="role_{{ $role->id }}">
                                                {{ $role->name }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <hr>
                        <h6 class="mb-0 text-primary">Change Password (Leave blank to keep current)</h6>

                        <!-- Password -->
                        <div class="col-md-6">
                            <label for="password" class="form-label fw-bold">New Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Min 8 characters">
                        </div>

                        <!-- Confirm Password -->
                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label fw-bold">Confirm Password</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Login Info</button>
                </div>
            </form>
        </div>
    </div>
</div>
