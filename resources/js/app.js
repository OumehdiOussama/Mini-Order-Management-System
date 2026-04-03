import './bootstrap';

const modal = document.getElementById('confirmModal');
const confirmMessage = document.getElementById('confirmMessage');
const cancelBtn = document.getElementById('cancelBtn');
const deleteBtn = document.getElementById('deleteBtn');
let formToSubmit = null;

// Listen for all delete button clicks
document.querySelectorAll('[data-confirm-delete]').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        formToSubmit = this.closest('form');
        
        const itemType = this.getAttribute('data-confirm-delete');
        confirmMessage.textContent = `Are you sure you want to delete this ${itemType} ? This action cannot be undone.`;
        
        modal.classList.add('show');
    });
});

// Cancel button
cancelBtn.addEventListener('click', function() {
    modal.classList.remove('show');
    formToSubmit = null;
});

// Delete button
deleteBtn.addEventListener('click', function() {
    if (formToSubmit) formToSubmit.submit();
});

// Close modal when clicking outside
modal.addEventListener('click', function(e) {
    if (e.target === modal) {
        modal.classList.remove('show');
        formToSubmit = null;
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && modal.classList.contains('show')) {
        modal.classList.remove('show');
        formToSubmit = null;
    }
});