<?php

namespace App\Http\Controllers;

use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;
use Dompdf\Dompdf;
class GeneratorPDF extends Controller
{
    

public function generatePDF()
{


    $codigoQR = base64_encode(QrCode::format('svg')->size(200)->errorCorrection('H')->generate('string'));


    $numeroPaginas = 5;

    $html = '';

    for ($i = 0; $i < $numeroPaginas; $i++) {
        $html .= view('components.ticket-pdf', ['codigoQR' => $codigoQR])->render();
    }


    $pdf = Pdf::loadHtml($html);


     return $pdf->stream('ticket.pdf');
}

}
