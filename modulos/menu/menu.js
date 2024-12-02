function editMenu(id, nombre, precio, estado, stock) {
    document.getElementById('menu-id').value = id;
    document.getElementById('menu-nombre').value = nombre;
    document.getElementById('menu-precio').value = precio;
    document.getElementById('menu-estado').value = estado;
    document.getElementById('menu-stock').value = stock; // Añadir stock

    // Cambiar el título y botones del formulario
    document.getElementById('form-title').textContent = 'Editar Menú';
    document.getElementById('create-btn').style.display = 'none';
    document.getElementById('update-btn').style.display = 'inline-block';
    document.getElementById('cancel-btn').style.display = 'inline-block';
}

function cancelEdit() {
    // Restablecer el formulario
    document.getElementById('menu-form').reset();
    document.getElementById('menu-id').value = '';

    // Cambiar el título y botones del formulario
    document.getElementById('form-title').textContent = 'Crear Nuevo Menú';
    document.getElementById('create-btn').style.display = 'inline-block';
    document.getElementById('update-btn').style.display = 'none';
    document.getElementById('cancel-btn').style.display = 'none';
}
