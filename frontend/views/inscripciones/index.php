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
    $where = " WHERE e.titulo LIKE '%$busqueda%' OR CONCAT(p.nombre, ' ', p.apellido) LIKE '%$busqueda%'";
}

// Obtener registros
$query = "SELECT r.*, 
                 e.titulo as evento_titulo,
                 e.fecha_evento,
                 e.hora_inicio,
                 e.hora_fin,
                 e.ubicacion,
                 CONCAT(p.nombre, ' ', p.apellido) as participante_nombre,
                 p.email as participante_email
          FROM registros r
          JOIN eventos e ON r.evento_id = e.evento_id
          JOIN participantes p ON r.participante_id = p.participante_id
          $where
          ORDER BY r.fecha_registro DESC
          LIMIT $inicio, $por_pagina";

$registros = mysqli_query($conn, $query);

// Obtener total de registros para paginación
$query_total = "SELECT COUNT(*) as total 
                FROM registros r
                JOIN eventos e ON r.evento_id = e.evento_id
                JOIN participantes p ON r.participante_id = p.participante_id
                $where";
$result_total = mysqli_query($conn, $query_total);
$total_registros = mysqli_fetch_assoc($result_total)['total'];
$total_paginas = ceil($total_registros / $por_pagina);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registros - Sistema de Gestión de Eventos Académicos</title>
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
                <h1 class="h2">Registros de Eventos</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="crear.php" class="btn btn-primary">
                        <i class="bi bi-plus-lg"></i> Nuevo Registro
                    </a>
                </div>
            </div>

            <!-- Buscador -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <form action="" method="GET" class="d-flex">
                        <input type="text" name="buscar" class="form-control me-2" placeholder="Buscar por evento o participante..." value="<?php echo htmlspecialchars($busqueda); ?>">
                        <button type="submit" class="btn btn-outline-primary">Buscar</button>
                    </form>
                </div>
            </div>

            <!-- Lista de Registros -->
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Evento</th>
                            <th>Participante</th>
                            <th>Fecha y Hora</th>
                            <th>Estado de Pago</th>
                            <th>Asistencia</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($registro = mysqli_fetch_assoc($registros)): ?>
                        <tr>
                            <td>
                                <div>
                                    <h6 class="mb-0"><?php echo htmlspecialchars($registro['evento_titulo']); ?></h6>
                                    <small class="text-muted">
                                        <?php echo date('d/m/Y', strtotime($registro['fecha_evento'])); ?> 
                                        <?php echo $registro['hora_inicio']; ?> - <?php echo $registro['hora_fin']; ?>
                                    </small>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <h6 class="mb-0"><?php echo htmlspecialchars($registro['participante_nombre']); ?></h6>
                                    <small class="text-muted"><?php echo htmlspecialchars($registro['participante_email']); ?></small>
                                </div>
                            </td>
                            <td><?php echo date('d/m/Y H:i', strtotime($registro['fecha_registro'])); ?></td>
                            <td>
                                <span class="badge bg-<?php 
                                    echo $registro['estado_pago'] === 'completado' ? 'success' : 
                                        ($registro['estado_pago'] === 'pendiente' ? 'warning' : 'danger'); 
                                ?>">
                                    <?php echo ucfirst($registro['estado_pago']); ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-<?php 
                                    echo $registro['asistencia'] === 'confirmada' ? 'success' : 
                                        ($registro['asistencia'] === 'pendiente' ? 'warning' : 'secondary'); 
                                ?>">
                                    <?php echo ucfirst($registro['asistencia'] ?? 'pendiente'); ?>
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="ver.php?id=<?php echo $registro['registro_id']; ?>" 
                                       class="btn btn-sm btn-outline-info" title="Ver detalles">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="editar.php?id=<?php echo $registro['registro_id']; ?>" 
                                       class="btn btn-sm btn-outline-primary" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-danger" 
                                            onclick="confirmarEliminacion(<?php echo $registro['registro_id']; ?>)" 
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
function confirmarEliminacion(registroId) {
    if (confirm('¿Estás seguro de que deseas eliminar este registro?')) {
        window.location.href = 'eliminar.php?id=' + registroId;
    }
}
</script>
</body>
</html> 