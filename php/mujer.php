<?php
include 'config.php';

$sql = "SELECT id_producto, nombre, precio, imagen_url FROM productos WHERE id_categoria = 2 LIMIT 8";
$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Productos para Mujeres - Tienda Deportiva</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;700&display=swap" rel="stylesheet" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        body { font-family: 'Montserrat', Arial, sans-serif; }
        .navbar-brand span { color: #0d6efd; }
        .carousel-caption { background: rgba(0,0,0,0.45); border-radius: 1rem; }
        .product-card { transition: transform .2s, box-shadow .2s; }
        .product-card:hover { transform: translateY(-8px) scale(1.03); box-shadow: 0 6px 24px rgba(0,0,0,0.13); }
        .product-image { height: 200px; object-fit: cover; }
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
            <li class="nav-item"><a class="nav-link active" aria-current="page" href="mujer.php">Mujeres</a></li>
            <li class="nav-item"><a class="nav-link" href="ninos.php">Niños</a></li>
            <li class="nav-item"><a class="nav-link" href="marcas.php">Marcas</a></li>
          </ul>
           <div class="d-flex gap-3" id="userNavArea">
            <!-- Aquí se insertará dinámicamente el icono o el botón -->
          </div>
        </div>
      </div>
    </nav>

    <!-- Carousel -->
    <div id="mainCarousel" class="carousel slide mt-4 mb-5" data-bs-ride="carousel">
      <div class="carousel-indicators">
        <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
        <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
        <button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
      </div>
      <div class="carousel-inner rounded-4 shadow">
        <div class="carousel-item active">
          <img src="https://images.unsplash.com/photo-1517649763962-0c623066013b?auto=format&fit=crop&w=900&q=80" 
            class="d-block w-100" alt="Producto 1" style="height:340px;object-fit:cover;" />
          <div class="carousel-caption d-none d-md-block p-4">
            <h2 class="fw-bold">Nuevos Tenis Nike</h2>
            <p>Descubre la nueva colección para correr</p>
          </div>
        </div>
        <div class="carousel-item">
          <img src="https://b2cimpulsmx.vtexassets.com/arquivos/ids/358759-800-800?v=638709166850700000&width=900&height=340&aspect=true" 
            class="d-block w-100" alt="Producto 2" style="height:340px;object-fit:cover;" />
          <div class="carousel-caption d-none d-md-block p-4">
            <h2 class="fw-bold">Ropa Deportiva Mujer</h2>
            <p>Estilo y comodidad para entrenar</p>
          </div>
        </div>
        <div class="carousel-item">
          <img src="https://images.unsplash.com/photo-1519864600265-abb23847ef2c?auto=format&fit=crop&w=900&q=80" 
            class="d-block w-100" alt="Producto 3" style="height:340px;object-fit:cover;" />
          <div class="carousel-caption d-none d-md-block p-4">
            <h2 class="fw-bold">Accesorios Exclusivos</h2>
            <p>Todo lo que necesitas para tu deporte favorito</p>
          </div>
        </div>
      </div>
      <button class="carousel-control-prev" type="button" data-bs-target="#mainCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Anterior</span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#mainCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Siguiente</span>
      </button>
    </div>

    <!-- Productos para Mujeres -->
    <section class="container mb-5">
      <h2 class="text-center fw-bold mb-4">Productos para Mujeres</h2>
      <div class="row g-4 justify-content-center">
        <?php
while ($row = $resultado->fetch_assoc()) {
    $id_producto = $row["id_producto"];
    $nombre = $row["nombre"];
    $precio = $row["precio"];
    $imagen = !empty($row["imagen_url"]) ? $row["imagen_url"] : "https://via.placeholder.com/400";

    echo '
    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
      <div class="card product-card h-100 shadow border-0">
        <img src="' . $imagen . '" class="card-img-top" alt="' . htmlspecialchars($nombre) . '" style="height:180px;object-fit:cover;">
        <div class="card-body text-center">
          <h5 class="card-title fw-bold">' . htmlspecialchars($nombre) . '</h5>
          <p class="card-text text-primary fw-bold">$' . $precio . ' MXN</p>
          <a href="detalle.php?id=' . $id_producto . '" class="btn btn-outline-primary w-100">Ver más</a>
        </div>
      </div>
    </div>';
}
        ?>
      </div>
    </section>

    <!-- Sección de Marcas -->
    <section class="container mb-5">
      <h2 class="text-center fw-bold mb-4">Nuestras Marcas</h2>
      <div class="row justify-content-center align-items-center g-4">
        <div class="col-6 col-sm-3 text-center">
          <img src="https://upload.wikimedia.org/wikipedia/commons/a/a6/Logo_NIKE.svg" alt="Nike" style="height:60px;object-fit:contain;filter: grayscale(1);transition:filter .2s;" onmouseover="this.style.filter='none'" onmouseout="this.style.filter='grayscale(1)'">
        </div>
        <div class="col-6 col-sm-3 text-center">
          <img src="https://upload.wikimedia.org/wikipedia/commons/2/20/Adidas_Logo.svg" alt="Adidas" style="height:60px;object-fit:contain;filter: grayscale(1);transition:filter .2s;" onmouseover="this.style.filter='none'" onmouseout="this.style.filter='grayscale(1)'">
        </div>
        <div class="col-6 col-sm-3 text-center">
          <img src="https://1000marcas.net/wp-content/uploads/2019/12/Puma-Logo-5.png" alt="Puma" style="height:60px;object-fit:contain;filter: grayscale(1);transition:filter .2s;" onmouseover="this.style.filter='none'" onmouseout="this.style.filter='grayscale(1)'">
        </div>
        <div class="col-6 col-sm-3 text-center">
          <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/4/44/Under_armour_logo.svg/1280px-Under_armour_logo.svg.png" alt="Under Armour" style="height:60px;object-fit:contain;filter: grayscale(1);transition:filter .2s;" onmouseover="this.style.filter='none'" onmouseout="this.style.filter='grayscale(1)'">
        </div>
      </div>
    </section>

    <?php include "cardLogin.php" ?>
    <!-- Footer -->
    <footer class="bg-dark text-white pt-5 pb-3 mt-5">
      <div class="container">
        <div class="row gy-4">
          <div class="col-12 col-md-4 text-center text-md-start">
            <a class="navbar-brand fw-bold text-white mb-2 d-inline-block" href="#" style="font-size:1.5rem;">Tienda<span style="color:#0d6efd;">Deportiva</span></a>
            <p class="small mt-2 mb-0">
              Tu tienda de confianza para ropa, calzado y accesorios deportivos de las mejores marcas.
            </p>
          </div>
          <div class="col-12 col-md-4 text-center">
            <h6 class="fw-bold mb-3">Contacto</h6>
            <ul class="list-unstyled mb-0">
              <li><i class="fa-solid fa-envelope me-2"></i> contacto@tiendadeportiva.com</li>
              <li><i class="fa-solid fa-phone me-2"></i> +52 55 1234 5678</li>
              <li><i class="fa-solid fa-location-dot me-2"></i> Queretaro, México</li>
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

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="../js/index.js"></script>

</body>
</html>

<?php $conn->close(); ?>
