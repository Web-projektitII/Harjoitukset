<?php
$pdf = '
// You can put your HTML code here
< h1 > Lorem Ipsum... </ h1 >
< h2 > Lorem Ipsum... </ h2 >
< h3 > Lorem Ipsum... </ h3 >
< h4 > Lorem Ipsum... </ h4 >
';

require('tcpdf/tcpdf.php');
$tcpdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set default monospaced font
$tcpdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set title of pdf
$tcpdf->SetTitle('Bill Collection Letter');

// set margins
$tcpdf->SetMargins(10, 10, 10, 10);
$tcpdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$tcpdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set header and footer in pdf
$tcpdf->setPrintHeader(false);
$tcpdf->setPrintFooter(false);
$tcpdf->setListIndentWidth(3);

// set auto page breaks
$tcpdf->SetAutoPageBreak(TRUE, 11);

// set image scale factor
$tcpdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

$tcpdf->AddPage();

$tcpdf->SetFont('times', '', 10.5);

$tcpdf->writeHTML($pdf, true, false, false, false, '');

//Close and output PDF document
$tcpdf->Output('demo.pdf', 'I');
?>
