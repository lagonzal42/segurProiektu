/**
 * Funtzio honek alert() funtzioa ordezkatzen du, mezua HTML elementu batean erakutsiz.
 * @param {string} mezua - Erakutsi beharreko errorea.
 */
function bistaratuMezua(mezua) {
    const mezuKutxa = document.getElementById('js-mezua');
    if (mezuKutxa) {
        mezuKutxa.innerHTML = `<span class="error-message">${mezua}</span>`;
        mezuKutxa.style.display = 'block';
    }
}

/**
 * Funtzio honek mezuen eremua garbitzen du.
 */
function garbituMezua() {
    const mezuKutxa = document.getElementById('js-mezua');
    if (mezuKutxa) {
        mezuKutxa.innerHTML = '';
        mezuKutxa.style.display = 'none';
    }
}


/**
 * Datuak balioztatzen ditu inprimakia bidali aurretik.
 */
function datuakegiaztatu() {
    garbituMezua(); // Aurreko mezuak garbitu

    // Balioak eskuratu
    const user = document.querySelector('input[name="user"]').value.trim();
    const izen = document.querySelector('input[name="iz_abz"]').value.trim();
    const nan = document.querySelector('input[name="nan"]').value.trim();
    const tlnf = document.querySelector('input[name="tlnf"]').value.trim();
    const jaiodata = document.querySelector('input[name="jaiodata"]').value.trim();
    const mail = document.querySelector('input[name="mail"]').value.trim();
    const pas = document.querySelector('input[name="pas"]').value.trim();

    // Regex-ak
    const nanRegex = /^[0-9]{8}[A-Z]$/i; // 'i' kasua ez bereizteko
    const tlnfRegex = /^\d{9}$/;
    const mailRegex = /^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/;
    const jaioRegex = /^\d{4}-\d{2}-\d{2}$/;
    const izenRegex = /^[A-Za-zÀ-ÖØ-öø-ÿ\s'-]+$/;
    
    // Balidazioak
    
    // NAN
    if (nan.length !== 9 || !nanRegex.test(nan)) {
        bistaratuMezua("NAN-aren formatua ez da egokia (8 zenbaki + 1 letra).");
        return false;
    }
    // NAN letra kalkulatu
    if (!konprobatunan(nan)){
        bistaratuMezua("NAN hori ez da existitzen (NAN-aren kontrol-letra ez da zuzena).");
        return false;
    } 

    // Telefonoa
    if (!tlnfRegex.test(tlnf)) {
        bistaratuMezua("Telefono zenbakiaren luzera ez da egokia (9 digitu).");
        return false;
    }    
    
    // Posta elektronikoa
    if (!mailRegex.test(mail)) {
        bistaratuMezua("Email-aren formatua ez da egokia.");
        return false;
    } 
    if (mail.length > 50) {
        bistaratuMezua("Email-a luzeegia da (gehienez 50 karaktere).");
        return false;
    }
    
    // Jaiotze data
    if(!jaioRegex.test(jaiodata)){
        bistaratuMezua("Jaiotze data ez da egokia (uuuu-hh-ee formatua).");
        return false;
    }
    
    // Izen Abizenak
    if(!izenRegex.test(izen)){
        bistaratuMezua("Izen-abizenetan onartzen ez diren karaktereak daude.");
        return false;
    }
    if(izen.length > 50){
        bistaratuMezua("Izen-abizena luzeegia da (gehienez 50 karaktere).");
        return false;
    }

    // Pasahitza
    if (pas === "") {
        bistaratuMezua("Pasahitza ezin da hutsik egon.");
        return false;
    }
    if(pas.length > 40){
        bistaratuMezua("Pasahitza luzeegia da (gehienez 40 karaktere).");
        return false;
    }

    // Erabiltzailea
    if (user === "") {
        bistaratuMezua("Erabiltzailea ezin da hutsik egon.");
        return false;
    }
    if(user.length > 50){
        bistaratuMezua("Erabiltzailea luzeegia da (gehienez 50 karaktere).");
        return false;
    }

    // Balidazio guztiak ondo badaude, inprimakia bidali
    document.getElementById('register_form').submit();
}

/**
 * NAN baten kontrol-letra balioztatzen du.
 * @param {string} nan - NAN zenbakia (8 digitu + letra).
 * @returns {boolean} - True balidazioa zuzena bada, False bestela.
 */
function konprobatunan(nan){
    const zenb = nan.substring(0, nan.length - 1);
    const letra = nan.substring(nan.length - 1, nan.length).toUpperCase();

    const letrak = "TRWAGMYFPDXBNJZSQVHLCKET";
    const kalkulatuta = letrak.charAt(zenb % 23);

    return kalkulatuta === letra;
}

// Gertaera-entzulea erantsi kargatzen denean (CSP betez)
document.addEventListener('DOMContentLoaded', () => {
    const submitButton = document.getElementById('register_submit');
    if (submitButton) {
        submitButton.addEventListener('click', datuakegiaztatu);
    }
});

// Ekitaldi-kudeatzailea gehitu "Hasierara" botoiari
document.addEventListener('DOMContentLoaded', () => {
    const homeButton = document.getElementById('home-button');
    if (homeButton) {
        homeButton.addEventListener('click', () => {
            window.location.href = '/';
        });
    }
});