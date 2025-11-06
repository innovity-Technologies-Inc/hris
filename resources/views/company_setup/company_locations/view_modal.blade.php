<!-- View Location Modal -->
<div class="modal fade" id="viewLocationModal{{ $location->id }}" tabindex="-1"
    aria-labelledby="viewLocationModalLabel{{ $location->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewLocationModalLabel{{ $location->id }}">
                    <i class="fas fa-map-marker-alt me-2"></i>Company Location Details
                    @if ($location->status == 'active')
                        <span class="badge bg-success ms-2">Active</span>
                    @else
                        <span class="badge bg-danger ms-2">Inactive</span>
                    @endif
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <!-- Company Information -->
                    <div class="col-12">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-title text-primary mb-3">
                                    <i class="fas fa-building me-2"></i>Company Information
                                </h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="mb-2">
                                            <strong>Company Name:</strong><br>
                                            <span class="text-muted">{{ $location->getCompany->name ?? 'N/A' }}</span>
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-2">
                                            <strong>Unit Name:</strong><br>
                                            <span class="text-muted">{{ $location->unit_name ?? 'N/A' }}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Location Address -->
                    <div class="col-12">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-title text-primary mb-3">
                                    <i class="fas fa-location-arrow me-2"></i>Address Information
                                </h6>
                                <p class="mb-2">
                                    <strong>Location Address:</strong><br>
                                    <span class="text-muted">{{ $location->location_address ?? 'N/A' }}</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Geographic Information -->
                    <div class="col-12">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-title text-primary mb-3">
                                    <i class="fas fa-globe me-2"></i>Geographic Details
                                </h6>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <p class="mb-0">
                                            <strong>State:</strong><br>
                                            <span class="text-muted">{{ $location->state ?? 'N/A' }}</span>
                                        </p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <p class="mb-0">
                                            <strong>Division:</strong><br>
                                            <span class="text-muted">{{ $location->division ?? 'N/A' }}</span>
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-0">
                                            <strong>City:</strong><br>
                                            <span class="text-muted">{{ $location->city ?? 'N/A' }}</span>
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-0">
                                            <strong>Country:</strong><br>
                                            <span class="text-muted">{{ $location->country ?? 'N/A' }}</span>
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
                <a href="{{ route('company_locations.edit', $location->id) }}" class="btn btn-primary">
                    <i class="fas fa-edit me-1"></i>Edit Location
                </a>
            </div>
        </div>
    </div>
</div>
