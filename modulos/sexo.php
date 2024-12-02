<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../database/db.php';

// Procesar el formulario de alta, baja o modificación de sexo
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['alta'])) {
        // Alta de sexo
        if (isset($_POST['descripcion'])) {
            $descripcion = $_POST['descripcion'];
            try {
                $stmt = $pdo->prepare("INSERT INTO sexo (descripcion, activo) VALUES (?, 1)");
                $stmt->execute([$descripcion]);
                echo "Sexo agregado exitosamente.";
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        } else {
            echo "Error: Faltan datos para agregar el sexo.";
        }
    } elseif (isset($_POST['baja'])) {
        // Baja de sexo
        if (isset($_POST['id_sexo'])) {
            $id_sexo = $_POST['id_sexo'];
            try {
                $stmt = $pdo->prepare("DELETE FROM sexo WHERE id_sexo = ?");
                $stmt->execute([$id_sexo]);
                echo "Sexo eliminado exitosamente.";
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        } else {
            echo "Error: Faltan datos para eliminar el sexo.";
        }
    } elseif (isset($_POST['modificar'])) {
        // Modificación de sexo
        if (isset($_POST['id_sexo'], $_POST['descripcion'])) {
            $id_sexo = $_POST['id_sexo'];
            $descripcion = $_POST['descripcion'];
            try {
                $stmt = $pdo->prepare("UPDATE sexo SET descripcion = ? WHERE id_sexo = ?");
                $stmt->execute([$descripcion, $id_sexo]);
                echo "Sexo modificado exitosamente.";
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        } else {
            echo "Error: Faltan datos para modificar el sexo.";
        }
    }
}

// Obtener listado de sexos
try {
    $stmt = $pdo->query("SELECT * FROM sexo");
    $sexos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>ABM Sexo</title>
</head>
<body>
    <h2>ABM Sexo</h2>
    <h3>Listado de Sexos</h3>
    <table border="1">
        <tr>
            <th>Descripción</th>
            <th>Acciones</th>
        </tr>
        <?php foreach ($sexos as $sexo): ?>
            <tr>
                <td><?= htmlspecialchars($sexo['descripcion']) ?></td>
                <td>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="id_sexo" value="<?= $sexo['id_sexo'] ?>">
                        <input type="submit" name="modificar" value="Modificar">
                    </form>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="id_sexo" value="<?= $sexo['id_sexo'] ?>">
                        <input type="submit" name="baja" value="Eliminar">
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <h3>Alta de Sexo</h3>
    <form method="post">
        <label for="descripcion">Descripción:</label>
        <input type="text" id="descripcion" name="descripcion" required><br>
        <input type="submit" name="alta" value="Agregar Sexo">
    </form>
</body>
</html>
