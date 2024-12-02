<?php
require '../../database/db.php'; // Incluir archivo de conexión a la base de datos

// Iniciar sesión si no está ya iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar la sesión de usuario (implementación opcional)
// if (!isset($_SESSION['usuario_id'])) {
//     header('Location: ../login.php');
//     exit;
// }

// Operaciones CRUD
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['create'])) {
        // Crear categoría
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];

        $stmt = $pdo->prepare("INSERT INTO Categoria (nombre, descripcion) VALUES (?, ?)");
        $stmt->execute([$nombre, $descripcion]);
    } elseif (isset($_POST['update'])) {
        // Actualizar categoría
        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $descripcion = $_POST['descripcion'];

        $stmt = $pdo->prepare("UPDATE Categoria SET nombre = ?, descripcion = ? WHERE id = ?");
        $stmt->execute([$nombre, $descripcion, $id]);
    } elseif (isset($_POST['delete'])) {
        // Eliminar categoría
        $id = $_POST['id'];

        $stmt = $pdo->prepare("DELETE FROM Categoria WHERE id = ?");
        $stmt->execute([$id]);
    }
}

// Obtener categorías
$query = $pdo->query("SELECT * FROM Categoria");
$categorias = $query->fetchAll(PDO::FETCH_ASSOC);
?>
