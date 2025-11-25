<?php
session_start();

// Seguridad
if (!isset($_SESSION['logeado']) || $_SESSION['logeado'] !== true) {
    header('Location: ./login.php');
    exit();
}

require_once '../conexion/connection.php';

try {
    // --- CONSULTA 1: MEDIAS ---
    $sqlMedias = "SELECT asig.nombre, AVG(n.nota) as media_nota, COUNT(n.id) as num_evaluaciones
                  FROM tbl_notas n
                  JOIN tbl_asignaturas asig ON n.id_asignatura = asig.id
                  GROUP BY asig.id, asig.nombre
                  ORDER BY media_nota DESC";
    $stmtMedias = $conn->query($sqlMedias);
    $medias = $stmtMedias->fetchAll(PDO::FETCH_ASSOC);

    $mejorMateria = (count($medias) > 0) ? $medias[0] : null;

    // --- CONSULTA 2: MEJORES ALUMNOS ---
    $sqlTop = "SELECT asig.nombre as asignatura, alu.nombre, alu.apellido1, n.nota
               FROM tbl_notas n
               JOIN tbl_asignaturas asig ON n.id_asignatura = asig.id
               JOIN tbl_alumnos alu ON n.id_alumno = alu.id
               WHERE (n.id_asignatura, n.nota) IN (
                    SELECT id_asignatura, MAX(nota) FROM tbl_notas GROUP BY id_asignatura
               )
               ORDER BY asig.nombre ASC, alu.id ASC";
    $stmtTop = $conn->query($sqlTop);
    $candidatos = $stmtTop->fetchAll(PDO::FETCH_ASSOC);

    // Filtro PHP para eliminar empates
    $topAlumnos = [];
    $asignaturasVistas = [];
    foreach ($candidatos as $alumno) {
        if (!in_array($alumno['asignatura'], $asignaturasVistas)) {
            $topAlumnos[] = $alumno;
            $asignaturasVistas[] = $alumno['asignatura'];
        }
    }

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estadísticas - Gestio-Notes</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <div class="dashboard-container">
        
        <div class="header-flex">
            <div class="header-title">
                <h1><i class="fas fa-chart-line"></i> Análisis de Resultados</h1>
                <p>Visión global del rendimiento académico</p>
            </div>
            <div>
                <a href="index.php" class="btn btn-primary">Volver al Listado</a>
            </div>
        </div>

        <div class="kpi-wrapper">
            <div class="kpi-card kpi-verde">
                <div class="kpi-label kpi-text-verde">🌟 Materia con mejor media</div>
                <?php if ($mejorMateria): ?>
                    <div class="kpi-value">
                        <?php echo htmlspecialchars($mejorMateria['nombre']); ?>
                    </div>
                    <div>Nota Media: <strong><?php echo number_format($mejorMateria['media_nota'], 2); ?></strong></div>
                <?php else: ?>
                    <p>No hay datos suficientes</p>
                <?php endif; ?>
            </div>

            <div class="kpi-card kpi-azul">
                <div class="kpi-label kpi-text-azul">📊 Total Exámenes</div>
                <div class="kpi-value">
                    <?php 
                        $total = 0;
                        foreach($medias as $m) $total += $m['num_evaluaciones'];
                        echo $total;
                    ?>
                </div>
                <div>Evaluaciones registradas</div>
            </div>
        </div>

        <h2>📉 Rendimiento Medio por Asignatura</h2>
        <div class="tarjeta card-full-width">
            <div class="progress-list">
                <?php foreach ($medias as $m): ?>
                    <?php 
                        $nota = $m['media_nota'];
                        $anchoBarra = $nota * 10; // Convertir 8.5 a 85%
                        
                        // Lógica de colores por clase CSS
                        $claseColor = 'bg-danger';
                        if ($nota >= 9) $claseColor = 'bg-success';
                        else if ($nota >= 7) $claseColor = 'bg-info';
                        else if ($nota >= 5) $claseColor = 'bg-warning';
                    ?>
                    
                    <div class="progress-item">
                        <div class="progress-label">
                            <?php echo htmlspecialchars($m['nombre']); ?>
                        </div>
                        <div class="progress-track">
                            <div class="progress-fill <?php echo $claseColor; ?>" style="width: <?php echo $anchoBarra; ?>%;">
                            </div>
                        </div>
                        <div class="progress-score">
                            <?php echo number_format($nota, 2); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <br>

        <h2>🏆 Cuadro de Honor (Mejores Alumnos)</h2>
        <div class="table-responsive">
            <table class="table">
                <thead class="table-header-honor">
                    <tr>
                        <th>Asignatura</th>
                        <th>Alumno Destacado</th>
                        <th>Nota Máxima</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($topAlumnos) > 0): ?>
                        <?php foreach ($topAlumnos as $top): ?>
                            <tr>
                                <td><b><?php echo htmlspecialchars($top['asignatura']); ?></b></td>
                                <td>
                                    <i class="fas fa-medal icon-medal"></i>
                                    <?php echo htmlspecialchars($top['nombre'] . ' ' . $top['apellido1']); ?>
                                </td>
                                <td class="text-success">
                                    <?php echo number_format($top['nota'], 2); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="3" class="text-center">No hay datos todavía.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </div>

</body>
</html>