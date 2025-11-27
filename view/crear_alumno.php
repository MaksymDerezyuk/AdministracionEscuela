<?php
require_once('../conexion/connection.php');

$stmt = $conn->prepare("SELECT id, nombre FROM tbl_grados");
$stmt->execute();
$grados = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Alumno</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<!-- Botón Volver al Panel de Administración -->
<a href="../index.php" class="btn-volver">&larr; Volver al Panel</a>

<div class="auth-split">
    <div class="auth-left">
        <img src="../img/uni.png" alt="Logo Escuela">
    </div>
    <div class="auth-right">
        <div class="tarjeta">
            <h1>Registrar Alumno</h1>
            <?php
            if(isset($_GET['msg'])){
                $msg = htmlspecialchars($_GET['msg']);
                $tipo = isset($_GET['tipo']) && $_GET['tipo'] === 'exito' ? 'alerta-exito' : 'alerta-error';
                echo "<div class='alerta $tipo'>$msg</div>";
            }
            ?>
            <form class="formulario" action="../proc/proc_crear_alumno.php" method="POST">
                <label for="dni">DNI:</label>
                <input type="text" name="dni" id="dni" required maxlength="15">

                <label for="nombre">Nombre:</label>
                <input type="text" name="nombre" id="nombre" required maxlength="50">

                <label for="apellido1">Primer Apellido:</label>
                <input type="text" name="apellido1" id="apellido1" required maxlength="50">

                <label for="apellido2">Segundo Apellido:</label>
                <input type="text" name="apellido2" id="apellido2" maxlength="50">

                <label for="email">Email:</label>
                <input type="email" name="email" id="email" maxlength="100">

                <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
                <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" required>

                <label for="grado">Grado:</label>
                <select name="grado" id="grado" required>
                    <option value="">Selecciona un grado</option>
                    <?php foreach($grados as $grado): ?>
                        <option value="<?= $grado['id'] ?>"><?= htmlspecialchars($grado['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>


                <button type="submit">Registrar Alumno</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
