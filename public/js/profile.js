// Profile — Delete account modal logic
document.addEventListener('DOMContentLoaded', function() {
    var modal = document.getElementById('deleteModal');
    if (!modal) return;

    // Show modal if there were validation errors (flag set via data attribute)
    if (modal.getAttribute('data-show') === 'true') {
        modal.classList.add('show');
    }

    // Close modal on overlay click
    modal.addEventListener('click', function(e) {
        if (e.target === modal) modal.classList.remove('show');
    });
});
