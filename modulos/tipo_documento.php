<?php
require_once '../database/db.php';

// Definir cantidad de registros por página
$registros_por_pagina = 5; // Puedes modificar este valor
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$offset = ($pagina - 1) * $registros_por_pagina;

// Manejo del formulario de alta, baja o modificación de tipo de documento
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['alta'])) {
        // Alta de tipo de documento
        if (isset($_POST['descripcion'])) {
            $descripcion = $_POST['descripcion'];
            try {
                $stmt = $pdo->prepare("INSERT INTO TipoDocumento (descripcion) VALUES (?)");
                $stmt->execute([$descripcion]);
                echo "Tipo de documento agregado exitosamente.";
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        } else {
            echo "Error: Faltan datos para agregar el tipo de documento.";
        }
    } elseif (isset($_POST['baja'])) {
        // Baja de tipo de documento
        if (isset($_POST['id_tipo_documento'])) {
            $id_tipo_documento = $_POST['id_tipo_documento'];
            try {
                $stmt = $pdo->prepare("DELETE FROM TipoDocumento WHERE id = ?");
                $stmt->execute([$id_tipo_documento]);
                echo "Tipo de documento eliminado exitosamente.";
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        } else {
            echo "Error: Faltan datos para eliminar el tipo de documento.";
        }
    } elseif (isset($_POST['modificar'])) {
        // Modificación de tipo de documento
        if (isset($_POST['id_tipo_documento'], $_POST['descripcion'])) {
            $id_tipo_documento = $_POST['id_tipo_documento'];
            $descripcion = $_POST['descripcion'];
            try {
                $stmt = $pdo->prepare("UPDATE TipoDocumento SET descripcion = ? WHERE id = ?");
                $stmt->execute([$descripcion, $id_tipo_documento]);
                echo "Tipo de documento modificado exitosamente.";
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        } else {
            echo "Error: Faltan datos para modificar el tipo de documento.";
        }
    }
}

// Obtener el total de tipos de documento para la paginación
try {
    $stmt = $pdo->query("SELECT COUNT(*) FROM TipoDocumento");
    $total_registros = $stmt->fetchColumn();
    $total_paginas = ceil($total_registros / $registros_por_pagina);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Obtener listado de tipos de documento con paginación
try {
    $stmt = $pdo->prepare("SELECT * FROM TipoDocumento LIMIT :limite OFFSET :offset");
    $stmt->bindParam(':limite', $registros_por_pagina, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $tipos_documentos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ABM Tipo de Documento</title>
    <!-- Incluir el CSS de Bootstrap -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<?php include ('../header.php')?>
<body class="bg-light text-dark">
    <div class="container mt-5">
        <h2 class="text-center">ABM Tipo de Documento</h2>
        <h3 class="mt-4">Listado de Tipos de Documento</h3>
        
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Descripción</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tipos_documentos as $tipo_documento): ?>
                    <tr>
                        <td><?= htmlspecialchars($tipo_documento['descripcion']) ?></td>
                        <td>
                            <!-- Botón de modificación -->
                            <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#modalModificar" 
                                data-id="<?= $tipo_documento['id'] ?>" 
                                data-descripcion="<?= htmlspecialchars($tipo_documento['descripcion']) ?>">
                                Modificar
                            </button>
                            
                            <!-- Botón de eliminación -->
                            <button class="btn btn-danger btn-sm" data-toggle="modal" data-target="#modalBaja" 
                                data-id="<?= $tipo_documento['id'] ?>">
                                Eliminar
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Enlaces de Paginación -->
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <li class="page-item <?= $pagina == 1 ? 'disabled' : '' ?>">
                    <a class="page-link" href="?pagina=<?= $pagina - 1 ?>">Anterior</a>
                </li>
                <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                    <li class="page-item <?= $i == $pagina ? 'active' : '' ?>">
                        <a class="page-link" href="?pagina=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
                <li class="page-item <?= $pagina == $total_paginas ? 'disabled' : '' ?>">
                    <a class="page-link" href="?pagina=<?= $pagina + 1 ?>">Siguiente</a>
                </li>
            </ul>
        </nav>

        <h3 class="mt-5">Alta de Tipo de Documento</h3>
        <form method="post">
            <div class="form-group">
                <label for="descripcion">Descripción:</label>
                <input type="text" id="descripcion" name="descripcion" class="form-control" required>
            </div>
            <button type="submit" name="alta" class="btn btn-success">Agregar Tipo de Documento</button>
        </form>
    </div>

    <!-- Modal para modificar -->
    <div class="modal fade" id="modalModificar" tabindex="-1" role="dialog" aria-labelledby="modalModificarLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalModificarLabel">Modificar Tipo de Documento</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post">
                        <input type="hidden" id="id_tipo_documento_modificar" name="id_tipo_documento">
                        <div class="form-group">
                            <label for="descripcion_modificar">Descripción:</label>
                            <input type="text" id="descripcion_modificar" name="descripcion" class="form-control" required>
                        </div>
                        <button type="submit" name="modificar" class="btn btn-warning">Modificar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para confirmar baja -->
    <div class="modal fade" id="modalBaja" tabindex="-1" role="dialog" aria-labelledby="modalBajaLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalBajaLabel">Confirmar Eliminación</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    ¿Está seguro de que desea eliminar este tipo de documento?
                </div>
                <div class="modal-footer">
                    <form method="post">
                        <input type="hidden" id="id_tipo_documento_baja" name="id_tipo_documento">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" name="baja" class="btn btn-danger">Eliminar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Incluir los scripts de Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        // Script para pasar los datos al modal de modificación
        $('#modalModificar').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            var descripcion = button.data('descripcion');
            var modal = $(this);
            modal.find('#id_tipo_documento_modificar').val(id);
            modal.find('#descripcion_modificar').val(descripcion);
        });

        // Script para pasar el ID al modal de baja
        $('#modalBaja').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var id = button.data('id');
            var modal = $(this);
            modal.find('#id_tipo_documento_baja').val(id);
        });
    </script>
</body>
</html>
