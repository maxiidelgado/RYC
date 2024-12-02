<?php
// Iniciar sesión
session_start();

// Incluir archivo de configuración para conexión a la base de datos
require '../database/db.php'; // Asegúrate de que la ruta sea correcta

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario_id'])) {
    die("Error: Debes iniciar sesión para acceder a los pedidos.");
}

// Configuración de paginación
$items_por_pagina = 10; // Número de pedidos por página
$pagina_actual = isset($_GET['pagina']) && is_numeric($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$pagina_actual = max($pagina_actual, 1); // Asegurar que la página actual sea al menos 1
$offset = ($pagina_actual - 1) * $items_por_pagina;

// Consultar el total de pedidos que cumplen con los estados especificados
$total_pedidos_query = $pdo->prepare("SELECT COUNT(*) FROM pedido WHERE estado IN ('pendiente', 'entregado', 'cancelado')");
$total_pedidos_query->execute();
$total_pedidos = $total_pedidos_query->fetchColumn();

// Calcular el número total de páginas
$total_paginas = ceil($total_pedidos / $items_por_pagina);

// Consultar los pedidos pendientes, entregados y cancelados con paginación y priorizando "pendiente"
$query = $pdo->prepare("
    SELECT p.*, u.username AS nombre_usuario 
    FROM pedido p 
    JOIN usuario u ON p.rela_usuario = u.id 
    WHERE p.estado IN ('pendiente', 'entregado', 'cancelado') 
    ORDER BY 
        CASE 
            WHEN p.estado = 'pendiente' THEN 1 
            WHEN p.estado = 'entregado' THEN 2 
            ELSE 3 
        END, 
        p.fecha_pedido DESC 
    LIMIT :offset, :items_por_pagina
");
$query->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
$query->bindValue(':items_por_pagina', (int)$items_por_pagina, PDO::PARAM_INT);
$query->execute();
$pedidos = $query->fetchAll(PDO::FETCH_ASSOC);

// Procesar la actualización del estado del pedido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['pedido_id']) && isset($_POST['accion'])) {
        $pedido_id = $_POST['pedido_id'];
        $accion = $_POST['accion'];

        // Validar que el pedido_id sea numérico
        if (!is_numeric($pedido_id)) {
            die("Error: ID de pedido inválido.");
        }

        // Determinar el nuevo estado basado en la acción
        if ($accion === 'entregado') {
            $estado = 'entregado';
        } elseif ($accion === 'cancelado') {
            $estado = 'cancelado';
        } else {
            die("Error: Acción inválida.");
        }

        // Actualizar el estado del pedido en la base de datos
        $updateQuery = $pdo->prepare("UPDATE pedido SET estado = ? WHERE id_pedido = ?");
        $updateQuery->execute([$estado, $pedido_id]);

        // Redirigir a la misma página para reflejar los cambios y evitar reenvío de formularios
        header("Location: pedidos_admin.php?pagina=" . $pagina_actual);
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Pedidos</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Asegúrate de que la ruta de styles.css sea correcta -->
    <link rel="stylesheet" href="../css/styles.css"> 
</head>
<body>
<header>
    <?php include '../header.php'; ?>
</header>
<main class="container mt-4">
    <h2 class="mb-4">Gestión de Pedidos</h2>

    <?php if (count($pedidos) === 0): ?>
        <div class="alert alert-info">No hay pedidos para gestionar.</div>
    <?php else: ?>
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>ID del Pedido</th>
                    <th>Usuario</th>
                    <th>Dirección de Entrega</th>
                    <th>Cantidad Total</th>
                    <th>Estado</th>
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
                        <td><?= htmlspecialchars(ucfirst($pedido['estado'])) ?></td>
                        <td>
                            <?php if ($pedido['estado'] === 'pendiente'): ?>
                                <form action="pedidos_admin.php?pagina=<?= $pagina_actual ?>" method="POST" style="display:inline;">
                                    <input type="hidden" name="pedido_id" value="<?= htmlspecialchars($pedido['id_pedido']) ?>">
                                    <input type="hidden" name="accion" value="entregado">
                                    <button type="submit" class="btn btn-success btn-sm">Marcar como Entregado</button>
                                </form>

                                <form action="pedidos_admin.php?pagina=<?= $pagina_actual ?>" method="POST" style="display:inline;">
                                    <input type="hidden" name="pedido_id" value="<?= htmlspecialchars($pedido['id_pedido']) ?>">
                                    <input type="hidden" name="accion" value="cancelado">
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de cancelar este pedido?');">Cancelar Pedido</button>
                                </form>
                            <?php elseif ($pedido['estado'] === 'entregado'): ?>
                                <span class="badge badge-success">Entregado</span>
                            <?php elseif ($pedido['estado'] === 'cancelado'): ?>
                                <span class="badge badge-danger">Cancelado</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Paginación -->
        <?php if ($total_paginas > 1): ?>
            <nav aria-label="Paginación de pedidos">
                <ul class="pagination justify-content-center">
                    <!-- Botón Anterior -->
                    <?php if ($pagina_actual > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="pedidos_admin.php?pagina=<?= $pagina_actual - 1 ?>" aria-label="Anterior">
                                <span aria-hidden="true">&laquo;</span>
                                <span class="sr-only">Anterior</span>
                            </a>
                        </li>
                    <?php else: ?>
                        <li class="page-item disabled">
                            <span class="page-link" aria-label="Anterior">
                                <span aria-hidden="true">&laquo;</span>
                                <span class="sr-only">Anterior</span>
                            </span>
                        </li>
                    <?php endif; ?>

                    <!-- Números de página -->
                    <?php
                    // Mostrar un rango limitado de páginas para mejorar la usabilidad
                    $rango = 2; // Número de páginas a mostrar antes y después de la página actual
                    $inicio = max(1, $pagina_actual - $rango);
                    $fin = min($total_paginas, $pagina_actual + $rango);

                    if ($inicio > 1) {
                        echo '<li class="page-item"><a class="page-link" href="pedidos_admin.php?pagina=1">1</a></li>';
                        if ($inicio > 2) {
                            echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                        }
                    }

                    for ($i = $inicio; $i <= $fin; $i++):
                        ?>
                        <li class="page-item <?= ($i == $pagina_actual) ? 'active' : '' ?>">
                            <a class="page-link" href="pedidos_admin.php?pagina=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor;

                    if ($fin < $total_paginas) {
                        if ($fin < $total_paginas - 1) {
                            echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                        }
                        echo '<li class="page-item"><a class="page-link" href="pedidos_admin.php?pagina=' . $total_paginas . '">' . $total_paginas . '</a></li>';
                    }
                    ?>

                    <!-- Botón Siguiente -->
                    <?php if ($pagina_actual < $total_paginas): ?>
                        <li class="page-item">
                            <a class="page-link" href="pedidos_admin.php?pagina=<?= $pagina_actual + 1 ?>" aria-label="Siguiente">
                                <span aria-hidden="true">&raquo;</span>
                                <span class="sr-only">Siguiente</span>
                            </a>
                        </li>
                    <?php else: ?>
                        <li class="page-item disabled">
                            <span class="page-link" aria-label="Siguiente">
                                <span aria-hidden="true">&raquo;</span>
                                <span class="sr-only">Siguiente</span>
                            </span>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        <?php endif; ?>
    <?php endif; ?>

    <p><a href="index.php" class="btn btn-primary">Volver al Menú Principal</a></p>
</main>

<footer >
    <p>&copy; <?php echo date('Y'); ?> Mi Sistema de Delivery</p>
</footer>

<!-- Scripts de Bootstrap -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
