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

    // Filtro PHP para eliminar empates (Solo el primer alumno)
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
    <title>Estadísticas</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <div class="dashboard-container">
        
        <div class="dashboard-header">
            <div>
                <h1><i class="fas fa-chart-line"></i> Análisis de Resultados</h1>
            </div>
            <div>
                <a href="index.php" class="btn btn-primary">Volver al Listado</a>
            </div>
        </div>

        <div class="kpi-container">
            <div class="card-kpi card-verde">
                <div class="kpi-titulo">Materia con Mejor Media</div>
                <?php if ($mejorMateria): ?>
                    <div class="kpi-valor" style="color: #2e7d32;">
                        <?php echo htmlspecialchars($mejorMateria['nombre']); ?>
                    </div>
                    <div>Media: <?php echo number_format($mejorMateria['media_nota'], 2); ?></div>
                <?php else: ?>
                    <p>Sin datos</p>
                <?php endif; ?>
            </div>

            <div class="card-kpi card-azul">
                <div class="kpi-titulo">Total Exámenes Corregidos</div>
                <div class="kpi-valor" style="color: #005A9C;">
                    <?php 
                        $total = 0;
                        foreach($medias as $m) $total += $m['num_evaluaciones'];
                        echo $total;
                    ?>
                </div>
            </div>
        </div>

        <h2>📉 Rendimiento Medio por Asignatura</h2>
        <div class="tarjeta-amplia" style="margin-bottom: 30px;">
            <div class="lista-progreso">
                <?php foreach ($medias as $m): ?>
                    <?php 
                        $nota = $m['media_nota'];
                        // Calculamos el % de ancho (La nota sobre 10 multiplicada por 10)
                        $anchoBarra = $nota * 10; 
                        
                        // Color según la nota
                        $claseColor = 'bg-suspenso';
                        if ($nota >= 9) $claseColor = 'bg-excelente';
                        else if ($nota >= 7) $claseColor = 'bg-bien';
                        else if ($nota >= 5) $claseColor = 'bg-regular';
                    ?>
                    
                    <div class="item-progreso">
                        <div class="nombre-asignatura">
                            <?php echo htmlspecialchars($m['nombre']); ?>
                        </div>
                        <div class="contenedor-barra">
                            <div class="barra-relleno <?php echo $claseColor; ?>" style="width: <?php echo $anchoBarra; ?>%;">
                            </div>
                        </div>
                        <div class="nota-numero">
                            <?php echo number_format($nota, 2); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <h2>🏆 Mejores Alumnos</h2>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Asignatura</th>
                        <th>Alumno Destacado</th>
                        <th>Nota</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($topAlumnos as $top): ?>
                        <tr>
                            <td><b><?php echo htmlspecialchars($top['asignatura']); ?></b></td>
                            <td>
                                <i class="fas fa-medal" style="color: #ffc107;"></i>
                                <?php echo htmlspecialchars($top['nombre'] . ' ' . $top['apellido1']); ?>
                            </td>
                            <td style="font-weight: bold; color: green;">
                                <?php echo number_format($top['nota'], 2); ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

    </div>

</body>
</html>