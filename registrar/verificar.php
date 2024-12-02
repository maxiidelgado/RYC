<?php
require '../database/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = $_POST['correo'];
    $codigo = $_POST['codigo'];

    // Verificar el código de verificación
    $stmt = $pdo->prepare("SELECT * FROM Usuario WHERE correo = ? AND codigo_verificacion = ?");
    $stmt->execute([$correo, $codigo]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        // Actualizar el estado de verificación del correo
        $stmt = $pdo->prepare("UPDATE Usuario SET correo_verificado = 1, codigo_verificacion = NULL WHERE id = ?");
        $stmt->execute([$usuario['id']]);
        
        // Redirigir a index.php después de la verificación exitosa
        header('Location: ../index.php');
        exit(); // Asegúrate de terminar el script después de redirigir
    } else {
        echo '<div class="alert alert-danger" role="alert">Código de verificación inválido o correo no registrado.</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Verificación de Correo</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Verifica tu Correo</h1>
        <form method="POST" class="mt-4">
            <div class="form-group">
                <label for="correo">Correo electrónico</label>
                <input type="email" name="correo" class="form-control" placeholder="Ingresa tu correo" required>
            </div>
            <div class="form-group">
                <label for="codigo">Código de verificación</label>
                <input type="text" name="codigo" class="form-control" placeholder="Ingresa el código de verificación" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Verificar</button>
        </form>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
