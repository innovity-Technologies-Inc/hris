@extends('structure.master')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border">
                <div class="card-header bg-light py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-semibold text-dark">
                        <i class="bi bi-file-earmark-plus me-2 text-primary"></i>Employee CV Intake Form
                    </h5>
                    <a href="{{ route('cv_bank.index') }}" class="btn btn-secondary btn-sm">
                        <i class="bi bi-arrow-left me-1"></i> Back to Bank
                    </a>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('cv_bank.store') }}" method="POST" id="cvBankForm" enctype="multipart/form-data">
                        @csrf

                        <!-- CV Entries Container -->
                        <div id="cvEntriesContainer">
                            <!-- Card Template / First Entry -->
                            <div class="cv-entry-card mb-4 border rounded p-3 shadow-sm bg-white" data-index="0">
                                <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                                    <span class="badge bg-primary px-3 py-1">CV Entry #1</span>
                                    <button type="button" class="btn btn-sm btn-outline-danger remove-entry-btn d-none">
                                        <i class="bi bi-x-circle me-1"></i> Remove
                                    </button>
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-4 col-sm-6">
                                        <label class="form-label small fw-semibold text-muted mb-1">Applicant Name *</label>
                                        <input type="text" name="cvs[0][applicant_name]" class="form-control form-control-sm" required placeholder="e.g. John Doe">
                                    </div>
                                    <div class="col-md-4 col-sm-6">
                                        <label class="form-label small fw-semibold text-muted mb-1">Company Name *</label>
                                        <input type="text" name="cvs[0][company_name]" class="form-control form-control-sm" required placeholder="e.g. TechCorp">
                                    </div>
                                    <div class="col-md-4 col-sm-6">
                                        <label class="form-label small fw-semibold text-muted mb-1">Designation *</label>
                                        <input type="text" name="cvs[0][designation]" class="form-control form-control-sm" required placeholder="e.g. Software Engineer">
                                    </div>
                                    <div class="col-md-4 col-sm-6">
                                        <label class="form-label small fw-semibold text-muted mb-1">Career Level *</label>
                                        <select name="cvs[0][career_level]" class="form-select form-select-sm" required>
                                            <option value="">Select Level</option>
                                            <option value="Entry">Entry</option>
                                            <option value="Mid">Mid</option>
                                            <option value="Senior">Senior</option>
                                            <option value="Executive">Executive</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2 col-sm-6">
                                        <label class="form-label small fw-semibold text-muted mb-1">CV Score (0-100) *</label>
                                        <input type="number" name="cvs[0][cv_score]" min="0" max="100" class="form-control form-control-sm" required placeholder="e.g. 85">
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <label class="form-label small fw-semibold text-muted mb-1">CV PDF / Document</label>
                                        <input type="file" name="cvs[0][attachment]" class="form-control form-control-sm" accept=".pdf,.doc,.docx">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Add Button -->
                        <div class="mb-4">
                            <button type="button" id="addEntryBtn" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-plus-circle me-1"></i> Add Another CV
                            </button>
                        </div>

                        <!-- Submit Section -->
                        <div class="text-end border-top pt-3">
                            <button type="submit" class="btn btn-primary px-4" id="submitBtn">
                                <i class="bi bi-cloud-arrow-up me-1"></i> Upload & Save All
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Template for new entries (Hidden) -->
    <div id="cvEntryTemplate" class="d-none">
        <div class="cv-entry-card mb-4 border rounded p-3 shadow-sm bg-white" data-index="__INDEX__">
            <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                <span class="badge bg-primary px-3 py-1">CV Entry #__NUMBER__</span>
                <button type="button" class="btn btn-sm btn-outline-danger remove-entry-btn">
                    <i class="bi bi-x-circle me-1"></i> Remove
                </button>
            </div>

            <div class="row g-3">
                <div class="col-md-4 col-sm-6">
                    <label class="form-label small fw-semibold text-muted mb-1">Applicant Name *</label>
                    <input type="text" name="cvs[__INDEX__][applicant_name]" class="form-control form-control-sm" required placeholder="e.g. John Doe">
                </div>
                <div class="col-md-4 col-sm-6">
                    <label class="form-label small fw-semibold text-muted mb-1">Company Name *</label>
                    <input type="text" name="cvs[__INDEX__][company_name]" class="form-control form-control-sm" required placeholder="e.g. TechCorp">
                </div>
                <div class="col-md-4 col-sm-6">
                    <label class="form-label small fw-semibold text-muted mb-1">Designation *</label>
                    <input type="text" name="cvs[__INDEX__][designation]" class="form-control form-control-sm" required placeholder="e.g. Software Engineer">
                </div>
                <div class="col-md-4 col-sm-6">
                    <label class="form-label small fw-semibold text-muted mb-1">Career Level *</label>
                    <select name="cvs[__INDEX__][career_level]" class="form-select form-select-sm" required>
                        <option value="">Select Level</option>
                        <option value="Entry">Entry</option>
                        <option value="Mid">Mid</option>
                        <option value="Senior">Senior</option>
                        <option value="Executive">Executive</option>
                    </select>
                </div>
                <div class="col-md-2 col-sm-6">
                    <label class="form-label small fw-semibold text-muted mb-1">CV Score (0-100) *</label>
                    <input type="number" name="cvs[__INDEX__][cv_score]" min="0" max="100" class="form-control form-control-sm" required placeholder="e.g. 85">
                </div>
                <div class="col-md-6 col-12">
                    <label class="form-label small fw-semibold text-muted mb-1">CV PDF / Document</label>
                    <input type="file" name="cvs[__INDEX__][attachment]" class="form-control form-control-sm" accept=".pdf,.doc,.docx">
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('cvEntriesContainer');
    const addBtn = document.getElementById('addEntryBtn');
    const form = document.getElementById('cvBankForm');

    // Add Entry Action
    addBtn.addEventListener('click', function() {
        const cards = container.querySelectorAll('.cv-entry-card');
        const nextIndex = cards.length;
        
        let templateHtml = document.getElementById('cvEntryTemplate').innerHTML;
        templateHtml = templateHtml.replace(/__INDEX__/g, nextIndex).replace(/__NUMBER__/g, nextIndex + 1);
        
        container.insertAdjacentHTML('beforeend', templateHtml);
        updateRemoveButtons();
    });

    // Remove Entry Action
    container.addEventListener('click', function(e) {
        const removeBtn = e.target.closest('.remove-entry-btn');
        if (removeBtn) {
            const card = removeBtn.closest('.cv-entry-card');
            card.remove();
            reindexCards();
            updateRemoveButtons();
        }
    });

    function reindexCards() {
        const cards = container.querySelectorAll('.cv-entry-card');
        cards.forEach((card, index) => {
            card.setAttribute('data-index', index);
            card.querySelector('.badge').textContent = `CV Entry #${index + 1}`;
            
            card.querySelectorAll('[name]').forEach(input => {
                const name = input.getAttribute('name');
                const updatedName = name.replace(/cvs\[\d+\]/, `cvs[${index}]`);
                input.setAttribute('name', updatedName);
            });
        });
    }

    function updateRemoveButtons() {
        const cards = container.querySelectorAll('.cv-entry-card');
        cards.forEach((card, index) => {
            const removeBtn = card.querySelector('.remove-entry-btn');
            if (cards.length === 1) {
                removeBtn.classList.add('d-none');
            } else {
                removeBtn.classList.remove('d-none');
            }
        });
    }

    // Axios Multipart Form Submit Intercept
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        // Clear existing errors
        document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        document.querySelectorAll('.invalid-feedback').forEach(el => el.remove());

        const formData = new FormData(form);
        const submitBtn = document.getElementById('submitBtn');
        const originalBtnHtml = submitBtn.innerHTML;

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Submitting...';

        axios({
            method: 'post',
            url: form.getAttribute('action'),
            data: formData,
            headers: { 'Content-Type': 'multipart/form-data' }
        })
        .then(response => {
            if (response.data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Saved Successfully',
                    text: response.data.message,
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    window.location.href = "{{ route('cv_bank.index') }}";
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.data.message || 'Something went wrong.'
                });
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnHtml;
            }
        })
        .catch(error => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnHtml;

            if (error.response && error.response.status === 422) {
                const errors = error.response.data.errors;

                Object.keys(errors).forEach(key => {
                    // Convert dot syntax to name array syntax (e.g. cvs.0.company_name -> cvs[0][company_name])
                    let inputName = key;
                    if (key.includes('.')) {
                        const parts = key.split('.');
                        inputName = parts[0] + '[' + parts[1] + ']';
                        for (let i = 2; i < parts.length; i++) {
                            inputName += '[' + parts[i] + ']';
                        }
                    }

                    const input = form.querySelector(`[name="${inputName}"]`);
                    if (input) {
                        input.classList.add('is-invalid');
                        const feedback = document.createElement('div');
                        feedback.className = 'invalid-feedback';
                        feedback.textContent = errors[key][0];
                        input.after(feedback);
                    }
                });

                Swal.fire({
                    icon: 'error',
                    title: 'Validation Error',
                    text: 'Please check your inputs and try again.'
                });
            } else {
                const errMsg = error.response && error.response.data && error.response.data.message
                    ? error.response.data.message
                    : 'An unexpected error occurred. Please try again.';
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: errMsg
                });
            }
        });
    });
});
</script>
@endpush
