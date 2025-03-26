<?php
// Iniciar la sesión
session_start();

// Si ya está logueado, lo redirigimos al historial
if (isset($_SESSION['user_id'])) {
    header("Location: historial.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Conexión a la base de datos
    include('php/db.php');
    
    // Obtener los datos del formulario
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        // Verificar si el nombre de usuario ya existe
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();

        if ($stmt->fetchColumn() > 0) {
            // Si el nombre de usuario ya existe
            echo "El nombre de usuario ya está en uso. Por favor, elige otro.";
        } else {
            // Hashear la contraseña
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Preparar la consulta SQL para insertar al usuario
            $sql = "INSERT INTO users (username, password) VALUES (:username, :password)";
            $stmt = $pdo->prepare($sql);
            
            // Vincular los parámetros
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $hashedPassword);
            
            // Ejecutar la consulta
            $stmt->execute();

            // Redirigir a login después del registro
            header("Location: php/login.php");
            exit();
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
    <title>Registrar Usuario</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <h1>Registrar Usuario</h1>
    <form action="registro.php" method="POST">
        <label for="username">Nombre de Usuario:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Contraseña:</label>
        <input type="password" id="password" name="password" required><br><br>

        <button type="submit">Registrar</button>
    </form>

    <p>¿Ya tienes una cuenta? <a href="php/login.php">Iniciar sesión</a></p>
</body>
</html>
