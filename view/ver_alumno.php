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
        $sql = "SELECT ta.nombre AS nombre, tas.nombre AS asignatura, tn.nota as nota, tg.nombre AS profesor FROM tbl_alumnos ta INNER JOIN tbl_notas tn ON ta.id = tn.id_alumno Inner Join tbl_asignaturas tas ON tn.id_asignatura = tas.id INNER JOIN tbl_profesor_asignatura tpa on tas.id = tpa.id_asignatura INNER JOIN tbl_gestores tg ON tpa.id_profesor = tg.id WHERE ta.id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$id_alumno]);
        $alumno = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
                        <a href="./proc/logout.php" class="btn btn-danger">Cerrar Sesión</a>
                    </div>
                </div>
                <h1>Notas</h1>
                <?php if (count($alumno) > 0): ?>
                    <h2 class="centrar">Alumno/a: <?php echo htmlspecialchars($alumno[0]['nombre']); ?></h2>
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