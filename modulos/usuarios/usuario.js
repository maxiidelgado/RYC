function editUser(id, nombre, apellido, username) {
    // Mostrar el modal de edición
    const modal = document.getElementById('editModal');
    document.getElementById('form-title').textContent = 'Modificar Usuario';
    document.getElementById('modificar-id').value = id;
    document.getElementById('modificar-nombre').value = nombre;
    document.getElementById('modificar-apellido').value = apellido;
    document.getElementById('modificar-username').value = username;
    modal.style.display = "block";
}

function closeModal() {
    const modal = document.getElementById('editModal');
    modal.style.display = "none";
}

function deleteUser(id) {
    if (confirm('¿Estás seguro de que deseas eliminar este usuario?')) {
        const form = document.createElement('form');
        form.method = 'post';
        form.action = '';
        form.innerHTML = `<input type="hidden" name="delete" value="true"><input type="hidden" name="id" value="${id}">`;
        document.body.appendChild(form);
        form.submit();
    }
}

// Cerrar el modal cuando se hace clic en el fondo
window.onclick = function(event) {
    const modal = document.getElementById('editModal');
    if (event.target === modal) {
        closeModal();
    }
}
