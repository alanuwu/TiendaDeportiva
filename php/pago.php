<?php
include 'config.php';
session_start();

// Procesar el pago al enviar el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener usuario desde la sesión
    if (!isset($_SESSION['id_usuario'])) {
        echo "<script>alert('Debes iniciar sesión para realizar el pedido.'); window.location.href='../index.php';</script>";
        exit();
    }
    $id_usuario = $_SESSION['id_usuario'];
    $nombreTitular = mysqli_real_escape_string($conn, $_POST['nombreTitular']);
    $numeroTarjeta = mysqli_real_escape_string($conn, $_POST['numeroTarjeta']);
    $expiracion = mysqli_real_escape_string($conn, $_POST['expiracion']);
    $cvv = mysqli_real_escape_string($conn, $_POST['cvv']);
    $direccion = mysqli_real_escape_string($conn, $_POST['direccion']);
    $productos = json_decode($_POST['productos'], true);

    // Insertar pedido
    $sqlPedido = "INSERT INTO pedidos (id_usuario, fecha_pedido, estado,total) VALUES ('$id_usuario', NOW(), 'Pendiente',78765)";
    if ($conn->query($sqlPedido)) {
        $id_pedido = $conn->insert_id;
        foreach ($productos as $prod) {
            $id_producto = intval($prod['id']);
            $precio = floatval($prod['precio']);
            // Insertar detalle del pedido
            $conn->query("INSERT INTO detalle_pedido (id_pedido, id_producto, cantidad, precio_unitario) VALUES ($id_pedido, $id_producto, 1, $precio)");
            // Actualizar inventario (restar 1)
            $conn->query("UPDATE inventario SET cantidad = cantidad - 1 WHERE id_producto = $id_producto AND cantidad > 0");
        }
        echo "<script>
            alert('¡Pedido realizado con éxito!');
            localStorage.removeItem('cart');
            window.location.href='../index.php';
        </script>";
        exit();
    } else {
        echo "<script>alert('Error al procesar el pedido.'); window.history.back();</script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Detalles del Pago - Tienda Deportiva</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500;700&display=swap" rel="stylesheet" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <style>
        body { font-family: 'Montserrat', Arial, sans-serif; background: #f7f7f7; }
        .navbar-brand span { color: #0d6efd; }
        .card { border-radius: 1rem; }
        .product-image { height: 80px; object-fit: cover; border-radius: 0.5rem; }
        .form-section { background: #fff; border-radius: 1rem; box-shadow: 0 2px 12px rgba(0,0,0,0.07); padding: 2rem; }
        .order-summary { background: #fff; border-radius: 1rem; box-shadow: 0 2px 12px rgba(0,0,0,0.07); padding: 2rem; }
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
            <li class="nav-item"><a class="nav-link active" href="pago.php">Detalles del pago</a></li>
          </ul>
          <div class="d-flex gap-3" id="userNavArea">
            <!-- Aquí se insertará dinámicamente el icono o el botón -->
          </div>
        </div>
      </div>
    </nav>

    <div class="container my-5">
      <div class="row g-4">
        <!-- Resumen del pedido -->
        <div class="col-lg-7">
          <div class="order-summary mb-4">
            <h4 class="fw-bold mb-4"><i class="fa-solid fa-cart-shopping me-2"></i>Resumen del Carrito</h4>
            <div id="detalleCarrito">
              <!-- Aquí se cargan los productos del carrito con JS -->
            </div>
          </div>
        </div>
        <!-- Formulario de pago -->
        <div class="col-lg-5">
          <div class="form-section">
            <h4 class="fw-bold mb-4"><i class="fa-solid fa-credit-card me-2"></i>Forma de Pago</h4>
            <form id="formPago" method="post" action="procesar_pago.php">
              <div class="mb-3">
                <label for="nombreTitular" class="form-label">Nombre del Titular</label>
                <input type="text" class="form-control" id="nombreTitular" name="nombreTitular" required>
              </div>
              <div class="mb-3">
                <label for="numeroTarjeta" class="form-label">Número de Tarjeta</label>
                <input type="text" class="form-control" id="numeroTarjeta" name="numeroTarjeta" maxlength="16" required pattern="\d{16}">
              </div>
              <div class="row">
                <div class="col-6 mb-3">
                  <label for="expiracion" class="form-label">Expiración</label>
                  <input type="text" class="form-control" id="expiracion" name="expiracion" placeholder="MM/AA" required pattern="\d{2}/\d{2}">
                </div>
                <div class="col-6 mb-3">
                  <label for="cvv" class="form-label">CVV</label>
                  <input type="text" class="form-control" id="cvv" name="cvv" maxlength="4" required pattern="\d{3,4}">
                </div>
              </div>
              <div class="mb-3">
                <label for="direccion" class="form-label">Dirección de Envío</label>
                <textarea class="form-control" id="direccion" name="direccion" rows="2" required></textarea>
              </div>
              <input type="hidden" name="productos" id="productosInput">
              <input type="hidden" name="total" id="totalInput">
              <div class="d-grid">
                <button type="submit" class="btn btn-success btn-lg mt-3">Pagar</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <?php 
            
    ?>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Cargar productos del carrito desde localStorage
    function cargarCarritoPago() {
      const carrito = JSON.parse(localStorage.getItem('cart') || '[]');
      const contenedor = document.getElementById('detalleCarrito');
      if (!carrito.length) {
        contenedor.innerHTML = '<div class="alert alert-warning text-center">No hay productos en el carrito.</div>';
        document.getElementById('formPago').style.display = 'none';
        return;
      }
      let total = 0;
      contenedor.innerHTML = carrito.map(item => {
        total += parseFloat(item.precio);
        return `
          <div class="d-flex align-items-center mb-3 border-bottom pb-2">
            <img src="${item.imagen}" alt="${item.nombre}" class="product-image me-3">
            <div>
              <div class="fw-bold">${item.nombre}</div>
              <div class="text-primary">$${item.precio} MXN</div>
            </div>
          </div>
        `;
      }).join('');
      contenedor.innerHTML += `
        <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top">
          <span class="fw-bold">Total:</span>
          <span class="fw-bold text-primary fs-5">$${total.toFixed(2)} MXN</span>
        </div>
      `;
      // Pasar productos y total al formulario para el backend
      document.getElementById('productosInput').value = JSON.stringify(carrito);
      document.getElementById('totalInput').value = total.toFixed(2);
    }

    document.addEventListener('DOMContentLoaded', cargarCarritoPago);
    </script>
</body>
</html>

<?php $conn->close(); ?>

<!-- Modal Carrito de Compras -->
<div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="cartModalLabel"><i class="fa-solid fa-cart-shopping me-2"></i>Carrito de Compras</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <div id="cartContent">
          <!-- Aquí se mostrarán los productos del carrito -->
        </div>
      </div>
    </div>
  </div>
</div>
