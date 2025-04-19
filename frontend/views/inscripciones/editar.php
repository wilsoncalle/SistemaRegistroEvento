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

// Obtener el registro actual
$query = "SELECT r.*, 
                 e.titulo as evento_titulo,
                 CONCAT(p.nombre, ' ', p.apellido) as participante_nombre
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

// Procesar el formulario cuando se envía
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $estado_pago = mysqli_real_escape_string($conn, $_POST['estado_pago']);
    $asistencia = mysqli_real_escape_string($conn, $_POST['asistencia']);
    $retroalimentacion = mysqli_real_escape_string($conn, $_POST['retroalimentacion']);

    try {
        // Actualizar registro
        $query = "UPDATE registros SET 
                    estado_pago = ?,
                    asistencia = ?,
                    retroalimentacion = ?
                  WHERE registro_id = ?";
        
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'sssi', 
            $estado_pago, $asistencia, $retroalimentacion, $registro_id
        );
        
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Error al actualizar el registro");
        }

        header('Location: index.php?mensaje=actualizado');
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
    <title>Editar Registro - Sistema de Gestión de Eventos Académicos</title>
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
                <h1 class="h2">Editar Registro</h1>
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
                    <form action="" method="POST" class="needs-validation" novalidate>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Evento</label>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($registro['evento_titulo']); ?>" readonly>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Participante</label>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($registro['participante_nombre']); ?>" readonly>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="estado_pago" class="form-label">Estado de Pago *</label>
                                <select class="form-select" id="estado_pago" name="estado_pago" required>
                                    <option value="pendiente" <?php echo $registro['estado_pago'] === 'pendiente' ? 'selected' : ''; ?>>Pendiente</option>
                                    <option value="completado" <?php echo $registro['estado_pago'] === 'completado' ? 'selected' : ''; ?>>Completado</option>
                                    <option value="cancelado" <?php echo $registro['estado_pago'] === 'cancelado' ? 'selected' : ''; ?>>Cancelado</option>
                                </select>
                                <div class="invalid-feedback">Por favor selecciona el estado de pago.</div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="asistencia" class="form-label">Asistencia *</label>
                                <select class="form-select" id="asistencia" name="asistencia" required>
                                    <option value="pendiente" <?php echo ($registro['asistencia'] ?? 'pendiente') === 'pendiente' ? 'selected' : ''; ?>>Pendiente</option>
                                    <option value="confirmada" <?php echo ($registro['asistencia'] ?? '') === 'confirmada' ? 'selected' : ''; ?>>Confirmada</option>
                                    <option value="ausente" <?php echo ($registro['asistencia'] ?? '') === 'ausente' ? 'selected' : ''; ?>>Ausente</option>
                                </select>
                                <div class="invalid-feedback">Por favor selecciona el estado de asistencia.</div>
                            </div>

                            <div class="col-12 mb-3">
                                <label for="retroalimentacion" class="form-label">Retroalimentación</label>
                                <textarea class="form-control" id="retroalimentacion" name="retroalimentacion" rows="3"><?php echo htmlspecialchars($registro['retroalimentacion'] ?? ''); ?></textarea>
                            </div>
                        </div>

                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Guardar Cambios
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