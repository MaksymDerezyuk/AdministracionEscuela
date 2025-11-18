function validaUsuario() {
     valor = document.getElementById("username").value.trim();
     error = document.getElementById("errorUsuario");
    if (valor.length === 0) {
        error.textContent = "El usuario no puede estar vacío";
    } else if (valor.length < 3) {
        error.textContent = "El usuario debe tener al menos 3 caracteres";
    } else {
        error.textContent = "";
    }
}

document.getElementById('username').onblur = validaUsuario;


function validaPassword() {
     valor = document.getElementById("password").value;
     error = document.getElementById("errorPassword");
    if (valor.length === 0) {
        error.textContent = "La contraseña no puede estar vacía";
    } else if (valor.length < 8) {
        error.textContent = "La contraseña debe tener al menos 8 caracteres";
    } else if (!/[A-Z]/.test(valor)) {
        error.textContent = "La contraseña debe contener al menos una letra mayúscula";
    } else if (!/[a-z]/.test(valor)) {
        error.textContent = "La contraseña debe contener al menos una letra minúscula";
    } else if (!/[0-9]/.test(valor)) {
        error.textContent = "La contraseña debe contener al menos un número";
    } else if (!/[!@#$%^&*(),.?":{}|<>]/.test(valor)) {
        error.textContent = "La contraseña debe contener al menos un carácter especial";
    } else {
        error.textContent = "";
    }
}

document.getElementById('password').onblur = validaPassword;



function validaConfirmPassword() {
     pass = document.getElementById("password").value;
     confirm = document.getElementById("confirm_password").value;
     error = document.getElementById("errorConfirm");
    if (confirm.length === 0) {
        error.textContent = "Debes confirmar la contraseña";
    } else if (pass !== confirm) {
        error.textContent = "Las contraseñas no coinciden";
    } else {
        error.textContent = "";
    }
}

document.getElementById('confirm_password').onblur = validaConfirmPassword;


function validaEmail() {
     valor = document.getElementById("email").value.trim();
     error = document.getElementById("errorEmail");
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
   if (valor.length === 0) {
       error.textContent = "El correo electrónico no puede estar vacío";
   } else if (!emailPattern.test(valor)) {
       error.textContent = "El correo electrónico no es válido";
   } else {
       error.textContent = "";
   }
}

document.getElementById('email').onblur = validaEmail;
