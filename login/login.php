<?php
require '../database/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        $stmt = $pdo->prepare("
            SELECT id, password, rela_perfil AS perfil_id
            FROM Usuario
            WHERE username = ?
        ");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['usuario_id'] = $user['id'];
            $_SESSION['perfil_id'] = $user['perfil_id'];
            header('Location: ../index.php');
            exit;
        } else {
            $error = "Usuario o contraseña incorrectos.";
        }
    } catch (PDOException $e) {
        $error = "Error de base de datos: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div class="login-container">
        <form method="post">
            <h2>Iniciar sesión</h2>
            <?php if (isset($error)) : ?>
                <p class="error-message"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>
            <div class="mb-3">
                <input type="text" class="form-control" name="username" placeholder="Usuario" required>
            </div>
            <div class="mb-3">
                <input type="password" class="form-control" name="password" placeholder="Contraseña" required>
            </div>
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">Iniciar sesión</button>
            </div>
            <a href="../registrar/register.php" class="register-link">¿No tienes una cuenta? Regístrate aquí</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
