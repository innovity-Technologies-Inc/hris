@extends('structure.master')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-users-cog me-2"></i>
                            <h5 class="mb-0">
                                @if (isset($organizationStructure))
                                    Edit Organization {{ $organizationStructure->member_type }}
                                @else
                                    Add Organization Member
                                @endif
                            </h5>
                        </div>
                        <a href="{{ route('organization-structure.index') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>Back to List
                        </a>
                    </div>
                </div>

                <div class="card-body p-4">
                    <!-- Tabs Navigation -->
                    <ul class="nav nav-tabs mb-4" id="memberTypeTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button
                                class="nav-link {{ isset($organizationStructure) ? ($organizationStructure->member_type === 'Board Member' ? 'active' : '') : 'active' }}"
                                id="board-member-tab" data-bs-toggle="tab" data-bs-target="#board-member" type="button"
                                role="tab" aria-controls="board-member"
                                aria-selected="{{ isset($organizationStructure) ? ($organizationStructure->member_type === 'Board Member' ? 'true' : 'false') : 'true' }}">
                                <i class="fas fa-users-cog me-2"></i>Board Member
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button
                                class="nav-link {{ isset($organizationStructure) && $organizationStructure->member_type === 'Key Member' ? 'active' : '' }}"
                                id="key-member-tab" data-bs-toggle="tab" data-bs-target="#key-member" type="button"
                                role="tab" aria-controls="key-member"
                                aria-selected="{{ isset($organizationStructure) && $organizationStructure->member_type === 'Key Member' ? 'true' : 'false' }}">
                                <i class="fas fa-user-tie me-2"></i>Key Member
                            </button>
                        </li>
                    </ul>

                    <!-- Tabs Content -->
                    <div class="tab-content" id="memberTypeTabsContent">
                        <!-- Board Member Tab -->
                        <div class="tab-pane fade {{ isset($organizationStructure) ? ($organizationStructure->member_type === 'Board Member' ? 'show active' : '') : 'show active' }}"
                            id="board-member" role="tabpanel" aria-labelledby="board-member-tab">
                            <form id="boardMemberForm"
                                action="{{ isset($organizationStructure) ? route('organization-structure.update', $organizationStructure->id) : route('organization-structure.store') }}"
                                method="post" enctype="multipart/form-data">
                                @csrf
                                @if (isset($organizationStructure))
                                    @method('PUT')
                                @endif
                                <input type="hidden" name="member_type" value="Board Member">

                                <!-- Type Section -->
                                <div class="mb-4">
                                    <h6 class="text-primary border-bottom pb-2 mb-3">
                                        <i class="fas fa-tag me-2"></i>Member Type
                                    </h6>
                                    <div class="row">
                                        <!-- Type -->
                                        <div class="col-md-4 mb-3">
                                            <label for="boardTypeSelect" class="form-label fw-semibold">
                                                <i class="fas fa-tag text-info me-1"></i>
                                                Type <span class="text-danger">*</span>
                                            </label>
                                            <select name="type" id="boardTypeSelect" class="form-select select2_list"
                                                required>
                                                <option value="">-- Select Type --</option>
                                                <option value="group"
                                                    {{ isset($organizationStructure) && $organizationStructure->type_form == 'group' ? 'selected' : '' }}>
                                                    Group</option>
                                                <option value="company"
                                                    {{ isset($organizationStructure) && $organizationStructure->type_form == 'company' ? 'selected' : '' }}>
                                                    Company</option>
                                            </select>
                                            @error('type')
                                                <small class="text-danger"><i
                                                        class="fas fa-exclamation-circle me-1"></i>{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Organization Hierarchy Section -->
                                <div class="mb-4">
                                    <h6 class="text-primary border-bottom pb-2 mb-3">
                                        <i class="fas fa-sitemap me-2"></i>Organization Hierarchy
                                    </h6>
                                    <div class="row">
                                        <!-- Group -->
                                        <div class="col-md-6 mb-3">
                                            <label for="boardGroupSelect" class="form-label fw-semibold">
                                                <i class="fas fa-users text-primary me-1"></i>
                                                Group <span class="text-danger">*</span>
                                            </label>
                                            <select name="group_id" id="boardGroupSelect" class="form-select select2_list"
                                                required>
                                                <option value="">-- Select Group --</option>
                                                @foreach ($groups as $group)
                                                    <option value="{{ $group->id }}"
                                                        {{ isset($organizationStructure) && $organizationStructure->group_id == $group->id ? 'selected' : '' }}>
                                                        {{ $group->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('group_id')
                                                <small class="text-danger"><i
                                                        class="fas fa-exclamation-circle me-1"></i>{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <!-- Company -->
                                        <div class="col-md-6 mb-3">
                                            <label for="boardCompanySelect" class="form-label fw-semibold">
                                                <i class="fas fa-building text-success me-1"></i>
                                                Company <span class="text-danger">*</span>
                                            </label>
                                            <select name="company_id" id="boardCompanySelect"
                                                class="form-select select2_list" required>
                                                <option value="">-- Select Company --</option>
                                                @foreach ($companies as $company)
                                                    <option value="{{ $company->id }}"
                                                        {{ isset($organizationStructure) && $organizationStructure->company_id == $company->id ? 'selected' : '' }}>
                                                        {{ $company->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('company_id')
                                                <small class="text-danger"><i
                                                        class="fas fa-exclamation-circle me-1"></i>{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Personal Information Section -->
                                <div class="mb-4">
                                    <h6 class="text-primary border-bottom pb-2 mb-3">
                                        <i class="fas fa-user-circle me-2"></i>Personal Information
                                    </h6>
                                    <div class="row">
                                        <!-- Profile Image Preview -->
                                        @if (isset($organizationStructure) && $organizationStructure->photo_path)
                                            <div class="col-12 mb-3 text-center">
                                                <div class="mb-2">
                                                    <img src="{{ \App\HelperClass::get_file_url($organizationStructure->photo_path) }}"
                                                        class="rounded-circle border-primary shadow"
                                                        style="width: 100px; height: 100px; object-fit: cover; border: 3px solid;"
                                                        alt="Current Profile">
                                                </div>
                                                <small class="text-muted">
                                                    <i class="fas fa-info-circle me-1"></i>Current Profile Image
                                                </small>
                                            </div>
                                        @endif

                                        <!-- Name -->
                                        <div class="col-md-4 mb-3">
                                            <label for="boardNameInput" class="form-label fw-semibold">
                                                <i class="fas fa-user text-primary me-1"></i>
                                                Full Name <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" name="name" id="boardNameInput"
                                                class="form-control" placeholder="Enter full name"
                                                value="{{ old('name', isset($organizationStructure) ? $organizationStructure->name : '') }}"
                                                required>
                                            @error('name')
                                                <small class="text-danger"><i
                                                        class="fas fa-exclamation-circle me-1"></i>{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <!-- Position (renamed from Designation) -->
                                        <div class="col-md-4 mb-3 position-relative">
                                            <label for="boardPositionInput" class="form-label fw-semibold">
                                                <i class="fas fa-id-badge text-success me-1"></i>
                                                Position <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" name="position" id="boardPositionInput"
                                                class="form-control" placeholder="Enter or select position"
                                                autocomplete="off"
                                                value="{{ old('position', isset($organizationStructure) ? $organizationStructure->position ?? ($organizationStructure->designation ?? '') : '') }}"
                                                required>
                                            <div class="suggestions-list list-group position-absolute w-100"
                                                id="boardSuggestionsList"
                                                style="z-index: 1000; display: none; max-height: 200px; overflow-y: auto;">
                                            </div>
                                            @error('position')
                                                <small class="text-danger"><i
                                                        class="fas fa-exclamation-circle me-1"></i>{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <!-- Profile Image Upload -->
                                        <div class="col-md-4 mb-3">
                                            <label for="board_photo_path" class="form-label fw-semibold">
                                                <i class="fas fa-image text-info me-1"></i>
                                                Profile Image
                                            </label>
                                            <input type="file" class="form-control" name="photo_path"
                                                id="board_photo_path" accept="image/jpeg,image/png,image/jpg,image/gif">
                                            <small class="text-muted d-block mt-1">
                                                <i class="fas fa-info-circle me-1"></i>Max 2MB (JPG, PNG, GIF)
                                            </small>
                                            @error('photo_path')
                                                <small class="text-danger"><i
                                                        class="fas fa-exclamation-circle me-1"></i>{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Contact Information Section -->
                                <div class="mb-4">
                                    <h6 class="text-primary border-bottom pb-2 mb-3">
                                        <i class="fas fa-address-book me-2"></i>Contact Information
                                    </h6>
                                    <div class="row">
                                        <!-- Email -->
                                        <div class="col-md-4 mb-3">
                                            <label for="boardEmailInput" class="form-label fw-semibold">
                                                <i class="fas fa-envelope text-danger me-1"></i>
                                                Email Address <span class="text-danger">*</span>
                                            </label>
                                            <input type="email" name="email" id="boardEmailInput"
                                                class="form-control" placeholder="example@company.com"
                                                value="{{ old('email', isset($organizationStructure) ? $organizationStructure->email : '') }}"
                                                required>
                                            @error('email')
                                                <small class="text-danger"><i
                                                        class="fas fa-exclamation-circle me-1"></i>{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <!-- Phone -->
                                        <div class="col-md-4 mb-3">
                                            <label for="boardPhoneInput" class="form-label fw-semibold">
                                                <i class="fas fa-phone text-success me-1"></i>
                                                Phone Number <span class="text-danger">*</span>
                                            </label>
                                            <input type="tel" name="contact_no" id="boardPhoneInput"
                                                class="form-control" placeholder="+880 1XXX-XXXXXX"
                                                value="{{ old('contact_no', isset($organizationStructure) ? $organizationStructure->contact_no : '') }}"
                                                required>
                                            @error('contact_no')
                                                <small class="text-danger"><i
                                                        class="fas fa-exclamation-circle me-1"></i>{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <!-- Status -->
                                        <div class="col-md-4 mb-3">
                                            <label for="boardStatus" class="form-label fw-semibold">
                                                <i class="fas fa-toggle-on text-warning me-1"></i>
                                                Status <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-select" name="status" id="boardStatus">
                                                <option value="active"
                                                    {{ isset($organizationStructure) && $organizationStructure->status_form == 'active' ? 'selected' : '' }}>
                                                    Active
                                                </option>
                                                <option value="inactive"
                                                    {{ isset($organizationStructure) && $organizationStructure->status_form == 'inactive' ? 'selected' : '' }}>
                                                    Inactive
                                                </option>
                                            </select>
                                        </div>

                                        <!-- Address -->
                                        <div class="col-md-12 mb-3">
                                            <label for="boardNotesInput" class="form-label fw-semibold">
                                                <i class="fas fa-map-marked-alt text-info me-1"></i>
                                                Address
                                            </label>
                                            <textarea name="address" id="boardNotesInput" class="form-control" rows="3"
                                                placeholder="Enter complete address (optional)">{{ old('address', isset($organizationStructure) ? $organizationStructure->address : '') }}</textarea>
                                            @error('address')
                                                <small class="text-danger"><i
                                                        class="fas fa-exclamation-circle me-1"></i>{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="d-flex justify-content-end gap-2 border-top pt-3 mt-4">
                                    <a href="{{ route('organization-structure.index') }}" class="btn btn-secondary px-4">
                                        <i class="fas fa-times me-1"></i>Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary px-5">
                                        <i class="fas fa-save me-1"></i>
                                        {{ isset($organizationStructure) ? 'Update Member' : 'Add Member' }}
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Key Member Tab -->
                        <div class="tab-pane fade {{ isset($organizationStructure) && $organizationStructure->member_type === 'Key Member' ? 'show active' : '' }}"
                            id="key-member" role="tabpanel" aria-labelledby="key-member-tab">
                            <form id="keyMemberForm"
                                action="{{ isset($organizationStructure) ? route('organization-structure.update', $organizationStructure->id) : route('organization-structure.store') }}"
                                method="post" enctype="multipart/form-data">
                                @csrf
                                @if (isset($organizationStructure))
                                    @method('PUT')
                                @endif
                                <input type="hidden" name="member_type" value="Key Member">

                                <!-- Type Section -->
                                <div class="mb-4">
                                    <h6 class="text-primary border-bottom pb-2 mb-3">
                                        <i class="fas fa-tag me-2"></i>Member Type
                                    </h6>
                                    <div class="row">
                                        <!-- Type -->
                                        <div class="col-md-4 mb-3">
                                            <label for="keyTypeSelect" class="form-label fw-semibold">
                                                <i class="fas fa-tag text-info me-1"></i>
                                                Type <span class="text-danger">*</span>
                                            </label>
                                            <select name="type" id="keyTypeSelect" class="form-select select2_list"
                                                required>
                                                <option value="">-- Select Type --</option>
                                                <option value="location"
                                                    {{ isset($organizationStructure) && $organizationStructure->type_form == 'location' ? 'selected' : '' }}>
                                                    Location</option>
                                                <option value="division"
                                                    {{ isset($organizationStructure) && $organizationStructure->type_form == 'division' ? 'selected' : '' }}>
                                                    Division</option>
                                                <option value="department"
                                                    {{ isset($organizationStructure) && $organizationStructure->type_form == 'department' ? 'selected' : '' }}>
                                                    Department</option>
                                                <option value="section"
                                                    {{ isset($organizationStructure) && $organizationStructure->type_form == 'section' ? 'selected' : '' }}>
                                                    Section</option>
                                            </select>
                                            @error('type')
                                                <small class="text-danger"><i
                                                        class="fas fa-exclamation-circle me-1"></i>{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Organization Hierarchy Section -->
                                <div class="mb-4">
                                    <h6 class="text-primary border-bottom pb-2 mb-3">
                                        <i class="fas fa-sitemap me-2"></i>Organization Hierarchy
                                    </h6>
                                    <div class="row mb-2">
                                        <!-- Company -->
                                        <div class="col-md-4 mb-3">
                                            <label for="key_company_id" class="form-label fw-semibold">
                                                <i class="fas fa-building text-success me-1"></i>
                                                Company <span class="text-danger">*</span>
                                            </label>
                                            <select name="company_id" id="key_company_id"
                                                class="form-select select2_list">
                                                <option value="">Select Company</option>
                                                @foreach ($companies as $company)
                                                    <option value="{{ $company->id }}"
                                                        {{ isset($organizationStructure) && $organizationStructure->company_id == $company->id ? 'selected' : '' }}>
                                                        {{ $company->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('company_id')
                                                <small class="text-danger"><i
                                                        class="fas fa-exclamation-circle me-1"></i>{{ $message }}</small>
                                            @enderror
                                        </div>

                                        @if (App\HelperClass::getGeneralSetting()->branch_status == 1)
                                            <!-- Branch -->
                                            <div class="col-md-4 mb-3">
                                                <label for="key_business_unit_id" class="form-label fw-semibold">
                                                    <i class="fas fa-map-marker-alt text-danger me-1"></i>
                                                    Branch
                                                </label>
                                                <select name="branch_unit_id" id="key_business_unit_id"
                                                    class="form-select select2_list">
                                                    <option value="">Select Branch</option>
                                                </select>
                                                @error('branch_unit_id')
                                                    <small class="text-danger"><i
                                                            class="fas fa-exclamation-circle me-1"></i>{{ $message }}</small>
                                                @enderror
                                            </div>
                                        @endif

                                        @if (App\HelperClass::getGeneralSetting()->division_status == 1)
                                            <!-- Division -->
                                            <div class="col-md-4 mb-3">
                                                <label for="key_division_id" class="form-label fw-semibold">
                                                    <i class="fas fa-project-diagram text-warning me-1"></i>
                                                    Division
                                                </label>
                                                <select name="division_id" id="key_division_id"
                                                    class="form-select select2_list">
                                                    <option value="">Select Division</option>
                                                </select>
                                                @error('division_id')
                                                    <small class="text-danger"><i
                                                            class="fas fa-exclamation-circle me-1"></i>{{ $message }}</small>
                                                @enderror
                                            </div>
                                        @endif
                                    </div>

                                    <div class="row">
                                        @if (App\HelperClass::getGeneralSetting()->department_status == 1)
                                            <!-- Department -->
                                            <div class="col-md-4 mb-3">
                                                <label for="key_department_id" class="form-label fw-semibold">
                                                    <i class="fas fa-sitemap text-info me-1"></i>
                                                    Department
                                                </label>
                                                <select name="department_id" id="key_department_id"
                                                    class="form-select select2_list">
                                                    <option value="">Select Department</option>
                                                </select>
                                                @error('department_id')
                                                    <small class="text-danger"><i
                                                            class="fas fa-exclamation-circle me-1"></i>{{ $message }}</small>
                                                @enderror
                                            </div>
                                        @endif

                                        @if (App\HelperClass::getGeneralSetting()->section_status == 1)
                                            <!-- Section -->
                                            <div class="col-md-4 mb-3">
                                                <label for="key_section_id" class="form-label fw-semibold">
                                                    <i class="fas fa-network-wired text-secondary me-1"></i>
                                                    Section
                                                </label>
                                                <select name="section_id" id="key_section_id"
                                                    class="form-select select2_list">
                                                    <option value="">Select Section</option>
                                                </select>
                                                @error('section_id')
                                                    <small class="text-danger"><i
                                                            class="fas fa-exclamation-circle me-1"></i>{{ $message }}</small>
                                                @enderror
                                            </div>
                                        @endif

                                        <!-- Employee Select -->
                                        <div class="col-md-12 mb-3">
                                            <label for="keyEmployeeSelect" class="form-label fw-semibold">
                                                <i class="fas fa-user text-primary me-1"></i>
                                                Select Employee <span class="text-danger">*</span>
                                            </label>
                                            <select id="keyEmployeeSelect" class="form-select select2" name="employee_id"
                                                required>
                                                <option value="">-- Select Employee --</option>
                                                @foreach ($employees as $employee)
                                                    <option value="{{ $employee->id }}"
                                                        {{ isset($organizationStructure) && $organizationStructure->employee_id == $employee->id ? 'selected' : '' }}>
                                                        {{ $employee->full_name }} ({{ $employee->system_id }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('employee_id')
                                                <small class="text-danger"><i
                                                        class="fas fa-exclamation-circle me-1"></i>{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Position Information Section -->
                                <div class="mb-4">
                                    <h6 class="text-primary border-bottom pb-2 mb-3">
                                        <i class="fas fa-briefcase me-2"></i>Position Information
                                    </h6>
                                    <div class="row">
                                        <!-- Position -->
                                        <div class="col-md-6 mb-3 position-relative">
                                            <label for="keyPositionInput" class="form-label fw-semibold">
                                                <i class="fas fa-id-badge text-success me-1"></i>
                                                Position <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" name="position" id="keyPositionInput"
                                                class="form-control" placeholder="Enter or select position"
                                                autocomplete="off"
                                                value="{{ old('position', isset($organizationStructure) ? $organizationStructure->position ?? ($organizationStructure->designation ?? '') : '') }}"
                                                required>
                                            <div class="suggestions-list list-group position-absolute w-100"
                                                id="keySuggestionsList"
                                                style="z-index: 1000; display: none; max-height: 200px; overflow-y: auto;">
                                            </div>
                                            @error('position')
                                                <small class="text-danger"><i
                                                        class="fas fa-exclamation-circle me-1"></i>{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <!-- Status -->
                                        <div class="col-md-6 mb-3">
                                            <label for="keyStatus" class="form-label fw-semibold">
                                                <i class="fas fa-toggle-on text-warning me-1"></i>
                                                Status <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-select" name="status" id="keyStatus">
                                                <option value="active"
                                                    {{ isset($organizationStructure) && $organizationStructure->status_form == 'active' ? 'selected' : '' }}>
                                                    Active
                                                </option>
                                                <option value="inactive"
                                                    {{ isset($organizationStructure) && $organizationStructure->status_form == 'inactive' ? 'selected' : '' }}>
                                                    Inactive
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="d-flex justify-content-end gap-2 border-top pt-3 mt-4">
                                    <a href="{{ route('organization-structure.index') }}" class="btn btn-secondary px-4">
                                        <i class="fas fa-times me-1"></i>Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary px-5">
                                        <i class="fas fa-save me-1"></i>
                                        {{ isset($organizationStructure) ? 'Update Member' : 'Add Member' }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            'use strict';

            const designationsByType = {
                group: ['Group Chairman', 'Group CEO', 'Group CFO', 'Group Director', 'Group VP'],
                company: ['Managing Director', 'CEO', 'CFO', 'Company Secretary', 'General Manager'],
                location: ['Site Manager', 'Branch Manager', 'Location Head', 'Operations Manager'],
                division: ['Division Head', 'VP - Division', 'Division Manager', 'Senior Manager'],
                department: ['Department Head', 'Manager', 'Assistant Manager', 'Team Lead'],
                section: ['Section Head', 'Supervisor', 'Team Lead', 'Senior Executive']
            };

            // Type hierarchy configuration
            const typeHierarchy = {
                group: ['group'],
                company: ['group', 'company'],
                location: ['group', 'company', 'location'],
                division: ['group', 'company', 'location', 'division'],
                department: ['group', 'company', 'location', 'division', 'department'],
                section: ['group', 'company', 'location', 'division', 'department', 'section']
            };

            // Initialize Select2 for all dropdowns with select2_list class first
            $(document).ready(function() {
                $('.select2_list').select2({
                    placeholder: function() {
                        return $(this).data('placeholder') || $(this).find('option:first')
                            .text();
                    },
                    allowClear: true,
                    width: '100%'
                });

                // Store initial groups for Board Member form (pre-loaded from PHP)
                const initialBoardGroups = [];
                $('#boardGroupSelect option').each(function() {
                    if ($(this).val()) {
                        initialBoardGroups.push({
                            id: $(this).val(),
                            name: $(this).text()
                        });
                    }
                });

                // Helper functions (exact copy from search_employee.blade.php)
                function loading($el, text = 'Loading...') {
                    $el.prop('disabled', true).html(`<option value="">${text}</option>`);
                }

                function reset($el, text) {
                    $el.prop('disabled', false).html(`<option value="">${text}</option>`);
                }

                function resetBoardGroupSelect() {
                    $('#boardGroupSelect').prop('disabled', false).html(
                        '<option value="">-- Select Group --</option>');
                    $.each(initialBoardGroups, function(_, item) {
                        $('#boardGroupSelect').append(
                            `<option value="${item.id}">${item.name}</option>`);
                    });
                }

                // ======================================================================
                // BOARD MEMBER ORGANIZATIONAL FILTERS
                // ======================================================================

                // ======================================================================
                // BOARD MEMBER - SIMPLE GROUP/COMPANY CASCADE
                // ======================================================================

                // Board Group Change → Load Companies
                $('#boardGroupSelect').on('change', function() {
                    const groupId = $(this).val();

                    reset($('#boardCompanySelect'), '-- Select Company --');

                    if (groupId) {
                        loading($('#boardCompanySelect'));
                        $.get(`/get-companies/${groupId}`, function(data) {
                            reset($('#boardCompanySelect'), '-- Select Company --');
                            if (!data.length) {
                                $('#boardCompanySelect').html(
                                    '<option value="">No company found</option>');
                            } else {
                                $.each(data, function(_, item) {
                                    $('#boardCompanySelect').append(
                                        `<option value="${item.id}">${item.name}</option>`
                                    );
                                });
                            }
                        }).fail(function(xhr, status, error) {
                            console.error('Failed to load companies:', error);
                            reset($('#boardCompanySelect'), '-- Select Company --');
                        });
                    }
                });

                // ======================================================================
                // KEY MEMBER ORGANIZATIONAL FILTERS (Exact copy from search_employee.blade.php)
                // ======================================================================

                // Load Divisions + Chain (Department + Section)
                function loadKeyDivisions() {
                    const companyId = $('#key_company_id').val();
                    if (!companyId) return;

                    const locationId = $('#key_business_unit_id').val() || 'null';

                    loading($('#key_division_id'));
                    reset($('#key_department_id'), 'Select Department');
                    reset($('#key_section_id'), 'Select Section');

                    $.get(`/get-divisions/${companyId}/${locationId}`, function(data) {
                        reset($('#key_division_id'), 'Select Division');
                        if (!data.length) {
                            $('#key_division_id').html(
                                '<option value="">No division found</option>');
                        } else {
                            $.each(data, function(_, item) {
                                $('#key_division_id').append(
                                    `<option value="${item.id}">${item.name}</option>`);
                            });
                        }
                        // Chain: Load departments after divisions
                        loadKeyDepartments();
                    });
                }

                // Load Departments + Chain (Section)
                function loadKeyDepartments() {
                    const companyId = $('#key_company_id').val();
                    if (!companyId) return;

                    const locationId = $('#key_business_unit_id').val() || 'null';
                    const divisionId = $('#key_division_id').val() || 'null';

                    loading($('#key_department_id'));
                    reset($('#key_section_id'), 'Select Section');

                    $.get(`/get-departments/${companyId}/${locationId}/${divisionId}`, function(data) {
                        reset($('#key_department_id'), 'Select Department');
                        if (!data.length) {
                            $('#key_department_id').html(
                                '<option value="">No department found</option>');
                        } else {
                            $.each(data, function(_, item) {
                                $('#key_department_id').append(
                                    `<option value="${item.id}">${item.department_name}</option>`
                                );
                            });
                        }
                        // Chain: Load sections after departments
                        loadKeySections();
                    });
                }

                // Load Sections
                function loadKeySections() {
                    const companyId = $('#key_company_id').val();
                    if (!companyId) return;

                    const locationId = $('#key_business_unit_id').val() || 'null';
                    const divisionId = $('#key_division_id').val() || 'null';
                    const departmentId = $('#key_department_id').val() || 'null';

                    loading($('#key_section_id'));

                    $.get(`/get-sections/${companyId}/${locationId}/${divisionId}/${departmentId}`,
                        function(data) {
                            reset($('#key_section_id'), 'Select Section');
                            if (!data.length) {
                                $('#key_section_id').html('<option value="">No section found</option>');
                            } else {
                                $.each(data, function(_, item) {
                                    $('#key_section_id').append(
                                        `<option value="${item.id}">${item.name}</option>`);
                                });
                            }
                        });
                }

                // Company Change → Load Branch + Full Chain
                $('#key_company_id').on('change', function() {
                    const companyId = $(this).val();
                    if (!companyId) return;

                    reset($('#key_division_id'), 'Select Division');
                    reset($('#key_department_id'), 'Select Department');
                    reset($('#key_section_id'), 'Select Section');

                    @if (App\HelperClass::getGeneralSetting()->branch_status == 1)
                        loading($('#key_business_unit_id'));

                        $.get(`/get-units/${companyId}`, function(data) {
                            reset($('#key_business_unit_id'), 'Select Branch');
                            if (!data.length) {
                                $('#key_business_unit_id').html(
                                    '<option value="">No branch found</option>');
                            } else {
                                $.each(data, function(_, item) {
                                    $('#key_business_unit_id').append(
                                        `<option value="${item.id}">${item.name}</option>`
                                    );
                                });
                            }
                            // Immediately load the full chain after branches
                            loadKeyDivisions();
                        }).fail(function(xhr, status, error) {
                            console.error('Failed to load branches:', error);
                            reset($('#key_business_unit_id'), 'Select Branch');
                        });
                    @else
                        // No branch → directly load divisions + chain
                        loadKeyDivisions();
                    @endif
                });

                // Branch Change → Reload Full Chain
                $('#key_business_unit_id').on('change', function() {
                    loadKeyDivisions(); // This will chain to department → section
                });

                // Division Change → Reload Department + Section
                $('#key_division_id').on('change', function() {
                    loadKeyDepartments(); // This will chain to section
                });

                // Department Change → Reload Section
                $('#key_department_id').on('change', function() {
                    loadKeySections();
                });

                // ======================================================================
                // AUTO-TRIGGER ON EDIT MODE
                // ======================================================================
                @if (isset($organizationStructure))
                    // Board Member edit mode
                    @if ($organizationStructure->member_type === 'Board Member')
                        @if ($organizationStructure->group_id)
                            setTimeout(function() {
                                $('#boardGroupSelect').val(
                                    '{{ $organizationStructure->group_id }}').trigger('change');

                                @if ($organizationStructure->company_id)
                                    setTimeout(function() {
                                        $('#boardCompanySelect').val(
                                            '{{ $organizationStructure->company_id }}');
                                    }, 300);
                                @endif
                            }, 100);
                        @endif
                    @endif

                    // Key Member edit mode
                    @if ($organizationStructure->member_type === 'Key Member')
                        @if ($organizationStructure->company_id)
                            setTimeout(function() {
                                $('#key_company_id').val(
                                    '{{ $organizationStructure->company_id }}').trigger(
                                    'change');

                                @if ($organizationStructure->branch_unit_id)
                                    setTimeout(function() {
                                        $('#key_business_unit_id').val(
                                            '{{ $organizationStructure->branch_unit_id }}'
                                        ).trigger('change');
                                    }, 600);
                                @endif

                                @if ($organizationStructure->division_id)
                                    setTimeout(function() {
                                        $('#key_division_id').val(
                                            '{{ $organizationStructure->division_id }}'
                                        ).trigger('change');
                                    }, 900);
                                @endif

                                @if ($organizationStructure->department_id)
                                    setTimeout(function() {
                                        $('#key_department_id').val(
                                            '{{ $organizationStructure->department_id }}'
                                        ).trigger('change');
                                    }, 1200);
                                @endif

                                @if ($organizationStructure->section_id)
                                    setTimeout(function() {
                                        $('#key_section_id').val(
                                            '{{ $organizationStructure->section_id }}');
                                    }, 1500);
                                @endif
                            }, 300);
                        @endif
                    @endif
                @endif

                // ======================================================================
                // POSITION AUTOCOMPLETE & EMPLOYEE SELECT
                // ======================================================================
                initializeBoardMemberForm();
                initializeKeyMemberForm();
            });

            function initializeBoardMemberForm() {
                const positionInput = document.getElementById('boardPositionInput');
                const suggestionsList = document.getElementById('boardSuggestionsList');

                // Position autocomplete
                if (positionInput) {
                    positionInput.addEventListener('input', (e) => {
                        const value = e.target.value.trim();
                        const selectedType = document.getElementById('boardTypeSelect').value;

                        if (value.length > 0 && selectedType && designationsByType[selectedType]) {
                            const suggestions = designationsByType[selectedType].filter(d =>
                                d.toLowerCase().includes(value.toLowerCase())
                            );

                            if (suggestions.length > 0) {
                                showSuggestions(suggestions, positionInput, suggestionsList);
                            } else {
                                hideSuggestions(suggestionsList);
                            }
                        } else {
                            hideSuggestions(suggestionsList);
                        }
                    });

                    positionInput.addEventListener('blur', () => {
                        setTimeout(() => hideSuggestions(suggestionsList), 200);
                    });
                }
            }

            function initializeKeyMemberForm() {
                const positionInput = document.getElementById('keyPositionInput');
                const suggestionsList = document.getElementById('keySuggestionsList');

                // Initialize Employee Select2 (simple, no AJAX)
                $('#keyEmployeeSelect').select2({
                    placeholder: '-- Select Employee --',
                    allowClear: true
                });

                // Position autocomplete
                if (positionInput) {
                    positionInput.addEventListener('input', (e) => {
                        const value = e.target.value.trim();
                        // Use generic suggestions for key members
                        const allSuggestions = [
                            'Managing Director', 'CEO', 'CFO', 'COO', 'CTO',
                            'General Manager', 'Manager', 'Senior Manager',
                            'Department Head', 'Team Lead', 'Supervisor'
                        ];

                        if (value.length > 0) {
                            const suggestions = allSuggestions.filter(d =>
                                d.toLowerCase().includes(value.toLowerCase())
                            );

                            if (suggestions.length > 0) {
                                showSuggestions(suggestions, positionInput, suggestionsList);
                            } else {
                                hideSuggestions(suggestionsList);
                            }
                        } else {
                            hideSuggestions(suggestionsList);
                        }
                    });

                    positionInput.addEventListener('blur', () => {
                        setTimeout(() => hideSuggestions(suggestionsList), 200);
                    });
                }
            }

            function showSuggestions(suggestions, inputElement, suggestionsList) {
                suggestionsList.innerHTML = '';

                suggestions.forEach(suggestion => {
                    const item = document.createElement('a');
                    item.href = '#';
                    item.className = 'list-group-item list-group-item-action';
                    item.innerHTML = `<i class="fas fa-briefcase text-muted me-2"></i>${suggestion}`;
                    item.addEventListener('click', (e) => {
                        e.preventDefault();
                        inputElement.value = suggestion;
                        hideSuggestions(suggestionsList);
                    });
                    suggestionsList.appendChild(item);
                });

                suggestionsList.style.display = 'block';
            }

            function hideSuggestions(suggestionsList) {
                suggestionsList.style.display = 'none';
            }

        });
    </script>
@endpush

