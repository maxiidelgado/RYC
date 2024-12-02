<?php
// Iniciar la sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Incluir el archivo de conexión a la base de datos
require_once '../database/db.php';

// Variables de mensaje
$mensaje = "";

// Procesar el formulario de alta, baja o modificación de país
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['accion'])) {
        try {
            if ($_POST['accion'] == 'alta' && isset($_POST['descripcion']) && !empty($_POST['descripcion'])) {
                // Alta de país
                $descripcion = $_POST['descripcion'];
                $stmt = $pdo->prepare("INSERT INTO pais (nombre) VALUES (?)");
                $stmt->execute([$descripcion]);
                $mensaje = "País agregado exitosamente.";
            } elseif ($_POST['accion'] == 'baja' && isset($_POST['id_pais'])) {
                // Baja de país
                $id_pais = $_POST['id_pais'];
                $stmt = $pdo->prepare("DELETE FROM pais WHERE id = ?");
                $stmt->execute([$id_pais]);
                $mensaje = "País eliminado exitosamente.";
            } elseif ($_POST['accion'] == 'modificar' && isset($_POST['id_pais'], $_POST['nombre']) && !empty($_POST['nombre'])) {
                // Modificación de país
                $id_pais = $_POST['id_pais'];
                $descripcion = $_POST['nombre'];
                $stmt = $pdo->prepare("UPDATE pais SET nombre = ? WHERE id = ?");
                $stmt->execute([$descripcion, $id_pais]);
                $mensaje = "País modificado exitosamente.";
            } else {
                $mensaje = "Error: Faltan datos necesarios.";
            }
        } catch (PDOException $e) {
            $mensaje = "Error: " . $e->getMessage();
        }
    }
}

// Obtener listado de países
try {
    $stmt = $pdo->query("SELECT * FROM pais");
    $paises = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $mensaje = "Error al obtener los países: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ABM País</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Estilo CSS adicional si es necesario -->
</head>
<body>

<?php include '../header.php'?>
    <div class="container mt-4">
       
        <?php if ($mensaje): ?>
            <div class="alert alert-info"><?= htmlspecialchars($mensaje) ?></div>
        <?php endif; ?>

        <h3>Listado de Países</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Descripción</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($paises as $pais): ?>
                    <tr>
                        <td><?= htmlspecialchars($pais['nombre']) ?></td>
                        <td>
                            <!-- Botón para modificar -->
                            <button class="btn btn-warning" data-toggle="modal" data-target="#modalModificar" 
                                data-id="<?= $pais['id'] ?>" data-nombre="<?= htmlspecialchars($pais['nombre']) ?>">
                                Modificar
                            </button>
                            <!-- Botón para eliminar -->
                            <button class="btn btn-danger" data-toggle="modal" data-target="#modalEliminar" 
                                data-id="<?= $pais['id'] ?>" data-nombre="<?= htmlspecialchars($pais['nombre']) ?>">
                                Eliminar
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal para modificar país -->
    <div class="modal fade" id="modalModificar" tabindex="-1" role="dialog" aria-labelledby="modalModificarLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalModificarLabel">Modificar País</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post">
                        <input type="hidden" id="id_pais" name="id_pais">
                        <div class="form-group">
                            <label for="nombre">Descripción:</label>
                            <input type="text" id="nombre" name="nombre" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary" name="accion" value="modificar">Guardar cambios</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para eliminar país -->
    <div class="modal fade" id="modalEliminar" tabindex="-1" role="dialog" aria-labelledby="modalEliminarLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEliminarLabel">Eliminar País</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>¿Estás seguro de que deseas eliminar este país?</p>
                    <form method="post">
                        <input type="hidden" id="id_pais_eliminar" name="id_pais">
                        <button type="submit" class="btn btn-danger" name="accion" value="baja">Eliminar</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <h3>Alta de País</h3>
    <form method="post">
        <div class="form-group">
            <label for="descripcion">Descripción:</label>
            <input type="text" id="descripcion" name="descripcion" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success" name="accion" value="alta">Agregar País</button>
    </form>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        // Llenar el modal de modificación con los datos del país
        $('#modalModificar').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            var nombre = button.data('nombre');
            var modal = $(this);
            modal.find('.modal-body #id_pais').val(id);
            modal.find('.modal-body #nombre').val(nombre);
        });

        // Llenar el modal de eliminación con el ID del país
        $('#modalEliminar').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            var modal = $(this);
            modal.find('.modal-body #id_pais_eliminar').val(id);
        });
    </script>
</body>
</html>
