<?php include 'inventario.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Inventario</title>
    <link rel="stylesheet" href="../css/styles.css"> <!-- Estilo CSS para la tabla -->
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
        <h2>Gestionar Inventario</h2>

        <!-- Formulario de Alta -->
        <h3 id="alta-title">Crear Producto</h3>
        <form id="alta-form" method="post">
            <input type="hidden" name="create">
            <label for="nombre-alta">Nombre:</label>
            <input type="text" name="nombre" id="nombre-alta" placeholder="Nombre" required>
            <label for="descripcion-alta">Descripción:</label>
            <textarea name="descripcion" id="descripcion-alta" placeholder="Descripción"></textarea>
            <label for="precio-alta">Precio:</label>
            <input type="number" name="precio" id="precio-alta" placeholder="Precio" step="0.01" required>
            <label for="stock-alta">Stock:</label>
            <input type="number" name="stock" id="stock-alta" placeholder="Stock" required>
            <label for="cantidad-alta">Cantidad inicial:</label>
            <input type="number" name="cantidad" id="cantidad-alta" placeholder="Cantidad inicial" required>
            <button type="submit">Crear</button>
        </form>

        <!-- Formulario de Modificación -->
        <h3 id="modificar-title">Modificar Producto</h3>
        <form id="modificar-form" method="post">
            <input type="hidden" name="update">
            <input type="hidden" name="id" id="modificar-id">
            <label for="nombre-modificar">Nombre:</label>
            <input type="text" name="nombre" id="modificar-nombre" placeholder="Nombre" required>
            <label for="descripcion-modificar">Descripción:</label>
            <textarea name="descripcion" id="modificar-descripcion" placeholder="Descripción"></textarea>
            <label for="precio-modificar">Precio:</label>
            <input type="number" name="precio" id="modificar-precio" placeholder="Precio" step="0.01" required>
            <label for="stock-modificar">Stock:</label>
            <input type="number" name="stock" id="modificar-stock" placeholder="Stock" required>
            <button type="submit">Guardar Cambios</button>
        </form>

        <!-- Tabla de Productos -->
        <h3>Listado de Productos</h3>
        <table class="styled-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Último Inventario</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($productos as $producto): ?>
                    <tr>
                        <td><?= $producto['id'] ?></td>
                        <td><?= htmlspecialchars($producto['nombre']) ?></td>
                        <td><?= htmlspecialchars($producto['descripcion']) ?></td>
                        <td><?= number_format($producto['precio'], 2, ',', '.') ?></td>
                        <td><?= $producto['stock'] ?></td>
                        <td><?= isset($producto['cantidad']) ? $producto['cantidad'] : 'N/A' ?></td>
                        <td>
                            <button onclick="editProducto(<?= $producto['id'] ?>, '<?= addslashes($producto['nombre']) ?>', '<?= addslashes($producto['descripcion']) ?>', <?= $producto['precio'] ?>, <?= $producto['stock'] ?>)">Editar</button>
                            <button onclick="deleteProducto(<?= $producto['id'] ?>)">Eliminar</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Script JS -->
    <script src="inventario.js"></script>
    <script src= "../../js/script.js"></script>
 
    
</body>
</html>
