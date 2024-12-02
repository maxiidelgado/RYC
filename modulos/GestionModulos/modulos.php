<?php
// Incluimos la conexión a la base de datos y el header
include '../../header.php';
include '../../database/db.php'; // Asegúrate de que este archivo conecta con la base de datos

// Inicializamos mensajes de éxito o error
$mensaje = "";

// Alta de módulo
if (isset($_POST['accion']) && $_POST['accion'] == 'crear') {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $url = $_POST['url'];
    $orden = $_POST['orden'];
    
    $sql = "INSERT INTO modulo (nombre, descripcion, url, orden) VALUES (:nombre, :descripcion, :url, :orden)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':descripcion', $descripcion);
    $stmt->bindParam(':url', $url);
    $stmt->bindParam(':orden', $orden);
    
    if ($stmt->execute()) {
        $mensaje = "Módulo creado exitosamente";
    } else {
        $mensaje = "Error al crear el módulo";
    }
    header('Location: vista_modulos.php?mensaje=' . urlencode($mensaje));
    exit(); 

}

// Modificación de módulo
if (isset($_POST['accion']) && $_POST['accion'] == 'modificar') {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $url = $_POST['url'];
    $orden = $_POST['orden'];
    
    $sql = "UPDATE modulo SET nombre=:nombre, descripcion=:descripcion, url=:url, orden=:orden WHERE id=:id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':nombre', $nombre);
    $stmt->bindParam(':descripcion', $descripcion);
    $stmt->bindParam(':url', $url);
    $stmt->bindParam(':orden', $orden);
    
    if ($stmt->execute()) {
        $mensaje = "Módulo modificado exitosamente";
    } else {
        $mensaje = "Error al modificar el módulo";
    }

    header('Location: vista_modulos.php?mensaje=' . urlencode($mensaje));
    exit(); 
}

// Baja de módulo
if (isset($_POST['accion']) && $_POST['accion'] == 'eliminar') {
    $id = $_POST['id'];
    
    $sql = "DELETE FROM modulo WHERE id=:id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
    
    if ($stmt->execute()) {
        $mensaje = "Módulo eliminado exitosamente";
    } else {
        $mensaje = "Error al eliminar el módulo";
    }
    header('Location: vista_modulos.php?mensaje='. urlencode($mensaje));
}

// Paginación
$modulosPorPagina = 5;
$paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($paginaActual - 1) * $modulosPorPagina;

// Total de módulos
$sqlTotal = "SELECT COUNT(*) AS total FROM modulo";
$stmtTotal = $pdo->prepare($sqlTotal);
$stmtTotal->execute();
$totalModulos = $stmtTotal->fetch(PDO::FETCH_ASSOC)['total'];

// Número total de páginas
$totalPaginas = ceil($totalModulos / $modulosPorPagina);

// Obtener la lista de módulos
$sql = "SELECT * FROM modulo ORDER BY orden LIMIT :offset, :limit";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->bindValue(':limit', $modulosPorPagina, PDO::PARAM_INT);
$stmt->execute();
$modulos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>