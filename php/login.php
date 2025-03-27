<?php
// Iniciar sesión
session_start();

// Redirigir si el usuario ya está logueado
if (isset($_SESSION['user_id'])) {
    header("Location: add_record.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Conexión a la base de datos
    include('db.php');

    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        // Verificar si el usuario existe
        $sql = "SELECT * FROM users WHERE username = :username";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verificar la contraseña
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id']; // Guardamos el ID del usuario en la sesión
            header("Location: add_record.php");  // Redirigir al historial
            exit();
        } else {
            echo "Usuario o contraseña incorrectos.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>

<body>
    <h1 class="title">Aplicación para el registro de tareas</h1>

    <h1>Iniciar Sesión</h1>
    <form action="login.php" method="POST">
        <label for="username">Nombre de Usuario:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password" required><br><br>

        <button type="submit">Iniciar sesión</button>
    </form>

    <p class="centered-text">¿No tienes cuenta? <a href="/registro.php">Regístrate aquí</a></p>

</body>

</html>