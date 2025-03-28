<?php
// filepath: c:\Users\admin\Documents\Registro-Diario\download_pdf.php

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
$pdf->SetFont('Arial', 'B', 12);

// Encabezado
$pdf->Image('images/image2.jpg', 10, 10, 20); // Logo izquierda
$pdf->Cell(0, 10, 'Región de Murcia', 0, 1, 'C');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 10, 'Consejería de Educación, Cultura y Universidades', 0, 1, 'C');
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'MÓDULO DE FORMACIÓN EN CENTROS DE TRABAJO', 0, 1, 'C');
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 10, 'HOJA SEMANAL DEL ALUMNO - ANEXO IV', 0, 1, 'C');
$pdf->Image('images/image1.jpg', 180, 10, 20); // Logo derecha
$pdf->Ln(10);

// Información general
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(95, 10, 'Centro docente: IES FRANCISCO DE GOYA - 30008340', 0, 0);
$pdf->Cell(95, 10, 'Centro de trabajo: COMENIUS IDI, S.L.', 0, 1);
$pdf->Cell(95, 10, 'Tutor/a del Centro Docente: JOSE ANTONIO BRAVO LÓPEZ', 0, 0);
$pdf->Cell(95, 10, 'Tutor/a del Centro de Trabajo: ANA FUENSANTA HERNÁNDEZ ORTIZ', 0, 1);
$pdf->Cell(95, 10, 'Alumno/a: ' . $tasks[0]['username'], 0, 1);
$pdf->Cell(95, 10, 'Familia profesional: INFORMÁTICA Y COMUNICACIONES', 0, 0);
$pdf->Cell(95, 10, 'Ciclo Formativo: DESARROLLO DE APLICACIONES WEB', 0, 1);
$pdf->Cell(95, 10, 'Periodo: 17/03/2025 - 16/06/2025', 0, 0);
$pdf->Cell(95, 10, 'Semana del 17 al 23 de MARZO de 2025', 0, 1);
$pdf->Ln(10);

// Tabla de actividades
$pdf->SetFont('Arial', 'B', 10);
$pdf->Cell(30, 10, 'Fecha', 1, 0, 'C');
$pdf->Cell(80, 10, 'Actividades realizadas', 1, 0, 'C');
$pdf->Cell(30, 10, 'Tiempo', 1, 0, 'C');
$pdf->Cell(50, 10, 'Observaciones', 1, 1, 'C');

$pdf->SetFont('Arial', '', 10);
foreach ($tasks as $task) {
    $pdf->Cell(30, 10, $task['fecha'], 1, 0, 'C');
    $pdf->Cell(80, 10, $task['actividad'], 1, 0, 'C');
    $pdf->Cell(30, 10, $task['tiempo'], 1, 0, 'C');
    $pdf->Cell(50, 10, $task['observaciones'], 1, 1, 'C');
}

// Firmas
$pdf->Ln(10);
$pdf->Cell(95, 10, 'Alumno/a: ' . $tasks[0]['username'], 0, 0);
$pdf->Cell(95, 10, 'Fdo.: ' . $tasks[0]['username'], 0, 1);
$pdf->Cell(95, 10, 'Vº Bº El Tutor del Centro de Trabajo', 0, 0);
$pdf->Cell(95, 10, 'Fdo.: ANA FUENSANTA HERNÁNDEZ ORTIZ', 0, 1);
$pdf->Cell(95, 10, 'Vº Bº El Tutor del Centro Docente', 0, 0);
$pdf->Cell(95, 10, 'Fdo.: JOSE ANTONIO BRAVO LÓPEZ', 0, 1);

// Descargar el archivo PDF
$pdf->Output('D', 'Hoja_Semanal.pdf');
?>