<?php
require '../database/db.php';
require '../mail/Mailer/Exception.php';  // Asegúrate de que la ruta sea correcta
require '../mail/Mailer/PHPMailer.php';  // Asegúrate de que la ruta sea correcta
require '../mail/Mailer/SMTP.php';       // Asegúrate de que la ruta sea correcta

// Importar las clases de PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Crear una instancia de PHPMailer
$mail = new PHPMailer(true);

// Obtener lista de países, provincias y tipos de documentos para llenar los select
$queryPaises = $pdo->query("SELECT id, nombre FROM Pais");
$paises = $queryPaises->fetchAll(PDO::FETCH_ASSOC);

$queryProvincias = $pdo->query("SELECT id, nombre FROM Provincia");
$provincias = $queryProvincias->fetchAll(PDO::FETCH_ASSOC);

$queryDocumentos = $pdo->query("SELECT id, descripcion FROM TipoDocumento");
$documentos = $queryDocumentos->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Capturar los datos enviados por el formulario
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $pais_id = $_POST['pais'];
    $provincia_id = $_POST['provincia'];
    $tipo_documento_id = $_POST['tipo_documento'];
    $numero_documento = $_POST['numero_documento'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $correo = $_POST['correo']; // Capturar el correo

    // Generar un código aleatorio para la verificación
    $codigo_verificacion = bin2hex(random_bytes(5)); // Genera un código de 10 caracteres

      // Validar que el usuario tenga al menos 16 años
      $fechaNacimiento = new DateTime($fecha_nacimiento);
      $hoy = new DateTime();
      $diferencia = $hoy->diff($fechaNacimiento);
  
      if ($diferencia->y < 16) {
          $error = "Debes tener al menos 16 años para registrarte.";
      } else {
          // Resto del procesamiento del formulario
          try {
              // Código para insertar los datos
          } catch (Exception $e) {
              $pdo->rollBack();
              $error = "Error al registrar el usuario: " . $e->getMessage();
          }
      }

    try {
        // Verificar si el nombre de usuario o el correo ya existen
        $stmt = $pdo->prepare("SELECT COUNT(*) AS count FROM Usuario WHERE username = ? OR correo = ?");
        $stmt->execute([$username, $correo]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row['count'] > 0) {
            $error = "El nombre de usuario o el correo ya están en uso.";
        } else {
            $pdo->beginTransaction();

            // Insertar datos en la tabla Persona
            $stmt = $pdo->prepare("INSERT INTO Persona (nombre, apellido, fecha_nacimiento, pais_id, provincia_id) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$nombre, $apellido, $fecha_nacimiento, $pais_id, $provincia_id]);
            $persona_id = $pdo->lastInsertId();

            // Insertar datos en la tabla Documento
            $stmt = $pdo->prepare("INSERT INTO Documento (persona_id, tipo_documento_id, numero) VALUES (?, ?, ?)");
            $stmt->execute([$persona_id, $tipo_documento_id, $numero_documento]);

            // Insertar datos en la tabla Usuario (incluyendo el código de verificación)
            $stmt = $pdo->prepare("INSERT INTO Usuario (persona_id, username, password, correo, codigo_verificacion, rela_perfil) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$persona_id, $username, $password, $correo, $codigo_verificacion, 2]);


            // Asignar el perfil de usuario normal (perfil_id = 2)
            $perfil_id = 2;
            $stmt = $pdo->prepare("INSERT INTO usuarioperfil (usuario_id, perfil_id) VALUES (?, ?)");
            $stmt->execute([$pdo->lastInsertId(), $perfil_id]);

            $pdo->commit();

            // Enviar correo de verificación
            $mail->SMTPDebug = SMTP::DEBUG_OFF; // Cambia a DEBUG_SERVER si necesitas depuración
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com'; // Cambia esto por tu servidor SMTP
            $mail->SMTPAuth   = true;
            $mail->Username   = 'maxiidelgado02@gmail.com'; // Cambia esto por tu correo
            $mail->Password   = 'koagovyzufxrsmse'; // Cambia esto por tu contraseña
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // o PHPMailer::ENCRYPTION_STARTTLS
            $mail->Port       = 465; // o 587

            // Destinatarios
            $mail->setFrom('maxiidelgado02@gmail.com', 'Ricos Y Caseros'); // Cambia esto por tu nombre
            $mail->addAddress($correo); // Agregar el correo del usuario

            // Contenido
            $mail->isHTML(true);                                  // Configurar el formato del correo a HTML
            $mail->Subject = 'Verificación de Correo';
            $mail->Body    = "Hola $nombre, <br> Gracias por registrarte. Tu código de verificación es: <strong>$codigo_verificacion</strong>.";
            $mail->AltBody = "Hola $nombre, Gracias por registrarte. Tu código de verificación es: $codigo_verificacion."; // Para clientes que no soportan HTML

            // Enviar correo
            if (!$mail->send()) {
                echo "Error al enviar el correo de verificación: " . $mail->ErrorInfo;
            } else {
                header('Location: verificar.php?correo=' . urlencode($correo)); // Asegúrate de incluir el correo como parámetro
                exit;
            }
        }
    } catch (Exception $e) {
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
    <link rel="stylesheet" href="styles.css">
    <script src="validaciones.js" defer></script> <!-- Incluir el archivo de validaciones -->
</head>
<body>
    
    <section class="container">
        <header>Formulario de Registro</header>
        <form id="registro-form" class="form" method="post">
            <div class="error-message" id="error-message" style="display: none;"><?php echo isset($error) ? $error : ''; ?></div> <!-- Contenedor para los mensajes de error -->
            
            <!-- Resto del formulario aquí -->

            <div class="input-box">
                <label>Nombre</label>
                <input type="text" name="nombre" placeholder="Ingresa tu nombre" required>
            </div>
            <div class="input-box">
                <label>Apellido</label>
                <input type="text" name="apellido" placeholder="Ingresa tu apellido" required>
            </div>
            <div class="column">
                <div class="input-box">
                    <label>Fecha de Nacimiento</label>
                    <input type="date" name="fecha_nacimiento" required>
                </div>
                <div class="input-box">
                    <label>País</label>
                    <select name="pais" required>
                        <?php foreach ($paises as $pais): ?>
                            <option value="<?= $pais['id'] ?>"><?= $pais['nombre'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="input-box">
                <label>Provincia</label>
                <select name="provincia" required>
                    <?php foreach ($provincias as $provincia): ?>
                        <option value="<?= $provincia['id'] ?>"><?= $provincia['nombre'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="input-box">
                <label>Tipo de Documento</label>
                <select name="tipo_documento" required>
                    <?php foreach ($documentos as $documento): ?>
                        <option value="<?= $documento['id'] ?>"><?= $documento['descripcion'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="input-box">
                <label>Número de Documento</label>
                <input type="text" name="numero_documento" placeholder="Ingresa el número de documento" required>
            </div>
            <div class="input-box">
                <label>Nombre de Usuario</label>
                <input type="text" name="username" placeholder="Ingresa tu nombre de usuario" required>
            </div>
            <div class="input-box">
                <label>Contraseña</label>
                <input type="password" name="password" placeholder="Ingresa tu contraseña" required>
            </div>
            <div class="input-box">
                <label>Correo Electrónico</label>
                <input type="email" name="correo" placeholder="Ingresa tu correo electrónico" required>
            </div>
            <button type="submit" class="btn">Registrarse</button>
        </form>
    </section>
</body>
</html>
