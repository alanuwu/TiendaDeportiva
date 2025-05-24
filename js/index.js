// Función para renderizar el área de usuario en la navbar
function renderUserNav() {
  const userNav = document.getElementById('userNavArea');
  userNav.innerHTML = '';
  const usuario = localStorage.getItem('usuario');
  if (usuario) {
    // Si existe usuario, muestra el icono de usuario y el carrito
    userNav.innerHTML = `
      <a href="#" class="text-white" data-bs-toggle="modal" data-bs-target="#userInfoModal">
        <i class="fa-regular fa-user fa-lg"></i>
      </a>
      <a href="#" class="text-white"><i class="fa-solid fa-cart-shopping fa-lg"></i></a>
    `;
  } else {
    // Si no existe usuario, muestra el botón de iniciar sesión y el carrito
    userNav.innerHTML = `
      <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#loginModal">
        <i class="fa-regular fa-user"></i> Iniciar Sesión
      </button>
      
    `;
  }
}

// Ejecuta al cargar la página
document.addEventListener('DOMContentLoaded', function() {
  renderUserNav();

  // Si tienes lógica para cerrar sesión, puedes limpiar localStorage y volver a renderizar:
  document.body.addEventListener('click', function(e) {
    if (e.target.closest('.btn-cerrar-sesion')) {
      localStorage.removeItem('usuario');
      renderUserNav();
      // Opcional: cerrar el modal de usuario si está abierto
      const userInfoModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('userInfoModal'));
      userInfoModal.hide();
    }
  });

  // Modal de usuario: muestra datos si existe usuario
  document.body.addEventListener('click', function(e) {
    if (e.target.closest('[data-bs-target="#userInfoModal"]')) {
      const usuario = JSON.parse(localStorage.getItem('usuario') || '{}');
      let html = `
        <ul class="list-group list-group-flush">
          <li class="list-group-item"><strong>ID:</strong> ${usuario.id_usuario ?? ''}</li>
          <li class="list-group-item"><strong>Nombre:</strong> ${usuario.nombre ?? ''}</li>
          <li class="list-group-item"><strong>Correo:</strong> ${usuario.correo ?? ''}</li>
          <li class="list-group-item"><strong>Numero Telefonico:</strong> ${usuario.telefono ?? ''}</li>
        </ul>
        <div class="mt-3 text-end">
          <button type="button" class="btn btn-danger btn-cerrar-sesion">Cerrar Sesión</button>
        </div>
      `;
      document.getElementById('userInfoContent').innerHTML = html;
    }
  });
});