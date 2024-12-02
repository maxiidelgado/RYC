<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../database/db.php';

// Parámetros de paginación
$provinciasPorPagina = 10; // Número de provincias por página
$páginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1; // Página actual (por defecto 1)
$inicio = ($páginaActual - 1) * $provinciasPorPagina; // Calcular el inicio de la página

// Procesar el formulario de alta, baja o modificación de provincia
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['alta'])) {
        // Alta de provincia
        if (isset($_POST['descripcion'], $_POST['rela_pais'])) {
            $descripcion = $_POST['descripcion'];
            $rela_pais = $_POST['rela_pais'];
            try {
                $stmt = $pdo->prepare("INSERT INTO provincia (nombre, pais_id, estado) VALUES (?, ?, 1)");
                $stmt->execute([$descripcion, $rela_pais]);
                echo "Provincia agregada exitosamente.";
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        } else {
            echo "Error: Faltan datos para agregar la provincia.";
        }
    } elseif (isset($_POST['baja'])) {
        // Baja de provincia
        if (isset($_POST['id_provincia'])) {
            $id_provincia = $_POST['id_provincia'];
            try {
                $stmt = $pdo->prepare("UPDATE provincia SET estado = 0 WHERE id = ?");
                $stmt->execute([$id_provincia]);
                echo "Provincia eliminada exitosamente.";
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        } else {
            echo "Error: Faltan datos para eliminar la provincia.";
        }
    } elseif (isset($_POST['modificar'])) {
        // Modificación de provincia
        if (isset($_POST['id_provincia'], $_POST['descripcion'], $_POST['rela_pais'])) {
            $id_provincia = $_POST['id_provincia'];
            $descripcion = $_POST['descripcion'];
            $rela_pais = $_POST['rela_pais'];
            try {
                $stmt = $pdo->prepare("UPDATE provincia SET nombre = ?, pais_id = ? WHERE id = ?");
                $stmt->execute([$descripcion, $rela_pais, $id_provincia]);
                echo "Provincia modificada exitosamente.";
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        } else {
            echo "Error: Faltan datos para modificar la provincia.";
        }
    } elseif (isset($_POST['editar'])) {
        // Obtener datos de la provincia a modificar
        if (isset($_POST['id_provincia'])) {
            $id_provincia = $_POST['id_provincia'];
            try {
                $stmt = $pdo->prepare("SELECT * FROM provincia WHERE id = ?");
                $stmt->execute([$id_provincia]);
                $provincia = $stmt->fetch(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        } else {
            echo "Error: Faltan datos para obtener la provincia.";
        }
    }
}

// Obtener listado de provincias con paginación
try {
    $stmt = $pdo->prepare("SELECT p.*, pais.nombre as nombre_pais FROM provincia p JOIN pais ON p.pais_id = pais.id WHERE p.estado = 1 LIMIT ?, ?");
    $stmt->bindValue(1, $inicio, PDO::PARAM_INT);
    $stmt->bindValue(2, $provinciasPorPagina, PDO::PARAM_INT);
    $stmt->execute();
    $provincias = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Obtener el total de provincias para calcular el número de páginas
    $stmtTotal = $pdo->query("SELECT COUNT(*) FROM provincia WHERE estado = 1");
    $totalProvincias = $stmtTotal->fetchColumn();
    $totalPaginas = ceil($totalProvincias / $provinciasPorPagina); // Calcular el total de páginas
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ABM Provincia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <?php include '../header.php'?>
    <div class="container mt-4">
        <h2>ABM Provincia</h2>

        <h3>Listado de Provincias</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Descripción</th>
                    <th>País</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($provincias as $provincia): ?>
                    <tr>
                        <td><?= htmlspecialchars($provincia['nombre']) ?></td>
                        <td><?= htmlspecialchars($provincia['nombre_pais']) ?></td>
                        <td>
                            <!-- Modificar -->
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal" 
                                data-id="<?= $provincia['id'] ?>"
                                data-nombre="<?= htmlspecialchars($provincia['nombre']) ?>"
                                data-pais_id="<?= $provincia['pais_id'] ?>">
                                Modificar
                            </button>

                            <!-- Eliminar -->
                            <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal"
                                data-id="<?= $provincia['id'] ?>">
                                Eliminar
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="d-flex justify-content-between">
            <!-- Enlaces de Paginación -->
            <div>
                <?php if ($páginaActual > 1): ?>
                    <a href="?pagina=<?= $páginaActual - 1 ?>" class="btn btn-secondary">Anterior</a>
                <?php endif; ?>

                <?php if ($páginaActual < $totalPaginas): ?>
                    <a href="?pagina=<?= $páginaActual + 1 ?>" class="btn btn-secondary">Siguiente</a>
                <?php endif; ?>
            </div>
            <div>
                <span>Página <?= $páginaActual ?> de <?= $totalPaginas ?></span>
            </div>
        </div>

        <h3>Alta de Provincia</h3>
        <form method="post">
            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción:</label>
                <input type="text" class="form-control" id="descripcion" name="descripcion" required>
            </div>
            <div class="mb-3">
                <label for="rela_pais" class="form-label">País:</label>
                <select class="form-select" id="rela_pais" name="rela_pais" required>
                    <?php
                    $stmt = $pdo->query("SELECT * FROM pais");
                    $paises = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($paises as $pais) {
                        echo "<option value=\"{$pais['id']}\">{$pais['nombre']}</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit" name="alta" class="btn btn-success">Agregar Provincia</button>
        </form>
    </div>

    <!-- Modal para editar provincia -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Modificar Provincia</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="edit_id_provincia" name="id_provincia">
                        <div class="mb-3">
                            <label for="edit_descripcion" class="form-label">Descripción:</label>
                            <input type="text" class="form-control" id="edit_descripcion" name="descripcion" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_rela_pais" class="form-label">País:</label>
                            <select class="form-select" id="edit_rela_pais" name="rela_pais" required>
                                <?php foreach ($paises as $pais): ?>
                                    <option value="<?= $pais['id'] ?>"><?= $pais['nombre'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" name="modificar" class="btn btn-primary">Modificar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal para eliminar provincia -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">Eliminar Provincia</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="delete_id_provincia" name="id_provincia">
                        <p>¿Está seguro de eliminar esta provincia?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" name="baja" class="btn btn-danger">Eliminar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Cargar datos en el modal de edición
        var editModal = document.getElementById('editModal');
        editModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var id = button.getAttribute('data-id');
            var nombre = button.getAttribute('data-nombre');
            var pais_id = button.getAttribute('data-pais_id');

            document.getElementById('edit_id_provincia').value = id;
            document.getElementById('edit_descripcion').value = nombre;
            document.getElementById('edit_rela_pais').value = pais_id;
        });

        // Cargar id de provincia en el modal de eliminación
        var deleteModal = document.getElementById('deleteModal');
        deleteModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var id = button.getAttribute('data-id');
            document.getElementById('delete_id_provincia').value = id;
        });
    </script>
</body>
</html>
