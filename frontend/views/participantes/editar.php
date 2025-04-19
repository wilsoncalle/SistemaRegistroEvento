<?php // Formulario de edición de participantes ?> 

<?php
require_once '../../config/database.php';

// Crear conexión
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Verificar conexión
if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Obtener ID del participante
$participante_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$participante_id) {
    header('Location: index.php?error=id_invalido');
    exit;
}

// Obtener datos del participante
$query = "SELECT * FROM participantes WHERE participante_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $participante_id);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);
$participante = mysqli_fetch_assoc($resultado);

if (!$participante) {
    header('Location: index.php?error=no_encontrado');
    exit;
}

// Procesar el formulario cuando se envía
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
    $apellido = mysqli_real_escape_string($conn, $_POST['apellido']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $telefono = mysqli_real_escape_string($conn, $_POST['telefono']);
    $institucion = mysqli_real_escape_string($conn, $_POST['institucion']);

    try {
        // Actualizar participante
        $query = "UPDATE participantes SET 
                    nombre = ?, apellido = ?, email = ?, 
                    telefono = ?, institucion = ? 
                  WHERE participante_id = ?";
        
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'sssssi', 
            $nombre, $apellido, $email, $telefono, $institucion, $participante_id
        );
        
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Error al actualizar el participante");
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
    <title>Editar Participante - Sistema de Gestión de Eventos Académicos</title>
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
                <h1 class="h2">Editar Participante</h1>
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
                            <!-- Información personal -->
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="nombre" class="form-label">Nombre *</label>
                                        <input type="text" class="form-control" id="nombre" name="nombre" 
                                               value="<?php echo isset($participante['nombre']) ? htmlspecialchars($participante['nombre']) : ''; ?>" required>
                                        <div class="invalid-feedback">Por favor ingresa el nombre del participante.</div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="apellido" class="form-label">Apellido *</label>
                                        <input type="text" class="form-control" id="apellido" name="apellido" 
                                               value="<?php echo isset($participante['apellido']) ? htmlspecialchars($participante['apellido']) : ''; ?>" required>
                                        <div class="invalid-feedback">Por favor ingresa el apellido del participante.</div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email *</label>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           value="<?php echo isset($participante['email']) ? htmlspecialchars($participante['email']) : ''; ?>" required>
                                    <div class="invalid-feedback">Por favor ingresa un email válido.</div>
                                </div>

                                <div class="mb-3">
                                    <label for="telefono" class="form-label">Teléfono</label>
                                    <input type="tel" class="form-control" id="telefono" name="telefono" 
                                           value="<?php echo isset($participante['telefono']) ? htmlspecialchars($participante['telefono']) : ''; ?>">
                                </div>

                                <div class="mb-3">
                                    <label for="institucion" class="form-label">Institución *</label>
                                    <input type="text" class="form-control" id="institucion" name="institucion" 
                                           value="<?php echo isset($participante['institucion']) ? htmlspecialchars($participante['institucion']) : ''; ?>" required>
                                    <div class="invalid-feedback">Por favor ingresa la institución del participante.</div>
                                </div>
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
