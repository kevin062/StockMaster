<?php
session_start();

if (!isset($_SESSION["usuario"])) {
    header("Location: login.html");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel de Control | StockMaster</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .navbar {
      background-color: #0d6efd;
    }
    .navbar-brand, .nav-link, .navbar-text {
      color: white !important;
    }
    .card {
      border-radius: 1rem;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }
  </style>
</head>

<body>
  <nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
      <a class="navbar-brand fw-bold" href="#">📦 StockMaster</a>
      <div class="d-flex">
        <span class="navbar-text me-3">
          👋 Bienvenido, <strong><?php echo htmlspecialchars($_SESSION["nombre"]); ?></strong>
          (<?php echo htmlspecialchars($_SESSION["rol"]); ?>)
        </span>
        <a href="logout.php" class="btn btn-light btn-sm">Cerrar sesión</a>
      </div>
    </div>
  </nav>

  <div class="container my-5">
    <div class="text-center mb-4">
      <h1 class="fw-bold">Panel de Control</h1>
      <p class="text-muted">Gestiona tu inventario, ventas y usuarios desde un solo lugar.</p>
    </div>

    <div class="row g-4">
      <div class="col-md-4">
        <div class="card text-center">
          <div class="card-body">
            <i class="fas fa-box fa-3x text-primary mb-3"></i>
            <h5 class="card-title">Productos</h5>
            <p class="card-text text-muted">Administra los productos de tu inventario.</p>
            <a href="consultar.html" class="btn btn-primary">Ir a productos</a>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card text-center">
          <div class="card-body">
            <i class="fas fa-cash-register fa-3x text-success mb-3"></i>
            <h5 class="card-title">Ventas</h5>
            <p class="card-text text-muted">Registra y consulta las ventas realizadas.</p>
            <a href="registrar.html" class="btn btn-success">Ir a ventas</a>
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card text-center">
          <div class="card-body">
            <i class="fas fa-users fa-3x text-warning mb-3"></i>
            <h5 class="card-title">Usuarios</h5>
            <p class="card-text text-muted">Gestiona usuarios y roles del sistema.</p>
            <a href="usuarios.html" class="btn btn-warning text-white">Ir a usuarios</a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <footer class="text-center text-muted py-4 border-top">
    <small>© <?php echo date("Y"); ?> StockMaster — Todos los derechos reservados.</small>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
