<?php
include 'conexion.php';
header('Content-Type: application/json; charset=utf-8');

$campos_obligatorios = ['codigo', 'nombre', 'categoria', 'proveedor', 'precio', 'stock', 'estado'];
foreach ($campos_obligatorios as $campo) {
    if (!isset($_POST[$campo]) || trim($_POST[$campo]) === '') {
        echo json_encode(['success' => false, 'message' => "El campo '$campo' es obligatorio."]);
        exit;
    }
}

$id_original = isset($_POST['original_id']) ? intval($_POST['original_id']) : 0;
if ($id_original <= 0) {
    echo json_encode(['success' => false, 'message' => 'ID del producto inválido.']);
    exit;
}

$codigo      = trim($_POST['codigo']);
$nombre      = trim($_POST['nombre']);
$descripcion = trim($_POST['descripcion'] ?? '');
$categoria   = trim($_POST['categoria']);
$proveedor   = trim($_POST['proveedor']);
$precio      = floatval($_POST['precio']);
$cantidad    = intval($_POST['stock']);
$ubicacion   = trim($_POST['ubicacion'] ?? '');
$estado      = trim($_POST['estado']);
$fecha_act   = $_POST['fecha_actualizacion'] ?: date('Y-m-d');
$sku         = trim($_POST['sku'] ?? '');

try {
    $query = "UPDATE productos SET
                codigo = :codigo,
                nombre = :nombre,
                descripcion = :descripcion,
                categoria = :categoria,
                proveedor = :proveedor,
                precio = :precio,
                stock = :stock,
                ubicacion = :ubicacion,
                estado = :estado,
                fecha_actualizacion = :fecha_actualizacion,
                sku = :sku
              WHERE id = :id";

    $stmt = $conn->prepare($query);
    $stmt->execute([
        ':codigo' => $codigo,
        ':nombre' => $nombre,
        ':descripcion' => $descripcion,
        ':categoria' => $categoria,
        ':proveedor' => $proveedor,
        ':precio' => $precio,
        ':stock' => $cantidad,
        ':ubicacion' => $ubicacion,
        ':estado' => $estado,
        ':fecha_actualizacion' => $fecha_act,
        ':sku' => $sku,
        ':id' => $id_original
    ]);

    echo json_encode(['success' => true, 'message' => '✅ Producto actualizado correctamente.']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => '❌ Error al actualizar: ' . $e->getMessage()]);
}
?>
