function editarBanner(id, imagen_url, link_url) {
    // Rellenar el formulario de modificación con los datos del banner seleccionado
    document.getElementById('modificar-id').value = id;
    document.getElementById('modificar-imagen-url').value = imagen_url;
    document.getElementById('modificar-link-url').value = link_url;

    // Mostrar el formulario de modificación
    document.getElementById('modificar-form').style.display = 'block';
    document.getElementById('alta-form').style.display = 'none';
}