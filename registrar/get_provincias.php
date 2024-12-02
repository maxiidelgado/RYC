<?php
require '../database/db.php';

// Obtener lista de países y tipos de documentos para llenar los select
$queryPaises = $pdo->query("SELECT id, nombre FROM Pais");
$paises = $queryPaises->fetchAll(PDO::FETCH_ASSOC);

$queryDocumentos = $pdo->query("SELECT id, descripcion FROM TipoDocumento");
$documentos = $queryDocumentos->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $pais_id = $_POST['pais'];
    $provincia_id = $_POST['provincia'];
    $tipo_documento_id = $_POST['tipo_documento'];
    $numero_documento = $_POST['numero_documento'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    try {
        // Verificar si el nombre de usuario ya existe
        $stmt = $pdo->prepare("SELECT COUNT(*) AS count FROM Usuario WHERE username = ?");
        $stmt->execute([$username]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row['count'] > 0) {
            $error = "El nombre de usuario ya está en uso.";
        } else {
            $pdo->beginTransaction();

            // Insertar datos en la tabla Persona
            $stmt = $pdo->prepare("INSERT INTO Persona (nombre, apellido, fecha_nacimiento, pais_id, provincia_id) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$nombre, $apellido, $fecha_nacimiento, $pais_id, $provincia_id]);
            $persona_id = $pdo->lastInsertId();

            // Insertar datos en la tabla Documento
            $stmt = $pdo->prepare("INSERT INTO Documento (persona_id, tipo_documento_id, numero) VALUES (?, ?, ?)");
            $stmt->execute([$persona_id, $tipo_documento_id, $numero_documento]);

            // Insertar datos en la tabla Usuario
            $stmt = $pdo->prepare("INSERT INTO Usuario (persona_id, username, password) VALUES (?, ?, ?)");
            $stmt->execute([$persona_id, $username, $password]);

            $pdo->commit();

            // Redireccionar al usuario a la página de inicio de sesión después del registro
            header('Location: ../index.php');
            exit;
        }
    } catch (Exception $e) {
        // Revertir la transacción en caso de error
        $pdo->rollBack();
        $error = "Error al registrar el usuario: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarse</title>
    <link rel="stylesheet" href="../css/styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <form method="post">
        <h2>Registrarse</h2>
        <?php if (isset($error)): ?>
            <p><?= $error ?></p>
        <?php endif; ?>
        <input type="text" name="nombre" placeholder="Nombre" required>
        <input type="text" name="apellido" placeholder="Apellido" required>
        <input type="date" name="fecha_nacimiento" placeholder="Fecha de nacimiento" required>

        <label for="pais">País:</label>
        <select name="pais" id="pais" required>
            <option value="">Selecciona un país</option>
            <?php foreach ($paises as $pais): ?>
                <option value="<?= $pais['id'] ?>"><?= $pais['nombre'] ?></option>
            <?php endforeach; ?>
        </select>

        <label for="provincia">Provincia:</label>
        <select name="provincia" id="provincia" required>
            <option value="">Selecciona un país primero</option>
        </select>

        <label for="tipo_documento">Tipo de Documento:</label>
        <select name="tipo_documento" id="tipo_documento" required>
            <?php foreach ($documentos as $documento): ?>
                <option value="<?= $documento['id'] ?>"><?= $documento['descripcion'] ?></option>
            <?php endforeach; ?>
        </select>

        <input type="text" name="numero_documento" placeholder="Número de documento" required>
        <input type="text" name="username" placeholder="Usuario" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        <button type="submit">Registrarse</button>
    </form>

    <script>
        $(document).ready(function() {
            $('#pais').on('change', function() {
                var paisId = $(this).val();
                if (paisId) {
                    $.ajax({
                        type: 'POST',
                        url: 'get_provincias.php',
                        data: { pais_id: paisId },
                        dataType: 'json',
                        success: function(data) {
                            $('#provincia').html('<option value="">Selecciona una provincia</option>');
                            $.each(data, function(key, value) {
                                $('#provincia').append('<option value="' + value.id + '">' + value.nombre + '</option>');
                            });
                        },
                        error: function(xhr, status, error) {
                            console.error('Error al obtener provincias: ' + error);
                        }
                    });
                } else {
                    $('#provincia').html('<option value="">Selecciona un país primero</option>');
                }
            });
        });
    </script>
</body>
</html>
