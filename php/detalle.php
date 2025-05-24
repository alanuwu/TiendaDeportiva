<?php
include "config.php"; // conexión

// Verificar que se envió el parámetro id
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "Producto no válido.";
    exit;
}

$id = intval($_GET['id']);

// Consulta que obtiene el producto y su categoría
$sql = "
    SELECT 
        p.nombre, 
        p.descripcion, 
        p.precio, 
        p.imagen_url, 
        c.nombre AS categoria
    FROM productos p
    INNER JOIN categorias c ON p.id_categoria = c.id_categoria
    WHERE p.id_producto = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    echo "Producto no encontrado.";
    exit;
}

$producto = $resultado->fetch_assoc();
$categoria = strtolower($producto['categoria']); // Para comparaciones consistentes
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Detalle de <?php echo htmlspecialchars($producto['nombre']); ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;700&display=swap" rel="stylesheet" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        body {
            font-family: 'Montserrat', Arial, sans-serif;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .navbar-brand span { color: #0d6efd; }
        .carousel-caption { background: rgba(0,0,0,0.45); border-radius: 1rem; }

        main {
            flex: 1;
        }

        .product-image {
            height: 200px;
            object-fit: cover;
        }
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
          </ul>
         <div class="d-flex gap-3" id="userNavArea">
            <!-- Aquí se insertará dinámicamente el icono o el botón -->
          </div>
        </div>
      </div>
    </nav>

    <main class="container my-5">
        <a href="<?php echo $categoria . '.php'; ?>" class="btn btn-secondary mb-4">← Regresar</a>

        <div class="row">
            <div class="col-md-6">
                <img src="<?php echo !empty($producto['imagen_url']) ? $producto['imagen_url'] : 'https://via.placeholder.com/600'; ?>" alt="<?php echo htmlspecialchars($producto['nombre']); ?>" class="img-fluid rounded shadow" />
            </div>
            <div class="col-md-6">
                <h1><?php echo htmlspecialchars($producto['nombre']); ?></h1>
                <h3 class="text-primary">$<?php echo $producto['precio']; ?> MXN</h3>
                <p><?php echo nl2br(htmlspecialchars($producto['descripcion'] ?? "Sin descripción disponible.")); ?></p>
                <button class="btn btn-primary btn-lg">Agregar al carrito</button>
            </div>
        </div>
    </main>

    <?php include "cardLogin.php" ?>
    <!-- Footer -->
    <footer class="bg-dark text-white pt-5 pb-3 mt-5">
        <div class="container">
            <div class="row gy-4">
                <div class="col-12 col-md-4 text-center text-md-start">
                    <a class="navbar-brand fw-bold text-white mb-2 d-inline-block" href="#" style="font-size:1.5rem;">Tienda<span style="color:#0d6efd;">Deportiva</span></a>
                    <p class="small mt-2 mb-0">Tu tienda de confianza para ropa, calzado y accesorios deportivos de las mejores marcas.</p>
                </div>
                <div class="col-12 col-md-4 text-center">
                    <h6 class="fw-bold mb-3">Contacto</h6>
                    <ul class="list-unstyled mb-0">
                        <li><i class="fa-solid fa-envelope me-2"></i> contacto@tiendadeportiva.com</li>
                        <li><i class="fa-solid fa-phone me-2"></i> +52 55 1234 5678</li>
                        <li><i class="fa-solid fa-location-dot me-2"></i> Querétaro, México</li>
                    </ul>
                    <div class="mt-3">
                        <a href="#" class="text-white me-3"><i class="fab fa-facebook fa-lg"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-instagram fa-lg"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-twitter fa-lg"></i></a>
                    </div>
                </div>
                <div class="col-12 col-md-4 text-center text-md-end">
                    <small>© 2025 Tienda Deportiva. Todos los derechos reservados.</small>
                </div>
            </div>
        </div>
    </footer>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script src="../js/index.js"></script>

</body>

</html>
