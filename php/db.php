<?php
$host = 'localhost';
$db = 'registro_diario';
$user = 'root';  // Cambia si tienes otro usuario
$pass = '1985';      // Cambia si tienes contraseña

// Conectar con la base de datos
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->exec("SET NAMES 'utf8mb4'");
    $pdo->exec("SET CHARACTER SET utf8mb4");
    $pdo->exec("SET SESSION collation_connection = 'utf8mb4_general_ci'");
    
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
