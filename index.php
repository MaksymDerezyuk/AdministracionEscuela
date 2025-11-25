<?php
session_start();

if (!isset($_SESSION['logeado']) || $_SESSION['logeado'] !== true) {
    header('Location: ./view/login.php');
    exit();
}

require_once './conexion/connection.php';

$usuario = $_SESSION['user_nombre'];

// --- Lógica para Filtros y Paginación ---
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

// Construir Consulta
$sql = "SELECT * FROM tbl_alumnos WHERE 1=1";
$params = [];

if (!empty($search)) {
    $sql .= " AND (nombre LIKE :search1 OR apellido1 LIKE :search2 OR apellido2 LIKE :search3 OR dni LIKE :search4 OR email LIKE :search5)";
    $searchTerm = "%$search%";
    $params[':search1'] = $searchTerm;
    $params[':search2'] = $searchTerm;
    $params[':search3'] = $searchTerm;
    $params[':search4'] = $searchTerm;
    $params[':search5'] = $searchTerm;
}

// Contar total para paginación
$sqlCount = str_replace("SELECT *", "SELECT COUNT(*)", $sql);
$stmtCount = $conn->prepare($sqlCount);
$stmtCount->execute($params);
$totalRecords = $stmtCount->fetchColumn();
$totalPages = ceil($totalRecords / $limit);

// Añadir Límite y Desplazamiento
$sql .= " LIMIT :limit OFFSET :offset";
$stmt = $conn->prepare($sql);
foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value);
}
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - Gestio-Notes</title>
    <link rel="stylesheet" href="./css/style.css">
    <!-- FontAwesome para iconos (opcional pero recomendado) -->
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

        <!-- Filtros y Acciones -->
        <div class="filters">
            <form action="" method="GET" style="display: flex; gap: 1rem; align-items: center; flex-wrap: wrap; width: 100%;">
                <div style="flex-grow: 1;">
                    <input type="text" name="search" placeholder="Buscar por nombre, DNI o email..." value="<?php echo htmlspecialchars($search); ?>" style="width: 100%;">
                </div>
                <div>
                    <label for="limit">Mostrar:</label>
                    <select name="limit" id="limit" onchange="this.form.submit()">
                        <option value="5" <?php if ($limit == 5) echo 'selected'; ?>>5</option>
                        <option value="10" <?php if ($limit == 10) echo 'selected'; ?>>10</option>
                        <option value="20" <?php if ($limit == 20) echo 'selected'; ?>>20</option>
                        <option value="50" <?php if ($limit == 50) echo 'selected'; ?>>50</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Filtrar</button>
                <?php if (!empty($search)): ?>
                    <a href="index.php" class="btn btn-warning">Limpiar</a>
                <?php endif;

                if ($_SESSION['user_rol'] == 'administrador') {
                    echo '<a href="./view/crear_alumno.php" class="btn btn-primary"><i class="fas fa-plus"></i> Nuevo Alumno</a>';
                }
                ?>
                <div style="margin-left: auto;">
                    <a href="./view/estadisticas.php" class="btn btn-info"><i class="fas fa-chart-bar"></i> Estadísticas</a>
                </div>
            </form>
        </div>

        <!-- Tabla -->
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
                        <?php foreach ($result as $row): ?>
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

        <!-- Paginación -->
        <?php if ($totalPages > 1): ?>
            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="?page=<?php echo $page - 1; ?>&limit=<?php echo $limit; ?>&search=<?php echo urlencode($search); ?>">&laquo; Anterior</a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="?page=<?php echo $i; ?>&limit=<?php echo $limit; ?>&search=<?php echo urlencode($search); ?>" class="<?php if ($i == $page) echo 'active'; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>

                <?php if ($page < $totalPages): ?>
                    <a href="?page=<?php echo $page + 1; ?>&limit=<?php echo $limit; ?>&search=<?php echo urlencode($search); ?>">Siguiente &raquo;</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    </div>

</body>

</html>