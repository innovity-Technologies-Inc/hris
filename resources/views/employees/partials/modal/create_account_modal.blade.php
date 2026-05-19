<!-- Create Employee Account Modal -->
<div class="modal fade" id="createAccountModal" tabindex="-1" aria-labelledby="createAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content card shadow-sm border-0 mb-0" style="border-radius: 15px; overflow: hidden;">
            <form id="createAccountForm" action="{{ route('employees.store_account') }}" method="POST">
                @csrf
                <div class="card-header text-white py-3 d-flex justify-content-between align-items-center" style="background-color: #974063;">
                    <h5 class="card-title mb-0 text-white"><i class="fas fa-user-shield me-2"></i>Create Employee Account</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="card-body p-4">
                    @include('employees.partials.modal.login_info_form', ['employee' => null, 'mode' => 'create'])
                </div>
                <div class="card-footer bg-light py-3">
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn px-4 fw-bold text-white" style="background-color: #974063;">
                            <i class="fas fa-user-plus me-1"></i> Create Account
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .hr-divider {
        display: flex;
        align-items: center;
        text-align: center;
    }
    .hr-divider::before, .hr-divider::after {
        content: '';
        flex: 1;
        border-bottom: 1px solid #e9ecef;
    }
    .hr-divider:not(:empty)::before {
        margin-right: .5em;
    }
    .hr-divider:not(:empty)::after {
        margin-left: .5em;
    }
</style>

@push('scripts')
{{-- Global password toggle in master.blade.php handles the eye icon --}}
@endpush
