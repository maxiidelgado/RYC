<?php
// Consultas a la base de datos

// Gráfico 1: Pedidos por Estado
$stmt = $pdo->query("SELECT estado, COUNT(*) AS total_pedidos FROM pedido GROUP BY estado");
$estado_pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
$estado_pedidos_labels = array_column($estado_pedidos, 'estado');
$estado_pedidos_values = array_column($estado_pedidos, 'total_pedidos');

// Gráfico 2: Ventas Diarias
$stmt = $pdo->query("SELECT DATE(fecha_pedido) AS fecha, SUM(total) AS total_ventas FROM pedido GROUP BY DATE(fecha_pedido) ORDER BY fecha DESC");
$ventas_diarias = $stmt->fetchAll(PDO::FETCH_ASSOC);
$ventas_diarias_labels = array_column($ventas_diarias, 'fecha');
$ventas_diarias_values = array_column($ventas_diarias, 'total_ventas');

// Gráfico 3: Productos Más Vendidos
$stmt = $pdo->query("SELECT m.nombre, SUM(pd.cantidad) AS cantidad_vendida FROM pedidodetalle pd JOIN menu m ON pd.rela_menu = m.id GROUP BY m.nombre ORDER BY cantidad_vendida DESC LIMIT 10");
$productos_mas_vendidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
$productos_mas_vendidos_labels = array_column($productos_mas_vendidos, 'nombre');
$productos_mas_vendidos_values = array_column($productos_mas_vendidos, 'cantidad_vendida');

// Gráfico 4: Pedidos por Usuario
$stmt = $pdo->query("SELECT per.nombre, COUNT(*) AS total_pedidos 
                     FROM pedido p
                     JOIN usuario u ON p.rela_usuario = u.id
                     JOIN persona per ON u.persona_id = per.id
                     GROUP BY per.nombre
                     ORDER BY total_pedidos DESC LIMIT 10");
$pedidos_por_usuario = $stmt->fetchAll(PDO::FETCH_ASSOC);
$pedidos_por_usuario_labels = array_column($pedidos_por_usuario, 'nombre');
$pedidos_por_usuario_values = array_column($pedidos_por_usuario, 'total_pedidos');
?>
