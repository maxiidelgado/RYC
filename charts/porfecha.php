<?php
// Incluir la conexión a la base de datos
include('../database/db.php');

// Realizar la consulta para obtener los pedidos por día
$stmt = $pdo->query("SELECT DATE(fecha_pedido) AS fecha, COUNT(*) AS total_pedidos FROM pedido GROUP BY DATE(fecha_pedido) ORDER BY fecha DESC");
$pedidos_diarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Preparar los datos para el gráfico
$pedidos_diarios_labels = array_column($pedidos_diarios, 'fecha'); // Fechas de los pedidos
$pedidos_diarios_values = array_column($pedidos_diarios, 'total_pedidos'); // Totales de pedidos por fecha
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gráfico de Pedidos Diarios</title>
    <!-- Incluir Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Incluir Bootstrap (opcional para el diseño) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Gráfico de Pedidos Diarios</h2>
        <canvas id="pedidosDiariosChart" width="400" height="200"></canvas>
    </div>

    <script>
        // Obtener los datos PHP y pasarlos a JavaScript
        var pedidosDiariosLabels = <?php echo json_encode($pedidos_diarios_labels); ?>;
        var pedidosDiariosValues = <?php echo json_encode($pedidos_diarios_values); ?>;

        // Configuración del gráfico
        var ctx = document.getElementById('pedidosDiariosChart').getContext('2d');
        var pedidosDiariosChart = new Chart(ctx, {
            type: 'bar', // Cambié de 'line' a 'bar' para que sea un gráfico de barras
            data: {
                labels: pedidosDiariosLabels, // Fechas
                datasets: [{
                    label: 'Total de Pedidos',
                    data: pedidosDiariosValues, // Totales de pedidos por fecha
                    backgroundColor: 'rgba(75, 192, 192, 0.6)', // Color de las barras
                    borderColor: 'rgba(75, 192, 192, 1)', // Color del borde de las barras
                    borderWidth: 1 // Ancho del borde
                }]
            },
            options: {
                responsive: true, // Gráfico responsivo
                scales: {
                    y: {
                        beginAtZero: true, // El eje Y comienza en 0
                        title: {
                            display: true,
                            text: 'Cantidad de Pedidos' // Título del eje Y
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Fecha' // Título del eje X
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'top', // Posición de la leyenda
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return 'Pedidos: ' + tooltipItem.raw; // Personalización del tooltip
                            }
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>
