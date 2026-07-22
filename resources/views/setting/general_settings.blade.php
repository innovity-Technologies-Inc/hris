@extends('structure.master')

@section('content')
    <div class="py-4" style="max-width: 1000px; margin: 0 auto;">
        <!-- Main Card -->
        <div class="card shadow-lg border-0 rounded-4">

            <!-- Form Body -->
            <div class="card-body p-4 p-md-5">
                <form id="settingsForm" action="{{route('setting.general_settings.store')}}" method="POST"
                      enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" name="id" value="{{ isset($generalSetting) ? $generalSetting->id : '' }}">

                    <input type="hidden" name="branch_status" value="0">
                    <input type="hidden" name="division_status" value="0">
                    <input type="hidden" name="department_status" value="0">
                    <input type="hidden" name="section_status" value="0">


                    <!-- Basic Information Section -->
                    <div class="mb-5">
                        <div class="d-flex align-items-center mb-4">
                            <div
                                class="bg-primary bg-opacity-10 rounded-circle p-3 me-3 d-inline-flex align-items-center justify-content-center">
                                <i class="bi bi-info-circle-fill text-primary fs-4"></i>
                            </div>
                            <h2 class="fs-4 fw-bold text-dark mb-0">Basic Information</h2>
                        </div>

                        <!-- Software Name -->
                        <div class="card border shadow-sm mb-4">
                            <div class="card-body p-4">
                                <label for="softwareName"
                                       class="form-label fw-semibold text-dark mb-3 d-flex align-items-center">
                                    <i class="bi bi-app-indicator text-primary me-2 fs-5"></i>
                                    <span>Software Name</span>
                                    <span class="badge bg-danger ms-2">Required</span>
                                </label>
                                <input type="text" class="form-control form-control-lg" id="softwareName"
                                       name="name" placeholder="Enter your software name"
                                       value="{{ isset($generalSetting) ? $generalSetting->name : old('name') }}"
                                       required>
                                @error('name')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                                @enderror
                                <div class="form-text mt-2">
                                    <i class="bi bi-lightbulb text-warning me-1"></i>
                                    This will be displayed across your application
                                </div>
                            </div>
                        </div>

                        <!-- Branding Assets -->
                        <div class="card border shadow-sm mb-4">
                            <div class="card-header bg-light border-bottom py-3">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-palette-fill text-info me-2"></i>
                                    <span class="fw-semibold text-dark">Branding Assets</span>
                                </div>
                            </div>
                            <div class="card-body p-4">
                                <div class="row g-4">
                                    <!-- Light Theme Logo -->
                                    <div class="col-md-4">
                                        <label for="logoLight"
                                               class="form-label fw-semibold text-dark mb-3 d-flex align-items-center">
                                            <i class="bi bi-brightness-high-fill text-warning me-2 fs-5"></i>
                                            <span>Light Theme Logo</span>
                                        </label>
                                        <input type="file" class="form-control" id="logoLight" name="logo_light"
                                               accept="image/*" onchange="previewLogo(event, 'logoLightPreview')">
                                        @error('logo_light')
                                        <div class="text-danger small mt-2">{{ $message }}</div>
                                        @enderror
                                        <div class="mt-3">
                                            <div
                                                class="border border-3 border-dashed rounded-3 d-flex align-items-center justify-content-center"
                                                id="logoLightPreview" style="height: 150px;">
                                                @if(isset($generalSetting) && $generalSetting->logo_light)
                                                    <img src="{{ \App\HelperClass::get_file_url($generalSetting->logo_light) }}"
                                                         alt="Light Logo"
                                                         class="img-fluid rounded-3 shadow"
                                                         style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                                @else
                                                    <div class="text-center">
                                                        <i class="bi bi-sun fs-1 text-warning mb-2 d-block"></i>
                                                        <span class="text-muted small">Light Logo</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-text mt-2">
                                            <i class="bi bi-info-circle me-1"></i>
                                            For white/light backgrounds
                                        </div>
                                    </div>

                                    <!-- Dark Theme Logo -->
                                    <div class="col-md-4">
                                        <label for="logoDark"
                                               class="form-label fw-semibold text-dark mb-3 d-flex align-items-center">
                                            <i class="bi bi-moon-stars-fill text-primary me-2 fs-5"></i>
                                            <span>Dark Theme Logo</span>
                                        </label>
                                        <input type="file" class="form-control" id="logoDark" name="logo_dark"
                                               accept="image/*" onchange="previewLogo(event, 'logoDarkPreview')">
                                        @error('logo_dark')
                                        <div class="text-danger small mt-2">{{ $message }}</div>
                                        @enderror
                                        <div class="mt-3">
                                            <div
                                                class="border border-3 border-dashed rounded-3 bg-dark d-flex align-items-center justify-content-center"
                                                id="logoDarkPreview" style="height: 150px;">
                                                @if(isset($generalSetting) && $generalSetting->logo_dark)
                                                    <img src="{{ \App\HelperClass::get_file_url($generalSetting->logo_dark) }}"
                                                         alt="Light Logo"
                                                         class="img-fluid rounded-3 shadow"
                                                         style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                                @else
                                                    <div class="text-center">
                                                        <i class="bi bi-moon-stars fs-1 text-white mb-2 d-block"></i>
                                                        <span class="text-white small">Dark Logo</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="form-text mt-2">
                                            <i class="bi bi-info-circle me-1"></i>
                                            For dark backgrounds
                                        </div>
                                    </div>

                                    <!-- Favicon -->
                                    <div class="col-md-4">
                                        <label for="favicon"
                                               class="form-label fw-semibold text-dark mb-3 d-flex align-items-center">
                                            <i class="bi bi-star-fill text-danger me-2 fs-5"></i>
                                            <span>Favicon Icon</span>
                                        </label>
                                        <input type="file" class="form-control" id="favicon" name="favicon"
                                               accept="image/x-icon,image/png,image/svg+xml"
                                               onchange="previewLogo(event, 'faviconPreview')">
                                        @error('favicon')
                                        <div class="text-danger small mt-2">{{ $message }}</div>
                                        @enderror
                                        <div class="mt-3">
                                            <div
                                                class="border border-3 border-dashed rounded-3 bg-light d-flex align-items-center justify-content-center"
                                                id="faviconPreview" style="height: 150px;">
                                                @if(isset($generalSetting) && $generalSetting->favicon)
                                                    <img src="{{ \App\HelperClass::get_file_url($generalSetting->favicon) }}"
                                                         alt="Favicon"
                                                         class="img-fluid rounded-3 shadow"
                                                         style="max-width: 100%; max-height: 100%; object-fit: contain;">
                                                @else
                                                    <div class="text-center">
                                                        <i class="bi bi-star fs-1 text-danger mb-2 d-block"></i>
                                                        <span class="text-muted small">Favicon</span>
                                                    </div>
                                                @endif

                                            </div>
                                        </div>
                                        <div class="form-text mt-2">
                                            <i class="bi bi-info-circle me-1"></i>
                                            32x32px or 64x64px ICO/PNG
                                        </div>
                                    </div>
                                </div>

                                <!-- Logo Help Text -->
                                <div class="alert alert-info border-2 mt-4 mb-0 d-flex align-items-start" role="alert">
                                    <i class="bi bi-lightbulb-fill fs-5 me-3 flex-shrink-0"></i>
                                    <div>
                                        <strong>Tip:</strong> Upload separate logos optimized for light and dark themes
                                        to
                                        ensure perfect visibility in all modes. Recommended size: 200x60px (PNG with
                                        transparent background).
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Currency Selection -->
                        <div class="card border shadow-sm">
                            <div class="card-body p-4">
                                <label for="currency"
                                       class="form-label fw-semibold text-dark mb-3 d-flex align-items-center">
                                    <i class="bi bi-currency-exchange text-primary me-2 fs-5"></i>
                                    <span>Currency</span>
                                    <span class="badge bg-danger ms-2">Required</span>
                                </label>
                                <select class="form-select form-select-lg" id="currency" name="currency" required>
                                    <option value="">Choose your currency</option>
                                    <option
                                        value="USD" {{ isset($generalSetting) && $generalSetting->currency == 'USD' ? 'selected' : '' }}>
                                        🇺🇸 USD - US
                                        Dollar ($)
                                    </option>
                                    <option
                                        value="BDT" {{ isset($generalSetting) && $generalSetting->currency == 'BDT' ? 'selected' : '' }}>
                                        🇧🇩 BDT -
                                        Bangladeshi Taka (৳)
                                    </option>
                                    <option
                                        value="EUR" {{ isset($generalSetting) && $generalSetting->currency == 'EUR' ? 'selected' : '' }}>
                                        🇪🇺 EUR - Euro
                                        (€)
                                    </option>
                                    <option
                                        value="GBP" {{ isset($generalSetting) && $generalSetting->currency == 'GBP' ? 'selected' : '' }}>
                                        🇬🇧 GBP -
                                        British Pound (£)
                                    </option>
                                    <option
                                        value="INR" {{ isset($generalSetting) && $generalSetting->currency == 'INR' ? 'selected' : '' }}>
                                        🇮🇳 INR -
                                        Indian Rupee (₹)
                                    </option>
                                    <option
                                        value="JPY" {{ isset($generalSetting) && $generalSetting->currency == 'JPY' ? 'selected' : '' }}>
                                        🇯🇵 JPY -
                                        Japanese Yen (¥)
                                    </option>
                                    <option
                                        value="CNY" {{ isset($generalSetting) && $generalSetting->currency == 'CNY' ? 'selected' : '' }}>
                                        🇨🇳 CNY -
                                        Chinese Yuan (¥)
                                    </option>
                                    <option
                                        value="AUD" {{ isset($generalSetting) && $generalSetting->currency == 'AUD' ? 'selected' : '' }}>
                                        🇦🇺 AUD -
                                        Australian Dollar (A$)
                                    </option>
                                    <option
                                        value="CAD" {{ isset($generalSetting) && $generalSetting->currency == 'CAD' ? 'selected' : '' }}>
                                        🇨🇦 CAD -
                                        Canadian Dollar (C$)
                                    </option>
                                </select>
                                @error('currency')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                                @enderror
                                <div class="form-text mt-2">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Used for all financial transactions
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Organization Structure Section -->
                    <div class="mb-5">
                        <div class="d-flex align-items-center mb-4">
                            <div
                                class="bg-success bg-opacity-10 rounded-circle p-3 me-3 d-inline-flex align-items-center justify-content-center">
                                <i class="bi bi-diagram-3-fill text-success fs-4"></i>
                            </div>
                            <h2 class="fs-4 fw-bold text-dark mb-0">Organization Structure</h2>
                        </div>

                        <!-- Fixed Hierarchy Display -->
                        <div class="card border shadow-sm mb-4">
                            <div class="card-header bg-light border-bottom py-3">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-lock-fill text-secondary me-2"></i>
                                    <span class="fw-semibold text-dark">Fixed Organizational Hierarchy</span>
                                    <span class="badge bg-secondary ms-2">Non-editable</span>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <!-- Level 1: Group -->
                                    <div class="col-md-4">
                                        <div class="card bg-secondary text-white border-0 h-100">
                                            <div class="p-2">
                                                <div class="d-flex align-items-center">
                                                    <span class="badge text-dark me-2">Level 1</span>
                                                    <div class="flex-fill">
                                                        <i class="bi bi-building me-2"></i>
                                                        <span class="fw-bold">Group</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Level 2: Company -->
                                    <div class="col-md-4">
                                        <div class="card bg-secondary text-white border-0 h-100">
                                            <div class="p-2">
                                                <div class="d-flex align-items-center">
                                                    <span class="badge text-dark me-2">Level 2</span>
                                                    <div class="flex-fill">
                                                        <i class="bi bi-buildings me-2"></i>
                                                        <span class="fw-bold">Company</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Selectable Levels -->
                        <div class="card border shadow-sm">
                            <div class="card-header bg-light border-bottom py-3">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-check2-square text-success me-2"></i>
                                    <span class="fw-semibold text-dark">Additional Organizational Levels</span>
                                    <span class="badge bg-success ms-2">Optional</span>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class="list-group list-group-flush">
                                    <!-- Branch Unit -->
                                    <label class="list-group-item list-group-item-action p-4 border-0 border-bottom"
                                           for="checkBranchUnit" style="cursor: pointer;">
                                        <div class="d-flex align-items-center">
                                            <input class="form-check-input me-3" type="checkbox" value="1"
                                                   id="checkBranchUnit" name="branch_status"
                                                   {{ isset($generalSetting) && $generalSetting->branch_status == 1 ? 'checked' : '' }}
                                                   style="width: 1.5rem; height: 1.5rem; cursor: pointer;">
                                            <div class="flex-fill">
                                                <div class="d-flex align-items-center mb-1">
                                                    <span class="badge bg-primary me-2">Level 3</span>
                                                    <i class="bi bi-geo-alt-fill text-primary me-2 fs-5"></i>
                                                    <span class="fw-bold text-dark">Branch Unit</span>
                                                </div>
                                                <small class="text-muted d-block ms-5">
                                                    Regional offices, locations, or geographic divisions
                                                </small>
                                            </div>
                                        </div>
                                    </label>

                                    <!-- Division -->
                                    <label class="list-group-item list-group-item-action p-4 border-0 border-bottom"
                                           for="checkDivision" style="cursor: pointer;">
                                        <div class="d-flex align-items-center">
                                            <input class="form-check-input me-3" type="checkbox" value="1"
                                                   id="checkDivision" name="division_status"
                                                   {{ isset($generalSetting) && $generalSetting->division_status == 1 ? 'checked' : '' }}
                                                   style="width: 1.5rem; height: 1.5rem; cursor: pointer;">
                                            <div class="flex-fill">
                                                <div class="d-flex align-items-center mb-1">
                                                    <span class="badge bg-info me-2">Level 4</span>
                                                    <i class="bi bi-grid-3x3-gap-fill text-info me-2 fs-5"></i>
                                                    <span class="fw-bold text-dark">Division</span>
                                                </div>
                                                <small class="text-muted d-block ms-5">
                                                    Major business divisions or functional groups
                                                </small>
                                            </div>
                                        </div>
                                    </label>

                                    <!-- Department -->
                                    <label class="list-group-item list-group-item-action p-4 border-0 border-bottom"
                                           for="checkDepartment" style="cursor: pointer;">
                                        <div class="d-flex align-items-center">
                                            <input class="form-check-input me-3" type="checkbox" value="1"
                                                   id="checkDepartment" name="department_status"
                                                   {{ isset($generalSetting) && $generalSetting->department_status == 1 ? 'checked' : '' }}
                                                   style="width: 1.5rem; height: 1.5rem; cursor: pointer;">
                                            <div class="flex-fill">
                                                <div class="d-flex align-items-center mb-1">
                                                    <span class="badge bg-warning me-2">Level 5</span>
                                                    <i class="bi bi-briefcase-fill text-warning me-2 fs-5"></i>
                                                    <span class="fw-bold text-dark">Department</span>
                                                </div>
                                                <small class="text-muted d-block ms-5">
                                                    Operational departments like HR, IT, Finance, etc.
                                                </small>
                                            </div>
                                        </div>
                                    </label>

                                    <!-- Section -->
                                    <label class="list-group-item list-group-item-action p-4 border-0"
                                           for="checkSection"
                                           style="cursor: pointer;">
                                        <div class="d-flex align-items-center">
                                            <input class="form-check-input me-3" type="checkbox" value="1"
                                                   id="checkSection" name="section_status"
                                                   {{ isset($generalSetting) && $generalSetting->section_status == 1 ? 'checked' : '' }}
                                                   style="width: 1.5rem; height: 1.5rem; cursor: pointer;">
                                            <div class="flex-fill">
                                                <div class="d-flex align-items-center mb-1">
                                                    <span class="badge bg-danger me-2">Level 6</span>
                                                    <i class="bi bi-people-fill text-danger me-2 fs-5"></i>
                                                    <span class="fw-bold text-dark">Section</span>
                                                </div>
                                                <small class="text-muted d-block ms-5">
                                                    Smallest organizational units or teams within departments
                                                </small>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-end gap-3">
                        <a href="{{ route('dashboard.index') }}"
                           class="btn btn-lg btn-outline-secondary px-4 px-md-5 rounded-3">
                            <i class="bi bi-x-circle me-2"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-lg btn-dark px-4 px-md-5 rounded-3 shadow">
                            <i class="bi bi-check-circle-fill me-2"></i>Save Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Footer Info -->
        <div class="text-center mt-4 text-muted small">
            <i class="bi bi-info-circle me-1"></i>
            Changes will take effect immediately after saving
        </div>
    </div>

    <script>
        function previewLogo(event, previewId) {
            const file = event.target.files[0];
            const preview = document.getElementById(previewId);

            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.innerHTML = '<img src="' + e.target.result +
                        '" alt="Preview" class="img-fluid rounded-3 shadow" style="max-width: 100%; max-height: 100%; object-fit: contain;">';
                }
                reader.readAsDataURL(file);
            }
        }

        // Add hover effects to list items
        document.querySelectorAll('.list-group-item-action').forEach(item => {
            item.addEventListener('mouseenter', function () {
                this.style.backgroundColor = 'var(--bs-light)';
            });
            item.addEventListener('mouseleave', function () {
                this.style.backgroundColor = '';
            });
        });

        // Add input validation styling
        document.querySelectorAll('input[required], select[required]').forEach(field => {
            field.addEventListener('blur', function () {
                if (this.value.trim() === '') {
                    this.classList.add('border-danger');
                    this.classList.remove('border-success');
                } else {
                    this.classList.add('border-success');
                    this.classList.remove('border-danger');
                }
            });
        });
    </script>
@endsection

