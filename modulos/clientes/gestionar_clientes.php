<?php include 'clientes.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Clientes</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<?php include '../../header.php'; ?>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Gestionar Clientes</h2>

        <!-- Formulario de búsqueda -->
        <form method="GET" class="mb-4">
            <div class="form-row align-items-center">
                <div class="col-auto">
                    <input type="text" class="form-control" name="search" placeholder="Buscar por nombre" value="<?= htmlspecialchars($search) ?>">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">Buscar</button>
                </div>
            </div>
        </form>

        <!-- Tabla de clientes -->
        <table class="table table-striped table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Correo</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($clientes)): ?>
                    <?php foreach ($clientes as $cliente): ?>
                        <tr>
                            <td><?= $cliente['id'] ?></td>
                            <td><?= htmlspecialchars($cliente['nombre']) ?></td>
                            <td><?= htmlspecialchars($cliente['apellido']) ?></td>
                            <td><?= htmlspecialchars($cliente['correo']) ?></td>
                            <td><?= $cliente['estado'] == 1 ? 'Activo' : 'Inactivo' ?></td>
                            <td>
                                <!-- Botones para editar o eliminar -->
                                <button class="btn btn-sm btn-primary" onclick="editCliente(<?= $cliente['id'] ?>, '<?= htmlspecialchars($cliente['nombre']) ?>', '<?= htmlspecialchars($cliente['apellido']) ?>', '<?= htmlspecialchars($cliente['correo']) ?>', '<?= $cliente['estado'] ?>')">Editar</button>
                                <button class="btn btn-sm btn-danger" onclick="deleteCliente(<?= $cliente['id'] ?>)">Eliminar</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6" class="text-center">No hay clientes disponibles.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Paginación -->
        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-center">
                <?php if ($pagina_actual > 1): ?>
                    <li class="page-item"><a class="page-link" href="?pagina=<?= $pagina_actual - 1 ?>&search=<?= htmlspecialchars($search) ?>">&laquo; Anterior</a></li>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                    <li class="page-item <?= $i == $pagina_actual ? 'active' : '' ?>">
                        <a class="page-link" href="?pagina=<?= $i ?>&search=<?= htmlspecialchars($search) ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>

                <?php if ($pagina_actual < $total_paginas): ?>
                    <li class="page-item"><a class="page-link" href="?pagina=<?= $pagina_actual + 1 ?>&search=<?= htmlspecialchars($search) ?>">Siguiente &raquo;</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>

    <!-- Modal para edición de cliente -->
    <div class="modal fade" id="modalCliente" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Editar Cliente</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="POST">
                        <input type="hidden" name="id" id="clienteId">
                        <div class="form-group">
                            <label for="nombre">Nombre:</label>
                            <input type="text" class="form-control" name="nombre" id="clienteNombre" required>
                        </div>
                        <div class="form-group">
                            <label for="apellido">Apellido:</label>
                            <input type="text" class="form-control" name="apellido" id="clienteApellido" required>
                        </div>
                        <div class="form-group">
                            <label for="correo">Correo:</label>
                            <input type="email" class="form-control" name="correo" id="clienteCorreo" required>
                        </div>
                        <div class="form-group">
                            <label for="estado">Estado:</label>
                            <select class="form-control" name="estado" id="clienteEstado">
                                <option value="1">Activo</option>
                                <option value="0">Inactivo</option>
                            </select>
                        </div>
                        <button type="submit" name="update" class="btn btn-primary">Guardar cambios</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para eliminar cliente -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Eliminar Cliente</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>¿Está seguro de eliminar este cliente?</p>
                    <form method="POST">
                        <input type="hidden" name="id" id="deleteId">
                        <button type="submit" name="delete" class="btn btn-danger">Eliminar</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS y jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="scripts.js"></script>
</body>
</html>
