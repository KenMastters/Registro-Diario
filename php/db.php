<?php
$host = 'localhost';
$db = 'registro_diario';
$user = 'root';  // Cambia si tienes otro usuario
$pass = '';      // Cambia si tienes contraseña

// Conectar con la base de datos
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
