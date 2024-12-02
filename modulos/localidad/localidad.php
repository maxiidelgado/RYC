<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../../database/db.php'; // Archivo de conexión a la base de datos

// Procesar el formulario de alta, baja o modificación de localidad
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['alta'])) {
        // Alta de localidad
        if (isset($_POST['descripcion'], $_POST['rela_provincia'])) {
            $descripcion = $_POST['descripcion'];
            $rela_provincia = $_POST['rela_provincia'];
            try {
                $stmt = $pdo->prepare("INSERT INTO Localidad (nombre, provincia_id) VALUES (?, ?)");
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
                $stmt = $pdo->prepare("DELETE FROM Localidad WHERE id = ?");
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
                $stmt = $pdo->prepare("UPDATE Localidad SET nombre = ?, provincia_id = ? WHERE id = ?");
                $stmt->execute([$descripcion, $rela_provincia, $id_localidad]);
                echo "Localidad modificada exitosamente.";
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        } else {
            echo "Error: Faltan datos para modificar la localidad.";
        }
    }
}

// Obtener el listado de localidades
function obtenerLocalidades($pdo) {
    try {
        $stmt = $pdo->query("SELECT l.*, p.nombre AS nombre_provincia FROM Localidad l 
                             JOIN Provincia p ON l.provincia_id = p.id");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return [];
    }
}

// Obtener el listado de provincias
function obtenerProvincias($pdo) {
    try {
        $stmt = $pdo->query("SELECT * FROM Provincia");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return [];
    }
}
?>
