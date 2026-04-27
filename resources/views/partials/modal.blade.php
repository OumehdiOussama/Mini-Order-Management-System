{{-- ══════════════════════════════════════════
    DELETE CONFIRMATION MODAL
    Triggered by data-confirm-delete attribute
══════════════════════════════════════════ --}}
<div id="deleteModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <div class="modal-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <span class="modal-title">Delete Confirmation</span>
        </div>
        <p class="modal-message" id="modalMessage">
            Are you sure you want to delete this item? This action cannot be undone.
        </p>
        <div class="modal-buttons">
            <button onclick="closeModal()" class="modal-btn modal-btn-cancel">
                Cancel
            </button>
            <button id="confirmDeleteBtn" class="modal-btn modal-btn-delete">
                Delete
            </button>
        </div>
    </div>
</div>

<script>
    let pendingForm = null;

    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('[data-confirm-delete]').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const label = this.getAttribute('data-confirm-delete');
                document.getElementById('modalMessage').textContent =
                    `Are you sure you want to delete this ${label}? This action cannot be undone.`;
                pendingForm = this.closest('form');
                document.getElementById('deleteModal').classList.add('show');
            });
        });

        document.getElementById('confirmDeleteBtn').addEventListener('click', () => {
            if (pendingForm) {
                pendingForm.submit();
                pendingForm = null;
            }
            closeModal();
        });
    });

    function closeModal() {
        document.getElementById('deleteModal').classList.remove('show');
        pendingForm = null;
    }

    // Close on backdrop click
    document.getElementById('deleteModal')?.addEventListener('click', function(e) {
        if (e.target === this) closeModal();
    });

    // Close on Escape
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') closeModal();
    });
</script>