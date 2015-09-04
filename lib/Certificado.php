<?php

require_once __DIR__ . '/WriteHTML.php';
require_once __DIR__ . '/QR.php';

function crear_certificado($parametros) {
    $pdf=new PDF_HTML('L', 'mm', 'Letter', 20, 7);
    $pdf->AddPage();
    $pdf->SetFont('Arial', '', 12);

    // IMAGENES

    // QR
    $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]" . "status_chofer.php";
    $qrFile = generarQR($url);
    $pdf->Image($qrFile, 18, 8, 0, 30, 'PNG');
    unlink($qrFile);

    $pdf->Image(__DIR__ . '/../img/logo2.png', 235, 8, 0, 30);
    $pdf->Image(__DIR__ . '/../img/logo.png', 105, 8, 0, 30);

    $pdf->Ln(15);

    // TITULO
    $title = '<p align="center"><b><u>CERTIFICADO DE CAPACITACIÓN PARA EL TRANSPORTE DE MERCANCÍAS PELIGROSAS</u></b></p>';

    $pdf->WriteHTML(utf8_decode($title));

    // BLOQUE 1
    $htmlprev = <<<'EOD'
El Prestador de Servicios de Formación Profesional para la Capacitación Básica y Complementaria Obligatoria de los Conductores de Vehículos Empleados
en el Transporte de Mercancías Peligrosas por Carretera, <b>#PRESTADOR</b>, certifica que el/la <b>#CHOFER</b>, Matricula <b>#MATRICULA</b>, DNI <b>#DNI</b>,
ha participado y completado el curso de Capacitación <b>#CURSO</b> según Resolución S.T N° 110/1997 modificada por Resolución S.T. N° 65/2000 para
los Conductores de los Vehículos antes mencionados.
EOD;

    $holders = ["#PRESTADOR", "#CHOFER", "#MATRICULA", "#DNI", "#CURSO"];
    $variables = [$parametros['prestador'], $parametros['chofer'], $parametros['matricula'], $parametros['dni'], $parametros['curso']];
    $html = str_replace($holders, $variables, utf8_decode($htmlprev));

    $pdf->WriteHTML($html);

    $pdf->Ln(10);

    // BLOQUE 2
    $html = <<<'EOD'
Se expide el presente Certificado, a los efectos de la obtención de la Licencia Nacional habilitante.
La vigencia del mismo es de UN (1) año a partir de la fecha de realización del Curso de Capacitación.
EOD;

    $pdf->WriteHTML(utf8_decode($html));

    $pdf->Ln(20);

    // FECHAS
    $texto = 'Sede del Curso: ';
    $pdf->Cell(50, 5, utf8_decode($texto), 0, 0, 'L');
    $pdf->setFont('Arial', 'B', 12);
    $pdf->Cell(100, 5, utf8_decode($parametros['sede']), 0, 0, 'L');
    $pdf->setFont('Arial', '', 12);

    $texto = 'Fecha del Curso: ';
    $pdf->Cell(10, 5, '', 0, 0, 'L');
    $pdf->Cell(0, 5, $texto, 0, 0, 'L');
    $pdf->setFont('Arial', 'B', 12);
    $pdf->Cell(0, 5, utf8_decode($parametros['fecha_curso']), 0, 1, 'R');
    $pdf->setFont('Arial', '', 12);

    $pdf->Ln(5);

    $texto = 'Numero de Transacción: ';
    $pdf->Cell(50, 5, utf8_decode($texto), 0, 0, 'L');
    $pdf->setFont('Arial', 'B', 12);
    $pdf->Cell(100, 5, utf8_decode($parametros['transaccion']), 0, 0, 'L');
    $pdf->setFont('Arial', '', 12);

    $texto = 'Fecha de Transacción: ';
    $pdf->Cell(10, 5, '', 0, 0, 'L');
    $pdf->Cell(0, 5, utf8_decode($texto), 0, 0, 'L');
    $pdf->setFont('Arial', 'B', 12);
    $pdf->Cell(0, 5, utf8_decode($parametros['fecha_transaccion']), 0, 1, 'R');
    $pdf->setFont('Arial', '', 12);

    $pdf->Ln(38);

    // FIRMAS
    $texto = 'Firma del Director del Prestador de Servicios';
    $pdf->Cell(100, 5, utf8_decode($texto), 0, 0, 'L');
    $pdf->Cell(50, 5, '', 0, 0, 'L');
    $texto = 'Firma del Auditor de la Comisión Nacional';
    $pdf->Cell(100, 5, utf8_decode($texto), 0, 0, 'L');

    $pdf->Ln(5);

    $texto = 'de Formacion Profesional.';
    $pdf->Cell(15, 5, '', 0, 0, 'L');
    $pdf->Cell(50, 5, utf8_decode($texto), 0, 0, 'L');
    $texto = 'del Tránsito y la Seguridad Vial.';
    $pdf->Cell(95, 5, '', 0, 0, 'L');
    $pdf->Cell(90, 5, utf8_decode($texto), 0, 0, 'L');

    return $pdf;
}
