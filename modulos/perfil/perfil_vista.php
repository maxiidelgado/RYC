<?php include 'perfil.php' ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ABM Perfil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Estilo para ocultar el formulario de modificación inicialmente */
        #modificar-form {
            display: none;
        }
    </style>
</head>
<header>
    <?php include ('../../header.php')?>
</header>
<body >
    <div class="container my-5">
        <h2 class="text-center mb-4">Gestión de Perfiles</h2>

        <div class="card mb-4">
            <div class="card-body">
                <h3 class="card-title">Listado de Perfiles</h3>
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($perfiles)): ?>
                            <?php foreach ($perfiles as $perfil): ?>
                                <tr>
                                    <td><?= htmlspecialchars($perfil['nombre']) ?></td>
                                    <td><?= htmlspecialchars($perfil['descripcion']) ?></td>
                                    <td>
                                        <button class="btn btn-warning btn-sm" onclick="editarPerfil(<?= $perfil['id'] ?>)">Modificar</button>
                                        <form method="post" style="display:inline;" onsubmit="return confirm('¿Seguro que deseas eliminar este perfil?');">
                                            <input type="hidden" name="id_perfil" value="<?= $perfil['id'] ?>">
                                            <input type="hidden" name="accion" value="baja">
                                            <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" class="text-center">No se encontraron perfiles.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Formulario de Alta -->
        <div class="card mb-4">
            <div class="card-body">
                <h3 class="card-title" id="alta-title">Alta de Perfil</h3>
                <form id="alta-form" method="post">
                    <input type="hidden" name="accion" value="alta">
                    <div class="mb-3">
                        <label for="nombre-alta" class="form-label">Nombre:</label>
                        <input type="text" id="nombre-alta" name="nombre" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="descripcion-alta" class="form-label">Descripción:</label>
                        <textarea id="descripcion-alta" name="descripcion" rows="4" class="form-control" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-success">Agregar Perfil</button>
                </form>
            </div>
        </div>

        <!-- Formulario de Modificación -->
        <div class="card">
            <div class="card-body">
                <h3 class="card-title" id="modificar-title">Modificar Perfil</h3>
                <form id="modificar-form" method="post">
                    <input type="hidden" name="id_perfil" id="modificar-id">
                    <input type="hidden" name="accion" id="modificar-accion" value="modificar">
                    <div class="mb-3">
                        <label for="nombre-modificar" class="form-label">Nombre:</label>
                        <input type="text" id="nombre-modificar" name="nombre" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="descripcion-modificar" class="form-label">Descripción:</label>
                        <textarea id="descripcion-modificar" name="descripcion" rows="4" class="form-control" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="perfil.js"></script>
</body>
</html>
