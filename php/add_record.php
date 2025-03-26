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

        // Mensaje de éxito
        $mensaje = "Registro agregado con éxito.";
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
    <h1>Agregar Nuevo Registro</h1>

    <?php if ($mensaje): ?>
        <p class="success-message"><?php echo $mensaje; ?></p>
    <?php endif; ?>

    <form action="add_record.php" method="POST">
        <label for="fecha">Fecha:</label>
        <input type="datetime-local" id="fecha" name="fecha" required><br><br>

        <label for="actividad">Actividad:</label>
        <input type="text" id="actividad" name="actividad" required><br><br>

        <label for="tiempo">Tiempo (en horas):</label>
        <input type="time" id="tiempo" name="tiempo" required><br><br>

        <label for="observaciones">Observaciones:</label>
        <textarea id="observaciones" name="observaciones"></textarea><br><br>

        <button type="submit" >Agregar Registro</button>
    </form>

    <br>
    <button class="historial-btn" onclick="window.location.href='../historial.php'">Ver Historial</button>
    <a href="logout.php" class="logout-btn">Cerrar Sesión</a>

</body>
</html>
