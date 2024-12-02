function editarCategoria(id, nombre, descripcion) {
    // Rellenar el formulario de modificación con los datos de la categoría seleccionada
    document.getElementById('modificar-id').value = id;
    document.getElementById('modificar-nombre').value = nombre;
    document.getElementById('modificar-descripcion').value = descripcion;

    // Mostrar el formulario de modificación y ocultar el de alta
    document.getElementById('modificar-form').style.display = 'block';
    document.getElementById('alta-form').style.display = 'none';
}