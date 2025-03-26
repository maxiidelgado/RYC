<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú Principal</title>
    <!-- Incluir Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Incluir FontAwesome para los iconos -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/styles.css"> <!-- Asegúrate de tener este archivo -->
</head>
<body>
    <?php include '../header.php'; ?>
    
    <div class="container mt-5">
        <div class="menu">
            <h2 class="text-center" style="color: #E9967A;">Menú de Gestión</h2>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="menu-item card shadow-sm">
                        <div class="card-body">
                            <a href="tipo_contacto.php" class="text-decoration-none">
                                <i class="fas fa-phone-alt fa-2x"></i> <br>Gestión de Tipo de Contacto
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="menu-item card shadow-sm">
                        <div class="card-body">
                            <a href="clientes/gestionar_clientes.php" class="text-decoration-none">
                                <i class="fas fa-users fa-2x"></i> <br>Gestión de Clientes
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="menu-item card shadow-sm">
                        <div class="card-body">
                            <a href="usuarios/vista_usuario.php" class="text-decoration-none">
                                <i class="fas fa-user-cog fa-2x"></i> <br>Gestión de Usuario
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="menu-item card shadow-sm">
                        <div class="card-body">
                            <a href="provincia.php" class="text-decoration-none">
                                <i class="fas fa-map-marked-alt fa-2x"></i> <br>Provincia
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="menu-item card shadow-sm">
                        <div class="card-body">
                            <a href="pais.php" class="text-decoration-none">
                                <i class="fas fa-flag fa-2x"></i> <br>País
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="menu-item card shadow-sm">
                        <div class="card-body">
                            <a href="localidad.php" class="text-decoration-none">
                                <i class="fas fa-location-arrow fa-2x"></i> <br>Localidad
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="menu-item card shadow-sm">
                        <div class="card-body">
                            <a href="tipo_documento.php" class="text-decoration-none">
                                <i class="fas fa-id-card fa-2x"></i> <br>Tipo de Documento
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="menu-item card shadow-sm">
                        <div class="card-body">
                            <a href="AsignarPerfil/vista_usuarios_perfiles.php" class="text-decoration-none">
                                <i class="fas fa-user-tag fa-2x"></i> <br>Asignar Perfil
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="menu-item card shadow-sm">
                        <div class="card-body">
                            <a href="perfil/perfil_vista.php" class="text-decoration-none">
                                <i class="fas fa-user fa-2x"></i> <br>Gestión de Perfil
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="menu-item card shadow-sm">
                        <div class="card-body">
                            <a href="GestionModulos/vista_modulos.php" class="text-decoration-none">
                                <i class="fas fa-cogs fa-2x"></i> <br>Gestión de Módulos
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer >
        <div class="container text-center">
            <p>&copy; <?php echo date('Y'); ?> Mi Sistema de Delivery</p>
        </div>
    </footer>

    <!-- Incluir Bootstrap JS y dependencias -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.11/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
