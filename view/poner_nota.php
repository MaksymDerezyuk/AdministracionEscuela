<?php
session_start();
if (!isset($_SESSION['logeado']) || $_SESSION['logeado'] !== true) {
    header('Location: ./login.php');
    exit();
}
require_once '../conexion/connection.php';
$profesor_id = $_SESSION['user_id'];
$alumno_id = $_GET['id'];


//comoprobar que el alumno este matriculado en alguna asignatura del profesor logueado
$sql_check = "SELECT COUNT(*) FROM tbl_alumnos ta INNER JOIN tbl_matriculas tm
    ON ta.id = tm.id_alumno
    INNER JOIN tbl_grados tg ON tm.id_grado = tg.id
    INNER JOIN tbl_asignaturas tas ON tg.id = tas.id_grado
    INNER JOIN tbl_profesor_asignatura tpa ON tas.id = tpa.id_asignatura
    WHERE ta.id = ? AND tpa.id_profesor = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->execute([$alumno_id, $profesor_id]);
$alumno_count = $stmt_check->fetchColumn();
if ($alumno_count == 0) {
    header('Location: ../index.php?id=' . urlencode($alumno_id) . '&error=No tienes permiso para poner nota a este alumno');
    exit();
}
// Obtener el nombre del alumno
$sql_alumno = "SELECT ta.nombre, ta.apellido1, ta.apellido2, tas.curso FROM tbl_alumnos ta INNER JOIN tbl_matriculas tm ON ta.id = tm.id_alumno INNER JOIN tbl_grados tg ON tm.id_grado=tg.id INNER JOIN tbl_asignaturas tas ON tg.id = tas.id_grado WHERE ta.id = ?";
$stmt_alumno = $conn->prepare($sql_alumno);
$stmt_alumno->execute([$alumno_id]);
$alumno = $stmt_alumno->fetch(PDO::FETCH_ASSOC);

$curso = $alumno['curso'];

// Obtener las asignaturas asociadas al profesor logueado
$sql_asignatura_profesor = "SELECT tas.id, tas.nombre FROM tbl_asignaturas tas INNER JOIN tbl_profesor_asignatura tpa ON tas.id = tpa.id_asignatura WHERE tpa.id_profesor = ?";
$stmt = $conn->prepare($sql_asignatura_profesor);
$stmt->execute([$profesor_id]);
$asignaturas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener las convocatorias existentes
$sql_notas = "SELECT DISTINCT convocatoria FROM tbl_notas where convocatoria IS NOT NULL ORDER BY convocatoria ASC";
$stmt_notas = $conn->prepare($sql_notas);
$stmt_notas->execute();
$notas = $stmt_notas->fetchAll(PDO::FETCH_ASSOC);



?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Poner Nota</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <main class="contenedor">
        <div class="auth-split">
            <div class="auth-right">
                <div class="tarjeta tarjeta-wide">
                    <h1>Poner Nota a </h1>
                    <h1><?php echo htmlspecialchars($alumno['nombre'] . ' ' . $alumno['apellido1'] . ' ' . $alumno['apellido2']); ?></h1>
                    <form action="../proc/proc_insert_nota.php" method="post" class="formulario formulario-grid">
                        <label>
                            Asignatura:
                            <select name="id_asignatura" id="id_asignatura">
                                <option value="" disabled selected>Seleccione una asignatura</option>
                                <?php foreach ($asignaturas as $asignatura): ?>
                                    <option value="<?php echo htmlspecialchars($asignatura['id']); ?>">
                                        <?php echo htmlspecialchars($asignatura['nombre']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <p id="error_asignatura"></p>
                        </label>
                        <label>
                            Convocatoria:
                            <select name="convocatoria" id="convocatoria">
                                <option value="" selected disabled>Seleccione una convocatoria</option>
                                <?php
                                foreach ($notas as $nota) {
                                    echo '<option value="' . htmlspecialchars($nota['convocatoria']) . '">' . htmlspecialchars($nota['convocatoria']) . '</option>';
                                }
                                ?>
                            </select>
                            <p id="error_convocatoria"></p>
                        </label>
                        <label>
                            Nota:
                            <input type="number" id="nota" name="nota" step="0.01">
                            <p id="error_nota"></p>
                        </label>
                        <input type="hidden" id="curso_alumno" name="curso_alumno" value="<?php echo htmlspecialchars($curso); ?>">
                        <input type="hidden" id="id_alumno" name="id_alumno" value="<?php echo htmlspecialchars($alumno_id); ?>">

                        <button type="submit" id="btn_enviar" class="btn btn-primary">Guardar Nota</button>
                        <?php
                        if (isset($_GET['error'])) {
                            echo '<p class="alerta alerta-error">' . htmlspecialchars($_GET['error']) . '</p>';
                        }
                        ?>
                        <a href="./ver_alumno.php?id=<?php echo htmlspecialchars($alumno_id); ?>" class="btn btn-primary">Volver al alumno</a>
                    </form>
                </div>
            </div>
        </div>
    </main>
    <script src="../js/poner_notas.js"></script>
</body>

</html>