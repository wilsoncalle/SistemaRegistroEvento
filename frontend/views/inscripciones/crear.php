<?php
require_once '../../config/database.php';

// Crear conexión
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Verificar conexión
if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Obtener eventos disponibles
$query = "SELECT * FROM eventos WHERE fecha_evento >= CURDATE() ORDER BY fecha_evento ASC";
$eventos = mysqli_query($conn, $query);

// Obtener participantes
$query = "SELECT * FROM participantes ORDER BY nombre, apellido";
$participantes = mysqli_query($conn, $query);

// Procesar el formulario cuando se envía
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $evento_id = (int)$_POST['evento_id'];
    $participante_id = (int)$_POST['participante_id'];
    $estado_pago = mysqli_real_escape_string($conn, $_POST['estado_pago']);

    try {
        // Verificar si hay cupo disponible
        $query = "SELECT COUNT(*) as total_registros, max_participantes 
                 FROM registros r
                 JOIN eventos e ON r.evento_id = e.evento_id
                 WHERE r.evento_id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'i', $evento_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $data = mysqli_fetch_assoc($result);

        if ($data['total_registros'] >= $data['max_participantes']) {
            throw new Exception("El evento ha alcanzado su capacidad máxima");
        }

        // Verificar si el participante ya está registrado
        $query = "SELECT registro_id FROM registros 
                 WHERE evento_id = ? AND participante_id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'ii', $evento_id, $participante_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            throw new Exception("El participante ya está registrado en este evento");
        }

        // Insertar nuevo registro
        $query = "INSERT INTO registros (
                    evento_id, participante_id, estado_pago
                  ) VALUES (?, ?, ?)";
        
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'iis', 
            $evento_id, $participante_id, $estado_pago
        );
        
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Error al crear el registro");
        }

        header('Location: index.php?mensaje=creado');
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
    <title>Crear Registro - Sistema de Gestión de Eventos Académicos</title>
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
                <h1 class="h2">Crear Nuevo Registro</h1>
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
                                <label for="evento_id" class="form-label">Evento *</label>
                                <select class="form-select" id="evento_id" name="evento_id" required>
                                    <option value="">Selecciona un evento</option>
                                    <?php while ($evento = mysqli_fetch_assoc($eventos)): ?>
                                        <option value="<?php echo $evento['evento_id']; ?>">
                                            <?php echo htmlspecialchars($evento['titulo']); ?> 
                                            (<?php echo date('d/m/Y', strtotime($evento['fecha_evento'])); ?>)
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                                <div class="invalid-feedback">Por favor selecciona un evento.</div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="participante_id" class="form-label">Participante *</label>
                                <select class="form-select" id="participante_id" name="participante_id" required>
                                    <option value="">Selecciona un participante</option>
                                    <?php while ($participante = mysqli_fetch_assoc($participantes)): ?>
                                        <option value="<?php echo $participante['participante_id']; ?>">
                                            <?php echo htmlspecialchars($participante['nombre'] . ' ' . $participante['apellido']); ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                                <div class="invalid-feedback">Por favor selecciona un participante.</div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="estado_pago" class="form-label">Estado de Pago *</label>
                                <select class="form-select" id="estado_pago" name="estado_pago" required>
                                    <option value="pendiente">Pendiente</option>
                                    <option value="completado">Completado</option>
                                    <option value="cancelado">Cancelado</option>
                                </select>
                                <div class="invalid-feedback">Por favor selecciona el estado de pago.</div>
                            </div>
                        </div>

                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Crear Registro
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