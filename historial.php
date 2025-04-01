<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: php/login.php");
    exit();
}

$user_id = $_SESSION['user_id']; // Obtener el ID del usuario logueado

// Conexión a la base de datos
include('php/db.php');

// Obtener solo las tareas del usuario logueado
$sql = "SELECT * FROM tasks WHERE user_id = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC); // Obtener todas las tareas como un array asociativo
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Tareas</title>
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    <?php if (isset($_GET['message'])): ?>
        <div class="message-container">
            <?php if ($_GET['message'] == 'deleted'): ?>
                <p class="success-message">El registro se eliminó correctamente.</p>
            <?php elseif ($_GET['message'] == 'error'): ?>
                <p class="error-message">Hubo un error al intentar eliminar el registro.</p>
            <?php elseif ($_GET['message'] == 'invalid'): ?>
                <p class="error-message">No se recibió un ID válido para eliminar.</p>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <h1>Historial de Tareas</h1>

    <div class="button-container">
        <button class="registro-btn" onclick="window.location.href='php/add_record.php'">Ir a crear tarea</button>
        <button class="logout-btn" onclick="window.location.href='php/logout.php'">Cerrar Sesión</button>
    </div>
    <div class="button-container">
        <button class="pdf-btn" onclick="window.location.href='download_pdf.php'">Descargar PDF</button>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th class="col-id">ID</th>
                    <th class="col-fecha">Fecha</th>
                    <th class="col-actividad">Actividad</th>
                    <th class="col-tiempo">Tiempo</th>
                    <th class="col-observaciones">Observaciones</th>
                    <th class="col-semana">Semana</th>
                    <th class="col-acciones">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($tasks)): ?>
                    <?php foreach ($tasks as $task): ?>
                        <tr>
                            <td class="col-id"><?php echo $task['id']; ?></td>
                            <td class="col-fecha"><?php echo date("d/m/Y", strtotime($task['fecha'])); ?></td>
                            <td class="col-actividad"><?php echo $task['actividad']; ?></td>
                            <td class="col-tiempo"><?php echo $task['tiempo']; ?></td>
                            <td class="col-observaciones truncate" title="<?php echo $task['observaciones']; ?>">
                                <?php echo $task['observaciones']; ?>
                            </td>
                            <td class="col-semana"><?php echo $task['semana']; ?></td>
                            <td class="col-acciones">
                                <!-- Botón para editar -->
                                <button onclick="window.location.href='php/edit_record.php?id=<?php echo htmlspecialchars($task['id']); ?>'" class="edit-btn">Editar</button>

                                <!-- Botón para eliminar -->
                                <button
                                    onclick="if(confirm('¿Estás seguro de que deseas eliminar este registro?')) { window.location.href='php/delete_record.php?id=<?php echo htmlspecialchars($task['id']); ?>'; }"
                                    class="delete-btn">
                                    Eliminar
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">No hay tareas registradas.</td>
                    </tr>
                <?php endif; ?>
                <script>
                    // Esperar 1 segundo (1000 ms) y luego ocultar el mensaje
                    setTimeout(() => {
                        const messageContainer = document.querySelector('.message-container');
                        if (messageContainer) {
                            messageContainer.style.transition = 'opacity 0.5s ease';
                            messageContainer.style.opacity = '0'; // Desvanecer el mensaje
                            setTimeout(() => messageContainer.remove(), 500); // Eliminar del DOM después de desvanecer
                        }
                    }, 2000); // 2000 ms = 2 segundos
                </script>
            </tbody>
        </table>
    </div>
</body>

</html>