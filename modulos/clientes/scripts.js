// Función para editar cliente
function editCliente(id, nombre, apellido, correo, estado) {
    document.getElementById('clienteId').value = id;
    document.getElementById('clienteNombre').value = nombre;
    document.getElementById('clienteApellido').value = apellido;
    document.getElementById('clienteCorreo').value = correo;
    document.getElementById('clienteEstado').value = estado;
    $('#modalCliente').modal('show');
}

// Función para eliminar cliente
function deleteCliente(id) {
    document.getElementById('deleteId').value = id;
    $('#deleteModal').modal('show');
}
