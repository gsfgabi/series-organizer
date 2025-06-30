import './bootstrap';
import Alpine from 'alpinejs';

// Make Alpine available on window
window.Alpine = Alpine;

// Start Alpine
Alpine.start();

// Global functions
window.confirmDelete = function(message = 'Tem certeza que deseja excluir?') {
    return confirm(message);
};

// Flash message auto-hide
document.addEventListener('DOMContentLoaded', function() {
    const flashMessages = document.querySelectorAll('.flash-message');
    flashMessages.forEach(function(message) {
        setTimeout(function() {
            message.style.opacity = '0';
            setTimeout(function() {
                message.remove();
            }, 300);
        }, 3000);
    });
});
