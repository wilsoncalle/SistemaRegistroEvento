<?php
require_once '../../config/database.php';

// Crear conexión
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Verificar conexión
if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Configuración de paginación
$por_pagina = 10;
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$inicio = ($pagina - 1) * $por_pagina;

// Búsqueda
$busqueda = isset($_GET['buscar']) ? mysqli_real_escape_string($conn, $_GET['buscar']) : '';
$where = '';
if ($busqueda) {
    $where = " WHERE p.nombre LIKE '%$busqueda%' OR p.apellido LIKE '%$busqueda%' OR p.email LIKE '%$busqueda%' OR p.especializacion LIKE '%$busqueda%'";
}

// Obtener ponentes
$query = "SELECT p.*, 
                 COUNT(DISTINCT ep.evento_id) as num_eventos
          FROM ponentes p
          LEFT JOIN eventos_ponentes ep ON p.ponente_id = ep.ponente_id
          $where
          GROUP BY p.ponente_id
          ORDER BY p.nombre ASC
          LIMIT $inicio, $por_pagina";

$ponentes = mysqli_query($conn, $query);

// Obtener total de ponentes para paginación
$query_total = "SELECT COUNT(*) as total FROM ponentes p $where";
$result_total = mysqli_query($conn, $query_total);
$total_ponentes = mysqli_fetch_assoc($result_total)['total'];
$total_paginas = ceil($total_ponentes / $por_pagina);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ponentes - Sistema de Gestión de Eventos Académicos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../../css/styles.css">
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <?php include '../layouts/sidebar.php'; ?>

        <!-- Main content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Ponentes</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="crear.php" class="btn btn-primary">
                        <i class="bi bi-plus-lg"></i> Nuevo Ponente
                    </a>
                </div>
            </div>

            <!-- Buscador -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <form action="" method="GET" class="d-flex">
                        <input type="text" name="buscar" class="form-control me-2" placeholder="Buscar ponentes..." value="<?php echo htmlspecialchars($busqueda); ?>">
                        <button type="submit" class="btn btn-outline-primary">Buscar</button>
                    </form>
                </div>
            </div>

            <!-- Lista de Ponentes -->
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Foto</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th>Especialización</th>
                            <th>Eventos</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($ponente = mysqli_fetch_assoc($ponentes)): ?>
                        <tr>
                            <td>
                                <?php if (!empty($ponente['imagen_perfil'])): ?>
                                    <img src="<?php echo htmlspecialchars($ponente['imagen_perfil']); ?>" 
                                         class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;" 
                                         alt="<?php echo htmlspecialchars($ponente['nombre'] . ' ' . $ponente['apellido']); ?>">
                                <?php else: ?>
                                    <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center" 
                                         style="width: 40px; height: 40px;">
                                        <i class="bi bi-person"></i>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div>
                                    <h6 class="mb-0"><?php echo htmlspecialchars($ponente['nombre'] . ' ' . $ponente['apellido']); ?></h6>
                                    <small class="text-muted"><?php echo htmlspecialchars(substr($ponente['biografia'], 0, 50)) . '...'; ?></small>
                                </div>
                            </td>
                            <td><?php echo htmlspecialchars($ponente['email']); ?></td>
                            <td><?php echo htmlspecialchars($ponente['telefono']); ?></td>
                            <td><?php echo htmlspecialchars($ponente['especializacion']); ?></td>
                            <td>
                                <span class="badge bg-info"><?php echo $ponente['num_eventos']; ?></span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="ver.php?id=<?php echo $ponente['ponente_id']; ?>" 
                                       class="btn btn-sm btn-outline-info" title="Ver detalles">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="editar.php?id=<?php echo $ponente['ponente_id']; ?>" 
                                       class="btn btn-sm btn-outline-primary" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-danger" 
                                            onclick="confirmarEliminacion(<?php echo $ponente['ponente_id']; ?>)" 
                                            title="Eliminar">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <?php if ($total_paginas > 1): ?>
            <nav aria-label="Page navigation" class="mt-4">
                <ul class="pagination justify-content-center">
                    <li class="page-item <?php echo $pagina <= 1 ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?pagina=<?php echo $pagina - 1; ?><?php echo $busqueda ? '&buscar=' . urlencode($busqueda) : ''; ?>">
                            Anterior
                        </a>
                    </li>
                    <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                        <li class="page-item <?php echo $pagina == $i ? 'active' : ''; ?>">
                            <a class="page-link" href="?pagina=<?php echo $i; ?><?php echo $busqueda ? '&buscar=' . urlencode($busqueda) : ''; ?>">
                                <?php echo $i; ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?php echo $pagina >= $total_paginas ? 'disabled' : ''; ?>">
                        <a class="page-link" href="?pagina=<?php echo $pagina + 1; ?><?php echo $busqueda ? '&buscar=' . urlencode($busqueda) : ''; ?>">
                            Siguiente
                        </a>
                    </li>
                </ul>
            </nav>
            <?php endif; ?>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function confirmarEliminacion(ponenteId) {
    if (confirm('¿Estás seguro de que deseas eliminar este ponente?')) {
        window.location.href = 'eliminar.php?id=' + ponenteId;
    }
}
</script>
</body>
</html>
