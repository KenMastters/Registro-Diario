<?php
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

$nombre_alumno = !empty($tasks) ? $tasks[0]['username'] : 'No disponible';

// Incluir TFPDF
require_once('tfpdf/tfpdf.php'); // Asegúrate de incluir el archivo correcto de tFPDF

$pdf = new tFPDF();

// Registrar las fuentes correctamente con la ruta de las fuentes
$pdf->AddFont('DejaVu','','DejaVuSans.ttf', true); // Fuente normal
$pdf->AddFont('DejaVu','B','DejaVuSans-Bold.ttf', true); // Fuente en negrita

// Establecer la fuente y generar el PDF
$pdf->SetFont('DejaVu', '', 12); // Fuente normal
$pdf->AddPage();

// Establecer márgenes para que todo esté alineado correctamente
$pdf->SetLeftMargin(10);
$pdf->SetRightMargin(10);

// Encabezado
$pdf->Image('images/Region de Murcia.jpg', 5, 10, 60, 0);

// Mover las líneas solicitadas después de la primera imagen y antes de la segunda
$pdf->SetFont('DejaVu', 'B', 11); // Fuente en negrita

// Alineación para evitar que se sobreponga a la imagen
$pdf->SetX(15); // Ajustamos el X para que el texto no se sobreponga a la imagen de la izquierda

$pdf->Cell(0, 10, 'MÓDULO DE FORMACIÓN EN CENTROS DE TRABAJO', 0, 1, 'C');
$pdf->SetFont('DejaVu', 'B', 11);
$pdf->SetX(15); 
$pdf->Cell(0, 10, 'HOJA SEMANAL DEL ALUMNO', 0, 1, 'C');

$pdf->SetFont('DejaVu', 'B', 11);
$pdf->SetX(15); 
$pdf->Cell(0, 10, 'ANEXO IV', 0, 1, 'C');

// Segunda imagen
$pdf->Image('images/Union Europea.jpg', 175, 10, 30, 0);
$pdf->Ln(10);

// Información general
$pdf->SetFont('DejaVu', '', 8);
$pdf->Cell(95, 10, 'Centro docente: IES FRANCISCO DE GOYA - 30008340', 1, 0, 'L');
$pdf->Cell(95, 10, 'Centro de trabajo: COMENIUS IDI, S.L.', 1, 1, 'L');
$pdf->Cell(95, 10, 'Tutor/a del Centro Docente: JOSE ANTONIO BRAVO LÓPEZ', 1, 0, 'L');
$pdf->Cell(95, 10, 'Tutor/a del Centro de Trabajo: ANA FUENSANTA HERNÁNDEZ ORTIZ', 1, 1, 'L');
$pdf->Cell(95, 10, 'Alumno/a: ' . $nombre_alumno, 1, 0, 'L');
$pdf->Cell(95, 10, '', 1, 1, 'L');
$pdf->Cell(95, 10, 'Familia profesional: INFORMÁTICA Y COMUNICACIONES', 1, 0, 'L');
$pdf->Cell(95, 10, 'Periodo: 17/03/2025 - 16/06/2025', 1, 1, 'L');
$pdf->Cell(95, 10, 'Ciclo Formativo: DESARROLLO DE APLICACIONES WEB', 1, 0, 'L');
$pdf->Cell(95, 10, 'Semana del 17 al 23 de MARZO de 2025', 1, 1, 'L');
$pdf->Ln(10);

// Tabla de actividades con celdas de tamaño ajustado
$pdf->SetFont('DejaVu', 'B', 10);

// Anchos para la tabla
$width_fecha = 30; // Ancho de la columna "Fecha"
$width_actividad = 80; // Ancho de la columna "Actividades realizadas"
$width_tiempo = 30; // Ancho de la columna "Tiempo"
$width_observaciones = 50; // Ancho de la columna "Observaciones"

// Cabecera de la tabla
$pdf->Cell($width_fecha, 10, 'Fecha', 1, 0, 'C'); // Columna Fecha
$pdf->Cell($width_actividad, 10, 'Actividades realizadas', 1, 0, 'C'); // Columna Actividades
$pdf->Cell($width_tiempo, 10, 'Tiempo', 1, 0, 'C'); // Columna Tiempo
$pdf->Cell($width_observaciones, 10, 'Observaciones', 1, 1, 'C'); // Columna Observaciones
$pdf->SetFont('DejaVu', '', 10);

// Mostrar los datos de la base de datos
foreach ($tasks as $task) {
    $fecha = date("d/m/Y", strtotime($task['fecha']));
    
    // Imprimir la fila de datos con celdas alineadas
    $pdf->Cell($width_fecha, 10, $fecha, 1, 0, 'C'); // Fecha
    $pdf->Cell($width_actividad, 10, $task['actividad'], 1, 0, 'C'); // Actividades realizadas
    $pdf->Cell($width_tiempo, 10, $task['tiempo'], 1, 0, 'C'); // Tiempo
    $pdf->Cell($width_observaciones, 10, $task['observaciones'], 1, 1, 'C'); // Observaciones

    // Salto de línea después de cada fila
    $pdf->Ln(1);
}

/// Firmas en tres columnas con el orden correcto
$pdf->Ln(10);

// Cambiar el tamaño de la fuente para las firmas
$pdf->SetFont('DejaVu', '', 8); // Tamaño de fuente más pequeño para las firmas

// Definir anchos para las columnas de firmas (ajustado para que quepan 3 columnas)
$width_columna = 60; // Ancho de cada columna para las firmas

// Fila de firmas: 3 columnas
$pdf->Cell($width_columna, 10, 'Alumno/a: ' . '', 0, 0, 'C'); // Columna 1: Alumno
$pdf->Cell($width_columna, 10, 'Vº Bº El Tutor del Centro de Trabajo', 0, 0, 'C'); // Columna 2: Tutor del Centro de Trabajo
$pdf->Cell($width_columna, 10, 'Vº Bº El Tutor del Centro Docente', 0, 1, 'C'); // Columna 3: Tutor del Centro Docente
$pdf->Ln(10);
$pdf->Cell($width_columna, 10, 'Fdo.: ' . $nombre_alumno, 0, 0, 'C'); // Columna 1: Firma Alumno
$pdf->Cell($width_columna, 10, 'Fdo.: ANA FUENSANTA HERNÁNDEZ ORTIZ', 0, 0, 'C'); // Columna 2: Firma Tutor Centro de Trabajo
$pdf->Cell($width_columna, 10, 'Fdo.: JOSE ANTONIO BRAVO LÓPEZ', 0, 1, 'C'); // Columna 3: Firma Tutor Centro Docente



// Descargar el archivo PDF
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="Hoja_Semanal.pdf"');
$pdf->Output('D', 'Hoja_Semanal.pdf');
?>
