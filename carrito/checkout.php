<?php
session_start();
require '../database/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Asegúrate de que el usuario está autenticado
    if (!isset($_SESSION['usuario_id'])) {
        echo json_encode(['status' => 'error', 'message' => 'Usuario no autenticado']);
        exit;
    }

    $usuarioId = $_SESSION['usuario_id'];
    $total = $_POST['total'];

    try {
        $pdo->beginTransaction();

        // Crear un nuevo pedido
        $stmt = $pdo->prepare("INSERT INTO Pedido (usuario_id, total) VALUES (?, ?)");
        $stmt->execute([$usuarioId, $total]);
        $pedidoId = $pdo->lastInsertId();

        // Insertar detalles del pedido
        $productos = json_decode($_POST['productos'], true);
        foreach ($productos as $producto) {
            $stmt = $pdo->prepare("INSERT INTO PedidoDetalle (pedido_id, producto_id, cantidad, precio) VALUES (?, ?, ?, ?)");
            $stmt->execute([$pedidoId, $producto['id'], $producto['cantidad'], $producto['precio']]);
        }

        $pdo->commit();

        // Vaciar el carrito
        unset($_SESSION['carrito']);

        echo json_encode(['status' => 'success', 'message' => 'Pedido realizado con éxito']);
    } catch (Exception $e) {
        $pdo->rollBack();
        echo json_encode(['status' => 'error', 'message' => 'Error al procesar el pedido: ' . $e->getMessage()]);
    }
}
