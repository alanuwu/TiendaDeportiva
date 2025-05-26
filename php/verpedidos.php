<?php
include 'config.php';
session_start();

// Verifica que el usuario esté logueado
if (!isset($_SESSION['id_usuario'])) {
    echo "<script>alert('Debes iniciar sesión para ver tus pedidos.'); window.location.href='../index.php';</script>";
    exit();
}
$id_usuario = $_SESSION['id_usuario'];

// Consulta los pedidos del usuario
$sqlPedidos = "SELECT p.id_pedido, p.fecha_pedido, p.estado, p.total
               FROM pedidos p
               WHERE p.id_usuario = $id_usuario
               ORDER BY p.fecha_pedido DESC";
$resPedidos = $conn->query($sqlPedidos);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Mis Pedidos - Tienda Deportiva</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;700&display=swap" rel="stylesheet" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        body { font-family: 'Montserrat', Arial, sans-serif; background: #f7f7f7; }
        .navbar-brand span { color: #0d6efd; }
        .pedido-card { background: #fff; border-radius: 1rem; box-shadow: 0 2px 12px rgba(0,0,0,0.07); padding: 2rem; margin-bottom: 2rem; }
        .pedido-header { border-bottom: 1px solid #eee; margin-bottom: 1rem; }
        .product-image { height: 60px; object-fit: cover; border-radius: 0.5rem; }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow">
      <div class="container">
        <a class="navbar-brand fw-bold" href="../index.php">Tienda<span> Deportiva</span></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" 
            aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
          <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
            <li class="nav-item"><a class="nav-link" href="../index.php">Inicio</a></li>
            <li class="nav-item"><a class="nav-link" href="hombre.php">Hombres</a></li>
            <li class="nav-item"><a class="nav-link" href="mujer.php">Mujeres</a></li>
            <li class="nav-item"><a class="nav-link" href="ninos.php">Niños</a></li>
            <li class="nav-item"><a class="nav-link" href="marcas.php">Marcas</a></li>
            <li class="nav-item"><a class="nav-link active" href="verpedidos.php">Mis pedidos</a></li>
          </ul>
          <div class="d-flex gap-3" id="userNavArea"></div>
        </div>
      </div>
    </nav>

    <div class="container my-5">
      <h2 class="fw-bold mb-4 text-center"><i class="fa-solid fa-box-open me-2"></i>Mis Pedidos</h2>
      <?php if ($resPedidos->num_rows > 0): ?>
        <?php while($pedido = $resPedidos->fetch_assoc()): ?>
          <div class="pedido-card">
            <div class="pedido-header d-flex justify-content-between align-items-center">
              <div>
                <span class="fw-bold">Pedido #<?php echo $pedido['id_pedido']; ?></span>
                <span class="badge bg-secondary ms-2"><?php echo date('d/m/Y H:i', strtotime($pedido['fecha_pedido'])); ?></span>
              </div>
              <span class="badge <?php
                switch($pedido['estado']) {
                  case 'Pendiente': echo 'bg-warning text-dark'; break;
                  case 'Enviado': echo 'bg-info text-dark'; break;
                  case 'Entregado': echo 'bg-success'; break;
                  case 'Cancelado': echo 'bg-danger'; break;
                  default: echo 'bg-secondary';
                }
              ?>"><?php echo $pedido['estado']; ?></span>
            </div>
            <!-- Productos del pedido -->
            <div class="mb-3">
              <?php
                $id_pedido = $pedido['id_pedido'];
                $sqlProductos = "SELECT dp.cantidad, dp.precio_unitario, pr.nombre, pr.imagen_url
                                 FROM detalle_pedidos dp
                                 JOIN productos pr ON dp.id_producto = pr.id_producto
                                 WHERE dp.id_pedido = $id_pedido";
                $resProductos = $conn->query($sqlProductos);
                while($prod = $resProductos->fetch_assoc()):
              ?>
                <div class="d-flex align-items-center mb-2">
                  <img src="<?php echo htmlspecialchars($prod['imagen_url'] ?: 'https://via.placeholder.com/60'); ?>" class="product-image me-3" alt="<?php echo htmlspecialchars($prod['nombre']); ?>">
                  <div>
                    <div class="fw-bold"><?php echo htmlspecialchars($prod['nombre']); ?></div>
                    <div class="small text-muted">Cantidad: <?php echo $prod['cantidad']; ?></div>
                  </div>
                  <div class="ms-auto fw-bold text-primary">
                    $<?php echo number_format($prod['precio_unitario'], 2); ?> MXN
                  </div>
                </div>
              <?php endwhile; ?>
            </div>
            <div class="d-flex justify-content-between align-items-center border-top pt-3">
              <span class="fw-bold">Total:</span>
              <span class="fw-bold text-primary fs-5">$<?php echo number_format($pedido['total'], 2); ?> MXN</span>
            </div>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <div class="alert alert-info text-center mt-5">
          <i class="fa-solid fa-info-circle me-2"></i>No tienes pedidos registrados aún.
        </div>
      <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php $conn->close(); ?>
