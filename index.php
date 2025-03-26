<?php
// Inicio de sesión
session_start();

// Si el usuario está logueado, lo redirigimos al historial
if (isset($_SESSION['user_id'])) {
    header("Location: historial.php");
    exit();
} else {
    // Si no está logueado, lo redirigimos al registro
    header("Location: registro.php");
    exit();
}
?>

