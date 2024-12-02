<?php include 'usuarios.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Usuarios</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include '../../header.php'; ?> <!-- Incluir el encabezado -->
    <div class="container">
        <h2>Gestionar Usuarios</h2>

        <!-- Formulario de Crear Usuario -->
        <h3 id="form-title">Crear Usuario</h3>
        <form id="create-form" method="post">
            <input type="hidden" name="create">
            <input type="text" name="nombre" id="user-nombre" placeholder="Nombre" required>
            <input type="text" name="apellido" id="user-apellido" placeholder="Apellido" required>
            <input type="date" name="fecha_nacimiento" id="user-fecha-nacimiento" placeholder="Fecha de nacimiento" required>
            <input type="text" name="username" id="user-username" placeholder="Usuario" required>
            <input type="password" name="password" id="user-password" placeholder="Contraseña" required>
            <button type="submit">Crear</button>
        </form>

        <!-- Formulario de Modificación (sin fecha de nacimiento) -->
        <h3 id="modificar-title">Modificar Usuario</h3>
        <form id="modificar-form" method="post">
            <input type="hidden" name="update">
            <input type="hidden" name="id" id="modificar-id">
            <label for="nombre">Nombre</label>
            <input type="text" name="nombre" id="modificar-nombre" placeholder="Nombre" required>
            <label for="apellido">Apellido</label>
            <input type="text" name="apellido" id="modificar-apellido" placeholder="Apellido" required>
            <label for="username">Usuario</label>
            <input type="text" name="username" id="modificar-username" placeholder="Usuario" required>
            <button type="submit">Guardar Cambios</button>
        </form>

        <!-- Tabla de Usuarios -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Usuario</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $usuario): ?>
                    <tr>
                        <td><?= $usuario['id'] ?></td>
                        <td><?= htmlspecialchars($usuario['nombre']) ?></td>
                        <td><?= htmlspecialchars($usuario['apellido']) ?></td>
                        <td><?= htmlspecialchars($usuario['username']) ?></td>
                        <td>
                            <button onclick="editUser(<?= $usuario['id'] ?>, '<?= htmlspecialchars($usuario['nombre']) ?>', '<?= htmlspecialchars($usuario['apellido']) ?>', '<?= htmlspecialchars($usuario['username']) ?>')">Editar</button>
                            <button onclick="deleteUser(<?= $usuario['id'] ?>)">Eliminar</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Paginación -->
        <nav>
            <ul class="pagination">
                <?php if ($pagina_actual > 1): ?>
                    <li><a href="?pagina=<?= $pagina_actual - 1 ?>">&laquo; Anterior</a></li>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                    <li><a href="?pagina=<?= $i ?>" <?= $i == $pagina_actual ? 'class="active"' : '' ?>><?= $i ?></a></li>
                <?php endfor; ?>

                <?php if ($pagina_actual < $total_paginas): ?>
                    <li><a href="?pagina=<?= $pagina_actual + 1 ?>">Siguiente &raquo;</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>

    <!-- Script JS -->
    <script src="usuario.js"></script>
    <script src="../../validaciones.js"></script>
</body>
</html>
