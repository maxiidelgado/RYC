<?php include 'banners.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Banners</title>
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        /* Estilo para ocultar el formulario de modificación inicialmente */
        #modificar-form {
            display: none;
        }
        .banner-img {
            max-width: 150px;
            height: auto;
        }
    </style>
</head>
<body>
    <?php include '../../header.php'; ?>
    <div class="container">
        <h2>Gestionar Banners</h2>

        <!-- Formulario de Alta -->
        <h3 id="alta-title">Alta de Banner</h3>
        <form id="alta-form" method="post" enctype="multipart/form-data">
            <input type="hidden" name="create" value="create">
            <label for="banner-imagen">Seleccionar Imagen:</label>
            <input type="file" name="imagen" id="banner-imagen" required><br>
            <label for="banner-link-url">URL del Enlace:</label>
            <input type="text" name="link_url" id="banner-link-url" placeholder="URL del enlace" required><br>
            <button type="submit">Crear</button>
        </form>

        <!-- Formulario de Modificación -->
        <h3 id="modificar-title">Modificar Banner</h3>
        <form id="modificar-form" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" id="modificar-id">
            <input type="hidden" name="update" value="update">
            <label for="modificar-imagen">Seleccionar Nueva Imagen:</label>
            <input type="file" name="imagen" id="modificar-imagen"><br>
            <label for="modificar-link-url">URL del Enlace:</label>
            <input type="text" name="link_url" id="modificar-link-url" placeholder="URL del enlace" required><br>
            <button type="submit">Guardar Cambios</button>
        </form>

        <!-- Tabla de Banners -->
        <h3>Listado de Banners</h3>
        <table class="styled-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Imagen</th>
                    <th>Link URL</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($banners)): ?>
                    <?php foreach ($banners as $banner): ?>
                        <tr>
                            <td><?= htmlspecialchars($banner['id']) ?></td>
                            <td>
                                <img src="<?= htmlspecialchars($banner['imagen_url']) ?>" alt="Banner Image" class="banner-img">
                            </td>
                            <td><?= htmlspecialchars($banner['link_url']) ?></td>
                            <td>
                                <button onclick="editarBanner(<?= $banner['id'] ?>, '<?= htmlspecialchars($banner['imagen_url']) ?>', '<?= htmlspecialchars($banner['link_url']) ?>')">Editar</button>
                                <form method="post" style="display:inline;" onsubmit="return confirm('¿Seguro que deseas eliminar este banner?');">
                                    <input type="hidden" name="id" value="<?= $banner['id'] ?>">
                                    <input type="hidden" name="delete" value="delete">
                                    <input type="submit" value="Eliminar">
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">No se encontraron banners.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script src="banner.js"></script>
</body>
</html>
