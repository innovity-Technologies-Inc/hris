
  <style>
    /* Modal entrance animation */
    .modal.fade .modal-dialog {
      transform: scale(0.92);
      transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    .modal.show .modal-dialog {
      transform: scale(1);
    }

    .modal-content {
      border: none;
      border-radius: 16px;
      overflow: hidden;
      box-shadow: 0 20px 50px -15px rgba(0, 0, 0, 0.25);
    }

    .modal-header {
      border: none;
      padding: 28px 32px 0;
      position: relative;
    }

    .modal-title {
      color: #1e293b;
      font-weight: 600;
    }

    /* Custom visible close button */
    .close-button {
      position: absolute;
      top: 24px;
      right: 24px;
      width: 42px;
      height: 42px;
      display: flex;
      align-items: center;
      justify-content: center;
      background: #f1f5f9;
      color: #64748b;
      border-radius: 50%;
      font-size: 1.5rem;
      font-weight: 300;
      cursor: pointer;
      border: none;
      transition: all 0.25s ease;
      z-index: 10;
    }

    .close-button:hover {
      background: #e2e8f0;
      color: #1e293b;
      transform: rotate(90deg);
    }

    .modal-body {
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 20px;
      min-height: 70vh;
    }

    #modalImage {
      max-width: 95vw;
      max-height: 75vh;
      object-fit: contain;
      border-radius: 12px;
      opacity: 0;
      transform: translateY(12px);
      transition: opacity 0.4s ease, transform 0.4s ease;
    }

    #modalImage.loaded {
      opacity: 1;
      transform: translateY(0);
    }

    .modal-footer {
      border: none;
      padding: 0 32px 32px;
      color: #94a3b8;
      font-size: 0.875rem;
      text-align: center;
    }

    .modal-backdrop.show {
      opacity: 0.8 !important;
    }
  </style>
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

