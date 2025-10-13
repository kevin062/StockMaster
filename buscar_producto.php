<?php
require_once 'conexion.php';
header('Content-Type: application/json; charset=utf-8');

$codigo = isset($_GET['codigo']) ? trim($_GET['codigo']) : '';
$id     = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($codigo === '' && $id === 0) {
    echo json_encode(['success' => false, 'message' => 'No se recibió ningún parámetro']);
    exit;
}

try {
    if ($id > 0) {
        $stmt = $conn->prepare("SELECT * FROM productos WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
    } else {
        $stmt = $conn->prepare("SELECT * FROM productos WHERE codigo = :codigo LIMIT 1");
        $stmt->execute([':codigo' => $codigo]);
    }

    $producto = $stmt->fetch();

    if ($producto) {
        echo json_encode(['success' => true, 'data' => $producto]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Producto no encontrado']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error en la consulta: ' . $e->getMessage()]);
}
?>
