@extends('structure.master')

@section('content')
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow-sm border">
                <div class="card-header bg-light py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-semibold text-dark">
                        <i class="bi bi-pencil-square me-2 text-primary"></i>Edit CV Record
                    </h5>
                    <a href="{{ route('cv_bank.index') }}" class="btn btn-secondary btn-sm">
                        <i class="bi bi-arrow-left me-1"></i> Back to Bank
                    </a>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('cv_bank.update', $cv->id) }}" method="POST" id="editCvForm" enctype="multipart/form-data">
                        @csrf

                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label small fw-semibold text-muted mb-1">Applicant Name *</label>
                                <input type="text" name="applicant_name" value="{{ old('applicant_name', $cv->applicant_name) }}" class="form-control" required placeholder="e.g. John Doe">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-muted mb-1">Company Name *</label>
                                <select name="company_name" class="form-select" required>
                                    <option value="">Select Company</option>
                                    @foreach($companies as $company)
                                        <option value="{{ $company->name }}" {{ old('company_name', $cv->company_name) === $company->name ? 'selected' : '' }}>{{ $company->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-muted mb-1">Designation *</label>
                                <select name="designation" class="form-select" required>
                                    <option value="">Select Designation</option>
                                    @foreach($designations as $designation)
                                        <option value="{{ $designation->company_designation }}" {{ old('designation', $cv->designation) === $designation->company_designation ? 'selected' : '' }}>{{ $designation->company_designation }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-8">
                                <label class="form-label small fw-semibold text-muted mb-1">Career Level *</label>
                                <select name="career_level" class="form-select" required>
                                    <option value="">Select Level</option>
                                    <option value="Entry" {{ old('career_level', $cv->career_level) === 'Entry' ? 'selected' : '' }}>Entry</option>
                                    <option value="Mid" {{ old('career_level', $cv->career_level) === 'Mid' ? 'selected' : '' }}>Mid</option>
                                    <option value="Senior" {{ old('career_level', $cv->career_level) === 'Senior' ? 'selected' : '' }}>Senior</option>
                                    <option value="Executive" {{ old('career_level', $cv->career_level) === 'Executive' ? 'selected' : '' }}>Executive</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-semibold text-muted mb-1">CV Score (0-100) *</label>
                                <input type="number" name="cv_score" min="0" max="100" value="{{ old('cv_score', $cv->cv_score) }}" class="form-control" required placeholder="e.g. 85">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label small fw-semibold text-muted mb-1">Upload New CV File (replaces old file)</label>
                                <input type="file" name="attachment" class="form-control mb-2" accept=".pdf,.doc,.docx">
                                @if($cv->attachment_path)
                                    <div class="mt-2">
                                        <span class="text-muted small">Current Attachment: </span>
                                        <a href="{{ asset('storage/' . $cv->attachment_path) }}" target="_blank" class="badge bg-light text-primary border">
                                            <i class="bi bi-file-earmark-pdf me-1"></i>View File
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Submit Section -->
                        <div class="text-end border-top pt-3 mt-4">
                            <button type="submit" class="btn btn-primary px-4" id="submitBtn">
                                <i class="bi bi-check-circle me-1"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('editCvForm');

    // Axios Form Submit Intercept
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        // Clear existing errors
        document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        document.querySelectorAll('.invalid-feedback').forEach(el => el.remove());

        const formData = new FormData(form);
        const submitBtn = document.getElementById('submitBtn');
        const originalBtnHtml = submitBtn.innerHTML;

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Saving...';

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
                    title: 'Updated Successfully',
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
                    const input = form.querySelector(`[name="${key}"]`);
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
                    text: 'Please correct the highlighted errors.'
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
