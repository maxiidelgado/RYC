<?php
require '../../database/db.php';

// Iniciar sesión si no está ya iniciada


// Verificar si el usuario tiene el perfil adecuado para gestionar banners
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../login.php');
    exit;
}

// Operaciones CRUD
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uploadDir = '../../uploads/banners/'; // Carpeta donde se guardarán las imágenes

    if (isset($_POST['create'])) {
        // Subir y guardar nuevo banner
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
            $fileName = basename($_FILES['imagen']['name']);
            $targetFilePath = $uploadDir . $fileName;
            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
            if (in_array($fileType, $allowedTypes)) {
                if (move_uploaded_file($_FILES['imagen']['tmp_name'], $targetFilePath)) {
                    $link_url = $_POST['link_url'];
                    $stmt = $pdo->prepare("INSERT INTO Banner (imagen_url, link_url) VALUES (?, ?)");
                    $stmt->execute([$targetFilePath, $link_url]);
                    echo "El banner se ha subido y guardado correctamente.";
                } else {
                    echo "Hubo un error al subir la imagen.";
                }
            } else {
                echo "Solo se permiten archivos JPG, JPEG, PNG, y GIF.";
            }
        }

    } elseif (isset($_POST['update'])) {
        // Actualizar banner existente
        $id = $_POST['id'];
        $link_url = $_POST['link_url'];

        // Si se sube una nueva imagen, actualizarla
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
            $fileName = basename($_FILES['imagen']['name']);
            $targetFilePath = $uploadDir . $fileName;
            $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
            if (in_array($fileType, $allowedTypes)) {
                if (move_uploaded_file($_FILES['imagen']['tmp_name'], $targetFilePath)) {
                    $stmt = $pdo->prepare("UPDATE Banner SET imagen_url = ?, link_url = ? WHERE id = ?");
                    $stmt->execute([$targetFilePath, $link_url, $id]);
                    echo "El banner se ha actualizado correctamente.";
                } else {
                    echo "Hubo un error al subir la imagen.";
                }
            } else {
                echo "Solo se permiten archivos JPG, JPEG, PNG, y GIF.";
            }
        } else {
            // Si no se sube una nueva imagen, solo actualizar el link
            $stmt = $pdo->prepare("UPDATE Banner SET link_url = ? WHERE id = ?");
            $stmt->execute([$link_url, $id]);
        }

    } elseif (isset($_POST['delete'])) {
        // Eliminar banner
        $id = $_POST['id'];

        $stmt = $pdo->prepare("DELETE FROM Banner WHERE id = ?");
        $stmt->execute([$id]);
    }
}

// Obtener banners
$query = $pdo->query("SELECT * FROM Banner");
$banners = $query->fetchAll(PDO::FETCH_ASSOC);
?>
