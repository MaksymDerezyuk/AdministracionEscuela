<?php
session_start();

if (!isset($_SESSION['logeado']) || $_SESSION['logeado'] !== true) {
    header('Location: ./login.php');
    exit();
} else {
    if (!isset($_GET['id'])) {
        header('Location: ./index.php?error=Necesitas el id del alumno para ver sus notas');
        exit();
    } else {
        require_once '../conexion/connection.php';
        $id_alumno = $_GET['id'];
        // Obtenemos el nombre del alumno
        $sql_nombre = "SELECT nombre FROM tbl_alumnos WHERE id = ?";
        $stmt_nombre = $conn->prepare($sql_nombre);
        $stmt_nombre->execute([$id_alumno]);
        $alumno_nombre = $stmt_nombre->fetchColumn();
        if (!$alumno_nombre) {
            header('Location: ../index.php?error=El alumno no existe');
            exit();
        }
        // Obtener las notas del alumno
        $sql = "SELECT  tas.nombre AS asignatura, tn.nota as nota, tg.nombre AS profesor FROM tbl_alumnos ta INNER JOIN tbl_notas tn ON ta.id = tn.id_alumno Inner Join tbl_asignaturas tas ON tn.id_asignatura = tas.id INNER JOIN tbl_profesor_asignatura tpa on tas.id = tpa.id_asignatura INNER JOIN tbl_gestores tg ON tpa.id_profesor = tg.id WHERE ta.id = ? and tn.nota IS NOT NULL";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id_alumno]);
        $alumno = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($_SESSION['user_rol'] === 'profesor') {
            // Comprobamos si el alumno esta matriculado en alguna asignatura del profesor logueado
            $profesor_id = $_SESSION['user_id'];
            $sql_check = "SELECT COUNT(*) FROM tbl_alumnos ta INNER JOIN tbl_matriculas tm
            ON ta.id = tm.id_alumno
            INNER JOIN tbl_grados tg ON tm.id_grado = tg.id
            INNER JOIN tbl_asignaturas tas ON tg.id = tas.id_grado
            INNER JOIN tbl_profesor_asignatura tpa ON tas.id = tpa.id_asignatura
            WHERE ta.id = ? AND tpa.id_profesor = ?";
            $stmt_check = $conn->prepare($sql_check);
            $stmt_check->execute([$id_alumno, $profesor_id]);
            $alumno_count = $stmt_check->fetchColumn();
            if ($alumno_count == 0) {
                header('Location: ../index.php?error=No tienes permiso para ver las notas de este alumno');
                exit();
            }
        }
?>
        <!DOCTYPE html>
        <html lang="es">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Ver Notas de <?php echo htmlspecialchars($alumno[0]['nombre']); ?></title>
            <link rel="stylesheet" href="../css/style.css">
        </head>

        <body>
            <div class="dashboard-container">
                <div class="dashboard-header">
                    <div>
                        <a class="btn btn-primary" href="../index.php">Volver al inicio</a>
                        <h1>Bienvenido, <?php echo $_SESSION['user_nombre']; ?></h1>
                        <p>Gestión de Alumnos y Notas</p>
                    </div>
                    <div>
                        <?php if ($_SESSION['user_rol'] === 'profesor'): ?>
                            <a class="btn btn-primary" href="./poner_nota.php?id=<?php echo htmlspecialchars($_GET['id']); ?>">Añadir nota</a>
                        <?php endif; ?>
                        <a href="../proc/logout.php" class="btn btn-danger">Cerrar Sesión</a>
                    </div>
                </div>
                <h1>Notas</h1>
                <h2 class="centrar">Alumno/a: <?php echo htmlspecialchars($alumno_nombre); ?></h2>
                <?php if (count($alumno) > 0): ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="centrar">Asignatura</th>
                                    <th class="centrar">Profesor</th>
                                    <th class="centrar">Nota</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($alumno as $nota): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($nota['asignatura']); ?></td>
                                        <td><?php echo htmlspecialchars($nota['profesor']); ?></td>
                                        <td><?php echo htmlspecialchars($nota['nota']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p>No tiene notas registradas.</p>
                <?php endif; ?>
        </body>

        </html>
<?php
    }
}
?>