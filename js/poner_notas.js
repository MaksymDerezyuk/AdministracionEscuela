function ValidarFormulario() {

    function comprobarbtn() {

        let sinErrores = false;
        let camposVacios = true;

        var btn = document.getElementById("btn_enviar");
        var error_asignatura = document.getElementById("error_asignatura").innerHTML;
        var error_convocatoria = document.getElementById("error_convocatoria").innerHTML;
        var error_nota = document.getElementById("error_nota").innerHTML;

        var asignatura = document.getElementById("id_asignatura").value;
        var convocatoria = document.getElementById("convocatoria").value;
        var nota = document.getElementById("nota").value;

        if (asignatura === "" || convocatoria === "" || nota === "") {
            camposVacios = true;
        } else {
            camposVacios = false;
        }

        if (error_asignatura === "" && error_convocatoria === "" && error_nota === "") {
            sinErrores = true;
        } else {
            sinErrores = false;
        }

        if (sinErrores === true && camposVacios === false) {
            btn.disabled = false;
        } else {
            btn.disabled = true;
        }
    }

    function ValidarAsignatura() {
        var elementoSelect = document.getElementById("id_asignatura");
        var error = document.getElementById("error_asignatura");

        if (elementoSelect.selectedIndex === 0) {
            error.innerHTML = "Debe seleccionar una asignatura.";
            error.classList.add("alerta", "alerta-error");
        } else {
            error.innerHTML = "";
            error.classList.remove("alerta", "alerta-error");
        }
        comprobarbtn();
    }
    document.getElementById("id_asignatura").onblur = ValidarAsignatura;

    function ValidarConvocatoria() {
        var elementoSelect = document.getElementById("convocatoria");
        var error = document.getElementById("error_convocatoria");

        if (elementoSelect.selectedIndex === 0) {
            error.innerHTML = "Debe seleccionar una convocatoria.";
            error.classList.add("alerta", "alerta-error");
        } else {
            error.innerHTML = "";
            error.classList.remove("alerta", "alerta-error");
        }
        comprobarbtn();
    }
    document.getElementById("convocatoria").onblur = ValidarConvocatoria;

    function ValidarNota() {
        var Nota = document.getElementById("nota").value;
        var error = document.getElementById("error_nota");

        if (Nota.trim() === "") {
            error.innerHTML = "La nota no puede estar vacía.";
            error.classList.add("alerta", "alerta-error");
        } else if (isNaN(Nota)) {
            error.innerHTML = "La nota debe ser un número válido.";
            error.classList.add("alerta", "alerta-error");
        } else if (Nota < 0 || Nota > 10) {
            error.innerHTML = "La nota debe estar entre 0 y 10.";
            error.classList.add("alerta", "alerta-error");
        } else {
            error.innerHTML = "";
            error.classList.remove("alerta", "alerta-error");
        }
        comprobarbtn();
    }
    document.getElementById("nota").onblur = ValidarNota;

    comprobarbtn();
}

window.onload = ValidarFormulario;