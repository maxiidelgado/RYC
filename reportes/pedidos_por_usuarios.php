<?php
// Incluir el archivo de conexión a la base de datos
require_once '../database/db.php';

// Consultas a la base de datos
$stmt = $pdo->query("SELECT per.nombre, COUNT(*) AS total_pedidos 
                     FROM pedido p
                     JOIN usuario u ON p.rela_usuario = u.id
                     JOIN persona per ON u.persona_id = per.id
                     GROUP BY per.nombre
                     ORDER BY total_pedidos DESC LIMIT 10");
$pedidos_por_usuario = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Mostrar los resultados
?>
<div class="mt-4">
    <h3>Pedidos por Usuario</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Usuario</th>
                <th>Total de Pedidos</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pedidos_por_usuario as $usuario): ?>
            <tr>
                <td><?php echo $usuario['nombre']; ?></td>
                <td><?php echo $usuario['total_pedidos']; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
