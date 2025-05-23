<!-- Productos destacados -->
    <section class="container mb-5">
      <h2 class="text-center fw-bold mb-4">Productos Destacados</h2>
      <div class="row g-4 justify-content-center">
        
                <?php 

        $querySelect = "SELECT * FROM productos";
        $eje = mysqli_query($conn,$querySelect);
        while ($datos = mysqli_fetch_array($eje)) { ?>

                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                <div class="card product-card h-100 shadow border-0">
                    <img src=<?=$datos['imagen_url']?> class="card-img-top" alt=<?=$datos['descripcion']?> style="height:180px;object-fit:cover;">
                    <div class="card-body text-center">
                    <h5 class="card-title fw-bold"><?=$datos['nombre']?></h5>
            <p class="card-text text-primary fw-bold">$<?=$datos['precio']?></p>
            <a href="#" class="btn btn-outline-primary w-100">Ver más</a>
                    </div>
                </div>
                </div>
        <?php } ?>
      </div>
    </section>