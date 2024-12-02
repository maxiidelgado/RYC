<?php
require '../../database/db.php'; // Incluir archivo de conexión a la base de datos
session_start();

// Verifica si el directorio de imágenes existe, y créalo si no existe
$uploadDir = '../../uploads/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// Operaciones CRUD
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['create'])) {
        // Crear entrada del menú
        $nombre = $_POST['nombre'];
        $precio = $_POST['precio'];
        $estado = $_POST['estado'];
        $stock = $_POST['stock']; // Nuevo campo stock

        // Manejo de la imagen
        $imagen = null;
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $imagen = $uploadDir . basename($_FILES['imagen']['name']);
            if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $imagen)) {
                echo "Error al subir la imagen.";
                exit(); // Detenemos la ejecución si falla la carga de la imagen
            }
        }

        // Inserta el nuevo registro con el campo stock
        $stmt = $pdo->prepare("INSERT INTO Menu (nombre, precio, estado, imagen, stock) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$nombre, $precio, $estado, $imagen, $stock]);
    } elseif (isset($_POST['update'])) {
        // Actualizar entrada del menú
        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $precio = $_POST['precio'];
        $estado = $_POST['estado'];
        $stock = $_POST['stock']; // Nuevo campo stock

        // Manejo de la imagen (si no se selecciona una nueva imagen, mantener la anterior)
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $imagen = $uploadDir . basename($_FILES['imagen']['name']);
            if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $imagen)) {
                echo "Error al subir la imagen.";
                exit(); // Detenemos la ejecución si falla la carga de la imagen
            }

            // Actualizar con nueva imagen
            $stmt = $pdo->prepare("UPDATE Menu SET nombre = ?, precio = ?, estado = ?, imagen = ?, stock = ? WHERE id = ?");
            $stmt->execute([$nombre, $precio, $estado, $imagen, $stock, $id]);
        } else {
            // Si no se carga nueva imagen, mantener la anterior
            $stmt = $pdo->prepare("UPDATE Menu SET nombre = ?, precio = ?, estado = ?, stock = ? WHERE id = ?");
            $stmt->execute([$nombre, $precio, $estado, $stock, $id]);
        }
    } elseif (isset($_POST['delete'])) {
        // Eliminar entrada del menú (cambio de estado a inactivo)
        $id = $_POST['id'];

        $stmt = $pdo->prepare("UPDATE Menu SET estado = 0 WHERE id = ?");
        $stmt->execute([$id]);
    }
}

// Redireccionar a la vista después de realizar operaciones
header('Location: vista_menu.php');
exit();
?>
