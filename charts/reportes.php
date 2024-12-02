<?php
// Incluir el archivo de conexión a la base de datos
require_once '../database/db.php';

// Consultas a la base de datos

// Gráfico 1: Pedidos por Estado
$stmt = $pdo->query("SELECT estado, COUNT(*) AS total_pedidos FROM pedido GROUP BY estado");
$estado_pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Gráfico 2: Ventas Diarias
$stmt = $pdo->query("SELECT DATE(fecha_pedido) AS fecha, SUM(total) AS total_ventas FROM pedido GROUP BY DATE(fecha_pedido) ORDER BY fecha DESC");
$ventas_diarias = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Gráfico 3: Productos Más Vendidos
$stmt = $pdo->query("SELECT m.nombre, SUM(pd.cantidad) AS cantidad_vendida FROM pedidodetalle pd JOIN menu m ON pd.rela_menu = m.id GROUP BY m.nombre ORDER BY cantidad_vendida DESC LIMIT 10");
$productos_mas_vendidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Gráfico 4: Pedidos por Usuario
$stmt = $pdo->query("SELECT per.nombre, COUNT(*) AS total_pedidos 
                     FROM pedido p
                     JOIN usuario u ON p.rela_usuario = u.id
                     JOIN persona per ON u.persona_id = per.id
                     GROUP BY per.nombre
                     ORDER BY total_pedidos DESC LIMIT 10");
$pedidos_por_usuario = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fecha del reporte
$fecha_reporte = date('Y-m-d');

// Crear el reporte en texto
$reporte = "Reporte Diario - Fecha: $fecha_reporte\n\n";

// Pedidos por Estado
$reporte .= "Pedidos por Estado:\n";
foreach ($estado_pedidos as $estado) {
    $reporte .= "- Estado: {$estado['estado']} | Total de Pedidos: {$estado['total_pedidos']}\n";
}

$reporte .= "\n";

// Ventas Diarias
$reporte .= "Ventas Diarias:\n";
foreach ($ventas_diarias as $venta) {
    $reporte .= "- Fecha: {$venta['fecha']} | Total Ventas: {$venta['total_ventas']} \n";
}

$reporte .= "\n";

// Productos Más Vendidos
$reporte .= "Productos Más Vendidos:\n";
foreach ($productos_mas_vendidos as $producto) {
    $reporte .= "- Producto: {$producto['nombre']} | Cantidad Vendida: {$producto['cantidad_vendida']}\n";
}

$reporte .= "\n";

// Pedidos por Usuario
$reporte .= "Pedidos por Usuario:\n";
foreach ($pedidos_por_usuario as $usuario) {
    $reporte .= "- Usuario: {$usuario['nombre']} | Total de Pedidos: {$usuario['total_pedidos']}\n";
}

// Mostrar el reporte
echo "<pre>$reporte</pre>";

?>
