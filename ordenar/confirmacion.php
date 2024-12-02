<?php
// Incluir archivo de configuración para conexión a la base de datos
require '../database/db.php'; // Cambiar según la ubicación de tu archivo db.php
require('../reportes/fpdf.php'); // Asegúrate de incluir FPDF

// Verificar que el pedido_id esté presente en la URL
if (!isset($_GET['pedido_id'])) {
    die("Error: No se ha proporcionado el ID del pedido.");
}

// Obtener el ID del pedido
$pedido_id = $_GET['pedido_id'];

// Consultar el pedido en la base de datos
$query = $pdo->prepare("SELECT p.*, u.username AS nombre_usuario, p.total, p.direccion_entrega, p.fecha_pedido, mp.metodo_pago
                         FROM pedido p 
                         JOIN usuario u ON p.rela_usuario = u.id 
                         JOIN metodo_pago mp ON p.rela_metodo_pago = mp.id_metodo_pago
                         WHERE p.id_pedido = ?");
$query->execute([$pedido_id]);
$pedido = $query->fetch(PDO::FETCH_ASSOC);

// Verificar que el pedido exista
if (!$pedido) {
    die("Error: Pedido no encontrado.");
}

// Consultar los detalles del pedido
$queryDetalles = $pdo->prepare("SELECT pd.*, m.nombre AS nombre_menu, m.precio 
                                 FROM pedidodetalle pd 
                                 JOIN menu m ON pd.rela_menu = m.id 
                                 WHERE pd.rela_pedido = ?");
$queryDetalles->execute([$pedido_id]);
$detalles = $queryDetalles->fetchAll(PDO::FETCH_ASSOC);

// Función para generar el PDF
function generarPDF($pedido, $detalles) {
    $pdf = new FPDF();
    $pdf->AddPage();
    
    // Título del comprobante
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(200, 10, 'Comprobante de Pedido', 0, 1, 'C');
    $pdf->Ln(10);

    // Información del pedido
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(100, 10, 'ID del Pedido: ', 0, 0);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(90, 10, $pedido['id_pedido'], 0, 1);
    
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(100, 10, 'Usuario: ', 0, 0);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(90, 10, $pedido['nombre_usuario'], 0, 1);
    
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(100, 10, 'Dirección de Entrega: ', 0, 0);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(90, 10, $pedido['direccion_entrega'], 0, 1);
    
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(100, 10, 'Fecha del Pedido: ', 0, 0);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(90, 10, $pedido['fecha_pedido'], 0, 1);
    
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(100, 10, 'Método de Pago: ', 0, 0);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(90, 10, $pedido['metodo_pago'], 0, 1);
    
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(100, 10, 'Total: ', 0, 0);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(90, 10, '$' . number_format($pedido['total'], 2), 0, 1);
    
    $pdf->Ln(10); // Espaciado
    
    // Encabezado de la tabla de detalles
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(60, 10, 'Menú', 1, 0, 'C');
    $pdf->Cell(30, 10, 'Cantidad', 1, 0, 'C');
    $pdf->Cell(40, 10, 'Precio Unitario', 1, 0, 'C');
    $pdf->Cell(40, 10, 'Total', 1, 1, 'C');
    
    // Detalles del pedido
    $pdf->SetFont('Arial', '', 12);
    foreach ($detalles as $detalle) {
        $pdf->Cell(60, 10, $detalle['nombre_menu'], 1, 0);
        $pdf->Cell(30, 10, $detalle['cantidad'], 1, 0, 'C');
        $pdf->Cell(40, 10, '$' . number_format($detalle['precio'], 2), 1, 0, 'C');
        $pdf->Cell(40, 10, '$' . number_format($detalle['precio'] * $detalle['cantidad'], 2), 1, 1, 'C');
    }
    
    // Salvar el PDF
    $pdf->Output('I', 'comprobante_pedido_' . $pedido['id_pedido'] . '.pdf');
}


// Verificar si se ha solicitado la descarga
if (isset($_GET['descargar_pdf']) && $_GET['descargar_pdf'] == '1') {
    generarPDF($pedido, $detalles);
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Pedido</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css"> <!-- Asegúrate de que la ruta sea correcta -->
</head>
<body>

<main>
    <div class="container">
        <h2 class="mt-4">Confirmación de Pedido</h2>
        <p class="lead">Gracias, <?= htmlspecialchars($pedido['nombre_usuario']) ?>, por tu pedido.</p>
        <p><strong>ID del Pedido:</strong> <?= htmlspecialchars($pedido['id_pedido']) ?></p>
        <p><strong>Dirección de Entrega:</strong> <?= htmlspecialchars($pedido['direccion_entrega']) ?></p>
        <p><strong>Fecha del Pedido:</strong> <?= htmlspecialchars($pedido['fecha_pedido']) ?></p>
        <p><strong>Método de Pago:</strong> <?= htmlspecialchars($pedido['metodo_pago']) ?></p>
        <p><strong>Total del Pedido:</strong> $<?= number_format(htmlspecialchars($pedido['total']), 2) ?></p>

        <h3>Detalles del Pedido:</h3>
        <table class="table table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>Menú</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($detalles as $detalle): ?>
                    <tr>
                        <td><?= htmlspecialchars($detalle['nombre_menu']) ?></td>
                        <td><?= htmlspecialchars($detalle['cantidad']) ?></td>
                        <td>$<?= number_format(htmlspecialchars($detalle['precio']), 2) ?></td>
                        <td>$<?= number_format(htmlspecialchars($detalle['precio']) * htmlspecialchars($detalle['cantidad']), 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <p class="text-center">
            <a href="../index.php" class="btn btn-primary">Volver al Menú Principal</a>
            <a href="?pedido_id=<?= $pedido['id_pedido'] ?>&descargar_pdf=1" class="btn btn-success">Descargar Comprobante PDF</a>
        </p>
    </div>
</main>

<footer>
    <div class="container">
        <p>&copy; <?php echo date('Y'); ?> Mi Sistema de Delivery</p>
    </div>
</footer>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
