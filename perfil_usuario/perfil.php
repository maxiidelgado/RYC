<?php
session_start();
require '../database/db.php';  // Incluir la conexión a la base de datos
require 'datos_usuario.php';     // Incluir las funciones

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login/login.php');
    exit();
}

$usuario_id = $_SESSION['usuario_id'];

$usuario = obtenerDatosUsuario($usuario_id, $pdo);

$historial_pedidos = obtenerHistorialPedidos($usuario_id, $pdo);

$puntos = obtenerPuntosUsuario($usuario_id, $pdo);

$message = '';
if ($usuario === null) {
    $message = 'No se encontraron datos del usuario.';
} elseif (isset($usuario['error'])) {
    $message = $usuario['error'];
}

if (isset($historial_pedidos['error'])) {
    $message = $historial_pedidos['error'];
}

if (isset($puntos['error'])) {
    $message = $puntos['error'];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Datos</title>
    <!-- Enlace a Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Enlace a Font Awesome para iconos -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- Enlace a tu archivo de estilos personalizado -->
    <link rel="stylesheet" href="../css/styles.css">
</head>
<?php include '../header.php'?>
<body class="bg-light text-dark">

    <div class="container mt-5">
        <h1 class="text-center mb-4" style="font-family: 'Lobster', cursive; color: #2c3e50;">Mi Perfil</h1>

        <!-- Mostrar mensaje si hay algún error o mensaje -->
        <?php if ($message): ?>
            <div class="alert alert-danger"><?php echo $message; ?></div>
        <?php endif; ?>

        <!-- Datos del Usuario -->
        <?php if ($usuario): ?>
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h3 class="card-title text-success"><i class="fas fa-user"></i> Información Personal</h3>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-envelope"></i> <strong>Correo:</strong> <?php echo htmlspecialchars($usuario['username']); ?></li>
                        <li><i class="fas fa-user-alt"></i> <strong>Nombre:</strong> <?php echo htmlspecialchars($usuario['nombre']); ?></li>
                        <li><i class="fas fa-user-alt-slash"></i> <strong>Apellido:</strong> <?php echo htmlspecialchars($usuario['apellido']); ?></li>
                        <li><i class="fas fa-birthday-cake"></i> <strong>Fecha de Nacimiento:</strong> <?php echo htmlspecialchars($usuario['fecha_nacimiento']); ?></li>
                    </ul>
                </div>
                <a href="../perfil.php" class="btn btn-lg" style="background-color:rgb(219, 186, 52); color: white;"><i class="fas fa-arrow-left"></i> Actualizar datos personales</a>
            </div>
        <?php endif; ?>

        <!-- Historial de Pedidos -->
        <div class="card shadow-sm mb-4">
    <div class="card-body">
        <h3 class="card-title text-success"><i class="fas fa-box"></i> Historial de Pedidos</h3>
        <?php if ($historial_pedidos): ?>
            <table class="table table-striped table-bordered text-center">
                <thead class="thead-light">
                    <tr>
                        <th>ID Pedido</th>
                        <th>Dirección</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($historial_pedidos as $pedido): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($pedido['id_pedido']); ?></td>
                            <td><?php echo htmlspecialchars($pedido['direccion_entrega']); ?></td>
                            <td><?php echo htmlspecialchars($pedido['fecha_pedido']); ?></td>
                            <td><?php echo htmlspecialchars($pedido['estado']); ?></td>
                            <td>$<?php echo htmlspecialchars($pedido['total']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <!-- Botón para ver más pedidos -->
            <div class="text-center mt-4">
                <a href="ver_historial.php" class="btn btn-outline-primary">Ver historial completo</a>
            </div>
            <?php else: ?>
                 <p class="text-center">No tienes pedidos realizados.</p>
            <?php endif; ?>
        </div>
    </div>


        <!-- Puntos Acumulados -->
        <div class="card shadow-sm mb-4">
            <div class="card-body text-center">
                <h3 class="card-title text-success"><i class="fas fa-gem"></i> Puntos Acumulados</h3>
                <p class="display-4"><?php echo $puntos; ?> <i class="fas fa-star"></i></p>
                <div class="progress mb-3">
                    <div class="progress-bar" role="progressbar" style="width: <?php echo min(100, $puntos / 10); ?>%" aria-valuenow="<?php echo $puntos; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>

        <!-- Botón para volver al Dashboard -->
        <div class="text-center mt-4">
            <a href="../index.php" class="btn btn-lg" style="background-color: #3498db; color: white;"><i class="fas fa-arrow-left"></i> Volver al Dashboard</a>
        </div>
    </div>


<footer>
    <div class="container text-center">
        <p>&copy; <?php echo date('Y'); ?> Mi Sistema de Delivery</p>
    </div>
</footer>
    <!-- Enlace a Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
