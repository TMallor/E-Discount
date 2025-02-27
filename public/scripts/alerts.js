document.addEventListener('DOMContentLoaded', function() {
    // Fermeture des alertes
    document.querySelectorAll('.close-alert').forEach(button => {
        button.addEventListener('click', function() {
            const alert = this.parentElement;
            alert.style.animation = 'fadeOut 0.3s ease-out forwards';
            setTimeout(() => alert.remove(), 300);
        });
    });

    // Auto-fermeture après 2 secondes
    document.querySelectorAll('.alert').forEach(alert => {
        setTimeout(() => {
            if (alert) {
                alert.style.animation = 'fadeOut 0.3s ease-out forwards';
                setTimeout(() => alert.remove(), 300);
            }
        }, 2000);
    });
}); 