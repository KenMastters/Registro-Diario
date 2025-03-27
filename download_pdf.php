<?php
// Iniciar sesión
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: php/login.php");
    exit();
}

$user_id = $_SESSION['user_id']; // Obtener el ID del usuario logueado

// Conexión a la base de datos
include('php/db.php');

// Consultar las tareas del usuario, incluyendo el nombre del usuario
$sql = "SELECT tasks.id, tasks.fecha, tasks.actividad, tasks.tiempo, tasks.observaciones, users.username 
        FROM tasks 
        JOIN users ON tasks.user_id = users.id 
        WHERE tasks.user_id = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Incluir la librería FPDF
require('fpdf/fpdf.php');

// Crear un nuevo PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);

// Título del PDF
$pdf->Cell(200, 10, 'Historial de Tareas', 0, 1, 'C');
$pdf->Ln(10); // Salto de línea

// Definir el ancho de las columnas
$widths = [40, 18, 30, 18, 80]; // Anchos de las celdas (Usuario, Fecha, Actividad, Tiempo, Observaciones)

// Definir la tabla con el nombre del usuario en lugar del ID
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell($widths[0], 10, 'Usuario', 1, 0, 'C');
$pdf->Cell($widths[1], 10, 'Fecha', 1, 0, 'C');
$pdf->Cell($widths[2], 10, 'Actividad', 1, 0, 'C');
$pdf->Cell($widths[3], 10, 'Tiempo', 1, 0, 'C');
$pdf->Cell($widths[4], 10, 'Observaciones', 1, 1, 'C'); // El 1 al final significa que se salta a la siguiente línea

// Rellenar la tabla con las tareas
$pdf->SetFont('Arial', '', 8);
foreach ($tasks as $task) {
    // Formatear la fecha para mostrar solo la parte de la fecha (sin la hora)
    $fecha = date('Y-m-d', strtotime($task['fecha']));  // 'Y-m-d' devuelve la fecha en formato: año-mes-día

    // Calcular la altura de la fila para la celda de observaciones
    $observaciones = $task['observaciones'];
    $actividad = $task['actividad'];
    
    // Usamos una función de FPDF para calcular cuántas líneas ocupará el texto de las observaciones
    $pdf->SetFont('Arial', '', 8);
    $lineHeight = 5; // Ajusta este valor según el tamaño de la fuente y la celda
    $maxWidth = $widths[4] - 2; // Restamos un pequeño margen
    $numLines = $pdf->GetStringWidth($observaciones) / $maxWidth;
    $numLines = ceil($numLines);  // Redondeamos para obtener el número total de líneas

    // Definir la altura de la fila según el número de líneas que ocupan las observaciones
    $height = max($numLines * $lineHeight, 10); // Al menos 10 unidades de altura para cada fila

    // Ajustar la altura de todas las celdas de esa fila
    $pdf->SetFont('Arial', '', 8);

    // Dibujar las celdas con la altura calculada para la fila
    // Usamos el mismo valor de altura para todas las celdas
    $pdf->Cell($widths[0], $height, $task['username'], 1, 0, 'C');
    $pdf->Cell($widths[1], $height, $fecha, 1, 0, 'C');
    $pdf->Cell($widths[2], $height, $actividad, 1, 0, 'C');
    $pdf->Cell($widths[3], $height, $task['tiempo'], 1, 0, 'C');
    
    // Usamos MultiCell para las observaciones, ya que puede ocupar varias líneas
    $pdf->MultiCell($widths[4], $lineHeight, $observaciones, 1, 'C');
    
    // Ahora, añadimos un salto de línea con la altura calculada
    // Para que todas las celdas en la fila tengan la misma altura
    $pdf->Ln($height);
}

// Descargar el archivo PDF
$pdf->Output('D', 'historial_tareas.pdf');
?>
