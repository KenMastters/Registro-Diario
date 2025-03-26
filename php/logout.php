<?php
session_start();

// Eliminar todas las variables de sesión
session_unset();

// Destruir la sesión
session_destroy();

// Redirigir al login
echo "Sesión cerrada correctamente.";
header("Location: login.php");
exit();
?>
