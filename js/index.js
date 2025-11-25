/**
 * Función para confirmar la eliminación de un alumno con SweetAlert2
 * @param {number} id - ID del alumno a eliminar
 * @param {string} nombreCompleto - Nombre completo del alumno
 */
function confirmarEliminacion(id, nombreCompleto) {
    Swal.fire({
        title: '¿Estás seguro?',
        html: `Se eliminará el alumno <strong>${nombreCompleto}</strong> y todos sus datos asociados:<br><br>` +
            `<ul style="text-align: left; display: inline-block;">` +
            `<li>Notas</li>` +
            `<li>Asistencias</li>` +
            `<li>Matrícula</li>` +
            `</ul>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // Redirigir al script de eliminación
            window.location.href = `./proc/proc_eliminar_alumno.php?id=${id}`;
        }
    });
}

/**
 * Mostrar SweetAlert de éxito si viene de actualización
 */
window.addEventListener('DOMContentLoaded', function () {
    const urlParams = new URLSearchParams(window.location.search);
    const success = urlParams.get('success');

    if (success) {
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: success,
            confirmButtonColor: '#005A9C',
            confirmButtonText: 'Aceptar'
        }).then(() => {
            // Limpiar la URL después de mostrar el mensaje
            window.history.replaceState({}, document.title, window.location.pathname);
        });
    }
});
