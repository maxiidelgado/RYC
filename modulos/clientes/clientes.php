<?php
require '../../database/db.php';

// Parámetros de paginación
$limite = 5; // Número de clientes por página
$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$inicio = ($pagina_actual > 1) ? ($pagina_actual * $limite) - $limite : 0;

// Obtener el total de clientes para la paginación con búsqueda
$search = isset($_GET['search']) ? $_GET['search'] : '';
$query_total = "SELECT COUNT(*) as total FROM Usuario u 
                JOIN Persona p ON u.persona_id = p.id 
                WHERE u.rela_perfil = 2 AND (p.nombre LIKE ?)";
$stmt_total = $pdo->prepare($query_total);
$stmt_total->execute(['%' . $search . '%']);
$total_clientes = $stmt_total->fetch()['total'];
$total_paginas = ceil($total_clientes / $limite);

// Operaciones CRUD
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update'])) {
        // Actualizar cliente
        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $correo = $_POST['correo'];
        $estado = $_POST['estado'];

        $pdo->beginTransaction();
        $stmt = $pdo->prepare("UPDATE Persona p JOIN Usuario u ON p.id = u.persona_id 
                                SET p.nombre = ?, p.apellido = ?, u.correo = ?, u.estado = ? 
                                WHERE u.id = ? AND u.rela_perfil = 2");
        $stmt->execute([$nombre, $apellido, $correo, $estado, $id]);
        $pdo->commit();
    } elseif (isset($_POST['delete'])) {
        // Eliminar cliente
        $id = $_POST['id'];

        $pdo->beginTransaction();
        $stmt = $pdo->prepare("DELETE u, p FROM Usuario u JOIN Persona p ON u.persona_id = p.id WHERE u.id = ? AND u.rela_perfil = 2");
        $stmt->execute([$id]);
        $pdo->commit();
    }
}

// Obtener clientes con perfil 2 (clientes) con límite para la paginación y búsqueda
$query = $pdo->prepare("SELECT u.id, p.nombre, p.apellido, u.username, u.correo, u.estado 
                        FROM Usuario u 
                        JOIN Persona p ON u.persona_id = p.id 
                        WHERE u.rela_perfil = 2 AND p.nombre LIKE ? 
                        LIMIT ?, ?");
$query->bindParam(1, $searchParam, PDO::PARAM_STR);
$query->bindParam(2, $inicio, PDO::PARAM_INT);
$query->bindParam(3, $limite, PDO::PARAM_INT);
$searchParam = '%' . $search . '%';
$query->execute();
$clientes = $query->fetchAll(PDO::FETCH_ASSOC);
?>
