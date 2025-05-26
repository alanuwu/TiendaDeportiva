<!-- Modal de Login Bootstrap -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="loginModalLabel"><i class="fa-regular fa-user me-2"></i>Iniciar Sesión</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <form action="login.php" method="POST" autocomplete="off">
        <div class="modal-body">
          <div class="mb-3">
            <label for="loginEmail" class="form-label">Correo electrónico</label>
            <input type="email" class="form-control" id="loginEmail" name="email" required autofocus>
          </div>
          <div class="mb-3">
            <label for="loginPassword" class="form-label">Contraseña</label>
            <input type="password" class="form-control" id="loginPassword" name="password" required>
          </div>
        </div>
        <div class="modal-footer d-flex justify-content-between">
          <a href="#" class="small text-decoration-none" data-bs-toggle="modal" data-bs-target="#registerModal" data-bs-dismiss="modal">¿No tienes cuenta? Regístrate</a>
          <button type="submit" class="btn btn-primary">Entrar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal de Registro Bootstrap -->
<div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="registerModalLabel"><i class="fa-regular fa-user me-2"></i>Crear Cuenta</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <form action="sign-in.php" method="POST" autocomplete="off">
        <div class="modal-body">
          <div class="mb-3">
            <label for="registerNombre" class="form-label">Nombre completo</label>
            <input type="text" class="form-control" id="registerNombre" name="nombre" required>
          </div>
          <div class="mb-3">
            <label for="registerEmail" class="form-label">Correo electrónico</label>
            <input type="email" class="form-control" id="registerEmail" name="email" required>
          </div>
          <div class="mb-3">
            <label for="registerTelefono" class="form-label">Teléfono</label>
            <input type="tel" class="form-control" id="registerTelefono" name="telefono" required>
          </div>
          <div class="mb-3">
            <label for="registerPassword" class="form-label">Contraseña</label>
            <input type="password" class="form-control" id="registerPassword" name="password" required>
          </div>
        </div>
        <div class="modal-footer d-flex justify-content-between">
          <a href="#" class="small text-decoration-none" data-bs-toggle="modal" data-bs-target="#loginModal" data-bs-dismiss="modal">¿Ya tienes cuenta? Inicia sesión</a>
          <button type="submit" class="btn btn-success">Registrarme</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal de Información del Usuario -->
<div class="modal fade" id="userInfoModal" tabindex="-1" aria-labelledby="userInfoModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="userInfoModalLabel"><i class="fa-regular fa-user me-2"></i>Mi Perfil</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <div id="userInfoContent">
          <!-- Aquí se mostrarán los datos del usuario -->
        </div>
      </div>
     
    </div>
  </div>
</div>

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

<a href="#" class="text-white" data-bs-toggle="modal" data-bs-target="#cartModal">
  <i class="fa-solid fa-cart-shopping fa-lg"></i>
</a>