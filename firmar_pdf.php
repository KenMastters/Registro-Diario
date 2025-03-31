<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/vendor/autoload.php');

use setasign\Fpdi\Tcpdf\Fpdi;

// Verificar si se ha proporcionado el archivo
if (!isset($_GET['file']) || !file_exists($_GET['file'])) {
    die('El archivo PDF no existe.');
}

$pdf_path = $_GET['file'];

// Crear una nueva instancia de FPDI extendida para TCPDF
$pdf = new Fpdi();
$pageCount = $pdf->setSourceFile($pdf_path);

// Importar todas las páginas del PDF original
for ($i = 1; $i <= $pageCount; $i++) {
    $tplIdx = $pdf->importPage($i);
    $pdf->AddPage();
    $pdf->useTemplate($tplIdx);
}

// Configurar el certificado digital
$cert_file = __DIR__ . '/certificados/certificado.crt';
$private_key_file = __DIR__ . '/certificados/clave_privada.pem';

if (!file_exists($cert_file) || !file_exists($private_key_file)) {
    die('El certificado o la clave privada no existen.');
}

$pdf->setSignature(
    $cert_file,
    $private_key_file,
    '', // Contraseña de la clave privada
    '',
    2
);

// Guardar el PDF firmado
$signed_pdf_path = __DIR__ . '/Hoja_Semanal_Firmada.pdf';
$pdf->Output($signed_pdf_path, 'F');

echo "<h3>El PDF se ha firmado correctamente.</h3>";
echo "<a href='" . basename($signed_pdf_path) . "'>Descargar PDF firmado</a>";