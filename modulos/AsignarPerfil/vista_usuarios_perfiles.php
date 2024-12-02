<?php
require 'usuarios_perfiles.php';
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
    <?php include '../../header.php';?>
<div class="container mt-5">
    <h2>Asignar Perfiles a Usuarios</h2>

    <?php if (isset($mensaje)): ?>
        <div class="alert alert-info"><?= htmlspecialchars($mensaje) ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="mb-3">
            <label for="usuario_id" class="form-label">Usuario</label>
            <select class="form-select" name="usuario_id" id="usuario_id" required>
                <option value="">Seleccione un usuario</option>
                <?php foreach ($usuarios as $usuario): ?>
                    <option value="<?= $usuario['id'] ?>">
                        <?= htmlspecialchars($usuario['nombre'] . ' ' . $usuario['apellido'] . ' (' . $usuario['username'] . ')') ?>
                    </option>
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
                    $perfiles_asignados = obtenerPerfilesAsignados($pdo, $usuario['id']);
                    foreach ($perfiles_asignados as $perfil_asignado) {
                        echo htmlspecialchars($perfil_asignado['nombre']) . '<br>';
                    }
                    ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Paginación -->
    <nav>
        <ul class="pagination justify-content-center">
            <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                <li class="page-item <?= ($i == $paginaActual) ? 'active' : '' ?>">
                    <a class="page-link" href="?pagina=<?= $i ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
        </ul>
    </nav>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
