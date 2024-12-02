<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../../database/db.php';

// Inicializar la variable $perfiles como un array vacío
$perfiles = [];

function altaPerfil($pdo, $nombre, $descripcion) {
    try {
        $stmt = $pdo->prepare("INSERT INTO perfil (nombre, descripcion) VALUES (?, ?)");
        $stmt->execute([$nombre, $descripcion]);
        return "Perfil agregado exitosamente.";
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    }
}

function bajaPerfil($pdo, $id_perfil) {
    try {
        $stmt = $pdo->prepare("DELETE FROM perfil WHERE id = ?");
        $stmt->execute([$id_perfil]);
        return "Perfil eliminado exitosamente.";
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    }
}

function modificarPerfil($pdo, $id_perfil, $nombre, $descripcion) {
    try {
        $stmt = $pdo->prepare("UPDATE perfil SET nombre = ?, descripcion = ? WHERE id = ?");
        $stmt->execute([$nombre, $descripcion, $id_perfil]);
        return "Perfil modificado exitosamente.";
    } catch (PDOException $e) {
        return "Error: " . $e->getMessage();
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $accion = filter_input(INPUT_POST, 'accion');
    $id_perfil = filter_input(INPUT_POST, 'id_perfil', FILTER_VALIDATE_INT);
    $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
    $descripcion = filter_input(INPUT_POST, 'descripcion', FILTER_SANITIZE_STRING);

    switch ($accion) {
        case 'alta':
            echo altaPerfil($pdo, $nombre, $descripcion);
            break;
        case 'baja':
            echo bajaPerfil($pdo, $id_perfil);
            break;
        case 'modificar':
            echo modificarPerfil($pdo, $id_perfil, $nombre, $descripcion);
            break;
        case 'editar':
            // Obtener el perfil a editar
            $stmt = $pdo->prepare("SELECT * FROM perfil WHERE id = ?");
            $stmt->execute([$id_perfil]);
            $perfil = $stmt->fetch(PDO::FETCH_ASSOC);

            // Devolver los datos del perfil en formato JSON
            header('Content-Type: application/json');
            echo json_encode($perfil);
            exit; // Asegúrate de que no se ejecute más código después de enviar la respuesta JSON
    }
}

// Obtener listado de perfiles
try {
    $stmt = $pdo->query("SELECT * FROM perfil");
    $perfiles = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
