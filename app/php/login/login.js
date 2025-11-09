function datuakegiaztatu() {
    var user = document.querySelector('input[name="user"]').value;
    var pas = document.querySelector('input[name="pas"]').value;

    if (user.trim() === "") {
        window.alert("Pasahitza ezin da hutsik egon.");
        return false;
    }
    else if(user.length > 50){
        window.alert("Pasahitza luzeegia da.")
        console.log(pas)
        return false;
    }

    if (pas.trim() === "") {
        window.alert("Pasahitza ezin da hutsik egon.");
        return false;
    }
    else if(pas.length > 40){
        window.alert("Pasahitza luzeegia da.")
        console.log(pas)
        return false;
    }

    document.getElementById('login_form').submit();
}