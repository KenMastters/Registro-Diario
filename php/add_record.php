<?php
// Iniciar sesión
session_start();

// Verificar si el usuario está logueado, si no redirigir a login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Conexión a la base de datos
include('db.php');

$mensaje = ""; // Variable para mostrar mensajes al usuario

// Verificar si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los datos del formulario
    $fecha = $_POST['fecha'];
    $actividad = $_POST['actividad'];
    $tiempo = $_POST['tiempo'];
    $observaciones = $_POST['observaciones'];
    $user_id = $_SESSION['user_id']; // Obtener el ID del usuario logueado

    try {
        // Preparar la consulta SQL para insertar la tarea
        $sql = "INSERT INTO tasks (fecha, actividad, tiempo, observaciones, user_id) 
                VALUES (:fecha, :actividad, :tiempo, :observaciones, :user_id)";
        $stmt = $pdo->prepare($sql);

        // Vincular los parámetros
        $stmt->bindParam(':fecha', $fecha);
        $stmt->bindParam(':actividad', $actividad);
        $stmt->bindParam(':tiempo', $tiempo);
        $stmt->bindParam(':observaciones', $observaciones);
        $stmt->bindParam(':user_id', $user_id);

        // Ejecutar la consulta
        $stmt->execute();

        // redirigir a la página de historial con un mensaje de éxito

        header("Location: add_record.php?success=1");
        exit();
    } catch (PDOException $e) {
        $mensaje = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Registro</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>

<body>
    <h1>Crear tarea</h1>

    <?php if (isset($_GET['success'])): ?>
        <p class="success-message" style="display: none;">Registro agregado con éxito!, si lo desea puede agregar más</p>
    <?php endif; ?>


    <form action="add_record.php" method="POST">
        <label for="fecha">Fecha:</label>
        <input type="date" id="fecha" name="fecha" required><br><br>

        <label for="actividad">Actividad:</label>
        <input type="text" id="actividad" name="actividad" required><br><br>

        <label for="tiempo">Tiempo (en horas):</label>
        <input type="time" id="tiempo" name="tiempo" required><br><br>

        <label for="observaciones">Observaciones:</label>
        <textarea id="observaciones" name="observaciones"></textarea><br><br>

        <button type="submit">Guardar tarea en el registro</button>
    </form>

    <br>
    <div class="button-container">
        <button class="historial-btn" onclick="window.location.href='../historial.php'">Ver historial de las tareas guardadas</button>
        <button class="logout-btn" onclick="window.location.href='logout.php'">Cerrar Sesión</button>
    </div>
    <script src="../js/success-message.js"></script>

</body>


</html>