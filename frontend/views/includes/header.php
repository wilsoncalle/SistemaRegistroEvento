<?php
// Obtener la ruta base del proyecto
$base_path = dirname(dirname(dirname(__FILE__)));

// Incluir archivos de configuración
require_once $base_path . '/config/config.php';
require_once $base_path . '/config/database.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo APP_URL; ?>/frontend/css/styles.css">
    <style>
        .profile-image {
            max-width: 200px;
            max-height: 200px;
            border-radius: 50%;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="<?php echo APP_URL; ?>"><?php echo APP_NAME; ?></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo APP_URL; ?>/frontend/views/ponentes">Ponentes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo APP_URL; ?>/frontend/views/eventos">Eventos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo APP_URL; ?>/frontend/views/participantes">Participantes</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container-fluid p-0">
        <div class="row">
            <?php include 'layouts/sidebar.php'; ?>
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4"> 