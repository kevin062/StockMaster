<?php
require_once 'conexion.php';
header('Content-Type: application/json; charset=utf-8');

$id      = intval($_POST['id_producto'] ?? 0);
$ubic    = trim($_POST['ubicacion'] ?? '');
$estado  = trim($_POST['estado'] ?? '');
$fecha   = $_POST['fecha_inventario'] ?: date('Y-m-d');

if ($id <= 0) {
    echo json_encode(['success' => false, 'message' => 'ID de producto inválido.']);
    exit;
}

try {
    $stmt = $conn->prepare("UPDATE productos SET ubicacion = :ubic, estado = :estado, fecha_inventario = :fecha WHERE id = :id");
    $stmt->execute([
        ':ubic' => $ubic,
        ':estado' => $estado,
        ':fecha' => $fecha,
        ':id' => $id
    ]);

    echo json_encode(['success' => true, 'message' => '✅ Inventario actualizado correctamente.']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => '❌ Error: ' . $e->getMessage()]);
}
?>
