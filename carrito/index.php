<?php
require '../database/db.php';



// Obtener productos
$queryProductos = $pdo->query("SELECT id, nombre, precio FROM Producto");
$productos = $queryProductos->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="productos-container">
    <?php foreach ($productos as $producto): ?>
        <div class="producto">
            <h3><?= $producto['nombre'] ?></h3>
            <p>Precio: $<?= $producto['precio'] ?></p>
            <button onclick="agregarProductoAlCarrito(<?= $producto['id'] ?>, '<?= $producto['nombre'] ?>', <?= $producto['precio'] ?>)">Agregar al Carrito</button>
        </div>
    <?php endforeach; ?>
</div>

<div id="carrito-container">
    <!-- El carrito se renderiza aquí -->
</div>
<p>Total: <span id="total-carrito">$0.00</span></p>
<button onclick="procesarCheckout()">Realizar Pedido</button>

<script src="carrito.js"></script>
<script src="checkout.js"></script>


