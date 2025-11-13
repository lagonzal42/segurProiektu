/**
 * Lógica de JavaScript externa para evitar 'unsafe-inline' en CSP.
 */

document.addEventListener('DOMContentLoaded', () => {
    const backButton = document.getElementById('back-to-home');
    
    // Verifica si el botón existe antes de añadir el listener
    if (backButton) {
        backButton.addEventListener('click', () => {
            // Navegación segura
            window.location.href = '/';
        });
    }
});