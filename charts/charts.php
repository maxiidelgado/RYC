<?php
include_once('../header.php');  // Incluir el encabezado
include_once('../database/db.php');  // Conexión a la base de datos

// Obtener datos para los gráficos
include('data.php');  // Este archivo contiene las consultas y los datos para los gráficos

$backgroundColors = ['rgba(255, 99, 132, 0.2)', 'rgba(75, 192, 192, 0.2)', 'rgba(153, 102, 255, 0.2)'];
$borderColors = ['rgba(255, 99, 132, 1)', 'rgba(75, 192, 192, 1)', 'rgba(153, 102, 255, 1)'];
?>
?>

<div class="container mt-4">
    <h2 class="text-center">Estadísticas de Pedidos</h2>
    <div class="row">
        <div class="col-md-6">
            <h4>Pedidos por Estado</h4>
            <canvas id="pedidosEstado"></canvas>
        </div>
        <div class="col-md-6">
            <h4>Ventas Diarias</h4>
            '<?php include 'porfecha.php'?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <h4>Productos Más Vendidos</h4>
            <canvas id="productosVendidos"></canvas>
        </div>
        <div class="col-md-6">
            <h4>Pedidos por Usuario</h4>
            <canvas id="pedidosUsuario"></canvas>
        </div>
    </div>
</div>

<!-- Cargar Chart.js desde CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Gráfico de Pedidos por Estado
var pedidosEstado = document.getElementById('pedidosEstado').getContext('2d');
new Chart(pedidosEstado, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode($estado_pedidos_labels); ?>,
        datasets: [{
            label: 'Total de Pedidos',
            data: <?php echo json_encode($estado_pedidos_values); ?>,
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 1
        }]
    }
});

// Gráfico de Ventas Diarias


// Gráfico de Productos Más Vendidos
var productosVendidos = document.getElementById('productosVendidos').getContext('2d');
    new Chart(productosVendidos, {
        type: 'pie',
        data: {
            labels: <?php echo json_encode($productos_mas_vendidos_labels); ?>, // Etiquetas de productos
            datasets: [{
                label: 'Cantidad Vendida',
                data: <?php echo json_encode($productos_mas_vendidos_values); ?>, // Valores de cantidad vendida
                backgroundColor: <?php echo json_encode($backgroundColors); ?>, // Colores de fondo para cada producto
                borderColor: <?php echo json_encode($borderColors); ?>, // Colores de borde para cada producto
                borderWidth: 1
            }]
        }
    });

// Gráfico de Pedidos por Usuario
var pedidosUsuario = document.getElementById('pedidosUsuario').getContext('2d');
new Chart(pedidosUsuario, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode($pedidos_por_usuario_labels); ?>,
        datasets: [{
            label: 'Total de Pedidos',
            data: <?php echo json_encode($pedidos_por_usuario_values); ?>,
            backgroundColor: 'rgba(153, 102, 255, 0.2)',
            borderColor: 'rgba(153, 102, 255, 1)',
            borderWidth: 1
        }]
    }
});
</script>
