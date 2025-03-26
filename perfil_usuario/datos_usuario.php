<?php
function obtenerDatosUsuario($usuario_id, $pdo) {
    try {
        $query = "SELECT u.username, p.nombre, p.apellido, p.fecha_nacimiento  
                  FROM Usuario u
                  JOIN Persona p ON u.persona_id = p.id
                  WHERE u.id = :usuario_id";

        $stmt = $pdo->prepare($query);

        $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);

        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            return null; // Si no se encuentran datos
        }
    } catch (PDOException $e) {
        // En caso de error en la consulta
        return ['error' => 'Error al obtener los datos: ' . $e->getMessage()];
    }
}

// Función para obtener el historial de pedidos de un usuario
function obtenerHistorialPedidos($usuario_id, $pdo) {
    try {
        // Consulta SQL para obtener el historial de pedidos
        $query = "SELECT id_pedido, direccion_entrega, fecha_pedido, estado, total
                  FROM pedido
                  WHERE rela_usuario = :usuario_id
                  ORDER BY fecha_pedido DESC LIMIT 4"; // Ordenar por fecha del pedido

        // Preparar la consulta
        $stmt = $pdo->prepare($query);

        // Vincular el parámetro de usuario_id
        $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);

        // Ejecutar la consulta
        $stmt->execute();

        // Verificar si el usuario tiene pedidos
        if ($stmt->rowCount() > 0) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return null; // Si no se encuentran pedidos
        }
    } catch (PDOException $e) {
        // En caso de error en la consulta
        return ['error' => 'Error al obtener el historial de pedidos: ' . $e->getMessage()];
    }
}

// Función para obtener el total de puntos de un usuario
function obtenerPuntosUsuario($usuario_id, $pdo) {
    try {
        // Consulta SQL para contar los puntos del usuario
        $query = "SELECT SUM(puntos) AS total_puntos
                  FROM puntos_usuario
                  WHERE rela_usuario = :usuario_id";

        // Preparar la consulta
        $stmt = $pdo->prepare($query);

        // Vincular el parámetro de usuario_id
        $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);

        // Ejecutar la consulta
        $stmt->execute();

        // Obtener el total de puntos
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado['total_puntos'] ?: 0; // Devolver 0 si no tiene puntos
    } catch (PDOException $e) {
        // En caso de error en la consulta
        return ['error' => 'Error al obtener los puntos: ' . $e->getMessage()];
    }
}


function obtenerHistorialPedidosCompleto($usuario_id, $pdo) {
    try {
        // Consulta SQL para obtener el historial de pedidos
        $query = "SELECT id_pedido, direccion_entrega, fecha_pedido, estado, total
                  FROM pedido
                  WHERE rela_usuario = :usuario_id
                  ORDER BY fecha_pedido DESC"; // Ordenar por fecha del pedido

        // Preparar la consulta
        $stmt = $pdo->prepare($query);

        // Vincular el parámetro de usuario_id
        $stmt->bindParam(':usuario_id', $usuario_id, PDO::PARAM_INT);

        // Ejecutar la consulta
        $stmt->execute();

        // Verificar si el usuario tiene pedidos
        if ($stmt->rowCount() > 0) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return null; // Si no se encuentran pedidos
        }
    } catch (PDOException $e) {
        // En caso de error en la consulta
        return ['error' => 'Error al obtener el historial de pedidos: ' . $e->getMessage()];
    }
}
?>
