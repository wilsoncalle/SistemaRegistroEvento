<?php // Vista para registrar asistencia ?> 

<?php
require_once '../../config/database.php';

// Crear conexión
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Verificar conexión
if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Obtener el ID del registro desde la URL
$registro_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Obtener información del registro
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
          WHERE r.registro_id = ?";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $registro_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$registro = mysqli_fetch_assoc($result);

if (!$registro) {
    header('Location: index.php?error=registro_no_encontrado');
    exit;
}

// Procesar el formulario cuando se envía
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $asistencia = isset($_POST['asistencia']) ? 1 : 0;
    $retroalimentacion = mysqli_real_escape_string($conn, $_POST['retroalimentacion']);

    try {
        // Actualizar el registro
        $query = "UPDATE registros SET 
                  asistencia = ?,
                  retroalimentacion = ?
                  WHERE registro_id = ?";
        
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'isi', $asistencia, $retroalimentacion, $registro_id);
        
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Error al actualizar la asistencia");
        }

        header('Location: index.php?mensaje=asistencia_actualizada');
        exit;

    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Asistencia - Sistema de Gestión de Eventos Académicos</title>
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
                <h1 class="h2">Registrar Asistencia</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <a href="index.php" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Volver
                    </a>
                </div>
            </div>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-body">
                    <!-- Información del Registro -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5 class="card-title">Información del Evento</h5>
                            <p><strong>Evento:</strong> <?php echo htmlspecialchars($registro['evento_titulo']); ?></p>
                            <p><strong>Fecha:</strong> <?php echo date('d/m/Y', strtotime($registro['fecha_evento'])); ?></p>
                            <p><strong>Horario:</strong> <?php echo date('H:i', strtotime($registro['hora_inicio'])) . ' - ' . date('H:i', strtotime($registro['hora_fin'])); ?></p>
                            <p><strong>Ubicación:</strong> <?php echo htmlspecialchars($registro['ubicacion']); ?></p>
                        </div>
                        <div class="col-md-6">
                            <h5 class="card-title">Información del Participante</h5>
                            <p><strong>Nombre:</strong> <?php echo htmlspecialchars($registro['participante_nombre']); ?></p>
                            <p><strong>Email:</strong> <?php echo htmlspecialchars($registro['participante_email']); ?></p>
                            <p><strong>Estado de Pago:</strong> 
                                <span class="badge bg-<?php echo $registro['estado_pago'] === 'completado' ? 'success' : 'warning'; ?>">
                                    <?php echo ucfirst($registro['estado_pago']); ?>
                                </span>
                            </p>
                        </div>
                    </div>

                    <form action="" method="POST" class="needs-validation" novalidate>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="asistencia" name="asistencia" 
                                               <?php echo $registro['asistencia'] ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="asistencia">Registrar Asistencia</label>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="retroalimentacion" class="form-label">Retroalimentación</label>
                                    <textarea class="form-control" id="retroalimentacion" name="retroalimentacion" 
                                              rows="4"><?php echo htmlspecialchars($registro['retroalimentacion']); ?></textarea>
                                    <div class="form-text">Ingresa cualquier comentario o retroalimentación sobre el evento.</div>
                                </div>
                            </div>
                        </div>

                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Guardar Asistencia
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Validación del formulario
(function () {
    'use strict'
    var forms = document.querySelectorAll('.needs-validation')
    Array.prototype.slice.call(forms).forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
            }
            form.classList.add('was-validated')
        }, false)
    })
})();
</script>
</body>
</html> 
