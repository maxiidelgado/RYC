<?php
require '../../database/db_auditoria.php';
require '../../database/db.php'; // Incluir archivo de conexión a la base de datos
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Operaciones CRUD
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['create'])) {
        // Crear producto
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $precio = $_POST['precio'];
        $stock = $_POST['stock'];

        $pdo->beginTransaction();
        $stmt = $pdo->prepare("INSERT INTO Producto (nombre, descripcion, precio, stock) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nombre, $descripcion, $precio, $stock]);
        $producto_id = $pdo->lastInsertId();

        // Crear entrada en el inventario inicial
        $cantidad = $_POST['cantidad'];
        $stmt = $pdo->prepare("INSERT INTO Inventario (producto_id, cantidad) VALUES (?, ?)");
        $stmt->execute([$producto_id, $cantidad]);

        $pdo->commit();
    } elseif (isset($_POST['update'])) {
        // Actualizar producto
        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];
        $precio = $_POST['precio'];
        $stock = $_POST['stock'];

        $stmt = $pdo->prepare("UPDATE Producto SET nombre = ?, descripcion = ?, precio = ?, stock = ? WHERE id = ?");
        $stmt->execute([$nombre, $descripcion, $precio, $stock, $id]);
    } elseif (isset($_POST['delete'])) {
        // Eliminar producto
        $id = $_POST['id'];

        $pdo->beginTransaction();
        $stmt = $pdo->prepare("DELETE FROM Producto WHERE id = ?");
        $stmt->execute([$id]);
        $pdo->commit();
    } elseif (isset($_POST['update_inventario'])) {
        // Actualizar inventario
        $id = $_POST['id'];
        $cantidad = $_POST['cantidad'];

        $stmt = $pdo->prepare("UPDATE Inventario SET cantidad = ? WHERE id = ?");
        $stmt->execute([$cantidad, $id]);
    }
}
 
// Obtener productos con su último inventario
$query = $pdo->query("SELECT p.id, p.nombre, p.descripcion, p.precio, p.stock, i.id AS inventario_id, i.cantidad 
                      FROM Producto p 
                      LEFT JOIN (SELECT MAX(id) AS id, producto_id, cantidad FROM Inventario GROUP BY producto_id) AS i 
                      ON p.id = i.producto_id");
$productos = $query->fetchAll(PDO::FETCH_ASSOC);
?>
