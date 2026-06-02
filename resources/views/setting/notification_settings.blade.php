@extends('structure.master')

@section('content')
    <div class="py-4" style="max-width: 1000px; margin: 0 auto;">
        <!-- Main Card -->
        <div class="card shadow-lg border-0 rounded-4">

            <!-- Form Body -->
            <div class="card-body p-4 p-md-5">
                <div class="d-flex align-items-center mb-4">
                    <div class="bg-info bg-opacity-10 rounded-circle p-3 me-3 d-inline-flex align-items-center justify-content-center">
                        <i class="bi bi-bell-fill text-info fs-4"></i>
                    </div>
                    <h2 class="fs-4 fw-bold text-dark mb-0">Notification Alert Settings</h2>
                </div>

                <p class="text-muted mb-5">Configure how many days in advance alerts should be sent for various employee milestones and document expiries.</p>

                <form id="notificationSettingsForm">
                    @csrf

                    <div class="row g-4">
                        <!-- Birthday Alerts -->
                        <div class="col-md-6">
                            <div class="card border shadow-sm h-100">
                                <div class="card-body p-4">
                                    <label for="birthday_days" class="form-label fw-semibold text-dark mb-2 d-flex align-items-center">
                                        <i class="bi bi-cake2 text-danger me-2 fs-5"></i>
                                        <span>Birthday Alert (Days)</span>
                                    </label>
                                    <input type="number" class="form-control form-control-lg" id="birthday_days"
                                           name="birthday_days" placeholder="0"
                                           value="{{ $settings->birthday_days }}" min="0" required>
                                    <div class="form-text mt-2 small">Alert sent to HR/Admin.</div>
                                </div>
                            </div>
                        </div>

                        <!-- Visa Expiry Alerts -->
                        <div class="col-md-6">
                            <div class="card border shadow-sm h-100">
                                <div class="card-body p-4">
                                    <label for="visa_days" class="form-label fw-semibold text-dark mb-2 d-flex align-items-center">
                                        <i class="bi bi-passport text-primary me-2 fs-5"></i>
                                        <span>Visa Expiry Alert (Days)</span>
                                    </label>
                                    <input type="number" class="form-control form-control-lg" id="visa_days"
                                           name="visa_days" placeholder="0"
                                           value="{{ $settings->visa_days }}" min="0" required>
                                    <div class="form-text mt-2 small">Alert sent to Employee & HR/Admin.</div>
                                </div>
                            </div>
                        </div>

                        <!-- Work Permit Alerts -->
                        <div class="col-md-6">
                            <div class="card border shadow-sm h-100">
                                <div class="card-body p-4">
                                    <label for="work_permit_days" class="form-label fw-semibold text-dark mb-2 d-flex align-items-center">
                                        <i class="bi bi-briefcase text-success me-2 fs-5"></i>
                                        <span>Work Permit Alert (Days)</span>
                                    </label>
                                    <input type="number" class="form-control form-control-lg" id="work_permit_days"
                                           name="work_permit_days" placeholder="0"
                                           value="{{ $settings->work_permit_days }}" min="0" required>
                                    <div class="form-text mt-2 small">Alert sent to Employee & HR/Admin.</div>
                                </div>
                            </div>
                        </div>

                        <!-- Passport Alerts -->
                        <div class="col-md-6">
                            <div class="card border shadow-sm h-100">
                                <div class="card-body p-4">
                                    <label for="passport_days" class="form-label fw-semibold text-dark mb-2 d-flex align-items-center">
                                        <i class="bi bi-book text-warning me-2 fs-5"></i>
                                        <span>Passport Expiry Alert (Days)</span>
                                    </label>
                                    <input type="number" class="form-control form-control-lg" id="passport_days"
                                           name="passport_days" placeholder="0"
                                           value="{{ $settings->passport_days }}" min="0" required>
                                    <div class="form-text mt-2 small">Alert sent to Employee & HR/Admin.</div>
                                </div>
                            </div>
                        </div>

                        <!-- License Alerts -->
                        <div class="col-md-6">
                            <div class="card border shadow-sm h-100">
                                <div class="card-body p-4">
                                    <label for="license_days" class="form-label fw-semibold text-dark mb-2 d-flex align-items-center">
                                        <i class="bi bi-card-checklist text-info me-2 fs-5"></i>
                                        <span>License Expiry Alert (Days)</span>
                                    </label>
                                    <input type="number" class="form-control form-control-lg" id="license_days"
                                           name="license_days" placeholder="0"
                                           value="{{ $settings->license_days }}" min="0" required>
                                    <div class="form-text mt-2 small">Alert sent to Employee & HR/Admin.</div>
                                </div>
                            </div>
                        </div>

                        <!-- Probation Alerts -->
                        <div class="col-md-6">
                            <div class="card border shadow-sm h-100">
                                <div class="card-body p-4">
                                    <label for="probation_days" class="form-label fw-semibold text-dark mb-2 d-flex align-items-center">
                                        <i class="bi bi-person-check text-secondary me-2 fs-5"></i>
                                        <span>Probation End Alert (Days)</span>
                                    </label>
                                    <input type="number" class="form-control form-control-lg" id="probation_days"
                                           name="probation_days" placeholder="0"
                                           value="{{ $settings->probation_days }}" min="0" required>
                                    <div class="form-text mt-2 small">Alert sent to Employee & HR/Admin.</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-5 text-end">
                        <button type="submit" class="btn btn-primary btn-lg px-5 rounded-pill shadow-sm" id="btnSaveSettings">
                            <i class="bi bi-save me-2"></i> Save Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('notificationSettingsForm');
    const btnSave = document.getElementById('btnSaveSettings');

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        // Loading state
        btnSave.disabled = true;
        btnSave.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span> Saving...';

        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());

        axios.post("{{ route('setting.notification_settings.store') }}", data)
            .then(response => {
                if (response.data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.data.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    throw new Error(response.data.message || 'Failed to save settings');
                }
            })
            .catch(error => {
                console.error(error);
                const msg = error.response?.data?.message || error.message || 'An error occurred while saving settings.';
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: msg
                });
            })
            .finally(() => {
                btnSave.disabled = false;
                btnSave.innerHTML = '<i class="bi bi-save me-2"></i> Save Settings';
            });
    });
});
</script>
@endpush
            </div>
        </div>
    </div>
@endsection
