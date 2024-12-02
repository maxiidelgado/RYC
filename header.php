<?php
// Iniciar la sesión (si no está iniciada)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Incluir la configuración de la base de datos y el archivo de configuración con ROOT_PATH
include 'database/db.php';
include 'config/config.php'; // Asegúrate de incluir el archivo config.php donde definiste ROOT_PATH

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    // Redirigir al login si no está logueado
    header('Location: login/login.php');
    exit;
}

// Obtener el ID del usuario logueado
$usuario_id = $_SESSION['usuario_id'];

// Obtener el perfil del usuario
$query = $pdo->prepare("SELECT perfil_id FROM usuarioperfil WHERE usuario_id = :usuario_id");
$query->execute(['usuario_id' => $usuario_id]);
$perfil_id = $query->fetchColumn();

if (!$perfil_id) {
    die("Perfil no encontrado para el usuario.");
}

// Obtener los módulos permitidos para el perfil
$query = $pdo->prepare("
    SELECT m.nombre, m.url 
    FROM modulo m
    JOIN moduloperfil mp ON m.id = mp.modulo_id
    WHERE mp.perfil_id = :perfil_id
    ORDER BY orden;
");
$query->execute(['perfil_id' => $perfil_id]);
$modulos = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Sistema de Delivery</title>
    <!-- Incluir Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css"> <!-- Asegúrate de tener este archivo -->
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #e67e22;"> <!-- Cambiar a fondo naranja -->
            <a class="navbar-brand" href="#" style="color: #ffffff;">RyC</a> <!-- Texto en blanco -->
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mr-auto">
                    <?php foreach ($modulos as $modulo): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= ROOT_PATH . htmlspecialchars($modulo['url']); ?>" style="color: #ffffff;"> <!-- Texto en blanco -->
                                <?php echo htmlspecialchars($modulo['nombre']); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= ROOT_PATH ?>logout.php" style="color: #ffffff;">Cerrar Sesión</a> <!-- Texto en blanco -->
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <div class="container mt-5">
        <h1 class="text-center" style="color: #E9967A;">Bienvenido a Ricos Y Caseros</h1> 
        <p class="text-center">Utiliza el menú de navegación para acceder a las diferentes secciones.</p>
    </div>

    <!-- Incluir Bootstrap JS y dependencias -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.11/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
