<?php // Formulario de creación de eventos ?> 

<?php
require_once '../../config/database.php';

// Crear conexión
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Verificar conexión
if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Obtener categorías para el select
$query = "SELECT * FROM categorias_evento ORDER BY nombre";
$categorias = mysqli_query($conn, $query);

// Obtener ponentes para el select múltiple
$query = "SELECT * FROM ponentes ORDER BY nombre, apellido";
$ponentes = mysqli_query($conn, $query);

// Procesar el formulario cuando se envía
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = mysqli_real_escape_string($conn, $_POST['titulo']);
    $descripcion = mysqli_real_escape_string($conn, $_POST['descripcion']);
    $fecha_evento = mysqli_real_escape_string($conn, $_POST['fecha_evento']);
    $hora_inicio = mysqli_real_escape_string($conn, $_POST['hora_inicio']);
    $hora_fin = mysqli_real_escape_string($conn, $_POST['hora_fin']);
    $ubicacion = mysqli_real_escape_string($conn, $_POST['ubicacion']);
    $max_participantes = (int)$_POST['max_participantes'];
    $categoria_id = (int)$_POST['categoria_id'];
    $ponentes_seleccionados = isset($_POST['ponentes']) ? $_POST['ponentes'] : [];

    // Manejar la imagen
    $imagen = isset($_POST['imagen_url']) ? $_POST['imagen_url'] : '';

    // Iniciar transacción
    mysqli_begin_transaction($conn);

    try {
        // Insertar evento
        $query = "INSERT INTO eventos (titulo, descripcion, fecha_evento, hora_inicio, hora_fin, ubicacion, 
                                     max_participantes, categoria_id, imagen) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'ssssssiis', 
            $titulo, $descripcion, $fecha_evento, $hora_inicio, $hora_fin, 
            $ubicacion, $max_participantes, $categoria_id, $imagen
        );
        
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Error al crear el evento");
        }

        $evento_id = mysqli_insert_id($conn);

        // Insertar ponentes del evento
        if (!empty($ponentes_seleccionados)) {
            $query = "INSERT INTO eventos_ponentes (evento_id, ponente_id) VALUES (?, ?)";
            $stmt = mysqli_prepare($conn, $query);

            foreach ($ponentes_seleccionados as $ponente_id) {
                mysqli_stmt_bind_param($stmt, 'ii', $evento_id, $ponente_id);
                if (!mysqli_stmt_execute($stmt)) {
                    throw new Exception("Error al asignar ponentes");
                }
            }
        }

        mysqli_commit($conn);
        header('Location: index.php?mensaje=creado');
        exit;

    } catch (Exception $e) {
        mysqli_rollback($conn);
        $error = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Evento - Sistema de Gestión de Eventos Académicos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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
                <h1 class="h2">Crear Nuevo Evento</h1>
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
                            <!-- Información básica -->
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="titulo" class="form-label">Título del Evento *</label>
                                    <input type="text" class="form-control" id="titulo" name="titulo" required>
                                    <div class="invalid-feedback">Por favor ingresa el título del evento.</div>
                                </div>

                                <div class="mb-3">
                                    <label for="descripcion" class="form-label">Descripción *</label>
                                    <textarea class="form-control" id="descripcion" name="descripcion" rows="4" required></textarea>
                                    <div class="invalid-feedback">Por favor ingresa la descripción del evento.</div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="fecha_evento" class="form-label">Fecha *</label>
                                        <input type="date" class="form-control" id="fecha_evento" name="fecha_evento" required>
                                        <div class="invalid-feedback">Por favor selecciona la fecha del evento.</div>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="hora_inicio" class="form-label">Hora de Inicio *</label>
                                        <input type="time" class="form-control" id="hora_inicio" name="hora_inicio" required>
                                        <div class="invalid-feedback">Por favor selecciona la hora de inicio del evento.</div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="hora_fin" class="form-label">Hora de Fin *</label>
                                        <input type="time" class="form-control" id="hora_fin" name="hora_fin" required>
                                    <div class="invalid-feedback">Por favor selecciona la hora de fin del evento.</div>
                                </div>
                                </div>

                                

                                <div class="mb-3">
                                    <label for="ubicacion" class="form-label">Ubicación *</label>
                                    <input type="text" class="form-control" id="ubicacion" name="ubicacion" required>
                                    <div class="invalid-feedback">Por favor ingresa la ubicación del evento.</div>
                                </div>
                            </div>

                            <!-- Configuración y detalles -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="imagen_url" class="form-label">URL de la Imagen</label>
                                    <input type="url" class="form-control" id="imagen_url" name="imagen_url" 
                                           placeholder="https://ejemplo.com/imagen.jpg">
                                    <div class="form-text">Ingresa la URL de la imagen del evento.</div>
                                </div>

                                <div class="mb-3">
                                    <label for="categoria_id" class="form-label">Categoría *</label>
                                    <select class="form-select" id="categoria_id" name="categoria_id" required>
                                        <option value="">Selecciona una categoría</option>
                                        <?php while ($categoria = mysqli_fetch_assoc($categorias)): ?>
                                            <option value="<?php echo $categoria['categoria_id']; ?>">
                                                <?php echo htmlspecialchars($categoria['nombre']); ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                    <div class="invalid-feedback">Por favor selecciona una categoría.</div>
                                </div>

                                <div class="mb-3">
                                    <label for="max_participantes" class="form-label">Máximo de Participantes *</label>
                                    <input type="number" class="form-control" id="max_participantes" name="max_participantes" min="1" required>
                                    <div class="invalid-feedback">Por favor ingresa el máximo de participantes.</div>
                                </div>

                                <div class="mb-3">
                                    <label for="ponentes" class="form-label">Ponentes</label>
                                    <select class="form-select" id="ponentes" name="ponentes[]" multiple>
                                        <?php while ($ponente = mysqli_fetch_assoc($ponentes)): ?>
                                            <option value="<?php echo $ponente['ponente_id']; ?>">
                                                <?php echo htmlspecialchars($ponente['nombre'] . ' ' . $ponente['apellido']); ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                    <div class="form-text">Puedes seleccionar múltiples ponentes.</div>
                                </div>
                            </div>
                        </div>

                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Guardar Evento
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
// Inicializar Select2 para el selector de ponentes
$(document).ready(function() {
    $('#ponentes').select2({
        placeholder: 'Selecciona los ponentes',
        allowClear: true
    });
});

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
