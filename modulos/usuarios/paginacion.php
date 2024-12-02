<?php
// Conexión a la base de datos
require '../../database/db.php';

// Parámetros de paginación
$limite = 5; // Número de usuarios por página
$pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$inicio = ($pagina_actual > 1) ? ($pagina_actual * $limite) - $limite : 0;

// Consulta para obtener el total de usuarios
$total_usuarios = $pdo->query("SELECT COUNT(*) AS total FROM Usuario")->fetch()['total'];
$total_paginas = ceil($total_usuarios / $limite);

// Consulta para obtener los usuarios con paginación
$query = $pdo->prepare("SELECT id, username, apellido, username FROM Usuario LIMIT ?, ?");
$query->bindParam(1, $inicio, PDO::PARAM_INT);
$query->bindParam(2, $limite, PDO::PARAM_INT);
$query->execute();
$usuarios = $query->fetchAll(PDO::FETCH_ASSOC);
?>
