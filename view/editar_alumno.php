<?php
session_start();

// Verificar que el usuario esté autenticado
if (!isset($_SESSION['logeado']) || $_SESSION['logeado'] !== true) {
    header('Location: login.php');
    exit();
}

// Verificar que sea administrador
if (!isset($_SESSION['user_rol']) || $_SESSION['user_rol'] !== 'administrador') {
    header('Location: ../index.php');
    exit();
}

require_once '../conexion/connection.php';

// Verificar que se haya pasado un ID válido
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header('Location: ../index.php?error=' . urlencode('ID de alumno no especificado'));
    exit();
}

$id_alumno = (int)$_GET['id'];

// Consultar los datos del alumno con su grado usando INNER JOIN
try {
    $sql = "SELECT 
                a.id,
                a.dni,
                a.nombre,
                a.apellido1,
                a.apellido2,
                a.email,
                a.fecha_nacimiento,
                m.id_grado,
                m.anio_academico,
                g.nombre as nombre_grado
            FROM tbl_alumnos a
            LEFT JOIN tbl_matriculas m ON a.id = m.id_alumno
            LEFT JOIN tbl_grados g ON m.id_grado = g.id
            WHERE a.id = :id
            LIMIT 1";
    
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id', $id_alumno, PDO::PARAM_INT);
    $stmt->execute();
    $alumno = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$alumno) {
        header('Location: ../index.php?error=' . urlencode('Alumno no encontrado'));
        exit();
    }
    
    // Obtener todos los grados disponibles para el selector
    $stmt_grados = $conn->prepare("SELECT id, nombre FROM tbl_grados ORDER BY nombre");
    $stmt_grados->execute();
    $grados = $stmt_grados->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    header('Location: ../index.php?error=' . urlencode('Error al consultar el alumno'));
    exit();
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Editar Alumno</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/editar_alumno.css">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <main class="contenedor">
        <div class="auth-split">
            <div class="auth-right">
                <div class="tarjeta tarjeta-wide">
                    <h1>Editar Alumno</h1>

                    <form id="formularioEditar" action="../proc/proc_editar_alumno.php" method="post" class="formulario formulario-grid">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($alumno['id']); ?>">
                        
                        <label>
                            Nombre
                            <input type="text" id="nombre" name="nombre" value="<?php echo htmlspecialchars($alumno['nombre']); ?>" >
                            <div id="errorNombre" class="error"></div>
                        </label>
                        
                        <label>
                            Primer Apellido
                            <input type="text" id="apellido1" name="apellido1" value="<?php echo htmlspecialchars($alumno['apellido1']); ?>" >
                            <div id="errorApellido1" class="error"></div>
                        </label>
                        
                        <label>
                            Segundo Apellido
                            <input type="text" id="apellido2" name="apellido2" value="<?php echo htmlspecialchars($alumno['apellido2']); ?>">
                            <div id="errorApellido2" class="error"></div>
                        </label>
                        
                        <label class="full-width">
                            Correo electrónico
                            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($alumno['email']); ?>">
                            <div id="errorEmail" class="error"></div>
                        </label>
                        
                        <label>
                            Fecha de Nacimiento
                            <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" value="<?php echo htmlspecialchars($alumno['fecha_nacimiento']); ?>">
                            <div id="errorFecha" class="error"></div>
                        </label>
                        
                        <label>
                            Grado
                            <select id="id_grado" name="id_grado" >
                                <option value="">Selecciona un grado</option>
                                <?php foreach ($grados as $grado): ?>
                                    <option value="<?php echo $grado['id']; ?>" 
                                        <?php echo ($alumno['id_grado'] == $grado['id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($grado['nombre']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div id="errorGrado" class="error"></div>
                        </label>
                        
                        <label class="full-width">
                            Año Académico
                            <input type="text" id="anio_academico" name="anio_academico" 
                                   value="<?php echo htmlspecialchars($alumno['anio_academico'] ?? '2024/2025'); ?>" 
                                   placeholder="2024/2025" >
                            <div id="errorAnio" class="error"></div>
                        </label>

                        <button type="submit" id="btnSubmit">Actualizar Alumno</button>
                        <a href="../index.php">Volver a la lista de alumnos</a>
                    </form>

                    <?php
                    if (!empty($_GET['error'])) {
                        echo "<div class='alerta alerta-error'>" . htmlspecialchars($_GET['error']) . "</div>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </main>
    <script src="../js/editar_alumno.js"></script>
</body>
</html>