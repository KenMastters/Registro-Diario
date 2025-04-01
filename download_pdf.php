<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: php/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Conexión a la base de datos
include('php/db.php');

// Recuperar el nombre del usuario desde la base de datos
$sql_usuario = "SELECT username AS nombre FROM users WHERE id = :user_id";
$stmt_usuario = $pdo->prepare($sql_usuario);
$stmt_usuario->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt_usuario->execute();
$result_usuario = $stmt_usuario->fetch(PDO::FETCH_ASSOC);

// Si se encuentra el usuario, usar su nombre; de lo contrario, usar un valor predeterminado
$nombre_alumno = $result_usuario['nombre'] ?? 'Usuario Desconocido';

// Consultar las tareas del usuario
$sql = "SELECT tasks.fecha, tasks.actividad, tasks.tiempo, tasks.observaciones 
        FROM tasks 
        WHERE tasks.user_id = :user_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Obtener la semana desde la base de datos
$sql_semana = "SELECT DISTINCT semana FROM tasks WHERE user_id = :user_id LIMIT 1";
$stmt_semana = $pdo->prepare($sql_semana);
$stmt_semana->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt_semana->execute();
$result_semana = $stmt_semana->fetch(PDO::FETCH_ASSOC);

// Si se encuentra una semana, úsala; de lo contrario, usa un valor predeterminado
$semana = $result_semana['semana'] ?? 'SEMANA NO DEFINIDA';

// Datos fijos
$centro_docente = 'IES FRANCISCO DE GOYA - 30008340';
$tutor_docente = 'JOSE ANTONIO BRAVO LÓPEZ';
$centro_trabajo = 'COMENIUS IDI, S.L.';
$tutor_trabajo = 'ANA FUENSANTA HERNÁNDEZ ORTIZ';
$familia_profesional = 'INFORMÁTICA Y COMUNICACIONES';
$ciclo_formativo = 'DESARROLLO DE APLICACIONES WEB';
$periodo = '17/03/2025 - 16/06/2025';
$horas = '400';

// Incluir TCPDF
require_once(__DIR__ . '/TCPDF/tcpdf.php');

// Crear instancia de TCPDF
$pdf = new TCPDF();
$pdf->SetMargins(10, 10, 10);
$pdf->SetAutoPageBreak(TRUE, 10);
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 8); // Fuente Helvetica, tamaño 10

// Encabezado con imágenes y texto centrado
$pdf->Image('images/Union Europea.jpg', 180, 13, 20); // Imagen derecha
$pdf->SetY(10); // Ajustar la posición vertical del texto
$pdf->SetFont('helvetica', 'B', 10); // Helvetica en negrita
$pdf->Cell(0, 4, 'MÓDULO DE FORMACIÓN EN CENTROS DE TRABAJO', 0, 1, 'C');
$pdf->Cell(0, 4, 'HOJA SEMANAL DEL ALUMNO', 0, 1, 'C');
$pdf->Cell(0, 4, 'ANEXO IV', 0, 1, 'C');
$pdf->Ln(4);
$pdf->Image('images/Region de Murcia.jpg', 10, 14, 50); // Imagen izquierda

// Contenido en HTML
$html = "<style>
    table { width: 100%; border-collapse: collapse; font-size: 8px; }
    th { border: 0.5px solid black; padding: 5px; text-align: center; background-color: #f2f2f2; font-weight: bold; }
    td { border: 0.5px solid black; padding: 5px; text-align: left; font-weight: normal; } 
</style>
<table>
    <tr>
        <td>
            Centro docente: $centro_docente<br>
            Tutor/a del Centro Docente: $tutor_docente<br>
            Alumno/a: $nombre_alumno
        </td>
        <td>
            Centro de trabajo: $centro_trabajo<br>
            Tutor/a del Centro de Trabajo: $tutor_trabajo
        </td>
    </tr>
    <tr>
        <td>
            Familia profesional: $familia_profesional<br>
            Ciclo Formativo: $ciclo_formativo
        </td>
        <td>
            Periodo: $periodo<br>
            Semana: $semana<br>
            Horas: $horas
        </td>
    </tr>
</table>
<br>";

// Escribir la primera tabla
$pdf->writeHTML($html, true, false, true, false, '');

// Crear la tabla de tareas separada
$html_tareas = "<style>
    table { width: 100%; border-collapse: collapse; font-size: 9px; }
    th { border: 0.5px solid black; padding: 5px; text-align: center; background-color: #f2f2f2; font-weight: bold; }
    td { border: 0.5px solid black; padding: 5px; text-align: center; font-weight: normal; }
</style>
<table>
    <tr>
        <th>Fecha</th>
        <th>Actividades realizadas</th>
        <th>Tiempo</th>
        <th>Observaciones</th>
    </tr>";

// Agregar datos de tareas en la tabla
foreach ($tasks as $task) {
    $html_tareas .= "<tr>
                        <td>" . date("d/m/Y", strtotime($task['fecha'])) . "</td>
                        <td>{$task['actividad']}</td>
                        <td>{$task['tiempo']}</td>
                        <td>{$task['observaciones']}</td>
                     </tr>";
}
$html_tareas .= "</table>";

// Escribir la tabla de tareas
$pdf->writeHTML($html_tareas, true, false, true, false, '');

// Firmas en tres columnas
$pdf->Ln(10); // Espacio antes de las firmas
$html_firmas = "<style>
    table { width: 100%; border-collapse: collapse; font-size: 10px; }
    td { text-align: center; vertical-align: top; padding: 20px 5px; font-weight: normal; } /* Espacio para las firmas */
</style>
<table>
    <tr>
        <td>
            Alumno/a:<br><br><br> <!-- Espacio para la firma -->
            Fdo.: $nombre_alumno
        </td>
        <td>
            Vº Bº El Tutor del Centro de Trabajo:<br><br><br> <!-- Espacio para la firma -->
            Fdo.: $tutor_trabajo
        </td>
        <td>
            Vº Bº El Tutor del Centro Docente:<br><br><br> <!-- Espacio para la firma -->
            Fdo.: $tutor_docente
        </td>
    </tr>
</table>";

// Escribir las firmas en el PDF
$pdf->writeHTML($html_firmas, true, false, true, false, '');

// Descargar el PDF directamente
$pdf->Output('Hoja_Semanal.pdf', 'D'); // 'D' fuerza la descarga del archivo

// // Guardar el PDF sin firmar
// $pdf_path = __DIR__ . '/Hoja_Semanal_Sin_Firma.pdf';
// $pdf->Output($pdf_path, 'F'); // Guardar el PDF en el servidor

// // Mostrar un enlace para firmar el PDF
// echo "<h3>El PDF se ha generado correctamente.</h3>";
// echo "<a href='firmar_pdf.php?file=" . urlencode($pdf_path) . "'>Haga clic aquí para firmar el PDF</a>";