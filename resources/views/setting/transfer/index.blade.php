@extends('structure.master')

@section('content')
    <div class="py-4" style="max-width: 1000px; margin: 0 auto;">
        <!-- Main Card -->
        <div class="card shadow-lg border-0 rounded-4">
            <!-- Form Body -->
            <div class="card-body p-4 p-md-5">
                <form id="transferSettingForm">
                    <!-- Career Movement Configuration Section -->
                    <div class="mb-5">
                        <div class="d-flex align-items-center mb-4">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3 d-inline-flex align-items-center justify-content-center">
                                <i class="bi bi-shuffle text-primary fs-4"></i>
                            </div>
                            <h2 class="fs-4 fw-bold text-dark mb-0">Career Movement Level Configuration</h2>
                        </div>

                        <!-- Employee Request Level -->
                        <div class="card border shadow-sm mb-4">
                            <div class="card-body p-4">
                                <label for="employee_transfer_level" class="form-label fw-semibold text-dark mb-3 d-flex align-items-center">
                                    <i class="bi bi-person-badge text-primary me-2 fs-5"></i>
                                    <span>Employee Career Movement Request Level</span>
                                    <span class="badge bg-danger ms-2">Required</span>
                                </label>
                                <select name="employee_transfer_level" id="employee_transfer_level" class="form-select form-select-lg" required>
                                    <option value="company" {{ $setting->employee_transfer_level == 'company' ? 'selected' : '' }}>Company</option>
                                    <option value="business_unit" {{ $setting->employee_transfer_level == 'business_unit' ? 'selected' : '' }}>Business Unit / Branch</option>
                                    <option value="division" {{ $setting->employee_transfer_level == 'division' ? 'selected' : '' }}>Division</option>
                                    <option value="department" {{ $setting->employee_transfer_level == 'department' ? 'selected' : '' }}>Department</option>
                                    <option value="section" {{ $setting->employee_transfer_level == 'section' ? 'selected' : '' }}>Section</option>
                                </select>
                                <div class="form-text mt-2">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Defines the minimum organizational level an Employee can select in their transfer application.
                                </div>
                            </div>
                        </div>

                        <!-- Supervisor Request Level -->
                        <div class="card border shadow-sm">
                            <div class="card-body p-4">
                                <label for="supervisor_transfer_level" class="form-label fw-semibold text-dark mb-3 d-flex align-items-center">
                                    <i class="bi bi-person-check text-info me-2 fs-5"></i>
                                    <span>Supervisor Career Movement Request Level</span>
                                    <span class="badge bg-danger ms-2">Required</span>
                                </label>
                                <select name="supervisor_transfer_level" id="supervisor_transfer_level" class="form-select form-select-lg" required>
                                    <option value="company" {{ $setting->supervisor_transfer_level == 'company' ? 'selected' : '' }}>Company</option>
                                    <option value="business_unit" {{ $setting->supervisor_transfer_level == 'business_unit' ? 'selected' : '' }}>Business Unit / Branch</option>
                                    <option value="division" {{ $setting->supervisor_transfer_level == 'division' ? 'selected' : '' }}>Division</option>
                                    <option value="department" {{ $setting->supervisor_transfer_level == 'department' ? 'selected' : '' }}>Department</option>
                                    <option value="section" {{ $setting->supervisor_transfer_level == 'section' ? 'selected' : '' }}>Section</option>
                                </select>
                                <div class="form-text mt-2">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Defines the minimum organizational level for Managers/Admins when creating transfers for others.
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-end gap-3">
                        <a href="{{ route('dashboard.index') }}" class="btn btn-lg btn-outline-secondary px-4 px-md-5 rounded-3">
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
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
document.getElementById('transferSettingForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const data = Object.fromEntries(formData.entries());

    // Show loading state
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';

    axios.post('{{ route('setting.transfer.update') }}', data)
        .then(res => {
            if (res.data.success) {
                Swal.fire({
                    title: 'Success!',
                    text: res.data.message,
                    icon: 'success',
                    confirmButtonColor: '#212529'
                });
            }
        })
        .catch(err => {
            Swal.fire('Error!', 'Failed to update settings.', 'error');
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        });
});

// Add input validation styling
document.querySelectorAll('select[required]').forEach(field => {
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
@endpush
