<?php include 'categoria.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Categorías</title>
    <style>
        /* Estilo para ocultar el formulario de modificación inicialmente */
        #modificar-form {
            display: none;
        }
    </style>
</head>
<body>
    <?php include '../../header.php'; ?> <!-- Incluir el encabezado -->
    <div class="container">
        <h2>Gestionar Categorías</h2>

        <!-- Formulario de Alta -->
        <h3 id="alta-title">Alta de Categoría</h3>
        <form id="alta-form" method="post">
            <input type="hidden" name="create" value="create">
            <label for="categoria-nombre">Nombre:</label>
            <input type="text" name="nombre" id="categoria-nombre" placeholder="Nombre" required><br>
            <label for="categoria-descripcion">Descripción:</label>
            <textarea name="descripcion" id="categoria-descripcion" placeholder="Descripción"></textarea><br>
            <button type="submit">Crear</button>
        </form>

        <!-- Formulario de Modificación -->
        <h3 id="modificar-title">Modificar Categoría</h3>
        <form id="modificar-form" method="post">
            <input type="hidden" name="id" id="modificar-id">
            <input type="hidden" name="update" value="update">
            <label for="modificar-nombre">Nombre:</label>
            <input type="text" name="nombre" id="modificar-nombre" placeholder="Nombre" required><br>
            <label for="modificar-descripcion">Descripción:</label>
            <textarea name="descripcion" id="modificar-descripcion" placeholder="Descripción"></textarea><br>
            <button type="submit">Guardar Cambios</button>
        </form>

        <!-- Tabla de Categorías -->
        <h3>Listado de Categorías</h3>
        <table class="styled-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($categorias)): ?>
                    <?php foreach ($categorias as $categoria): ?>
                        <tr>
                            <td><?= htmlspecialchars($categoria['id']) ?></td>
                            <td><?= htmlspecialchars($categoria['nombre']) ?></td>
                            <td><?= htmlspecialchars($categoria['descripcion']) ?></td>
                            <td>
                                <button onclick="editarCategoria(<?= $categoria['id'] ?>, '<?= htmlspecialchars($categoria['nombre']) ?>', '<?= htmlspecialchars($categoria['descripcion']) ?>')">Editar</button>
                                <form method="post" style="display:inline;" onsubmit="return confirm('¿Seguro que deseas eliminar esta categoría?');">
                                    <input type="hidden" name="id" value="<?= $categoria['id'] ?>">
                                    <input type="hidden" name="delete" value="delete">
                                    <input type="submit" value="Eliminar">
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">No se encontraron categorías.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Script JS -->
    <script src= "categoria.js"> </script>
    <script src="../../js/script.js"></script>
      
    
</body>
</html>
