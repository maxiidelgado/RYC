<?php
require '../database/db.php'; // Incluir archivo de conexión a la base de datos
session_start();

// Verificar la sesión de usuario (implementación opcional)
// if (!isset($_SESSION['usuario_id'])) {
//     header('Location: ../login.php');
//     exit;
// }

// Operaciones CRUD
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['create'])) {
        // Crear entrada del menú
        $nombre = $_POST['nombre'];
        $url = $_POST['url'];
        $orden = $_POST['orden'];

        $stmt = $pdo->prepare("INSERT INTO Menu (nombre, url, orden) VALUES (?, ?, ?)");
        $stmt->execute([$nombre, $url, $orden]);
    } elseif (isset($_POST['update'])) {
        // Actualizar entrada del menú
        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $url = $_POST['url'];
        $orden = $_POST['orden'];

        $stmt = $pdo->prepare("UPDATE Menu SET nombre = ?, url = ?, orden = ? WHERE id = ?");
        $stmt->execute([$nombre, $url, $orden, $id]);
    } elseif (isset($_POST['delete'])) {
        // Eliminar entrada del menú
        $id = $_POST['id'];

        $stmt = $pdo->prepare("DELETE FROM Menu WHERE id = ?");
        $stmt->execute([$id]);
    }
}

// Obtener entradas del menú ordenadas por 'orden'
$query = $pdo->query("SELECT * FROM Menu ORDER BY orden");
$menu = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Menú</title>
    <link rel="stylesheet" href="../css/styles.css"> <!-- Estilo CSS para la tabla -->
</head>
<body>
    <?php include '../header.php'; ?> <!-- Incluir el encabezado -->
    <div class="container">
        <h2>Gestionar Menú</h2>
        <form method="post">
            <input type="hidden" name="id" id="menu-id">
            <input type="text" name="nombre" id="menu-nombre" placeholder="Nombre" required>
            <input type="text" name="url" id="menu-url" placeholder="URL" required>
            <input type="number" name="orden" id="menu-orden" placeholder="Orden" required>
            <button type="submit" name="create">Crear</button>
            <button type="submit" name="update">Actualizar</button>
            <button type="submit" name="delete">Eliminar</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>URL</th>
                    <th>Orden</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($menu as $item): ?>
                    <tr>
                        <td><?= $item['id'] ?></td>
                        <td><?= $item['nombre'] ?></td>
                        <td><?= $item['url'] ?></td>
                        <td><?= $item['orden'] ?></td>
                        <td>
                            <button onclick="editMenu(<?= $item['id'] ?>, '<?= $item['nombre'] ?>', '<?= $item['url'] ?>', <?= $item['orden'] ?>)">Editar</button>
                            <button onclick="deleteMenu(<?= $item['id'] ?>)">Eliminar</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Script JS -->
    <script src="../js/script.js"></script>
</body>
</html>
