<?php

namespace App\Http\Controllers;

use DateTime;
use Dompdf\Dompdf;
use App\Models\Ticket;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class GeneratorPDF extends Controller
{


    public function generatePDF()
    {
        $tickets = Ticket::getTicketsInformation();





        $html = '';

        foreach ($tickets as $ticket) {
            $codigoQR = base64_encode(QrCode::format('svg')->size(200)->errorCorrection('H')->generate($ticket->id));
            $date = $ticket->date; // Suponiendo que 'date' es el nombre de la columna que contiene la fecha

            // Convertir la fecha a un objeto DateTime para poder manipularla
            $dateObj = new DateTime($date);

            // Obtener el día, el mes y el año por separado
            $day = $dateObj->format('d'); // 'd' devuelve el día del mes con ceros iniciales (01 a 31)
            $month = $dateObj->format('m'); // 'm' devuelve el número del mes (01 para enero, 02 para febrero, etc.)
            $year = $dateObj->format('Y'); // 'Y' devuelve el año en formato de 4 dígitos (ej. 2024)
            $html .= view('components.ticket-pdf', ['codigoQR' => $codigoQR, 'ticket' => $ticket, 'day'=>$day, 'month'=>$month, 'year'=>$year])->render();
        }






        $pdf = Pdf::loadHtml($html);


        return $pdf->stream('ticket.pdf');
    }
}
