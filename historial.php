<?php
// Conexión a la base de datos
include('php/db.php');

// Obtener todas las tareas
$sql = "SELECT * FROM tasks";
$stmt = $pdo->query($sql);
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
                        <a href="php/edit_record.php?id=<?php echo $task['id']; ?>" class="edit-btn">Editar</a>
                        <a href="php/delete_record.php?id=<?php echo $task['id']; ?>" class="delete-btn" onclick="return confirmDelete()">Eliminar</a>
                    </td>

                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <a href="php/add_record.php" class="registro-btn">Volver al Registro</a>
    <script>
    function confirmDelete() {
        // Mostrar el cuadro de confirmación
        return confirm("¿Estás seguro de que deseas eliminar este registro?");
    }
</script>


</body>

</html>