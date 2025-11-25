<?php
require_once('../conexion/connection.php');
require_once('validaciones_alumno.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $datos = [
        'dni' => trim($_POST['dni']),
        'nombre' => trim($_POST['nombre']),
        'apellido1' => trim($_POST['apellido1']),
        'apellido2' => trim($_POST['apellido2']),
        'email' => trim($_POST['email']),
        'fecha_nacimiento' => $_POST['fecha_nacimiento'],
        'grado' => $_POST['grado']
    ];

    $errores = validarAlumno($datos);

    if (!empty($errores)) {
        $errorMsg = urlencode(implode(' ', $errores));
        header("Location: ../view/crear_alumno.php?msg=$errorMsg&tipo=error");
        exit;
    }

    try {
        $conn->beginTransaction();

        $stmt1 = $conn->prepare("INSERT INTO tbl_alumnos (dni, nombre, apellido1, apellido2, email, fecha_nacimiento) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt1->execute([
            $datos['dni'],
            $datos['nombre'],
            $datos['apellido1'],
            $datos['apellido2'],
            $datos['email'],
            $datos['fecha_nacimiento']
        ]);

        $alumnoId = $conn->lastInsertId();

        $stmt2 = $conn->prepare("INSERT INTO tbl_matriculas (id_alumno, id_grado) VALUES (?, ?)");
        $stmt2->execute([$alumnoId, $datos['grado']]);

        $conn->commit();

        header("Location: ../view/crear_alumno.php?msg=Alumno registrado correctamente.&tipo=exito");
        exit;

    } catch (PDOException $e) {
        $conn->rollBack();
        $error = urlencode("Error al registrar: ".$e->getMessage());
        header("Location: ../view/crear_alumno.php?msg=$error&tipo=error");
        exit;
    }
} else {
    header("Location: ../view/crear_alumno.php");
    exit;
}
