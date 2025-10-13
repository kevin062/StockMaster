<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once 'conexion.php';
header('Content-Type: application/json; charset=utf-8');

try {
  $codigo = trim($_POST['codigo'] ?? '');

  if (empty($codigo)) {
    echo json_encode(['success' => false, 'message' => 'El código del producto es obligatorio.']);
    exit;
  }

  $stmt = $conn->prepare("SELECT id, nombre FROM productos WHERE codigo = :codigo");
  $stmt->execute([':codigo' => $codigo]);
  $producto = $stmt->fetch(PDO::FETCH_ASSOC);

  if (!$producto) {
    echo json_encode(['success' => false, 'message' => 'No se encontró ningún producto con ese código.']);
    exit;
  }

  $stmt = $conn->prepare("DELETE FROM productos WHERE id = :id");
  $stmt->execute([':id' => $producto['id']]);

  echo json_encode([
    'success' => true,
    'message' => "✅ Producto eliminado: {$producto['nombre']} (Código: $codigo)"
  ]);
} catch (PDOException $e) {
  echo json_encode(['success' => false, 'message' => 'Error al eliminar el producto: ' . $e->getMessage()]);
}
