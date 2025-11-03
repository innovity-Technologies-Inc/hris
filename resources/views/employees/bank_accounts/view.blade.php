@extends('structure.master')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0 text-dark">
                        <i class="fas fa-wallet text-primary"></i> Bank Account Details
                    </h5>
                    <div>
                        <a href="{{ route('employee-bank-accounts.edit', $employeeBankAccount->id) }}"
                           class="btn btn-primary btn-sm me-1">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('employee-bank-accounts.index') }}"
                           class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>

                <div class="card-body p-4">
                    <!-- Employee Details -->
                    <div class="mb-4">
                        <h6 class="text-uppercase text-muted mb-3 fw-bold">
                            <i class="fas fa-user-tie text-info"></i> Employee Information
                        </h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="icon-circle bg-light-info me-3">
                                        <i class="fas fa-user text-info"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Employee Name</small>
                                        <strong>{{ $employeeBankAccount->getEmployee->full_name ?? 'N/A' }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Bank Details -->
                    <div class="mb-4">
                        <h6 class="text-uppercase text-muted mb-3 fw-bold">
                            <i class="fas fa-building text-success"></i> Bank Information
                        </h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="icon-circle bg-light-success me-3">
                                        <i class="fas fa-university text-success"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Bank Name</small>
                                        <strong>{{ $employeeBankAccount->getBank->name ?? 'N/A' }}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="icon-circle bg-light-success me-3">
                                        <i class="fas fa-map-marker-alt text-success"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Branch Name</small>
                                        <strong>{{ $employeeBankAccount->getBranch->name ?? 'N/A' }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Account Details -->
                    <div class="mb-4">
                        <h6 class="text-uppercase text-muted mb-3 fw-bold">
                            <i class="fas fa-credit-card text-primary"></i> Account Information
                        </h6>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="icon-circle bg-light-primary me-3">
                                        <i class="fas fa-id-card text-primary"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Account Holder</small>
                                        <strong>{{ $employeeBankAccount->account_holder_name }}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="icon-circle bg-light-primary me-3">
                                        <i class="fas fa-hashtag text-primary"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Account Number</small>
                                        <strong class="text-monospace">{{ $employeeBankAccount->account_number }}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="icon-circle me-3 {{ $employeeBankAccount->status == 'active' ? 'bg-light-success' : 'bg-light-danger' }}">
                                        <i class="fas {{ $employeeBankAccount->status == 'active' ? 'fa-check-circle text-success' : 'fa-times-circle text-danger' }}"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Status</small>
                                        <span class="badge {{ $employeeBankAccount->status == 'active' ? 'bg-success' : 'bg-danger' }}">
                                            {{ ucfirst($employeeBankAccount->status) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($employeeBankAccount->remarks)
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="alert alert-light border-start border-4 border-info" role="alert">
                                    <div class="d-flex">
                                        <div class="me-3">
                                            <i class="fas fa-comment-dots text-info"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block mb-1">Remarks</small>
                                            <p class="mb-0">{{ $employeeBankAccount->remarks }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>

                    <hr class="my-4">

                    <!-- System Info -->
                    <div class="mb-2">
                        <h6 class="text-uppercase text-muted mb-3 fw-bold">
                            <i class="fas fa-info-circle text-secondary"></i> System Information
                        </h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="icon-circle bg-light me-3">
                                        <i class="fas fa-user-edit text-secondary"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Last Updated By</small>
                                        <strong>{{ $employeeBankAccount->updater->name ?? 'System' }}</strong>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="icon-circle bg-light me-3">
                                        <i class="fas fa-clock text-secondary"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Last Updated</small>
                                        <strong>{{ $employeeBankAccount->updated_at->format('d M Y, h:i A') }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-light py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="fas fa-trash-alt"></i> Delete
                        </button>
                        <a href="{{ route('employee-bank-accounts.edit', $employeeBankAccount->id) }}"
                           class="btn btn-primary">
                            <i class="fas fa-edit"></i> Edit Details
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title">
                        <i class="fas fa-exclamation-triangle text-warning"></i> Confirm Delete
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this bank account?</p>
                    <p class="text-muted small mb-0">This action cannot be undone.</p>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="#" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash-alt"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        .icon-circle {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }
        .bg-light-primary {
            background-color: rgba(13, 110, 253, 0.1);
        }
        .bg-light-success {
            background-color: rgba(25, 135, 84, 0.1);
        }
        .bg-light-info {
            background-color: rgba(13, 202, 240, 0.1);
        }
        .bg-light-danger {
            background-color: rgba(220, 53, 69, 0.1);
        }
        .text-monospace {
            font-family: 'Courier New', monospace;
            font-weight: 600;
        }
        .shadow-sm {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
        }
    </style>
@endsection
