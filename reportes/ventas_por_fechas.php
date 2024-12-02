<?php
// Incluir el archivo de conexión a la base de datos
require_once '../database/db.php';

// Consultas a la base de datos
$stmt = $pdo->query("SELECT 
                        p.fecha_pedido, 
                        SUM(pd.cantidad * CAST(m.precio AS DECIMAL(10, 2))) AS total_ventas
                     FROM 
                        pedidodetalle pd
                     JOIN 
                        menu m ON pd.rela_menu = m.id
                     JOIN 
                        pedido p ON pd.rela_pedido = p.id_pedido
                     GROUP BY 
                        p.fecha_pedido
                     ORDER BY 
                        p.fecha_pedido DESC");
$ventas_por_fecha = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Mostrar los resultados
?>
<div class="mt-4">
    <h3>Ventas por Fecha</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Total Ventas</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($ventas_por_fecha as $venta_fecha): ?>
            <tr>
                <td><?php echo $venta_fecha['fecha_pedido']; ?></td>
                <td>$<?php echo number_format($venta_fecha['total_ventas'], 2); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
