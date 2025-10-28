function datuakegiaztatu() {
    var iz_abz = document.querySelector('input[name="iz_abz"]').value;
    var pas = document.querySelector('input[name="pas"]').value;

    var izenRegex = /^[A-Za-zÀ-ÖØ-öø-ÿ\s]+$/;

    if (!izenRegex.test(iz_abz)) {
        window.alert("Izen hori ez da honartzen.")
        return false;
    } 

    if (pas.trim() === "") {
        window.alert("Pasahitza ezin da hutsik egon.");
        return false;
    }

    document.getElementById('login_form').submit();
}