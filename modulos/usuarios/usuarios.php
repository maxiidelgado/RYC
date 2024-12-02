<?php
require '../../database/db.php';

// Verificar la sesión y permisos (opcional)
// if (!isset($_SESSION['usuario_id'])) {
//     header('Location: ../login.php');
//     exit;
// }

// Parámetros de paginación
$limite = 5; // Número de usuarios por página
$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$inicio = ($pagina_actual > 1) ? ($pagina_actual * $limite) - $limite : 0;

// Obtener el total de usuarios para la paginación
$total_usuarios = $pdo->query("SELECT COUNT(*) as total FROM Usuario")->fetch()['total'];
$total_paginas = ceil($total_usuarios / $limite);

// Operaciones CRUD
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['create'])) {
        // Crear usuario
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $fecha_nacimiento = $_POST['fecha_nacimiento'];
        $username = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

        $pdo->beginTransaction();
        $stmt = $pdo->prepare("INSERT INTO Persona (nombre, apellido, fecha_nacimiento) VALUES (?, ?, ?)");
        $stmt->execute([$nombre, $apellido, $fecha_nacimiento]);
        $persona_id = $pdo->lastInsertId();

        $stmt = $pdo->prepare("INSERT INTO Usuario (persona_id, username, password) VALUES (?, ?, ?)");
        $stmt->execute([$persona_id, $username, $password]);
        $pdo->commit();
    } elseif (isset($_POST['update'])) {
        // Actualizar usuario sin modificar la fecha de nacimiento
        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $username = $_POST['username'];

        $pdo->beginTransaction();
        $stmt = $pdo->prepare("UPDATE Persona p JOIN Usuario u ON p.id = u.persona_id SET p.nombre = ?, p.apellido = ?, u.username = ? WHERE u.id = ?");
        $stmt->execute([$nombre, $apellido, $username, $id]);
        $pdo->commit();
    } elseif (isset($_POST['delete'])) {
        // Baja lógica de usuario
        $id = $_POST['id'];

        $pdo->beginTransaction();
        $stmt = $pdo->prepare("UPDATE Usuario SET estado = 0 WHERE id = ?");
        $stmt->execute([$id]);
        $pdo->commit();
    }
}

// Obtener usuarios con límite para la paginación
$query = $pdo->prepare("SELECT u.id, p.nombre, p.apellido, u.username FROM Usuario u JOIN Persona p ON u.persona_id = p.id WHERE u.estado = 1 LIMIT ?, ?");
$query->bindParam(1, $inicio, PDO::PARAM_INT);
$query->bindParam(2, $limite, PDO::PARAM_INT);
$query->execute();
$usuarios = $query->fetchAll(PDO::FETCH_ASSOC);
?>
