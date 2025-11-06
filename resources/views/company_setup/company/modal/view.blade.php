<!-- View Company Modal -->
<div class="modal fade" id="companyView{{ $item->id }}" tabindex="-1"
    aria-labelledby="companyViewLabel{{ $item->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="companyViewLabel{{ $item->id }}">
                    <i class="fas fa-building me-2"></i>Company Details
                    @if ($item->status == 'active')
                        <span class="badge bg-success ms-2">Active</span>
                    @else
                        <span class="badge bg-danger ms-2">Inactive</span>
                    @endif
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <!-- Company Logo & Basic Info -->
                    <div class="col-12">
                        <div class="card bg-light">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                    <img src="{{ asset('storage/' . $item->logo) }}" class="rounded me-3"
                                        style="width: 80px; height: 80px; object-fit: contain; background: white; padding: 8px; border: 1px solid #dee2e6;"
                                        alt="Company Logo">
                                    <div>
                                        <h6 class="card-title text-primary mb-1">
                                            <i class="fas fa-building me-2"></i>{{ $item->name }}
                                        </h6>
                                        <p class="text-muted mb-0">{{ $item->short_name }}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="mb-2">
                                            <strong>Company Type:</strong><br>
                                            <span class="text-muted">{{ $item->getCompanyType->name ?? 'N/A' }}</span>
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-2">
                                            <strong>Group:</strong><br>
                                            <span class="text-muted">{{ $item->getGroup->name ?? 'N/A' }}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Address Information -->
                    <div class="col-12">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-title text-primary mb-3">
                                    <i class="fas fa-map-marker-alt me-2"></i>Address Information
                                </h6>
                                <p class="mb-0">
                                    <strong>Address:</strong><br>
                                    <span class="text-muted">{{ $item->address ?? 'N/A' }}</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="col-12">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-title text-primary mb-3">
                                    <i class="fas fa-phone me-2"></i>Contact Information
                                </h6>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <p class="mb-0">
                                            <strong>Email:</strong><br>
                                            <span class="text-muted">
                                                <i class="fas fa-envelope me-1"></i>{{ $item->email ?? 'N/A' }}
                                            </span>
                                        </p>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <p class="mb-0">
                                            <strong>Telephone:</strong><br>
                                            <span class="text-muted">
                                                <i class="fas fa-phone me-1"></i>{{ $item->telephone ?? 'N/A' }}
                                            </span>
                                        </p>
                                    </div>
                                    <div class="col-md-4">
                                        <p class="mb-0">
                                            <strong>Fax:</strong><br>
                                            <span class="text-muted">
                                                <i class="fas fa-fax me-1"></i>{{ $item->fax ?? 'N/A' }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Close
                </button>
                <a href="{{ route('companies.edit', $item->id) }}" class="btn btn-primary">
                    <i class="fas fa-edit me-1"></i>Edit Company
                </a>
            </div>
        </div>
    </div>
</div>
