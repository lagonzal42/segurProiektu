function datuakegiaztatu() {                                                // Formularioan sartutako datuak egokiak direla egiaztatzen ditu.
    var izen = document.querySelector('input[name="iz_abz"]').value;        // Formularioan idatzitako izen abizenaren balioa gordeko duen aldagaia, laburtzeko izen deitu zaiona.
    var pas = document.querySelector('input[name="pas"]').value;            // Formularioan idatzitako pasahitzaren balioa gordeko duen aldagaia.

    var izenRegex = /^[A-Za-zÀ-ÖØ-öø-ÿ\s]+$/;                               // Izenaren formatua egokia dela egiaztatzen duen adierazpen erregularra.

    if(!izenRegex.test(izen)){                                              // Izenaren formatua egokia dela konprobatzen du, textu soila dela.
        window.alert("Izen hori ez da onartzen.")                           // Formatu ez egokia badu, mezu bat pantailaratuko da.
        return false;
    }
    else if(izen.length > 50){                                              // Izena luzeegia ez dela konprobatzen du.
        window.alert("Izena luzeegia da.")                                  // Luzeegia bada, mezu bat pantailaratuko da.
        return false;
    }

    if (pas.trim() === "") {                                                // Pasahitza hutsik dagoenentz konprobatzen du.
        window.alert("Pasahitza ezin da hutsik egon.");                     // Hutsik badago, mezu bat pantailaratuko da.
        return false;
    }
    else if(pas.length > 40){                                               // Pasahitza luzeegia ez dela konprobatzen du.
        window.alert("Pasahitza luzeegia da.")                              // Luzeegia bada, mezu bat pantailaratuko da.
        return false;
    }

    document.getElementById('login_form').submit();                         // Datu guztiak egokiak badira, formularioa bidaltzen du.
}