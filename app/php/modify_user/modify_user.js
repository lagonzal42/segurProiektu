function datuakegiaztatu() {                                             // Formularioan sartutako datuak egokiak direla egiaztatzen ditu.
    var tlnf = document.querySelector('input[name="Telefonoa"]').value;          // Formularioan idatzitako telefono zenbakiaren balioa gordeko duen aldagaia.
    var jaiodata = document.querySelector('input[name="Jaio_Data"]').value;  // Formularioan idatzitako jaiotze dataren balioa gordeko duen aldagaia.
    var mail = document.querySelector('input[name="Email"]').value;          // Formularioan idatzitako email-aren balioa gordeko duen aldagaia.
    var izen = document.querySelector('input[name="Izen_Abizen"]').value;   // Formularioan idatzitako izen abizenaren balioa gordeko duen aldagaia.

    var mailRegex = /^[A-Za-z0-9.]+@{1}[A-Za-z0-9.]+.[A-Za-z]{2,}$/;        // Email-aren formatua egokia dela egiaztatzen duen adierazpen erregularra.
    var jaioRegex = /^\d{4}-\d{2}-\d{2}$/;                                  // Jaiotze dataren formatua egokia dela egiaztatzen duen adierazpen erregularra. 
    var izenRegex = /^[A-Za-zÀ-ÖØ-öø-ÿ\s]+$/;                               // Izenaren formatua egokia dela egiaztatzen duen adierazpen erregularra.
    var tlnfRegex = /^[0-9]{9}$/;                                           // Telefono zenbakiaren formatua egokia dela egiaztatzen duen adierazpen erregularra.   
                
    if (tlnf.length < 9 || tlnf.length > 9) {                               // Telefono zenbakiaren luzera egokia dela konprobatzen du.
        window.alert("Telefono zenbakiaren luzera ez da egokia.")           // Luzera ez egokia badu, mezu bat pantailaratuko da.
        return false;
    }
    else if (!tlnfRegex.test(tlnf)) {                                       // Telefono zenbakiaren formatua egokia dela konprobatzen du, karaktere guztiak zenbakiak direla.
        window.alert("Telefono zenbakiak ez du formatu egokia.")            // Formatu ez egokia badu, mezu bat pantailaratuko da.
        return false;
    }    

    if (!mailRegex.test(mail)) {                                            // Email-aren formatua egokia dela konprobatzen du.
        window.alert("Email-a ez da egokia.")                               // Formatu ez egokia badu, mezu bat pantailaratuko da.
        return false;
    } 
    else if (mail.length > 50) {                                            // Email-a luzegia ez dela konprobatzen du.
        window.alert("Email-a luzeegia da.")                                // Luzeegia bada, mezu bat pantailaratuko da.
        return false;
    }
    
    if(!jaioRegex.test(jaiodata)){                                          // Jaiotze dataren formatua egokia dela konprobatzen du.
        window.alert("Jaiotze data ez da egokia.")                          // Formatu ez egokia badu, mezu bat pantailaratuko da.
        return false;
    }
    
    if(!izenRegex.test(izen)){                                              // Izenaren formatua egokia dela konprobatzen du, textu soila dela.
        window.alert("Izen hori ez da onartzen.")                           // Formatu ez egokia badu, mezu bat pantailaratuko da.
        return false;
    }
    else if(izen.length > 50){                                              // Izena luzeegia ez dela konprobatzen du.
        window.alert("Izena luzeegia da.")                                  //  Luzeegia bada, mezu bat pantailaratuko da.
        return false;
    }

    document.getElementById('user_modify_form').submit();                   // Datu guztiak egokiak badira, formularioa bidaltzen du.

}

function konprobatunan(nan){                                                // NAN-a existitzen dela konprobatuko duen funtzioa.
    var existitzen = false;                                                 // NAN-a existitzen denentz adieraziko duen aldagaia, false-n hasieratuta.
    zenb = nan.substring(0, nan.length - 1);                                // NAN-aren zenbaki zatia gordeko duen aldagaia.
    letra = nan.substring(nan.length - 1, nan.length);                      // NAN-aren letra zatia gordeko duen aldagaia.

    letrak = "TRWAGMYFPDXBNJZSQVHLCKET";                                    // NAN-aren letra kalkulatzeko erabiliko den karaktere katea.
    kalkulatuta = letrak.charAt(zenb % 23);                                 // NAN-aren letra kalkulatuta gordeko duen aldagaia.

    if(kalkulatuta != letra){                                               // NAN-aren letra kalkulatutakoarekin bat egiten badu konprobatzen du.
        return existitzen;                                                  // NAN-aren letra bat egiten ez badu, false itzultzen du.
    }                   
    else{                   
        return !existitzen;                                                 // NAN-aren letra bat egiten badu, true itzultzen du.
    }
}
