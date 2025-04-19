<?php
// Incluir archivo de conexión a la base de datos
require_once '../../config/database.php';

// Crear conexión
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Verificar conexión
if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Obtener estadísticas
try {
    // Total de eventos
    $query = "SELECT COUNT(*) as total FROM eventos";
    $result = mysqli_query($conn, $query);
    $totalEventos = mysqli_fetch_assoc($result)['total'];

    // Eventos activos (futuros)
    $query = "SELECT COUNT(*) as total FROM eventos WHERE fecha_evento >= CURDATE()";
    $result = mysqli_query($conn, $query);
    $eventosActivos = mysqli_fetch_assoc($result)['total'];

    // Total de ponentes
    $query = "SELECT COUNT(*) as total FROM ponentes";
    $result = mysqli_query($conn, $query);
    $totalPonentes = mysqli_fetch_assoc($result)['total'];

    // Total de registros
    $query = "SELECT COUNT(*) as total FROM registros";
    $result = mysqli_query($conn, $query);
    $totalRegistros = mysqli_fetch_assoc($result)['total'];

    // Próximos eventos
    $query = "SELECT e.*, c.nombre as categoria_nombre 
             FROM eventos e 
             LEFT JOIN categorias_evento c ON e.categoria_id = c.categoria_id 
             WHERE e.fecha_evento >= CURDATE() 
             ORDER BY e.fecha_evento LIMIT 5";
    $proximosEventos = mysqli_query($conn, $query);

    // Últimos registros
    $query = "SELECT r.*, e.titulo as evento_titulo, 
             CONCAT(p.nombre, ' ', p.apellido) as participante_nombre 
             FROM registros r 
             JOIN eventos e ON r.evento_id = e.evento_id 
             JOIN participantes p ON r.participante_id = p.participante_id 
             ORDER BY r.fecha_registro DESC LIMIT 5";
    $ultimosRegistros = mysqli_query($conn, $query);

} catch(Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistema de Gestión de Eventos Académicos</title>
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
                <h1 class="h2">Dashboard</h1>
            </div>

            <!-- Estadísticas -->
            <div class="row g-4 mb-4">
                <!-- Total Eventos -->
                <div class="col-xl-3 col-md-6">
                    <div class="card stat-card">
                        <div class="card-body">
                            <h6 class="card-title">Total Eventos</h6>
                            <div class="d-flex align-items-center">
                                <h3 class="stat-value"><?php echo $totalEventos; ?></h3>
                                <i class="bi bi-calendar-event ms-auto"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Eventos Activos -->
                <div class="col-xl-3 col-md-6">
                    <div class="card stat-card">
                        <div class="card-body">
                            <h6 class="card-title">Eventos Activos</h6>
                            <div class="d-flex align-items-center">
                                <h3 class="stat-value"><?php echo $eventosActivos; ?></h3>
                                <i class="bi bi-check-circle ms-auto"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Ponentes -->
                <div class="col-xl-3 col-md-6">
                    <div class="card stat-card">
                        <div class="card-body">
                            <h6 class="card-title">Total Ponentes</h6>
                            <div class="d-flex align-items-center">
                                <h3 class="stat-value"><?php echo $totalPonentes; ?></h3>
                                <i class="bi bi-person-badge ms-auto"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Registros -->
                <div class="col-xl-3 col-md-6">
                    <div class="card stat-card">
                        <div class="card-body">
                            <h6 class="card-title">Total Registros</h6>
                            <div class="d-flex align-items-center">
                                <h3 class="stat-value"><?php echo $totalRegistros; ?></h3>
                                <i class="bi bi-clipboard-check ms-auto"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tablas de información -->
            <div class="row g-4">
                <!-- Próximos Eventos -->
                <div class="col-xl-6">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white py-3">
                            <h6 class="m-0 fw-bold">Próximos Eventos</h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Título</th>
                                            <th>Categoría</th>
                                            <th>Fecha</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($evento = mysqli_fetch_assoc($proximosEventos)): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($evento['titulo']); ?></td>
                                            <td><?php echo htmlspecialchars($evento['categoria_nombre']); ?></td>
                                            <td><?php echo date('d/m/Y', strtotime($evento['fecha_evento'])); ?></td>
                                        </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Últimos Registros -->
                <div class="col-xl-6">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white py-3">
                            <h6 class="m-0 fw-bold">Últimos Registros</h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Participante</th>
                                            <th>Evento</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($registro = mysqli_fetch_assoc($ultimosRegistros)): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($registro['participante_nombre']); ?></td>
                                            <td><?php echo htmlspecialchars($registro['evento_titulo']); ?></td>
                                            <td>
                                                <span class="badge bg-<?php 
                                                    echo $registro['estado_pago'] == 'pagado' ? 'success' : 
                                                        ($registro['estado_pago'] == 'pendiente' ? 'warning' : 'danger'); 
                                                ?>">
                                                    <?php echo ucfirst($registro['estado_pago']); ?>
                                                </span>
                                            </td>
                                        </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</body>
</html>
