<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once 'conexion.php';
header('Content-Type: application/json; charset=utf-8');

try {
  $idProducto = isset($_POST['idproducto']) ? intval($_POST['idproducto']) : 0;
  $nombre     = isset($_POST['nombreproducto']) ? trim($_POST['nombreproducto']) : '';
  $cantidad   = isset($_POST['cantidadvendida']) ? intval($_POST['cantidadvendida']) : 0;
  $precio     = isset($_POST['preciounitario']) ? floatval($_POST['preciounitario']) : 0;
  $total      = isset($_POST['totalventa']) ? floatval($_POST['totalventa']) : 0;
  $metodo     = isset($_POST['metodopago']) ? trim($_POST['metodopago']) : '';
  $vendedor   = isset($_POST['vendedor']) ? trim($_POST['vendedor']) : '';

  $metodosValidos = ['efectivo', 'tarjetacredito', 'tarjetadebito', 'transferencia'];
  if (!in_array($metodo, $metodosValidos)) {
    echo json_encode(['success' => false, 'message' => 'Método de pago inválido.']);
    exit;
  }

  if ($idProducto <= 0 || $cantidad <= 0 || $precio <= 0 || $total <= 0 || empty($nombre) || empty($vendedor)) {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos o inválidos.']);
    exit;
  }

  $stmt = $conn->prepare("SELECT stock FROM productos WHERE id = :id");
  $stmt->execute([':id' => $idProducto]);
  $stockActual = $stmt->fetchColumn();

  if ($stockActual === false) {
    echo json_encode(['success' => false, 'message' => 'Producto no encontrado.']);
    exit;
  }

  if ($stockActual < $cantidad) {
    echo json_encode(['success' => false, 'message' => 'Stock insuficiente para realizar la venta.']);
    exit;
  }

  $stmt = $conn->prepare("INSERT INTO ventas 
    (idproducto, nombreproducto, cantidadvendida, preciounitario, totalventa, metodopago, vendedor, fecha_venta)
    VALUES (:idproducto, :nombreproducto, :cantidadvendida, :preciounitario, :totalventa, :metodopago, :vendedor, NOW())");

  $stmt->execute([
    ':idproducto'      => $idProducto,
    ':nombreproducto'  => $nombre,
    ':cantidadvendida' => $cantidad,
    ':preciounitario'  => $precio,
    ':totalventa'      => $total,
    ':metodopago'      => $metodo,
    ':vendedor'        => $vendedor
  ]);

  $nuevoStock = $stockActual - $cantidad;
  $stmt = $conn->prepare("UPDATE productos SET stock = :nuevo WHERE id = :id");
  $stmt->execute([':nuevo' => $nuevoStock, ':id' => $idProducto]);

  echo json_encode(['success' => true, 'message' => '✅ Venta registrada correctamente. Stock actualizado.']);
} catch (PDOException $e) {
  echo json_encode(['success' => false, 'message' => 'Error en la base de datos: ' . $e->getMessage()]);
}
