@extends('structure.master')

@section('content')
    <div class="py-4">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fs-3 fw-bold text-dark mb-1">ID Card Designs</h2>
                <p class="text-muted mb-0">Manage your employee ID card templates</p>
            </div>
            <a href="{{ route('settings.id_design.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Create New Design
            </a>
        </div>

        <!-- Success/Error Messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('info'))
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <i class="bi bi-info-circle me-2"></i>{{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Active Design Notice -->
        @if ($activeDesign)
            <div class="alert alert-success border-0 shadow-sm mb-4">
                <div class="d-flex align-items-center">
                    <i class="bi bi-shield-check fs-2 me-3"></i>
                    <div>
                        <h6 class="mb-1 fw-bold">Currently Active Design</h6>
                        <p class="mb-0">{{ $activeDesign->theme_name }}</p>
                    </div>
                </div>
            </div>
        @else
            <div class="alert alert-warning border-0 shadow-sm mb-4">
                <div class="d-flex align-items-center">
                    <i class="bi bi-exclamation-triangle fs-2 me-3"></i>
                    <div>
                        <h6 class="mb-1 fw-bold">No Active Design</h6>
                        <p class="mb-0">Please activate a design to use for ID card generation</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Designs Grid -->
        @if ($designs->count() > 0)
            <div class="row g-4">
                @foreach ($designs as $design)
                    <div class="col-md-6 col-lg-4">
                        <div
                            class="card h-100 border-0 shadow-sm {{ $design->status === 'active' ? 'border border-success border-3' : '' }}">
                            <!-- Status Badge -->
                            <div class="position-absolute top-0 end-0 m-3" style="z-index: 10;">
                                @if ($design->status === 'active')
                                    <span class="badge bg-success px-3 py-2">
                                        <i class="bi bi-check-circle me-1"></i>Active
                                    </span>
                                @else
                                    <span class="badge bg-secondary px-3 py-2">Inactive</span>
                                @endif
                            </div>

                            <!-- Preview Image -->
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                                style="height: 250px; overflow: hidden;">
                                @if ($design->preview_image && Storage::disk('public')->exists($design->preview_image))
                                    <img src="{{ Storage::url($design->preview_image) }}" alt="{{ $design->theme_name }}"
                                        class="img-fluid" style="max-height: 100%; max-width: 100%; object-fit: contain;">
                                @else
                                    <div class="text-center text-muted">
                                        <i class="bi bi-card-image fs-1 mb-2"></i>
                                        <p class="mb-0">No Preview Available</p>
                                    </div>
                                @endif
                            </div>

                            <!-- Card Body -->
                            <div class="card-body">
                                <h5 class="card-title fw-bold mb-2">{{ $design->theme_name }}</h5>
                                <p class="card-text text-muted small mb-3">
                                    {{ $design->description ?? 'No description provided' }}
                                </p>

                                <div class="text-muted small mb-3">
                                    <i class="bi bi-calendar3 me-1"></i>
                                    Created: {{ $design->created_at->format('M d, Y') }}
                                </div>

                                <!-- Action Buttons -->
                                <div class="d-flex gap-2 flex-wrap">
                                    <!-- Preview Button -->
                                    <a href="{{ route('settings.id_design.preview', $design->id) }}"
                                        class="btn btn-sm btn-outline-primary" target="_blank" title="Preview Design">
                                        <i class="bi bi-eye"></i> Preview
                                    </a>

                                    <!-- Activate/Deactivate Button -->
                                    @if ($design->status === 'active')
                                        <form action="{{ route('settings.id_design.deactivate', $design->id) }}"
                                            method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-warning"
                                                title="Deactivate Design">
                                                <i class="bi bi-pause-circle"></i> Deactivate
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('settings.id_design.activate', $design->id) }}"
                                            method="POST" class="d-inline"
                                            onsubmit="return confirm('This will deactivate all other designs. Continue?')">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success" title="Activate Design">
                                                <i class="bi bi-check-circle"></i> Activate
                                            </button>
                                        </form>
                                    @endif

                                    <!-- Download Button -->
                                    <a href="{{ route('settings.id_design.download', $design->id) }}"
                                        class="btn btn-sm btn-outline-secondary" title="Download Template">
                                        <i class="bi bi-download"></i>
                                    </a>

                                    <!-- Delete Button -->
                                    @if ($design->status !== 'active')
                                        <form action="{{ route('settings.id_design.destroy', $design->id) }}"
                                            method="POST" class="d-inline"
                                            onsubmit="return confirm('Are you sure you want to delete this design? This action cannot be undone.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                title="Delete Design">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>

                            <!-- File Info Footer -->
                            <div class="card-footer bg-light border-0">
                                <small class="text-muted">
                                    <i class="bi bi-file-code me-1"></i>
                                    {{ basename($design->file_path) }}
                                </small>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-5">
                <div class="mb-4">
                    <i class="bi bi-inbox text-muted" style="font-size: 4rem;"></i>
                </div>
                <h4 class="text-muted mb-3">No ID Card Designs Yet</h4>
                <p class="text-muted mb-4">Create your first ID card design to get started</p>
                <a href="{{ route('settings.id_design.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-2"></i>Create Your First Design
                </a>
            </div>
        @endif
    </div>

    <style>
        .card {
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }

        .border-3 {
            border-width: 3px !important;
        }
    </style>
@endsection
