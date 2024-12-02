document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('registro-form');
    const errorMessage = document.getElementById('error-message'); // Contenedor para mensajes de error

    form.addEventListener('submit', async (event) => {
        event.preventDefault(); // Evita el envío del formulario

        const username = form.username.value.trim();
        const correo = form.correo.value.trim();
        const errorMessages = [];

        // Validación de nombre de usuario
        if (username.length < 3 || username.length > 20) {
            errorMessages.push('El nombre de usuario debe tener entre 3 y 20 caracteres.');
        }

        // Validación de correo electrónico
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailPattern.test(correo)) {
            errorMessages.push('Ingresa un correo electrónico válido.');
        }

        // Validaciones de longitud del formulario
        if (errorMessages.length > 0) {
            errorMessage.innerHTML = errorMessages.join('<br>'); // Muestra los errores en el contenedor
            errorMessage.style.display = 'block'; // Muestra el contenedor
            errorMessage.classList.add('error'); // Clase para errores
            return; // Detiene el envío del formulario
        }

        // Oculta el mensaje de error si todo es válido
        errorMessage.style.display = 'none';

        // Verificación en el servidor
        const response = await fetch('verificar_usuario.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ username, correo }),
        });

        const result = await response.json();
        if (result.exists) {
            errorMessage.innerHTML = 'El nombre de usuario o el correo ya están en uso.'; // Mensaje de error
            errorMessage.style.display = 'block'; // Muestra el contenedor
            errorMessage.classList.add('error'); // Clase para errores
        } else {
            form.submit(); // Envío del formulario si todo es válido
        }
    });
});
