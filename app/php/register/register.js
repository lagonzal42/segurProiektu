function datuakegiaztatu() {
    var nan = document.querySelector('input[name="nan"]').value;
    var tlnf = document.querySelector('input[name="tlnf"]').value;
    var jaiodata = document.querySelector('input[name="jaiodata"]').value;
    var mail = document.querySelector('input[name="mail"]').value;
    var izen = document.querySelector('input[name="iz_abz"]').value;
    var pas = document.querySelector('input[name="pas"]').value;

    var nanRegex = /^[0-9]{8}[A-Z]$/;
    var mailRegex = /^[A-Za-z0-9.]+@{1}[A-Za-z0-9.]+.[A-Za-z]{2,}$/;
    var jaioRegex = /^\d{4}-\d{2}-\d{2}$/;
    var izenRegex = /^[A-Za-zÀ-ÖØ-öø-ÿ\s]+$/;


    if (nan.length < 9 || nan.length > 9) {
        window.alert("NAN-aren luzera ez da egokia.")
        return false;
    }
    else if (!nanRegex.test(nan)) {
        window.alert("NAN-ak ez du formatu egokia.")
        return false;
    }

    if (tlnf.length < 9 || tlnf.length > 9) {
        window.alert("Telefono zenbakiaren luzera ez da egokia.")
        return false;
    }    

    if (!mailRegex.test(mail)) {
        window.alert("Email-a ez da egokia.")
        return false;
    } 
    
    if(!jaioRegex.test(jaiodata)){
        window.alert("Jaiotze data ez da egokia.")
        return false;
    }
    
    if(!izenRegex.test(izen)){
        window.alert("Izen hori ez da onartzen")
        console.log(pas)
        return false;
    }

    if (pas.trim() === "") {
        window.alert("Pasahitza ezin da hutsik egon.");
        return false;
    }

    document.getElementById('register_form').submit();

}
