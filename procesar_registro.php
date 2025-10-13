<?php
require_once "conexion.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = trim($_POST["nombre"] ?? '');
    $correo = trim($_POST["correo"] ?? '');
    $usuario = trim($_POST["usuario"] ?? '');
    $contrasena = trim($_POST["contrasena"] ?? '');
    $confirmar = trim($_POST["confirmar"] ?? '');

    if (empty($nombre) || empty($correo) || empty($usuario) || empty($contrasena) || empty($confirmar)) {
        echo "<script>alert('Por favor completa todos los campos.'); window.history.back();</script>";
        exit;
    }

    if ($contrasena !== $confirmar) {
        echo "<script>alert('Las contraseñas no coinciden.'); window.history.back();</script>";
        exit;
    }

    $hash = password_hash($contrasena, PASSWORD_DEFAULT);

    try {
        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE usuario = :usuario OR email = :email");
        $stmt->execute([':usuario' => $usuario, ':email' => $correo]);
        if ($stmt->fetch()) {
            echo "<script>alert('El usuario o correo ya están registrados.'); window.history.back();</script>";
            exit;
        }

        $sql = "INSERT INTO usuarios (usuario, contrasena, nombre_completo, email, rol, activo) 
                VALUES (:usuario, :contrasena, :nombre, :email, 'vendedor', 1)";
        $insert = $conn->prepare($sql);
        $insert->execute([
            ':usuario' => $usuario,
            ':contrasena' => $hash,
            ':nombre' => $nombre,
            ':email' => $correo
        ]);

        echo "<script>alert('✅ Usuario registrado exitosamente. Ahora puedes iniciar sesión.'); 
              window.location.href='login.html';</script>";

    } catch (PDOException $e) {
        echo "Error al registrar el usuario: " . $e->getMessage();
    }
}
?>
