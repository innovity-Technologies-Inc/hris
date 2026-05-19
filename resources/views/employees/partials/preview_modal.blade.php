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

            previewContent.innerHTML = '';
            let html = '<div class="row g-4">';

            const inputs = form.querySelectorAll('input:not([type="hidden"]):not([type="file"]):not([type="password"]):not([type="submit"]):not([type="reset"]), select, textarea');
            
            // Map to store grouped array fields
            const groups = {};

            inputs.forEach((input) => {
                if (!input.name) return;

                // Check if name is an array like "educations[0][title]"
                const arrayMatch = input.name.match(/^(.+)\[(\d+)\]\[(.+)\]$/);
                
                if (arrayMatch) {
                    const groupName = arrayMatch[1];
                    const index = arrayMatch[2];
                    const fieldName = arrayMatch[3];

                    if (!groups[groupName]) groups[groupName] = {};
                    if (!groups[groupName][index]) groups[groupName][index] = [];

                    groups[groupName][index].push({
                        label: fieldName.replace(/_/g, ' ').toUpperCase(),
                        value: getInputValue(input)
                    });
                } else {
                    // Regular field
                    let labelText = '';
                    const label = form.querySelector(`label[for="${input.id}"]`);
                    if (label) {
                        labelText = label.innerText.replace('*', '').trim();
                    } else {
                        labelText = input.name.replace(/\[|\]|_/g, ' ').toUpperCase();
                    }

                    html += `
                        <div class="col-md-6 border-bottom pb-2">
                            <label class="text-muted small fw-bold text-uppercase d-block mb-1">${labelText}</label>
                            <div class="text-dark fw-medium">${getInputValue(input)}</div>
                        </div>
                    `;
                }
            });

            // Process grouped array fields
            for (const [groupName, indices] of Object.entries(groups)) {
                html += `<div class="col-12 mt-4 mb-2"><h6 class="text-primary border-bottom pb-2 text-uppercase"><i class="mdi mdi-layers-outline me-1"></i>${groupName.replace(/_/g, ' ')}</h6></div>`;
                
                for (const [index, fields] of Object.entries(indices)) {
                    html += `<div class="col-12"><div class="row bg-light p-3 rounded mb-2">`;
                    html += `<div class="col-12 mb-2 text-muted small fw-bold"># ${parseInt(index) + 1}</div>`;
                    fields.forEach(field => {
                        html += `
                            <div class="col-md-4 mb-3">
                                <label class="text-muted small fw-bold text-uppercase d-block mb-1">${field.label}</label>
                                <div class="text-dark fw-medium">${field.value}</div>
                            </div>
                        `;
                    });
                    html += `</div></div>`;
                }
            }

            html += '</div>';
            previewContent.innerHTML = html;
            previewModal.show();
        });
    }

    function getInputValue(input) {
        let value = '';
        if (input.tagName === 'SELECT') {
            value = input.options[input.selectedIndex] ? input.options[input.selectedIndex].text : '';
            if (value.toLowerCase().includes('choose one') || value === '') value = '<span class="text-muted italic">Not selected</span>';
        } else if (input.type === 'checkbox') {
            value = input.checked ? '<span class="badge bg-success">Yes</span>' : '<span class="badge bg-light text-dark border">No</span>';
        } else if (input.type === 'radio') {
            if (input.checked) {
                value = input.nextElementSibling ? input.nextElementSibling.innerText : 'Checked';
            } else {
                return ''; // Should be handled by caller
            }
        } else {
            value = input.value.trim();
            if (value === '') value = '<span class="text-muted italic">Empty</span>';
        }
        return value;
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
#previewContent label {
    font-size: 0.75rem;
    letter-spacing: 0.5px;
}
#previewContent .text-dark {
    word-break: break-all;
}
.italic {
    font-style: italic;
}
</style>
