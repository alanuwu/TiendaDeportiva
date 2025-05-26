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
      <a href="#" class="text-white" data-bs-toggle="modal" data-bs-target="#cartModal">
        <i class="fa-solid fa-cart-shopping fa-lg"></i>
      </a>
    `;
  } else {
    // Si no existe usuario, muestra el botón de iniciar sesión y el carrito
    userNav.innerHTML = `
      <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#loginModal">
        <i class="fa-regular fa-user"></i> Iniciar Sesión
      </button>
      <a href="#" class="text-white" data-bs-toggle="modal" data-bs-target="#cartModal">
        <i class="fa-solid fa-cart-shopping fa-lg"></i>
      </a>
    `;
  }
}

// Carrito de compras
function getCart() {
  return JSON.parse(localStorage.getItem('cart') || '[]');
}
function setCart(cart) {
  localStorage.setItem('cart', JSON.stringify(cart));
}
function renderCart() {
  const cart = getCart();
  const cartContent = document.getElementById('cartContent');
  if (!cart.length) {
    cartContent.innerHTML = '<p class="text-center text-muted">El carrito está vacío.</p>';
    return;
  }
  let total = 0;
  cartContent.innerHTML = cart.map(item => {
    total += parseFloat(item.precio);
    return `
      <div class="d-flex align-items-center mb-3 border-bottom pb-2">
        <img src="${item.imagen}" alt="${item.nombre}" style="width:60px;height:60px;object-fit:cover;" class="rounded me-3">
        <div>
          <div class="fw-bold">${item.nombre}</div>
          <div class="text-primary">$${item.precio} MXN</div>
        </div>
        <button class="btn btn-sm btn-danger ms-auto btn-remove-cart" data-id="${item.id}"><i class="fa fa-trash"></i></button>
      </div>
    `;
  }).join('');
  cartContent.innerHTML += `
    <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top">
      <span class="fw-bold">Total:</span>
      <span class="fw-bold text-primary fs-5">$${total.toFixed(2)} MXN</span>
    </div>
  `;
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

  const cartModal = document.getElementById('cartModal');
  if (cartModal) {
    cartModal.addEventListener('show.bs.modal', renderCart);
  }
});

// Agregar producto al carrito
document.body.addEventListener('click', function(e) {
  const btn = e.target.closest('.btn-add-cart');
  if (btn) {
    const producto = {
      id: btn.getAttribute('data-id'),
      nombre: btn.getAttribute('data-nombre'),
      precio: btn.getAttribute('data-precio'),
      imagen: btn.getAttribute('data-imagen')
    };
    let cart = getCart();
    // Evitar duplicados (puedes sumar cantidades si quieres)
    if (!cart.find(p => p.id === producto.id)) {
      cart.push(producto);
      setCart(cart);
    }
    renderCart();
    // Abrir modal carrito
    const cartModal = bootstrap.Modal.getOrCreateInstance(document.getElementById('cartModal'));
    cartModal.show();
  }
});

// Eliminar producto del carrito
document.body.addEventListener('click', function(e) {
  const btn = e.target.closest('.btn-remove-cart');
  if (btn) {
    let cart = getCart();
    cart = cart.filter(p => p.id !== btn.getAttribute('data-id'));
    setCart(cart);
    renderCart();
  }
});

// Renderizar carrito al abrir modal
document.addEventListener('DOMContentLoaded', function() {
  renderUserNav();
  const cartModal = document.getElementById('cartModal');
  if (cartModal) {
    cartModal.addEventListener('show.bs.modal', renderCart);
  }
});