<?php
require_once '../../config/database.php';

// Crear conexión
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Verificar conexión
if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Procesar el formulario cuando se envía
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = mysqli_real_escape_string($conn, $_POST['nombre']);
    $apellido = mysqli_real_escape_string($conn, $_POST['apellido']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $telefono = mysqli_real_escape_string($conn, $_POST['telefono']);
    $especializacion = mysqli_real_escape_string($conn, $_POST['especializacion']);
    $biografia = mysqli_real_escape_string($conn, $_POST['biografia']);
    $imagen_perfil = isset($_POST['imagen_perfil']) ? $_POST['imagen_perfil'] : '';

    try {
        // Insertar nuevo ponente
        $query = "INSERT INTO ponentes (
                    nombre, apellido, email, telefono, 
                    especializacion, biografia, imagen_perfil
                  ) VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'sssssss', 
            $nombre, $apellido, $email, $telefono, 
            $especializacion, $biografia, $imagen_perfil
        );
        
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Error al crear el ponente");
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
    <title>Crear Ponente - Sistema de Gestión de Eventos Académicos</title>
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
                <h1 class="h2">Crear Nuevo Ponente</h1>
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
                                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                                        <div class="invalid-feedback">Por favor ingresa el nombre del ponente.</div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="apellido" class="form-label">Apellido *</label>
                                        <input type="text" class="form-control" id="apellido" name="apellido" required>
                                        <div class="invalid-feedback">Por favor ingresa el apellido del ponente.</div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email *</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                    <div class="invalid-feedback">Por favor ingresa un email válido.</div>
                                </div>

                                <div class="mb-3">
                                    <label for="telefono" class="form-label">Teléfono</label>
                                    <input type="tel" class="form-control" id="telefono" name="telefono">
                                </div>

                                <div class="mb-3">
                                    <label for="especializacion" class="form-label">Especialización *</label>
                                    <input type="text" class="form-control" id="especializacion" name="especializacion" required>
                                    <div class="invalid-feedback">Por favor ingresa la especialización del ponente.</div>
                                </div>

                                <div class="mb-3">
                                    <label for="biografia" class="form-label">Biografía *</label>
                                    <textarea class="form-control" id="biografia" name="biografia" rows="4" required></textarea>
                                    <div class="invalid-feedback">Por favor ingresa la biografía del ponente.</div>
                                </div>
                            </div>

                            <!-- Imagen y detalles adicionales -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="imagen_perfil" class="form-label">URL de la Imagen de Perfil</label>
                                    <input type="url" class="form-control" id="imagen_perfil" name="imagen_perfil"
                                           placeholder="https://ejemplo.com/imagen.jpg">
                                    <div class="form-text">Ingresa la URL de la imagen de perfil del ponente.</div>
                                </div>
                            </div>
                        </div>

                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Crear Ponente
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
