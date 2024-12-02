<?php
include '../database/db.php';

// Lógica para manejar la paginación
$records_per_page = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $records_per_page;

// Lógica para alta de registros
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add'])) {
    $descripcion = $_POST['descripcion'];

    $query = "INSERT INTO tipocontacto (descripcion) VALUES (:descripcion)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);

    if ($stmt->execute()) {
        header('Location: tipo_contacto.php');
        exit();
    }
}

// Lógica para editar un registro
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit'])) {
    $id = $_POST['id'];
    $descripcion = $_POST['descripcion'];

    $update_query = "UPDATE tipocontacto SET descripcion = :descripcion WHERE id = :id";
    $update_stmt = $pdo->prepare($update_query);
    $update_stmt->bindParam(':descripcion', $descripcion, PDO::PARAM_STR);
    $update_stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if ($update_stmt->execute()) {
        header('Location: tipo_contacto.php');
        exit();
    }
}

// Lógica para eliminar un registro
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    $delete_query = "DELETE FROM tipocontacto WHERE id = :id";
    $delete_stmt = $pdo->prepare($delete_query);
    $delete_stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if ($delete_stmt->execute()) {
        header('Location: tipo_contacto.php');
        exit();
    }
}

// Obtener registros desde la base de datos
$query = "SELECT * FROM tipocontacto LIMIT :offset, :records_per_page";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->bindParam(':records_per_page', $records_per_page, PDO::PARAM_INT);
$stmt->execute();
$tipocontacto = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Contar el total de registros
$total_query = "SELECT COUNT(*) FROM tipocontacto";
$total_stmt = $pdo->query($total_query);
$total_records = $total_stmt->fetchColumn();
$total_pages = ceil($total_records / $records_per_page);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Tipos de Contacto</title>
    <!-- Cargar Bootstrap desde el CDN más reciente -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<?php include '../header.php'?>
<body class="bg-light">
<div class="container mt-5">
    <h2 class="mb-4">Gestión de Tipos de Contacto</h2>

    <!-- Formulario para agregar un nuevo tipo de contacto -->
    <form method="POST">
        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <input type="text" class="form-control" id="descripcion" name="descripcion" required>
        </div>
        <button type="submit" class="btn btn-primary" name="add">Agregar</button>
    </form>

    <h3 class="mt-4">Lista de Tipos de Contacto</h3>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Descripción</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tipocontacto as $row): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['descripcion'] ?></td>
                    <td>
                        <!-- Editar -->
                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#editModal" data-id="<?= $row['id'] ?>" data-descripcion="<?= $row['descripcion'] ?>">Editar</button>
                        <!-- Eliminar -->
                        <a href="tipo_contacto.php?delete=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar este registro?')">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Paginación -->
    <nav>
        <ul class="pagination">
            <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                <a class="page-link" href="?page=<?= $page - 1 ?>">Anterior</a>
            </li>
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
            <li class="page-item <?= $page >= $total_pages ? 'disabled' : '' ?>">
                <a class="page-link" href="?page=<?= $page + 1 ?>">Siguiente</a>
            </li>
        </ul>
    </nav>

    <!-- Modal para editar -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Editar Tipo de Contacto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST">
                        <input type="hidden" id="edit_id" name="id">
                        <div class="mb-3">
                            <label for="edit_descripcion" class="form-label">Descripción</label>
                            <input type="text" class="form-control" id="edit_descripcion" name="descripcion" required>
                        </div>
                        <button type="submit" class="btn btn-warning" name="edit">Actualizar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Cargar Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Llenar el modal con los datos del registro a editar
    var editModal = document.getElementById('editModal');
    editModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        var id = button.getAttribute('data-id');
        var descripcion = button.getAttribute('data-descripcion');

        var modalId = editModal.querySelector('#edit_id');
        var modalDescripcion = editModal.querySelector('#edit_descripcion');

        modalId.value = id;
        modalDescripcion.value = descripcion;
    });
</script>
</body>
</html>
