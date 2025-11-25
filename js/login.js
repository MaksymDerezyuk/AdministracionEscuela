function email() {
    var email = document.getElementById("email").value;
    var requisitos = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (email.trim() === '') {
        document.getElementById("error-email").innerHTML = "Tienes que rellenar el email.";
    } else if (!requisitos.test(email)) {
        document.getElementById("error-email").innerHTML = "El formato del email no es válido.";
    } else {
        document.getElementById("error-email").innerHTML = "";
    }
    
}

document.getElementById("email").onblur = email;

function contrasena() {
    var pass = document.getElementById("contrasena").value;
    if (pass.trim() === '') {
        document.getElementById("error-contrasena").innerHTML = "Tienes que rellenar la contraseña.";
    } else if (pass.length < 6) {
        document.getElementById("error-contrasena").innerHTML = "La contraseña debe tener al menos 6 caracteres.";
    } else {
        document.getElementById("error-contrasena").innerHTML = "";
    }
}

document.getElementById("contrasena").onblur = contrasena;