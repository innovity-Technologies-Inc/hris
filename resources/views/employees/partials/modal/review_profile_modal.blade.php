<div class="modal fade" id="reviewProfileModal" tabindex="-1" aria-labelledby="reviewProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reviewProfileModalLabel">Review Employee Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('employees.review.submit', $employee->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Review Status</label>
                        <select name="status" id="reviewStatus" class="form-select" required>
                            <option value="">Select Status</option>
                            <option value="active">Accept (Set to Active)</option>
                            <option value="incomplete">Incomplete (Revert for Correction)</option>
                        </select>
                    </div>

                    <div class="mb-3 d-none" id="causeField">
                        <label class="form-label">Cause / Feedback</label>
                        <textarea name="cause" class="form-control" rows="4" placeholder="Explain what is missing or needs correction..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Submit Review</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusSelect = document.getElementById('reviewStatus');
    const causeField = document.getElementById('causeField');
    const causeTextarea = causeField.querySelector('textarea');

    statusSelect.addEventListener('change', function() {
        if (this.value === 'incomplete') {
            causeField.classList.remove('d-none');
            causeTextarea.setAttribute('required', 'required');
        } else {
            causeField.classList.add('d-none');
            causeTextarea.removeAttribute('required');
        }
    });
});
</script>
