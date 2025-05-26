<?php
include "config.php";

// Verificar que se envió el parámetro id
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "Producto no válido.";
    exit;
}

$id = intval($_GET['id']);

//  producto y su categoría
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
$categoria = strtolower($producto['categoria']);

// tallas  
$sql_tallas = "SELECT talla FROM talla_productos WHERE id_producto = ?";
$stmt_tallas = $conn->prepare($sql_tallas);
$stmt_tallas->bind_param("i", $id);
$stmt_tallas->execute();
$res_tallas = $stmt_tallas->get_result();
$tallas = [];
while ($row = $res_tallas->fetch_assoc()) {
    $tallas[] = $row['talla'];
}

//  reseñas 
$sql_resenas = "SELECT u.nombre AS usuario, r.comentario, r.calificacion, r.fecha
                FROM resenas r
                JOIN usuarios u ON r.id_usuario = u.id_usuario
                WHERE r.id_producto = ?
                ORDER BY r.fecha DESC";
$stmt_resenas = $conn->prepare($sql_resenas);
$stmt_resenas->bind_param("i", $id);
$stmt_resenas->execute();
$res_resenas = $stmt_resenas->get_result();
$resenas = [];
while ($row = $res_resenas->fetch_assoc()) {
    $resenas[] = $row;
}
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

        .navbar-brand span {
            color: #0d6efd;
        }

        .carousel-caption {
            background: rgba(0, 0, 0, 0.45);
            border-radius: 1rem;
        }

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
        <a class="navbar-brand fw-bold" href="../index.php">ALI<span>Sports</span></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" 
            aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
          <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
            <li class="nav-item"><a class="nav-link" href="../index.php">Inicio</a></li>
            <li class="nav-item"><a class="nav-link" href="hombre.php">Hombres</a></li>
            <li class="nav-item"><a class="nav-link" href="mujer.php">Mujeres</a></li>
            <li class="nav-item"><a class="nav-link" href="niño.php">Niños</a></li>
            <li class="nav-item"><a class="nav-link" href="marcas.php">Marcas</a></li>
          </ul>
         <div class="d-flex gap-3" id="userNavArea">

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
                <?php if (count($tallas) > 0): ?>
                    <div class="mb-3">
                        <label for="select-talla" class="form-label fw-bold">Talla:</label>
                        <select id="select-talla" class="form-select mb-2">
                            <?php foreach ($tallas as $talla): ?>
                                <option value="<?= htmlspecialchars($talla) ?>"><?= htmlspecialchars($talla) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php endif; ?>
                <button 
                  class="btn btn-primary btn-add-cart w-100"
                  data-id="<?= $id ?>"
                  data-nombre="<?= htmlspecialchars($producto['nombre']) ?>"
                  data-precio="<?= $producto['precio'] ?>"
                  data-imagen="<?= $producto['imagen_url'] ?>"
                  <?php if (count($tallas) > 0): ?>
                    data-talla=""
                  <?php endif; ?>
                  id="btnAddCart"
                >
                  <i class="fa-solid fa-cart-plus"></i> Agregar al carrito
                </button>
            </div>
        </div>

        <!-- Reseñas -->
        <div class="mt-5">
            <div class="d-flex align-items-center mb-3">
                <h4 class="fw-bold mb-0 me-3">Reseñas</h4>
                <?php
                $promedio = 0;
                if (count($resenas) > 0) {
                    $suma = 0;
                    foreach ($resenas as $r) {
                        $suma += $r['calificacion'];
                    }
                    $promedio = $suma / count($resenas);
                }
                ?>
                <?php if (count($resenas) > 0): ?>
                    <span class="d-flex align-items-center">
                        <?php
                        $rounded = round($promedio * 2) / 2; // redondea a 0.5
                        for ($i = 1; $i <= 5; $i++) {
                            if ($rounded >= $i) {
                                echo '<i class="fa-solid fa-star text-warning" style="font-size:1.7rem;"></i>';
                            } elseif ($rounded >= $i - 0.5) {
                                echo '<i class="fa-solid fa-star-half-stroke text-warning" style="font-size:1.7rem;"></i>';
                            } else {
                                echo '<i class="fa-regular fa-star text-secondary" style="font-size:1.7rem;"></i>';
                            }
                        }
                        ?>
                        <span class="ms-2 fw-bold" style="font-size:1.2rem;">
                            <?= number_format($promedio, 1) ?>
                        </span>
                    </span>
                <?php else: ?>
                    <span class="text-muted ms-2">Sin calificaciones</span>
                <?php endif; ?>
            </div>
            <?php if (count($resenas) > 0): ?>
                <?php $primera = $resenas[0]; ?>
                <div class="border rounded p-3 mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-bold"><?= htmlspecialchars($primera['usuario']) ?></span>
                        <span>
                            <?php for ($i = 0; $i < 5; $i++): ?>
                                <i class="fa-star<?= $i < $primera['calificacion'] ? ' fa-solid text-warning' : ' fa-regular text-secondary' ?>" style="font-size: 1.5rem;"></i>
                            <?php endfor; ?>
                        </span>
                    </div>
                    <div class="text-muted small mb-1"><?= date('d/m/Y', strtotime($primera['fecha'])) ?></div>
                    <div><?= nl2br(htmlspecialchars($primera['comentario'])) ?></div>
                </div>
                <?php if (count($resenas) > 1): ?>
                    <button class="btn btn-link p-0 mb-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseResenas" aria-expanded="false" aria-controls="collapseResenas">
                        Ver todas las reseñas (<?= count($resenas) ?>)
                    </button>
                    <div class="collapse" id="collapseResenas">
                        <?php foreach (array_slice($resenas, 1) as $resena): ?>
                            <div class="border rounded p-3 mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-bold"><?= htmlspecialchars($resena['usuario']) ?></span>
                                    <span>
                                        <?php for ($i = 0; $i < 5; $i++): ?>
                                            <i class="fa-star<?= $i < $resena['calificacion'] ? ' fa-solid text-warning' : ' fa-regular text-secondary' ?>" style="font-size: 1.5rem;"></i>
                                        <?php endfor; ?>
                                    </span>
                                </div>
                                <div class="text-muted small mb-1"><?= date('d/m/Y', strtotime($resena['fecha'])) ?></div>
                                <div><?= nl2br(htmlspecialchars($resena['comentario'])) ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="alert alert-info">Este producto aún no tiene reseñas.</div>
            <?php endif; ?>
        </div>
    </main>

    <?php include "cardLogin.php" ?>
    <!-- Footer -->
    <footer class="bg-dark text-white pt-5 pb-3 mt-5">
        <div class="container">
            <div class="row gy-4">
                <div class="col-12 col-md-4 text-center text-md-start">
                    <a class="navbar-brand fw-bold text-white mb-2 d-inline-block" href="#" style="font-size:1.5rem;">ALI<span style="color:#0d6efd;">Sports</span></a>
                    <p class="small mt-2 mb-0">Tu tienda de confianza para ropa, calzado y accesorios deportivos de las mejores marcas.</p>
                </div>
                <div class="col-12 col-md-4 text-center">
                    <h6 class="fw-bold mb-3">Contacto</h6>
                    <ul class="list-unstyled mb-0">
                        <li><i class="fa-solid fa-envelope me-2"></i> contacto@alisports.com</li>
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
                    <small>© 2025 ALI Sports. Todos los derechos reservados.</small>
                </div>
            </div>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../js/index.js"></script>
    <script>
    // Si hay select de talla, agrega la talla seleccionada al botón antes de agregar al carrito
    document.addEventListener('DOMContentLoaded', function() {
        const selectTalla = document.getElementById('select-talla');
        const btnAddCart = document.getElementById('btnAddCart');
        if (selectTalla && btnAddCart) {
            btnAddCart.setAttribute('data-talla', selectTalla.value);
            selectTalla.addEventListener('change', function() {
                btnAddCart.setAttribute('data-talla', this.value);
            });
        }
    });
    </script>
</body>

</html>
