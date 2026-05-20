  <!-- Modal -->
  <div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Image Preview</h5>
          <!-- Custom visible close button -->
          <button type="button" class="close-button" id="modalCloseBtn" aria-label="Close">×</button>
        </div>
        <div class="modal-body">
          <img id="modalImage" src="" alt="Image preview">
        </div>
        <div class="modal-footer">
          Click outside or press × to close
        </div>
      </div>
    </div>
  </div>

  <script>
    const imageModal = document.getElementById('imageModal');
    const modalImage = document.getElementById('modalImage');
    const closeBtn = document.getElementById('modalCloseBtn');
    const viewLinks = document.querySelectorAll('.view-link');

    // Open modal and load image
    viewLinks.forEach(link => {
      link.addEventListener('click', function(event) {
        event.preventDefault();  // Prevent default action (like navigation)
        const imgSrc = this.getAttribute('data-img');
        modalImage.classList.remove('loaded');
        modalImage.src = imgSrc;

        const modal = new bootstrap.Modal(imageModal);
        modal.show();
      });
    });

    // Close on custom × button
    closeBtn.addEventListener('click', () => {
      bootstrap.Modal.getInstance(imageModal).hide();
    });

    // Close on backdrop click
    imageModal.addEventListener('click', (e) => {
      if (e.target === imageModal) {
        bootstrap.Modal.getInstance(imageModal).hide();
      }
    });

    // Animate image when loaded
    modalImage.addEventListener('load', () => {
      modalImage.classList.add('loaded');
    });
  </script>


