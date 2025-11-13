// Ekitaldi-kudeatzailea gehitu "Hasierara" botoiari
document.addEventListener('DOMContentLoaded', () => {
    const homeButton = document.getElementById('home-button');
    if (homeButton) {
        homeButton.addEventListener('click', () => {
            window.location.href = '/';
        });
    }
});