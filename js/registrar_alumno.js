function validaDni() {
    var dniInput = document.getElementById('dni');
    var dni = dniInput.value.trim();
    var error = document.getElementById('error-dni');

    if (dni === '') {
        error.textContent = "El DNI es obligatorio.";
        return false;
    }

    var dniRegex = /^\d{8}[A-Za-z]$/;
    if (!dniRegex.test(dni)) {
        error.textContent = "El formato del DNI no es correcto (8 números y 1 letra).";
        return false;
    }

    var numero = dni.substr(0, 8);
    var letra = dni.substr(8, 1).toUpperCase();
    var letrasValidas = "TRWAGMYFPDXBNJZSQVHLCKE";
    var letraCalculada = letrasValidas.charAt(numero % 23);

    if (letra !== letraCalculada) {
        error.textContent = "La letra del DNI no es correcta.";
        return false;
    }

    error.textContent = "";
    return true;
}

function validaNombre() {
    var nombreInput = document.getElementById('nombre');
    var nombre = nombreInput.value.trim();
    var error = document.getElementById('error-nombre');

    if (nombre === '') {
        error.textContent = "El nombre es obligatorio.";
        return false;
    }

    if (nombre.length > 50) {
        error.textContent = "El nombre no puede superar los 50 caracteres.";
        return false;
    }

    var letrasRegex = /^[A-Za-zÁÉÍÓÚáéíóúñÑ\s]+$/;
    if (!letrasRegex.test(nombre)) {
        error.textContent = "El nombre solo puede contener letras.";
        return false;
    }

    error.textContent = "";
    return true;
}

function validaApellido1() {
    var apellidoInput = document.getElementById('apellido1');
    var apellido = apellidoInput.value.trim();
    var error = document.getElementById('error-apellido1');

    if (apellido === '') {
        error.textContent = "El primer apellido es obligatorio.";
        return false;
    }

    if (apellido.length > 50) {
        error.textContent = "El apellido no puede superar los 50 caracteres.";
        return false;
    }

    var letrasRegex = /^[A-Za-zÁÉÍÓÚáéíóúñÑ\s]+$/;
    if (!letrasRegex.test(apellido)) {
        error.textContent = "El apellido solo puede contener letras.";
        return false;
    }

    error.textContent = "";
    return true;
}

function validaApellido2() {
    var apellidoInput = document.getElementById('apellido2');
    var apellido = apellidoInput.value.trim();
    var error = document.getElementById('error-apellido2');

    if (apellido === '') {
        error.textContent = "El segundo apellido es obligatorio.";
        return false;
    }

    if (apellido.length > 50) {
        error.textContent = "El apellido no puede superar los 50 caracteres.";
        return false;
    }

    var letrasRegex = /^[A-Za-zÁÉÍÓÚáéíóúñÑ\s]+$/;
    if (!letrasRegex.test(apellido)) {
        error.textContent = "El apellido solo puede contener letras.";
        return false;
    }

    error.textContent = "";
    return true;
}

function validaEmail() {
    var emailInput = document.getElementById('email');
    var email = emailInput.value.trim();
    var error = document.getElementById('error-email');

    if (email === '') {
        error.textContent = "El email es obligatorio.";
        return false;
    }

    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        error.textContent = "El formato del email no es correcto.";
        return false;
    }

    error.textContent = "";
    return true;
}

function validaFecha() {
    var fechaInput = document.getElementById('fecha_nacimiento');
    var fecha = fechaInput.value;
    var error = document.getElementById('error-fecha');

    if (fecha === '') {
        error.textContent = "La fecha de nacimiento es obligatoria.";
        return false;
    }

    var dateObj = new Date(fecha);
    if (isNaN(dateObj.getTime())) {
        error.textContent = "El formato de la fecha no es correcto.";
        return false;
    }

    error.textContent = "";
    return true;
}

function validaGrado() {
    var gradoInput = document.getElementById('grado');
    var grado = gradoInput.value;
    var error = document.getElementById('error-grado');

    if (grado === '' || grado === null) {
        error.textContent = "Debes seleccionar un grado.";
        return false;
    }

    error.textContent = "";
    return true;
}

// Asignación de eventos onblur
document.getElementById('dni').onblur = validaDni;
document.getElementById('nombre').onblur = validaNombre;
document.getElementById('apellido1').onblur = validaApellido1;
document.getElementById('apellido2').onblur = validaApellido2;
document.getElementById('email').onblur = validaEmail;
document.getElementById('fecha_nacimiento').onblur = validaFecha;
document.getElementById('grado').onblur = validaGrado;

function validarFormulario() {
    var vDni = validaDni();
    var vNombre = validaNombre();
    var vApellido1 = validaApellido1();
    var vApellido2 = validaApellido2();
    var vEmail = validaEmail();
    var vFecha = validaFecha();
    var vGrado = validaGrado();

    return vDni && vNombre && vApellido1 && vApellido2 && vEmail && vFecha && vGrado;
}
