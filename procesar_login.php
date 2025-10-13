<?php
session_start();
require_once "conexion.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usuario    = trim($_POST["usuario"] ?? '');
    $contrasena = trim($_POST["contrasena"] ?? '');
    $ip         = $_SERVER["REMOTE_ADDR"];

    try {
        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE usuario = ? AND activo = 1");
        $stmt->execute([$usuario]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($contrasena, $user["contrasena"])) {
            $_SESSION["usuario"] = $user["usuario"];
            $_SESSION["rol"]     = $user["rol"];
            $_SESSION["nombre"]  = $user["nombre_completo"];

            $update = $conn->prepare("UPDATE usuarios SET ultimo_acceso = NOW() WHERE id = ?");
            $update->execute([$user["id"]]);

            $log = $conn->prepare("INSERT INTO logs_ingresos (usuario, ip_address, estado, mensaje)
                                   VALUES (?, ?, 'exitoso', 'Inicio de sesión correcto')");
            $log->execute([$usuario, $ip]);

            if ($user["rol"] === "admin") {
                header("Location: admin.html");
                exit;
            } elseif ($user["rol"] === "vendedor") {
                header("Location: vendedor.html");
                exit;
            } else {
                header("Location: login.html?error=rol");
                exit;
            }

        } else {
            $log = $conn->prepare("INSERT INTO logs_ingresos (usuario, ip_address, estado, mensaje)
                                   VALUES (?, ?, 'fallido', 'Credenciales incorrectas')");
            $log->execute([$usuario, $ip]);

            header("Location: login.html?error=credenciales");
            exit;
        }

    } catch (PDOException $e) {
        header("Location: login.html?error=conexion");
        exit;
    }
} else {
    header("Location: login.html");
    exit;
}
?>