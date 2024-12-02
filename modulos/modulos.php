<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../database/db.php';

// Procesar el formulario de alta, baja o modificación de módulo
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['alta'])) {
        // Alta de módulo
        if (isset($_POST['nombre'], $_POST['descripcion'])) {
            $nombre = $_POST['nombre'];
            $descripcion = $_POST['descripcion'];
            try {
                $stmt = $pdo->prepare("INSERT INTO modulo (nombre, descripcion) VALUES (?, ?)");
                $stmt->execute([$nombre, $descripcion]);
                echo "Módulo agregado exitosamente.";
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        } else {
            echo "Error: Faltan datos para agregar el módulo.";
        }
    } elseif (isset($_POST['baja'])) {
        // Baja de módulo
        if (isset($_POST['id_modulo'])) {
            $id_modulo = $_POST['id_modulo'];
            try {
                $stmt = $pdo->prepare("DELETE FROM modulo WHERE id = ?");
                $stmt->execute([$id_modulo]);
                echo "Módulo eliminado exitosamente.";
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        } else {
            echo "Error: Faltan datos para eliminar el módulo.";
        }
    } elseif (isset($_POST['modificar'])) {
        // Modificación de módulo
        if (isset($_POST['id_modulo'], $_POST['nombre'], $_POST['descripcion'])) {
            $id_modulo = $_POST['id_modulo'];
            $nombre = $_POST['nombre'];
            $descripcion = $_POST['descripcion'];
            try {
                $stmt = $pdo->prepare("UPDATE modulo SET nombre = ?, descripcion = ? WHERE id = ?");
                $stmt->execute([$nombre, $descripcion, $id_modulo]);
                echo "Módulo modificado exitosamente.";
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        } else {
            echo "Error: Faltan datos para modificar el módulo.";
        }
    }
}

// Obtener listado de módulos
try {
    $stmt = $pdo->query("SELECT * FROM modulo");
    $modulos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>ABM Módulo</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <h2>ABM Módulo</h2>
    <h3>Listado de Módulos</h3>
    <table border="1">
        <tr>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Acciones</th>
        </tr>
        <?php foreach ($modulos as $modulo): ?>
            <tr>
                <td><?= htmlspecialchars($modulo['nombre']) ?></td>
                <td><?= htmlspecialchars($modulo['descripcion']) ?></td>
                <td>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="id_modulo" value="<?= $modulo['id'] ?>">
                        <input type="submit" name="modificar" value="Modificar">
                    </form>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="id_modulo" value="<?= $modulo['id'] ?>">
                        <input type="submit" name="baja" value="Eliminar">
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <h3>Alta de Módulo</h3>
    <form method="post">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required><br>
        <label for="descripcion">Descripción:</label>
        <input type="text" id="descripcion" name="descripcion" required><br>
        <input type="submit" name="alta" value="Agregar Módulo">
    </form>
</body>
</html>
