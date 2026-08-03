@extends('structure.master')

@section('content')
    <div class="py-4" style="max-width: 900px; margin: 0 auto;">
        <!-- Header -->
        <div class="mb-4">
            <a href="{{ route('setting.id_design.index') }}" class="btn btn-outline-secondary btn-sm mb-3">
                <i class="bi bi-arrow-left me-1"></i> Back to Designs
            </a>
            <h2 class="fs-3 fw-bold text-dark mb-1">Edit ID Card Design</h2>
            <p class="text-muted mb-0">Modify details or update the template file for this design</p>
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
                <form action="{{ route('setting.id_design.update', $design->id) }}" method="POST" enctype="multipart/form-data"
                    id="designForm">
                    @csrf
                    @method('PUT')

                    <!-- Theme Name -->
                    <div class="mb-4">
                        <label for="theme_name" class="form-label fw-semibold text-dark mb-2">
                            <i class="bi bi-tag-fill text-primary me-2"></i>
                            Theme Name
                            <span class="badge bg-danger">Required</span>
                        </label>
                        <input type="text" class="form-control form-control-lg @error('theme_name') is-invalid @enderror"
                            id="theme_name" name="theme_name" value="{{ old('theme_name', $design->theme_name) }}"
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
                            rows="3" placeholder="Describe the design features and use cases...">{{ old('description', $design->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Template Source Selection -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold text-dark mb-2">
                            <i class="bi bi-gear-fill text-primary me-2"></i>
                            Template File Source
                        </label>
                        <div class="d-flex flex-column gap-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="template_source" id="source_keep" value="keep_existing" {{ old('template_source', 'keep_existing') === 'keep_existing' ? 'checked' : '' }}>
                                <label class="form-check-label" for="source_keep">
                                    Keep Existing Template File <span class="text-muted">({{ basename($design->file_path) }})</span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="template_source" id="source_preloaded" value="preloaded" {{ old('template_source') === 'preloaded' ? 'checked' : '' }}>
                                <label class="form-check-label" for="source_preloaded">
                                    Replace with Preloaded Demo Template
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="template_source" id="source_upload" value="upload" {{ old('template_source') === 'upload' ? 'checked' : '' }}>
                                <label class="form-check-label" for="source_upload">
                                    Replace with Uploaded Custom File
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
                            Image will be used as a design card preview (Max: 2MB)
                        </div>

                        <!-- Front Image Preview Container -->
                        <div class="mt-3" id="frontPreviewContainer" style="display: {{ $design->preview_front_card && Storage::disk('public')->exists($design->preview_front_card) ? 'block' : 'none' }};">
                            <p class="small text-muted mb-2">Front Card Preview Image:</p>
                            <img id="frontPreview" 
                                 src="{{ $design->preview_front_card && Storage::disk('public')->exists($design->preview_front_card) ? Storage::url($design->preview_front_card) : '#' }}" 
                                 alt="Front Preview" 
                                 class="img-thumbnail" 
                                 style="max-height: 200px; max-width: 100%; object-fit: contain;">
                        </div>
                    </div>

                    <!-- Back Card Preview -->
                    <div class="mb-4">
                        <label for="preview_back_card" class="form-label fw-semibold text-dark mb-2">
                            <i class="bi bi-card-heading text-primary me-2"></i>
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
                            Image will be used as a design card preview (Max: 2MB)
                        </div>

                        <!-- Back Image Preview Container -->
                        <div class="mt-3" id="backPreviewContainer" style="display: {{ $design->preview_back_card && Storage::disk('public')->exists($design->preview_back_card) ? 'block' : 'none' }};">
                            <p class="small text-muted mb-2">Back Card Preview Image:</p>
                            <img id="backPreview" 
                                 src="{{ $design->preview_back_card && Storage::disk('public')->exists($design->preview_back_card) ? Storage::url($design->preview_back_card) : '#' }}" 
                                 alt="Back Preview" 
                                 class="img-thumbnail" 
                                 style="max-height: 200px; max-width: 100%; object-fit: contain;">
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex gap-3 justify-content-end">
                        <a href="{{ route('setting.id_design.index') }}" class="btn btn-outline-secondary px-4">
                            <i class="bi bi-x-circle me-2"></i>Cancel
                        </a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-check-circle me-2"></i>Update Design
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
        const sourceKeep = document.getElementById('source_keep');
        const sourcePreloaded = document.getElementById('source_preloaded');
        const sourceUpload = document.getElementById('source_upload');
        const preloadedContainer = document.getElementById('preloaded_template_container');
        const uploadContainer = document.getElementById('upload_file_container');
        const designFileInput = document.getElementById('design_file');
        const preloadedTemplateSelect = document.getElementById('preloaded_template');

        function toggleTemplateSource() {
            if (sourceKeep.checked) {
                preloadedContainer.style.display = 'none';
                uploadContainer.style.display = 'none';
                designFileInput.removeAttribute('required');
                preloadedTemplateSelect.removeAttribute('required');
            } else if (sourcePreloaded.checked) {
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

        sourceKeep.addEventListener('change', toggleTemplateSource);
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
            } else if (sourcePreloaded.checked) {
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
@endsection
