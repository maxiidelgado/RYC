<?php
// Incluir archivo de configuración para la base de datos
require '../database/db.php'; 

// Obtener el ID del pedido desde el parámetro GET y validar que sea numérico
$pedido_id = isset($_GET['pedido_id']) && is_numeric($_GET['pedido_id']) ? $_GET['pedido_id'] : null;

// Verificar que el ID del pedido es válido
if ($pedido_id) {
    // Verificar si el pedido existe
    $query = $pdo->prepare("SELECT * FROM pedido WHERE id_pedido = ?");
    $query->execute([$pedido_id]);
    $pedido = $query->fetch(PDO::FETCH_ASSOC);

    if (!$pedido) {
        die("Error: El pedido no existe.");
    }

    // Verificar si el pedido ha sido confirmado por el administrador
    $queryConfirmacion = $pdo->prepare("SELECT * FROM notificaciones_admin WHERE rela_pedido = ? AND estado = 'Confirmado'");
    $queryConfirmacion->execute([$pedido_id]);
    $confirmacion = $queryConfirmacion->fetch(PDO::FETCH_ASSOC);

    // Si el pedido está confirmado, redirigir al usuario a una página de éxito
    if ($confirmacion) {
        header("Location: confirmacion.php?pedido_id=" . $pedido_id);
        exit();
    }
} else {
    die("Error: El ID del pedido no está presente o no es válido.");
}

// Acción de cancelar el pedido
if (isset($_POST['cancelar'])) {
    // Realizar la cancelación del pedido (ejemplo de actualización en base de datos)
    $queryCancelacion = $pdo->prepare("UPDATE pedido SET estado = 'Cancelado' WHERE id_pedido = ?");
    $queryCancelacion->execute([$pedido_id]);

    // Redirigir a la página principal
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Esperando Confirmación</title>
    <!-- Cargar Font Awesome para los íconos -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f9f3e0; /* Color cálido de fondo */
            color: #6c4f1d; /* Color marrón suave para el texto */
            text-align: center;
            padding: 50px;
            animation: backgroundAnimation 5s infinite alternate;
        }

        h1 {
            color: #b03a2e; /* Color cálido para el título */
            font-size: 2.5em;
        }

        p {
            font-size: 18px;
            color: #d35400; /* Naranja cálido para el texto */
        }

        /* Animación para el fondo */
        @keyframes backgroundAnimation {
            0% { background-color: #f9f3e0; }
            50% { background-color: #f3e5ab; }
            100% { background-color: #f8c471; }
        }

        /* Estilo para el botón de cancelar */
        .btn-cancelar {
            display: inline-block;
            background-color: #e67e22; /* Naranja cálido */
            color: #fff;
            padding: 12px 25px;
            font-size: 18px;
            border-radius: 30px;
            text-decoration: none;
            margin-top: 30px;
            cursor: pointer;
            border: none;
            transition: background-color 0.3s;
        }

        .btn-cancelar:hover {
            background-color: #d35400; /* Cambiar a un tono más oscuro */
        }

        /* Loader animado con icono de comida */
        .loader {
            margin: 20px auto;
            font-size: 50px;
            color: #e67e22; /* Naranja cálido */
            animation: spin 1.5s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
        }

        .modal-content {
            background-color: #fff;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 60%;
            text-align: center;
        }

        .close {
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            float: right;
        }

        .close:hover,
        .close:focus {
            color: black;
            cursor: pointer;
        }

        .btn-confirmar-cancelar {
            background-color: #e67e22;
            color: white;
            padding: 12px 25px;
            font-size: 18px;
            border: none;
            border-radius: 30px;
            cursor: pointer;
        }

        .btn-confirmar-cancelar:hover {
            background-color: #d35400;
        }

    </style>
</head>
<body>
    <h1>Esperando Confirmación del Pedido...</h1>
    <p>Tu pedido está siendo procesado. Por favor, espera a que el administrador lo confirme.</p>

    <!-- Loader animado con un icono de comida (plato con cubiertos) -->
    <div class="loader">
        <i class="fas fa-utensils"></i> <!-- Icono de tenedor y cuchillo -->
    </div>

    <!-- Botón para cancelar el pedido -->
    <button class="btn-cancelar" onclick="showModal()">Cancelar Pedido</button>

    <!-- Modal de confirmación -->
    <div id="cancelModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>¿Estás seguro de que deseas cancelar el pedido?</h2>
            <form method="POST">
                <button type="submit" name="cancelar" class="btn-confirmar-cancelar">Sí, cancelar pedido</button>
                <button type="button" class="btn-confirmar-cancelar" onclick="closeModal()">No, volver</button>
            </form>
        </div>
    </div>

    <!-- Script para mostrar/ocultar el modal -->
    <script>
        // Función para mostrar el modal
        function showModal() {
            document.getElementById("cancelModal").style.display = "block";
        }

        // Función para cerrar el modal
        function closeModal() {
            document.getElementById("cancelModal").style.display = "none";
        }

        // Cerrar el modal si se hace clic fuera del mismo
        window.onclick = function(event) {
            if (event.target == document.getElementById("cancelModal")) {
                closeModal();
            }
        }
    </script>
</body>
</html>
