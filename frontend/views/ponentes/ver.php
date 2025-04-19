<?php
require_once '../../config/database.php';

// Crear conexión
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Verificar conexión
if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Obtener el ID del ponente
$ponente_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($ponente_id === 0) {
    header('Location: index.php');
    exit;
}

// Obtener los datos del ponente
$query = "SELECT * FROM ponentes WHERE ponente_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $ponente_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$ponente = mysqli_fetch_assoc($result);

if (!$ponente) {
    header('Location: index.php');
    exit;
}

// Obtener los eventos del ponente
$query = "SELECT e.*, ep.titulo_presentacion, ep.hora_presentacion 
          FROM eventos e 
          JOIN eventos_ponentes ep ON e.evento_id = ep.evento_id 
          WHERE ep.ponente_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $ponente_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$eventos = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Ponente - Sistema de Gestión de Eventos Académicos</title>
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
                <h1 class="h2">Detalles del Ponente</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="index.php" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Volver
                    </a>
                </div>
            </div>

            <div class="row">
                <!-- Información del Ponente -->
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Información Personal</h5>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($ponente['imagen_perfil'])): ?>
                            <div class="text-center mb-3">
                                <img src="<?php echo htmlspecialchars($ponente['imagen_perfil']); ?>" 
                                     alt="Foto de perfil" 
                                     class="img-fluid rounded-circle" 
                                     style="max-width: 150px;">
                            </div>
                            <?php endif; ?>
                            
                            <dl class="row mb-0">
                                <dt class="col-sm-4">Nombre</dt>
                                <dd class="col-sm-8"><?php echo htmlspecialchars($ponente['nombre']); ?></dd>

                                <dt class="col-sm-4">Apellido</dt>
                                <dd class="col-sm-8"><?php echo htmlspecialchars($ponente['apellido']); ?></dd>

                                <dt class="col-sm-4">Email</dt>
                                <dd class="col-sm-8"><?php echo htmlspecialchars($ponente['email']); ?></dd>

                                <dt class="col-sm-4">Teléfono</dt>
                                <dd class="col-sm-8"><?php echo htmlspecialchars($ponente['telefono']); ?></dd>

                                <dt class="col-sm-4">Especialización</dt>
                                <dd class="col-sm-8"><?php echo htmlspecialchars($ponente['especializacion']); ?></dd>
                            </dl>

                            <?php if (!empty($ponente['biografia'])): ?>
                            <div class="mt-3">
                                <h6>Biografía</h6>
                                <p class="mb-0"><?php echo nl2br(htmlspecialchars($ponente['biografia'])); ?></p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Eventos del Ponente -->
                <div class="col-12 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Eventos y Presentaciones</h5>
                        </div>
                        <div class="card-body">
                            <?php if (count($eventos) > 0): ?>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Título del Evento</th>
                                                <th>Fecha</th>
                                                <th>Título de la Presentación</th>
                                                <th>Hora de Presentación</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($eventos as $evento): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($evento['titulo']); ?></td>
                                                    <td><?php echo date('d/m/Y', strtotime($evento['fecha_evento'])); ?></td>
                                                    <td><?php echo htmlspecialchars($evento['titulo_presentacion']); ?></td>
                                                    <td><?php echo $evento['hora_presentacion']; ?></td>
                                                    <td>
                                                        <a href="../eventos/ver.php?id=<?php echo $evento['evento_id']; ?>" class="btn btn-sm btn-info">
                                                            <i class="bi bi-eye"></i> Ver Evento
                                                        </a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-info">
                                    El ponente no tiene eventos asignados.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-end">
                <a href="editar.php?id=<?php echo $ponente_id; ?>" class="btn btn-primary">
                    <i class="bi bi-pencil"></i> Editar Ponente
                </a>
            </div>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 