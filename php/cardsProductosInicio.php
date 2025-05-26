<!-- Productos destacados -->
    <section class="container mb-5">
      <h2 class="text-center fw-bold mb-4">Productos Destacados</h2>
      <div class="row g-4 justify-content-center">
        
                <?php
    // Producto de Mujer
    $qMujer = "SELECT * FROM productos WHERE id_categoria = 2 LIMIT 1";
    $rMujer = mysqli_query($conn, $qMujer);
    if ($mujer = mysqli_fetch_assoc($rMujer)) { ?>
      <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
        <div class="card product-card h-100 shadow border-0">
          <img src="<?= $mujer['imagen_url'] ?>" class="card-img-top" alt="<?= $mujer['descripcion'] ?>" style="height:180px;object-fit:cover;">
          <div class="card-body text-center">
            <h5 class="card-title fw-bold"><?= $mujer['nombre'] ?></h5>
            <p class="card-text text-primary fw-bold mb-2">$<?= $mujer['precio'] ?></p>
            <a href="php/detalle.php?id=<?= $mujer['id_producto'] ?>" class="btn btn-outline-primary w-100 mb-2">Ver más</a>
          </div>
        </div>
      </div>
    <?php }

    // Producto de Hombre
    $qHombre = "SELECT * FROM productos WHERE id_categoria = 1 LIMIT 1";
    $rHombre = mysqli_query($conn, $qHombre);
    if ($hombre = mysqli_fetch_assoc($rHombre)) { ?>
      <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
        <div class="card product-card h-100 shadow border-0">
          <img src="<?= $hombre['imagen_url'] ?>" class="card-img-top" alt="<?= $hombre['descripcion'] ?>" style="height:180px;object-fit:cover;">
          <div class="card-body text-center">
            <h5 class="card-title fw-bold"><?= $hombre['nombre'] ?></h5>
            <p class="card-text text-primary fw-bold mb-2">$<?= $hombre['precio'] ?></p>
            <a href="php/detalle.php?id=<?= $hombre['id_producto'] ?>" class="btn btn-outline-primary w-100 mb-2">Ver más</a>
          </div>
        </div>
      </div>
    <?php }

    // Producto de Niños
    $qNinos = "SELECT * FROM productos WHERE id_categoria = 3  LIMIT 1";
    $rNinos = mysqli_query($conn, $qNinos);
    if ($nino = mysqli_fetch_assoc($rNinos)) { ?>
      <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
        <div class="card product-card h-100 shadow border-0">
          <img src="<?= $nino['imagen_url'] ?>" class="card-img-top" alt="<?= $nino['descripcion'] ?>" style="height:180px;object-fit:cover;">
          <div class="card-body text-center">
            <h5 class="card-title fw-bold"><?= $nino['nombre'] ?></h5>
            <p class="card-text text-primary fw-bold mb-2">$<?= $nino['precio'] ?></p>
            <a href="php/detalle.php?id=<?= $nino['id_producto'] ?>" class="btn btn-outline-primary w-100 mb-2">Ver más</a>
          </div>
        </div>
      </div>
    <?php }

    // Producto aleatorio que no sea de las anteriores categorías
    $qAleatorio = "SELECT * FROM productos WHERE id_categoria NOT IN (1,2,3) ORDER BY RAND() LIMIT 1";
    $rAleatorio = mysqli_query($conn, $qAleatorio);
    if ($aleatorio = mysqli_fetch_assoc($rAleatorio)) { ?>
      <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
        <div class="card product-card h-100 shadow border-0">
          <img src="<?= $aleatorio['imagen_url'] ?>" class="card-img-top" alt="<?= $aleatorio['descripcion'] ?>" style="height:180px;object-fit:cover;">
          <div class="card-body text-center">
            <h5 class="card-title fw-bold"><?= $aleatorio['nombre'] ?></h5>
            <p class="card-text text-primary fw-bold mb-2">$<?= $aleatorio['precio'] ?></p>
            <a href="php/detalle.php?id=<?= $aleatorio['id_producto'] ?>" class="btn btn-outline-primary w-100 mb-2">Ver más</a>
          </div>
        </div>
      </div>
    <?php } ?>
  </div>
</section>