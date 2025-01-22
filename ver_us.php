<?php
session_start();
require 'database.php'; // Conexión a la base de datos

// Consulta a la bd para obtener todos los usuarios en orden alfabético junto con los departamentos de colombia
$query = $pdo->prepare("
    SELECT usuarios.*, usuarios.ID_us AS id_us, departamentos_col.nombre_dpto AS nombre_dpto, departamentos_col.ID_dpto AS id_dpto
    FROM usuarios
    JOIN departamentos_col ON usuarios.dpto_resid = departamentos_col.ID_dpto
    ORDER BY usuarios.nombres ASC
");
$query->execute();
$usuarios = $query->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios</title> <!-- Nombre de la página -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet"> <!-- Recurso de Bootstrapp (CSS) -->
    <style>
        #table_view {
            /* Tamaño y alineación del texto de la tabla */
            font-size: 12px;
            text-align: center;
        }
        /* Interfaz del estado del usuario */
        .estado-activo {
            background-color: #d4edda; /* Verde claro */
        }
        .estado-eliminado {
            background-color: #f8d7da; /* Rojo claro */
        }
        .buton-home {
            /* Estilo del botón que lleva al menú principal */
            position: absolute;
            top: 10px;
            right: 20px;
            padding: 10px 20px;
            background-color: #2ECC71;
            color: white;
            display: inline-block;
        }
    </style>
</head>

