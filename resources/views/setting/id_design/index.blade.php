@extends('structure.master')

@section('content')
    <div class="py-4">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fs-3 fw-bold text-dark mb-1">ID Card Designs</h2>
                <p class="text-muted mb-0">Manage your employee ID card templates</p>
            </div>
            <a href="{{ route('setting.id_design.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Create New Design
            </a>
        </div>

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
                            class="card h-100 shadow-sm {{ $design->status === 'active' ? 'border-3 border-success' : 'border-0' }}">
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

                            <!-- Preview Image with Flip Animation -->
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center flip-card-container"
                                style="height: 250px; overflow: hidden; perspective: 1000px;">
                                @if (
                                    $design->preview_front_card &&
                                        $design->preview_back_card &&
                                        \App\HelperClass::file_exists($design->preview_front_card) &&
                                        \App\HelperClass::file_exists($design->preview_back_card))
                                    <!-- Flip Card with Front/Back - Click to open modal -->
                                    <div class="flip-card"
                                        onclick="openPreviewModal('{{ $design->theme_name }}', '{{ \App\HelperClass::get_file_url($design->preview_front_card) }}', '{{ \App\HelperClass::get_file_url($design->preview_back_card) }}')"
                                        style="cursor: pointer;">
                                        <div class="flip-card-inner">
                                            <div class="flip-card-front">
                                                <img src="{{ \App\HelperClass::get_file_url($design->preview_front_card) }}"
                                                    alt="{{ $design->theme_name }} - Front" class="img-fluid"
                                                    style="max-height: 100%; max-width: 100%; object-fit: contain;">
                                                <div class="flip-hint">
                                                    <i class="bi bi-search-plus"></i> Click to enlarge
                                                </div>
                                            </div>
                                            <div class="flip-card-back">
                                                <img src="{{ \App\HelperClass::get_file_url($design->preview_back_card) }}"
                                                    alt="{{ $design->theme_name }} - Back" class="img-fluid"
                                                    style="max-height: 100%; max-width: 100%; object-fit: contain;">
                                                <div class="flip-hint">
                                                    <i class="bi bi-search-plus"></i> Click to enlarge
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @elseif ($design->preview_front_card && \App\HelperClass::file_exists($design->preview_front_card))
                                    <img src="{{ \App\HelperClass::get_file_url($design->preview_front_card) }}"
                                        alt="{{ $design->theme_name }}" class="img-fluid"
                                        style="max-height: 100%; max-width: 100%; object-fit: contain;">
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
                                    @if (
                                        $design->preview_front_card &&
                                            $design->preview_back_card &&
                                            \App\HelperClass::file_exists($design->preview_front_card) &&
                                            \App\HelperClass::file_exists($design->preview_back_card))
                                        <button type="button" class="btn btn-sm btn-outline-primary"
                                            onclick="openPreviewModal('{{ $design->theme_name }}', '{{ \App\HelperClass::get_file_url($design->preview_front_card) }}', '{{ \App\HelperClass::get_file_url($design->preview_back_card) }}')"
                                            title="Preview Design">
                                            <i class="bi bi-eye"></i> Preview
                                        </button>
                                    @else
                                        <a href="{{ route('setting.id_design.preview', $design->id) }}"
                                            class="btn btn-sm btn-outline-primary" target="_blank" title="Preview Design">
                                            <i class="bi bi-eye"></i> Preview
                                        </a>
                                    @endif

                                    <!-- Activate/Deactivate Button -->
                                    @if ($design->status === 'active')
                                        <button type="button" class="btn btn-sm btn-outline-warning"
                                            onclick="confirmDeactivate('deactivate-form-{{ $design->id }}')"
                                            title="Deactivate Design">
                                            <i class="bi bi-pause-circle"></i> Deactivate
                                        </button>
                                        <form action="{{ route('setting.id_design.deactivate', $design->id) }}"
                                            method="POST" style="display: none;" id="deactivate-form-{{ $design->id }}">
                                            @csrf
                                        </form>
                                    @else
                                        <button type="button" class="btn btn-sm btn-success"
                                            onclick="confirmActivate('activate-form-{{ $design->id }}')"
                                            title="Activate Design">
                                            <i class="bi bi-check-circle"></i> Activate
                                        </button>
                                        <form action="{{ route('setting.id_design.activate', $design->id) }}"
                                            method="POST" style="display: none;" id="activate-form-{{ $design->id }}">
                                            @csrf
                                        </form>
                                    @endif

                                    <!-- Edit Button -->
                                    <a href="{{ route('setting.id_design.edit', $design->id) }}"
                                        class="btn btn-sm btn-outline-info" title="Edit Design">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>

                                    <!-- Download Button -->
                                    <a href="{{ route('setting.id_design.download', $design->id) }}"
                                        class="btn btn-sm btn-outline-secondary" title="Download Template">
                                        <i class="bi bi-download"></i>
                                    </a>

                                    <!-- Delete Button -->
                                    @if ($design->status !== 'active')
                                        <form action="{{ route('setting.id_design.destroy', $design->id) }}"
                                            method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger confirmDelete"
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
                <a href="{{ route('setting.id_design.create') }}" class="btn btn-primary">
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

        /* Flip Card Styles */
        .flip-card-container {
            cursor: pointer;
        }

        .flip-card {
            width: 100%;
            height: 100%;
            position: relative;
            transition: transform 0.2s ease;
        }

        .flip-card:hover {
            transform: scale(1.02);
        }

        .flip-card-inner {
            position: relative;
            width: 100%;
            height: 100%;
            transition: transform 0.6s;
            transform-style: preserve-3d;
        }

        .flip-card.flipped .flip-card-inner,
        .flip-card:hover .flip-card-inner {
            transform: rotateY(180deg);
        }

        .flip-card-front,
        .flip-card-back {
            position: absolute;
            width: 100%;
            height: 100%;
            backface-visibility: hidden;
            -webkit-backface-visibility: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
        }

        .flip-card-back {
            transform: rotateY(180deg);
        }

        .flip-hint {
            position: absolute;
            bottom: 8px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0, 0, 0, 0.6);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            opacity: 0;
            transition: opacity 0.3s;
            backface-visibility: hidden;
            -webkit-backface-visibility: hidden;
        }

        .flip-card:hover .flip-hint {
            opacity: 1;
        }

        .flip-card.flipped .flip-hint {
            opacity: 1;
        }
    </style>

    {{-- Include Preview Modal --}}
    @include('setting.id_design.partials.preview_modal')

    {{-- SweetAlert for Activate and Deactivate Confirmations --}}
    <script>
        // Function to confirm and activate design
        function confirmActivate(formId) {
            Swal.fire({
                title: 'Activate This Design?',
                text: 'This will deactivate all other designs and set this as the active ID card template.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, Activate',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                }
            });
        }

        // Function to confirm and deactivate design
        function confirmDeactivate(formId) {
            Swal.fire({
                title: 'Deactivate This Design?',
                text: 'No ID card design will be active. You can activate another design or this one later.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#ffc107',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, Deactivate',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(formId).submit();
                }
            });
        }
    </script>
@endsection

