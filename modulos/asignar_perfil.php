<?php
require '../database/db.php'; // Conexión a la base de datos

// Obtener la lista de usuarios
$stmt = $pdo->query("SELECT u.id, p.nombre, p.apellido, u.username, u.rela_perfil FROM Usuario u JOIN Persona p ON u.persona_id = p.id");
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
    } else {
        echo "Este perfil ya está asignado al usuario.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asignar Perfiles a Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Asignar Perfiles a Usuarios</h2>

    <form method="POST" >
        <div class="mb-3">
            <label for="usuario_id" class="form-label">Usuario</label>
            <select class="form-select" name="usuario_id" id="usuario_id" required>
                <option value="">Seleccione un usuario</option>
                <?php foreach ($usuarios as $usuario): ?>
                    <option value="<?= $usuario['id'] ?>"><?= htmlspecialchars($usuario['nombre'] . ' ' . $usuario['apellido'] . ' (' . $usuario['username'] . ')') ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="perfil_id" class="form-label">Perfil</label>
            <select class="form-select" name="perfil_id" id="perfil_id" required>
                <option value="">Seleccione un perfil</option>
                <?php foreach ($perfiles as $perfil): ?>
                    <option value="<?= $perfil['id'] ?>"><?= htmlspecialchars($perfil['nombre']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Asignar Perfil</button>
    </form>

    <!-- Tabla de usuarios y sus perfiles asignados -->
    <h3 class="mt-5">Usuarios con Perfiles Asignados</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID Usuario</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Perfiles Asignados</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($usuarios as $usuario): ?>
            <tr>
                <td><?= $usuario['id'] ?></td>
                <td><?= htmlspecialchars($usuario['nombre']) ?></td>
                <td><?= htmlspecialchars($usuario['apellido']) ?></td>
                <td>
                    <?php
                    // Obtener perfiles asignados a cada usuario
                    $stmt = $pdo->prepare("SELECT perfil.nombre FROM usuarioperfil JOIN perfil ON usuarioperfil.perfil_id = perfil.id WHERE usuarioperfil.usuario_id = ?");
                    $stmt->execute([$usuario['id']]);
                    $perfiles_asignados = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    // Mostrar los perfiles asignados
                    foreach ($perfiles_asignados as $perfil_asignado) {
                        echo htmlspecialchars($perfil_asignado['nombre']) . '<br>';
                    }
                    ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
