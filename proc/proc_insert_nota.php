<?php
session_start();

if (!isset($_SESSION['logeado']) || $_SESSION['logeado'] !== true) {
    header('Location: ../view/login.php');
    exit();
}

if (!isset($_SESSION['user_rol']) || $_SESSION['user_rol'] !== 'profesor') {
    header('Location: ../index.php?error=' . urlencode('No tienes permisos para realizar esta acción.'));
    exit();
}

$alumno_id = trim($_POST['id_alumno'] ?? '');
$curso_alumno = trim($_POST['curso_alumno'] ?? '');
$nota = trim($_POST['nota'] ?? '');
$id_asignatura = trim($_POST['id_asignatura'] ?? '');
$convocatoria = trim($_POST['convocatoria'] ?? '');
$profesor_id = $_SESSION['user_id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../conexion/connection.php';

    if (empty($alumno_id)) {
        header('Location: ../index.php?error=No ha llegado el id del alumno');
        exit();
    }

    if (empty($curso_alumno)) {
        header('Location: ../view/poner_nota.php?id=' . urlencode($alumno_id) . '&error=No ha llegado el curso del alumno');
        exit();
    }

    if (empty($id_asignatura)) {
        header('Location: ../view/poner_nota.php?id=' . urlencode($alumno_id) . '&error=Tienes que seleccionar una asignatura');
        exit();
    }

    if (empty($convocatoria)) {
        header('Location: ../view/poner_nota.php?id=' . urlencode($alumno_id) . '&error=Tienes que seleccionar una convocatoria');
        exit();
    }

    if (!is_numeric($nota)) {
        header('Location: ../view/poner_nota.php?id=' . urlencode($alumno_id) . '&error=La nota debe ser un valor numérico');
        exit();
    }

    if ($nota < 0 || $nota > 10) {
        header('Location: ../view/poner_nota.php?id=' . urlencode($alumno_id) . '&error=La nota debe estar entre 0 y 10');
        exit();
    }

    // Validar que el profesor imparte la asignatura y el alumno está matriculado en ella
    $sqlAuth = "SELECT COUNT(*) FROM tbl_matriculas tm\n                    INNER JOIN tbl_asignaturas tas ON tm.id_grado = tas.id_grado\n                    INNER JOIN tbl_profesor_asignatura tpa ON tas.id = tpa.id_asignatura\n                    WHERE tm.id_alumno = :alumno\n                      AND tas.id = :asignatura\n                      AND tpa.id_profesor = :profesor";

    $stmtAuth = $conn->prepare($sqlAuth);
    $stmtAuth->execute([
        ':alumno' => $alumno_id,
        ':asignatura' => $id_asignatura,
        ':profesor' => $profesor_id
    ]);

    if ((int)$stmtAuth->fetchColumn() === 0) {
        header('Location: ../view/poner_nota.php?id=' . urlencode($alumno_id) . '&error=No puedes calificar este alumno para esa asignatura');
        exit();
    }

    try {
        $conn->beginTransaction();
        $sql_insert = "INSERT INTO tbl_notas (id_alumno, id_asignatura, convocatoria, nota) VALUES (?, ?, ?, ?)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->execute([$alumno_id, $id_asignatura, $convocatoria, $nota]);
        $conn->commit();
        header('Location: ../view/ver_alumno.php?id=' . urlencode($alumno_id) . '&success=Nota añadida correctamente');
        exit();
    } catch (Exception $e) {
        if ($conn->inTransaction()) {
            $conn->rollBack();
        }
        header('Location: ../view/poner_nota.php?id=' . urlencode($alumno_id) . '&error=Error al insertar la nota: ' . urlencode($e->getMessage()));
        exit();
    }
} else {
    header('Location: ../index.php');
    exit();
}
