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

    // Lógica para Filtros Móvil y Botones de Acción
    const filtersContainer = document.querySelector('.filters');
    if (filtersContainer) {
        const filtersForm = filtersContainer.querySelector('form');

        if (filtersForm) {
            // 1. Inyectar Botón Toggle
            const toggleBtn = document.createElement('button');
            toggleBtn.type = 'button';
            toggleBtn.className = 'filters-toggle';
            toggleBtn.id = 'filtersToggle';
            toggleBtn.innerHTML = '<i class="fas fa-filter"></i> Filtros <i class="fas fa-chevron-down toggle-icon"></i>';

            filtersContainer.insertBefore(toggleBtn, filtersForm);

            // Funcionalidad Toggle
            toggleBtn.addEventListener('click', function () {
                filtersForm.classList.toggle('show');
                toggleBtn.classList.toggle('active');
            });

            // 2. Mover Botones de Acción (Nuevo Alumno, Estadísticas)
            // Estructura: form > div (bottom row) > div (right side buttons)
            const bottomRow = filtersForm.lastElementChild;
            if (bottomRow) {
                const actionButtons = bottomRow.lastElementChild;
                // Verificamos que sea el div correcto (debe tener enlaces)
                if (actionButtons && actionButtons.tagName === 'DIV' && actionButtons.querySelector('a')) {
                    actionButtons.classList.add('action-buttons-moved');
                    // Mover después del contenedor de filtros
                    filtersContainer.parentNode.insertBefore(actionButtons, filtersContainer.nextSibling);
                }
            }
        }
    }
});
