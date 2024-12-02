<?php
// Incluir el archivo de conexión a la base de datos
require_once '../database/db.php';

// Verificar qué reporte se desea mostrar
$reporte = isset($_GET['reporte']) ? $_GET['reporte'] : 'pedidos_por_estado';

// Paginación
$pagina = isset($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
$registros_por_pagina = 10; // Número de registros por página
$offset = ($pagina - 1) * $registros_por_pagina; // Calcular el desplazamiento para la consulta

// Función para obtener el número total de registros
function obtener_total_registros($pdo, $reporte) {
    switch ($reporte) {
        case 'pedidos_por_estado':
            $stmt = $pdo->query("SELECT COUNT(DISTINCT estado) FROM pedido");
            break;
        case 'productos_mas_vendidos':
            $stmt = $pdo->query("SELECT COUNT(DISTINCT m.nombre) FROM pedidodetalle pd JOIN menu m ON pd.rela_menu = m.id");
            break;
        case 'pedidos_por_usuario':
            $stmt = $pdo->query("SELECT COUNT(DISTINCT per.nombre) FROM pedido p JOIN usuario u ON p.rela_usuario = u.id JOIN persona per ON u.persona_id = per.id");
            break;
        case 'ventas_por_fecha':
            $stmt = $pdo->query("SELECT COUNT(DISTINCT p.fecha_pedido) FROM pedidodetalle pd JOIN menu m ON pd.rela_menu = m.id JOIN pedido p ON pd.rela_pedido = p.id_pedido");
            break;
        default:
            return 0;
    }
    return $stmt->fetchColumn();
}

// Función para calcular el total de páginas
function obtener_total_paginas($total_registros, $registros_por_pagina) {
    return ceil($total_registros / $registros_por_pagina);
}

// Obtener el total de registros para la paginación
$total_registros = obtener_total_registros($pdo, $reporte);
$total_paginas = obtener_total_paginas($total_registros, $registros_por_pagina);

// Consultar datos dependiendo del reporte
switch ($reporte) {
    case 'pedidos_por_estado':
        $stmt = $pdo->prepare("SELECT estado, COUNT(*) AS total_pedidos 
                               FROM pedido 
                               GROUP BY estado 
                               LIMIT :limite OFFSET :offset");
        break;
    case 'productos_mas_vendidos':
        $stmt = $pdo->prepare("SELECT m.nombre, SUM(pd.cantidad) AS cantidad_vendida 
                               FROM pedidodetalle pd
                               JOIN menu m ON pd.rela_menu = m.id
                               GROUP BY m.nombre
                               ORDER BY cantidad_vendida DESC
                               LIMIT :limite OFFSET :offset");
        break;
    case 'pedidos_por_usuario':
        $stmt = $pdo->prepare("SELECT per.nombre, COUNT(*) AS total_pedidos 
                               FROM pedido p 
                               JOIN usuario u ON p.rela_usuario = u.id
                               JOIN persona per ON u.persona_id = per.id
                               GROUP BY per.nombre
                               ORDER BY total_pedidos DESC
                               LIMIT :limite OFFSET :offset");
        break;
    case 'ventas_por_fecha':
        $stmt = $pdo->prepare("SELECT 
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
                                    p.fecha_pedido DESC
                                LIMIT :limite OFFSET :offset");
        break;
    default:
        // Si no se reconoce el reporte, no hacer nada
        $stmt = null;
        break;
}

if ($stmt) {
    $stmt->bindValue(':limite', $registros_por_pagina, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <?php include '../header.php'?>
    <div class="container mt-4">
        <h2 class="text-center">Reportes</h2>

        <!-- Menú de navegación de reportes -->
        <div class="mb-4">
            <ul class="nav nav-pills justify-content-center">
                <li class="nav-item">
                    <a class="nav-link <?php echo ($reporte == 'pedidos_por_estado') ? 'active' : ''; ?>" href="?reporte=pedidos_por_estado">Pedidos por Estado</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($reporte == 'productos_mas_vendidos') ? 'active' : ''; ?>" href="?reporte=productos_mas_vendidos">Productos Más Vendidos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($reporte == 'pedidos_por_usuario') ? 'active' : ''; ?>" href="?reporte=pedidos_por_usuario">Pedidos por Usuario</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($reporte == 'ventas_por_fecha') ? 'active' : ''; ?>" href="?reporte=ventas_por_fecha">Ventas por Fecha</a>
                </li>
            </ul>
        </div>

        <!-- Mostrar resultados del reporte -->
        <div class="mt-4">
            <h3 class="text-center"><?php echo ucfirst(str_replace('_', ' ', $reporte)); ?></h3>
            <table class="table table-bordered table-striped">
                <thead class="table-light">
                    <tr>
                        <?php if ($reporte == 'pedidos_por_estado'): ?>
                        <th>Estado</th>
                        <th>Total de Pedidos</th>
                        <?php elseif ($reporte == 'productos_mas_vendidos'): ?>
                        <th>Producto</th>
                        <th>Cantidad Vendida</th>
                        <?php elseif ($reporte == 'pedidos_por_usuario'): ?>
                        <th>Usuario</th>
                        <th>Total de Pedidos</th>
                        <?php elseif ($reporte == 'ventas_por_fecha'): ?>
                        <th>Fecha</th>
                        <th>Total de Ventas</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($resultados as $fila): ?>
                    <tr>
                        <?php if ($reporte == 'pedidos_por_estado'): ?>
                        <td><?php echo $fila['estado']; ?></td>
                        <td><?php echo $fila['total_pedidos']; ?></td>
                        <?php elseif ($reporte == 'productos_mas_vendidos'): ?>
                        <td><?php echo $fila['nombre']; ?></td>
                        <td><?php echo $fila['cantidad_vendida']; ?></td>
                        <?php elseif ($reporte == 'pedidos_por_usuario'): ?>
                        <td><?php echo $fila['nombre']; ?></td>
                        <td><?php echo $fila['total_pedidos']; ?></td>
                        <?php elseif ($reporte == 'ventas_por_fecha'): ?>
                        <td><?php echo $fila['fecha_pedido']; ?></td>
                        <td><?php echo number_format($fila['total_ventas'], 2, ',', '.'); ?></td>
                        <?php endif; ?>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="text-center mt-4">
            <nav aria-label="Page navigation">
                <ul class="pagination">
                    <?php if ($pagina > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?reporte=<?php echo $reporte; ?>&pagina=<?php echo $pagina - 1; ?>">Anterior</a>
                    </li>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                    <li class="page-item <?php echo ($i == $pagina) ? 'active' : ''; ?>">
                        <a class="page-link" href="?reporte=<?php echo $reporte; ?>&pagina=<?php echo $i; ?>"><?php echo $i; ?></a>
                    </li>
                    <?php endfor; ?>

                    <?php if ($pagina < $total_paginas): ?>
                    <li class="page-item">
                        <a class="page-link" href="?reporte=<?php echo $reporte; ?>&pagina=<?php echo $pagina + 1; ?>">Siguiente</a>
                    </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>

        <!-- Botón para exportar a PDF -->
        <form action="export_pdf.php" method="POST">
            <input type="hidden" name="fecha_reporte" id="fecha_reporte" />
            <button type="submit" class="btn btn-primary" >Generar PDF</button>
        </form>

<script>
    // Obtener la fecha actual
    const fechaActual = new Date();

    // Formatear la fecha en formato YYYY-MM-DD
    const anio = fechaActual.getFullYear();
    const mes = String(fechaActual.getMonth() + 1).padStart(2, '0'); // Los meses son indexados desde 0
    const dia = String(fechaActual.getDate()).padStart(2, '0');

    // Crear el formato final YYYY-MM-DD
    const fechaFormateada = `${anio}-${mes}-${dia}`;

    // Asignar la fecha al campo de entrada
    document.getElementById('fecha_reporte').value = fechaFormateada;
</script>



    </div>
</body>
</html>
