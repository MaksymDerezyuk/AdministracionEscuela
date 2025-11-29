<?php
session_start();

// Verificar si el usuario está logueado y es administrador
if (!isset($_SESSION['logeado']) || $_SESSION['logeado'] !== true) {
    header('Location: ../view/login.php');
    exit();
}

if (!isset($_SESSION['user_rol']) || $_SESSION['user_rol'] !== 'administrador') {
    header('Location: ../index.php');
    exit();
}

require_once '../conexion/connection.php';

// Verificar método POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php');
    exit();
}

// Guardar datos recibidos en variables
$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
$apellido1 = isset($_POST['apellido1']) ? trim($_POST['apellido1']) : '';
$apellido2 = isset($_POST['apellido2']) ? trim($_POST['apellido2']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$fecha_nacimiento = isset($_POST['fecha_nacimiento']) ? trim($_POST['fecha_nacimiento']) : '';
$id_grado_nuevo = isset($_POST['id_grado']) ? (int)$_POST['id_grado'] : 0; // Grado que viene del formulario
$anio_academico = isset($_POST['anio_academico']) ? trim($_POST['anio_academico']) : '';

// Empezamos las validaciones
if ($id <= 0) {
    header('Location: ../index.php?error=' . urlencode('ID de alumno inválido'));
    exit();
}

if (empty($nombre) || empty($apellido1) || empty($fecha_nacimiento)) {
    header('Location: ../view/editar_alumno.php?id=' . $id . '&error=' . urlencode('Todos los campos obligatorios deben estar completos'));
    exit();
}

if ($id_grado_nuevo <= 0) {
    header('Location: ../view/editar_alumno.php?id=' . $id . '&error=' . urlencode('Debe seleccionar un grado válido'));
    exit();
}

if (empty($anio_academico)) {
    header('Location: ../view/editar_alumno.php?id=' . $id . '&error=' . urlencode('El año académico es obligatorio'));
    exit();
}

if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: ../view/editar_alumno.php?id=' . $id . '&error=' . urlencode('El formato del email no es válido'));
    exit();
}

try {
    // 4. INICIO DE TRANSACCIÓN
    $conn->beginTransaction();

    // --- Verificar existencia del alumno ---
    $stmt = $conn->prepare("SELECT id FROM tbl_alumnos WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $alumnoExistente = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$alumnoExistente) {
        header ('Location: ../view/editar_alumno.php?id=' . $id . '&error=' . urlencode("El alumno no existe."));
        exit();
    }

    // --- Verificar email duplicado ---
    if (!empty($email)) {
        $stmt = $conn->prepare("SELECT id FROM tbl_alumnos WHERE email = :email AND id != :id");
        $stmt->execute([':email' => $email, ':id' => $id]);
        if ($stmt->fetch()) {
            header('Location: ../view/editar_alumno.php?id=' . $id . '&error=' . urlencode("El email ya está registrado en otro alumno."));
            exit();
        }
    }

    // --- ACTUALIZAR DATOS PERSONALES ---
    $sql = "UPDATE tbl_alumnos SET 
            nombre = :nombre, apellido1 = :apellido1, apellido2 = :apellido2, 
            email = :email, fecha_nacimiento = :fecha_nacimiento
            WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':nombre' => $nombre,
        ':apellido1' => $apellido1,
        ':apellido2' => $apellido2,
        ':email' => $email,
        ':fecha_nacimiento' => $fecha_nacimiento,
        ':id' => $id
    ]);


    // --- GESTIÓN DE MATRÍCULA Y ASIGNATURAS ---
    // Obtenemos la matrícula actual
    $stmtMatricula = $conn->prepare("SELECT id_grado FROM tbl_matriculas WHERE id_alumno = :id_alumno");
    $stmtMatricula->execute([':id_alumno' => $id]);
    $matriculaActual = $stmtMatricula->fetch(PDO::FETCH_ASSOC);

    $id_grado_viejo = $matriculaActual ? (int)$matriculaActual['id_grado'] : 0;

    // Si tenía matrícula y el grado ha cambiado, eliminar asignaturas del grado viejo
    if ($id_grado_viejo != 0 && $id_grado_viejo != $id_grado_nuevo) {
        // Borramos de tbl_notas las asignaturas que pertenecen al grado viejo
        $sqlDelete = "DELETE FROM tbl_notas 
                      WHERE id_alumno = :id_alumno 
                      AND id_asignatura IN (
                          SELECT id FROM tbl_asignaturas WHERE id_grado = :id_grado_viejo
                      )";
        $stmtDelete = $conn->prepare($sqlDelete);
        $stmtDelete->execute([
            ':id_alumno' => $id,
            ':id_grado_viejo' => $id_grado_viejo
        ]);
    }

    // Actualizar o insertar matrícula
    if ($matriculaActual) {
        $sql = "UPDATE tbl_matriculas SET id_grado = :id_grado, anio_academico = :anio WHERE id_alumno = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id_grado' => $id_grado_nuevo, ':anio' => $anio_academico, ':id' => $id]);
    } else {
        $sql = "INSERT INTO tbl_matriculas (id_alumno, id_grado, anio_academico) VALUES (:id, :id_grado, :anio)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id' => $id, ':id_grado' => $id_grado_nuevo, ':anio' => $anio_academico]);
    }

    // Añadir asignaturas del nuevo grado
    // Obtenemos las asignaturas del nuevo grado
    $stmtAsig = $conn->prepare("SELECT id FROM tbl_asignaturas WHERE id_grado = :id_grado");
    $stmtAsig->execute([':id_grado' => $id_grado_nuevo]);
    $nuevasAsignaturas = $stmtAsig->fetchAll(PDO::FETCH_ASSOC);

    // Preparamos las consultas para verificar e insertar
    $stmtCheck = $conn->prepare("SELECT id FROM tbl_notas WHERE id_alumno = :id AND id_asignatura = :id_asig");
    $stmtInsert = $conn->prepare("INSERT INTO tbl_notas (id_alumno, id_asignatura, nota, convocatoria) VALUES (:id, :id_asig, NULL, NULL)");

    foreach ($nuevasAsignaturas as $asignatura) {
        $stmtCheck->execute([':id' => $id, ':id_asig' => $asignatura['id']]);

        if (!$stmtCheck->fetch()) {
            // Insertamos la vinculación
            $stmtInsert->execute([':id' => $id, ':id_asig' => $asignatura['id']]);
        }
    }

    $conn->commit();

    header('Location: ../index.php?success=' . urlencode('Alumno modificado correctamente'));
    exit();
} catch (Exception $e) {
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }
    header('Location: ../view/editar_alumno.php?id=' . $id . '&error=' . urlencode('Error: ' . $e->getMessage()));
    exit();
}
