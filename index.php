<?php
session_start();

if (!isset($_SESSION['logeado']) || $_SESSION['logeado'] !== true) {
    header('Location: ./view/login.php');
    exit();
}

require_once './conexion/connection.php';

$usuario = $_SESSION['user_nombre'];

// --- Logic for Filters and Pagination ---
$search_nombre = isset($_GET['search_nombre']) ? trim($_GET['search_nombre']) : '';
$search_apellido = isset($_GET['search_apellido']) ? trim($_GET['search_apellido']) : '';
$search_dni = isset($_GET['search_dni']) ? trim($_GET['search_dni']) : '';
$search_email = isset($_GET['search_email']) ? trim($_GET['search_email']) : '';
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

// Build Query
$sql = "SELECT * FROM tbl_alumnos WHERE 1=1";
$params = [];

if (!empty($search_nombre)) {
    $sql .= " AND nombre LIKE :nombre";
    $params[':nombre'] = "%$search_nombre%";
}
if (!empty($search_apellido)) {
    $sql .= " AND (apellido1 LIKE :apellido1 OR apellido2 LIKE :apellido2)";
    $params[':apellido1'] = "%$search_apellido%";
    $params[':apellido2'] = "%$search_apellido%";
}
if (!empty($search_dni)) {
    $sql .= " AND dni LIKE :dni";
    $params[':dni'] = "%$search_dni%";
}
if (!empty($search_email)) {
    $sql .= " AND email LIKE :email";
    $params[':email'] = "%$search_email%";
}

// Count total for pagination
$sqlCount = str_replace("SELECT *", "SELECT COUNT(*)", $sql);
$stmtCount = $conn->prepare($sqlCount);
$stmtCount->execute($params);
$totalRecords = $stmtCount->fetchColumn();
$totalPages = ceil($totalRecords / $limit);

// Add Limit and Offset
$sql .= " LIMIT :limit OFFSET :offset";
$stmt = $conn->prepare($sql);
foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value);
}
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Helper for pagination links
$queryParams = http_build_query([
    'search_nombre' => $search_nombre,
    'search_apellido' => $search_apellido,
    'search_dni' => $search_dni,
    'search_email' => $search_email,
    'limit' => $limit
]);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - Gestio-Notes</title>
    <link rel="stylesheet" href="./css/style.css">
    <!-- FontAwesome for icons (optional but recommended) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

<div class="dashboard-container">
    <div class="dashboard-header">
        <div>
            <h1>Bienvenido, <?php echo htmlspecialchars($usuario); ?></h1>
            <p>Gestión de Alumnos y Notas</p>
        </div>
        <div>
            <a href="./proc/logout.php" class="btn btn-danger">Cerrar Sesión</a>
        </div>
    </div>

    <!-- Filters & Actions -->
    <div class="filters">
        <form action="" method="GET" style="display: flex; flex-direction: column; gap: 1rem; width: 100%;">
            
            <!-- Top Row: Search Fields -->
            <div style="display: flex; gap: 1rem; flex-wrap: wrap; width: 100%;">
                <div style="flex: 1 1 150px; min-width: 150px;">
                    <label for="search_nombre" style="display: block; font-size: 0.8rem; margin-bottom: 0.2rem;">Nombre:</label>
                    <input type="text" name="search_nombre" id="search_nombre" placeholder="Buscar por nombre..." value="<?php echo htmlspecialchars($search_nombre); ?>" style="width: 100%;">
                </div>
                <div style="flex: 1 1 150px; min-width: 150px;">
                    <label for="search_apellido" style="display: block; font-size: 0.8rem; margin-bottom: 0.2rem;">Apellidos:</label>
                    <input type="text" name="search_apellido" id="search_apellido" placeholder="Buscar por apellidos..." value="<?php echo htmlspecialchars($search_apellido); ?>" style="width: 100%;">
                </div>
                <div style="flex: 1 1 150px; min-width: 150px;">
                    <label for="search_dni" style="display: block; font-size: 0.8rem; margin-bottom: 0.2rem;">DNI:</label>
                    <input type="text" name="search_dni" id="search_dni" placeholder="Buscar por DNI..." value="<?php echo htmlspecialchars($search_dni); ?>" style="width: 100%;">
                </div>
                <div style="flex: 1 1 200px; min-width: 200px;">
                    <label for="search_email" style="display: block; font-size: 0.8rem; margin-bottom: 0.2rem;">Email:</label>
                    <input type="text" name="search_email" id="search_email" placeholder="Buscar por email..." value="<?php echo htmlspecialchars($search_email); ?>" style="width: 100%;">
                </div>
            </div>

            <!-- Bottom Row: Limit + Buttons -->
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                
                <div style="display: flex; align-items: center; gap: 1rem; flex-wrap: wrap;">
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <label for="limit" style="font-size: 0.8rem; margin: 0;">Mostrar:</label>
                        <select name="limit" id="limit" onchange="this.form.submit()" style="min-width: 80px;">
                            <option value="5" <?php if($limit == 5) echo 'selected'; ?>>5</option>
                            <option value="10" <?php if($limit == 10) echo 'selected'; ?>>10</option>
                            <option value="20" <?php if($limit == 20) echo 'selected'; ?>>20</option>
                            <option value="50" <?php if($limit == 50) echo 'selected'; ?>>50</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Filtrar</button>
                    <?php if(!empty($search_nombre) || !empty($search_apellido) || !empty($search_dni) || !empty($search_email)): ?>
                        <a href="index.php" class="btn btn-warning">Limpiar</a>
                    <?php endif; ?>
                </div>

                <div style="display: flex; gap: 1rem;">
                    <a href="./view/crear_alumno.php" class="btn btn-primary"><i class="fas fa-plus"></i> Nuevo Alumno</a>
                    <a href="./view/estadisticas.php" class="btn btn-info"><i class="fas fa-chart-bar"></i> Estadísticas</a>
                </div>
            </div>

        </form>
    </div>

    <!-- Table -->
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>DNI</th>
                    <th>Nombre</th>
                    <th>Apellidos</th>
                    <th>Email</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($result) > 0): ?>
                    <?php foreach($result as $row): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['dni']); ?></td>
                            <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                            <td><?php echo htmlspecialchars($row['apellido1'] . ' ' . $row['apellido2']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td class="actions">
                                <a href="./view/ver_alumno.php?id=<?php echo $row['id']; ?>" class="btn btn-info" title="Ver Notas"><i class="fas fa-eye"></i></a>
                                <a href="./view/editar_alumno.php?id=<?php echo $row['id']; ?>" class="btn btn-warning" title="Editar"><i class="fas fa-edit"></i></a>
                                <a href="./proc/proc_eliminar_alumno.php?id=<?php echo $row['id']; ?>" class="btn btn-danger" onclick="return confirm('¿Estás seguro de eliminar este alumno y todas sus notas?');" title="Eliminar"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="text-align: center;">No se encontraron alumnos.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?php echo $page - 1; ?>&<?php echo $queryParams; ?>">&laquo; Anterior</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?page=<?php echo $i; ?>&<?php echo $queryParams; ?>" class="<?php if ($i == $page) echo 'active'; ?>">
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>

            <?php if ($page < $totalPages): ?>
                <a href="?page=<?php echo $page + 1; ?>&<?php echo $queryParams; ?>">Siguiente &raquo;</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>

</div>

</body>
</html>