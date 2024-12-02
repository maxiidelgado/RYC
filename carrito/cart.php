<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $productoId = $_POST['producto_id'];
    $cantidad = $_POST['cantidad'] ?? 1;
    
    if ($action === 'agregar') {
        if (isset($_SESSION['carrito'][$productoId])) {
            $_SESSION['carrito'][$productoId]['cantidad'] += $cantidad;
        } else {
            $_SESSION['carrito'][$productoId] = [
                'id' => $productoId,
                'nombre' => $_POST['nombre'],
                'precio' => $_POST['precio'],
                'cantidad' => $cantidad
            ];
        }
    } elseif ($action === 'actualizar') {
        if (isset($_SESSION['carrito'][$productoId])) {
            $_SESSION['carrito'][$productoId]['cantidad'] = $cantidad;
        }
    } elseif ($action === 'eliminar') {
        unset($_SESSION['carrito'][$productoId]);
    }

    echo json_encode(['carrito' => $_SESSION['carrito']]);
}
