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

// Calcular la semana actual (de lunes a domingo)
setlocale(LC_TIME, 'es_ES.UTF-8', 'es_ES', 'Spanish_Spain', 'es'); // Configurar el idioma a español
$inicio_semana = strtotime('last monday', strtotime('tomorrow')); // Lunes de esta semana
$fin_semana = strtotime('next sunday', $inicio_semana); // Domingo de esta semana

$semana_actual = "SEMANA DEL " . strftime('%d de %B', $inicio_semana) . " AL " . strftime('%d de %B de %Y', $fin_semana);

// Verificar si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los datos del formulario
    $fecha = $_POST['fecha'];
    $actividad = $_POST['actividad'];
    $tiempo = $_POST['tiempo'];
    $observaciones = $_POST['observaciones'];
    $semana = $_POST['semana']; // Capturar el valor de la semana
    $user_id = $_SESSION['user_id']; // Obtener el ID del usuario logueado

    // Validar el formato del campo "Semana"
    if (!preg_match('/^SEMANA DEL/', $semana)) {
        $mensaje = "El campo 'Semana' debe comenzar con 'SEMANA DEL'.";
    } else {
        try {
            // Preparar la consulta SQL para insertar la tarea
            $sql = "INSERT INTO tasks (fecha, actividad, tiempo, observaciones, semana, user_id) 
                    VALUES (:fecha, :actividad, :tiempo, :observaciones, :semana, :user_id)";
            $stmt = $pdo->prepare($sql);

            // Vincular los parámetros
            $stmt->bindParam(':fecha', $fecha);
            $stmt->bindParam(':actividad', $actividad);
            $stmt->bindParam(':tiempo', $tiempo);
            $stmt->bindParam(':observaciones', $observaciones);
            $stmt->bindParam(':semana', $semana); // Vincular el valor de la semana
            $stmt->bindParam(':user_id', $user_id);

            // Ejecutar la consulta
            $stmt->execute();

            // Redirigir a la página con un mensaje de éxito
            header("Location: add_record.php?success=1");
            exit();
        } catch (PDOException $e) {
            $mensaje = "Error: " . $e->getMessage();
        }
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

    <?php if (!empty($mensaje)): ?>
        <p class="error-message"><?php echo $mensaje; ?></p>
    <?php endif; ?>

    <?php if (isset($_GET['success'])): ?>
        <p class="success-message">¡Registro agregado con éxito! Si lo desea, puede agregar más tareas.</p>
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

        <!-- Nuevo campo para la semana -->
        <label for="semana">Semana:</label>
        <input type="text" id="semana" name="semana" value="<?php echo $semana_actual; ?>" required><br><br>

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