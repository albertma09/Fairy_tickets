<?php

namespace App\Http\Controllers;

use DateTime;
use Dompdf\Dompdf;
use App\Models\Ticket;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class GeneratorPDF extends Controller
{


    public function generatePDF($session_id, $email)
    {
        $tickets = Ticket::getTicketsInformation($session_id, $email);
        $html = '';

        foreach ($tickets as $ticket) {
            $codigoQR = base64_encode(QrCode::format('svg')->size(200)->errorCorrection('H')->generate($ticket->id));
            $date = $ticket->date; 
            $dateObj = new DateTime($date);

            
            $day = $dateObj->format('d'); 
            $month = $dateObj->format('m'); 
            $year = $dateObj->format('Y'); 
            $html .= view('components.ticket-pdf', ['codigoQR' => $codigoQR, 'ticket' => $ticket, 'day'=>$day, 'month'=>$month, 'year'=>$year])->render();
        }

        $pdf = Pdf::loadHtml($html);
        


        return $pdf->stream('ticket.pdf');
    }

    public function sendPdfEmail(){

        $data = Ticket::getEventInformation();
        $event = $data[0];
        $session_id = $event->id;

        Mail::send('components.email-pdf', ['session_id'=> 234, 'email'=>'avis.beahan@example.org', 'event'=>$event, 'session_id'=>$session_id ], function ($message) {
            $message->to('recipient@example.com')
                ->subject('Correo de prueba para descargar tickets');
        });

        return view('sessions.mostrar');

    }
}
