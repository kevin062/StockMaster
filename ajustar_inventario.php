<?php
require_once 'conexion.php';
header('Content-Type: application/json; charset=utf-8');

$id       = intval($_POST['id_producto'] ?? 0);
$tipo     = $_POST['tipo'] ?? '';
$cantidad = intval($_POST['cantidad'] ?? 0);
$motivo   = trim($_POST['motivo'] ?? '');
$usuario  = 'admin'; // Puedes cambiar esto por el usuario actual si tienes sesión

if ($id <= 0 || $cantidad <= 0 || !in_array($tipo, ['incremento', 'decremento'])) {
    echo json_encode(['success' => false, 'message' => 'Datos inválidos para el ajuste.']);
    exit;
}

try {
    // Obtener stock actual
    $stmt = $conn->prepare("SELECT stock FROM productos WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $actual = $stmt->fetchColumn();

    if ($actual === false) {
        echo json_encode(['success' => false, 'message' => 'Producto no encontrado.']);
        exit;
    }

    $nuevo = ($tipo === 'incremento') ? $actual + $cantidad : max(0, $actual - $cantidad);

    // Actualizar stock
    $stmt = $conn->prepare("UPDATE productos SET stock = :nuevo WHERE id = :id");
    $stmt->execute([':nuevo' => $nuevo, ':id' => $id]);

    // Registrar movimiento
    $stmt = $conn->prepare("INSERT INTO movimientos_inventario 
        (producto_id, tipo_movimiento, cantidad, stock_antes, stock_despues, motivo, usuario)
        VALUES (:id, :tipo, :cantidad, :antes, :despues, :motivo, :usuario)");
    $stmt->execute([
        ':id' => $id,
        ':tipo' => $tipo,
        ':cantidad' => $cantidad,
        ':antes' => $actual,
        ':despues' => $nuevo,
        ':motivo' => $motivo,
        ':usuario' => $usuario
    ]);

    echo json_encode(['success' => true, 'message' => '✅ Ajuste aplicado correctamente.', 'stock_nuevo' => $nuevo]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => '❌ Error: ' . $e->getMessage()]);
}
?>
