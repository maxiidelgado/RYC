<?php
require_once('fpdf.php');

// Recuperar los datos del reporte desde la base de datos
require_once '../database/db.php';

// Consultas a la base de datos
$stmt = $pdo->query("SELECT estado, COUNT(*) AS total_pedidos FROM pedido GROUP BY estado");
$estado_pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Nueva consulta para las ventas diarias
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
$ventas_diarias = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Productos más vendidos
$stmt = $pdo->query("SELECT m.nombre, SUM(pd.cantidad) AS cantidad_vendida FROM pedidodetalle pd JOIN menu m ON pd.rela_menu = m.id GROUP BY m.nombre ORDER BY cantidad_vendida DESC LIMIT 10");
$productos_mas_vendidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Pedidos por usuario
$stmt = $pdo->query("SELECT per.nombre, COUNT(*) AS total_pedidos 
                     FROM pedido p
                     JOIN usuario u ON p.rela_usuario = u.id
                     JOIN persona per ON u.persona_id = per.id
                     GROUP BY per.nombre
                     ORDER BY total_pedidos DESC LIMIT 10");
$pedidos_por_usuario = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Crear el PDF
$pdf = new FPDF();
$pdf->AddPage();

// Establecer fuentes
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(200, 10, 'Reporte Diario - Fecha: ' . $_POST['fecha_reporte'], 0, 1, 'C');

// Salto de línea
$pdf->Ln(10);

// Títulos de las secciones con un color de fondo
$pdf->SetFillColor(0, 51, 102); // Color de fondo azul oscuro
$pdf->SetTextColor(255, 255, 255); // Color de texto blanco
$pdf->SetFont('Arial', 'B', 12);

// Pedidos por Estado
$pdf->Cell(200, 10, 'Pedidos por Estado', 0, 1, 'L', true);
$pdf->SetTextColor(0, 0, 0); // Regresar al color de texto negro
$pdf->SetFont('Arial', '', 10);
foreach ($estado_pedidos as $estado) {
    $pdf->Cell(95, 10, 'Estado: ' . $estado['estado'], 0, 0);
    $pdf->Cell(95, 10, 'Total de Pedidos: ' . $estado['total_pedidos'], 0, 1);
}

$pdf->Ln(5); // Salto de línea adicional

// Ventas Diarias - Actualizado
$pdf->SetFillColor(0, 51, 102);
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(200, 10, 'Ventas Diarias', 0, 1, 'L', true);
$pdf->SetTextColor(0, 0, 0);
foreach ($ventas_diarias as $venta) {
    $pdf->Cell(95, 10, 'Fecha: ' . $venta['fecha_pedido'], 0, 0);
    $pdf->Cell(95, 10, 'Total Ventas: $' . number_format($venta['total_ventas'], 2), 0, 1);
}

$pdf->Ln(5);

// Productos Más Vendidos
$pdf->SetFillColor(0, 51, 102);
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(200, 10, 'Productos Más Vendidos', 0, 1, 'L', true);
$pdf->SetTextColor(0, 0, 0);
foreach ($productos_mas_vendidos as $producto) {
    $pdf->Cell(95, 10, 'Producto: ' . $producto['nombre'], 0, 0);
    $pdf->Cell(95, 10, 'Cantidad Vendida: ' . $producto['cantidad_vendida'], 0, 1);
}

$pdf->Ln(5);

// Pedidos por Usuario
$pdf->SetFillColor(0, 51, 102);
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(200, 10, 'Pedidos por Usuario', 0, 1, 'L', true);
$pdf->SetTextColor(0, 0, 0);
foreach ($pedidos_por_usuario as $usuario) {
    $pdf->Cell(95, 10, 'Usuario: ' . $usuario['nombre'], 0, 0);
    $pdf->Cell(95, 10, 'Total de Pedidos: ' . $usuario['total_pedidos'], 0, 1);
}

// Salto de línea final
$pdf->Ln(10);

// Output el PDF
$pdf->Output();
?>
