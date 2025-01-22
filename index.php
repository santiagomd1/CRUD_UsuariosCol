<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios Colombia</title> <!--Nombre de la página -->
    <style>
        body, html { /* Configuración estilo body y html */
            height: 100%;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
        }
        .center-wrapper { /* Ajuste del div principal */
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }
        .text-gradient {
            font-size: 2em; /* Ajuste del tamaño del texto */
            font-weight: bold;
        }
        .yellow {
            color: #FFD700; /* Color amarillo */
        }
        .blue {
            color: #0033A0; /* Color azul */
        }
        .red {
            color: #CE1126; /* Color rojo */
        }
    </style>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> <!-- Recurso de Bootstrapp (CSS) -->
</head>
<body>
    <div class="center-wrapper">  <!-- Div principal -->
        <div class="container text-center" id="inicial"> <!-- Contenedor adicional para mantener estilos de Bootstrap -->
            <h1>
                <!-- Nombre del aplicativo -->
                <span class="yellow">U</span> 
                <span class="blue">s</span>
                <span class="red">u</span>
                <span class="yellow">a</span>
                <span class="blue">r</span>
                <span class="red">i</span>
                <span class="yellow">o</span>
                <span class="blue">s</span>
                <span>‎</span>
                <span class="yellow"> </span>
                <span class="red">C</span>
                <span class="blue">o</span>
                <span class="yellow">l</span>
                <span class="red">o</span>
                <span class="blue">m</span>
                <span class="yellow">b</span>
                <span class="red">i</span>
                <span class="blue">a</span>
            </h1>
            <p>Seleccione la acción de su preferencia:</p>
            <!-- Menú para la gestión en Usuarios Colombia -->
            <a href="crear_us.php" class="btn btn-outline-primary" role="button">Crear Usuarios</a>
            <a href="ver_us.php" class="btn btn-outline-success" role="button">Listado y Gestión de Usuarios</a>
        </div>
    </div>
    <!-- Inclusión y ejecución de código de la biblioteca jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- Adición y ejecución de código de la biblioteca JavaScript de BootStrap -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
