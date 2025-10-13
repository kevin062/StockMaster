<?php
require_once 'conexion.php';
header('Content-Type: application/json; charset=utf-8');

$id = intval($_GET['id_producto'] ?? 0);
if ($id <= 0) {
    echo json_encode([]);
    exit;
}

try {
    $stmt = $conn->prepare("SELECT fecha_movimiento AS fecha, tipo_movimiento AS tipo, cantidad, stock_antes, stock_despues, motivo, usuario
                            FROM movimientos_inventario
                            WHERE producto_id = :id
                            ORDER BY fecha_movimiento DESC");
    $stmt->execute([':id' => $id]);
    $movimientos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($movimientos);
} catch (PDOException $e) {
    echo json_encode([]);
}
?>
