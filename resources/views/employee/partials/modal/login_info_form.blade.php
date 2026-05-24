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
                <select class="form-select user-type-select" id="{{ $mode }}_user_type" name="user_type" required>
                    <option value="">Select User Type</option>
                    @foreach(['Group', 'Company', 'Business Unit', 'Division', 'Department', 'Section', 'Employee'] as $type)
                        <option value="{{ $type }}" {{ (old('user_type', $currentUserType) == $type) ? 'selected' : '' }}>
                            {{ $type }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Organizational Scoping for Non-Employee/Group Users -->
            <div class="col-12 mt-2 organizational-scope-section" style="display: none;">
                <div class="card shadow-none border bg-light bg-opacity-25 mb-0">
                    <div class="card-body p-3">
                        <h6 class="small fw-bold text-uppercase text-muted mb-3">Administrative Scope Settings</h6>
                        <div class="row g-2">
                            <div class="col-md-6 mb-2">
                                <label class="small mb-1">Company</label>
                                <select name="scope_company_id" id="{{ $mode }}_scope_company_id" class="form-select form-select-sm select2_list">
                                    <option value="">Select Company</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="small mb-1">Branch/Unit</label>
                                <select name="scope_unit_id" id="{{ $mode }}_scope_unit_id" class="form-select form-select-sm select2_list">
                                    <option value="">Select Unit</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="small mb-1">Division</label>
                                <select name="scope_division_id" id="{{ $mode }}_scope_division_id" class="form-select form-select-sm select2_list">
                                    <option value="">Select Division</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="small mb-1">Department</label>
                                <select name="scope_department_id" id="{{ $mode }}_scope_department_id" class="form-select form-select-sm select2_list">
                                    <option value="">Select Department</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="small mb-1">Section</label>
                                <select name="scope_section_id" id="{{ $mode }}_scope_section_id" class="form-select form-select-sm select2_list">
                                    <option value="">Select Section</option>
                                </select>
                            </div>
                        </div>
                        <div class="mt-2 text-info small">
                            <i class="fas fa-info-circle me-1"></i> These settings define the administrator's operational scope.
                        </div>
                    </div>
                </div>
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
                <select class="form-select user-type-select" id="{{ $mode }}_user_type" name="user_type" required>
                    <option value="">Select User Type</option>
                    @foreach(['Group', 'Company', 'Business Unit', 'Division', 'Department', 'Section', 'Employee'] as $type)
                        <option value="{{ $type }}" {{ (old('user_type', $currentUserType) == $type) ? 'selected' : '' }}>
                            {{ $type }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Organizational Scoping for Non-Employee/Group Users -->
            <div class="col-12 mt-2 organizational-scope-section" style="display: none;">
                <div class="card shadow-none border bg-light bg-opacity-25 mb-0">
                    <div class="card-body p-3">
                        <h6 class="small fw-bold text-uppercase text-muted mb-3">Administrative Scope Settings</h6>
                        <div class="row g-2">
                            <div class="col-md-6 mb-2">
                                <label class="small mb-1">Company</label>
                                <select name="scope_company_id" id="{{ $mode }}_scope_company_id" class="form-select form-select-sm select2_list">
                                    <option value="">Select Company</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="small mb-1">Branch/Unit</label>
                                <select name="scope_unit_id" id="{{ $mode }}_scope_unit_id" class="form-select form-select-sm select2_list">
                                    <option value="">Select Unit</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="small mb-1">Division</label>
                                <select name="scope_division_id" id="{{ $mode }}_scope_division_id" class="form-select form-select-sm select2_list">
                                    <option value="">Select Division</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="small mb-1">Department</label>
                                <select name="scope_department_id" id="{{ $mode }}_scope_department_id" class="form-select form-select-sm select2_list">
                                    <option value="">Select Department</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="small mb-1">Section</label>
                                <select name="scope_section_id" id="{{ $mode }}_scope_section_id" class="form-select form-select-sm select2_list">
                                    <option value="">Select Section</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
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
                <span class="input-group-text bg-white border-start-0 cursor-pointer"><i class="fas fa-eye password-toggle"></i></span>
            </div>
            @if($mode === 'edit')
                <small class="text-muted mt-1 d-block"><i class="fas fa-info-circle me-1"></i>Leave blank to keep current password</small>
            @endif
        </div>

        <div class="col-md-6 mb-3">
            <label for="{{ $mode }}_password_confirmation" class="form-label fw-bold" style="color: #974063;">Confirm Password @if($mode === 'create') <span class="text-danger">*</span> @endif</label>
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="fas fa-check-double" style="color: #974063;"></i></span>
                <input type="password" class="form-control border-start-0" id="{{ $mode }}_password_confirmation" name="password_confirmation" 
                       placeholder="••••••••" {{ $mode === 'create' ? 'required' : '' }}>
                <span class="input-group-text bg-white border-start-0 cursor-pointer"><i class="fas fa-eye password-toggle"></i></span>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    const mode = '{{ $mode }}';
    const userTypeSelect = $(`#${mode}_user_type`);
    const scopeSection = $('.organizational-scope-section');
    
    const companySelect = $(`#${mode}_scope_company_id`);
    const unitSelect = $(`#${mode}_scope_unit_id`);
    const divisionSelect = $(`#${mode}_scope_division_id`);
    const departmentSelect = $(`#${mode}_scope_department_id`);
    const sectionSelect = $(`#${mode}_scope_section_id`);

    function toggleScopeVisibility() {
        const type = userTypeSelect.val();
        if (type && type !== 'Employee' && type !== 'Group') {
            scopeSection.show();
            if (companySelect.children('option').length <= 1) {
                fetchScopeCompanies();
            }
        } else {
            scopeSection.hide();
        }
    }

    userTypeSelect.on('change', toggleScopeVisibility);
    toggleScopeVisibility(); 

    function fetchScopeCompanies() {
        axios.get('{{ route('transfer.api.companies') }}').then(res => {
            populateSelect(companySelect, res.data.data, 'Select Company');
            @if(isset($employee->officeInfo))
                companySelect.val('{{ $employee->officeInfo->current_company_id }}').trigger('change');
            @endif
        });
    }

    function populateSelect($el, data, placeholder, labelKey = 'name') {
        $el.html(`<option value="">${placeholder}</option>`);
        data.forEach(item => {
            $el.append(`<option value="${item.id}">${item[labelKey]}</option>`);
        });
    }

    // Cascading Logic
    companySelect.on('change', function() {
        const companyId = $(this).val();
        resetScopeFilters(['unit', 'division', 'dept', 'section']);
        if (companyId) {
            axios.get(`/get-units/${companyId}`).then(res => {
                populateSelect(unitSelect, res.data, 'Select Unit');
                @if(isset($employee->officeInfo))
                if(unitSelect.val() === '') {
                    unitSelect.val('{{ $employee->officeInfo->current_business_unit_id }}').trigger('change');
                }
                @endif
            });
        }
    });

    unitSelect.on('change', function() {
        const companyId = companySelect.val();
        const unitId = $(this).val() || 'null';
        resetScopeFilters(['division', 'dept', 'section']);
        if (companyId) {
            axios.get(`/get-divisions/${companyId}/${unitId}`).then(res => {
                populateSelect(divisionSelect, res.data, 'Select Division');
                @if(isset($employee->officeInfo))
                if(divisionSelect.val() === '') {
                    divisionSelect.val('{{ $employee->officeInfo->current_division_id }}').trigger('change');
                }
                @endif
            });
        }
    });

    divisionSelect.on('change', function() {
        const companyId = companySelect.val();
        const unitId = unitSelect.val() || 'null';
        const divisionId = $(this).val() || 'null';
        resetScopeFilters(['dept', 'section']);
        if (companyId) {
            axios.get(`/get-departments/${companyId}/${unitId}/${divisionId}`).then(res => {
                populateSelect(departmentSelect, res.data, 'Select Department');
                @if(isset($employee->officeInfo))
                if(departmentSelect.val() === '') {
                    departmentSelect.val('{{ $employee->officeInfo->current_department_id }}').trigger('change');
                }
                @endif
            });
        }
    });

    departmentSelect.on('change', function() {
        const companyId = companySelect.val();
        const unitId = unitSelect.val() || 'null';
        const divisionId = divisionSelect.val() || 'null';
        const deptId = $(this).val() || 'null';
        resetScopeFilters(['section']);
        if (companyId) {
            axios.get(`/get-sections/${companyId}/${unitId}/${divisionId}/${deptId}`).then(res => {
                populateSelect(sectionSelect, res.data, 'Select Section');
                @if(isset($employee->officeInfo))
                if(sectionSelect.val() === '') {
                    sectionSelect.val('{{ $employee->officeInfo->current_section_id }}');
                }
                @endif
            });
        }
    });

    function resetScopeFilters(keys) {
        if (keys.includes('unit')) unitSelect.html('<option value="">Select Unit</option>');
        if (keys.includes('division')) divisionSelect.html('<option value="">Select Division</option>');
        if (keys.includes('dept')) departmentSelect.html('<option value="">Select Department</option>');
        if (keys.includes('section')) sectionSelect.html('<option value="">Select Section</option>');
    }
});
</script>
@endpush
