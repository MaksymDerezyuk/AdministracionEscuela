/**
 * Validaciones para el formulario de editar alumno
 * TODOS LOS CAMPOS SON OBLIGATORIOS
 */

// Elementos del formulario
const nombre = document.getElementById('nombre');
const apellido1 = document.getElementById('apellido1');
const apellido2 = document.getElementById('apellido2');
const email = document.getElementById('email');
const fecha_nacimiento = document.getElementById('fecha_nacimiento');
const id_grado = document.getElementById('id_grado');
const anio_academico = document.getElementById('anio_academico');
const btnSubmit = document.getElementById('btnSubmit');

// Elementos de error
const errorNombre = document.getElementById('errorNombre');
const errorApellido1 = document.getElementById('errorApellido1');
const errorApellido2 = document.getElementById('errorApellido2');
const errorEmail = document.getElementById('errorEmail');
const errorFecha = document.getElementById('errorFecha');
const errorGrado = document.getElementById('errorGrado');
const errorAnio = document.getElementById('errorAnio');

/**
 * Validar nombre
 */
nombre.onblur = function () {
    if (nombre.value.trim() === '') {
        errorNombre.textContent = 'El nombre es obligatorio';
        nombre.style.borderColor = '#e74c3c';
        validarFormularioCompleto();
        return false;
    }
    if (nombre.value.trim().length < 2) {
        errorNombre.textContent = 'El nombre debe tener al menos 2 caracteres';
        nombre.style.borderColor = '#e74c3c';
        validarFormularioCompleto();
        return false;
    }
    if (!/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/.test(nombre.value)) {
        errorNombre.textContent = 'El nombre solo puede contener letras';
        nombre.style.borderColor = '#e74c3c';
        validarFormularioCompleto();
        return false;
    }
    errorNombre.textContent = '';
    nombre.style.borderColor = '#28a745';
    validarFormularioCompleto();
    return true;
};

/**
 * Validar primer apellido
 */
apellido1.onblur = function () {
    if (apellido1.value.trim() === '') {
        errorApellido1.textContent = 'El primer apellido es obligatorio';
        apellido1.style.borderColor = '#e74c3c';
        validarFormularioCompleto();
        return false;
    }
    if (apellido1.value.trim().length < 2) {
        errorApellido1.textContent = 'El apellido debe tener al menos 2 caracteres';
        apellido1.style.borderColor = '#e74c3c';
        validarFormularioCompleto();
        return false;
    }
    if (!/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/.test(apellido1.value)) {
        errorApellido1.textContent = 'El apellido solo puede contener letras';
        apellido1.style.borderColor = '#e74c3c';
        validarFormularioCompleto();
        return false;
    }
    errorApellido1.textContent = '';
    apellido1.style.borderColor = '#28a745';
    validarFormularioCompleto();
    return true;
};

/**
 * Validar segundo apellido (OBLIGATORIO)
 */
apellido2.onblur = function () {
    if (apellido2.value.trim() === '') {
        errorApellido2.textContent = 'El segundo apellido es obligatorio';
        apellido2.style.borderColor = '#e74c3c';
        validarFormularioCompleto();
        return false;
    }
    if (apellido2.value.trim().length < 2) {
        errorApellido2.textContent = 'El apellido debe tener al menos 2 caracteres';
        apellido2.style.borderColor = '#e74c3c';
        validarFormularioCompleto();
        return false;
    }
    if (!/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/.test(apellido2.value)) {
        errorApellido2.textContent = 'El apellido solo puede contener letras';
        apellido2.style.borderColor = '#e74c3c';
        validarFormularioCompleto();
        return false;
    }
    errorApellido2.textContent = '';
    apellido2.style.borderColor = '#28a745';
    validarFormularioCompleto();
    return true;
};

/**
 * Validar email (OBLIGATORIO)
 */
email.onblur = function () {
    if (email.value.trim() === '') {
        errorEmail.textContent = 'El email es obligatorio';
        email.style.borderColor = '#e74c3c';
        validarFormularioCompleto();
        return false;
    }
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email.value)) {
        errorEmail.textContent = 'El formato del email no es válido';
        email.style.borderColor = '#e74c3c';
        validarFormularioCompleto();
        return false;
    }
    errorEmail.textContent = '';
    email.style.borderColor = '#28a745';
    validarFormularioCompleto();
    return true;
};

/**
 * Validar fecha de nacimiento
 */
fecha_nacimiento.onblur = function () {
    if (fecha_nacimiento.value === '') {
        errorFecha.textContent = 'La fecha de nacimiento es obligatoria';
        fecha_nacimiento.style.borderColor = '#e74c3c';
        validarFormularioCompleto();
        return false;
    }

    const fechaNac = new Date(fecha_nacimiento.value);
    const hoy = new Date();
    const edad = hoy.getFullYear() - fechaNac.getFullYear();

    if (fechaNac > hoy) {
        errorFecha.textContent = 'La fecha no puede ser futura';
        fecha_nacimiento.style.borderColor = '#e74c3c';
        validarFormularioCompleto();
        return false;
    }

    if (edad < 3 || edad > 100) {
        errorFecha.textContent = 'La edad debe estar entre 3 y 100 años';
        fecha_nacimiento.style.borderColor = '#e74c3c';
        validarFormularioCompleto();
        return false;
    }

    errorFecha.textContent = '';
    fecha_nacimiento.style.borderColor = '#28a745';
    validarFormularioCompleto();
    return true;
};

