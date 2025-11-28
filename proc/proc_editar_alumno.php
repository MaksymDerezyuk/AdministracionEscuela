<?php
session_start();

// Verificar que el usuario esté autenticado
if (!isset($_SESSION['logeado']) || $_SESSION['logeado'] !== true) {
    header('Location: ../view/login.php');
    exit();
}

// Verificar que sea administrador
if (!isset($_SESSION['user_rol']) || $_SESSION['user_rol'] !== 'administrador') {
    header('Location: ../index.php');
    exit();
}

require_once '../conexion/connection.php';

// Verificar que se haya enviado el formulario por POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../index.php');
    exit();
}

// Obtener y validar los datos del formulario
$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
$apellido1 = isset($_POST['apellido1']) ? trim($_POST['apellido1']) : '';
$apellido2 = isset($_POST['apellido2']) ? trim($_POST['apellido2']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$fecha_nacimiento = isset($_POST['fecha_nacimiento']) ? trim($_POST['fecha_nacimiento']) : '';
$id_grado = isset($_POST['id_grado']) ? (int)$_POST['id_grado'] : 0;
$anio_academico = isset($_POST['anio_academico']) ? trim($_POST['anio_academico']) : '';

// Validaciones básicas
if ($id <= 0) {
    header('Location: ../index.php?error=' . urlencode('ID de alumno inválido'));
    exit();
}

if (empty($nombre) || empty($apellido1) || empty($fecha_nacimiento)) {
    header('Location: ../view/editar_alumno.php?id=' . $id . '&error=' . urlencode('Todos los campos obligatorios deben estar completos'));
    exit();
}

if ($id_grado <= 0) {
    header('Location: ../view/editar_alumno.php?id=' . $id . '&error=' . urlencode('Debe seleccionar un grado válido'));
    exit();
}

if (empty($anio_academico)) {
    header('Location: ../view/editar_alumno.php?id=' . $id . '&error=' . urlencode('El año académico es obligatorio'));
    exit();
}

// Validar formato de email si se proporciona
if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header('Location: ../view/editar_alumno.php?id=' . $id . '&error=' . urlencode('El formato del email no es válido'));
    exit();
}

try {
    // Iniciar transacción
    $conn->beginTransaction();
    
    // Verificar que el alumno existe
    $stmt = $conn->prepare("SELECT id FROM tbl_alumnos WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    
    if (!$stmt->fetch()) {
        $conn->rollBack();
        header('Location: ../index.php?error=' . urlencode('Alumno no encontrado'));
        exit();
    }
    
    
    // Verificar que el email no esté duplicado si se proporciona (excepto para el mismo alumno)
    if (!empty($email)) {
        $stmt = $conn->prepare("SELECT id FROM tbl_alumnos WHERE email = :email AND id != :id");
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        if ($stmt->fetch()) {
            $conn->rollBack();
            header('Location: ../view/editar_alumno.php?id=' . $id . '&error=' . urlencode('El email ya está registrado para otro alumno'));
            exit();
        }
    }
    
    // Verificar que el grado existe
    $stmt = $conn->prepare("SELECT id FROM tbl_grados WHERE id = :id_grado");
    $stmt->bindParam(':id_grado', $id_grado, PDO::PARAM_INT);
    $stmt->execute();
    
    if (!$stmt->fetch()) {
        $conn->rollBack();
        header('Location: ../view/editar_alumno.php?id=' . $id . '&error=' . urlencode('El grado seleccionado no existe'));
        exit();
    }
    
    // Actualizar datos del alumno (DNI no se edita)
    $sql = "UPDATE tbl_alumnos SET 
            nombre = :nombre,
            apellido1 = :apellido1,
            apellido2 = :apellido2,
            email = :email,
            fecha_nacimiento = :fecha_nacimiento
            WHERE id = :id";
    
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':apellido1', $apellido1);
    $stmt->bindParam(':apellido2', $apellido2);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':fecha_nacimiento', $fecha_nacimiento);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    
    
    // Verificar si ya existe una matrícula para este alumno
    $stmt = $conn->prepare("SELECT id FROM tbl_matriculas WHERE id_alumno = :id_alumno");
    $stmt->bindParam(':id_alumno', $id, PDO::PARAM_INT);
    $stmt->execute();
    $matricula_existente = $stmt->fetch();
    
    if ($matricula_existente) {
        // Actualizar matrícula existente
        $sql = "UPDATE tbl_matriculas SET 
                id_grado = :id_grado,
                anio_academico = :anio_academico
                WHERE id_alumno = :id_alumno";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id_grado', $id_grado, PDO::PARAM_INT);
        $stmt->bindParam(':anio_academico', $anio_academico);
        $stmt->bindParam(':id_alumno', $id, PDO::PARAM_INT);
        $stmt->execute();
    } else {
        // Crear nueva matrícula
        $sql = "INSERT INTO tbl_matriculas (id_alumno, id_grado, anio_academico) 
                VALUES (:id_alumno, :id_grado, :anio_academico)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id_alumno', $id, PDO::PARAM_INT);
        $stmt->bindParam(':id_grado', $id_grado, PDO::PARAM_INT);
        $stmt->bindParam(':anio_academico', $anio_academico);
        $stmt->execute();
    }
    
    // Confirmar transacción
    $conn->commit();
    
    header('Location: ../index.php?success=' . urlencode('Alumno actualizado correctamente'));
    exit();
    
} catch (PDOException $e) {
    // Revertir cambios en caso de error
    if ($conn->inTransaction()) {
        $conn->rollBack();
    }
    header('Location: ../view/editar_alumno.php?id=' . $id . '&error=' . urlencode('Error en la base de datos: ' . $e->getMessage()));
    exit();
}
?>
