<?php

namespace App\Http\Controllers;

use SimpleSoftwareIO\QrCode\Facades\QrCode;
class GeneratorPDF extends Controller
{
    

public function generatePDF()
{
    $codigoQR = QrCode::generate('prueba');
    return view('components.ticket-pdf',['codigoQR' => $codigoQR]);
}

}
