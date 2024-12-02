<?php
require '../../database/db.php'; // Incluir archivo de conexión a la base de datos

// Obtener entradas del menú activas
$query = $pdo->query("SELECT * FROM Menu WHERE estado = 1 ORDER BY id");
$menu = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Menú</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../../css/styles.css"> <!-- Asegúrate de que esta ruta sea correcta -->
</head>
<body>
<?php include '../../header.php'; ?> <!-- Incluir el encabezado -->

    <div class="container mt-5">
        <h2 class="text-center">Gestionar Menú</h2>

        <!-- Formulario para Crear/Actualizar Menú -->
        <div class="card mb-4">
            <div class="card-header">
                <h3 id="form-title" class="mb-0">Crear Nuevo Menú</h3>
            </div>
            <div class="card-body">
                <form action="menu.php" method="POST" id="menu-form" enctype="multipart/form-data"> <!-- Agregar enctype -->
                    <input type="hidden" name="id" id="menu-id">
                    <div class="form-group">
                        <label for="menu-nombre">Nombre:</label>
                        <input type="text" class="form-control" name="nombre" id="menu-nombre" placeholder="Nombre" required>
                    </div>
                    <div class="form-group">
                        <label for="menu-precio">Precio:</label>
                        <input type="text" class="form-control" name="precio" id="menu-precio" placeholder="Precio" required>
                    </div>
                    <div class="form-group">
                        <label for="menu-stock">Stock:</label> <!-- Campo nuevo para el stock -->
                        <input type="number" class="form-control" name="stock" id="menu-stock" placeholder="Cantidad en stock" required>
                    </div>
                    <div class="form-group">
                        <label for="menu-estado">Estado:</label>
                        <select name="estado" id="menu-estado" class="form-control">
                            <option value="1">Activo</option>
                            <option value="0">Inactivo</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="menu-imagen">Imagen:</label>
                        <input type="file" class="form-control-file" name="imagen" id="menu-imagen" accept="image/*"><br> <!-- Campo para subir imagen -->
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary" name="create" id="create-btn">Crear</button>
                        <button type="submit" class="btn btn-warning" name="update" id="update-btn" style="display:none;">Actualizar</button>
                        <button type="button" class="btn btn-secondary" id="cancel-btn" style="display:none;" onclick="cancelEdit()">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Lista de Menús -->
        <h3 class="text-center mt-4">Lista de Menús</h3>
        <div class="row">
            <?php foreach ($menu as $item): ?>
                <div class="col-md-4 mb-4">
                    <div class="card menu-item h-100">
                        <img src="<?= htmlspecialchars($item['imagen']) ?>" alt="Imagen de <?= htmlspecialchars($item['nombre']) ?>" class="card-img-top">
                        <div class="card-body text-center">
                            <h5 class="card-title"><?= htmlspecialchars($item['nombre']) ?></h5>
                            <p class="card-text">Precio: <?= htmlspecialchars($item['precio']) ?></p>
                            <p class="card-text">Stock: <?= htmlspecialchars($item['stock']) ?> unidades</p> <!-- Mostrar stock -->
                            <p class="card-text"><?= $item['estado'] == 1 ? 'Activo' : 'Inactivo' ?></p>
                            <button type="button" class="btn btn-info" onclick="editMenu(<?= $item['id'] ?>, '<?= htmlspecialchars($item['nombre']) ?>', '<?= htmlspecialchars($item['precio']) ?>', <?= $item['estado'] ?>, '<?= htmlspecialchars($item['stock']) ?>')">Editar</button>
                            <form action="menu.php" method="POST" style="display:inline;">
                                <input type="hidden" name="id" value="<?= $item['id'] ?>">
                                <button type="submit" class="btn btn-danger" name="delete" onclick="return confirm('¿Está seguro de eliminar este menú?')">Eliminar</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

<footer>
    <div class="container text-center">
        <p>&copy; <?php echo date('Y'); ?> Mi Sistema de Delivery</p>
    </div>
</footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.11/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="menu.js"></script>
</body>
</html>
