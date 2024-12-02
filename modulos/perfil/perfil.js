function editarPerfil(id) {
    var altaForm = document.getElementById('alta-form');
    var modificarForm = document.getElementById('modificar-form');
    var title = document.getElementById('modificar-title');

    // Ocultar el formulario de alta y mostrar el formulario de modificación
    altaForm.style.display = 'none';
    modificarForm.style.display = 'block';

    fetch('perfil.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'accion=editar&id_perfil=' + id,
    })
    .then(response => response.json())
    .then(data => {
        if (data) {
            document.getElementById('modificar-id').value = data.id;
            document.getElementById('nombre-modificar').value = data.nombre;
            document.getElementById('descripcion-modificar').value = data.descripcion;
            document.getElementById('modificar-accion').value = 'modificar';
            title.textContent = 'Modificar Perfil';
            modificarForm.querySelector('input[type="submit"]').value = 'Guardar Cambios';
        } else {
            console.error('No se recibieron datos del servidor.');
        }
    })
    .catch(error => console.error('Error:', error));
}
