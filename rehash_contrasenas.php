<?php
require 'conexion.php';

// Lista de usuarios a actualizar
$usuarios = ['admin', 'vendedor'];

foreach ($usuarios as $u) {
    // Generar nuevo hash con PHP
    $nuevoHash = password_hash('12345', PASSWORD_DEFAULT);

    $stmt = $conn->prepare("UPDATE usuarios SET contrasena = ? WHERE usuario = ?");
    $stmt->execute([$nuevoHash, $u]);

    echo "✅ Contraseña actualizada correctamente para el usuario: $u<br>";
}

echo "<br>Proceso completado.";
?>
