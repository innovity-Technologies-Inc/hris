<!-- Form Preview Modal -->
<div class="modal fade" id="formPreviewModal" tabindex="-1" aria-labelledby="formPreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="formPreviewModalLabel">
                    <i class="mdi mdi-eye me-2"></i> Form Preview
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4" id="previewContent">
                <!-- Preview content will be injected here via JavaScript -->
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light border-top">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="mdi mdi-close me-1"></i> Close & Edit
                </button>
                <button type="button" id="confirmSubmitBtn" class="btn btn-primary px-4">
                    <i class="mdi mdi-check-circle me-1"></i> Confirm & Submit
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const previewBtn = document.getElementById('previewBtn');
    const confirmSubmitBtn = document.getElementById('confirmSubmitBtn');
    const previewModal = new bootstrap.Modal(document.getElementById('formPreviewModal'));
    const previewContent = document.getElementById('previewContent');

    if (previewBtn) {
        previewBtn.addEventListener('click', function() {
            const form = this.closest('form');
            if (!form) return;

            // 1. Clone the form content
            const clone = form.cloneNode(true);

            // 2. Cleanup: Remove elements we don't want in preview
            const toRemove = clone.querySelectorAll('button, input[type="submit"], input[type="reset"], .text-danger, script, .alert, input[type="hidden"]');
            toRemove.forEach(el => el.remove());

            // 3. Transform inputs to read-only labels
            const inputs = clone.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                const originalInput = form.querySelector(`[name="${input.name}"]`);
                if (!originalInput) return;

                let displayValue = '';
                
                if (input.tagName === 'SELECT') {
                    displayValue = originalInput.options[originalInput.selectedIndex] ? originalInput.options[originalInput.selectedIndex].text : '';
                    if (displayValue.toLowerCase().includes('select') || displayValue === '') displayValue = '<span class="text-muted italic">Not selected</span>';
                } else if (input.type === 'checkbox' || input.type === 'radio') {
                    // Skip the input itself, we'll handle the label
                    if (originalInput.checked) {
                        displayValue = '<span class="badge bg-primary px-2 py-1">Selected</span>';
                    } else {
                        // If it's a grouped checkbox (like weekends), and unchecked, we might want to just hide it or show 'No'
                        input.parentElement.remove();
                        return;
                    }
                } else if (input.type === 'password') {
                    displayValue = '********';
                } else {
                    displayValue = originalInput.value.trim();
                    if (displayValue === '') displayValue = '<span class="text-muted italic">Empty</span>';
                }

                // Create a styled display element
                const displayEl = document.createElement('div');
                displayEl.className = 'preview-value p-2 bg-light rounded mt-1 fw-bold text-dark border';
                displayEl.innerHTML = displayValue;

                // For textareas, don't limit height as much
                if (input.tagName === 'TEXTAREA') {
                    displayEl.classList.add('h-auto');
                }

                // Replace input with display element
                input.parentNode.replaceChild(displayEl, input);
            });

            // 4. Final Polish: Clean up asterisks and spacing
            clone.innerHTML = clone.innerHTML.replace(/\*/g, '');

            // 5. Inject and Show
            previewContent.innerHTML = '';
            previewContent.appendChild(clone);
            
            // Fix any select2 or custom wrapper leftovers that might look weird
            const select2Containers = previewContent.querySelectorAll('.select2-container');
            select2Containers.forEach(el => el.remove());

            previewModal.show();
        });
    }

    if (confirmSubmitBtn) {
        confirmSubmitBtn.addEventListener('click', function() {
            const originalSubmitBtn = document.querySelector('button[type="submit"]');
            if (originalSubmitBtn) {
                previewModal.hide();
                originalSubmitBtn.click();
            } else {
                const form = document.querySelector('form');
                if (form) form.submit();
            }
        });
    }
});
</script>

<style>
#previewContent .preview-value {
    font-size: 0.85rem;
    min-height: 38px;
    display: flex;
    align-items: center;
    background-color: #f8f9fa !important;
    border: 1px solid #dee2e6 !important;
}
#previewContent .preview-value.h-auto {
    min-height: auto;
    align-items: flex-start;
}
#previewContent label {
    font-size: 0.75rem;
    letter-spacing: 0.5px;
    font-weight: 700;
    color: #495057;
    text-transform: uppercase;
    margin-bottom: 4px;
}
#previewContent .card {
    margin-bottom: 1.25rem;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
}
#previewContent .text-dark {
    word-break: break-all;
}
.italic {
    font-style: italic;
}
</style>
