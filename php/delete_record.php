<?php
session_start();  // Mantener la sesión activa
include('db.php');

// Verificar que el ID esté presente en la URL
if (!isset($_GET['id'])) {
    header("Location: ../historial.php");  // Si no hay ID, redirigir al historial
    exit();
}

$id = $_GET['id'];

// Eliminar el registro con el ID proporcionado
$stmt = $pdo->prepare("DELETE FROM tasks WHERE id = :id");
$stmt->bindParam(':id', $id);

if ($stmt->execute()) {
    // Redirigir al formulario de agregar registro después de la eliminación
    header("Location: ../historial.php");
    exit();
} else {
    // Si hay un error en la eliminación, mostrar un mensaje
    echo "Error al eliminar el registro.";
}
?>
