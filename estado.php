<?php
require 'database.php'; // Conexión a la base de datos

// Configurar la zona horaria de Bogotá para la fecha de últ. modificación
date_default_timezone_set('America/Bogota');
// Captura los datos JSON enviados por medio de HTTP y los convierte en un array contenido en la variable '$data'
$data = json_decode(file_get_contents('php://input'), true);

// Condicional para la verificación de variables
if (isset($data['usuario']) && isset($data['estado'])) {
    $usuario = $data['usuario']; // Usuario relacionado
    $estado = $data['estado']; // Estado asociado
    $fecha_ult_mod = date("Y-m-d H:i:s"); // Fecha y hora actual para cambiar la fecha de la última modificación

    // Actualización de los datos en la base de datos
    $query = $pdo->prepare("UPDATE usuarios SET estado_us = :estado_us, fecha_ult_mod = :fecha_ult_mod WHERE ID_us = :ID_us");
    $query->execute(['estado_us' => $estado, 'fecha_ult_mod' => $fecha_ult_mod, 'ID_us' => $usuario]);
    
    // Condicional de acuerdo a la ejecución del query
    if ($query->rowCount() > 0) {
        echo json_encode(['success' => true, 'refresh' => true]); // Ejecución del cambio y recarga de la página
    } else {
        echo json_encode(['success' => false, 'message' => 'No se pudo actualizar el estado']); // Mensaje de error en caso de fallo en el query
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Datos inválidos']); // Mensaje de error si alguna o las dos variables no fueron encontradas
}
?>

