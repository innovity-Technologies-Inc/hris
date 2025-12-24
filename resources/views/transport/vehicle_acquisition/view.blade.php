@extends('structure.master')

@section('content')
    <div class="container-fluid px-4 py-4">
        <!-- Page Header -->
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h3 class="mb-2 fw-bold">
                            <i data-feather="truck" style="width: 32px; height: 32px;"></i>
                            Vehicle Details
                        </h3>
                        <p class="text-muted mb-0">Complete information about {{ $vehicleAcquisition->model_number }}</p>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <a href="{{ route('transport.vehicle_acquisitions.index') }}" class="btn btn-secondary me-2">
                            <i data-feather="arrow-left" style="width: 16px; height: 16px;"></i> Back
                        </a>
                        <a href="{{ route('transport.vehicle_acquisitions.edit', $vehicleAcquisition->id) }}"
                            class="btn btn-primary">
                            <i data-feather="edit" style="width: 16px; height: 16px;"></i> Edit
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Vehicle Image Section -->
            <div class="col-lg-4 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-0">
                        @if ($vehicleAcquisition->vehicle_image)
                            <img src="{{ asset('storage/' . $vehicleAcquisition->vehicle_image) }}"
                                alt="{{ $vehicleAcquisition->model_number }}" class="w-100 rounded-top vehicle-image-thumb"
                                style="height: 350px; object-fit: contain; background: var(--bs-body-bg); cursor: pointer;"
                                data-image-url="{{ asset('storage/' . $vehicleAcquisition->vehicle_image) }}">
                        @else
                            <div class="text-center p-5 bg-light rounded-top"
                                style="height: 350px; display: flex; align-items: center; justify-content: center;">
                                <div>
                                    <i data-feather="image" style="width: 80px; height: 80px;" class="text-muted"></i>
                                    <p class="text-muted mt-3 mb-0">No Image Available</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="card shadow-sm border-0 mt-4">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0">
                            <i data-feather="zap" style="width: 18px; height: 18px;"></i> Quick Stats
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row text-center g-3">
                            <div class="col-6">
                                <div class="p-3 bg-light rounded">
                                    <i data-feather="calendar" class="text-primary mb-2"
                                        style="width: 24px; height: 24px;"></i>
                                    <h5 class="mb-0 fw-bold">{{ $vehicleAcquisition->manufacture_year }}</h5>
                                    <small class="text-muted">Year</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 bg-light rounded">
                                    <i data-feather="droplet" class="text-success mb-2"
                                        style="width: 24px; height: 24px;"></i>
                                    <h5 class="mb-0 fw-bold">{{ $vehicleAcquisition->fuel_type }}</h5>
                                    <small class="text-muted">Fuel</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 bg-light rounded">
                                    <i data-feather="users" class="text-warning mb-2"
                                        style="width: 24px; height: 24px;"></i>
                                    <h5 class="mb-0 fw-bold">{{ $vehicleAcquisition->seating_capacity ?? 'N/A' }}</h5>
                                    <small class="text-muted">Seats</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3 bg-light rounded">
                                    <i data-feather="activity" class="text-info mb-2"
                                        style="width: 24px; height: 24px;"></i>
                                    <h5 class="mb-0 fw-bold">{{ $vehicleAcquisition->mileage ?? 'N/A' }}</h5>
                                    <small class="text-muted">KM/L</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Vehicle Information Section -->
            <div class="col-lg-8">
                <!-- Summary Cards -->
                <div class="row g-3 mb-4">
                    <!-- Category Card -->
                    <div class="col-md-4">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body text-center">
                                <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3"
                                    style="width: 60px; height: 60px;">
                                    <i data-feather="tag" class="text-primary" style="width: 28px; height: 28px;"></i>
                                </div>
                                <h6 class="text-muted mb-2 small text-uppercase">Category</h6>
                                <h5 class="fw-bold mb-0">
                                    <span class="badge bg-primary">{{ $vehicleAcquisition->vehicle_category }}</span>
                                </h5>
                            </div>
                        </div>
                    </div>

                    <!-- Model Card -->
                    <div class="col-md-4">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body text-center">
                                <div class="rounded-circle bg-success bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3"
                                    style="width: 60px; height: 60px;">
                                    <i data-feather="truck" class="text-success" style="width: 28px; height: 28px;"></i>
                                </div>
                                <h6 class="text-muted mb-2 small text-uppercase">Model</h6>
                                <h5 class="fw-bold mb-0">{{ $vehicleAcquisition->model_number }}</h5>
                            </div>
                        </div>
                    </div>

                    <!-- Status Card -->
                    <div class="col-md-4">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body text-center">
                                <div class="rounded-circle bg-info bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3"
                                    style="width: 60px; height: 60px;">
                                    @if ($vehicleAcquisition->status == 'Active')
                                        <i data-feather="check-circle" class="text-success"
                                            style="width: 28px; height: 28px;"></i>
                                    @else
                                        <i data-feather="x-circle" class="text-danger"
                                            style="width: 28px; height: 28px;"></i>
                                    @endif
                                </div>
                                <h6 class="text-muted mb-2 small text-uppercase">Status</h6>
                                @if ($vehicleAcquisition->status == 'Active')
                                    <span class="badge bg-success fs-6">Active</span>
                                @else
                                    <span class="badge bg-danger fs-6">Inactive</span>
                                @endif
                                <br><small class="text-muted">{{ $vehicleAcquisition->ownership_type }}</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detailed Information Cards -->
                <div class="row g-3">
                    <!-- Basic Information -->
                    <div class="col-lg-6">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-header bg-primary text-white">
                                <h6 class="mb-0">
                                    <i data-feather="info" style="width: 18px; height: 18px;"></i>
                                    Basic Information
                                </h6>
                            </div>
                            <div class="card-body">
                                <table class="table table-sm mb-0">
                                    <tbody>
                                        <tr>
                                            <td class="text-muted border-0 py-2">
                                                <i data-feather="box" class="me-2"
                                                    style="width: 16px; height: 16px;"></i>
                                                Body Type
                                            </td>
                                            <td class="fw-semibold border-0 py-2">
                                                {{ $vehicleAcquisition->body_type ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted border-0 py-2">
                                                <i data-feather="cpu" class="me-2"
                                                    style="width: 16px; height: 16px;"></i>
                                                Engine Capacity
                                            </td>
                                            <td class="fw-semibold border-0 py-2">
                                                {{ $vehicleAcquisition->engine_capacity ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted border-0 py-2">
                                                <i data-feather="circle" class="me-2"
                                                    style="width: 16px; height: 16px;"></i>
                                                Color
                                            </td>
                                            <td class="fw-semibold border-0 py-2">
                                                <span
                                                    class="badge bg-secondary">{{ $vehicleAcquisition->color ?? 'N/A' }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted border-0 py-2">
                                                <i data-feather="hash" class="me-2"
                                                    style="width: 16px; height: 16px;"></i>
                                                License Number
                                            </td>
                                            <td class="fw-semibold border-0 py-2">
                                                {{ $vehicleAcquisition->license_number ?? 'N/A' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Purchase Information -->
                    <div class="col-lg-6">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-header bg-success text-white">
                                <h6 class="mb-0">
                                    <i data-feather="shopping-bag" style="width: 18px; height: 18px;"></i>
                                    Purchase Information
                                </h6>
                            </div>
                            <div class="card-body">
                                <table class="table table-sm mb-0">
                                    <tbody>
                                        <tr>
                                            <td class="text-muted border-0 py-2">
                                                <i data-feather="dollar-sign" class="me-2"
                                                    style="width: 16px; height: 16px;"></i>
                                                Purchase Price
                                            </td>
                                            <td class="fw-semibold border-0 py-2">
                                                @if ($vehicleAcquisition->purchase_price)
                                                    <span class="text-success fw-bold">
                                                        {{ \App\HelperClass::getGeneralSetting()->currency ?? 'Tk' }}
                                                        {{ number_format($vehicleAcquisition->purchase_price, 2) }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">N/A</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted border-0 py-2">
                                                <i data-feather="calendar" class="me-2"
                                                    style="width: 16px; height: 16px;"></i>
                                                Purchase Date
                                            </td>
                                            <td class="fw-semibold border-0 py-2">
                                                {{ $vehicleAcquisition->purchase_date ? \Carbon\Carbon::parse($vehicleAcquisition->purchase_date)->format('d M Y') : 'N/A' }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted border-0 py-2">
                                                <i data-feather="shopping-cart" class="me-2"
                                                    style="width: 16px; height: 16px;"></i>
                                                Purchase Type
                                            </td>
                                            <td class="fw-semibold border-0 py-2">
                                                <span
                                                    class="badge bg-info">{{ $vehicleAcquisition->purchase_type }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted border-0 py-2">
                                                <i data-feather="user-check" class="me-2"
                                                    style="width: 16px; height: 16px;"></i>
                                                Ownership
                                            </td>
                                            <td class="fw-semibold border-0 py-2">
                                                <span
                                                    class="badge bg-warning text-dark">{{ $vehicleAcquisition->ownership_type }}</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Documents -->
                    <div class="col-12">
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-secondary text-white">
                                <h6 class="mb-0">
                                    <i data-feather="file-text" style="width: 18px; height: 18px;"></i>
                                    Documents & Attachments
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="p-3 border rounded bg-light">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <div>
                                                    <i data-feather="paperclip" class="text-success me-2"
                                                        style="width: 20px; height: 20px;"></i>
                                                    <strong>License Document</strong>
                                                </div>
                                                @if ($vehicleAcquisition->license_document)
                                                    <a href="{{ asset('storage/' . $vehicleAcquisition->license_document) }}"
                                                        target="_blank" class="btn btn-sm btn-success">
                                                        <i data-feather="download" style="width: 14px; height: 14px;"></i>
                                                        Download
                                                    </a>
                                                @else
                                                    <span class="badge bg-secondary">Not Available</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-3 border rounded bg-light">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <div>
                                                    <i data-feather="file" class="text-primary me-2"
                                                        style="width: 20px; height: 20px;"></i>
                                                    <strong>Purchase Invoice</strong>
                                                </div>
                                                @if ($vehicleAcquisition->purchase_document)
                                                    <a href="{{ asset('storage/' . $vehicleAcquisition->purchase_document) }}"
                                                        target="_blank" class="btn btn-sm btn-primary">
                                                        <i data-feather="download" style="width: 14px; height: 14px;"></i>
                                                        Download
                                                    </a>
                                                @else
                                                    <span class="badge bg-secondary">Not Available</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Fullscreen Image Modal -->
    <div id="imageFullscreenModal" class="position-fixed w-100 h-100" 
        style="top: 0; left: 0; background: rgba(0, 0, 0, 0.9); display: none; z-index: 9999; align-items: center; justify-content: center;">
        <div class="position-relative w-100 h-100 d-flex align-items-center justify-content-center">
            <img id="fullscreenImage" src="" alt="Full Screen Image" 
                style="max-width: 90vw; max-height: 90vh; object-fit: contain;">
            <button id="closeFullscreen" class="position-absolute btn btn-light rounded-circle" 
                style="top: 20px; right: 20px; width: 40px; height: 40px; padding: 0; display: flex; align-items: center; justify-content: center; z-index: 10000;">
                <i data-feather="x" style="width: 24px; height: 24px;"></i>
            </button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const imageThumb = document.querySelector('.vehicle-image-thumb');
            const fullscreenModal = document.getElementById('imageFullscreenModal');
            const fullscreenImage = document.getElementById('fullscreenImage');
            const closeBtn = document.getElementById('closeFullscreen');

            // Open fullscreen when image is clicked
            if (imageThumb) {
                imageThumb.addEventListener('click', function() {
                    fullscreenImage.src = this.getAttribute('data-image-url');
                    fullscreenModal.style.display = 'flex';
                    document.body.style.overflow = 'hidden';
                    feather.replace();
                });
            }

            // Close fullscreen when close button is clicked
            if (closeBtn) {
                closeBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    fullscreenModal.style.display = 'none';
                    document.body.style.overflow = 'auto';
                });
            }

            // Close fullscreen when clicking outside the image
            fullscreenModal.addEventListener('click', function(e) {
                if (e.target === this) {
                    fullscreenModal.style.display = 'none';
                    document.body.style.overflow = 'auto';
                }
            });

            // Close on Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && fullscreenModal.style.display === 'flex') {
                    fullscreenModal.style.display = 'none';
                    document.body.style.overflow = 'auto';
                }
            });
        });
    </script>
@endsection
