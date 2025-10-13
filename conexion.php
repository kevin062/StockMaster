<?php
// conexion.php
// Archivo de conexión central con PDO para MySQL

$host     = "localhost";       // Servidor local
$bd       = "stockmaster";     // Nombre de tu base de datos
$usuario  = "root";            // Usuario por defecto de XAMPP
$clave    = "";                // Contraseña (vacía por defecto en XAMPP)

try {
    // Conexión con PDO
    $conn = new PDO("mysql:host=$host;dbname=$bd;charset=utf8mb4", $usuario, $clave);

    // Configurar atributos para manejo de errores y resultados
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // En caso de error de conexión
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "❌ Error de conexión a la base de datos: " . $e->getMessage()
    ]);
    exit;
}
?>
