<?php
// Iniciar sesión
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
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    <h1>Historial de Tareas</h1>

    <div class="button-container">
        <button class="registro-btn" onclick="window.location.href='php/add_record.php'">Ir a crear tarea</button>
    </div>


    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Fecha</th>
                <th>Actividad</th>
                <th>Tiempo</th>
                <th>Observaciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tasks as $task): ?>
                <tr>
                    <td><?php echo $task['id']; ?></td>
                    <td><?php echo $task['fecha']; ?></td>
                    <td><?php echo $task['actividad']; ?></td>
                    <td><?php echo $task['tiempo']; ?></td>
                    <td><?php echo $task['observaciones']; ?></td>
                    <td>
                        <!-- Botón para editar -->
                        <button onclick="window.location.href='php/edit_record.php?id=<?php echo htmlspecialchars($task['id']); ?>'" class="edit-btn">Editar</button>

                        <!-- Botón para eliminar -->
                        <button onclick="return confirmDelete('<?php echo htmlspecialchars($task['id']); ?>')" class="delete-btn">Eliminar</button>
                    </td>

                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <script>
        function confirmDelete(id) {
            const confirmation = confirm("¿Estás seguro de que deseas eliminar este registro?");
            if (confirmation) {
                // Si se confirma, redirige para eliminar el registro
                window.location.href = 'php/delete_record.php?id=' + id;
            }
            return false; // Evitar que la página se recargue automáticamente
        }
    </script>


</body>

</html>