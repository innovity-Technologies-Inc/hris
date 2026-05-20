<!-- Edit Login Information Modal -->
<div class="modal fade" id="editLoginInfoModal" tabindex="-1" aria-labelledby="editLoginInfoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <!-- Using the card directly as the modal content -->
        <div class="modal-content card shadow-sm border-0 mb-0" style="border-radius: 15px; overflow: hidden;">
            <form action="{{ route('employee.update_login_info', $employee->id) }}" method="POST">
                @csrf
                <div class="card-header text-white py-3 d-flex justify-content-between align-items-center" style="background-color: #974063;">
                    <h5 class="card-title mb-0 text-white"><i class="fas fa-user-shield me-2"></i>Edit Login Information</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="card-body p-4">
                    @include('employee.partials.modal.login_info_form', ['employee' => $employee, 'mode' => 'edit'])
                </div>
                <div class="card-footer bg-light py-3">
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn px-4 fw-bold text-white" style="background-color: #974063;">
                            <i class="fas fa-save me-1"></i> Update Account Credentials
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

