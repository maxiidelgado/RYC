<?php
session_start();
require '../database/db.php';  // Incluir la conexión a la base de datos
require 'datos_usuario.php';     // Incluir las funciones

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login/login.php');
    exit();
}

$usuario_id = $_SESSION['usuario_id'];

$historial_pedidos = obtenerHistorialPedidosCompleto($usuario_id, $pdo);  // Esta función obtiene todos los pedidos

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Pedidos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body class="bg-light text-dark">

    <div class="container mt-5">
        <h1 class="text-center mb-4" style="font-family: 'Lobster', cursive; color: #2c3e50;">Historial Completo de Pedidos</h1>

        <?php if ($historial_pedidos): ?>
            <table class="table table-striped table-bordered text-center">
                <thead class="thead-light">
                    <tr>
                        <th>ID Pedido</th>
                        <th>Dirección</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($historial_pedidos as $pedido): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($pedido['id_pedido']); ?></td>
                            <td><?php echo htmlspecialchars($pedido['direccion_entrega']); ?></td>
                            <td><?php echo htmlspecialchars($pedido['fecha_pedido']); ?></td>
                            <td><?php echo htmlspecialchars($pedido['estado']); ?></td>
                            <td>$<?php echo htmlspecialchars($pedido['total']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-center">No tienes pedidos realizados.</p>
        <?php endif; ?>

        <div class="text-center mt-4">
            <a href="perfil.php" class="btn btn-outline-primary">Volver a Mis Datos</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
