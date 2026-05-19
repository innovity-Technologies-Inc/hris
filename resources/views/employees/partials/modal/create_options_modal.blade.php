<!-- Create Options Modal -->
<div class="modal fade" id="createOptionsModal" tabindex="-1" aria-labelledby="createOptionsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px; overflow: hidden;">
            <div class="modal-header text-white py-3" style="background-color: #974063;">
                <h5 class="modal-title fw-bold text-white" id="createOptionsModalLabel">
                    <i class="mdi mdi-plus-circle me-2"></i>Create New Record
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <p class="text-muted mb-4">Please select the type of record you want to create.</p>
                
                <div class="row g-3">
                    <div class="col-md-6">
                        <a href="{{ route('employees.general_informations.create') }}" class="card h-100 border-0 shadow-sm transition-hover text-decoration-none">
                            <div class="card-body p-4 d-flex flex-column align-items-center justify-content-center bg-light rounded-3">
                                <div class="avatar-lg rounded-circle mb-3 d-flex align-items-center justify-content-center" style="background-color: rgba(151, 64, 99, 0.1);">
                                    <i class="mdi mdi-account-plus fs-32" style="color: #974063;"></i>
                                </div>
                                <h6 class="fw-bold text-dark mb-1">Employee Profile</h6>
                                <p class="small text-muted mb-0">Full employee record</p>
                            </div>
                        </a>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card h-100 border-0 shadow-sm transition-hover cursor-pointer" id="selectEmployeeAccount">
                            <div class="card-body p-4 d-flex flex-column align-items-center justify-content-center bg-light rounded-3">
                                <div class="avatar-lg bg-soft-success rounded-circle mb-3 d-flex align-items-center justify-content-center">
                                    <i class="mdi mdi-account-key fs-32 text-success"></i>
                                </div>
                                <h6 class="fw-bold text-dark mb-1">Employee Account</h6>
                                <p class="small text-muted mb-0">Login credentials only</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .transition-hover {
        transition: all 0.3s ease;
    }
    .transition-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(151, 64, 99, 0.1) !important;
        background-color: #fff !important;
    }
    .bg-soft-primary {
        background-color: rgba(151, 64, 99, 0.1);
    }
    .bg-soft-success {
        background-color: rgba(40, 167, 69, 0.1);
    }
    .cursor-pointer {
        cursor: pointer;
    }
    .fs-32 {
        font-size: 32px;
    }
</style>

@push('scripts')
<script>
    $(document).ready(function() {
        $('#selectEmployeeAccount').on('click', function() {
            var createOptionsModal = bootstrap.Modal.getInstance(document.getElementById('createOptionsModal'));
            if (createOptionsModal) {
                createOptionsModal.hide();
                
                // Show the next modal after the first one is hidden
                $('#createOptionsModal').one('hidden.bs.modal', function() {
                    var modalEl = document.getElementById('createAccountModal');
                    var createAccountModal = bootstrap.Modal.getOrCreateInstance(modalEl);
                    createAccountModal.show();
                });
            } else {
                // Fallback if instance not found
                $('#createOptionsModal').modal('hide');
                setTimeout(function() {
                    $('#createAccountModal').modal('show');
                }, 400);
            }
        });
    });
</script>
@endpush
