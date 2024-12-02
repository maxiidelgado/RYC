
function mostrarFormularioModificacion(id, descripcion, provinciaId) {
    // Mostrar el formulario de modificación
    document.getElementById('formulario-modificar').style.display = 'block';
    // Rellenar los campos del formulario con los datos de la localidad seleccionada
    document.getElementById('id_localidad_modificar').value = id;
    document.getElementById('descripcion_modificar').value = descripcion;
    document.getElementById('rela_provincia_modificar').value = provinciaId;
}

function ocultarFormularioModificacion() {
    // Ocultar el formulario de modificación
    document.getElementById('formulario-modificar').style.display = 'none';
}
