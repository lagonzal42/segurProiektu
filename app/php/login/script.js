// Espera a que el DOM esté completamente cargado antes de intentar adjuntar el listener
document.addEventListener('DOMContentLoaded', function() {
    // Selecciona el botón usando el ID que definiste en el HTML
    const homeButton = document.getElementById('hasiera_btn');

    // Verifica que el botón existe antes de añadir el listener
    if (homeButton) {
        // Añade el Event Listener para la acción de click
        homeButton.addEventListener('click', function() {
            // Redirige a la raíz del sitio
            window.location.href = '/';
        });
    }
});