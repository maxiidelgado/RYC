<?php
// Incluir archivo de configuración para conexión a la base de datos y el header
require 'database/db.php'; // Cambiar según la ubicación de tu archivo db.php
include 'header.php';

// Obtener los menús activos con stock disponible desde la base de datos
$query = $pdo->query("SELECT * FROM Menu WHERE estado = 1 AND stock > 0 ORDER BY id");
$menus = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Delivery</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&family=Lobster&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="css/styles.css"> <!-- Asegúrate de que la ruta sea correcta -->
</head>
<body>

<main>

    <section id="menu" class="menu-section py-5">
        <div class="container">
            <h2 class="text-center mb-5" style="font-family: 'Lobster', cursive; color: #e67e22;">Menú del Día</h2>
            <div class="row">
                <?php if (count($menus) > 0): ?>
                    <?php foreach ($menus as $menu): ?>
                        <div class="col-md-4 mb-4">
                            <div class="card h-100 shadow-sm border-0" style="border-radius: 15px;">
                                <img src="modulos/menu/<?= htmlspecialchars($menu['imagen']) ?>" class="card-img-top" alt="<?= htmlspecialchars($menu['nombre']) ?>" style="border-radius: 15px 15px 0 0;">
                                <div class="card-body text-center">
                                    <h3 class="card-title" style="font-family: 'Roboto', sans-serif; color: #2ecc71;"><?= htmlspecialchars($menu['nombre']) ?></h3>
                                    <p class="card-text" style="font-size: 1.2rem; color: #e74c3c;">Precio: $<?= htmlspecialchars($menu['precio']) ?></p>
                                    <p class="card-text" style="font-size: 1rem; color: #3498db;">Stock disponible: <?= htmlspecialchars($menu['stock']) ?></p>
                                    <form action="ordenar/ordenar.php" method="GET">
                                        <input type="hidden" name="menu_id" value="<?= $menu['id'] ?>">
                                        <button type="submit" class="btn btn-success btn-lg" style="background-color: #e67e22; border: none;">Ordenar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center">
                        <p>No hay menús disponibles en este momento.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

</main>

<footer>
    <div class="container text-center">
        <p>&copy; <?php echo date('Y'); ?> Mi Sistema de Delivery</p>
    </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="main.js"></script> <!-- Asegúrate de que la ruta sea correcta -->

</body>
</html>
