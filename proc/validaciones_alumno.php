<?php
function validarAlumno($datos) {
    $errores = [];

    // DNI: obligatorio, 15 caracteres máximo
    if (empty($datos['dni'])) {
        $errores['dni'] = "El DNI es obligatorio.";
    } elseif (strlen($datos['dni']) > 15) {
        $errores['dni'] = "El DNI no puede superar 15 caracteres.";
    }

    // Nombre: obligatorio, máximo 50
    if (empty($datos['nombre'])) {
        $errores['nombre'] = "El nombre es obligatorio.";
    } elseif (strlen($datos['nombre']) > 50) {
        $errores['nombre'] = "El nombre no puede superar 50 caracteres.";
    }

    // Apellido1: obligatorio, máximo 50
    if (empty($datos['apellido1'])) {
        $errores['apellido1'] = "El primer apellido es obligatorio.";
    } elseif (strlen($datos['apellido1']) > 50) {
        $errores['apellido1'] = "El primer apellido no puede superar 50 caracteres.";
    }

    // Apellido2: opcional, máximo 50
    if (!empty($datos['apellido2']) && strlen($datos['apellido2']) > 50) {
        $errores['apellido2'] = "El segundo apellido no puede superar 50 caracteres.";
    }

    // Email: opcional pero si se introduce debe ser válido y máximo 100
    if (!empty($datos['email'])) {
        if (!filter_var($datos['email'], FILTER_VALIDATE_EMAIL)) {
            $errores['email'] = "El email no es válido.";
        } elseif (strlen($datos['email']) > 100) {
            $errores['email'] = "El email no puede superar 100 caracteres.";
        }
    }

    // Fecha nacimiento: obligatorio y formato válido
    if (empty($datos['fecha_nacimiento'])) {
        $errores['fecha_nacimiento'] = "La fecha de nacimiento es obligatoria.";
    } elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $datos['fecha_nacimiento'])) {
        $errores['fecha_nacimiento'] = "La fecha de nacimiento debe tener formato AAAA-MM-DD.";
    }

    // Grado: obligatorio
    if (empty($datos['grado']) || !is_numeric($datos['grado'])) {
        $errores['grado'] = "Debe seleccionar un grado.";
    }

    return $errores;
}
