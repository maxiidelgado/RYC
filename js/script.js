document.querySelectorAll('.add-to-cart').forEach(button => {
    button.addEventListener('click', function() {
        const productId = this.dataset.id;
        // Aquí puedes agregar la lógica para agregar el producto al carrito.
        alert('Producto ' + productId + ' agregado al carrito.');
    });
});


function editUser(id, nombre, apellido, username) {
    document.getElementById('user-id').value = id;
    document.getElementById('user-nombre').value = nombre;
    document.getElementById('user-apellido').value = apellido;
    document.getElementById('user-username').value = username;
    document.getElementById('user-password').required = false;
}

function deleteUser(id) {
    if (confirm('¿Estás seguro de que deseas eliminar este usuario?')) {
        document.getElementById('user-id').value = id;
        document.querySelector('button[name="delete"]').click();
    }
}


function editBanner(id, imagen_url, link_url) {
    document.getElementById('banner-id').value = id;
    document.getElementById('banner-imagen-url').value = imagen_url;
    document.getElementById('banner-link-url').value = link_url;
}

function deleteBanner(id) {
    if (confirm('¿Estás seguro de que deseas eliminar este banner?')) {
        document.getElementById('banner-id').value = id;
        document.querySelector('button[name="delete"]').click();
    }
}
