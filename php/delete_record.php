<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Verificar si se recibió el parámetro 'id'
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Asegurarse de que el ID sea un número entero

    // Conexión a la base de datos
    include('db.php');

    // Preparar la consulta para eliminar el registro
    $sql = "DELETE FROM tasks WHERE id = :id AND user_id = :user_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        // Redirigir al historial con un mensaje de éxito
        header("Location: ../historial.php?message=deleted");
        exit();
    } else {
        // Redirigir al historial con un mensaje de error
        header("Location: ../historial.php?message=error");
        exit();
    }
} else {
    // Redirigir al historial si no se recibió un ID
    header("Location: ../historial.php?message=invalid");
    exit();
}