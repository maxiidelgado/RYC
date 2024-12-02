// Validar formulario de usuario
document.querySelector('form').addEventListener('submit', function (event) {
    // Obtener valores del formulario
    var nombre = document.getElementById('user-nombre').value.trim();
    var apellido = document.getElementById('user-apellido').value.trim();
    var fechaNacimiento = document.getElementById('user-fecha-nacimiento').value;
    var username = document.getElementById('user-username').value.trim();
    var password = document.getElementById('user-password').value.trim();

    // Expresiones regulares
    var nombreApellidoRegex = /^[a-zA-ZÀ-ÿ\s]{2,}$/; // Solo letras y espacios, mínimo 2 caracteres
    var usernameRegex = /^[a-zA-Z0-9_]{3,}$/; // Letras, números y guión bajo, mínimo 3 caracteres
    var passwordRegex = /^.{6,}$/; // Mínimo 6 caracteres

    // Validaciones
    if (!nombreApellidoRegex.test(nombre)) {
        alert('El nombre debe tener al menos 2 caracteres y solo puede contener letras y espacios.');
        event.preventDefault();
        return;
    }

    if (!nombreApellidoRegex.test(apellido)) {
        alert('El apellido debe tener al menos 2 caracteres y solo puede contener letras y espacios.');
        event.preventDefault();
        return;
    }

    if (!fechaNacimiento) {
        alert('Por favor, ingresa una fecha de nacimiento válida.');
        event.preventDefault();
        return;
    }

    if (!usernameRegex.test(username)) {
        alert('El nombre de usuario debe tener al menos 3 caracteres y solo puede contener letras, números y guiones bajos.');
        event.preventDefault();
        return;
    }

    if (document.querySelector('button[name="create"]').disabled === false && !passwordRegex.test(password)) {
        alert('La contraseña debe tener al menos 6 caracteres.');
        event.preventDefault();
        return;
    }
});

// Llenar el formulario para editar usuario
function editUser(id, nombre, apellido, username) {
    document.getElementById('user-id').value = id;
    document.getElementById('user-nombre').value = nombre;
    document.getElementById('user-apellido').value = apellido;
    document.getElementById('user-username').value = username;
    document.getElementById('user-password').required = false;
    document.querySelector('button[name="create"]').disabled = true;
    document.querySelector('button[name="update"]').disabled = false;
    document.querySelector('button[name="delete"]').disabled = true;
}

// Confirmación para eliminar usuario
function deleteUser(id) {
    if (confirm('¿Estás seguro de que deseas eliminar este usuario?')) {
        document.getElementById('user-id').value = id;
        document.querySelector('button[name="delete"]').disabled = false;
        document.querySelector('form').submit();
    }
}


// Validar formulario de alta, baja o modificación de tipo de documento
document.querySelectorAll('form').forEach(function(form) {
    form.addEventListener('submit', function(event) {
        // Verificar qué botón fue presionado
        var submitType = event.submitter.name;

        if (submitType === 'alta' || submitType === 'modificar') {
            var descripcion = form.querySelector('input[name="descripcion"]').value.trim();
            var descripcionRegex = /^[a-zA-ZÀ-ÿ\s]{2,}$/; // Solo letras y espacios, mínimo 2 caracteres

            if (!descripcionRegex.test(descripcion)) {
                alert('La descripción debe tener al menos 2 caracteres y solo puede contener letras y espacios.');
                event.preventDefault();
                return;
            }
        } else if (submitType === 'baja') {
            var confirmacion = confirm('¿Estás seguro de que deseas eliminar este tipo de documento?');
            if (!confirmacion) {
                event.preventDefault();
                return;
            }
        }
    });
});
