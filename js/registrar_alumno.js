function validaDni() {
    var dni = document.getElementById('dni').value;
    var error = document.getElementById('error-dni');
    if (dni.trim() === '') {
        error.textContent = "El DNI es obligatorio.";
        return false;
    } else if (dni.trim().length < 9) {
        error.textContent = "El DNI debe tener al menos 9 caracteres.";
        return false;
    } else {
        error.textContent = "";
        return true;
    }
}

function validaNombre() {
    var nombre = document.getElementById('nombre').value;
    var error = document.getElementById('error-nombre');
    if (nombre.trim() === '') {
        error.textContent = "El nombre es obligatorio.";
        return false;
    } else {
        error.textContent = "";
        return true;
    }
}

function validaApellido1() {
    var apellido1 = document.getElementById('apellido1').value;
    var error = document.getElementById('error-apellido1');
    if (apellido1.trim() === '') {
        error.textContent = "El primer apellido es obligatorio.";
        return false;
    } else {
        error.textContent = "";
        return true;
    }
}

function validaEmail() {
    var email = document.getElementById('email').value;
    var error = document.getElementById('error-email');
    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (email.trim() !== '' && !emailRegex.test(email.trim())) {
        error.textContent = "El formato del email no es válido.";
        return false;
    } else {
        error.textContent = "";
        return true;
    }
}

function validaFecha() {
    var fechaNacimiento = document.getElementById('fecha_nacimiento').value;
    var error = document.getElementById('error-fecha');

    if (!fechaNacimiento) {
        error.textContent = "La fecha de nacimiento es obligatoria.";
        return false;
    } else {
        var fecha = new Date(fechaNacimiento);
        var hoy = new Date();
        if (fecha > hoy) {
            error.textContent = "La fecha de nacimiento no puede ser futura.";
            return false;
        } else {
            error.textContent = "";
            return true;
        }
    }
}

function validaGrado() {
    var grado = document.getElementById('grado').value;
    var error = document.getElementById('error-grado');

    if (!grado) {
        error.textContent = "Debes seleccionar un grado.";
        return false;
    } else {
        error.textContent = "";
        return true;
    }
}

// Asignación de eventos onblur
document.getElementById('dni').onblur = validaDni;
document.getElementById('nombre').onblur = validaNombre;
document.getElementById('apellido1').onblur = validaApellido1;
document.getElementById('email').onblur = validaEmail;
document.getElementById('fecha_nacimiento').onblur = validaFecha;
document.getElementById('grado').onblur = validaGrado;

function validarFormulario() {
    var vDni = validaDni();
    var vNombre = validaNombre();
    var vApellido1 = validaApellido1();
    var vEmail = validaEmail();
    var vFecha = validaFecha();
    var vGrado = validaGrado();

    return vDni && vNombre && vApellido1 && vEmail && vFecha && vGrado;
}
