// Cargar los módulos al cargar la página
window.onload = function() {
    listarModulos();
};

// Enviar el formulario de creación o modificación
document.getElementById('formModulo').onsubmit = function(event) {
    event.preventDefault();
    let formData = new FormData(this);
    let action = formData.get('id') ? 'update' : 'create';
    formData.append('action', action);

    fetch('modulos.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        alert(data);
        listarModulos();
        document.getElementById('formModulo').reset();
    });
};

// Listar los módulos
function listarModulos() {
    fetch('modulos.php?action=list')
        .then(response => response.json())
        .then(data => {
            let tabla = document.getElementById('tablaModulos').getElementsByTagName('tbody')[0];
            tabla.innerHTML = '';
            data.forEach(modulo => {
                let fila = `<tr>
                    <td>${modulo.nombre}</td>
                    <td>${modulo.descripcion}</td>
                    <td>${modulo.url}</td>
                    <td>${modulo.orden}</td>
                    <td>
                        <button onclick="editarModulo(${modulo.id}, '${modulo.nombre}', '${modulo.descripcion}', '${modulo.url}', ${modulo.orden})">Editar</button>
                        <button onclick="eliminarModulo(${modulo.id})">Eliminar</button>
                    </td>
                </tr>`;
                tabla.innerHTML += fila;
            });
        });
}

// Cargar los datos del módulo en el formulario para editar
function editarModulo(id, nombre, descripcion, url, orden) {
    document.getElementById('id').value = id;
    document.getElementById('nombre').value = nombre;
    document.getElementById('descripcion').value = descripcion;
    document.getElementById('url').value = url;
    document.getElementById('orden').value = orden;
}

// Eliminar un módulo
function eliminarModulo(id) {
    if (confirmarEliminacion()) {
        let formData = new FormData();
        formData.append('id', id);
        formData.append('action', 'delete');

        fetch('modulos.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            alert(data);
            listarModulos();
        });
    }
}
