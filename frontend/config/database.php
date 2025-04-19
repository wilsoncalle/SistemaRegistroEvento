<?php // Configuración de la base de datos ?> 

<?php
require_once __DIR__ . '/config.php';

// Crear conexión a la base de datos
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Establecer el conjunto de caracteres
$conn->set_charset("utf8mb4");
?> 
