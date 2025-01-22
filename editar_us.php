<?php
session_start();
require 'database.php'; // Conexión a la base de datos

// Configurar zona horaria de Bogotá para la fecha de últ. modificación
date_default_timezone_set('America/Bogota');

// Consulta para obtener todos los usuarios junto con los departamentos de colombia
$query = $pdo->prepare("
    SELECT usuarios.*, usuarios.ID_us AS id_us, departamentos_col.nombre_dpto AS nombre_dpto, departamentos_col.ID_dpto AS id_dpto
    FROM usuarios
    JOIN departamentos_col ON usuarios.dpto_resid = departamentos_col.ID_dpto
");
$query->execute();
$usuarios = $query->fetchAll();

if (isset($_GET['ID_us']) && !empty($_GET['ID_us'])) { // Verificar si el ID_us está presente y no está vacío
    $usuario_id = $_GET['ID_us'];

    // Consulta para obtener los detalles del respectivo usuario
    $query = $pdo->prepare("SELECT * FROM usuarios WHERE ID_us = ?");
    $query->execute([$usuario_id]);
    $usuario = $query->fetch();

    if (!$usuario) { // Mensaje de error y terminación del proceso
        echo "Usuario no encontrado.";
        exit();
    }
} else {
    echo "El ID de usuario no fue dado."; // Mensaje en caso de que no posea un id asociado
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') { // Envío de formulario
    $nombres = $_POST['nombres'];
    $apellidos = $_POST['apellidos'];
    $edad = $_POST['edad'];
    $telefono = $_POST['telefono'];
    $email = $_POST['email'];
    $fecha_reg = $usuario['fecha_reg']; // Usar la fecha de registro de la base de datos
    $fecha_ult_mod = date('Y-m-d H:i:s'); // Cambiar el formato de fecha últ. mod.
    $dpto_resid = $_POST['dpto_resid'];
    $estado_us = 'Activo'; // Estado Activo predeterminado

    // Verificar si el correo electrónico ya existe en la base de datos 
    $check_email_query = $pdo->prepare("SELECT COUNT(*) FROM usuarios WHERE email = ? AND ID_us != ?");
    $check_email_query->execute([$email, $usuario['ID_us']]);
    $email_count = $check_email_query->fetchColumn();

    if ($email_count > 0) {
        // Mostrar mensaje de error si el correo ya existe y retorno a la página actual de Edición de usuario
        echo "<script>alert('El correo electrónico ya está en uso. Por favor digite uno distinto.'); window.history.back();</script>";
        exit();
    } else { // Actualizar los detalles del usuario en la base de datos 
        $query = $pdo->prepare("UPDATE usuarios SET nombres = ?, apellidos = ?, edad = ?, telefono = ?, email = ?, fecha_reg = ?, fecha_ult_mod = ?, dpto_resid = ?, estado_us = ? WHERE ID_us = ?");
        $query->execute([$nombres, $apellidos, $edad, $telefono, $email, $fecha_reg, $fecha_ult_mod, $dpto_resid, $estado_us, $usuario['ID_us']]);

        // Mostrar ventana emergente de éxito 
        echo "<script>alert('Usuario modificado correctamente.'); window.location.href = 'ver_us.php';</script>";
        exit();
    }
}
// Consulta para obtener la lista de departamentos en la base de datos
$departamentos_query = $pdo->query("SELECT ID_dpto, nombre_dpto FROM departamentos_col");
$departamentos_col = $departamentos_query->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuarios</title> <!-- Nombre de la página -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet"> <!-- Recurso de Bootstrapp (CSS) -->
    <style>
        .boton-us {
            /* Estilo del botón que retorna al Listado y Gestión de Usuarios */
            position: absolute;
            top: 10px;
            right: 20px;
            padding: 10px 20px;
            background-color: #5DADE2;
            color: white;
            display: inline-block;
        }
    </style>
</head>

<body>
    <!-- Botón de Historial de Usuarios con script 'retorno' para la confirmación del regreso -->
    <a href="ver_us.php" class="boton-us" onclick="retorno(event)">Ver Usuarios <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-bounding-box" viewBox="0 0 16 16">
            <path d="M1.5 1a.5.5 0 0 0-.5.5v3a.5.5 0 0 1-1 0v-3A1.5 1.5 0 0 1 1.5 0h3a.5.5 0 0 1 0 1zM11 .5a.5.5 0 0 1 .5-.5h3A1.5 1.5 0 0 1 16 1.5v3a.5.5 0 0 1-1 0v-3a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 1-.5-.5M.5 11a.5.5 0 0 1 .5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 1 0 1h-3A1.5 1.5 0 0 1 0 14.5v-3a.5.5 0 0 1 .5-.5m15 0a.5.5 0 0 1 .5.5v3a1.5 1.5 0 0 1-1.5 1.5h-3a.5.5 0 0 1 0-1h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 1 .5-.5" />
            <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm8-9a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
        </svg>
    </a>
    <!-- Div principal -->
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header text-center">
                        <h4>Editar Usuario</h4>
                    </div>
                    <!-- Modificación de datos para el respectivo Usuario, htmlspecialchars muestra los datos alojados en la bd -->
                    <div class="card-body text-center">
                        <form action="editar_us.php?ID_us=<?= $usuario['ID_us'] ?>" method="post">
                            <label>Realice los cambios en los campos requeridos:</label> <br>
                            <!-- Nombres del usuario -->
                            <div class="form-group">
                                <label for="nombres">Nombres</label>
                                <input type="text" class="form-control" id="nombres" name="nombres" value="<?= htmlspecialchars($usuario['nombres']) ?>" required>
                            </div>
                            <!-- Apellidos del usuario -->
                            <div class="form-group">
                                <label for="apellidos">Apellidos</label>
                                <input type="text" class="form-control" id="apellidos" name="apellidos" value="<?= htmlspecialchars($usuario['apellidos']) ?>" required>
                            </div>
                            <!-- Edad del usuario -->
                            <div class="form-group">
                                <label for="edad">Edad</label>
                                <input type="number" class="form-control" id="edad" name="edad" value="<?= htmlspecialchars($usuario['edad']) ?>" required>
                            </div>
                            <!-- Teléfono del usuario -->
                            <div class="form-group">
                                <label for="telefono">Teléfono</label>
                                <input type="number" class="form-control" id="telefono" name="telefono" value="<?= htmlspecialchars($usuario['telefono']) ?>" required>
                            </div>
                            <!-- Email del usuario -->
                            <div class="form-group">
                                <label for="email">Correo electrónico</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($usuario['email']) ?>" required>
                            </div>
                            <!-- Selección del Departamento de residencia del usuario -->
                            <div class="form-group">
                                <label for="dpto_resid">Departamento de Residencia</label>
                                <select class="form-control" id="dpto_resid" name="dpto_resid" required>
                                    <!-- Bucle para muestra de cada departamentos_col (consulta previa) -->
                                    <?php foreach ($departamentos_col as $departamento) : ?>
                                        <!-- Si el ID del departamento coincide con el de residencia actual del usuario se selecciona esta opción -->
                                        <option value="<?= htmlspecialchars($departamento['ID_dpto']) ?>" <?= $departamento['ID_dpto'] == $usuario['dpto_resid'] ? 'selected' : '' ?>>
                                            <!-- Mostrar el nombre del departamento en la opción -->
                                            <?= htmlspecialchars($departamento['nombre_dpto']) ?>
                                        </option>
                                    <?php endforeach; ?> <!-- Fin del bucle -->
                                </select>
                            </div>
                            <!-- Envío de información del Usuario -->
                            <button type="submit" class="btn btn-success btn-block">Guardar Cambios</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Script del 'boton-us' (retorno al Listado y Gestión de Usuarios) -->
    <script>
        function retorno(event) {
            event.preventDefault(); // Impide que sea ejecutado el enlace predeterminado
            // Condicional de confirmación o cancelación de ir al Listado y Gestión de Usuarios
            if (confirm("¿Desea retornar al Listado y Gestión de Usuarios? No se realizará la edición del usuario.")) {
                window.location.href = "ver_us.php"; // Redirección al Listado de Usuarios
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