<?php
session_start();

// Verificar que el usuario esté autenticado
if (!isset($_SESSION['logeado']) || $_SESSION['logeado'] !== true) {
    header('Location: ../view/login.php');
    exit();
}

// Verificar que sea administrador
if (!isset($_SESSION['user_rol']) || $_SESSION['user_rol'] !== 'administrador') {
    header('Location: ../index.php?error=' . urlencode('No tienes permisos para realizar esta acción'));
    exit();
}

require_once '../conexion/connection.php';

// Verificar que se haya pasado un ID válido
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: ../index.php?error=' . urlencode('ID de alumno no especificado'));
    exit();
}

$id_alumno = (int)$_GET['id'];

if ($id_alumno <= 0) {
    header('Location: ../index.php?error=' . urlencode('ID de alumno inválido'));
    exit();
}

try {
    // Iniciar transacción para garantizar la integridad de los datos
    $conn->beginTransaction();
    
    // Verificar que el alumno existe
    $stmt = $conn->prepare("SELECT id, nombre, apellido1 FROM tbl_alumnos WHERE id = :id");
    $stmt->bindParam(':id', $id_alumno, PDO::PARAM_INT);
    $stmt->execute();
    $alumno = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$alumno) {
        $conn->rollBack();
        header('Location: ../index.php?error=' . urlencode('Alumno no encontrado'));
        exit();
    }
    
    // Paso 1: Eliminar todas las notas del alumno (tbl_notas)
    $stmt = $conn->prepare("DELETE FROM tbl_notas WHERE id_alumno = :id_alumno");
    $stmt->bindParam(':id_alumno', $id_alumno, PDO::PARAM_INT);
    $stmt->execute();
    $notas_eliminadas = $stmt->rowCount();
    
    // Paso 2: Eliminar todas las asistencias del alumno (tbl_asistencias)
    $stmt = $conn->prepare("DELETE FROM tbl_asistencias WHERE id_alumno = :id_alumno");
    $stmt->bindParam(':id_alumno', $id_alumno, PDO::PARAM_INT);
    $stmt->execute();
    $asistencias_eliminadas = $stmt->rowCount();
    
    // Paso 3: Eliminar la matrícula del alumno (tbl_matriculas)
    $stmt = $conn->prepare("DELETE FROM tbl_matriculas WHERE id_alumno = :id_alumno");
    $stmt->bindParam(':id_alumno', $id_alumno, PDO::PARAM_INT);
    $stmt->execute();
    $matriculas_eliminadas = $stmt->rowCount();
    
    // Paso 4: Finalmente, eliminar el alumno (tbl_alumnos)
    $stmt = $conn->prepare("DELETE FROM tbl_alumnos WHERE id = :id");
    $stmt->bindParam(':id', $id_alumno, PDO::PARAM_INT);
    $stmt->execute();
    
    // Confirmar la transacción
    $conn->commit();
    
    // Mensaje de éxito con detalles
    $mensaje = "Alumno eliminado correctamente";
    if ($notas_eliminadas > 0 || $asistencias_eliminadas > 0 || $matriculas_eliminadas > 0) {
        $mensaje .= " (incluyendo {$notas_eliminadas} notas, {$asistencias_eliminadas} asistencias y {$matriculas_eliminadas} matrículas)";
    }
    
    header('Location: ../index.php?success=' . urlencode($mensaje));
    exit();
    
} catch (PDOException $e) {
    // Revertir todos los cambios en caso de error
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }
    
    // Log del error (opcional, para debugging)
    error_log("Error al eliminar alumno ID {$id_alumno}: " . $e->getMessage());
    
    header('Location: ../index.php?error=' . urlencode('Error al eliminar el alumno. Por favor, inténtelo de nuevo.'));
    exit();
}
?>
