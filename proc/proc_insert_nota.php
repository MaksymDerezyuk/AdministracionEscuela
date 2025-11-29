<?php
session_start();

if (!isset($_SESSION['logeado']) || $_SESSION['logeado'] !== true) {
    header('Location: ../login.php');
    exit();
} else {
    $alumno_id = trim($_POST['id_alumno'] ?? '');
    $curso_alumno = trim($_POST['curso_alumno'] ?? '');
    $nota = trim($_POST['nota'] ?? '');
    $id_asignatura = trim($_POST['id_asignatura'] ?? '');
    $convocatoria = trim($_POST['convocatoria'] ?? '');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        require_once '../conexion/connection.php';
        if (!empty($alumno_id)) {
            if (!empty($curso_alumno)) {
                if (!empty($id_asignatura)) {
                    if (!empty($convocatoria)) {
                        if (is_numeric($nota)) {
                            if ($nota >= 0 && $nota <= 10) {
                                try {
                                    // Iniciar la transacción
                                    $conn->beginTransaction();
                                    // Insertar la nota en la base de datos
                                    $sql_insert = "INSERT INTO tbl_notas (id_alumno, id_asignatura, convocatoria, nota) VALUES (?, ?, ?, ?)";
                                    $stmt_insert = $conn->prepare($sql_insert);
                                    $stmt_insert->execute([$alumno_id, $id_asignatura, $convocatoria, $nota]);
                                    $conn->commit();
                                    header('Location: ../view/ver_alumno.php?id=' . urlencode($alumno_id) . '&success=Nota añadida correctamente');
                                    exit();
                                } catch (Exception $e) {
                                    // Revertir la transacción en caso de error
                                    $conn->rollBack();
                                    header('Location: ../view/poner_nota.php?id=' . urlencode($alumno_id) . '&error=Error al insertar la nota: ' . urlencode($e->getMessage()));
                                    exit();
                                }
                            } else {
                                header('Location: ../view/poner_nota.php?id=' . urlencode($alumno_id) . '&error=La nota debe estar entre 0 y 10');
                                exit();
                            }
                        } else {
                            header('Location: ../view/poner_nota.php?id=' . urlencode($alumno_id) . '&error=La nota debe ser un valor numérico');
                            exit();
                        }
                    } else {
                        header('Location: ../view/poner_nota.php?id=' . urlencode($alumno_id) . '&error=Tienes que seleccionar una convocatoria');
                        exit();
                    }
                } else {
                    header('Location: ../view/poner_nota.php?id=' . urlencode($alumno_id) . '&error=Tienes que seleccionar una asignatura');
                    exit();
                }
            } else {
                header('Location: ../view/poner_nota.php?id=' . urlencode($alumno_id) . '&error=No ha llegado el curso del alumno');
                exit();
            }
        } else {
            header('Location: ../index.php?error=No ha llegado el id del alumno');
            exit();
        }
    } else {
        header('Location: ../index.php');
        exit();
    }
}
