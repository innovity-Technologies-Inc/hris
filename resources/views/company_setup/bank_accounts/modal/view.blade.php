{{-- Bank Account View Modal --}}
<div class="modal fade" id="bankAccountsView{{ $item->id }}" tabindex="-1"
    aria-labelledby="bankAccountViewLabel{{ $item->id }}" aria-modal="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            {{-- Modal Header --}}
            <div class="modal-header bg-primary text-white border-0">
                <h5 class="modal-title fw-semibold" id="bankAccountViewLabel{{ $item->id }}">
                    <i class="fa-solid fa-building-columns me-2"></i>Bank Account Information
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>

            {{-- Modal Body --}}
            <div class="modal-body p-3">
                <div class="row g-2">
                    {{-- Account Details Section --}}
                    <div class="col-12">
                        <div class="card border-0 bg-light">
                            <div class="card-body p-3">
                                <h6 class="card-title text-primary fw-bold mb-2 pb-1 border-bottom border-primary">
                                    <i class="fa-solid fa-credit-card me-2 text-primary"></i>Account Details
                                </h6>
                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <div class="d-flex align-items-start">
                                            <i class="fa-solid fa-hashtag text-primary me-2 mt-1" style="font-size: 14px;"></i>
                                            <span class="text-muted fw-medium" style="min-width: 120px;">Account
                                                No:</span>
                                            <span class="text-dark ms-2">{{ $item->account_no }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <div class="d-flex align-items-start">
                                            <i class="fa-solid fa-tag text-primary me-2 mt-1" style="font-size: 14px;"></i>
                                            <span class="text-muted fw-medium" style="min-width: 120px;">Account
                                                Type:</span>
                                            <span class="text-dark ms-2">{{ ucwords($item->account_type) }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <div class="d-flex align-items-start">
                                            <i class="fa-solid fa-user text-primary me-2 mt-1" style="font-size: 14px;"></i>
                                            <span class="text-muted fw-medium" style="min-width: 120px;">Holder
                                                Name:</span>
                                            <span class="text-dark ms-2">{{ $item->holder_name }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <div class="d-flex align-items-start">
                                            <i class="fa-solid fa-toggle-on text-primary me-2 mt-1"
                                                style="font-size: 14px;"></i>
                                            <span class="text-muted fw-medium" style="min-width: 120px;">Status:</span>
                                            <span class="ms-2">
                                                @if ($item->status == 'active')
                                                    <span class="badge bg-success px-2 py-1">
                                                        <i class="fa-solid fa-circle-check me-1"></i>Active
                                                    </span>
                                                @else
                                                    <span class="badge bg-danger px-2 py-1">
                                                        <i class="fa-solid fa-circle-xmark me-1"></i>Inactive
                                                    </span>
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Bank Details Section --}}
                    <div class="col-12">
                        <div class="card border-0 bg-light">
                            <div class="card-body p-3">
                                <h6 class="card-title text-primary fw-bold mb-2 pb-1 border-bottom border-primary">
                                    <i class="fa-solid fa-building me-2 text-primary"></i>Bank & Branch Details
                                </h6>
                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <div class="d-flex align-items-start">
                                            <i class="fa-solid fa-building-columns text-primary me-2 mt-1" style="font-size: 14px;"></i>
                                            <span class="text-muted fw-medium" style="min-width: 120px;">Bank
                                                Name:</span>
                                            <span class="text-dark ms-2">{{ $item->getBank->name }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <div class="d-flex align-items-start">
                                            <i class="fa-solid fa-location-dot text-primary me-2 mt-1"
                                                style="font-size: 14px;"></i>
                                            <span class="text-muted fw-medium" style="min-width: 120px;">Branch
                                                Name:</span>
                                            <span class="text-dark ms-2">{{ $item->getBranch->name }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <div class="d-flex align-items-start">
                                            <i class="fa-solid fa-signs-post text-primary me-2 mt-1"
                                                style="font-size: 14px;"></i>
                                            <span class="text-muted fw-medium" style="min-width: 120px;">Routing
                                                No:</span>
                                            <span class="text-dark ms-2">{{ $item->getBranch->routing_no }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Contact Information Section --}}
                    <div class="col-12">
                        <div class="card border-0 bg-light">
                            <div class="card-body p-3">
                                <h6 class="card-title text-primary fw-bold mb-2 pb-1 border-bottom border-primary">
                                    <i class="fa-solid fa-id-badge me-2 text-primary"></i>Contact Information
                                </h6>
                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <div class="d-flex align-items-start">
                                            <i class="fa-solid fa-id-card text-primary me-2 mt-1"
                                                style="font-size: 14px;"></i>
                                            <span class="text-muted fw-medium" style="min-width: 120px;">Contact
                                                Person:</span>
                                            <span class="text-dark ms-2">{{ $item->contact_person ?? 'N/A' }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <div class="d-flex align-items-start">
                                            <i class="fa-solid fa-phone text-primary me-2 mt-1"
                                                style="font-size: 14px;"></i>
                                            <span class="text-muted fw-medium" style="min-width: 120px;">Contact
                                                No:</span>
                                            <span
                                                class="text-dark ms-2">{{ $item->contact_person_no ?? 'N/A' }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <div class="d-flex align-items-start">
                                            <i class="fa-solid fa-envelope text-primary me-2 mt-1"
                                                style="font-size: 14px;"></i>
                                            <span class="text-muted fw-medium" style="min-width: 120px;">Email:</span>
                                            <span class="text-dark ms-2">{{ $item->email ?? 'N/A' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Modal Footer --}}
            <div class="modal-footer border-0 bg-light py-2">
                <button type="button" class="btn btn-secondary px-3 py-1" data-bs-dismiss="modal">
                    <i class="fa-solid fa-circle-xmark me-1"></i>Close
                </button>
            </div>
        </div>
    </div>
</div>
