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
                                @if(isset($organizationStructure))
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
                            <button class="nav-link {{ isset($organizationStructure) ? ($organizationStructure->member_type === 'Board Member' ? 'active' : '') : 'active' }}" id="board-member-tab" data-bs-toggle="tab"
                                data-bs-target="#board-member" type="button" role="tab" aria-controls="board-member"
                                aria-selected="{{ isset($organizationStructure) ? ($organizationStructure->member_type === 'Board Member' ? 'true' : 'false') : 'true' }}">
                                <i class="fas fa-users-cog me-2"></i>Board Member
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ isset($organizationStructure) && $organizationStructure->member_type === 'Key Member' ? 'active' : '' }}" id="key-member-tab" data-bs-toggle="tab" data-bs-target="#key-member"
                                type="button" role="tab" aria-controls="key-member" aria-selected="{{ isset($organizationStructure) && $organizationStructure->member_type === 'Key Member' ? 'true' : 'false' }}">
                                <i class="fas fa-user-tie me-2"></i>Key Member
                            </button>
                        </li>
                    </ul>

                    <!-- Tabs Content -->
                    <div class="tab-content" id="memberTypeTabsContent">
                        <!-- Board Member Tab -->
                        <div class="tab-pane fade {{ isset($organizationStructure) ? ($organizationStructure->member_type === 'Board Member' ? 'show active' : '') : 'show active' }}" id="board-member" role="tabpanel"
                            aria-labelledby="board-member-tab">
                            <form id="boardMemberForm"
                                action="{{ isset($organizationStructure) ? route('organization-structure.update', $organizationStructure->id) : route('organization-structure.store') }}"
                                method="post" enctype="multipart/form-data">
                                @csrf
                                @if (isset($organizationStructure))
                                    @method('PUT')
                                @endif
                                <input type="hidden" name="member_type" value="Board Member">

                                <!-- Organization Hierarchy Section -->
                                <div class="mb-4">
                                    <h6 class="text-primary border-bottom pb-2 mb-3">
                                        <i class="fas fa-sitemap me-2"></i>Organization Hierarchy
                                    </h6>
                                    <div class="row">
                                        <!-- Type Selector -->
                                        <div class="col-md-6 mb-3">
                                            <label for="boardTypeSelect" class="form-label fw-semibold">
                                                <i class="fas fa-layer-group text-info me-1"></i>
                                                Type <span class="text-danger">*</span>
                                            </label>
                                            <select name="type" id="boardTypeSelect" class="form-select" required>
                                                <option value="">-- Select Type --</option>
                                                <option value="group"
                                                    {{ isset($organizationStructure) && $organizationStructure->type_form == 'group' ? 'selected' : '' }}>
                                                    <i class="fas fa-users"></i> Group
                                                </option>
                                                <option value="company"
                                                    {{ isset($organizationStructure) && $organizationStructure->type_form == 'company' ? 'selected' : '' }}>
                                                    Company
                                                </option>
                                            </select>
                                            @error('type')
                                                <small class="text-danger"><i
                                                        class="fas fa-exclamation-circle me-1"></i>{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <!-- Group -->
                                        <div class="col-md-6 mb-3" id="boardGroupField" style="display: none;">
                                            <label for="boardGroupSelect" class="form-label fw-semibold">
                                                <i class="fas fa-users text-primary me-1"></i>
                                                Group <span class="text-danger">*</span>
                                            </label>
                                            <select name="group_id" id="boardGroupSelect" class="form-select select2_list">
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
                                        <div class="col-md-6 mb-3" id="boardCompanyField" style="display: none;">
                                            <label for="boardCompanySelect" class="form-label fw-semibold">
                                                <i class="fas fa-building text-success me-1"></i>
                                                Company <span class="text-danger">*</span>
                                            </label>
                                            <select name="company_id" id="boardCompanySelect"
                                                class="form-select select2_list">
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
                                                    <img src="{{ asset('storage/' . $organizationStructure->photo_path) }}"
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
                        <div class="tab-pane fade {{ isset($organizationStructure) && $organizationStructure->member_type === 'Key Member' ? 'show active' : '' }}" id="key-member" role="tabpanel" aria-labelledby="key-member-tab">
                            <form id="keyMemberForm"
                                action="{{ isset($organizationStructure) ? route('organization-structure.update', $organizationStructure->id) : route('organization-structure.store') }}"
                                method="post" enctype="multipart/form-data">
                                @csrf
                                @if (isset($organizationStructure))
                                    @method('PUT')
                                @endif
                                <input type="hidden" name="member_type" value="Key Member">

                                <!-- Organization Hierarchy Section -->
                                <div class="mb-4">
                                    <h6 class="text-primary border-bottom pb-2 mb-3">
                                        <i class="fas fa-sitemap me-2"></i>Organization Hierarchy
                                    </h6>
                                    <div class="row">
                                        <!-- Type Selector -->
                                        <div class="col-md-6 mb-3">
                                            <label for="keyTypeSelect" class="form-label fw-semibold">
                                                <i class="fas fa-layer-group text-info me-1"></i>
                                                Type <span class="text-danger">*</span>
                                            </label>
                                            <select name="type" id="keyTypeSelect" class="form-select" required>
                                                <option value="">-- Select Type --</option>
                                                <option value="location"
                                                    {{ isset($organizationStructure) && $organizationStructure->type_form == 'location' ? 'selected' : '' }}>
                                                    Location
                                                </option>
                                                <option value="division"
                                                    {{ isset($organizationStructure) && $organizationStructure->type_form == 'division' ? 'selected' : '' }}>
                                                    Division
                                                </option>
                                                <option value="department"
                                                    {{ isset($organizationStructure) && $organizationStructure->type_form == 'department' ? 'selected' : '' }}>
                                                    Department
                                                </option>
                                                <option value="section"
                                                    {{ isset($organizationStructure) && $organizationStructure->type_form == 'section' ? 'selected' : '' }}>
                                                    Section
                                                </option>
                                            </select>
                                            @error('type')
                                                <small class="text-danger"><i
                                                        class="fas fa-exclamation-circle me-1"></i>{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <!-- Group -->
                                        <div class="col-md-6 mb-3" id="keyGroupField" style="display: none;">
                                            <label for="keyGroupSelect" class="form-label fw-semibold">
                                                <i class="fas fa-users text-primary me-1"></i>
                                                Group <span class="text-danger">*</span>
                                            </label>
                                            <select name="group_id" id="keyGroupSelect" class="form-select select2_list">
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
                                        <div class="col-md-6 mb-3" id="keyCompanyField" style="display: none;">
                                            <label for="keyCompanySelect" class="form-label fw-semibold">
                                                <i class="fas fa-building text-success me-1"></i>
                                                Company <span class="text-danger">*</span>
                                            </label>
                                            <select name="company_id" id="keyCompanySelect"
                                                class="form-select select2_list">
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

                                        <!-- Location -->
                                        <div class="col-md-6 mb-3" id="keyLocationField" style="display: none;">
                                            <label for="keyLocationSelect" class="form-label fw-semibold">
                                                <i class="fas fa-map-marker-alt text-danger me-1"></i>
                                                Location <span class="text-danger">*</span>
                                            </label>
                                            <select name="branch_unit_id" id="keyLocationSelect"
                                                class="form-select select2_list">
                                                <option value="">-- Select Location --</option>
                                                @foreach ($locations as $location)
                                                    <option value="{{ $location->id }}"
                                                        {{ isset($organizationStructure) && $organizationStructure->branch_unit_id == $location->id ? 'selected' : '' }}>
                                                        {{ $location->unit_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('branch_unit_id')
                                                <small class="text-danger"><i
                                                        class="fas fa-exclamation-circle me-1"></i>{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <!-- Division -->
                                        <div class="col-md-6 mb-3" id="keyDivisionField" style="display: none;">
                                            <label for="keyDivisionSelect" class="form-label fw-semibold">
                                                <i class="fas fa-project-diagram text-warning me-1"></i>
                                                Division <span class="text-danger">*</span>
                                            </label>
                                            <select name="division_id" id="keyDivisionSelect"
                                                class="form-select select2_list">
                                                <option value="">-- Select Division --</option>
                                                @foreach ($divisions as $division)
                                                    <option value="{{ $division->id }}"
                                                        {{ isset($organizationStructure) && $organizationStructure->division_id == $division->id ? 'selected' : '' }}>
                                                        {{ $division->division_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('division_id')
                                                <small class="text-danger"><i
                                                        class="fas fa-exclamation-circle me-1"></i>{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <!-- Department -->
                                        <div class="col-md-6 mb-3" id="keyDepartmentField" style="display: none;">
                                            <label for="keyDepartmentSelect" class="form-label fw-semibold">
                                                <i class="fas fa-sitemap text-info me-1"></i>
                                                Department <span class="text-danger">*</span>
                                            </label>
                                            <select name="department_id" id="keyDepartmentSelect"
                                                class="form-select select2_list">
                                                <option value="">-- Select Department --</option>
                                                @foreach ($departments as $department)
                                                    <option value="{{ $department->id }}"
                                                        {{ isset($organizationStructure) && $organizationStructure->department_id == $department->id ? 'selected' : '' }}>
                                                        {{ $department->department_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('department_id')
                                                <small class="text-danger"><i
                                                        class="fas fa-exclamation-circle me-1"></i>{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <!-- Section -->
                                        <div class="col-md-6 mb-3" id="keySectionField" style="display: none;">
                                            <label for="keySectionSelect" class="form-label fw-semibold">
                                                <i class="fas fa-network-wired text-secondary me-1"></i>
                                                Section <span class="text-danger">*</span>
                                            </label>
                                            <select name="section_id" id="keySectionSelect"
                                                class="form-select select2_list">
                                                <option value="">-- Select Section --</option>
                                                @foreach ($sections as $section)
                                                    <option value="{{ $section->id }}"
                                                        {{ isset($organizationStructure) && $organizationStructure->section_id == $section->id ? 'selected' : '' }}>
                                                        {{ $section->section_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('section_id')
                                                <small class="text-danger"><i
                                                        class="fas fa-exclamation-circle me-1"></i>{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <!-- Employee Select -->

                                        <div class="col-md-6 mb-2">
                                            <label for="keyEmployeeSelect" class="form-label">Select Employee <span
                                                    class="text-danger">*</span></label>
                                            <select id="keyEmployeeSelect" class="form-select select2_list" name="employee_id"
                                                required>
                                                <option value="">-- Select Employee --</option>
                                                @foreach ($employees as $employee)
                                                    <option value="{{ $employee->id }}"
                                                        {{ isset($organizationStructure) && $organizationStructure->employee_id == $employee->id ? 'selected' : '' }}>
                                                        {{ $employee->full_name }}
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

    <script>
        (function() {
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
                division: ['group', 'company', 'division'],
                department: ['group', 'company', 'division', 'department'],
                section: ['group', 'company', 'division', 'department', 'section']
            };

            // Initialize Board Member Form
            initializeBoardMemberForm();

            // Initialize Key Member Form
            initializeKeyMemberForm();

            // Initialize Select2 for Employee dropdown
            initializeEmployeeSelect2();

            function initializeBoardMemberForm() {
                const typeSelect = document.getElementById('boardTypeSelect');
                const groupSelect = document.getElementById('boardGroupSelect');
                const companySelect = document.getElementById('boardCompanySelect');
                const positionInput = document.getElementById('boardPositionInput');
                const suggestionsList = document.getElementById('boardSuggestionsList');

                const groupField = document.getElementById('boardGroupField');
                const companyField = document.getElementById('boardCompanyField');

                // Initialize form on page load
                @if (isset($organizationStructure))
                    const initialType = '{{ $organizationStructure->type_form }}';
                    if (initialType && (initialType === 'group' || initialType === 'company')) {
                        showBoardFieldsForType(typeHierarchy[initialType]);
                    }
                @endif

                // Handle type change
                typeSelect.addEventListener('change', (e) => {
                    const selectedType = e.target.value;
                    hideBoardFields();

                    if (selectedType && typeHierarchy[selectedType]) {
                        showBoardFieldsForType(typeHierarchy[selectedType]);
                        updatePositionSuggestions(selectedType, positionInput, suggestionsList);
                    }
                });

                // Group change - load companies
                groupSelect.addEventListener('change', (e) => {
                    const groupId = e.target.value;
                    if (groupId) {
                        fetch(`/get-companies/${groupId}`)
                            .then(response => response.json())
                            .then(data => {
                                populateSelect(companySelect, data, 'name');
                            })
                            .catch(error => console.error('Error fetching companies:', error));
                    } else {
                        resetSelect(companySelect);
                    }
                });

                // Position autocomplete
                positionInput.addEventListener('input', (e) => {
                    const value = e.target.value.trim();
                    const selectedType = typeSelect.value;

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

                function hideBoardFields() {
                    groupField.style.display = 'none';
                    companyField.style.display = 'none';
                }

                function showBoardFieldsForType(fields) {
                    const fieldMap = {
                        group: groupField,
                        company: companyField
                    };

                    fields.forEach(fieldName => {
                        if (fieldMap[fieldName]) {
                            fieldMap[fieldName].style.display = 'block';
                        }
                    });
                }
            }

            function initializeKeyMemberForm() {
                const typeSelect = document.getElementById('keyTypeSelect');
                const groupSelect = document.getElementById('keyGroupSelect');
                const companySelect = document.getElementById('keyCompanySelect');
                const locationSelect = document.getElementById('keyLocationSelect');
                const divisionSelect = document.getElementById('keyDivisionSelect');
                const departmentSelect = document.getElementById('keyDepartmentSelect');
                const sectionSelect = document.getElementById('keySectionSelect');
                const positionInput = document.getElementById('keyPositionInput');
                const suggestionsList = document.getElementById('keySuggestionsList');

                const groupField = document.getElementById('keyGroupField');
                const companyField = document.getElementById('keyCompanyField');
                const locationField = document.getElementById('keyLocationField');
                const divisionField = document.getElementById('keyDivisionField');
                const departmentField = document.getElementById('keyDepartmentField');
                const sectionField = document.getElementById('keySectionField');

                // Initialize form on page load
                @if (isset($organizationStructure))
                    const initialType = '{{ $organizationStructure->type_form }}';
                    if (initialType && initialType !== 'group' && initialType !== 'company') {
                        showKeyFieldsForType(typeHierarchy[initialType]);
                    }
                @endif

                // Handle type change
                typeSelect.addEventListener('change', (e) => {
                    const selectedType = e.target.value;
                    hideKeyFields();

                    if (selectedType && typeHierarchy[selectedType]) {
                        showKeyFieldsForType(typeHierarchy[selectedType]);
                        updatePositionSuggestions(selectedType, positionInput, suggestionsList);
                    }
                });

                // Group change - load companies
                groupSelect.addEventListener('change', (e) => {
                    const groupId = e.target.value;
                    if (groupId) {
                        fetch(`/get-companies/${groupId}`)
                            .then(response => response.json())
                            .then(data => {
                                populateSelect(companySelect, data, 'name');
                            })
                            .catch(error => console.error('Error fetching companies:', error));
                    } else {
                        resetSelect(companySelect);
                        resetSelect(locationSelect);
                        resetSelect(divisionSelect);
                        resetSelect(departmentSelect);
                        resetSelect(sectionSelect);
                    }
                });

                // Company change - load locations and divisions
                companySelect.addEventListener('change', (e) => {
                    const companyId = e.target.value;
                    if (companyId) {
                        // Fetch locations
                        fetch(`/get-locations/${companyId}`)
                            .then(response => response.json())
                            .then(data => {
                                populateSelect(locationSelect, data, 'unit_name');
                            })
                            .catch(error => console.error('Error fetching locations:', error));

                        // Fetch divisions
                        fetch(`/get-org-divisions/${companyId}`)
                            .then(response => response.json())
                            .then(data => {
                                populateSelect(divisionSelect, data, 'division_name');
                            })
                            .catch(error => console.error('Error fetching divisions:', error));
                    } else {
                        resetSelect(locationSelect);
                        resetSelect(divisionSelect);
                        resetSelect(departmentSelect);
                        resetSelect(sectionSelect);
                    }
                });

                // Division change - load departments
                divisionSelect.addEventListener('change', (e) => {
                    const divisionId = e.target.value;
                    if (divisionId) {
                        fetch(`/get-org-departments/${divisionId}`)
                            .then(response => response.json())
                            .then(data => {
                                populateSelect(departmentSelect, data, 'department_name');
                            })
                            .catch(error => console.error('Error fetching departments:', error));
                    } else {
                        resetSelect(departmentSelect);
                        resetSelect(sectionSelect);
                    }
                });

                // Department change - load sections
                departmentSelect.addEventListener('change', (e) => {
                    const departmentId = e.target.value;
                    if (departmentId) {
                        fetch(`/get-org-sections/${departmentId}`)
                            .then(response => response.json())
                            .then(data => {
                                populateSelect(sectionSelect, data, 'section_name');
                            })
                            .catch(error => console.error('Error fetching sections:', error));
                    } else {
                        resetSelect(sectionSelect);
                    }
                });

                // Position autocomplete
                positionInput.addEventListener('input', (e) => {
                    const value = e.target.value.trim();
                    const selectedType = typeSelect.value;

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

                function hideKeyFields() {
                    [groupField, companyField, locationField, divisionField, departmentField, sectionField].forEach(
                        field => {
                            field.style.display = 'none';
                        });
                }

                function showKeyFieldsForType(fields) {
                    const fieldMap = {
                        group: groupField,
                        company: companyField,
                        location: locationField,
                        division: divisionField,
                        department: departmentField,
                        section: sectionField
                    };

                    fields.forEach(fieldName => {
                        if (fieldMap[fieldName]) {
                            fieldMap[fieldName].style.display = 'block';
                        }
                    });
                }
            }

            function initializeEmployeeSelect2() {
                $('#keyEmployeeSelect').select2({
                    placeholder: '-- Select Employee --',
                    ajax: {
                        url: '/get-org-employees',
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                search: params.term,
                                page: params.page || 1
                            };
                        },
                        processResults: function(data) {
                            return {
                                results: data.map(function(employee) {
                                    return {
                                        id: employee.id,
                                        text: employee.full_name + ' (' + employee.system_id + ')'
                                    };
                                })
                            };
                        },
                        cache: true
                    },
                    minimumInputLength: 0
                });

                // Set initial value if editing
                @if (isset($organizationStructure) && $organizationStructure->employee_id)
                    fetch(`/get-org-employee-by-id/{{ $organizationStructure->employee_id }}`)
                        .then(response => response.json())
                        .then(employee => {
                            const option = new Option(
                                employee.full_name + ' (' + employee.system_id + ')',
                                employee.id,
                                true,
                                true
                            );
                            $('#keyEmployeeSelect').append(option).trigger('change');
                        })
                        .catch(error => console.error('Error loading employee:', error));
                @endif
            }

            // Helper functions
            function populateSelect(selectElement, data, nameField) {
                // Keep first option (placeholder)
                selectElement.innerHTML = selectElement.options[0].outerHTML;

                data.forEach(item => {
                    const option = document.createElement('option');
                    option.value = item.id;
                    option.textContent = item[nameField];
                    selectElement.appendChild(option);
                });
            }

            function resetSelect(selectElement) {
                selectElement.innerHTML = selectElement.options[0].outerHTML;
                selectElement.value = '';
            }

            function updatePositionSuggestions(type, inputElement, suggestionsElement) {
                inputElement.value = '';
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

        })();
    </script>
@endsection
