<?php
session_start();
require 'database/db.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login/login.php');
    exit();
}

$usuario_id = $_SESSION['usuario_id'];
$message = '';
$errors = [];

// Obtener datos del usuario y de la persona relacionada
$query = $pdo->prepare("
    SELECT u.username, u.password, p.nombre, p.apellido, p.fecha_nacimiento 
    FROM Usuario u
    JOIN Persona p ON u.persona_id = p.id
    WHERE u.id = ?
");
$query->execute([$usuario_id]);
$usuario = $query->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['verify_password'])) {
        // Verificar la contraseña del usuario
        $password = $_POST['password'];
        if (password_verify($password, $usuario['password'])) {
            $_SESSION['verified'] = true;
        } else {
            $errors[] = 'Contraseña incorrecta.';
        }
    } elseif (isset($_POST['update_profile'])) {
        if (isset($_SESSION['verified']) && $_SESSION['verified']) {
            // Actualizar perfil del usuario
            $nombre = $_POST['nombre'];
            $apellido = $_POST['apellido'];
            $fecha_nacimiento = $_POST['fecha_nacimiento'];
            $username = $_POST['username'];

            $query = $pdo->prepare("
                UPDATE Persona SET nombre = ?, apellido = ?, fecha_nacimiento = ?
                WHERE id = (SELECT persona_id FROM Usuario WHERE id = ?)
            ");
            $query->execute([$nombre, $apellido, $fecha_nacimiento, $usuario_id]);

            $query = $pdo->prepare("
                UPDATE Usuario SET username = ? WHERE id = ?
            ");
            $query->execute([$username, $usuario_id]);

            $message = 'Perfil actualizado correctamente.';
            unset($_SESSION['verified']);
        } else {
            $errors[] = 'Debe verificar su contraseña antes de realizar cambios.';
        }
    } elseif (isset($_POST['change_password'])) {
        // Cambiar contraseña
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        // Verificar la contraseña actual
        if (password_verify($current_password, $usuario['password'])) {
            if ($new_password === $confirm_password) {
                // Hash de la nueva contraseña
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $query = $pdo->prepare("
                    UPDATE Usuario SET password = ? WHERE id = ?
                ");
                $query->execute([$hashed_password, $usuario_id]);
                $message = 'Contraseña cambiada exitosamente.';
            } else {
                $errors[] = 'Las contraseñas nuevas no coinciden.';
            }
        } else {
            $errors[] = 'Contraseña actual incorrecta.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil del Usuario</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="container mt-5">
        <h1 class="text-center">Perfil del Usuario</h1>

        <?php if (!empty($message)): ?>
            <div class="alert alert-success" role="alert"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <?php foreach ($errors as $error): ?>
                <div class="alert alert-danger" role="alert"><?= htmlspecialchars($error) ?></div>
            <?php endforeach; ?>
        <?php endif; ?>

        <?php if (!isset($_SESSION['verified']) || !$_SESSION['verified']): ?>
            <form method="POST" class="mt-4">
                <div class="form-group">
                    <label for="password">Contraseña actual:</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>
                <button type="submit" name="verify_password" class="btn btn-primary">Verificar</button>
            </form>
        <?php else: ?>
            <form method="POST" class="mt-4">
                <div class="form-group">
                    <label for="username">Nombre de usuario:</label>
                    <input type="text" id="username" name="username" class="form-control" value="<?= htmlspecialchars($usuario['username']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" class="form-control" value="<?= htmlspecialchars($usuario['nombre']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="apellido">Apellido:</label>
                    <input type="text" id="apellido" name="apellido" class="form-control" value="<?= htmlspecialchars($usuario['apellido']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="fecha_nacimiento">Fecha de nacimiento:</label>
                    <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" class="form-control" value="<?= htmlspecialchars($usuario['fecha_nacimiento']) ?>" required>
                </div>

                <button type="submit" name="update_profile" class="btn btn-primary">Actualizar Perfil</button>
            </form>

            <h2 class="mt-5">Cambiar Contraseña</h2>
            <form method="POST" class="mt-4">
                <div class="form-group">
                    <label for="current_password">Contraseña actual:</label>
                    <input type="password" id="current_password" name="current_password" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="new_password">Nueva contraseña:</label>
                    <input type="password" id="new_password" name="new_password" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirmar nueva contraseña:</label>
                    <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
                </div>

                <button type="submit" name="change_password" class="btn btn-warning">Cambiar Contraseña</button>
            </form>
        <?php endif; ?>

        <a href="index.php" class="btn btn-secondary mt-4">Volver al inicio</a>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
