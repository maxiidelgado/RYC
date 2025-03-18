<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú Principal</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <?php include '../header.php'; ?>
    
    <div class="container">
        <div class="menu">
            <h2>Menú de Gestión</h2>
            <div class="menu-items">

                <div class="menu-item">
                    <a href="tipo_contacto.php">Gestión de Tipo de Contacto</a>
                </div>
                <div class="menu-item">
                    <a href="clientes/gestionar_clientes.php">Gestion de Clientes</a>
                </div>
                <div class="menu-item">
                    <a href="usuarios/vista_usuario.php">Gestión de Usuario</a>
                </div>
                <div class="menu-item">
                    <a href="provincia.php">Provincia</a>
                </div>
                <div class="menu-item">
                    <a href="pais.php">País</a>
                </div>
                <div class="menu-item">
                    <a href="localidad.php">Localidad</a>
                </div>
                <div class="menu-item">
                    <a href="tipo_documento.php">Tipo de Documento</a>
                </div>
                <div class="menu-item">
                    <a href="AsignarPerfil/vista_usuarios_perfiles.php">Asignar Perfil</a>
                </div>
                <div class="menu-item">
                    <a href="perfil/perfil_vista.php">Gestion De Perfil</a>
                </div>
                <div class="menu-item">
                    <a href="GestionModulos/vista_modulos.php">Gestión de Módulos</a>
                </div>
            </div>
        </div>
    </div>

<footer>
    <div class="container text-center">
        <p>&copy; <?php echo date('Y'); ?> Mi Sistema de Delivery</p>
    </div>
</footer>
</body>
</html>