<body>
    <!-- Botón de retorno al Menú principal -->
    <a href="index.php" class="buton-home">Menú <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-house-door" viewBox="0 0 16 16">
            <path d="M8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4.5a.5.5 0 0 0 .5-.5v-4h2v4a.5.5 0 0 0 .5.5H14a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293zM2.5 14V7.707l5.5-5.5 5.5 5.5V14H10v-4a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5v4z" />
        </svg>
    </a>
    <!-- Div principal -->
    <div class="container mt-5 text-center">
        <h1><svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="bi bi-ticket-perforated" viewBox="0 0 16 16">
                <path d="M4 4.85v.9h1v-.9zm7 0v.9h1v-.9zm-7 1.8v.9h1v-.9zm7 0v.9h1v-.9zm-7 1.8v.9h1v-.9zm7 0v.9h1v-.9zm-7 1.8v.9h1v-.9zm7 0v.9h1v-.9z" />
                <path d="M1.5 3A1.5 1.5 0 0 0 0 4.5V6a.5.5 0 0 0 .5.5 1.5 1.5 0 1 1 0 3 .5.5 0 0 0-.5.5v1.5A1.5 1.5 0 0 0 1.5 13h13a1.5 1.5 0 0 0 1.5-1.5V10a.5.5 0 0 0-.5-.5 1.5 1.5 0 0 1 0-3A.5.5 0 0 0 16 6V4.5A1.5 1.5 0 0 0 14.5 3zM1 4.5a.5.5 0 0 1 .5-.5h13a.5.5 0 0 1 .5.5v1.05a2.5 2.5 0 0 0 0 4.9v1.05a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-1.05a2.5 2.5 0 0 0 0-4.9z" />
            </svg>
            Listado y Gestión de Usuarios <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="bi bi-ticket-perforated" viewBox="0 0 16 16">
                <path d="M4 4.85v.9h1v-.9zm7 0v.9h1v-.9zm-7 1.8v.9h1v-.9zm7 0v.9h1v-.9zm-7 1.8v.9h1v-.9zm7 0v.9h1v-.9zm-7 1.8v.9h1v-.9zm7 0v.9h1v-.9z" />
                <path d="M1.5 3A1.5 1.5 0 0 0 0 4.5V6a.5.5 0 0 0 .5.5 1.5 1.5 0 1 1 0 3 .5.5 0 0 0-.5.5v1.5A1.5 1.5 0 0 0 1.5 13h13a1.5 1.5 0 0 0 1.5-1.5V10a.5.5 0 0 0-.5-.5 1.5 1.5 0 0 1 0-3A.5.5 0 0 0 16 6V4.5A1.5 1.5 0 0 0 14.5 3zM1 4.5a.5.5 0 0 1 .5-.5h13a.5.5 0 0 1 .5.5v1.05a2.5 2.5 0 0 0 0 4.9v1.05a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-1.05a2.5 2.5 0 0 0 0-4.9z" />
            </svg>
        </h1>
        <!-- Tabla Usuarios -->
        <table class="table table-bordered" id="table_view">
            <thead>
                <!-- Nombres de la columnas -->
                <tr>
                    <th>ID</th>
                    <th>Nombres (A-Z)</th>
                    <th>Apellidos</th>
                    <th>Edad</th>
                    <th>Teléfono</th>
                    <th>Correo electrónico</th>
                    <th>Departamento de Residencia</th>
                    <th>Fecha de registro (A/M/D)</th>
                    <th>Fecha últ. modificación (A/M/D)</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <!-- Bucle para cada elemento 'usuarios' (consulta previa) -->
                <?php foreach ($usuarios as $usuario) : ?>
                    <?php // Definir la clase CSS según el estado
                    $estado_class = '';
                    switch ($usuario['estado_us']) {
                        case 'Activo':
                            $estado_class = 'estado-activo';
                            break;
                        case 'Eliminado':
                            $estado_class = 'estado-eliminado';
                            break;
                    } ?>
                    <tr>
                        <!-- Detalles de los Usuarios Colombia -->
                        <td><?= htmlspecialchars($usuario['id_us']) ?></td>
                        <td><?= htmlspecialchars($usuario['nombres']) ?></td>
                        <td><?= htmlspecialchars($usuario['apellidos']) ?></td>
                        <td><?= htmlspecialchars($usuario['edad']) ?></td>
                        <td><?= htmlspecialchars($usuario['telefono']) ?></td>
                        <td><?= htmlspecialchars($usuario['email']) ?></td>
                        <!-- Número del departamento colombiano (ID) y su respectivo nombre -->
                        <td><?= htmlspecialchars($usuario['id_dpto']) ?>: <?= htmlspecialchars($usuario['nombre_dpto']) ?></td>
                        <td><?= htmlspecialchars($usuario['fecha_reg']) ?></td>
                        <td><?= htmlspecialchars($usuario['fecha_ult_mod']) ?></td>
                        <!-- Estado del usuario (Activo o Eliminado) -->
                        <td class="<?= $estado_class ?>"><?= htmlspecialchars($usuario['estado_us']) ?></td>
                        <td>
                            <!-- Cuando el estado es "Activo", se muestran los botones "Editar" y "Eliminar" -->
                            <?php if ($usuario['estado_us'] == 'Activo') : ?>
                                <a href="editar_us.php?ID_us=<?= $usuario['id_us'] ?>" class="btn btn-sm btn-info editar-boton">Editar <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                        <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                        <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                                    </svg></a>
                                <br><br>
                                <!-- Al dar clic en 'Eliminar' se ejecutará el script relacionado a través de 'data-id' -->
                                <button class="btn btn-sm btn-danger eliminar-boton" data-id="<?= $usuario['id_us'] ?>">Eliminar <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="currentColor" class="bi bi-person-x-fill" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd" d="M1 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6m6.146-2.854a.5.5 0 0 1 .708 0L14 6.293l1.146-1.147a.5.5 0 0 1 .708.708L14.707 7l1.147 1.146a.5.5 0 0 1-.708.708L14 7.707l-1.146 1.147a.5.5 0 0 1-.708-.708L13.293 7l-1.147-1.146a.5.5 0 0 1 0-.708" />
                                    </svg>
                                </button>
                            <?php else : ?> <!-- Cuando el estado es "Eliminado" no se realiza ninguna acción -->
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?> <!-- Fin del bucle -->
            </tbody>
        </table>
    </div>
    <!-- Acciones generadas al dar clic sobre el botón 'Eliminar' -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.eliminar-boton').forEach(button => {
                button.addEventListener('click', function() {
                    const userId = this.getAttribute('data-id');
                    const parentTd = this.parentElement;
                    const row = parentTd.parentElement;
                    const estadoTd = row.children[8];

                    // Mostrar ventana emergente de confirmación
                    const confirmation = confirm('¿Está seguro de que desea eliminar el registro? No podrá revertir este cambio.');

                    // Condicional de acuerdo a la confirmación o cancelación de la acción
                    if (confirmation) {
                        // Remover el botón de eliminar
                        this.remove();
                        // Eliminar el botón de editar
                        const editButton = parentTd.querySelector('.editar-boton');
                        if (editButton) {
                            editButton.remove();
                        }

                        // Solicitud AJAX para actualizar el estado del usuario y la fecha de última modificación a través de 'estado.php'
                        fetch('estado.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({
                                    usuario: userId, // Referencia de acuerdo al ID_us
                                    estado: 'Eliminado' // Nuevo estado del Usuario
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    if (data.refresh) {
                                        // Recargar la página con el fin de mostrar la nueva fecha últ. mod.
                                        location.reload();
                                    } else {
                                        // Actualizar la interfaz de usuario para reflejar el cambio de estado
                                        estadoTd.textContent = 'Eliminado';
                                        estadoTd.className = 'estado-eliminado';
                                        alert('Usuario eliminado correctamente.'); // Mostrar mensaje de acuerdo a la eliminación
                                    }
                                } else {
                                    alert('Error al actualizar el estado: ' + data.message); // Mensaje de error en el cambio de estado
                                }
                            })
                            .catch(error => { // Mensaje de error
                                console.error('Error:', error);
                                alert('Error en la actualización del estado.');
                            });
                    }
                });
            });
        });
    </script>
    <!-- Inclusión y ejecución de código de la biblioteca jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- Adición y ejecución de código de la biblioteca JavaScript de BootStrap -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>