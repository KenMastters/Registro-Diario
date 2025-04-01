<?php
session_start();
include('db.php');

// Verificar si hay un ID en la URL
if (!isset($_GET['id'])) {
    header("Location: ../historial.php");
    exit();
}

$id = $_GET['id'];

// Obtener los datos del registro
$stmt = $pdo->prepare("SELECT * FROM tasks WHERE id = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();
$task = $stmt->fetch(PDO::FETCH_ASSOC);

// Si no se encuentra el registro
if (!$task) {
    echo "Registro no encontrado.";
    exit();
}

// Procesar la actualización
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fecha = $_POST['fecha'];
    $actividad = $_POST['actividad'];
    $tiempo = $_POST['tiempo'];
    $observaciones = $_POST['observaciones'];
    $semana = $_POST['semana']; // Capturar el valor de la semana

    $updateStmt = $pdo->prepare("UPDATE tasks SET fecha = :fecha, actividad = :actividad, tiempo = :tiempo, observaciones = :observaciones, semana = :semana WHERE id = :id");
    $updateStmt->bindParam(':fecha', $fecha);
    $updateStmt->bindParam(':actividad', $actividad);
    $updateStmt->bindParam(':tiempo', $tiempo);
    $updateStmt->bindParam(':observaciones', $observaciones);
    $updateStmt->bindParam(':semana', $semana); // Vincular el valor de la semana
    $updateStmt->bindParam(':id', $id);

    if ($updateStmt->execute()) {
        header("Location: ../historial.php");
        exit();
    } else {
        echo "Error al actualizar el registro.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Editar Registro</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>

<body>
    <h1>Editar Registro</h1>
    <form action="" method="POST">
        <label for="fecha">Fecha:</label>
        <input type="datetime-local" id="fecha" name="fecha" value="<?= $task['fecha'] ?>" required><br><br>

        <label for="actividad">Actividad:</label>
        <input type="text" id="actividad" name="actividad" value="<?= $task['actividad'] ?>" required><br><br>

        <label for="tiempo">Tiempo (en horas):</label>
        <input type="time" id="tiempo" name="tiempo" value="<?= $task['tiempo'] ?>" required><br><br>

        <label for="observaciones">Observaciones:</label>
        <textarea id="observaciones" name="observaciones"><?= $task['observaciones'] ?></textarea><br><br>

        <!-- Nuevo campo para la semana -->
        <label for="semana">Semana:</label>
        <input type="text" id="semana" name="semana" value="<?= $task['semana'] ?>" required><br><br>

        <button type="submit">Guardar Cambios</button>
    </form>
    <div class="button-container">
        <button class="historial-btn" onclick="window.location.href='../historial.php'">Volver al Historial</button>
    </div>

    <!-- Script para calcular la semana automáticamente -->
    <script>
        document.getElementById('fecha').addEventListener('change', function () {
            const fechaSeleccionada = new Date(this.value);
            const lunes = new Date(fechaSeleccionada);
            lunes.setDate(fechaSeleccionada.getDate() - fechaSeleccionada.getDay() + 1); // Lunes
            const domingo = new Date(lunes);
            domingo.setDate(lunes.getDate() + 6); // Domingo

            const opciones = { day: '2-digit', month: 'long', year: 'numeric' };
            const semanaTexto = `SEMANA DEL ${lunes.toLocaleDateString('es-ES', opciones)} AL ${domingo.toLocaleDateString('es-ES', opciones)}`;
            document.getElementById('semana').value = semanaTexto;
        });
    </script>
</body>

</html>