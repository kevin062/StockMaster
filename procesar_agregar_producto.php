<?php
require_once "conexion.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $codigo = trim($_POST["codigo"] ?? '');
    $nombre = trim($_POST["nombre"] ?? '');
    $descripcion = trim($_POST["descripcion"] ?? '');
    $categoria = trim($_POST["categoria"] ?? '');
    $proveedor = trim($_POST["proveedor"] ?? '');
    $precio = trim($_POST["precio"] ?? '');
    $cantidad = trim($_POST["cantidad"] ?? '');
    $fecha_ingreso = trim($_POST["fecha_ingreso"] ?? '');
    $estado = trim($_POST["estado"] ?? '');

    if (empty($codigo) || empty($nombre) || empty($descripcion) || empty($categoria) ||
        empty($proveedor) || empty($precio) || empty($cantidad) || empty($fecha_ingreso) || empty($estado)) {
        echo "<script>alert('⚠️ Todos los campos son obligatorios.'); window.history.back();</script>";
        exit;
    }

    try {
        $check = $conn->prepare("SELECT id FROM productos WHERE codigo = :codigo");
        $check->execute([':codigo' => $codigo]);

        if ($check->fetch()) {
            echo "<script>alert('⚠️ Ya existe un producto con este código.'); window.history.back();</script>";
            exit;
        }

        $sql = "INSERT INTO productos 
                (codigo, nombre, descripcion, categoria, proveedor, precio, stock, estado, fecha_ingreso)
                VALUES 
                (:codigo, :nombre, :descripcion, :categoria, :proveedor, :precio, :stock, :estado, :fecha_ingreso)";
        $stmt = $conn->prepare($sql);

        $stmt->execute([
            ':codigo' => $codigo,
            ':nombre' => $nombre,
            ':descripcion' => $descripcion,
            ':categoria' => $categoria,
            ':proveedor' => $proveedor,
            ':precio' => $precio,
            ':stock' => $cantidad,
            ':estado' => $estado,
            ':fecha_ingreso' => $fecha_ingreso
        ]);

        echo "<script>
                alert('✅ Producto agregado exitosamente.');
                window.location.href='consultar.html';
              </script>";
    } catch (PDOException $e) {
        echo "<script>
                alert('❌ Error al agregar producto: " . $e->getMessage() . "');
                window.history.back();
              </script>";
    }
} else {
    echo "<script>alert('Método no permitido.'); window.history.back();</script>";
}
?>
