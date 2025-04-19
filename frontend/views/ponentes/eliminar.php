<?php
require_once '../../config/database.php';

// Crear conexión
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Verificar conexión
if (!$conn) {
    die("Error de conexión: " . mysqli_connect_error());
}

// Obtener ID del ponente
$ponente_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$ponente_id) {
    header('Location: index.php?error=id_invalido');
    exit;
}

// Iniciar transacción
mysqli_begin_transaction($conn);

try {
    // 1. Eliminar registros de eventos_ponentes
    $query = "DELETE FROM eventos_ponentes WHERE ponente_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'i', $ponente_id);
    mysqli_stmt_execute($stmt);

    // 2. Eliminar el ponente
    $query = "DELETE FROM ponentes WHERE ponente_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'i', $ponente_id);
    
    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception("Error al eliminar el ponente");
    }

    mysqli_commit($conn);
    header('Location: index.php?mensaje=eliminado');
    exit;

} catch (Exception $e) {
    mysqli_rollback($conn);
    header('Location: index.php?error=' . urlencode($e->getMessage()));
    exit;
}
?> 