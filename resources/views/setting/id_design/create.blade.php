@extends('structure.master')

@section('content')
    <div class="py-4" style="max-width: 900px; margin: 0 auto;">
        <!-- Header -->
        <div class="mb-4">
            <a href="{{ route('setting.id_design.index') }}" class="btn btn-outline-secondary btn-sm mb-3">
                <i class="bi bi-arrow-left me-1"></i> Back to Designs
            </a>
            <h2 class="fs-3 fw-bold text-dark mb-1">Create New ID Card Design</h2>
            <p class="text-muted mb-0">Upload a custom Blade template for employee ID cards</p>
        </div>

        <!-- Main Card -->
        <div class="card shadow-lg border-0 rounded-4">
            <div class="card-body p-4 p-md-5">
                <!-- Validation Errors -->
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <h6 class="alert-heading fw-bold mb-2">
                            <i class="bi bi-exclamation-triangle me-2"></i>Validation Errors
                        </h6>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Form -->
                <form action="{{ route('setting.id_design.store') }}" method="POST" enctype="multipart/form-data"
                    id="designForm">
                    @csrf

                    <!-- Theme Name -->
                    <div class="mb-4">
                        <label for="theme_name" class="form-label fw-semibold text-dark mb-2">
                            <i class="bi bi-tag-fill text-primary me-2"></i>
                            Theme Name
                            <span class="badge bg-danger">Required</span>
                        </label>
                        <input type="text" class="form-control form-control-lg @error('theme_name') is-invalid @enderror"
                            id="theme_name" name="theme_name" value="{{ old('theme_name') }}"
                            placeholder="e.g., Modern Corporate Design" required>
                        @error('theme_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="form-text text-muted">
                            Give your design a unique, descriptive name
                        </small>
                    </div>

                    <!-- Description -->
                    <div class="mb-4">
                        <label for="description" class="form-label fw-semibold text-dark mb-2">
                            <i class="bi bi-text-left text-primary me-2"></i>
                            Description
                            <span class="badge bg-secondary">Optional</span>
                        </label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                            rows="3" placeholder="Describe the design features and use cases...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Template Source Selection -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold text-dark mb-2">
                            <i class="bi bi-gear-fill text-primary me-2"></i>
                            Template Source
                        </label>
                        <div class="d-flex gap-4">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="template_source" id="source_preloaded" value="preloaded" {{ old('template_source', 'preloaded') === 'preloaded' ? 'checked' : '' }}>
                                <label class="form-check-label" for="source_preloaded">
                                    Preloaded Demo Template
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="template_source" id="source_upload" value="upload" {{ old('template_source') === 'upload' ? 'checked' : '' }}>
                                <label class="form-check-label" for="source_upload">
                                    Upload Custom File
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Preloaded Template Dropdown -->
                    <div class="mb-4" id="preloaded_template_container" style="display: none;">
                        <label for="preloaded_template" class="form-label fw-semibold text-dark mb-2">
                            <i class="bi bi-file-earmark-code-fill text-primary me-2"></i>
                            Select Demo Template
                            <span class="badge bg-danger">Required</span>
                        </label>
                        <select class="form-select @error('preloaded_template') is-invalid @enderror" id="preloaded_template" name="preloaded_template">
                            <option value="" disabled selected>Choose a demo template...</option>
                            <option value="design_1" {{ old('preloaded_template') === 'design_1' ? 'selected' : '' }}>Theme 1 (Modern Corporate)</option>
                            <option value="design_2" {{ old('preloaded_template') === 'design_2' ? 'selected' : '' }}>Theme 2 (Modern Clean with Orange Badge)</option>
                            <option value="design_3" {{ old('preloaded_template') === 'design_3' ? 'selected' : '' }}>Theme 3 (Professional Bordered)</option>
                            <option value="design_4" {{ old('preloaded_template') === 'design_4' ? 'selected' : '' }}>Theme 4 (Minimalist Portrait)</option>
                        </select>
                        @error('preloaded_template')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Design File Upload -->
                    <div class="mb-4" id="upload_file_container" style="display: none;">
                        <label for="design_file" class="form-label fw-semibold text-dark mb-2">
                            <i class="bi bi-file-code text-primary me-2"></i>
                            Design Template File
                            <span class="badge bg-danger">Required</span>
                        </label>
                        <input type="file" class="form-control @error('design_file') is-invalid @enderror"
                            id="design_file" name="design_file" accept=".php,.blade.php">
                        @error('design_file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            <i class="bi bi-info-circle me-1"></i>
                            <strong>Accepted formats:</strong> .blade.php, .php (Max: 2MB)
                        </div>

                        <!-- Security Notice -->
                        <div class="alert alert-warning mt-2 mb-0">
                            <small>
                                <i class="bi bi-shield-exclamation me-1"></i>
                                <strong>Security:</strong> Dangerous PHP functions (eval, exec, system, etc.) are not
                                allowed and will be rejected.
                            </small>
                        </div>
                    </div>

                    <!-- Front Card Preview -->
                    <div class="mb-4">
                        <label for="preview_front_card" class="form-label fw-semibold text-dark mb-2">
                            <i class="bi bi-card-heading text-primary me-2"></i>
                            Front Card Preview
                            <span class="badge bg-secondary">Optional</span>
                        </label>
                        <input type="file" class="form-control @error('preview_front_card') is-invalid @enderror"
                            id="preview_front_card" name="preview_front_card"
                            accept="image/jpeg,image/png,image/jpg,image/gif">
                        @error('preview_front_card')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            <i class="bi bi-info-circle me-1"></i>
                            Upload a screenshot of the front side of your ID card (JPEG, PNG, GIF - Max: 2MB)
                        </div>

                        <!-- Front Preview Container -->
                        <div id="frontPreviewContainer" class="mt-3" style="display: none;">
                            <div class="card border">
                                <div class="card-body text-center">
                                    <span class="badge bg-primary mb-2">Front</span>
                                    <img id="frontPreview" src="" alt="Front Preview" class="img-fluid"
                                        style="max-height: 200px;">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Back Card Preview -->
                    <div class="mb-4">
                        <label for="preview_back_card" class="form-label fw-semibold text-dark mb-2">
                            <i class="bi bi-card-text text-primary me-2"></i>
                            Back Card Preview
                            <span class="badge bg-secondary">Optional</span>
                        </label>
                        <input type="file" class="form-control @error('preview_back_card') is-invalid @enderror"
                            id="preview_back_card" name="preview_back_card"
                            accept="image/jpeg,image/png,image/jpg,image/gif">
                        @error('preview_back_card')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            <i class="bi bi-info-circle me-1"></i>
                            Upload a screenshot of the back side of your ID card (JPEG, PNG, GIF - Max: 2MB)
                        </div>

                        <!-- Back Preview Container -->
                        <div id="backPreviewContainer" class="mt-3" style="display: none;">
                            <div class="card border">
                                <div class="card-body text-center">
                                    <span class="badge bg-secondary mb-2">Back</span>
                                    <img id="backPreview" src="" alt="Back Preview" class="img-fluid"
                                        style="max-height: 200px;">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Template Guidelines -->
                    <div class="card bg-light border-0 mb-4">
                        <div class="card-body">
                            <h6 class="fw-bold mb-3">
                                <i class="bi bi-lightbulb text-warning me-2"></i>
                                Template Guidelines
                            </h6>
                            <ul class="mb-0 small">
                                <li>Your template should be a valid Blade (.blade.php) file</li>
                                <li>Use <code>{{ '$employee' }}</code> variable for employee data (full_name, system_id,
                                    photo_path, etc.)</li>
                                <li>Use <code>{{ '$company' }}</code> variable for company information</li>
                                <li>Keep file size under 2MB</li>
                                <li>Avoid using dangerous PHP functions for security</li>
                                <li>Test your template thoroughly before activating</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Sample Template -->
                    <div class="card border-primary mb-4">
                        <div class="card-header bg-primary text-white">
                            <i class="bi bi-code-square me-2"></i>
                            Sample Template Structure
                        </div>
                        <div class="card-body">
                            <pre class="mb-0 small"><code>&lt;div class="id-card"&gt;
    &lt;div class="header"&gt;
        &lt;img src="@{{ \App\HelperClass::get_file_url($company - > logo_light) }}" alt="Company Logo"&gt;
    &lt;/div&gt;
    &lt;div class="photo"&gt;
        &lt;img src="@{{ \App\HelperClass::get_file_url($employee - > photo_path) }}" alt="Employee Photo"&gt;
    &lt;/div&gt;
    &lt;h3&gt;@{{ $employee - > full_name }}&lt;/h3&gt;
    &lt;p&gt;ID: @{{ $employee - > system_id }}&lt;/p&gt;
    &lt;p&gt;@{{ $employee - > designation }}&lt;/p&gt;
&lt;/div&gt;</code></pre>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex gap-3 justify-content-end">
                        <a href="{{ route('setting.id_design.index') }}" class="btn btn-outline-secondary px-4">
                            <i class="bi bi-x-circle me-2"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-check-circle me-2"></i>Create Design
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Generic image preview functionality
        function setupImagePreview(inputId, previewId, containerId) {
            document.getElementById(inputId).addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        document.getElementById(previewId).src = e.target.result;
                        document.getElementById(containerId).style.display = 'block';
                    }
                    reader.readAsDataURL(file);
                } else {
                    document.getElementById(containerId).style.display = 'none';
                }
            });
        }

        // Setup all image previews
        setupImagePreview('preview_front_card', 'frontPreview', 'frontPreviewContainer');
        setupImagePreview('preview_back_card', 'backPreview', 'backPreviewContainer');

        // Template source toggling
        const sourcePreloaded = document.getElementById('source_preloaded');
        const sourceUpload = document.getElementById('source_upload');
        const preloadedContainer = document.getElementById('preloaded_template_container');
        const uploadContainer = document.getElementById('upload_file_container');
        const designFileInput = document.getElementById('design_file');
        const preloadedTemplateSelect = document.getElementById('preloaded_template');

        function toggleTemplateSource() {
            if (sourcePreloaded.checked) {
                preloadedContainer.style.display = 'block';
                uploadContainer.style.display = 'none';
                designFileInput.removeAttribute('required');
                preloadedTemplateSelect.setAttribute('required', 'required');
            } else {
                preloadedContainer.style.display = 'none';
                uploadContainer.style.display = 'block';
                designFileInput.setAttribute('required', 'required');
                preloadedTemplateSelect.removeAttribute('required');
            }
        }

        sourcePreloaded.addEventListener('change', toggleTemplateSource);
        sourceUpload.addEventListener('change', toggleTemplateSource);
        
        // Initial call
        toggleTemplateSource();

        // Auto-complete other fields when preloaded template is selected
        const themeNameInput = document.getElementById('theme_name');
        const descriptionInput = document.getElementById('description');

        const preloadedDetails = {
            'design_1': {
                name: 'Theme 1 (Modern Corporate)',
                desc: 'Modern vertical corporate ID card design with abstract shapes and clean hierarchy.'
            },
            'design_2': {
                name: 'Theme 2 (Modern Clean with Orange Badge)',
                desc: 'Clean vertical design featuring a distinct primary header bar and orange accents divider.'
            },
            'design_3': {
                name: 'Theme 3 (Professional Bordered)',
                desc: 'Professional bordered vertical ID card design with light grayscale layout.'
            },
            'design_4': {
                name: 'Theme 4 (Minimalist Portrait)',
                desc: 'Sleek minimalist vertical ID card design focusing on content space and clean typography.'
            }
        };

        preloadedTemplateSelect.addEventListener('change', function() {
            const selectedVal = preloadedTemplateSelect.value;
            if (preloadedDetails[selectedVal]) {
                themeNameInput.value = preloadedDetails[selectedVal].name;
                descriptionInput.value = preloadedDetails[selectedVal].desc;
            }
        });

        // Form validation
        document.getElementById('designForm').addEventListener('submit', function(e) {
            const themeNameInput = document.getElementById('theme_name');

            if (!themeNameInput.value.trim()) {
                e.preventDefault();
                alert('Please enter a theme name');
                themeNameInput.focus();
                return false;
            }

            if (sourceUpload.checked) {
                if (!designFileInput.files.length) {
                    e.preventDefault();
                    alert('Please upload a design template file');
                    designFileInput.focus();
                    return false;
                }

                // File size validation (2MB = 2097152 bytes)
                const file = designFileInput.files[0];
                if (file.size > 2097152) {
                    e.preventDefault();
                    alert('Design file size must be less than 2MB');
                    return false;
                }
            } else {
                if (!preloadedTemplateSelect.value) {
                    e.preventDefault();
                    alert('Please select a demo template');
                    preloadedTemplateSelect.focus();
                    return false;
                }
            }

            // Validate preview images if provided
            const imageInputs = ['preview_front_card', 'preview_back_card'];
            for (const inputId of imageInputs) {
                const input = document.getElementById(inputId);
                if (input.files.length && input.files[0].size > 2097152) {
                    e.preventDefault();
                    alert(`${inputId.replace(/_/g, ' ')} must be less than 2MB`);
                    return false;
                }
            }
        });
    </script>

    <style>
        code {
            background-color: #f8f9fa;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 0.9em;
        }

        pre code {
            display: block;
            padding: 1rem;
            background-color: #f8f9fa;
            border-radius: 0.25rem;
            overflow-x: auto;
        }
    </style>
@endsection

