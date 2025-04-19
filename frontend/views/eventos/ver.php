<?php
require_once '../../config/database.php';

// Crear conexión
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Verificar conexión
if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Obtener el ID del registro
$registro_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($registro_id === 0) {
    header('Location: index.php');
    exit;
}

// Obtener el registro
$query = "SELECT r.*, 
                 e.titulo as evento_titulo,
                 e.descripcion as evento_descripcion,
                 e.fecha_evento,
                 e.hora_inicio,
                 e.hora_fin,
                 e.ubicacion,
                 CONCAT(p.nombre, ' ', p.apellido) as participante_nombre,
                 p.email as participante_email,
                 p.telefono as participante_telefono,
                 p.institucion as participante_institucion
          FROM registros r
          JOIN eventos e ON r.evento_id = e.evento_id
          JOIN participantes p ON r.participante_id = p.participante_id
          WHERE r.registro_id = ?";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $registro_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$registro = mysqli_fetch_assoc($result);

if (!$registro) {
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Registro - Sistema de Gestión de Eventos Académicos</title>
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
                <h1 class="h2">Detalles del Registro</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="index.php" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Volver
                    </a>
                </div>
            </div>

            <div class="row">
                <!-- Información del Evento -->
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Información del Evento</h5>
                        </div>
                        <div class="card-body">
                            <dl class="row mb-0">
                                <dt class="col-sm-4">Título</dt>
                                <dd class="col-sm-8"><?php echo htmlspecialchars($registro['evento_titulo']); ?></dd>

                                <dt class="col-sm-4">Descripción</dt>
                                <dd class="col-sm-8"><?php echo nl2br(htmlspecialchars($registro['evento_descripcion'])); ?></dd>

                                <dt class="col-sm-4">Fecha</dt>
                                <dd class="col-sm-8"><?php echo date('d/m/Y', strtotime($registro['fecha_evento'])); ?></dd>

                                <dt class="col-sm-4">Horario</dt>
                                <dd class="col-sm-8"><?php echo $registro['hora_inicio']; ?> - <?php echo $registro['hora_fin']; ?></dd>

                                <dt class="col-sm-4">Ubicación</dt>
                                <dd class="col-sm-8"><?php echo htmlspecialchars($registro['ubicacion']); ?></dd>
                            </dl>
                        </div>
                    </div>
                </div>

                <!-- Información del Participante -->
                <div class="col-md-6 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Información del Participante</h5>
                        </div>
                        <div class="card-body">
                            <dl class="row mb-0">
                                <dt class="col-sm-4">Nombre</dt>
                                <dd class="col-sm-8"><?php echo htmlspecialchars($registro['participante_nombre']); ?></dd>

                                <dt class="col-sm-4">Email</dt>
                                <dd class="col-sm-8"><?php echo htmlspecialchars($registro['participante_email']); ?></dd>

                                <dt class="col-sm-4">Teléfono</dt>
                                <dd class="col-sm-8"><?php echo htmlspecialchars($registro['participante_telefono']); ?></dd>

                                <dt class="col-sm-4">Institución</dt>
                                <dd class="col-sm-8"><?php echo htmlspecialchars($registro['participante_institucion']); ?></dd>
                            </dl>
                        </div>
                    </div>
                </div>

                <!-- Estado del Registro -->
                <div class="col-12 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Estado del Registro</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Estado de Pago</label>
                                        <div>
                                            <span class="badge bg-<?php 
                                                echo $registro['estado_pago'] === 'completado' ? 'success' : 
                                                    ($registro['estado_pago'] === 'pendiente' ? 'warning' : 'danger'); 
                                            ?>">
                                                <?php echo ucfirst($registro['estado_pago']); ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Asistencia</label>
                                        <div>
                                            <span class="badge bg-<?php 
                                                echo $registro['asistencia'] === 'confirmada' ? 'success' : 
                                                    ($registro['asistencia'] === 'pendiente' ? 'warning' : 'secondary'); 
                                            ?>">
                                                <?php echo ucfirst($registro['asistencia'] ?? 'pendiente'); ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">Fecha de Registro</label>
                                        <div>
                                            <?php echo date('d/m/Y H:i', strtotime($registro['fecha_registro'])); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php if (!empty($registro['retroalimentacion'])): ?>
                            <div class="mt-3">
                                <label class="form-label">Retroalimentación</label>
                                <div class="border rounded p-3 bg-light">
                                    <?php echo nl2br(htmlspecialchars($registro['retroalimentacion'])); ?>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-end">
                <a href="editar.php?id=<?php echo $registro_id; ?>" class="btn btn-primary">
                    <i class="bi bi-pencil"></i> Editar Registro
                </a>
            </div>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 