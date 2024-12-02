function editProducto(id, nombre, descripcion, precio, stock) {
    // Mostrar el formulario de modificación y ocultar el de alta
    document.getElementById('alta-form').style.display = 'none';
    document.getElementById('modificar-form').style.display = 'block';
    document.getElementById('modificar-title').scrollIntoView();

    // Llenar el formulario de modificación con los datos del producto
    document.getElementById('modificar-id').value = id;
    document.getElementById('modificar-nombre').value = nombre;
    document.getElementById('modificar-descripcion').value = descripcion;
    document.getElementById('modificar-precio').value = precio;
    document.getElementById('modificar-stock').value = stock;
}

function deleteProducto(id) {
    if (confirm('¿Estás seguro de que deseas eliminar este producto?')) {
        const form = document.createElement('form');
        form.method = 'post';
        form.action = '';
        form.innerHTML = `<input type="hidden" name="delete" value="true"><input type="hidden" name="id" value="${id}">`;
        document.body.appendChild(form);
        form.submit();
    }
}