/**
 * Validar grado
 */
id_grado.onblur = function () {
    if (id_grado.value === '' || id_grado.value === '0') {
        errorGrado.textContent = 'Debe seleccionar un grado';
        id_grado.style.borderColor = '#e74c3c';
        validarFormularioCompleto();
        return false;
    }
    errorGrado.textContent = '';
    id_grado.style.borderColor = '#28a745';
    validarFormularioCompleto();
    return true;
};

/**
 * Validar año académico
 */
anio_academico.onblur = function () {
    if (anio_academico.value.trim() === '') {
        errorAnio.textContent = 'El año académico es obligatorio';
        anio_academico.style.borderColor = '#e74c3c';
        validarFormularioCompleto();
        return false;
    }

    // Validar formato YYYY/YYYY
    const anioRegex = /^\d{4}\/\d{4}$/;
    if (!anioRegex.test(anio_academico.value)) {
        errorAnio.textContent = 'Formato inválido. Use: 2024/2025';
        anio_academico.style.borderColor = '#e74c3c';
        validarFormularioCompleto();
        return false;
    }

    const [anio1, anio2] = anio_academico.value.split('/').map(Number);
    if (anio2 !== anio1 + 1) {
        errorAnio.textContent = 'El segundo año debe ser consecutivo al primero';
        anio_academico.style.borderColor = '#e74c3c';
        validarFormularioCompleto();
        return false;
    }

    errorAnio.textContent = '';
    anio_academico.style.borderColor = '#28a745';
    validarFormularioCompleto();
    return true;
};

/**
 * Validar todo el formulario y habilitar/deshabilitar botón
 */
function validarFormularioCompleto() {
    // TODOS LOS CAMPOS SON OBLIGATORIOS
    const nombreValido = nombre.value.trim() !== '' &&
        nombre.value.trim().length >= 2 &&
        /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/.test(nombre.value);

    const apellido1Valido = apellido1.value.trim() !== '' &&
        apellido1.value.trim().length >= 2 &&
        /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/.test(apellido1.value);

    const apellido2Valido = apellido2.value.trim() !== '' &&
        apellido2.value.trim().length >= 2 &&
        /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/.test(apellido2.value);

    const emailValido = email.value.trim() !== '' &&
        /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value);

    const fechaValida = fecha_nacimiento.value !== '';

    const gradoValido = id_grado.value !== '' && id_grado.value !== '0';

    const anioValido = anio_academico.value.trim() !== '' &&
        /^\d{4}\/\d{4}$/.test(anio_academico.value);

    // Habilitar o deshabilitar botón según validación
    if (nombreValido && apellido1Valido && apellido2Valido && emailValido &&
        fechaValida && gradoValido && anioValido) {
        btnSubmit.disabled = false;
        btnSubmit.style.opacity = '1';
        btnSubmit.style.cursor = 'pointer';
    } else {
        btnSubmit.disabled = true;
        btnSubmit.style.opacity = '0.5';
        btnSubmit.style.cursor = 'not-allowed';
    }
}

// Validar también con onkeyup para actualización en tiempo real
nombre.onkeyup = validarFormularioCompleto;
apellido1.onkeyup = validarFormularioCompleto;
apellido2.onkeyup = validarFormularioCompleto;
email.onkeyup = validarFormularioCompleto;
fecha_nacimiento.onchange = validarFormularioCompleto;
id_grado.onchange = validarFormularioCompleto;
anio_academico.onkeyup = validarFormularioCompleto;

/**
 * Validar todo el formulario antes de enviar
 */
const formulario = document.getElementById('formularioEditar');
formulario.onsubmit = function (e) {
    // Ejecutar todas las validaciones
    const nombreValido = nombre.onblur();
    const apellido1Valido = apellido1.onblur();
    const apellido2Valido = apellido2.onblur();
    const emailValido = email.onblur();
    const fechaValida = fecha_nacimiento.onblur();
    const gradoValido = id_grado.onblur();
    const anioValido = anio_academico.onblur();

    // Si alguna validación falla, prevenir envío
    if (!nombreValido || !apellido1Valido || !apellido2Valido || !emailValido ||
        !fechaValida || !gradoValido || !anioValido) {
        e.preventDefault();
        Swal.fire({
            icon: 'error',
            title: 'Errores en el formulario',
            text: 'Por favor, corrija los errores antes de continuar',
            confirmButtonColor: '#005A9C'
        });
        return false;
    }

    return true;
};

/**
 * Validar al cargar la página y mostrar SweetAlert de éxito
 */
window.addEventListener('DOMContentLoaded', function () {
    // Validar estado inicial del formulario
    validarFormularioCompleto();

    // Mostrar SweetAlert de éxito si viene de actualización
    const urlParams = new URLSearchParams(window.location.search);
    const success = urlParams.get('success');

    if (success) {
        Swal.fire({
            icon: 'success',
            title: '¡Actualizado!',
            text: success,
            confirmButtonColor: '#005A9C',
            confirmButtonText: 'Aceptar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirigir al index limpiando la URL
                window.location.href = '../index.php';
            }
        });
    }
});
