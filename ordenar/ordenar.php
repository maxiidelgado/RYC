<?php
// Incluir archivo de configuración para conexión a la base de datos
require '../database/db.php'; // Cambiar según la ubicación de tu archivo db.php

// Iniciar sesión si no está iniciada ya
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario_id'])) {
    die("Error: Debes iniciar sesión para realizar un pedido.");
}

// Verificar si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $menu_id = $_POST['menu_id'];
    $cantidad = $_POST['cantidad'];
    $direccion = $_POST['direccion'];
    $metodo = $_POST['metodo_pago'];  // Corregido
    $usuario_id = $_SESSION['usuario_id']; // Usar el ID del usuario almacenado en la sesión

    // Validar que todos los campos tengan valor
    if (!empty($menu_id) && !empty($cantidad) && !empty($direccion) && !empty($usuario_id)) {
        // Obtener la información del menú
        $queryMenu = $pdo->prepare("SELECT * FROM Menu WHERE id = ?");
        $queryMenu->execute([$menu_id]);
        $menu = $queryMenu->fetch(PDO::FETCH_ASSOC);

        if (!$menu) {
            die("Error: Menú no encontrado.");
        }

        // Calcular el total del pedido
        $total = $menu['precio'] * $cantidad;

        // Insertar en la tabla Pedido
        $queryPedido = $pdo->prepare("INSERT INTO pedido (rela_usuario, direccion_entrega, fecha_pedido, estado, total, rela_metodo_pago) 
        VALUES (?, ?, NOW(), 'Pendiente', ?, ?)");
        $queryPedido->execute([$usuario_id, $direccion, $total, $metodo]);
        
        // Obtener el ID del pedido recién insertado
        $pedido_id = $pdo->lastInsertId();

        // Insertar en la tabla PedidoDetalle
        $queryDetalle = $pdo->prepare("INSERT INTO pedidodetalle (rela_pedido, rela_menu, cantidad) VALUES (?, ?, ?)");
        $queryDetalle->execute([$pedido_id, $menu_id, $cantidad]);

        // Actualizar el stock del menú
        $queryStock = $pdo->prepare("UPDATE Menu SET stock = stock - ? WHERE id = ?");
        $queryStock->execute([$cantidad, $menu_id]);

        $queryNotificacion = $pdo->prepare("INSERT INTO notificaciones_admin (rela_pedido, mensaje, fecha_notificacion, estado) 
        VALUES (?, ?, NOW(), 'Pendiente')");
        $queryNotificacion->execute([
            $pedido_id,  // Asegúrate de pasar el ID del pedido recién insertado
            "Nuevo pedido de usuario " . $usuario_id . ". Dirección: " . $direccion . ". Total: $" . $total,
        ]);

        // Redirigir a una página de confirmación o mostrar mensaje de éxito
        header("Location: esperar_confirmacion.php?pedido_id=" . $pedido_id);
        exit();
    } else {
        $error = "Por favor, complete todos los campos.";
    }
} else {
    // Obtener el ID del menú que el usuario quiere ordenar desde el parámetro GET o POST
    $menu_id = $_GET['menu_id'] ?? $_POST['menu_id'];
    
    // Verificar que se haya proporcionado un menu_id válido
    if (empty($menu_id)) {
        die("Error: No se ha seleccionado ningún menú.");
    }

    // Obtener la información del menú de la base de datos
    $query = $pdo->prepare("SELECT * FROM Menu WHERE id = ?");
    $query->execute([$menu_id]);
    $menu = $query->fetch(PDO::FETCH_ASSOC);

    if (!$menu) {
        die("Error: Menú no encontrado.");
    }
}




$queryMetodos = $pdo->prepare("SELECT * FROM metodo_pago");
$queryMetodos->execute();
$metodos = $queryMetodos->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ordenar <?= htmlspecialchars($menu['nombre']) ?></title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link rel="stylesheet" href="styles.css">
</head>
<body>

    <div class="container mt-5">
        <h2 class="text-center mb-4">Ordenar: <?= htmlspecialchars($menu['nombre']) ?></h2>
        <p class="text-center">Precio: <strong>$<?= htmlspecialchars($menu['precio']) ?></strong></p>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Selecciona tu dirección</h5>

                <!-- Mapa interactivo para seleccionar la dirección -->
                <div id="map" class="mb-3"></div>

                <!-- Campo para mostrar la dirección seleccionada -->
                <div class="form-group">
                    <label for="direccion">Dirección seleccionada:</label>
                    <input type="text" id="direccion" class="form-control" placeholder="Dirección seleccionada" readonly>
                </div>

                <!-- Formulario para enviar el pedido -->
                <form action="ordenar.php" method="POST">
                    <input type="hidden" name="menu_id" value="<?= $menu_id ?>">
                    <input type="hidden" name="direccion" id="direccion_hidden">

                    <div class="form-group">
                        <label for="cantidad">Cantidad:</label>
                        <input type="number" name="cantidad" id="cantidad" class="form-control" min="1" value="1" required>
                    </div>
                    <div class="form-group">
                        <label for="metodo_pago">Método de Pago:</label>
                        <select name="metodo_pago" id="metodo_pago" class="form-control" required>
                            <?php foreach ($metodos as $metodo): ?>
                                <option value="<?= $metodo['id_metodo_pago'] ?>"><?= $metodo['metodo_pago'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-block">Confirmar Pedido</button>
                </form>
            </div>
        </div>

        <!-- Mostrar error si faltan campos -->
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger mt-3"><?= $error ?></div>
        <?php endif; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="mapa.js"></script>
</body>
</html>
