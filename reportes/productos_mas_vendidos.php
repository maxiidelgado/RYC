<?php
// Incluir el archivo de conexión a la base de datos
require_once '../database/db.php';

// Consultas a la base de datos
$stmt = $pdo->query("SELECT m.nombre, SUM(pd.cantidad) AS cantidad_vendida FROM pedidodetalle pd JOIN menu m ON pd.rela_menu = m.id GROUP BY m.nombre ORDER BY cantidad_vendida DESC LIMIT 10");
$productos_mas_vendidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Mostrar los resultados
?>
<div class="mt-4">
    <h3>Productos Más Vendidos</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad Vendida</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($productos_mas_vendidos as $producto): ?>
            <tr>
                <td><?php echo $producto['nombre']; ?></td>
                <td><?php echo $producto['cantidad_vendida']; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
