<?php
include 'conexion.php';
$result = $conn->query("SELECT * FROM ventas ORDER BY fecha_venta DESC");
$ventas = [];
while ($row = $result->fetch_assoc()) {
  $ventas[] = $row;
}
echo json_encode($ventas);
$conn->close();
?>
