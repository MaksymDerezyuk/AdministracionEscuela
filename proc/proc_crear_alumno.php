<?php
session_start();

if (!isset($_SESSION['logeado']) || $_SESSION['logeado'] !== true) {
    header('Location: ../view/login.php');
    exit();
}

if (!isset($_SESSION['user_rol']) || $_SESSION['user_rol'] !== 'administrador') {
    header('Location: ../index.php');
    exit();
}

require_once('../conexion/connection.php');
require_once('validaciones_alumno.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sessionToken = $_SESSION['csrf_token'] ?? '';
    $postedToken = $_POST['csrf_token'] ?? '';

    if (empty($sessionToken) || empty($postedToken) || !hash_equals($sessionToken, $postedToken)) {
        header("Location: ../view/crear_alumno.php?msg=" . urlencode('Token CSRF inválido, vuelve a intentarlo.') . "&tipo=error");
        exit;
    }

    unset($_SESSION['csrf_token']);

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

        // 1. INSERTAR ALUMNO
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

        // 2. INSERTAR MATRÍCULA
        // Nota: En tu imagen 'tbl_matriculas' tiene 'anio_academico'.
        // Es recomendable insertarlo. Aquí calculo el año actual (ej: "2024-2025").
        $anio_actual = date('Y') . '-' . (date('Y') + 1); 
        
        $stmt2 = $conn->prepare("INSERT INTO tbl_matriculas (id_alumno, id_grado, anio_academico) VALUES (?, ?, ?)");
        $stmt2->execute([$alumnoId, $datos['grado'], $anio_actual]);

        // 3. OBTENER ASIGNATURAS DEL GRADO
        // Seleccionamos las asignaturas vinculadas a este grado
        $stmtAsignaturas = $conn->prepare("SELECT id FROM tbl_asignaturas WHERE id_grado = ?");
        $stmtAsignaturas->execute([$datos['grado']]);
        $asignaturas = $stmtAsignaturas->fetchAll(PDO::FETCH_ASSOC);

        // 4. INSERTAR EN TBL_NOTAS (Vincular alumno con asignaturas)
        // Preparamos la consulta fuera del bucle para mayor rendimiento
        $stmtNotas = $conn->prepare("INSERT INTO tbl_notas (id_alumno, id_asignatura, nota, convocatoria) VALUES (?, ?, NULL, NULL)");

        foreach ($asignaturas as $asignatura) {
            $stmtNotas->execute([
                $alumnoId, 
                $asignatura['id']
            ]);
        }
        $conn->commit();

        header("Location: ../view/crear_alumno.php?msg=Alumno y asignaturas registrados correctamente.&tipo=exito");
        exit;

    } catch (PDOException $e) {
        $conn->rollBack();
        $error = urlencode("Error al registrar: " . $e->getMessage());
        header("Location: ../view/crear_alumno.php?msg=$error&tipo=error");
        exit;
    }
} else {
    header("Location: ../view/crear_alumno.php");
    exit;
}
?>