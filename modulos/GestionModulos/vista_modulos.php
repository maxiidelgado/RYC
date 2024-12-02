<?php
include 'modulos.php'; // Incluye la lógica

// Si hay un mensaje en la URL, mostramos una alerta
if (isset($_GET['mensaje'])) {
    $mensaje = urldecode($_GET['mensaje']);
    echo "<div class='alert alert-success'>$mensaje</div>";
}
?>

<div class="container">
    <h1 class="my-4">Gestión de Módulos</h1>

    <!-- Formulario de alta de módulo -->
    <h3>Agregar Módulo</h3>
    <form action="modulos.php" method="POST">
        <input type="hidden" name="accion" value="crear">
        <div class="form-group">
            <label for="nombre">Nombre</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required>
        </div>
        <div class="form-group">
            <label for="descripcion">Descripción</label>
            <textarea class="form-control" id="descripcion" name="descripcion" required></textarea>
        </div>
        <div class="form-group">
            <label for="url">URL</label>
            <input type="text" class="form-control" id="url" name="url" required>
        </div>
        <div class="form-group">
            <label for="orden">Orden</label>
            <input type="number" class="form-control" id="orden" name="orden" required>
        </div>
        <button type="submit" class="btn btn-success">Agregar Módulo</button>
    </form>

    <!-- Listado de módulos -->
    <h3 class="mt-5">Listado de Módulos</h3>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>URL</th>
                <th>Orden</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($modulos as $modulo): ?>
                <tr>
                    <td><?= $modulo['id'] ?></td>
                    <td><?= $modulo['nombre'] ?></td>
                    <td><?= $modulo['descripcion'] ?></td>
                    <td><?= $modulo['url'] ?></td>
                    <td><?= $modulo['orden'] ?></td>
                    <td>
                        <a href="#modificarModal<?= $modulo['id'] ?>" data-toggle="modal" class="btn btn-primary">Modificar</a>
                        <form action="modulos.php" method="POST" class="d-inline">
                            <input type="hidden" name="accion" value="eliminar">
                            <input type="hidden" name="id" value="<?= $modulo['id'] ?>">
                            <button type="submit" class="btn btn-danger" onclick="return confirm('¿Estás seguro de eliminar este módulo?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Paginación -->
    <div class="pagination-container mt-4">
        <nav aria-label="Paginación">
            <ul class="pagination justify-content-center">
                <li class="page-item <?= $paginaActual == 1 ? 'disabled' : '' ?>">
                    <a class="page-link" href="?pagina=1">Primera</a>
                </li>
                <li class="page-item <?= $paginaActual == 1 ? 'disabled' : '' ?>">
                    <a class="page-link" href="?pagina=<?= $paginaActual - 1 ?>">Anterior</a>
                </li>
                <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                    <li class="page-item <?= $i == $paginaActual ? 'active' : '' ?>">
                        <a class="page-link" href="?pagina=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
                <li class="page-item <?= $paginaActual == $totalPaginas ? 'disabled' : '' ?>">
                    <a class="page-link" href="?pagina=<?= $paginaActual + 1 ?>">Siguiente</a>
                </li>
                <li class="page-item <?= $paginaActual == $totalPaginas ? 'disabled' : '' ?>">
                    <a class="page-link" href="?pagina=<?= $totalPaginas ?>">Última</a>
                </li>
            </ul>
        </nav>
    </div>
</div>



<!-- Bootstrap JS y jQuery -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
