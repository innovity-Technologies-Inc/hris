<!-- NID Verification Modal -->
<div class="modal fade" id="nidVerificationModal" tabindex="-1" aria-labelledby="nidVerificationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-info text-white border-0">
                <h5 class="modal-title d-flex align-items-center" id="nidVerificationModalLabel">
                    <i class="mdi mdi-card-account-details-outline me-2 fs-22"></i> NID Verification
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="text-center mb-4">
                    <div class="avatar-lg mx-auto mb-3">
                        <div class="avatar-title bg-soft-info text-info rounded-circle fs-32">
                            <i class="mdi mdi-shield-check-outline"></i>
                        </div>
                    </div>
                    <h5 class="fw-bold">Verify National ID</h5>
                    <p class="text-muted">Please confirm the NID number below for <strong>{{ $employee->full_name }}</strong>.</p>
                </div>

                <div class="mb-3">
                    <label for="nid_display" class="form-label fw-semibold">National ID (NID) Number</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="mdi mdi-numeric text-muted"></i>
                        </span>
                        <input type="text" class="form-control bg-light border-start-0" id="nid_display" 
                               value="{{ $employee->nid ?? $employee->residency_id_number ?? 'Not Provided' }}" readonly>
                    </div>
                    @if(empty($employee->nid) && empty($employee->residency_id_number))
                        <div class="alert alert-warning mt-2 mb-0 p-2 d-flex align-items-center" role="alert">
                            <i class="mdi mdi-alert-circle me-2 fs-18"></i>
                            <div class="small">No NID found. Please update the profile first.</div>
                        </div>
                    @endif
                </div>

                <div id="verification_status" class="d-none">
                    <!-- Status message will be injected here -->
                </div>
            </div>
            <div class="modal-footer border-0 p-3 pt-0">
                <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-info text-white px-4 d-flex align-items-center" id="btnVerifyNID" 
                        {{ (empty($employee->nid) && empty($employee->residency_id_number)) || $employee->is_nid_verified ? 'disabled' : '' }}>
                    <span id="verify_spinner" class="spinner-border spinner-border-sm me-2 d-none" role="status"></span>
                    <i class="mdi mdi-check-circle-outline me-1 fs-16" id="verify_icon"></i>
                    {{ $employee->is_nid_verified ? 'Verified' : 'Verify NID' }}
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnVerifyNID = document.getElementById('btnVerifyNID');
    if (btnVerifyNID) {
        btnVerifyNID.addEventListener('click', function() {
            const employeeId = '{{ $employee->id }}';
            const spinner = document.getElementById('verify_spinner');
            const icon = document.getElementById('verify_icon');

            // UI Loading State
            btnVerifyNID.disabled = true;
            spinner.classList.remove('d-none');
            icon.classList.add('d-none');

            axios.post(`{{ route('employee.profile.verify_nid', '') }}/${employeeId}`)
                .then(response => {
                    if (response.data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: response.data.message,
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        throw new Error(response.data.message || 'Verification failed');
                    }
                })
                .catch(error => {
                    console.error(error);
                    const msg = error.response?.data?.message || error.message || 'An error occurred during verification.';
                    Swal.fire({
                        icon: 'error',
                        title: 'Verification Failed',
                        text: msg
                    });
                    
                    // Reset UI
                    btnVerifyNID.disabled = false;
                    spinner.classList.add('d-none');
                    icon.classList.remove('d-none');
                });
        });
    }
});
</script>
@endpush
