<?php
session_start();
require 'database.php'; // Conexión a la base de datos

// Configurar zona horaria de Bogotá para la fecha de registro y fecha de últ. modificación
date_default_timezone_set('America/Bogota');

if ($_SERVER['REQUEST_METHOD'] == 'POST') { // Envío de formulario
    $nombres = $_POST['nombres'];
    $apellidos = $_POST['apellidos'];
    $edad = $_POST['edad'];
    $telefono = $_POST['telefono'];
    $email = $_POST['email'];
    $dpto_resid = $_POST['dpto_resid'];

    // Verificar si el correo electrónico ya existe por medio de una consulta a la base de datos
    $query = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE email = ?");
    $query->execute([$email]);
    $emailExists = $query->fetchColumn();

    // Condicional ejecutado cuando el email ingresado ya está alojado en la base de datos
    if ($emailExists) {
        // Mostrar mensaje de error si el correo ya existe y retorno a la página actual de Creación de usuario
        echo "<script>alert('El correo electrónico ya está registrado. Por favor digite uno distinto.'); window.history.back();</script>";
    } else {
        $fecha_reg = date("Y-m-d H:i:s"); // Fecha de registro en formato año/mes/día horas:minutos:segundos
        $fecha_ult_mod = date("Y-m-d H:i:s"); // Fecha de última modificación en formato año/mes/día horas:minutos:segundos

        // Insertar el usuario en la base de datos con sus respectivos campos
        $query = $pdo->prepare("INSERT INTO usuarios (nombres, apellidos, edad, telefono, email, fecha_reg, fecha_ult_mod, dpto_resid, estado_us) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Activo')");
        $query->execute([$nombres, $apellidos, $edad, $telefono, $email, $fecha_reg, $fecha_ult_mod, $dpto_resid]);
        $usuario = $pdo->lastInsertId();

        // Mostrar un mensaje de creación y redirigir al página de Listado y Gestión de Usuarios
        echo "<script>alert('Usuario creado exitosamente.'); window.location.href='ver_us.php';</script>";
        exit();
    }
}

// Consulta para obtener la lista de departamentos de la bd
$departamentos_query = $pdo->query("SELECT ID_dpto, nombre_dpto FROM departamentos_col");
$departamentos_col = $departamentos_query->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Usuario</title> <!-- Nombre de la página -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet"> <!-- Recurso de Bootstrapp (CSS) -->
    <style>
        .boton-menu {
            /* Estilo del botón que retorna al menú principal */
            position: absolute;
            top: 10px;
            right: 20px;
            padding: 10px 20px;
            background-color: #2ECC71;
            color: white;
            border: none;
        }
    </style>
</head>

<body>
    <!-- Botón Menú principal con script 'retorno' de confirmación de salida -->
    <button type="button" class="boton-menu" onclick="retorno()">Menú <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-house-door" viewBox="0 0 16 16">
            <path d="M8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4.5a.5.5 0 0 0 .5-.5v-4h2v4a.5.5 0 0 0 .5.5H14a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293zM2.5 14V7.707l5.5-5.5 5.5 5.5V14H10v-4a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5v4z" />
        </svg>
    </button>
    <!-- Div principal -->
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header text-center">
                        <h2>Creación de Nuevo Usuario <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="bi bi-person-plus-fill" viewBox="0 0 16 16">
                                <path d="M1 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
                                <path fill-rule="evenodd" d="M13.5 5a.5.5 0 0 1 .5.5V7h1.5a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0V8h-1.5a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5" /></svg>
                        </h2>
                    </div>
                    <!-- Ingreso y selección en los campos para crear el nuevo usuario -->
                    <div class="card-body text-center">
                        <form action="crear_us.php" method="post">
                            <label>Diligencie los siguientes campos:</label> <br>
                            <!-- Nombres del usuario -->
                            <div class="form-group">
                                <label for="nombres">Nombres</label>
                                <input type="text" class="form-control" id="nombres" name="nombres" required>
                            </div>
                            <!-- Apellidos del usuario -->
                            <div class="form-group">
                                <label for="apellidos">Apellidos</label>
                                <input type="text" class="form-control" id="apellidos" name="apellidos" required>
                            </div>
                            <!-- Edad del usuario -->
                            <div class="form-group">
                                <label for="edad">Edad</label>
                                <input type="number" class="form-control" id="edad" name="edad" required>
                            </div>
                            <!-- Teléfono del usuario -->
                            <div class="form-group">
                                <label for="telefono">Teléfono</label>
                                <input type="number" class="form-control" id="telefono" name="telefono" required>
                            </div>
                            <!-- Email del usuario -->
                            <div class="form-group">
                                <label for="email">Correo electrónico</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <!-- Selección del Departamento en el cual reside el usuario -->
                            <div class="form-group">
                                <label for="dpto_resid">Departamento de Residencia</label>
                                <select class="form-control" id="dpto_resid" name="dpto_resid" required>
                                    <!-- Bucle para muestra de cada departamentos_col (consulta previa) -->
                                    <?php foreach ($departamentos_col as $departamento) : ?>
                                        <option value="<?= $departamento['ID_dpto'] ?>"><?= htmlspecialchars($departamento['nombre_dpto']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <!-- Envío de los respectivos datos del usuario -->
                            <button type="submit" class="btn btn-success">Crear Usuario</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Script del 'boton-menu' -->
    <script>
        function retorno(event) {
            // Condicional para la confirmación de la salida
            if (confirm("¿Desea retornar al menú inicial? No se realizará la creación del usuario.")) {
                window.location.href = "index.php"; // Redigir al usuario al menú principal
            } else {
                // Se mantendrá en la página actual con los datos diligenciados
            }
        }
    </script>
    <!-- Inclusión y ejecución de código de la biblioteca jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- Adición y ejecución de código de la biblioteca JavaScript de BootStrap -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>