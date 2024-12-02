<?php
require_once 'localidad.php'; // Incluir la lógica del backend

$localidades = obtenerLocalidades($pdo);
$provincias = obtenerProvincias($pdo);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ABM Localidad</title>
    <link rel="stylesheet" href="../css/styles.css"> <!-- Estilo CSS para la tabla -->
    <script src = "localidad.js"></script>
</head>
<body>
    <h2>ABM Localidad</h2>

    <h3>Listado de Localidades</h3>
    <table border="1">
        <tr>
            <th>Descripción</th>
            <th>Provincia</th>
            <th>Acciones</th>
        </tr>
        <?php foreach ($localidades as $localidad): ?>
            <tr>
                <td><?= htmlspecialchars($localidad['nombre']) ?></td>
                <td><?= htmlspecialchars($localidad['nombre_provincia']) ?></td>
                <td>
                    <!-- Botón de Modificar que llama a la función JavaScript para mostrar el formulario -->
                    <button type="button" onclick="mostrarFormularioModificacion('<?= $localidad['id'] ?>', '<?= htmlspecialchars($localidad['nombre']) ?>', '<?= $localidad['provincia_id'] ?>')">Modificar</button>

                    <!-- Formulario de baja -->
                    <form method="post" action="localidad.php" style="display:inline;">
                        <input type="hidden" name="id_localidad" value="<?= $localidad['id'] ?>">
                        <input type="submit" name="baja" value="Eliminar">
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <h3>Alta de Localidad</h3>
    <form method="post" action="localidad.php">
        <label for="descripcion">Descripción:</label>
        <input type="text" id="descripcion" name="descripcion" required><br>
        <label for="rela_provincia">Provincia:</label>
        <select id="rela_provincia" name="rela_provincia" required>
            <?php foreach ($provincias as $provincia): ?>
                <option value="<?= $provincia['id'] ?>"><?= htmlspecialchars($provincia['nombre']) ?></option>
            <?php endforeach; ?>
        </select><br>
        <input type="submit" name="alta" value="Agregar Localidad">
    </form>

    <!-- Formulario de Modificación (Oculto por defecto) -->
    <h3>Modificar Localidad</h3>
    <form id="formulario-modificar" method="post" action="localidad.php" style="display:none;">
        <input type="hidden" id="id_localidad_modificar" name="id_localidad" value="">
        <label for="descripcion_modificar">Descripción:</label>
        <input type="text" id="descripcion_modificar" name="descripcion" required><br>
        <label for="rela_provincia_modificar">Provincia:</label>
        <select id="rela_provincia_modificar" name="rela_provincia" required>
            <?php foreach ($provincias as $provincia): ?>
                <option value="<?= $provincia['id'] ?>"><?= htmlspecialchars($provincia['nombre']) ?></option>
            <?php endforeach; ?>
        </select><br>
        <input type="submit" name="modificar" value="Modificar Localidad">
        <button type="button" onclick="ocultarFormularioModificacion()">Cancelar</button>
    </form>

</body>
</html>
