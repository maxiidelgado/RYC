<?php
// Incluir el archivo de conexión a la base de datos
require_once '../database/db.php';

// Consultas a la base de datos
$stmt = $pdo->query("SELECT estado, COUNT(*) AS total_pedidos FROM pedido GROUP BY estado");
$estado_pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Mostrar los resultados
?>
<div class="mt-4">
    <h3>Pedidos por Estado</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Estado</th>
                <th>Total de Pedidos</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($estado_pedidos as $estado): ?>
            <tr>
                <td><?php echo $estado['estado']; ?></td>
                <td><?php echo $estado['total_pedidos']; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
