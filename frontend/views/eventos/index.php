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
    $where = " WHERE e.titulo LIKE '%$busqueda%' OR e.descripcion LIKE '%$busqueda%' OR c.nombre LIKE '%$busqueda%'";
}

// Obtener eventos
$query = "SELECT e.*, c.nombre as categoria_nombre, 
                 COUNT(DISTINCT p.ponente_id) as num_ponentes,
                 COUNT(DISTINCT r.registro_id) as num_registros
          FROM eventos e
          LEFT JOIN categorias_evento c ON e.categoria_id = c.categoria_id
          LEFT JOIN eventos_ponentes ep ON e.evento_id = ep.evento_id
          LEFT JOIN ponentes p ON ep.ponente_id = p.ponente_id
          LEFT JOIN registros r ON e.evento_id = r.evento_id
          $where
          GROUP BY e.evento_id
          ORDER BY e.fecha_evento DESC
          LIMIT $inicio, $por_pagina";

$eventos = mysqli_query($conn, $query);

// Obtener total de eventos para paginación
$query_total = "SELECT COUNT(DISTINCT e.evento_id) as total 
                FROM eventos e 
                LEFT JOIN categorias_evento c ON e.categoria_id = c.categoria_id
                $where";
$result_total = mysqli_query($conn, $query_total);
$total_eventos = mysqli_fetch_assoc($result_total)['total'];
$total_paginas = ceil($total_eventos / $por_pagina);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eventos - Sistema de Gestión de Eventos Académicos</title>
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
                <h1 class="h2">Eventos</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="crear.php" class="btn btn-primary">
                        <i class="bi bi-plus-lg"></i> Nuevo Evento
                    </a>
                </div>
            </div>

            <!-- Buscador -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <form action="" method="GET" class="d-flex">
                        <input type="text" name="buscar" class="form-control me-2" placeholder="Buscar eventos..." value="<?php echo htmlspecialchars($busqueda); ?>">
                        <button type="submit" class="btn btn-outline-primary">Buscar</button>
                    </form>
                </div>
            </div>

            <!-- Lista de Eventos -->
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Imagen</th>
                            <th>Título</th>
                            <th>Categoría</th>
                            <th>Fecha</th>
                            <th>Ponentes</th>
                            <th>Registros</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($evento = mysqli_fetch_assoc($eventos)): 
                            $fecha_evento = strtotime($evento['fecha_evento']);
                            $hoy = strtotime('today');
                            $estado = $fecha_evento >= $hoy ? 'Próximo' : 'Finalizado';
                        ?>
                        <tr>
                            <td>
                                <?php if (!empty($evento['imagen'])): ?>
                                    <img src="<?php echo htmlspecialchars($evento['imagen']); ?>" 
                                         class="rounded" style="width: 60px; height: 60px; object-fit: cover; border: 1px solid #dee2e6;" 
                                         alt="<?php echo htmlspecialchars($evento['titulo']); ?>">
                                <?php else: ?>
                                    <div class="rounded bg-secondary text-white d-flex align-items-center justify-content-center" 
                                         style="width: 60px; height: 60px; border: 1px solid #dee2e6;">
                                        <i class="bi bi-calendar-event" style="font-size: 1.5rem;"></i>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div>
                                    <h6 class="mb-0"><?php echo htmlspecialchars($evento['titulo']); ?></h6>
                                    <small class="text-muted"><?php echo htmlspecialchars(substr($evento['descripcion'], 0, 50)) . '...'; ?></small>
                                </div>
                            </td>
                            <td><?php echo htmlspecialchars($evento['categoria_nombre']); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($evento['fecha_evento'])); ?></td>
                            <td>
                                <span class="badge bg-info"><?php echo $evento['num_ponentes']; ?></span>
                            </td>
                            <td>
                                <span class="badge bg-success"><?php echo $evento['num_registros']; ?></span>
                            </td>
                            <td>
                                <span class="badge bg-<?php echo $estado == 'Próximo' ? 'primary' : 'secondary'; ?>">
                                    <?php echo $estado; ?>
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="ver.php?id=<?php echo $evento['evento_id']; ?>" 
                                       class="btn btn-sm btn-outline-info" title="Ver detalles">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="editar.php?id=<?php echo $evento['evento_id']; ?>" 
                                       class="btn btn-sm btn-outline-primary" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-danger" 
                                            onclick="confirmarEliminacion(<?php echo $evento['evento_id']; ?>)" 
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
function confirmarEliminacion(eventoId) {
    if (confirm('¿Estás seguro de que deseas eliminar este evento?')) {
        window.location.href = 'eliminar.php?id=' + eventoId;
    }
}
</script>
</body>
</html>
