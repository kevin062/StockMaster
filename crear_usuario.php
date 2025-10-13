// crear_usuario.php//

<?php
require 'conexion.php';
$usuario = 'admin';
$contrasena = password_hash('12345', PASSWORD_DEFAULT);
$nombre = 'Administrador del sistema';
$email = 'admin@stockmaster.com';
$rol = 'admin';
$activo = 1;

try {
    $sql = "INSERT INTO usuarios (usuario, contrasena, nombre_completo, email, rol, activo)
            VALUES (:usuario, :contrasena, :nombre, :email, :rol, :activo)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':usuario' => $usuario,
        ':contrasena' => $contrasena,
        ':nombre' => $nombre,
        ':email' => $email,
        ':rol' => $rol,
        ':activo' => $activo
    ]);
    echo "✅ Usuario creado correctamente.";
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>
