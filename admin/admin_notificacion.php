<?php
// Incluir archivo de configuración para la base de datos
require '../database/db.php'; 

// Definir cantidad de resultados por página
$resultsPerPage = 10;

// Calcular la página actual
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$startFrom = ($currentPage - 1) * $resultsPerPage;

// Obtener todos los pedidos pendientes de confirmación con paginación
$query = $pdo->prepare("SELECT * FROM notificaciones_admin WHERE estado = 'Pendiente' LIMIT :start, :limit");
$query->bindParam(':start', $startFrom, PDO::PARAM_INT);
$query->bindParam(':limit', $resultsPerPage, PDO::PARAM_INT);
$query->execute();
$pedidosPendientes = $query->fetchAll(PDO::FETCH_ASSOC);

// Calcular el número total de registros
$queryTotal = $pdo->prepare("SELECT COUNT(*) FROM notificaciones_admin WHERE estado = 'Pendiente'");
$queryTotal->execute();
$totalPedidos = $queryTotal->fetchColumn();

// Calcular el número total de páginas
$totalPages = ceil($totalPedidos / $resultsPerPage);

// Si el administrador confirma un pedido
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirmar_pedido_id'])) {
    $pedido_id = $_POST['confirmar_pedido_id'];

    // Actualizar el estado del pedido y la notificación
    $queryPedido = $pdo->prepare("UPDATE pedido SET estado = 'Confirmado' WHERE id_pedido = ?");
    $queryPedido->execute([$pedido_id]);

    $queryNotificacion = $pdo->prepare("UPDATE notificaciones_admin SET estado = 'Confirmado' WHERE rela_pedido = ?");
    $queryNotificacion->execute([$pedido_id]);

    // Redirigir a la página de administración con el pedido confirmado
    header("Location: admin_notificacion.php");
    exit();
}

// Si el administrador cancela un pedido
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancelar_pedido_id'])) {
    $pedido_id = $_POST['cancelar_pedido_id'];

    // Actualizar el estado del pedido y la notificación
    $queryPedido = $pdo->prepare("UPDATE pedido SET estado = 'Cancelado' WHERE id_pedido = ?");
    $queryPedido->execute([$pedido_id]);

    $queryNotificacion = $pdo->prepare("UPDATE notificaciones_admin SET estado = 'Cancelado' WHERE rela_pedido = ?");
    $queryNotificacion->execute([$pedido_id]);

    // Redirigir a la página de administración con el pedido cancelado
    header("Location: admin_notificacion.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrar Pedidos</title>
    <!-- Incluir Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Incluir tu archivo de estilos -->
    <link href="../css/styles.css" rel="stylesheet">
</head>
<body>
    <!-- Incluir el header -->
    <?php include '../header.php'; ?>

    <div class="container mt-5">
        <h1 class="text-center">Pedidos Pendientes de Confirmación</h1>
        
        <!-- Tabla de pedidos -->
        <table class="table table-bordered mt-4">
            <thead class="thead-dark">
                <tr>
                    <th>Pedido ID</th>
                    <th>Usuario</th>
                    <th>Fecha de Notificación</th>
                    <th>Estado</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pedidosPendientes as $pedido): ?>
                    <tr>
                        <td><?= htmlspecialchars($pedido['rela_pedido']) ?></td>
                        <td><?= htmlspecialchars($pedido['mensaje']) ?></td>
                        <td><?= htmlspecialchars($pedido['fecha_notificacion']) ?></td>
                        <td><?= htmlspecialchars($pedido['estado']) ?></td>
                        <td>
                            <!-- Formulario para confirmar pedido -->
                            <form method="POST" class="d-inline">
                                <input type="hidden" name="confirmar_pedido_id" value="<?= $pedido['rela_pedido'] ?>">
                                <button type="submit" class="btn btn-success">Confirmar</button>
                            </form>
                            <!-- Formulario para cancelar pedido -->
                            <form method="POST" class="d-inline">
                                <input type="hidden" name="cancelar_pedido_id" value="<?= $pedido['rela_pedido'] ?>">
                                <button type="submit" class="btn btn-danger">Cancelar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Paginación -->
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <li class="page-item <?= $currentPage == 1 ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $currentPage - 1 ?>" aria-label="Anterior">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <?php for ($page = 1; $page <= $totalPages; $page++): ?>
                    <li class="page-item <?= $page == $currentPage ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $page ?>"><?= $page ?></a>
                    </li>
                <?php endfor; ?>
                <li class="page-item <?= $currentPage == $totalPages ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $currentPage + 1 ?>" aria-label="Siguiente">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>

<footer>
    <div class="container text-center">
        <p>&copy; <?php echo date('Y'); ?> Mi Sistema de Delivery</p>
    </div>
</footer>

    <!-- Incluir Bootstrap JS y dependencias -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
