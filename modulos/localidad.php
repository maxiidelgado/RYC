<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../database/db.php';

// Parámetros de paginación
$localidadesPorPagina = 10; // Número de localidades por página
$páginaActual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1; // Página actual (por defecto 1)
$inicio = ($páginaActual - 1) * $localidadesPorPagina; // Calcular el inicio de la página

// Procesar el formulario de alta, baja o modificación de localidad
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['alta'])) {
        // Alta de localidad
        if (isset($_POST['descripcion'], $_POST['rela_provincia'])) {
            $descripcion = $_POST['descripcion'];
            $rela_provincia = $_POST['rela_provincia'];
            try {
                $stmt = $pdo->prepare("INSERT INTO localidad (nombre, provincia_id) VALUES (?, ?)");
                $stmt->execute([$descripcion, $rela_provincia]);
                echo "Localidad agregada exitosamente.";
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        } else {
            echo "Error: Faltan datos para agregar la localidad.";
        }
    } elseif (isset($_POST['baja'])) {
        // Baja de localidad
        if (isset($_POST['id_localidad'])) {
            $id_localidad = $_POST['id_localidad'];
            try {
                $stmt = $pdo->prepare("DELETE FROM localidad WHERE id = ?");
                $stmt->execute([$id_localidad]);
                echo "Localidad eliminada exitosamente.";
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        } else {
            echo "Error: Faltan datos para eliminar la localidad.";
        }
    } elseif (isset($_POST['modificar'])) {
        // Modificación de localidad
        if (isset($_POST['id_localidad'], $_POST['descripcion'], $_POST['rela_provincia'])) {
            $id_localidad = $_POST['id_localidad'];
            $descripcion = $_POST['descripcion'];
            $rela_provincia = $_POST['rela_provincia'];
            try {
                $stmt = $pdo->prepare("UPDATE localidad SET nombre = ?, provincia_id = ? WHERE id = ?");
                $stmt->execute([$descripcion, $rela_provincia, $id_localidad]);
                echo "Localidad modificada exitosamente.";
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        } else {
            echo "Error: Faltan datos para modificar la localidad.";
        }
    } elseif (isset($_POST['editar'])) {
        // Obtener datos de la localidad a modificar
        if (isset($_POST['id_localidad'])) {
            $id_localidad = $_POST['id_localidad'];
            try {
                $stmt = $pdo->prepare("SELECT * FROM localidad WHERE id = ?");
                $stmt->execute([$id_localidad]);
                $localidad = $stmt->fetch(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        } else {
            echo "Error: Faltan datos para obtener la localidad.";
        }
    }
}

// Obtener listado de localidades con paginación
try {
    $stmt = $pdo->prepare("SELECT l.*, p.nombre as nombre_provincia FROM localidad l JOIN provincia p ON l.provincia_id = p.id LIMIT ?, ?");
    $stmt->bindValue(1, $inicio, PDO::PARAM_INT);
    $stmt->bindValue(2, $localidadesPorPagina, PDO::PARAM_INT);
    $stmt->execute();
    $localidades = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Obtener el total de localidades para calcular el número de páginas
    $stmtTotal = $pdo->query("SELECT COUNT(*) FROM localidad");
    $totalLocalidades = $stmtTotal->fetchColumn();
    $totalPaginas = ceil($totalLocalidades / $localidadesPorPagina); // Calcular el total de páginas
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ABM Localidad</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <?php include('../header.php')?>
    <div class="container mt-4">
        <h2>ABM Localidad</h2>

        <h3>Listado de Localidades</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Descripción</th>
                    <th>Provincia</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($localidades as $localidad): ?>
                    <tr>
                        <td><?= htmlspecialchars($localidad['nombre']) ?></td>
                        <td><?= htmlspecialchars($localidad['nombre_provincia']) ?></td>
                        <td>
                            <!-- Modificar -->
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal" 
                                data-id="<?= $localidad['id'] ?>"
                                data-nombre="<?= htmlspecialchars($localidad['nombre']) ?>"
                                data-provincia_id="<?= $localidad['provincia_id'] ?>">
                                Modificar
                            </button>

                            <!-- Eliminar -->
                            <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal"
                                data-id="<?= $localidad['id'] ?>">
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

        <h3>Alta de Localidad</h3>
        <form method="post">
            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción:</label>
                <input type="text" class="form-control" id="descripcion" name="descripcion" required>
            </div>
            <div class="mb-3">
                <label for="rela_provincia" class="form-label">Provincia:</label>
                <select class="form-select" id="rela_provincia" name="rela_provincia" required>
                    <?php
                    $stmt = $pdo->query("SELECT * FROM provincia");
                    $provincias = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($provincias as $provincia) {
                        echo "<option value=\"{$provincia['id']}\">{$provincia['nombre']}</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit" name="alta" class="btn btn-success">Agregar Localidad</button>
        </form>
    </div>

    <!-- Modal para editar localidad -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Modificar Localidad</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id_localidad" id="edit_id_localidad">
                        <div class="mb-3">
                            <label for="edit_descripcion" class="form-label">Descripción:</label>
                            <input type="text" class="form-control" id="edit_descripcion" name="descripcion" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_rela_provincia" class="form-label">Provincia:</label>
                            <select class="form-select" id="edit_rela_provincia" name="rela_provincia" required>
                                <?php
                                foreach ($provincias as $provincia) {
                                    echo "<option value=\"{$provincia['id']}\">{$provincia['nombre']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="modificar" class="btn btn-primary">Modificar</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal para eliminar localidad -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">Eliminar Localidad</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        ¿Estás seguro de que deseas eliminar esta localidad?
                        <input type="hidden" name="id_localidad" id="delete_id_localidad">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="baja" class="btn btn-danger">Eliminar</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
