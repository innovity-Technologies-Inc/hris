{{-- ID Card Preview Modal --}}
<div class="modal fade" id="idCardPreviewModal" tabindex="-1" aria-labelledby="idCardPreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h5 class="modal-title fw-bold" id="idCardPreviewModalLabel">
                    <i class="bi bi-card-image me-2 text-primary"></i>
                    <span id="modalDesignName">ID Card Preview</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="text-center mb-3">
                    <p class="text-muted mb-2">
                        <i class="bi bi-info-circle me-1"></i>
                        Click the card to flip and view both sides
                    </p>
                </div>

                <!-- Flip Card Container -->
                <div class="d-flex justify-content-center align-items-center" style="min-height: 400px;">
                    <div class="flip-card-preview" id="previewFlipCard" onclick="this.classList.toggle('flipped')">
                        <div class="flip-card-preview-inner">
                            <!-- Front Side -->
                            <div class="flip-card-preview-front">
                                <img id="modalFrontImage" src="" alt="Front Card"
                                    class="img-fluid rounded shadow-lg"
                                    style="max-height: 400px; max-width: 100%; object-fit: contain; cursor: pointer;">
                                <div class="flip-hint-modal">
                                    <i class="bi bi-arrow-repeat me-1"></i> Click to flip
                                </div>
                            </div>
                            <!-- Back Side -->
                            <div class="flip-card-preview-back">
                                <img id="modalBackImage" src="" alt="Back Card"
                                    class="img-fluid rounded shadow-lg"
                                    style="max-height: 400px; max-width: 100%; object-fit: contain; cursor: pointer;">
                                <div class="flip-hint-modal">
                                    <i class="bi bi-arrow-repeat me-1"></i> Click to flip
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Instructions -->
                <div class="alert alert-info border-0 mt-4 mb-0" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-lightbulb fs-4 me-3"></i>
                        <div>
                            <strong>Tip:</strong> This is a preview of how the ID card will appear when generated for
                            employees.
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i> Close
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    /* Flip Card Preview in Modal */
    .flip-card-preview {
        width: 100%;
        max-width: 500px;
        height: 400px;
        position: relative;
        perspective: 1000px;
        cursor: pointer;
    }

    .flip-card-preview-inner {
        position: relative;
        width: 100%;
        height: 100%;
        transition: transform 0.8s;
        transform-style: preserve-3d;
    }

    .flip-card-preview.flipped .flip-card-preview-inner {
        transform: rotateY(180deg);
    }

    .flip-card-preview-front,
    .flip-card-preview-back {
        position: absolute;
        width: 100%;
        height: 100%;
        backface-visibility: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
    }

    .flip-card-preview-back {
        transform: rotateY(180deg);
    }

    .flip-hint-modal {
        position: absolute;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        background: rgba(0, 0, 0, 0.75);
        color: white;
        padding: 8px 20px;
        border-radius: 25px;
        font-size: 13px;
        font-weight: 500;
        opacity: 0;
        transition: opacity 0.3s ease;
        pointer-events: none;
        z-index: 10;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    }

    .flip-card-preview:hover .flip-hint-modal,
    .flip-card-preview.flipped .flip-hint-modal {
        opacity: 1;
    }

    /* Modal animation */
    .modal.fade .modal-dialog {
        transition: transform 0.3s ease-out;
    }

    .modal.show .modal-dialog {
        transform: none;
    }
</style>

<script>
    // Function to open preview modal
    function openPreviewModal(designName, frontImageUrl, backImageUrl) {
        // Set modal title
        document.getElementById('modalDesignName').textContent = designName;

        // Set images
        document.getElementById('modalFrontImage').src = frontImageUrl;
        document.getElementById('modalBackImage').src = backImageUrl;

        // Reset flip state
        document.getElementById('previewFlipCard').classList.remove('flipped');

        // Show modal
        const modal = new bootstrap.Modal(document.getElementById('idCardPreviewModal'));
        modal.show();
    }
</script>

