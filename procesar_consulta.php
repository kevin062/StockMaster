<?php
require_once "conexion.php";
header('Content-Type: application/json; charset=utf-8');

try {
    if (!$conn) {
        throw new PDOException("Conexión no disponible.");
    }

    $codigo     = isset($_GET['codigo']) ? trim($_GET['codigo']) : '';
    $nombre     = isset($_GET['nombre']) ? trim($_GET['nombre']) : '';
    $categoria  = isset($_GET['categoria']) ? trim($_GET['categoria']) : '';
    $proveedor  = isset($_GET['proveedor']) ? trim($_GET['proveedor']) : '';
    $precioMin  = isset($_GET['precio-min']) ? floatval($_GET['precio-min']) : 0;
    $precioMax  = isset($_GET['precio-max']) ? floatval($_GET['precio-max']) : 0;
    $estado     = isset($_GET['estado']) ? trim($_GET['estado']) : '';

    if ($precioMax > 0 && $precioMax < $precioMin) {
        echo json_encode([
            "status" => "error",
            "message" => "El precio máximo no puede ser menor que el precio mínimo."
        ]);
        exit;
    }

    $query = "SELECT * FROM productos WHERE 1=1";
    $params = [];

    if ($codigo !== '') {
        $query .= " AND codigo LIKE :codigo";
        $params[':codigo'] = "%$codigo%";
    }
    if ($nombre !== '') {
        $query .= " AND nombre LIKE :nombre";
        $params[':nombre'] = "%$nombre%";
    }
    if ($categoria !== '') {
        $query .= " AND categoria = :categoria";
        $params[':categoria'] = $categoria;
    }
    if ($proveedor !== '') {
        $query .= " AND proveedor LIKE :proveedor";
        $params[':proveedor'] = "%$proveedor%";
    }
    if ($precioMin > 0) {
        $query .= " AND precio >= :precioMin";
        $params[':precioMin'] = $precioMin;
    }
    if ($precioMax > 0) {
        $query .= " AND precio <= :precioMax";
        $params[':precioMax'] = $precioMax;
    }
    if ($estado !== '') {
        $query .= " AND estado = :estado";
        $params[':estado'] = $estado;
    }

    $query .= " ORDER BY nombre ASC LIMIT 50";

    $stmt = $conn->prepare($query);
    $stmt->execute($params);
    $resultados = $stmt->fetchAll();

    http_response_code(200);
    echo json_encode([
        "status" => "success",
        "total" => count($resultados),
        "data" => $resultados
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Error al consultar productos: " . $e->getMessage()
    ]);
}
?>
