<?php
// Incluir archivo de configuración para conexión a la base de datos
require '../database/db.php'; // Cambiar según la ubicación de tu archivo db.php

if (!isset($_SESSION['usuario_id'])) {
    die("Error: Debes iniciar sesión para acceder a los pedidos.");
}

// Consultar los pedidos pendientes
$query = $pdo->prepare("SELECT p.*, u.username AS nombre_usuario 
                         FROM pedido p 
                         JOIN usuario u ON p.rela_usuario = u.id 
                         WHERE p.estado = 'pendiente' 
                         ORDER BY p.fecha_pedido DESC");
$query->execute();
$pedidos = $query->fetchAll(PDO::FETCH_ASSOC);

// Procesar la actualización del estado del pedido
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pedido_id'])) {
    $pedido_id = $_POST['pedido_id'];
    
    // Actualizar el estado del pedido a 'entregado'
    $updateQuery = $pdo->prepare("UPDATE pedido SET estado = 'entregado' WHERE id_pedido = ?");
    $updateQuery->execute([$pedido_id]);

    // Redirigir a la misma página para mostrar la actualización
    header("Location: pedidos.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedidos Pendientes</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css"> <!-- Asegúrate de que la ruta sea correcta -->
</head>
<body>
<header>
    <?php include '../header.php'; ?>
</header>
<main class="container mt-4">
    <h2 class="mb-4">Pedidos Pendientes</h2>

    <?php if (count($pedidos) === 0): ?>
        <div class="alert alert-info">No hay pedidos pendientes.</div>
    <?php else: ?>
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>ID del Pedido</th>
                    <th>Usuario</th>
                    <th>Dirección de Entrega</th>
                    <th>Cantidad Total</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pedidos as $pedido): ?>
                    <tr>
                        <td><?= htmlspecialchars($pedido['id_pedido']) ?></td>
                        <td><?= htmlspecialchars($pedido['nombre_usuario']) ?></td>
                        <td><?= htmlspecialchars($pedido['direccion_entrega']) ?></td>
                        <td>
                            <?php
                            // Obtener la cantidad total de este pedido
                            $detalleQuery = $pdo->prepare("SELECT SUM(cantidad) AS total_cantidad 
                                                            FROM pedidodetalle 
                                                            WHERE rela_pedido = ?");
                            $detalleQuery->execute([$pedido['id_pedido']]);
                            $detalle = $detalleQuery->fetch(PDO::FETCH_ASSOC);
                            echo htmlspecialchars($detalle['total_cantidad']);
                            ?>
                        </td>
                        <td>
                            <form action="pedidos.php" method="POST" style="display:inline;">
                                <input type="hidden" name="pedido_id" value="<?= htmlspecialchars($pedido['id_pedido']) ?>">
                                <button type="submit" class="btn btn-success">Marcar como Entregado</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <p><a href="index.php" class="btn btn-primary">Volver al Menú Principal</a></p>
</main>

<footer class="bg-dark text-white text-center py-3 mt-4">
    <p>&copy; <?php echo date('Y'); ?> Mi Sistema de Delivery</p>
</footer>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
