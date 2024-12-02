<?php
require '../../database/db.php'; // Conexión a la base de datos

// Configuración de la paginación
$registrosPorPagina = 10; // Cantidad de registros por página
$paginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$inicio = ($paginaActual - 1) * $registrosPorPagina;

// Obtener la lista de usuarios con paginación
$stmt = $pdo->prepare("
    SELECT u.id, p.nombre, p.apellido, u.username, u.rela_perfil 
    FROM Usuario u 
    JOIN Persona p ON u.persona_id = p.id 
    LIMIT :inicio, :registrosPorPagina
");
$stmt->bindValue(':inicio', $inicio, PDO::PARAM_INT);
$stmt->bindValue(':registrosPorPagina', $registrosPorPagina, PDO::PARAM_INT);
$stmt->execute();
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener el total de registros para calcular las páginas
$stmt = $pdo->query("SELECT COUNT(*) as total FROM Usuario");
$totalRegistros = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
$totalPaginas = ceil($totalRegistros / $registrosPorPagina);

// Obtener la lista de perfiles
$stmt = $pdo->query("SELECT id, nombre FROM Perfil");
$perfiles = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Asignar perfil a usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario_id = $_POST['usuario_id'];
    $perfil_id = $_POST['perfil_id'];

    // Verificar si el usuario ya tiene asignado el perfil
    $stmt = $pdo->prepare("SELECT * FROM usuarioperfil WHERE usuario_id = ? AND perfil_id = ?");
    $stmt->execute([$usuario_id, $perfil_id]);
    $existe = $stmt->fetch();

    if (!$existe) {
        // Asignar perfil al usuario
        $stmt = $pdo->prepare("INSERT INTO usuarioperfil (usuario_id, perfil_id) VALUES (?, ?)");
        $stmt->execute([$usuario_id, $perfil_id]);
        $mensaje = "Perfil asignado correctamente.";
    } else {
        $mensaje = "Este perfil ya está asignado al usuario.";
    }
}

// Función para obtener los perfiles asignados a un usuario
function obtenerPerfilesAsignados($pdo, $usuario_id) {
    $stmt = $pdo->prepare("SELECT perfil.nombre FROM usuarioperfil JOIN perfil ON usuarioperfil.perfil_id = perfil.id WHERE usuarioperfil.usuario_id = ?");
    $stmt->execute([$usuario_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>
