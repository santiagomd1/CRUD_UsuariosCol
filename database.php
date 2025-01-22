<?php
$host = '127.0.0.1'; // Dirección del servidor MySQL
$db   = 'usuarios_col'; // Nombre de la base de datos
$user = 'root'; // Usuario por defecto de MySQL
$pass = ''; // Contraseña por defecto de MySQL
$charset = 'utf8mb4'; // Permite almacenar caracteres de 4 bytes en la base de datos

// Cadena de conexión para la base de datos con las anteriores variables
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
// Array para configurar el comportamiento de PHP Data Objects
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options); // Crea un nuevo apartado PDO para realizar la conexión con la base de datos
} catch (PDOException $e) { // Si ocurre una PDOException en catch, crea una nueva instancia de la misma y hace que esta sea reenviada correctamente
    throw new PDOException($e->getMessage(), (int)$e->getCode());
}
?>